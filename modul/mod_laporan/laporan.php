<script type="text/javascript">
	(function($) {
		$("#t").click(function() {
			$("#excel").slideDown();
			$("#y").show();
			$("#tampilkan").hide();
			("#pdf").hide();

		});
		$("#f").click(function() {
			$("#excel").toggle();
			$("#tampilkan").show();
		});
		$("#p").click(function() {
			$("#pdf").slideDown();
			$("#pdfp").show();
			$("#excel").hide();
			$("#tampilkan").hide();
		});
		$("#zp").click(function() {
			$("#excel").hide();
			$("#tampilkan").show();
			$("#pdf").hide();
		});
	});
</script>
<?php
//print_r($_POST);
$aksi = "../modul/mod_barang/aksi_laporan.php";
switch ($_GET[act]) {
		// Tampil barang
	default:
		echo "<h2><img src='../img/icons/16x16_0680/page_white_text.png'>&nbspLaporan</h2><hr>
			<input type='button' class='btn btn-default' value='laporan rugi laba' onClick=\"window.location.href='llb';\">
			<input type='button' class='btn' value='laporan Penjualan' onClick=\"window.location.href='lpj';\">
			<input type='button' class='btn' value='laporan Pembelian' onClick=\"window.location.href='lpb';\">
			<input type='button' class='btn' value='laporan Stok' onClick=\"window.location.href='lps';\">";
		echo "<br></br>
		<div id='inti'><fieldset><legend>Laporan Global</legend>
			<legend>Penjualan</legend>";
		$pertama = $thn_sekarang . '-' . $bln_sekarang . '-' . "01";
		$total = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between 
			'$pertama' and '$tgl_sekarang' order by tanggal asc")) or die(mysql_error());
		echo "Total Penjualan <br>Bulan";
		echo tgl_indo(date("Y m "));
		echo "</br>ini Sebesar<br>
			Rp.&nbsp" . number_format($total[total], 2, ',', '.');
		echo "<legend>Hutang</legend>";
		$pertama = $thn_sekarang . '-' . $bln_sekarang . '-' . "01";
		$total = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between 
			'$pertama' and '$tgl_sekarang' and status='Tempo' order by tanggal asc")) or die(mysql_error());
		echo "Total Hutang <br>Bulan";
		echo tgl_indo(date("Y m "));
		echo "</br>ini Sebesar<br>
			Rp.&nbsp" . number_format($total[total], 2, ',', '.');
		echo "
			
			<legend>Pembelian</legend>";
		$pertama = $thn_sekarang . '-' . $bln_sekarang . '-' . "01";
		$total = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between 
			'$pertama' and '$tgl_sekarang' and status='Tunai' order by tanggal asc")) or die(mysql_error());
		echo "Total Pembelian <br>Bulan";
		echo tgl_indo(date("Y m "));
		echo "</br>ini Sebesar<br>
			Rp.&nbsp" . number_format($total[total], 2, ',', '.');
		echo "
			<legend>Stok Barang</legend>";
		$query = mysql_query("select * from barang") or die(mysql_error());
		while ($r = mysql_fetch_array($query)) {
			$jml = $r[stok];
			$hrg = $r[hrg_beli];
			$subtotal = $jml * $hrg;
			$totalp = $totalp + $subtotal;
		}
		echo "Total Persedian <br>Bulan";
		echo tgl_indo(date("Y m "));
		echo "</br>ini Sebesar<br>
			Rp.&nbsp" . number_format($totalp, 2, ',', '.');
		echo "	</fieldset></div>
			<div id='laba'>
			<fieldset>
			<legend>Laba Rugi periode ";
		echo tgl_indo($tgl_sekarang);
		echo "</legend>
			"; ?>
		<script type="text/javascript">
			$(function() {
				var chart;

				$(document).ready(function() {

					// Build the chart
					chart = new Highcharts.Chart({
						chart: {
							renderTo: 'container',
							plotBackgroundColor: null,
							plotBorderWidth: null,
							plotShadow: false
						},
						title: {
							text: 'Grafik Pie<br><?php echo "$pemilik[nm_perusahaan]" ?>'
						},
						tooltip: {
							pointFormat: '{series.name}: <b>{point.percentage}%</b>',
							percentageDecimals: 1
						},
						plotOptions: {
							pie: {
								allowPointSelect: true,
								cursor: 'pointer',
								dataLabels: {
									enabled: false
								},
								showInLegend: true
							}
						},
						series: [
							<?php
							//penjualan
							$pertama = $thn_sekarang . '-' . $bln_sekarang . '-' . "01";
							$penj = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between 
			'$pertama' and '$tgl_sekarang' order by tanggal asc")) or die(mysql_error());
							//pembelian
							$pertama = $thn_sekarang . '-' . $bln_sekarang . '-' . "01";
							$pemb = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between 
			'$pertama' and '$tgl_sekarang' and status='Tunai' order by tanggal asc")) or die(mysql_error());
							//laba
							$pjl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
							$pbl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' and status='Tunai' ")) or die(mysql_error());
							$psd = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='113'")) or die(mysql_error());
							$ret = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='511'")) or die(mysql_error());
							$biaya = mysql_fetch_array(mysql_query("select sum(jumlah) as jumlah from rekening where kd_rek between '611' and '699'")) or die(mysql_error());
							$pot = mysql_fetch_array(mysql_query("select sum(disc) as pot from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' and status='Tunai'")) or die(mysql_error());

							$tampil = mysql_query("SELECT * FROM barang ORDER BY nama");
							while ($r = mysql_fetch_array($tampil)) {
								$jml = $r[stok];
								$hrg = $r[hrg_beli];
								$subtotal = $jml * $hrg;
								$totall = $totall + $subtotal;
							}
							$x = $pbl[total] - $ret[jumlah] - $pot[pot];
							$x2 = $x + $psd[jumlah];
							$hpp = $x2 - $totall;
							$kotor = $pjl[total] - $hpp;
							$pajak = $kotor * 10 / 100;
							$beban = $biaya[jumlah] + $pajak;
							$laba = $kotor - $beban;

							$pj = $penj[total];
							$tot = $pj + $pb + $lb;
							$pb = $pemb[total];
							$prtma = $pj / $tot * 100 / 100;
							$lb = $laba;
							$kedua = $pb / $tot * 100 / 100;
							$ketiga = $lb / $tot * 100 / 100;
							?> {
								type: 'pie',
								name: 'Laporan',
								data: [
									['Penjulan', <?php echo $prtma ?>],
									['Pembelian', <?php echo $kedua ?>],
									{
										name: 'Laba',
										y: <?php echo $ketiga ?>,
										sliced: true,
										selected: true
									},
								]
							}
						]
					});
				});

			});
		</script>
		</head>

		<body>
			<script src="../js/highcharts.js"></script>
			<div id="container" style="width:120px;float:right; height: 200px; margin: 0 auto"></div>

			<?php echo "
		";
			$pjl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
			$pbl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
			$psd = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='113'"));
			$ret = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='511'"));
			$biaya = mysql_fetch_array(mysql_query("select sum(jumlah) as jumlah from rekening where kd_rek between '611' and '699'"));
			$pot = mysql_fetch_array(mysql_query("select sum(disc) as pot from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' "));

			$tampil = mysql_query("SELECT * FROM barang ORDER BY nama");
			while ($r = mysql_fetch_array($tampil)) {
				$jml = $r[stok];
				$hrg = $r[hrg_beli];
				$subtotal = $jml * $hrg;
				$total1 = $total1 + $subtotal;
			} //persediaan akhir
			$jmlretpo = $ret[jumlah] + $pot[pot];
			$pblbersih = $pbl[total] - $jmlretpo; //pembelian bersih
			$x2 = $pblbersih + $psd[jumlah]; //barang siap jual
			$hpp = $x2 - $total1; //barang siap jual di kurangi persediaan akhir
			$kotor = $pjl[total] - $hpp;
			$pajak = $kotor * 10 / 100;
			$beban = $biaya[jumlah] + $pajak;
			$laba = $kotor - $beban;
			?>
			<table class="informasi" style='width:400px;'>
				<tr>
					<th colspan='8'>Keterangan</th>
					<th></th>
					<th></th>
				</tr>
				<tr class="body">
					<td colspan='8'>
						<div align="left">Penjualan</div>
					</td>
					<td align="right"><?php echo "Rp." . number_format($pjl[total], 2, '.', ','); ?></td>
					<td align="right"><?php  ?></td>
				</tr>
				<tr class="body">
					<td colspan='8'>
						<div align="left">HPP</div>
					</td>
					<td align="right"><?php echo "Rp." . number_format($hpp, 2, '.', ','); ?></td>
					<td align="right"><?php  ?></td>
				</tr>
				<tr class="body">
					<td colspan='8'>
						<div align="right">laba Kotor</div>
					</td>
					<td align="right"><?php  ?></td>
					<td align="right"><?php echo "Rp." . number_format($kotor, 2, '.', ','); ?></td>
				</tr>
				<tr class="body">
					<td colspan='8'>
						<div align="left">Biaya</div>
					</td>
					<td align="right"><?php echo "Rp." . number_format($biaya[jumlah], 2, '.', ','); ?></td>
					<td align="right"><?php  ?></td>
				</tr>
				<tr class="body">
					<td colspan='8'>
						<div align="left">Pajak</div>
					</td>
					<td align="right"><?php echo "Rp." . number_format($pajak, 2, '.', ','); ?></td>
					<td align="right"><?php  ?></td>
				</tr>
				<tr class="body">
					<td colspan='8'>
						<div align="right">Jumlah Beban</div>
					</td>
					<td align="right"><?php  ?></td>
					<td align="right"><?php echo "Rp." . number_format($beban, 2, '.', ','); ?></td>
				</tr>
				<tr class="footer">
					<td colspan="9">
						<div align="center"><strong>Total Laba</strong></div>
					</td>
					<td align="right"><strong><?php echo "Rp." . number_format($laba, 2, '.', ','); ?></strong></td>
				</tr>
			</table><a href="llb">Detail laporan</a>
			</fieldset>
			</div>
		<?php
		break;
	case "labarugi":

		$pjl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
		$pbl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
		$psd = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='113'"));
		$ret = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='511'"));
		$biaya = mysql_fetch_array(mysql_query("select sum(jumlah) as jumlah from rekening where kd_rek between '611' and '699'"));
		$pot = mysql_fetch_array(mysql_query("select sum(disc) as pot from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang'"));

		$tampil = mysql_query("SELECT * FROM barang ORDER BY nama");
		while ($r = mysql_fetch_array($tampil)) {
			$jml = $r[stok];
			$hrg = $r[hrg_beli];
			$subtotal = $jml * $hrg;
			$total1 = $total1 + $subtotal;
		} //persediaan akhir
		$jmlretpo = $ret[jumlah] + $pot[pot];
		$pblbersih = $pbl[total] - $jmlretpo; //pembelian bersih
		$x2 = $pblbersih + $psd[jumlah]; //barang siap jual
		$hpp = $x2 - $total1; //barang siap jual di kurangi persediaan akhir
		$kotor = $pjl[total] - $hpp;
		$pajak = $kotor * 10 / 100;
		$beban = $biaya[jumlah] + $pajak;
		$laba = $kotor - $beban;
		$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal"); ?>


<div class="pagetitle" style="position: relative;">
	<h1>Laporan Rugi/Laba</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active"><a href="#">Laporan Rugi/Laba</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header text-center">
					<h5 class="card-title p-0" style="display: inline-block"><?php echo"$pemilik[nm_perusahaan]<br>$pemilik[alamat]" ?><br>Laporan Rugi/Laba Priode <?php echo tgl_indo($tgl_sekarang);?></h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="3">Keterangan</th>
								</tr>
							</thead>
							<tbody>
								<tr class="body">
									<td colspan='10'><div align="left">Penjualan</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pjl[total],2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								<tr class="body">
									<td colspan='7'><div align="left">Persedian Awal</div></td>
									<td align="right"><?php  echo "Rp.".number_format($psd[jumlah],2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
								<tr class="body">
									<td colspan='6'><div align="left">Pembelian</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pbl[total],2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='5'><div align="left">Potongan Pembelian</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pot[pot],2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='5'><div align="left">Retur Pembelian</div></td>
									<td align="right"><?php  echo "Rp.".number_format($ret[jumlah],2,'.',','); ?>+<hr></td>
									<td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='6'><div align="left"></div></td>
									<td align="right"><?php  echo "Rp.".number_format($jmlretpo,2,'.',','); ?>+<hr></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  ?></td><td align="right"><?php  ?></td>
								</tr><tr class="body">
									<td colspan='7'><div align="left">Pembelian Bersih</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pblbersih,2,'.',','); ?><hr></td>
									<td align="right"><?php echo"+"; ?></td>
								</tr></tr><tr class="body">
									<td colspan='7'><div align="left">Barang Siap Jual</div></td>
									<td align="right"><?php  echo "Rp.".number_format($x2,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  ?></td>
								</tr></tr><tr class="body">
									<td colspan='7'><div align="left">Persediaan Akhir</div></td>
									<td align="right"><?php  echo "Rp.".number_format($total1,2,'.',','); ?><hr></td>
									<td align="right"><?php echo"-"; ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								<tr class="body">
									<td colspan='9'><div align="left">Harga Pokok Penjualan</div></td>
									<td align="right"><?php  echo "Rp.".number_format($hpp,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								<tr class="body">
									<td colspan='9'><div align="right">laba Kotor</div></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  echo "Rp.".number_format($kotor,2,'.',','); ?></td>
								</tr>
								<tr class="body">
									<td colspan='9'><div align="left">Biaya</div></td>
									<td align="right"><?php  echo "Rp.".number_format($biaya[jumlah],2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								<tr class="body">
									<td colspan='9'><div align="left">Pajak</div></td>
									<td align="right"><?php  echo "Rp.".number_format($pajak,2,'.',','); ?></td>
									<td align="right"><?php  ?></td>
								</tr>
								<tr class="body">
									<td colspan='9'><div align="right">Jumlah Beban</div></td>
									<td align="right"><?php  ?></td>
									<td align="right"><?php  echo "Rp.".number_format($beban,2,'.',','); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0">Priode</h5>
				</div>
				<div class="card-body mt-3">
					<div class="row">
						<form action="llb.pdf" method="post" name="postform" class="d-flex" style="gap: 15px"  target='_blank'>
							<?php 
							echo 	
								combonamabln(1,12,'bln_1',$bln_sekarang);
								combothn(2000,$thn_sekarang,'thn_1',$thn_sekarang);
							?>
							<input type="submit" class='btn btn-success' name="report" value="Tampilkan" /> <a onclick="window.open('llb.print','Print','menubar=no,navigator=no,width=825,height=600,left=200,top=150,toolbar=no')";><i class='icon-print'></i></a>
						</form> 
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
	<?php

		break;
	case "penjualan";
		$query_tanggal = mysql_fetch_array(mysql_query("select min(tanggal_transaksi) as tanggal_pertama from master_transaksi"));
		$tanggal_pertama = $query_tanggal['tanggal_pertama'];

		//untuk menyelesaikan transaksi
		$p      = new Paging8;
		$batas  = 10;
		$posisi = $p->cariPosisi($batas);
		$no = $posisi + 1;
		if (isset($_POST['report'])) {

			//tanggal periode laporan
			$tanggal1 = $_POST[thn_1] . '-' . $_POST[bln_1] . '-' . $_POST[tgl_1];
			$tanggal2 = $_POST[thn_2] . '-' . $_POST[bln_2] . '-' . $_POST[tgl_2];

			$query_transaksi = mysql_query("select * from kd_penj where tanggal between '$tanggal1' and '$tanggal2' order by tanggal asc limit $posisi,$batas");
			$total = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$tanggal1' and '$tanggal2' order by tanggal asc limit  $posisi,$batas"));
		} else {

			//$query_transaksi=mysql_query("select * from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' order by tanggal asc limit $posisi,$batas") or die (mysql_error());
			//$total=mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' order by tanggal asc"))or die (mysql_error());

			unset($_POST['report']);
		}
		$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal"); ?>
		
<div class="pagetitle" style="position: relative;">
	<h1>Laporan Penjualan</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active"><a href="#">Laporan Penjualan</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">			
			<!-- DATA TABLE SCRIPTS -->
			<script src="assets/js/dataTables/datatables.min.js"></script>
			<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
			<script>
				$(document).ready(function () {
					$('.datatable-jquery').dataTable({
						sDom: 'lBfrtip',
						"bLengthChange": false,
						columnDefs: [{
							className: 'text-center',
							targets: [0, 1, 2, 3]
						},
						{
							width: "14%",
							targets: [2]
						}],
					});
				});
			</script>

			<section class="section">
				<div class="row">
					<div class="col-lg-12">

						<div class="card">
							<div class="card-header text-center">
								<h5 class="card-title p-0" style="display: inline-block"><?php echo"$pemilik[nm_perusahaan]<br>$pemilik[alamat]" ?><br>Laporan Penjualan Priode <?php echo tgl_indo($tgl_sekarang);?></h5>
							</div>
							<div class="card-body mt-5">
								<div class="row" style="gap: 11px 0; display: inline-flex!important">
									<form action="lpj" method="post" name="postform" style="z-index: 100; display: flex; gap: 10px; align-items: center;">
										<?php 
										echo 	
											combotgl(1,31,'tgl_1',1);
											combonamabln(1,12,'bln_1',$bln_sekarang);
											combothn(2000,$thn_sekarang,'thn_1',$thn_sekarang);
										?>
										
										S/D
										<?php
										echo 	
											combotgl(1,31,'tgl_2',$tgl_skrg);
											combonamabln(1,12,'bln_2',$bln_sekarang);
											combothn(2000,$thn_sekarang,'thn_2',$thn_sekarang); 
										?>
										
										<input type="submit" name="report" class="btn btn-success" value="tampilkan" />
										<div class="btn-group">
											<button class="btn btn-warning">Cetak</button>
											<button data-toggle="dropdown" class="btn btn-warning dropdown-toggle"><span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="<?php echo"lpj.pdf?tgl1=$tanggal1&tgl2=$tanggal2";?>" target='_blank'>PDF</a></li>
											</ul>
										</div>
									</form>
								</div>
								<div class="table-responsive" style="margin-top: -35px">
									<table
										class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
										<thead>
											<tr>
												<th scope="col">No.</th>
												<th scope="col">Tanggal</th>
												<th scope="col">Nomor Bukti</th>
												<th scope="col">Total</th>
											</tr>
										</thead>
										<tbody>
											<?php
											while($row_tran=mysql_fetch_array($query_transaksi)){
												
												?>
												<tr class="body">
													<td><div align="center"><?php echo $no?></div></td>
													<td><div align="center"><?php echo $row_tran['tanggal'];?></div></td>
													<td><div align="center"><?php echo $row_tran['kd_pjl'];?></div></td>
													<td><div align="center"><?php echo "Rp.&nbsp" . number_format($row_tran['total'],2,'.',','); ?></div></td>
												</tr>
												<?php
												$no++;
											}?>
										</tbody>
										<tfoot>
											<tr class="footer">
												<td colspan="3"><div align="center"><strong>TOTAL PENJUALAN</strong></div></td>
												<td><div align="center"><strong><?php echo "Rp.&nbsp" . number_format($total['total'],2,'.',','); ?></strong></div></td>
											</tr>
										</tfoot>
									</table>
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
	break;
case "pembelian";
	$query_tanggal = mysql_fetch_array(mysql_query("select min(tanggal_transaksi) as tanggal_pertama from master_transaksi"));
	$tanggal_pertama = $query_tanggal['tanggal_pertama'];

	//untuk menyelesaikan transaksi
	$p      = new Paging4;
	$batas  = 10;
	$posisi = $p->cariPosisi($batas);
	$no = 1;
	if (isset($_POST['report'])) {

		//tanggal periode laporan
		$tanggal1 = $_POST[thn_1] . '-' . $_POST[bln_1] . '-' . $_POST[tgl_1];
		$tanggal2 = $_POST[thn_2] . '-' . $_POST[bln_2] . '-' . $_POST[tgl_2];

		$query_transaksi = mysql_query("select * from supplier,kd_pemb where kd_pemb.status='tunai' and kd_pemb.id_supplier=supplier.id and tanggal between '$tanggal1' and '$tanggal2' order by tanggal asc limit $posisi,$batas");
		$total = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where kd_pemb.status='tunai' and tanggal between '$tanggal1' and '$tanggal2' order by tanggal asc limit  $posisi,$batas"));
	} else {

		//	$query_transaksi=mysql_query("select * from supplier,kd_pemb where kd_pemb.status='tunai' and kd_pemb.id_supplier=supplier.id_supplier and  tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' order by tanggal asc limit $posisi,$batas") or die (mysql_error());
		//$total=mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where kd_pemb.status='tunai' and tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' order by tanggal asc"))or die (mysql_error());

		unset($_POST['report']);
	}
	$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal"); ?>

			
<div class="pagetitle" style="position: relative;">
	<h1>Laporan Pembelian</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active"><a href="#">Laporan Pembelian</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">			
			<!-- DATA TABLE SCRIPTS -->
			<script src="assets/js/dataTables/datatables.min.js"></script>
			<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
			<script>
				$(document).ready(function () {
					$('.datatable-jquery').dataTable({
						sDom: 'lBfrtip',
						"bLengthChange": false,
						columnDefs: [{
							className: 'text-center',
							targets: [0, 1, 2, 3, 4, 5, 6]
						},
						{
							width: "14%",
							targets: [2]
						}],
					});
				});
			</script>

			<section class="section">
				<div class="row">
					<div class="col-lg-12">

						<div class="card">
							<div class="card-header text-center">
								<h5 class="card-title p-0" style="display: inline-block"><?php echo"$pemilik[nm_perusahaan]<br>$pemilik[alamat]" ?><br>Laporan Pembelian Priode <?php echo tgl_indo($tgl_sekarang);?></h5>
							</div>
							<div class="card-body mt-5">
								<div class="row" style="gap: 11px 0; display: inline-flex!important">
									<form action="lpb" method="post" name="postform" style="z-index: 100; display: flex; gap: 10px; align-items: center;">
										<?php 
											if(empty($_POST['tanggal1'])){ 
												echo
													combotgl(1,31,'tgl_1',1);
													combonamabln(1,12,'bln_1',$bln_sekarang);
													combothn(2000,$thn_sekarang,'thn_1',$thn_sekarang);
											}else{	
												echo 	
													combotgl(1,31,'tgl_1',$_POST[tgl_1]);
													combonamabln(1,12,'bln_1',$_POST[bln_1]);
													combothn(2000,$thn_sekarang,'thn_1',$_POST[thn_1]); 
										}?>
										
										S/D
										<?php 
											if(empty($_POST['tanggal2'])){ 
												echo 	
													combotgl(1,31,'tgl_2',$tgl_skrg);
													combonamabln(1,12,'bln_2',$bln_sekarang);
													combothn(2000,$thn_sekarang,'thn_2',$thn_sekarang);
											}else{ 
												echo 	
													combotgl(1,31,'tgl_2',$_POST[tgl_2]);
													combonamabln(1,12,'bln_2',$_POST[bln_2]);
													combothn(2000,$thn_sekarang,'thn_2',$_POST[thn_2]);  
											}?>
										
										<input type="submit" name="report" class="btn btn-success" value="tampilkan" />
										<div class="btn-group">
											<button class="btn btn-warning">Cetak</button>
											<button data-toggle="dropdown" class="btn btn-warning dropdown-toggle"><span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="<?php echo"lpb.pdf?tgl1=$tanggal1&tgl2=$tanggal2";?>" target='_blank'>PDF</a></li>
											</ul>
										</div>
									</form>
								</div>
								<div class="table-responsive" style="margin-top: -35px">
									<table
										class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
										<thead>
											<tr>
												<th scope="col">No.</th>
												<th scope="col">Tanggal</th>
												<th scope="col">Nomor Bukti</th>
												<th scope="col">Nomor Faktur</th>
												<th scope="col">Tanggal Faktur</th>
												<th scope="col">Supplier</th>
												<th scope="col">Total</th>
											</tr>
										</thead>
										<tbody>
											<?php
											while($row_tran=mysql_fetch_array($query_transaksi)){
												
												?>
												<tr class="body">
													<td><div align="center"><?php echo $no?></div></td>
													<td><div align="center"><?php echo $row_tran['tanggal'];?></div></td>
													<td><div align="center"><?php echo $row_tran['kd_pmb'];?></div></td>
													<td><div align="center"><?php echo $row_tran['nofaktur'];?></div></td>
													<td><div align="center"><?php echo $row_tran['tgl_faktur'];?></div></td>
													<td><div align="center"><?php echo $row_tran['nm_supplier'];?></div></td>
													<td align="right"><?php echo "Rp.&nbsp" . number_format($row_tran['total'],2,'.',','); ?></td>
												</tr>
												<?php
												$no++;
											}
											?>
										</tbody>
										<tfoot>
											<tr class="footer">
												<td colspan="6"><div align="center"><strong>TOTAL PEMBELIAN</strong></div></td>
												<td align="right"><strong><?php echo "Rp.&nbsp" . number_format($total['total'],2,'.',','); ?></strong></td>
											</tr>
										</tfoot>
									</table>
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
	break;
case "stok";
	$qmin = mysql_fetch_array(mysql_query("select min(bulan) as bulan, min(tahun) as tahun from master_laporan"));
	$pertamax = $qmin['bulan'];
	$pertama2 = $qmin['tahun'];

	//untuk menyelesaikan transaksi


	$query_transaksi = mysql_query("SELECT b.kode ,b.jenis, b.nama , b.satuan , p.pembelian , j.penjualan , b.stok , b.hrg_beli 
FROM barang b

left join
(
select id_product ,SUM(jumlah) as pembelian
from pembelian
where tanggal BETWEEN '$thn_sekarang-$bln_sekarang-1' AND '$tgl_sekarang'
group by id_product 
) p on p.id_product = b.kode

left join
(
select id_product,SUM(jumlah) as penjualan
from penjualan
where tanggal BETWEEN '$thn_sekarang-$bln_sekarang-1' AND '$tgl_sekarang'
group by id_product 
) j on j.id_product = b.kode

ORDER BY b.nama") or die(mysql_error());

$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal");

?>

<div class="pagetitle" style="position: relative;">
	<h1>Laporan Persediaan</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active"><a href="#">Laporan Persediaan</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">			
			<!-- DATA TABLE SCRIPTS -->
			<script src="assets/js/dataTables/datatables.min.js"></script>
			<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
			<script>
				$(document).ready(function () {
					$('.datatable-jquery').dataTable({
						sDom: 'lBfrtip',
						columnDefs: [{
							className: 'text-center',
							targets: [0, 1, 2, 3, 4, 5, 6, 7]
						},
						{
							width: "7%",
							targets: [0]
						}],
					});
				});
			</script>

			<section class="section">
				<div class="row">
					<div class="col-lg-12">

						<div class="card">
							<div class="card-header text-center">
								<h5 class="card-title p-0" style="display: inline-block"><?php echo"$pemilik[nm_perusahaan]<br>$pemilik[alamat]" ?><br>Laporan Persediaan Priode <?php echo tgl_indo($tgl_sekarang);?></h5>
							</div>
							<div class="card-body mt-4">
								<div class="mb-3 d-flex justify-content-end">
									<a href="lps.pdf" class="btn btn-secondary " target='_blank'><i class="bi bi-file-earmark-break-fill mr-2"></i>Print To PDF</a>
								</div>
								<div class="table-responsive">
									<table
										class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
										<thead>
											<tr>
												<th scope="col">No.</th>
												<th scope="col">Kode Barang</th>
												<th scope="col">Jenis</th>
												<th scope="col">Nama Barang</th>
												<th scope="col">Satuan</th>
												<th scope="col">Masuk</th>
												<th scope="col">Keluar</th>
												<th scope="col">Sisa</th>
												<th scope="col" class="text-center">Harga Beli</th>
												<th scope="col" class="text-center">SubTotal</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no=1;
											while($row_tran=mysql_fetch_array($query_transaksi)){
												$jml=$row_tran['stok'];$hrg=$row_tran['hrg_beli'];
												$subtotal= $jml*$hrg;
												$total= $total + $subtotal;
												?>
												<tr>
													<td><div align="center"><?php echo $no?></div></td>
													<td><div align="center"><?php echo $row_tran['0'];?></div></td>
													<td><div align="center"><?php echo $row_tran['1'];?></div></td>
													<td><div align="left"><?php echo $row_tran['2'];?></div></td>
													<td><div align="center"><?php echo $row_tran['3'];?></div></td>
													<td><div align="center"><?php echo $row_tran['4'];?></div></td>
													<td align="center"><?php echo $row_tran['5'] ?></td>
													<td align="center"><?php echo $row_tran['6'] ?></td>
													<td align="center"><?php echo "Rp.&nbsp" . $row_tran['7'] ?></td>
													<td align="right"><?php echo "Rp.&nbsp" . number_format($subtotal,2,'.',','); ?></td>
												</tr>
												<?php
												$no++;
											}
											?>
										</tbody>
										<tfoot>
											<tr class="footer">
												<td colspan="9"><div align="center"><strong>TOTAL Persediaan</strong></div></td>
												<td align="right" style="text-align: end;"><strong><?php echo "Rp.&nbsp" . number_format($total,2,'.',','); ?></strong></td>
											</tr>
										</tfoot>
									</table>
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
	break;
case "perubahanmodal";

	$kas = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='111'"));
	$prive = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='312'"));
	$hut = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='211'"));
	$modal = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='311'"));
	$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal");

	$pjl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
	$pbl = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die(mysql_error());
	$psd = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='113'"));
	$ret = mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='511'"));
	$biaya = mysql_fetch_array(mysql_query("select sum(jumlah) as jumlah from rekening where kd_rek between '611' and '699'"));
	$pot = mysql_fetch_array(mysql_query("select sum(disc) as pot from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' "));

	$tampil = mysql_query("SELECT * FROM barang ORDER BY nama");
	while ($r = mysql_fetch_array($tampil)) {
		$jml = $r[stok];
		$hrg = $r[hrg_beli];
		$subtotal = $jml * $hrg;
		$total1 = $total1 + $subtotal;
	} //persediaan akhir
	$jmlretpo = $ret[jumlah] + $pot[pot];
	$pblbersih = $pbl[total] - $jmlretpo; //pembelian bersih
	$x2 = $pblbersih + $psd[jumlah]; //barang siap jual
	$hpp = $x2 - $total1; //barang siap jual di kurangi persediaan akhir
	$kotor = $pjl[total] - $hpp;
	$pajak = $kotor * 10 / 100;
	$beban = $biaya[jumlah] + $pajak;
	$laba = $kotor - $beban;

	$total = $laba - $prive[jumlah];
	$total1 = $modal[jumlah] + $total;
	?>

<div class="pagetitle" style="position: relative;">
	<h1>Laporan Perubahan Modal</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active"><a href="#">Laporan Perubahan Modal</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header text-center">
					<h5 class="card-title p-0" style="display: inline-block"><?php echo"$pemilik[nm_perusahaan]<br>$pemilik[alamat]" ?><br>Laporan Perubahan Modal <?php echo tgl_indo($tgl_sekarang);?></h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th colspan="6">Keterangan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="4">Modal Awal</td>
									<td><?php  echo "Rp.".number_format($modal[jumlah],2,'.',','); ?></td>
								</tr>
									<tr>
									<td colspan="2">Laba</td>
									<td><?php  echo "Rp.".number_format($laba,2,'.',','); ?></td>
								</tr>
								<tr>
									<td colspan="2">Prive</td>
									<td><?php  echo "Rp.".number_format($prive[jumlah],2,'.',','); ?></td>
									<td align="right"><?php echo"-"; ?></td>
								</tr>
								<tr>
									<td colspan="4"></td>
									<td><?php  echo "Rp.".number_format($total,2,'.',',') . "      +"; ?></td>
								</tr>
								<tr>
									<td colspan="4">Modal Akhir</td>
									<td><?php  echo "Rp.".number_format($total1,2,'.',','); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
}
?>