<?php
switch ($_GET[act]) {
    default:
        $pjl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
        $pbl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
        $psd = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='113'"));
        //t
        $psd2 = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='112'"));
        $ret = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='511'"));
        $biaya = mysql_fetch_array(mysql_query("select sum(jumlah) as jumlah from rekening where kd_rek between '611' and '699'"));
        $pot = mysql_fetch_array(mysql_query("select sum(disc) as pot from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' "));
        $kas = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='111'"));
        $hut = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='211'"));
        $pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal");
        $modal = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='311'")); //
        $prive = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='312'")); //


        $tampil = mysql_query("SELECT * FROM barang ORDER BY nama");
        while ($r = mysql_fetch_array($tampil)) {
            $jml = $r[stok];
            $hrg = $r[hrg_beli];
            $subtotal = $jml * $hrg;
            $total1 = $total1 + $subtotal;
        } //persediaan akhir


        $jmlretpo = $ret[jumlah] + $pot[pot];
        // $pblbersih = $pbl[total] - $jmlretpo; //pembelian bersih
        $pblbersih = $pbl[total]; //pembelian bersih
        $x2 = $pblbersih + $psd[jumlah]; //barang siap jual
        $hpp = $x2 - $total1; //barang siap jual di kurangi persediaan akhir
        $kotor = $pjl[total] - $hpp;
        $pajak = $kotor * 10 / 100;
        $beban = $biaya[jumlah] + $pajak;
        $laba = $kotor - $beban;

        $total = $laba - $prive[jumlah];
        $modalakhir = $modal[jumlah] + $total;

        $aktiva = $psd2[jumlah] + $kas[jumlah];
        $passiva = $modalakhir + $hut[jumlah];

        $tampil = mysql_query("SELECT * FROM barang ORDER BY nama");
        while ($r = mysql_fetch_array($tampil)) {
            $stok = $r[stok];
            $hrg_beli = $r[hrg_beli];
            $sub_total = $stok * $hrg_beli;
            $persedian_akhir = $persedian_akhir + $sub_total;
        }

?>
<div class="pagetitle" style="position: relative;">
	<h1>Laporan Neraca</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active"><a href="#">Laporan Neraca</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">
			<section class="section">
				<div class="row">
					<div class="col-lg-12">

						<div class="card">
							<div class="card-header text-center">
								<h5 class="card-title p-0" style="display: inline-block"><?php echo"$pemilik[nm_perusahaan]<br>$pemilik[alamat]" ?><br>Laporan Persediaan Priode <?php echo tgl_indo($tgl_sekarang);?></h5>
							</div>
							<div class="card-body mt-4">
								<div class="row">
                                    <div class="col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Aktiva</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3">Kas</td>
                                                        <td><?php  echo "Rp.".number_format($kas[jumlah],2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Persedian Akhir Barang</td>
                                                        <td><?php  echo "Rp.".number_format($persedian_akhir,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td align="right"><?php  echo "Rp.".number_format($aktiva,2,'.',','); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Passiva</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3">Hutang</td>
                                                        <td><?php  echo "Rp.".number_format($hut[jumlah],2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Modal</td>
                                                        <td><?php  echo "Rp.".number_format($modalakhir,2,'.',','); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td align="right"><?php  echo "Rp.".number_format($passiva,2,'.',','); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>

					</div>
				</div>
			</section>
		</div>
	</div>
</section>

<?php
}
?>