<?php

// Météo de Matane (Open-Meteo) et marées (Pêches et Océans Canada)
// doc Open-Meteo : https://open-meteo.com/en/docs
// doc API marées : https://api-iwls.dfo-mpo.gc.ca/swagger-ui/index.html

define('EFC_METEO_LAT', 48.8486);
define('EFC_METEO_LON', -67.5333);
define('EFC_METEO_FUSEAU', 'America/Toronto');
define('EFC_MAREE_STATION', '5cebf1e43d0f4a073c4bc364'); // station Matane : https://tides.gc.ca/fr/stations/02955

// Retourne toutes les données météo (gardées en cache 30 min)
// doc transients : https://developer.wordpress.org/apis/transients/
function efc_get_meteo()
{
    $meteo = get_transient('efc_meteo');
    if ($meteo !== false) {
        return $meteo;
    }

    $meteo = efc_fetch_meteo();
    if (!$meteo) {
        return null;
    }

    $meteo['maree'] = efc_fetch_maree();
    $meteo['mis_a_jour'] = time();

    set_transient('efc_meteo', $meteo, 30 * MINUTE_IN_SECONDS);
    return $meteo;
}

// Appel à l'API et décodage du JSON
// doc : https://developer.wordpress.org/reference/functions/wp_remote_get/
// doc : https://www.php.net/manual/fr/function.json-decode.php
function efc_api_json($url)
{
    $reponse = wp_remote_get($url, ['timeout' => 10]);

    if (is_wp_error($reponse) || wp_remote_retrieve_response_code($reponse) !== 200) {
        return null;
    }

    return json_decode(wp_remote_retrieve_body($reponse), true);
}

// Météo
// liste des variables (current, hourly, daily) : https://open-meteo.com/en/docs
function efc_fetch_meteo()
{
    // doc add_query_arg : https://developer.wordpress.org/reference/functions/add_query_arg/
    $url = add_query_arg([
        'latitude'       => EFC_METEO_LAT,
        'longitude'      => EFC_METEO_LON,
        'current'        => 'temperature_2m,apparent_temperature,relative_humidity_2m,dew_point_2m,weather_code,wind_speed_10m,wind_gusts_10m,wind_direction_10m,is_day',
        'hourly'         => 'temperature_2m,precipitation_probability,weather_code,is_day',
        'daily'          => 'temperature_2m_max,temperature_2m_min,sunrise,sunset',
        'timezone'       => EFC_METEO_FUSEAU,
        'timeformat'     => 'unixtime',
        'forecast_hours' => 6,
        'forecast_days'  => 1,
    ], 'https://api.open-meteo.com/v1/forecast');

    $data = efc_api_json($url);
    if (!$data || empty($data['current'])) {
        return null;
    }

    $actuel = $data['current'];
    $heures = $data['hourly'];
    $jour   = $data['daily'];

    // prochaines heures (la 1re = maintenant)
    $prochaines = [];
    foreach ($heures['time'] as $i => $temps) {
        $prochaines[] = [
            'heure' => $temps,
            'temp'  => round($heures['temperature_2m'][$i]),
            'pluie' => $heures['precipitation_probability'][$i] ?? 0,
            'code'  => $heures['weather_code'][$i],
            'jour'  => $heures['is_day'][$i],
        ];
    }

    return [
        'temp'        => round($actuel['temperature_2m']),
        'ressenti'    => round($actuel['apparent_temperature']),
        'code'        => $actuel['weather_code'],
        'jour'        => $actuel['is_day'],
        'max'         => round($jour['temperature_2m_max'][0]),
        'min'         => round($jour['temperature_2m_min'][0]),
        'vent'        => round($actuel['wind_speed_10m']),
        'rafales'     => round($actuel['wind_gusts_10m']),
        'direction'   => efc_meteo_direction($actuel['wind_direction_10m']),
        'humidite'    => $actuel['relative_humidity_2m'],
        'rosee'       => round($actuel['dew_point_2m']),
        'lever'       => $jour['sunrise'][0],
        'coucher'     => $jour['sunset'][0],
        'prochaines'  => $prochaines,
    ];
}

// Marées montante ou descendante et prochaine pleine/basse mer
// wlp-hilo = prédictions des pleines et basses mers : https://api-iwls.dfo-mpo.gc.ca/swagger-ui/index.html
function efc_fetch_maree()
{
    // doc gmdate : https://www.php.net/manual/fr/function.gmdate.php
    $url = add_query_arg([
        'time-series-code' => 'wlp-hilo',
        'from'             => gmdate('Y-m-d\TH:i:s\Z', time() - 6 * HOUR_IN_SECONDS),
        'to'               => gmdate('Y-m-d\TH:i:s\Z', time() + 30 * HOUR_IN_SECONDS),
    ], 'https://api-iwls.dfo-mpo.gc.ca/api/v1/stations/' . EFC_MAREE_STATION . '/data');

    $evenements = efc_api_json($url);
    if (!$evenements) {
        return null;
    }

    // 2 prochains événements
    $futurs = [];
    foreach ($evenements as $evenement) {
        // doc strtotime : https://www.php.net/manual/fr/function.strtotime.php
        $temps = strtotime($evenement['eventDate']);
        if ($temps > time()) {
            $futurs[] = ['heure' => $temps, 'niveau' => $evenement['value']];
        }
    }
    if (count($futurs) < 2) {
        return null;
    }

    // si le prochain est plus haut que le suivant, c'est une pleine mer donc ça monte
    $montante = $futurs[0]['niveau'] > $futurs[1]['niveau'];

    return [
        'montante' => $montante,
        'heure'    => $futurs[0]['heure'],
    ];
}

// Degrés, nom de la direction du vent
function efc_meteo_direction($degres)
{
    $directions = ['Nord', 'Nord-est', 'Est', 'Sud-est', 'Sud', 'Sud-ouest', 'Ouest', 'Nord-ouest'];
    return $directions[round($degres / 45) % 8];
}

// Heure au format « 7 h 12 »
// doc wp_date : https://developer.wordpress.org/reference/functions/wp_date/
// lettres du format (G, i...) : https://www.php.net/manual/fr/datetime.format.php
function efc_meteo_heure($temps)
{
    return wp_date('G \h i', $temps, new DateTimeZone(EFC_METEO_FUSEAU));
}

// icône perso
// codes : https://open-meteo.com/en/docs (section « WMO Weather interpretation codes »)
// doc get_template_directory_uri : https://developer.wordpress.org/reference/functions/get_template_directory_uri/
function efc_meteo_icone($code, $jour = 1)
{
    if ($code == 0) {
        $icone = $jour ? 'soleil.svg' : 'lune.svg';
    } elseif ($code <= 2) {
        $icone = $jour ? 'partiellement-nuageux.svg' : 'nuageux.svg';
    } elseif ($code == 3) {
        $icone = 'nuageux.svg';
    } elseif ($code <= 48) {
        $icone = 'brouillard.svg';
    } elseif ($code <= 67 || ($code >= 80 && $code <= 82)) {
        $icone = 'pluie.svg';
    } elseif ($code <= 77 || $code == 85 || $code == 86) {
        $icone = 'neige.svg';
    } else {
        $icone = 'orage.svg';
    }

    return get_template_directory_uri() . '/assets/objets/icons/' . $icone;
}

// texte (pour le alt des icônes)
// mêmes codes que plus haut : https://open-meteo.com/en/docs
function efc_meteo_description($code)
{
    if ($code == 0) return 'Ciel dégagé';
    if ($code <= 2) return 'Partiellement nuageux';
    if ($code == 3) return 'Nuageux';
    if ($code <= 48) return 'Brouillard';
    if ($code <= 67 || ($code >= 80 && $code <= 82)) return 'Pluie';
    if ($code <= 77 || $code == 85 || $code == 86) return 'Neige';
    return 'Orage';
}
