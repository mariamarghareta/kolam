<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

            <h5 class="centered"><?php echo strtoupper($_SESSION['uname']);?></h5>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Monitoring" id="menu_monitoring" >
                    <i class="fa fa-clock-o"></i>
                    <span>Monitoring</span>
                </a>
            </li>
            <?php if ($_SESSION['role_id'] == 1) {?>
            <li class="sub-menu">
                <a class="" href="#" id="menu_bt">
                    <i class="fa fa-pencil"></i>
                    <span>Master Data</span>
                </a>
                <ul class="sub" id="sub_menu_master_data">
                    <li class="" id="menu_blok"><a  href="<?= base_url();?><?= index_page();?>/Masterblok" >Master Blok</a></li>
                    <li id="menu_kolam"><a  href="<?= base_url();?><?= index_page();?>/Masterkolam" >Master Kolam</a></li>
                    <li id="menu_ikan"><a  href="<?= base_url();?><?= index_page();?>/Masterikan" >Master Ikan</a></li>
                    <li id="menu_pakan"><a  href="<?= base_url();?><?= index_page();?>/Masterpakan" >Master Pakan</a></li>
                    <li id="menu_obat"><a  href="<?= base_url();?><?= index_page();?>/Masterobat" >Master Obat</a></li>
                    <li id="menu_tabel_pakan"><a  href="<?= base_url();?><?= index_page();?>/Mastertabelpakan" >Master Tabel Pakan</a></li>
                    <li id="menu_mitra"><a  href="<?= base_url();?><?= index_page();?>/Mastermitra" >Master Mitra Bisnis</a></li>
                    <li id="menu_karyawan"><a  href="<?= base_url();?><?= index_page();?>/Masterkaryawan" >Master Karyawan</a></li>
                    <li id="menu_invadj"><a  href="<?= base_url();?><?= index_page();?>/Stockadj" >Inventory Adjustment</a></li>
                </ul>
            </li>
            <?php } ?>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Mastertebar" id="menu_tebar" >
                    <i class="fa fa-plus"></i>
                    <span>Tebar Bibit</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Mastersampling" id="menu_sampling" >
                    <i class="fa fa-search"></i>
                    <span>Sampling</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Mastergrading" id="menu_grading" >
                    <i class="fa fa-book"></i>
                    <span>Grading</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Pembuatanpakan" id="menu_pembuatan_pakan" >
                    <i class="fa fa-arrow-right"></i>
                    <span>Pembuatan Pakan</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Monitoringpakan" id="menu_monitoring_pakan" >
                    <i class="fa fa-eye"></i>
                    <span>Monitoring Pakan</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Monitoringair" id="menu_monitoring_air" >
                    <i class="fa fa-tint"></i>
                    <span>Monitoring Air</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Monitoringsayur" id="menu_monitoring_sayur" >
                    <i class="fa fa-leaf"></i>
                    <span>Monitoring Sayur</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Transaksipembelian" id="menu_pembelian" >
                    <i class="fa fa-minus"></i>
                    <span>Pembelian</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Transaksipenjualan" id="menu_penjualan" >
                    <i class="fa fa-dollar"></i>
                    <span>Penjualan</span>
                </a>
            </li>
            <?php if ($_SESSION['role_id'] == 1) {?>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Mastertime" id="menu_time" >
                    <i class="fa fa-clock-o"></i>
                    <span>Ubah Timeout Session</span>
                </a>
            </li>
            <?php } ?>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Masterpass" id="menu_pass" >
                    <i class="fa fa-key"></i>
                    <span>Ubah Password</span>
                </a>
            </li>
            <li class="sub-menu">
                <a class="" href="#" id="menu_laporan">
                    <i class="fa fa-book"></i>
                    <span>Laporan</span>
                </a>
                <ul class="sub" id="sub_menu_laporan">
                    <li class="" id="menu_laporan_keuangan"><a  href="<?= base_url();?><?= index_page();?>/Laporankeuangan" >Laporan Keuangan</a></li>
                    <li class="" id="menu_laporan_mon_air"><a  href="<?= base_url();?><?= index_page();?>/Laporanmonair" >Laporan Monitoring Air</a></li>
                    <li class="" id="menu_laporan_mon_pakan"><a  href="<?= base_url();?><?= index_page();?>/Laporanmonpakan" >Laporan Monitoring Pakan</a></li>
                    <li class="" id="menu_laporan_mon_sayur"><a  href="<?= base_url();?><?= index_page();?>/Laporanmonsayur" >Laporan Monitoring Sayur</a></li>
                    <li class="" id="menu_laporan_tebar"><a  href="<?= base_url();?><?= index_page();?>/Laporantebar" >Laporan Tebar</a></li>
                    <li class="" id="menu_laporan_pembuatan_pakan"><a  href="<?= base_url();?><?= index_page();?>/Laporanpembuatanpakan" >Laporan Pembuatan Pakan</a></li>
                </ul>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->