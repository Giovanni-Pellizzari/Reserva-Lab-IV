<nav class="navbar p-0 fixed-top d-flex flex-row w-100">
  <div class="navbar-menu-wrapper flex-grow d-flex align-items-center">
    <a href="{{ url('home') }}">
      <img src="{{ asset('assets/images/turnos3.jpg') }}" alt="logo" style="width: 150px; height: auto;" />
    </a>

    <ul class="navbar-nav navbar-nav-right">

      <!-- Notificaciones -->
      <li class="nav-item dropdown border-left">
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
          <h6 class="p-3 mb-0">Notificaciones</h6>
          <div class="dropdown-divider"></div>
          <p class="p-3 mb-0 text-center">No tienes nuevas notificaciones</p>
        </div>
      </li>

      <!-- Perfil del Usuario -->
      @auth
      <li class="nav-item dropdown">
        <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
          <div class="navbar-profile">
            <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/icono-usuario.png') }}" alt="Imagen de perfil">
            <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ Auth::user()->name }}</p>
            <i class="mdi mdi-menu-down d-none d-sm-block"></i>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
          <a class="dropdown-item preview-item" href="{{ route('profile') }}">
            <div class="preview-item-content">
              <p class="preview-subject mb-1">Perfil</p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item" href="{{ route('logout') }}" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="preview-item-content">
              <p class="preview-subject mb-1">Cerrar sesión</p>
            </div>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
      @endauth

      @guest
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
      </li>
      @endguest

    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-format-line-spacing"></span>
    </button>
  </div>
</nav>
