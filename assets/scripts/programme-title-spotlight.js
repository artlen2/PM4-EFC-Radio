document.querySelectorAll('.programme-title').forEach((title) => {
  let animationFrame = 0;
  let pointerX = 0;
  let pointerY = 0;

  title.addEventListener('pointermove', (event) => {
    if (event.pointerType === 'touch') {
      return;
    }

    const bounds = title.getBoundingClientRect();
    pointerX = event.clientX - bounds.left;
    pointerY = event.clientY - bounds.top;

    if (!animationFrame) {
      animationFrame = window.requestAnimationFrame(() => {
        title.style.setProperty('--spot-x', `${pointerX}px`);
        title.style.setProperty('--spot-y', `${pointerY}px`);
        animationFrame = 0;
      });
    }
  });

  title.addEventListener('pointerleave', () => {
    window.cancelAnimationFrame(animationFrame);
    animationFrame = 0;
    title.style.setProperty('--spot-x', '-20rem');
    title.style.setProperty('--spot-y', '-20rem');
  });
});

document.querySelectorAll('.programme-character').forEach((character) => {
  let dragOffsetX = 0;
  let dragOffsetY = 0;

  character.addEventListener('pointerdown', (event) => {
    if (event.button !== 0 && event.pointerType === 'mouse') {
      return;
    }

    const container = character.offsetParent;
    if (!container) {
      return;
    }

    const containerBounds = container.getBoundingClientRect();
    const characterBounds = character.getBoundingClientRect();

    dragOffsetX = event.clientX - characterBounds.left;
    dragOffsetY = event.clientY - characterBounds.top;
    character.style.left = `${characterBounds.left - containerBounds.left}px`;
    character.style.top = `${characterBounds.top - containerBounds.top}px`;
    character.style.right = 'auto';
    character.style.bottom = 'auto';
    character.classList.add('is-dragging');
    character.setPointerCapture(event.pointerId);
    event.preventDefault();
  });

  character.addEventListener('pointermove', (event) => {
    if (!character.hasPointerCapture(event.pointerId)) {
      return;
    }

    const container = character.parentElement;
    if (!container) {
      return;
    }

    const containerBounds = container.getBoundingClientRect();
    const maxX = Math.max(0, container.clientWidth - character.offsetWidth);
    const maxY = Math.max(0, container.scrollHeight - character.offsetHeight);
    const x = Math.min(maxX, Math.max(0, event.clientX - containerBounds.left - dragOffsetX));
    const y = Math.min(maxY, Math.max(0, event.clientY - containerBounds.top - dragOffsetY));

    character.style.left = `${x}px`;
    character.style.top = `${y}px`;
  });

  const stopDragging = (event) => {
    if (character.hasPointerCapture(event.pointerId)) {
      character.releasePointerCapture(event.pointerId);
    }
    character.classList.remove('is-dragging');
  };

  character.addEventListener('pointerup', stopDragging);
  character.addEventListener('pointercancel', stopDragging);
});


// animation de spotlight au hover sur titre, tu le mets pis tu change la couleur comme tu veux