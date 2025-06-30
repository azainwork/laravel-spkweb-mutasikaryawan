<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" href="{{ route('admin.pegawai.index') }}">
            <i class="fas fa-users"></i> Data Pegawai
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" href="{{ route('admin.pegawai.index') }}">
            <i class="fas fa-file-alt"></i> Permohonan Mutasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" href="{{ route('admin.pegawai.index') }}">
            <i class="fas fa-cogs"></i> Data Kriteria
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" href="{{ route('admin.pegawai.index') }}">
            <i class="fas fa-chart-bar"></i> Data Penilaian
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" href="{{ route('admin.pegawai.index') }}">
            <i class="fas fa-calculator"></i> Perhitungan Oreste
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}" href="{{ route('admin.pegawai.index') }}">
            <i class="fas fa-trophy"></i> Hasil Akhir
        </a>
    </li>
</ul>
