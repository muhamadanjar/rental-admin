<li class="nav-header">Menu Aplikasi</li>
<li class="nav-item">
    <a href="{{ route('backend.dashboard.index')}}" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Dashboard</p>
    </a>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Promo
        <i class="fas fa-angle-left right"></i>
    </p>
    
    </a>
    <ul class="nav nav-treeview">
        
        <li class="nav-item">
            <a href="{{ route('backend.promo.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>List Promo</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('backend.promo.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Tambah Promo</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="{{ route('backend.dashboard.index')}}" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Regular</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('backend.promo.index')}}" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Banner Home</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('backend.reviews.index')}}" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Reviews</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('backend.reqsaldo.index')}}" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Request Saldo</p>
    </a>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Manajemen User
        <i class="fas fa-angle-left right"></i>
    </p>
    
    </a>
    <ul class="nav nav-treeview">
        
        <li class="nav-item">
        <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Driver</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Customer</p>
                </a>
            </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Pemesanan
        <i class="fas fa-angle-left right"></i>
    </p>
    
    </a>
    <ul class="nav nav-treeview">
        
        <li class="nav-item">
        <a href="{{ route('backend.trip_job.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Trip</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
    <i class="fas fa-circle nav-icon"></i>
    <p>Pengaturan
        <i class="fas fa-angle-left right"></i>
    </p>
    
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Umum</p>
                </a>
            </li>
        <li class="nav-item">
        <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>User</p>
            </a>
        </li>
    </ul>
</li>