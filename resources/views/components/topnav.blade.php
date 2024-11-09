<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container-fluid">
      <a href="/" class="navbar-brand">
        <img src="{{ asset('assets/icon/resource.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">HRIS Sistem</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <ul class="navbar-nav">

          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Dashboard</a>
          </li>

          @if (auth()->user()->hasRole('admin'))

            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Master Data</a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">

                <li><a href="{{ route('clients.index') }}" class="dropdown-item {{ request()->routeIs('clients.index') ? 'active' : '' }}">Client</a></li>

                <li><a href="{{ route('contracts.index') }}" class="dropdown-item {{ request()->routeIs('contracts.index') ? 'active' : '' }}">Contract</a></li>

                <li><a href="{{ route('departements.index') }}" class="dropdown-item {{ request()->routeIs('departements.index') ? 'active' : '' }}">Departement</a></li>

                <li><a href="{{ route('posisions.index') }}" class="dropdown-item {{ request()->routeIs('posisions.index') ? 'active' : '' }}">Posision</a></li>

                <li><a href="{{ route('shifts.index') }}" class="dropdown-item {{ request()->routeIs('shifts.index') ? 'active' : '' }}">Shift</a></li>

                

              </ul>
            </li>

          @endif

          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Personnel</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">

              @if (auth()->user()->hasRole('admin'))
              
                <li><a href="{{ route('employee.index') }}" class="dropdown-item {{ request()->routeIs('employee.index') ? 'active' : '' }}">Data</a></li>

                <li><a href="#" class="dropdown-item">Salary Calculation</a></li>

              @endif
              

              <li><a href="{{ route('overtimes.index') }}" class="dropdown-item {{ request()->routeIs('overtimes.index') ? 'active' : '' }}">Overtimes</a></li>

              <li><a href="{{ route('presences.index') }}" class="dropdown-item {{ request()->routeIs('presences.index') ? 'active' : '' }}">Presences</a></li>

              <li><a href="{{ route('vacations.index') }}" class="dropdown-item  {{ request()->routeIs('vacations.index') ? 'active' : '' }}">Vacation</a></li>


            </ul>
          </li>

          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Jadwal</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">

              <li><a href="{{ route('schedules.index') }}" class="dropdown-item">Data</a></li>

            </ul>
          </li>



        </ul>

       
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            Hallo, {{  auth()->user()->name  }}
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right text-center">
            <span class="dropdown-header">Anda Login Sebagai : <strong>{{ auth()->user()->roles->pluck('name')[0] }}</strong></span>
            <span class="dropdown-header">{{  auth()->user()->email  }}</span>

            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              Ubah Password
            </a>
            <div class="dropdown-divider"></div>
            <a href="#"  id="logout" class="dropdown-item">
              Keluar
            </a>

          </div>
        </li>
       
      </ul>
    </div>
  </nav>