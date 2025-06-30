<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kepalapusat.dashboard') ? 'active' : '' }}" href="{{ route('kepalapusat.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kepalapusat.pegawai.*') ? 'active' : '' }}" href="{{ route('kepalapusat.pegawai.index') }}">
            <i class="fas fa-users"></i> Data Pegawai
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kepalapusat.mutasi.*') ? 'active' : '' }}" href="{{ route('kepalapusat.mutasi.index') }}">
            <i class="fas fa-file-alt"></i> Permohonan Mutasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kepalapusat.penilaian.*') ? 'active' : '' }}" href="{{ route('kepalapusat.penilaian.index') }}">
            <i class="fas fa-chart-bar"></i> Data Penilaian
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kepalapusat.hasil-akhir.*') ? 'active' : '' }}" href="{{ route('kepalapusat.hasil-akhir.index') }}">
            <i class="fas fa-trophy"></i> Hasil Akhir
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('kepalapusat.laporan.*') ? 'active' : '' }}" href="{{ route('kepalapusat.laporan.index') }}">
            <i class="fas fa-file-pdf"></i> Laporan
        </a>
    </li>
</ul> 