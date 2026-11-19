document.addEventListener('DOMContentLoaded', function () {
  const filters = document.querySelectorAll('[data-programme-day]');
  const cards = document.querySelectorAll('.programme-card');
  const emptyState = document.querySelector('.programme-empty-state');

  if (!filters.length || !cards.length || !emptyState) {
    return;
  }

  const filterByDay = function (day) {
    let visibleCards = 0;

    cards.forEach(function (card) {
      const days = (card.dataset.programmeDays || '')
        .toLowerCase()
        .split(/\s+/)
        .filter(Boolean);
      const isVisible = days.includes(day.toLowerCase()) || days.includes('all');
      card.hidden = !isVisible;
      card.style.display = isVisible ? '' : 'none';
      visibleCards += isVisible ? 1 : 0;
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
