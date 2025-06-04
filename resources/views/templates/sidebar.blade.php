<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logoyayat1.png') }}" alt="logo">
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="{{route('dashboard')}}" style="text-decoration: none;"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                    </li>
                    <li>
                        <a href="/pengeluaran" style="text-decoration: none;"><i class="ti-money"></i><span>Pengeluaran</span></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true" style="text-decoration: none;"><i class="ti-layout-sidebar-left"></i><span>Input nota</span></a>
                        <ul class="collapse">
                            <li><a href="/input" style="text-decoration: none;">input</a></li>
                        </ul>
                    </li>

                    <!-- MASTER DATA Section -->
                    @if(auth()->check() && auth()->user()->role->name == 'admin')
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true" style="text-decoration: none;">
                            <i class="ti-layout-sidebar-left"></i><span>MASTER DATA</span>
                        </a>
                        <ul class="collapse">
                            {{-- <li><a style="border-bottom: none; text-decoration: none;" href="/data-user">Lihat Data User</a></li> --}}
                            <li><a style="border-bottom: none; text-decoration: none;" href="{{ route('barangs.index') }}">Data Barang</a></li>
                            <li><a style="border-bottom: none; text-decoration: none;" href="{{ route('customers.index') }}">Data Customer</a></li>
                        </ul>
                    </li>

                    @endif
                    <li>
                        <a href="/hutangs" style="text-decoration: none;"><i class="ti-dashboard"></i><span style="font-size: 14px; font-weight: 600;">HUTANG</span></a>
                    </li>
                    <!-- LAPORAN Section -->
                    @if(auth()->check() && auth()->user()->role->name == 'admin')
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true" style="text-decoration: none;"><i class="ti-file"></i><span>LAPORAN</span></a>
                        <ul class="collapse">
                            <li><a style="border-bottom: none; text-decoration: none;" href="/laporan/data_hutang">Lihat Laporan Hutang</a></li>
                            <li><a style="border-bottom: none; text-decoration: none;" href="/laporan/pemasukan">Lihat Laporan Pemasukan</a></li>
                            <li><a style="border-bottom: none; text-decoration: none;" href="/laporan/pengeluaran">Lihat Laporan Pengeluaran</a></li>
                        </ul>
                    </li>
                    
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
.sidebar-menu {
    background: #f8fafc;
    min-height: 100vh;
    box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.06);
    width: 240px;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
    overflow-y: auto;
    transition: width 0.2s;
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    border-right: 1px solid #e5e7eb;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f8fafc;
}
.sidebar-menu::-webkit-scrollbar {
    width: 8px;
}
.sidebar-menu::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 8px;
}
.sidebar-header {
    padding: 2rem 1rem 1rem 1rem;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
    background: #fff;
}
.sidebar-header .logo img {
    max-width: 150px;
    height: auto;
    display: block;
    margin: 0 auto;
}
.menu-inner {
    padding: 1.5rem 0.5rem 2rem 0.5rem;
}
.metismenu {
    list-style: none;
    padding: 0;
    margin: 0;
}
.metismenu > li {
    margin-bottom: 0.5rem;
}
.metismenu > li > a {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1.5rem 0.85rem 1.25rem;
    border-radius: 0.6rem 0 0 0.6rem;
    color: #334155;
    font-weight: 500;
    font-size: 1.05rem;
    background: none;
    transition: background 0.2s, color 0.2s, border-left 0.2s;
    text-decoration: none !important;
    border-left: 4px solid transparent;
    letter-spacing: 0.01em;
}
.metismenu > li > a:hover, .metismenu > li > a:focus {
    background: #e0e7ef;
    color: #2563eb;
    border-left: 4px solid #2563eb;
}
.metismenu > li.active > a, .metismenu > li > a.active {
    background: #dbeafe;
    color: #1d4ed8;
    font-weight: 700;
    border-left: 4px solid #1d4ed8;
}
.metismenu i {
    font-size: 1.1rem;
    min-width: 22px;
    text-align: center;
    color: #64748b;
    transition: color 0.2s;
}
.metismenu > li > a:hover i,
.metismenu > li.active > a i,
.metismenu > li > a.active i {
    color: #2563eb;
}
.metismenu .collapse {
    padding-left: 1.5rem;
    margin-top: 0.25rem;
    background: #f1f5f9;
    border-radius: 0.5rem;
}
.metismenu .collapse li a {
    font-size: 0.97rem;
    color: #64748b;
    padding: 0.55rem 1.25rem;
    border-radius: 0.4rem;
    display: block;
    margin-bottom: 0.2rem;
    font-weight: 500;
    background: none;
    transition: background 0.2s, color 0.2s;
}
.metismenu .collapse li a:hover, .metismenu .collapse li a:focus {
    background: #e0e7ef;
    color: #2563eb;
}
.metismenu .collapse li a.active {
    background: #dbeafe;
    color: #1d4ed8;
    font-weight: 600;
}
@media (max-width: 991px) {
    .sidebar-menu {
        width: 100vw;
        min-height: auto;
        position: relative;
        box-shadow: none;
    }
}
</style>
