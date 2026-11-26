document.querySelectorAll('[data-soutenir-mascot]').forEach((button) => {
  button.addEventListener('click', () => {
    const mascot = button.querySelector('img');
    if (!mascot) {
      return;
    }

    mascot.classList.remove('soutenir-mascot-jump');
    void mascot.offsetWidth;
    mascot.classList.add('soutenir-mascot-jump');
  });
});

// animation bonhome disque