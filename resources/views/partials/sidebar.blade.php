<div class="container-scroller" style="position: relative;"> <!-- Añadido style para posición relativa -->
  <!-- Botón de menú hamburguesa -->
  <button id="menu-toggle" class="menu-toggle">
    &#9776; <!-- Este es el símbolo de menú hamburguesa -->
  </button>

  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      </li>
            <!-- Enlace a Home -->
            <li class="nav-item menu-items">
        <a class="nav-link" href="{{ route('home') }}">
          <span class="menu-icon">
            <i class="mdi mdi-home"></i>
          </span>
          <span class="menu-title">Home</span>
        </a>
      </li>

      <!-- Enlace al Dashboard -->
      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ route('dashboard') }}">
          <span class="menu-icon">
            <i class="mdi mdi-speedometer"></i>
          </span>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>

      <!-- Enlace a Mis Reservas -->
      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ route('reservas.index') }}">
          <span class="menu-icon">
            <i class="mdi mdi-calendar-check"></i>
          </span>
          <span class="menu-title">Mis Reservas</span>
        </a>
      </li>

      <!-- Enlace a Disponibilidad -->
      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ route('disponibilidad.index') }}">
          <span class="menu-icon">
            <i class="mdi mdi-calendar"></i>
          </span>
          <span class="menu-title">Disponibilidad</span>
        </a>
      </li>

      <!-- Enlace a Perfil -->
      <li class="nav-item menu-items">
        <a class="nav-link" href="{{ route('profile') }}">
          <span class="menu-icon">
            <i class="mdi mdi-account-circle"></i>
          </span>
          <span class="menu-title">Perfil</span>
        </a>
      </li>
    </ul>
  </nav>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggler = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    toggler.addEventListener('click', function () {
      sidebar.classList.toggle('active'); // Alternar la clase active
    });
  });
</script>


