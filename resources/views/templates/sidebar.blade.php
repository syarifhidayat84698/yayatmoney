<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/yayat.png') }}" alt="logo">
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="dashboard"><i class="ti-dashboard"></i><span>Dashboard</span></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-sidebar-left"></i><span>Transaksi</span></a>
                        <ul class="collapse">
                            <li><a href="/pemasukan">Pemasukan</a></li>
                            <li><a href="/pengeluaran">Pengeluaran</a></li>
                        </ul>
                    </li>

                    <!-- MASTER DATA Section -->
                    @if(auth()->check() && auth()->user()->role->name == 'admin')
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true">
                            <i class="ti-layout-sidebar-left"></i><span>MASTER DATA</span></a>
                        <ul class="collapse">
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true">
                                    <i class="ti-pie-chart"></i>
                                    <span>Input Hutang/Piutang</span>
                                </a>
                                <ul class="collapse">
                                    <li><a href="/hutang">Tambah Hutang</a></li>
                                    <li><a href="/piutang">Tambah Piutang</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true">
                                    <i class="ti-pie-chart"></i>
                                    <span>User Manajemen</span>
                                </a>
                                <ul class="collapse">
                                    <li><a href="/data-user">Lihat Data User</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true">
                                    <i class="ti-slice"></i>
                                    <span>Fitur Tambahan</span>
                                </a>
                                <ul class="collapse">
                                    <li><a href="/fitur_tambahan">Optimalisasi Keuangan</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- LAPORAN Section -->
                    @if(auth()->check() && auth()->user()->role->name == 'admin')
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-file"></i><span>LAPORAN</span></a>
                        <ul class="collapse">
                            <li><a href="/laporan-keuangan">Lihat Laporan Keuangan</a></li>
                            <li><a href="/laporan-pengeluaran">Lihat Laporan Pengeluaran</a></li>
                            <li><a href="/laporan-pemasukan">Lihat Laporan Pemasukan</a></li>
                            <li><a href="/laporan-hutang">Lihat Laporan Hutang</a></li>
                            <li><a href="/laporan-piutang">Lihat Piutang</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>