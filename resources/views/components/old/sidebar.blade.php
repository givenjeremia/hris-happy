<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        {{-- <img src="{{ asset('brand/logo/titik_koma.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8"> --}}
        <span class="brand-text font-weight-light">HRIS SISTEM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{  auth()->user()->name  }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
               <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Client
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Contract
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('departements.index') }}" class="nav-link {{ request()->routeIs('departements.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Departement
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('posisions.index') }}" class="nav-link {{ request()->routeIs('posisions.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Posision
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('employee.index') }}" class="nav-link {{ request()->routeIs('employee.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Employee
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('overtimes.index') }}" class="nav-link {{ request()->routeIs('overtimes.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Overtimes
                        </p>
                    </a>
                </li>


               

                <li class="nav-item">
                    <a href="{{ route('presences.index') }}" class="nav-link {{ request()->routeIs('presences.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Presences
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('vacations.index') }}" class="nav-link {{ request()->routeIs('vacations.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Vacation
                        </p>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</aside>
