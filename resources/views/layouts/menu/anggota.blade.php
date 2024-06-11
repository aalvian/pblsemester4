<li class="nav-item @if (request()->routeIs('divisi', 'create-divisi', 'edit-divisi')) active @endif">
    <a class="nav-link" href="{{ route('divisi') }}">
        <i class="fas fa-shield-alt"></i>
        <span>Divisi</span></a>
</li>
<li class="nav-item @if (request()->routeIs('divisi', 'create-divisi', 'edit-divisi')) active @endif">
    <a class="nav-link" href="{{ route ('view-presensi')}}">
        <i class="fas fa-shield-alt"></i>
        <span>Presensi</span></a>
</li>
