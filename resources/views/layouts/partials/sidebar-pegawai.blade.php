<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}" href="{{ route('pegawai.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pegawai.profil.*') ? 'active' : '' }}" href="{{ route('pegawai.profil.index') }}">
            <i class="fas fa-user"></i> Profil Saya
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pegawai.mutasi.*') ? 'active' : '' }}" href="{{ route('pegawai.mutasi.index') }}">
            <i class="fas fa-file-alt"></i> Permohonan Mutasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pegawai.penilaian.*') ? 'active' : '' }}" href="{{ route('pegawai.penilaian.index') }}">
            <i class="fas fa-chart-bar"></i> Data Penilaian
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pegawai.hasil-akhir.*') ? 'active' : '' }}" href="{{ route('pegawai.hasil-akhir.index') }}">
            <i class="fas fa-trophy"></i> Hasil Akhir
        </a>
    </li>
</ul> 