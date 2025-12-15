<!-- User Sidebar Blade (converted from user_sidebar.php) -->

<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>

<!-- Toggle Button for Mobile -->
<button class="sidebar-toggle" id="sidebarToggle">
  <i class="fas fa-bars"></i>
</button>

<!-- Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="uc-sidebar" id="ucSidebar">
  <div class="uc-header">
    <h2><i class="fas fa-hotel"></i> CI</h2>
  </div>

  <ul class="uc-menu">

    <li>
      <a href="{{ route('dashboard') }}"
         class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li>
      <a href="{{ route('rooms.browse') }}"
         class="{{ request()->routeIs('rooms.browse') ? 'active' : '' }}">
        <i class="fas fa-door-open"></i>
        <span>Browse</span>
      </a>
    </li>

    <li>
      <a href="{{ route('bookings.index') }}"
         class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i>
        <span>Bookings</span>
      </a>
    </li>

    <li>
      <a href="{{ route('reviews.index') }}"
         class="{{ request()->routeIs('reviews.index') ? 'active' : '' }}">
        <i class="fas fa-star"></i>
        <span>Reviews</span>
      </a>
    </li>

    <li>
      <a href="{{ route('settings.index') }}"
         class="{{ request()->routeIs('settings.index') ? 'active' : '' }}">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
      </a>
    </li>

    <!-- Logout -->
    <li class="logout">
      <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </li>
  </ul>
</aside>

<style>
/* --- All your CSS preserved exactly --- */
  .uc-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 90px;
    background: linear-gradient(180deg, #dc3545 0%, #b71c1c 100%);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 15px;
    box-shadow: 3px 0 8px rgba(0, 0, 0, 0.15);
    z-index: 3000;
    transition: transform 0.3s ease-in-out;
  }

  .uc-header {
    width: 100%;
    text-align: center;
    margin-bottom: 15px;
  }

  .uc-header h2 {
    font-size: 1.1rem;
    color: #fff;
    font-weight: 700;
    margin: 0;
  }

  .uc-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }

  .uc-menu li {
    width: 100%;
  }

  .uc-menu a {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.9);
    padding: 10px 0;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
    transition: background 0.3s ease, color 0.3s ease;
  }

  .uc-menu a i {
    font-size: 1.2rem;
    margin-bottom: 3px;
  }

  .uc-menu a:hover,
  .uc-menu a.active {
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
  }

  .uc-menu .logout {
    margin-top: auto;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
  }

  main,
  .content-wrapper,
  .container,
  .main-content {
    margin-left: 70px;
    transition: margin-left 0.3s ease;
  }

  .sidebar-toggle {
    display: none;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 4000;
    background: #dc3545;
    border: none;
    color: #fff;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.2rem;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 2999;
    backdrop-filter: blur(2px);
  }

  .sidebar-overlay.active {
    display: block;
  }

  @media (max-width: 768px) {
    .uc-sidebar {
      transform: translateX(-100%);
    }
    .uc-sidebar.active {
      transform: translateX(0);
    }
    .sidebar-toggle {
      display: block;
    }
    main,
    .content-wrapper,
    .container,
    .main-content {
      margin-left: 0 !important;
    }
  }

  /* Smooth transitions */
  .uc-sidebar,
  .sidebar-toggle,
  .sidebar-overlay,
  main,
  .content-wrapper,
  .container,
  .main-content {
    transition: all 0.3s ease;
  }
</style>

<script>
/* --- Your sidebar JS preserved exactly --- */
(function(){
  const sidebarId = 'ucSidebar';
  const overlayId = 'sidebarOverlay';
  const toggleSelector = '.sidebar-toggle, #sidebarToggle';

  function getSidebar(){ return document.getElementById(sidebarId); }
  function getOverlay(){ return document.getElementById(overlayId); }

  function setHamburgerVisibility(show){
    document.querySelectorAll('.sidebar-toggle').forEach(btn=>{
      btn.style.display = show ? '' : 'none';
    });
  }

  function openSidebar(){
    const s = getSidebar(), o = getOverlay();
    s.classList.add('active'); 
    o.classList.add('active');
    document.body.style.overflow = 'hidden';
    setHamburgerVisibility(false);
  }

  function closeSidebar(){
    const s = getSidebar(), o = getOverlay();
    s.classList.remove('active');
    o.classList.remove('active');
    document.body.style.overflow = '';
    if (window.innerWidth <= 768) setHamburgerVisibility(true);
  }

  document.addEventListener('click', function(e){
    const toggle = e.target.closest(toggleSelector);
    if(toggle){
      const s = getSidebar();
      if (s.classList.contains('active')) closeSidebar(); else openSidebar();
      return;
    }
    if (e.target.closest('#' + overlayId)){
      closeSidebar();
    }
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') closeSidebar();
  });

  window.addEventListener('resize', function(){
    const s = getSidebar();
    if (!s) return;
    if (window.innerWidth > 768){
      setHamburgerVisibility(false);
      s.classList.remove('active');
      getOverlay()?.classList.remove('active');
      document.body.style.overflow = '';
    } else {
      if (!s.classList.contains('active')) setHamburgerVisibility(true);
    }
  });

})();
</script>
