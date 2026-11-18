document.addEventListener('DOMContentLoaded', () => {
  const root = document.documentElement;
  const themeBtn = document.getElementById('theme-btn');
  const savedTheme = localStorage.getItem('theme') || 'light';

  root.dataset.theme = savedTheme;
  if (themeBtn) {
    themeBtn.textContent = savedTheme === 'dark' ? '☀' : '☾';
    themeBtn.addEventListener('click', () => {
      const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
      root.dataset.theme = nextTheme;
      localStorage.setItem('theme', nextTheme);
      themeBtn.textContent = nextTheme === 'dark' ? '☀' : '☾';
    });
  }

  const menuBtn = document.getElementById('menu-btn');
  const nav = document.getElementById('nav');
  if (menuBtn && nav) {
    menuBtn.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      menuBtn.setAttribute('aria-expanded', String(open));
    });
    nav.querySelectorAll('a').forEach(link =>
      link.addEventListener('click', () => nav.classList.remove('open'))
    );
  }

  const links = document.querySelectorAll('nav a[href^="#"]');
  const sections = document.querySelectorAll('main section[id]');
  if ('IntersectionObserver' in window && links.length && sections.length) {
    const spy = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          links.forEach(link =>
            link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`)
          );
        }
      });
    }, { rootMargin: '-40% 0px -55% 0px' });
    sections.forEach(section => spy.observe(section));
  }

  const topBtn = document.getElementById('top');
  if (topBtn) {
    window.addEventListener('scroll', () => {
      topBtn.classList.toggle('show', window.scrollY > 400);
    });
    topBtn.addEventListener('click', () =>
      window.scrollTo({ top: 0, behavior: 'smooth' })
    );
  }

  const typed = document.getElementById('typed');
  if (typed) {
    const words = ['Information Technology'];
    let wordIndex = 0;
    let charIndex = 0;
    let deleting = false;
    const tick = () => {
      const word = words[wordIndex];
      typed.textContent = word.slice(0, charIndex);
      if (!deleting && charIndex === word.length) {
        deleting = true;
        return setTimeout(tick, 1400);
      }
      if (deleting && charIndex === 0) {
        deleting = false;
        wordIndex = (wordIndex + 1) % words.length;
      }
      charIndex += deleting ? -1 : 1;
      setTimeout(tick, deleting ? 50 : 110);
    };
    tick();
  }

  const fills = document.querySelectorAll('.fill');
  if ('IntersectionObserver' in window && fills.length) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.width = `${entry.target.dataset.level}%`;
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    fills.forEach(fill => observer.observe(fill));
  }

  document.querySelectorAll('.filters button').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('.filters button').forEach(item => item.classList.remove('on'));
      button.classList.add('on');
      document.querySelectorAll('.project').forEach(project => {
        project.classList.toggle('hide', button.dataset.filter !== 'all' && project.dataset.type !== button.dataset.filter);
      });
    });
  });

  const modal = document.getElementById('modal');
  if (modal) {
    const largeImage = modal.querySelector('img');
    if (largeImage) {
      document.querySelectorAll('.project img').forEach(image => {
        image.addEventListener('click', () => {
          largeImage.src = image.src;
          largeImage.alt = image.alt;
          modal.classList.add('open');
        });
      });
      modal.addEventListener('click', () => modal.classList.remove('open'));
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape') modal.classList.remove('open');
      });
    }
  }

  document.querySelectorAll('.acc-head').forEach(head => {
    head.addEventListener('click', () => {
      const body = head.nextElementSibling;
      if (!body) return;
      const open = head.getAttribute('aria-expanded') === 'true';
      head.setAttribute('aria-expanded', String(!open));
      body.classList.toggle('open', !open);
      body.style.maxHeight = open ? '0' : `${body.scrollHeight}px`;
    });
  });
  document.querySelector('.acc-head')?.click();

  // Contact forms use native browser validation and submit to the PHP handler.
  // Server-side validation and CSRF verification are performed by contact.php.
});
