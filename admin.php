<?php
error_reporting(0);
//memulai session
session_start();
//membuat session user dan password
if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {
    echo "<link href='css/style.css' rel='stylesheet' type='text/css'><peringatan>
 <center><i class='icon-warning-sign '></i><br><br><br>Untuk mengakses sistem ini,<br> Anda harus login <br><br>";
    echo "<input type='button' class='btn' value='Klik disini' title='klik disini' onclick=\"window.location.href='index.php';\"></center></peringatan>";
} else {
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Akuntansi Apotek SAKA SASMITRA</title>
    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <!-- Bootstrap Styles-->
    <link href="assets\js\dataTables\datatables.min.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <!-- <link href="assets/css/custom-styles.css" rel="stylesheet" /> -->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/jquery.ui.datepicker.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom-scripts.js"></script>
    
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/sweetalert/sweetalert.min.js"></script>

    <script src="assets/js/app-apotek.js"></script>
</head>

<body>
    <div id="wrapper">
        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">

            <div class="d-flex align-items-center justify-content-between">
                <a href="home" class="logo d-flex align-items-center">
                    <img src="assets/img/logo.png" alt="">
                    <span class="d-none d-lg-block">SAKA SASMITRA</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div><!-- End Logo -->

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">

                    <li class="nav-item dropdown pe-3">

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                            data-bs-toggle="dropdown">
                            <img src="assets\img\avatar\avatar-1.png" alt="Profile" class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2"><?= $_SESSION['namauser']; ?></span>
                        </a><!-- End Profile Iamge Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6><?= $_SESSION['namauser']; ?></h6>
                                <span><?= $_SESSION['leveluser']; ?></span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="akun">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center text-danger" href="keluar.php">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Log Out</span>
                                </a>
                            </li>

                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->

                </ul>
            </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->

        <!-- ======= Sidebar ======= -->

        <?php
        function activeToggle($currect_page){
        $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
        $url = end($url_array);
        foreach ($currect_page as $cp){
            if($cp == $url){
                echo 'show'; //class name in css 
                return;
            }
        }
        }

        function active($currect_page){
        $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
        $url = end($url_array);
        if($currect_page == $url){
            echo 'active'; //class name in css 
            return;
        } 
        }
        ?>
        <aside id="sidebar" class="sidebar">

            <ul class="sidebar-nav" id="sidebar-nav">

                <li class="nav-item">
                    <a class="nav-link <?php active('home');?>" href="home">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed <?php active('grafik');?>" href="grafik">
                        <i class="bi bi-bar-chart"></i><span>Grafik</span>
                    </a>
                </li><!-- End Charts Nav -->

                <li class="nav-item">
                    <a class="nav-link <?php if(activeToggle(array('barang', 'supplier', 'dokter', 'rekening')) !== 'active'){echo 'collapsed';}?>" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-menu-button-wide"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="charts-nav" class="nav-content collapse <?php activeToggle(array('barang', 'supplier', 'dokter', 'rekening')); ?>" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="barang" class="<?php active('barang');?>">
                                <i class="bi bi-circle"></i><span>Barang</span>
                            </a>
                        </li>
                        <li>
                            <a href="supplier" class="<?php active('supplier');?>">
                                <i class="bi bi-circle"></i><span>Supplier</span>
                            </a>
                        </li>
                        <li>
                            <a href="dokter" class="<?php active('dokter');?>">
                                <i class="bi bi-circle"></i><span>Dokter</span>
                            </a>
                        </li>
                        <li>
                            <a href="rekening" class="<?php active('rekening');?>">
                                <i class="bi bi-circle"></i><span>Akun</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Master Nav -->

                <li class="nav-item">
                    <a class="nav-link <?php if(activeToggle(array('transaksi.penjualan', 'transaksi.pembelian')) !== 'active'){echo 'collapsed';}?>" data-bs-target="#transaksi-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-cart"></i><span>Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="transaksi-nav" class="nav-content collapse <?php activeToggle(array('transaksi.penjualan', 'transaksi.pembelian')); ?>" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="transaksi.penjualan" class="<?php active('transaksi.penjualan');?>">
                                <i class="bi bi-circle"></i><span>Penjualan</span>
                            </a>
                        </li>
                        <li>
                            <a href="transaksi.pembelian" class="<?php active('transaksi.pembelian');?>">
                                <i class="bi bi-circle"></i><span>Pembelian</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Transaksi Nav -->

                <li class="nav-item">
                    <a class="nav-link <?php if(activeToggle(array('jurnal', 'jurnal.penjualan', 'jurnal.pembelian', 'jurnal.penyesuaian')) !== 'active'){echo 'collapsed';}?>" data-bs-target="#jurnal-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-bookmark-fill"></i><span>Jurnal</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="jurnal-nav" class="nav-content collapse <?php activeToggle(array('jurnal', 'jurnal.penjualan', 'jurnal.pembelian', 'jurnal.penyesuaian')); ?>" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="jurnal" class="<?php active('jurnal');?>">
                                <i class="bi bi-circle"></i><span>Jurnal Umum</span>
                            </a>
                        </li>
                        <li>
                            <a href="jurnal.penjualan" class="<?php active('jurnal.penjualan');?>">
                                <i class="bi bi-circle"></i><span>Jurnal Penjualan</span>
                            </a>
                        </li>
                        <li>
                            <a href="jurnal.pembelian" class="<?php active('jurnal.pembelian');?>">
                                <i class="bi bi-circle"></i><span>Jurnal Pembelian</span>
                            </a>
                        </li>
                        <li>
                            <a href="jurnal.penyesuaian" class="<?php active('jurnal.penyesuaian');?>">
                                <i class="bi bi-circle"></i><span>Jurnal Penyesuaian</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Jurnal Nav -->

                <li class="nav-item">
                    <a class="nav-link <?php if(activeToggle(array('llb', 'neraca', 'perubahanmodal', 'lpj', 'lpb', 'laporan.retur', 'laporan.hutang', 'lps')) !== 'active'){echo 'collapsed';}?>" data-bs-target="#laporan-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-book"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="laporan-nav" class="nav-content collapse <?php activeToggle(array('llb', 'neraca', 'perubahanmodal', 'lpj', 'lpb', 'laporan.retur', 'laporan.hutang', 'lps')); ?>" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="llb" class="<?php active('llb');?>">
                                <i class="bi bi-circle"></i><span>Laporan Rugi/Laba</span>
                            </a>
                        </li>
                        <li>
                            <a href="neraca" class="<?php active('neraca');?>">
                                <i class="bi bi-circle"></i><span>Laporan Neraca</span>
                            </a>
                        </li>
                        <li>
                            <a href="perubahanmodal" class="<?php active('perubahanmodal');?>">
                                <i class="bi bi-circle"></i><span>Laporan Perubahan Modal</span>
                            </a>
                        </li>
                        <li>
                            <a href="lpj" class="<?php active('lpj');?>">
                                <i class="bi bi-circle"></i><span>Laporan Penjualan</span>
                            </a>
                        </li>
                        <li>
                            <a href="lpb" class="<?php active('lpb');?>">
                                <i class="bi bi-circle"></i><span>Laporan Pembelian</span>
                            </a>
                        </li>
                        <li>
                            <a href="laporan.retur" class="<?php active('laporan.retur');?>">
                                <i class="bi bi-circle"></i><span>Laporan Retur Pembelian</span>
                            </a>
                        </li>
                        <li>
                            <a href="laporan.hutang" class="<?php active('laporan.hutang');?>">
                                <i class="bi bi-circle"></i><span>Laporan Hutang</span>
                            </a>
                        </li>
                        <li>
                            <a href="lps" class="<?php active('lps');?>">
                                <i class="bi bi-circle"></i><span>Laporan Persediaan</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Transaksi Nav -->

                <li class="nav-item">
                    <a class="nav-link <?php if(activeToggle(array('pengaturan', 'bantuan', 'pemulihan')) !== 'active'){echo 'collapsed';}?>" data-bs-target="#pengaturan-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-gear-fill"></i><span>Pengaturan</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="pengaturan-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="pengaturan" class="<?php active('pengaturan');?>">
                                <i class="bi bi-circle"></i><span>Plugin</span>
                            </a>
                        </li>
                        <li>
                            <a href="bantuan" class="<?php active('bantuan');?>">
                                <i class="bi bi-circle"></i><span>Penutupan</span>
                            </a>
                        </li>
                        <li>
                            <a href="pemulihan" class="<?php active('pemulihan');?>">
                                <i class="bi bi-circle"></i><span>Backup</span>
                            </a>
                        </li>
                    </ul>
                </li><!-- End Transaksi Nav -->

            </ul>

        </aside><!-- End Sidebar-->

        <!-- /. NAV SIDE  -->
        <main id="main" class="main">
            <?php include "halaman.php"; ?>
        </main>
    </div>
    <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <script src="assets/js/main.js"></script>

</body>

</html>
<?php
}
?>