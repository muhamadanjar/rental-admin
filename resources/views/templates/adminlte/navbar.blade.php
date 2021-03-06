<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-cyan">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('backend.dashboard.index')}}" class="nav-link">Home</a>
          </li>
          
        </ul>
    
        <!-- SEARCH FORM -->
        <form id="form-search" class="form-inline ml-3" action="{{ route('backend.search')}}">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>
    
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Messages Dropdown Menu -->
          
          <!-- Notifications Dropdown Menu -->
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge notif_count">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-header menu_header">15 Notifications</span>
              <div class="menu_notifikasi">

              </div>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">Lihat Semua</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
                class="fas fa-th-large"></i></a>
          </li>
        </ul>
      </nav>
      