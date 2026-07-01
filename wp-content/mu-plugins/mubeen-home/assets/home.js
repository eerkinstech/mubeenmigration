(() => {
  const button = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-site-menu]');

  if (!button || !menu) return;

  const submenuButtons = [...menu.querySelectorAll('[data-submenu-toggle]')];
  const collapseSubmenus = (except = null) => {
    submenuButtons.forEach((submenuButton) => {
      if (submenuButton === except) return;
      submenuButton.setAttribute('aria-expanded', 'false');
      submenuButton.closest('.mm-nav-item')?.classList.remove('is-submenu-open');
    });
  };

  const closeMenu = () => {
    button.setAttribute('aria-expanded', 'false');
    menu.classList.remove('is-open');
    document.body.classList.remove('mm-menu-open');
    collapseSubmenus();
  };

  button.addEventListener('click', () => {
    const open = button.getAttribute('aria-expanded') !== 'true';
    button.setAttribute('aria-expanded', String(open));
    menu.classList.toggle('is-open', open);
    document.body.classList.toggle('mm-menu-open', open);
  });

  submenuButtons.forEach((submenuButton) => {
    submenuButton.addEventListener('click', () => {
      const open = submenuButton.getAttribute('aria-expanded') !== 'true';
      collapseSubmenus(open ? submenuButton : null);
      submenuButton.setAttribute('aria-expanded', String(open));
      submenuButton.closest('.mm-nav-item')?.classList.toggle('is-submenu-open', open);
    });
  });

  menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeMenu();
  });
})();
