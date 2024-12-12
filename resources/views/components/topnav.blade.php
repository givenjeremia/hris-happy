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
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active navbar-active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt mr-1"></i>Dashboard
            </a>
          </li>

          @if (auth()->user()->hasRole('admin'))

            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle {{ request()->is('*master-data*') ? 'active navbar-active' : '' }}"><i class="nav-icon fas fa-file mr-1"></i>Master Data</a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow p-2" style="left: 0px; right: inherit;">

                <li><a href="{{ route('clients.index') }}" class="dropdown-item {{ request()->is('*master-data/clients*') ? 'active  navbar-active' : '' }}">Client</a></li>

                <li><a href="{{ route('contracts.index') }}" class="dropdown-item {{ request()->is('*master-data/contracts*')  ? 'active navbar-active' : '' }}">Contract</a></li>

                <li><a href="{{ route('departements.index') }}" class="dropdown-item {{ request()->is('*master-data/departements*') ? 'active navbar-active' : '' }}">Departement</a></li>

                <li><a href="{{ route('posisions.index') }}" class="dropdown-item {{ request()->is('*master-data/posisions*') ? 'active navbar-active' : '' }}">Posision</a></li>

                <li><a href="{{ route('shifts.index') }}" class="dropdown-item {{ request()->is('*master-data/shifts*') ? 'active navbar-active' : '' }}">Shift</a></li>

                <li><a href="{{ route('allowance.index') }}" class="dropdown-item {{ request()->is('*master-data/allowance*') ? 'active navbar-active' : '' }}">Allowance</a></li>

                <li><a href="{{ route('bpjs.index') }}" class="dropdown-item {{ request()->is('*master-data/bpjs*') ? 'active navbar-active' : '' }}">BPJS</a></li>

                

              </ul>
            </li>

          @endif

          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle {{ request()->is('*personnel*') ? 'active navbar-active' : '' }}"><i class="nav-icon fas fa-users mr-1"></i>Personnel</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow p-2" style="left: 0px; right: inherit;">

              @if (auth()->user()->hasRole('admin'))
              
                <li><a href="{{ route('employee.index') }}" class="dropdown-item {{ request()->is('*personnel/employee*') ? 'active  navbar-active' : '' }}">Data Employee</a></li>

              @endif
              

              <li><a href="{{ route('overtimes.index') }}" class="dropdown-item {{ request()->is('*personnel/overtime*') ? 'active  navbar-active' : '' }}">Overtimes</a></li>

              <li><a href="{{ route('presences.index') }}" class="dropdown-item {{ request()->is('*personnel/presencese*') ? 'active  navbar-active' : '' }}">Presences</a></li>

              <li><a href="{{ route('vacations.index') }}" class="dropdown-item  {{ request()->is('*personnel/vacations*') ? 'active  navbar-active' : '' }}">Vacation</a></li>


            </ul>
          </li>

          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle {{ request()->is('*schedules*') ? 'active navbar-active' : '' }}"><i class="nav-icon fas fa-calendar mr-1"></i>Jadwal</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow p-2" style="left: 0px; right: inherit;">

              <li><a href="{{ route('schedules.index') }}" class="dropdown-item {{ request()->is('*schedules*') ? 'active  navbar-active' : '' }}">Data</a></li>

            </ul>
          </li>

          @if (auth()->user()->hasRole('admin'))

          <li class="nav-item">
            <a href="{{ route('income.index') }}" class="nav-link {{ request()->routeIs('income.index') ? 'active navbar-active' : '' }}">
              <i class="nav-icon fas fa-money-bill mr-1"></i>Income
            </a>
          </li>

          @endif




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
            <a href="{{ route('password.reset.form') }}" class="dropdown-item">
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