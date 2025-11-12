// basic nav toggle & active link management
document.addEventListener('DOMContentLoaded', function(){
  // mobile nav toggle
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.main-nav');
  if(toggle && nav){
    toggle.addEventListener('click', () => {
      nav.style.display = (nav.style.display === 'flex') ? 'none' : 'flex';
    });
    // close nav when clicking outside (mobile)
    document.addEventListener('click', (e) => {
      if(window.innerWidth <= 800){
        if(!e.target.closest('.header-inner')){
          nav.style.display = 'none';
        }
      }
    });
  }

  // mark active nav item based on current URL
  const links = document.querySelectorAll('.main-nav a');
  links.forEach(a => {
    if(a.href === location.href || a.href === location.pathname.split('/').pop()){
      a.classList.add('active');
    }
  });

  // Enhance UX: remove mobile nav after clicking a link
  links.forEach(a => a.addEventListener('click', () => {
    if(window.innerWidth <= 800 && nav) nav.style.display = 'none';
  }));
});
