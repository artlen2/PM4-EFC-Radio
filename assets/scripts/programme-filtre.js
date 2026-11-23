document.addEventListener('DOMContentLoaded', function () {
  const filters = document.querySelectorAll('[data-programme-day]');
  const cards = document.querySelectorAll('.programme-card');
  const emptyState = document.querySelector('.programme-empty-state');

  if (!filters.length || !cards.length || !emptyState) {
    return;
  }

  const hideTimers = new WeakMap();

  const filterByDay = function (day) {
    let visibleCards = 0;

    cards.forEach(function (card) {
      const days = (card.dataset.programmeDays || '')
        .toLowerCase()
        .split(/\s+/)
        .filter(Boolean);
      const isVisible = days.includes(day.toLowerCase()) || days.includes('all');

      if (isVisible) {
        const pendingTimer = hideTimers.get(card);
        if (pendingTimer) {
          window.clearTimeout(pendingTimer);
          hideTimers.delete(card);
        }
        card.hidden = false;
        card.style.display = '';
        card.style.opacity = '0';
        card.style.transform = 'translateY(6px)';
        requestAnimationFrame(function () {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        });
        visibleCards += 1;
      } else {
        card.style.opacity = '0';
        card.style.transform = 'translateY(6px)';
        const hideTimer = window.setTimeout(function () {
          card.hidden = true;
          card.style.display = 'none';
          hideTimers.delete(card);
        }, 180);
        hideTimers.set(card, hideTimer);
      }
    });

    filters.forEach(function (filter) {
      filter.setAttribute('aria-pressed', String(filter.dataset.programmeDay === day));
    });
    emptyState.hidden = visibleCards > 0;
    emptyState.style.display = visibleCards > 0 ? 'none' : 'block';
  };

  filters.forEach(function (filter) {
    filter.addEventListener('click', function () {
      filterByDay(filter.dataset.programmeDay);
    });
  });

  filterByDay('lundi');
});

// cache et ouvre les cartes en fonctions du jour sélectionné (le filtre)