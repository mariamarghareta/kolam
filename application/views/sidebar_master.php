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
                </ul>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Mastertebar" id="menu_tebar" >
                    <i class="fa fa-plus"></i>
                    <span>Tebar Bibit</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Mastertime" id="menu_time" >
                    <i class="fa fa-clock-o"></i>
                    <span>Ubah Timeout Session</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= base_url();?><?= index_page();?>/Masterpass" id="menu_pass" >
                    <i class="fa fa-key"></i>
                    <span>Ubah Password</span>
                </a>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->