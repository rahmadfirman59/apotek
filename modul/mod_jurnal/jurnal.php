<?php
switch($_GET[act]){
	default:
	
	$query_tanggal=mysql_fetch_array(mysql_query("select min(tanggal_transaksi) as tanggal_pertama from master_transaksi"));
	$tanggal_pertama=$query_tanggal['tanggal_pertama'];

	//untuk menyelesaikan transaksi

	if(isset($_POST['report'])){
		
		//tanggal periode laporan
		$tanggal1=$_POST[thn_1].'-'.$_POST[bln_1].'-'.$_POST[tgl_1];
		$tanggal2=$_POST[thn_2].'-'.$_POST[bln_2].'-'.$_POST[tgl_2];
		
		$query_transaksi=mysql_query("select * from master_transaksi where tanggal_transaksi between '$tanggal1' and '$tanggal2' ORDER BY id_transaksi desc");
		$total=mysql_fetch_array(mysql_query("select sum(debet) as tot_debet, sum(kredit) as tot_kredit from master_transaksi where tanggal_transaksi between '$tanggal1' and '$tanggal2' order by kode_rekening asc"));

	}else{
	
		$query_transaksi=mysql_query("select * from master_transaksi where tanggal_transaksi between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ORDER BY id_transaksi desc ") or die (mysql_error());
		$total=mysql_fetch_array(mysql_query("select sum(debet) as tot_debet, sum(kredit) as tot_kredit from master_transaksi where tanggal_transaksi between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ORDER BY id_transaksi "));
	
		unset($_POST['report']);
	}
	?>


<div class="pagetitle" style="position: relative;">
	<h1>Jurnal Umum</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Jurnal</a></li>
			<li class="breadcrumb-item active"><a href="#">Jurnal Umum</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-8">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Priode</h5>
				</div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
						<form action="jurnal" method="post" name="postform" style="display: flex; gap: 10px; align-items: center;">
							<?php 
							if(isset($_POST['report'])){ 
								echo 	combotgl(1,31,'tgl_1',$_POST[tgl_1]);
										combonamabln(1,12,'bln_1',$_POST[bln_1]);
										combothn(2000,$thn_sekarang,'thn_1',$_POST[thn_1]); 
							}else{	
								echo 	combotgl(1,31,'tgl_1',1);
										combonamabln(1,12,'bln_1',$bln_sekarang);
										combothn(2000,$thn_sekarang,'thn_1',$thn_sekarang);
							}?>
							
							S/D
							<?php 
								if(isset($_POST['report'])){ 
									echo 	combotgl(1,31,'tgl_2',$_POST[tgl_2]);
											combonamabln(1,12,'bln_2',$_POST[bln_2]);
											combothn(2000,$thn_sekarang,'thn_2',$_POST[thn_2]);  
									
								}else{ 
									echo 	combotgl(1,31,'tgl_2',$tgl_skrg);
											combonamabln(1,12,'bln_2',$bln_sekarang);
											combothn(2000,$thn_sekarang,'thn_2',$thn_sekarang);
								}?>
							
							<input type="submit" name="report" class="btn btn-success" value="tampilkan" />
						</form>
                    </div>
				</div>
			</div>
		</div>
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
							<div class="card-header">
								<h5 class="card-title p-0" style="display: inline-block">Data Jurnal Umum</h5>
							</div>
							<div class="card-body mt-3">
								<div class="table-responsive">
									<table
										class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
										<thead>
											<tr>
												<th scope="col">Tanggal</th>
												<th scope="col">Nomor Bukti</th>
												<th scope="col">Kode Rekening</th>
												<th scope="col">Keterangan</th>
												<th scope="col" class="text-center">Debet</th>
												<th scope="col" class="text-center">Kredit</th>
											</tr>
										</thead>
										<tbody>
											<?php
											while($row_tran=mysql_fetch_array($query_transaksi)){
												$debet=$row_tran['debet'];
												$kredit=$row_tran['kredit'];
												
												?>
												<tr class="body">
													<td><div align="center"><?php echo $row_tran['tanggal_transaksi'];?></div></td>
													<td><div><?php echo $row_tran['kode_transaksi'];?></div></td>
													<td><div align="center"><?php echo $row_tran['kode_rekening'];?></div></td>
													<td><?php echo $row_tran['keterangan_transaksi'];?></td>
													<td align="right"><?php echo "Rp&nbsp". number_format($debet,2,'.',','); ?></td>
													<td align="right"><?php echo "Rp&nbsp". number_format($kredit,2,'.',','); ?></td>
												</tr>
												<?php
											}
											?>		
										</tbody>
										<tfoot>
											<tr class="footer">
												<td colspan="4"><div align="center"><strong>TOTAL TRANSAKSI</strong></div></td>
												<td align="right" style="text-align: right"><strong><?php echo "Rp&nbsp". number_format($total['tot_debet'],2,'.',','); ?></strong></td>
												<td align="right" style="text-align: right"><strong><?php echo "Rp&nbsp". number_format($total['tot_kredit'],2,'.',','); ?></strong></td>
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
	case "jurnalpenjualan":
	?>
<!-- DATA TABLE SCRIPTS -->
<script src="assets/js/dataTables/datatables.min.js"></script>
<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
<script>
	$(document).ready(function () {
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
				className: 'text-center',
				targets: [0, 1, 2, 3, 4]
			},
			{
				width: "7%",
				targets: [0]
			}],
		});
	});
</script>

<div class="pagetitle" style="position: relative;">
	<h1>Jurnal Penjualan</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Jurnal</a></li>
			<li class="breadcrumb-item active"><a href="#">Jurnal Penjualan</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Data Jurnal Penjualan</h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table
							class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">ID Transaksi</th>
									<th scope="col">Waktu</th>
									<th scope="col">Total</th>
									<th scope="col">Cetak</th>
								</tr>
							</thead>
							<tbody>
								<?php	
								$no=1;
								$tampil= mysql_query("SELECT * FROM kd_penj order by tanggal desc");
								while($r = mysql_fetch_array($tampil)){
								$total=number_format($r[total], 2,',','.');
								$grandtotal= $grandtotal+$total;
										echo"<tr class='odd gradeX'>
												<td>$no</td>
												<td>$r[kd_pjl]</td>
											<td>$r[tanggal]</td>
											<td>Rp&nbsp$total</td>
											<td>
											<button class='btn btn-warning btn-sm mr-1'><a style='color: white' href=penjualan.$r[kd_pjl]>
												<i class='bi bi-file-earmark-text-fill'></i>
											</a></button>
									</td></tr>";
									$no++;
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<?php
		break;
	case "jurnalpembelian":
	?>
<!-- DATA TABLE SCRIPTS -->
<script src="assets/js/dataTables/datatables.min.js"></script>
<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
<script>
	$(document).ready(function () {
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
				className: 'text-center',
				targets: [0, 1, 2, 4, 5, 6, 7]
			},
			{
				width: "7%",
				targets: [0]
			}],
		});
	});
</script>

<div class="pagetitle" style="position: relative;">
	<h1>Jurnal Pembelian</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Jurnal</a></li>
			<li class="breadcrumb-item active"><a href="#">Jurnal Pembelian</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Data Jurnal Pembelian</h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table
							class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">ID Transaksi</th>
									<th scope="col">No. Faktur</th>
									<th scope="col">Supplier</th>
									<th scope="col">Pembayaran</th>
									<th scope="col">Waktu</th>
									<th scope="col">Total</th>
									<th scope="col">Cetak</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$no = 1;
									$tampil = mysql_query("SELECT * from kd_pemb, supplier where kd_pemb.id_supplier=supplier.id order by kd_pemb.tanggal DESC");
									while ($r = mysql_fetch_array($tampil)) {
										$total = number_format($r[total], 2, ',', '.');
										$grandtotal = $grandtotal + $total;
										echo "
										<tr class='odd gradeX'>
											<td>$no</td>
											<td>$r[kd_pmb]</td>
											<td>$r[nofaktur]</td>
											<td>$r[nm_supplier]</td>
											<td>$r[status]</td>
											<td>$r[tanggal]</td>
											<td>Rp&nbsp$total</td>
											<td>
												<button class='btn btn-warning btn-sm mr-1'><a style='color: white' href=pembelian.$r[kd_pmb]>
													<i class='bi bi-file-earmark-text-fill'></i>
												</a></button>
											</td>
										</tr>";
										$no++;
									}
									?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<?php
		break;
	case "jurnalPenyesuaian":

		//untuk menyelesaikan transaksi
		$p      = new Paging2;
		$batas  = 10;
		$posisi = $p->cariPosisi($batas);
		$sql = mysql_query("select * from rekening where kd_rek between '611' and '666'
					order by kd_rek asc limit $posisi,$batas") or die(mysql_error());
		$total = mysql_fetch_array(mysql_query("select sum(jumlah) as total from rekening where kd_rek between '611' and '666'")) or die(mysql_error());

	?>
	<!-- DATA TABLE SCRIPTS -->
<script src="assets/js/dataTables/datatables.min.js"></script>
<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
<script>
	$(document).ready(function () {
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
					className: 'text-center',
					targets: [0, 2, 3]
				}
			],
		});
	});
</script>

<div class="pagetitle" style="position: relative;">
	<h1>Jurnal Penyesuaian</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Jurnal</a></li>
			<li class="breadcrumb-item active"><a href="#">Jurnal Penyesuaian</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Tabel Penyesuaian</h5>
					<button type="button"
						style="margin-right: 10px; position: absolute; right: 12px; top: 13px; box-shadow: 0 2px 6px #ffc473; background: #ffa426; color: white; font-size: 13px"
						class="btn btn-warning" onclick="window.location.href='tambahbiaya';"><i
							class="fa fa-plus mr-1"></i>
						Tambah Biaya</button>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
							<thead>
								<tr>
									<th scope="col">Kode Rekening</th>
									<th scope="col">Keterangan</th>
									<th scope="col">Masa</th>
									<th scope="col">Jumlah</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while($r=mysql_fetch_array($sql)){
								echo"	<tr>
										<td><div align=\"center\">$r[kd_rek]</div></td>
										<td><div align=\"center\"></div>$r[nama_rekening]</td>
										<td><div align=\"center\">1 BULAN</div></td>
										<td><div align=\"right\">Rp.&nbsp".number_format($r[jumlah],2,'.',',');echo"</div></td>
									</tr>";
									}
								?>	
							</tbody>
							<tfoot>
								<tr class="footer">
									<td colspan="3"><div align="center"><strong>TOTAL Penyesuaian</strong></div></td>
									<td><strong><?php echo 'Rp.&nbsp' . number_format($total['total'],2,'.',','); ?></strong></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
	<?php
		break;
case "lihatpenjualan":
	$query = mysql_query("select kd_penj.kd_pjl,penjualan.id_product,barang.nama,
										penjualan.harga,penjualan.jumlah,penjualan.subtotal
										from penjualan,kd_penj,barang
										where kd_penj.kd_pjl=penjualan.id_transaksi and barang.kode=penjualan.id_product
										and penjualan.id_transaksi='$_GET[id]'") or die(mysql_error());
	$sql = mysql_fetch_array(mysql_query("SELECT * FROM kd_penj WHERE kd_pjl='$_GET[id]'")) or die("query gagal");
	$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal");
	?>
<div class="pagetitle" style="position: relative;">
	<h1>Detail Penjualan</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Jurnal</a></li>
			<li class="breadcrumb-item"><a href="jurnal.penjualan">Jurnal Penjualan</a></li>
			<li class="breadcrumb-item active"><a href="#">Detail Penjualan </a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card p-3">
				<div class="card-header pb-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="d-flex justify-content-between">
								<h3 class="fw-bold" style="color: #6c757d">Detail Penjualan</h3>
								<div style="font-size: 1rem; text-align: end; font-weight: 700; font-family: 'Nunito', 'Segoe UI', arial"><?php echo"<span style='font-size: 20px; color: #6c757d'>Nota : $sql[kd_pjl]</span><br>$sql[tanggal]";?></div>
							</div>
							<div class="row mt-4">
								<div class="col-md-12">
									<address class="row">
										<div class="col-6" style="color: #5e666d">
											<strong>Form:</strong><br>
											<?php echo"<p style='font-size: .9rem; margin-top: 8px; font-family: \"Nunito\"'>$pemilik[nm_perusahaan]<br>$pemilik[alamat]</p>"; ?>
										</div>
										<div class="col-6" style="color: #5e666d">
											<strong>Petugas:</strong><br>
											<?php echo"<p style='font-size: .9rem; margin-top: 8px; font-family: \"Nunito\"'>$sql[user]</p>"; ?>
										</div>
									</address>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body mt-3">
					<div class="row">
						<div class="section-badge">Barang Teroder</div>
						<div class="table-responsive">
							<table class="table table-md">
								<thead>
									<tr style="background: #e4e4e4d9;">
										<th class="text-center">No.</th>
										<th class="text-center">Kode Barang</th>
										<th>Nama</th>
										<th class="text-center">Harga</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no=1;
									while($r=mysql_fetch_row($query)){
										echo "<tr class=body>
												<td align='center'>$no</td>
												<td align='center'>$r[1]</td>
												<td>$r[2]</td>
												<td align='center'>";echo"Rp&nbsp".number_format($r[3],2,',','.');echo"</td>
												<td align='center'>$r[4]</td>
												<td align='center'>";echo"Rp&nbsp".number_format($r[5],2,',','.');echo"</td>
											</tr>";
										$no++;
									}
									?>
								</tbody>
								<tfoot>
									<tr class="footer" style="font-size: 1rem;">
										<td colspan="5"><div align="center"><strong>TOTAL Penyesuaian</strong></div></td>
										<td align="center"><strong><?php echo "Rp&nbsp". number_format($sql['total'],2,'.',','); ?></strong></td>
									</tr>
								</tfoot>
							</table>
						</div>
						<p style="color: red; font-family: 'nunito', arial; font-size: .8rem">*barang yang sudah dibeli tidak bisa dikembalikan</p>
						<div class="d-flex justify-content-between mt-4">
							<button class="btn btn-danger btn-icon icon-left">
								<a href="batalpnj.<?php echo"$_GET[id]";?>" style="color: white">
									<i class="bi bi-x-circle-fill"></i> Batal
								</a>
							</button>
							<button class="btn btn-warning btn-icon icon-left" <?= "onclick=\"window.open('cetakpenjualan.$_GET[id]','Print','menubar=no,navigator=no,width=500,height=450,left=200,top=150,toolbar=no')\";" ?>><i class="bi bi-printer-fill"></i></i> Print</button>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

	<?php
		break;
	case "tambahbiaya":

		?>
	<div class="row">
		<div class="col-lg-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					Tambah Biaya
				</div>


				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div id="pesan"></div>
							<div id="data-rekening"></div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		break;
	case "lihatpembelian":
		$query = mysql_query("select kd_pemb.
							kd_pmb,pembelian.id_product,
							barang.nama,
                            pembelian.harga,
							pembelian.jumlah,pembelian.subtotal,pembelian.ed
                            from pembelian,kd_pemb,barang
                            where kd_pemb.kd_pmb=pembelian.id_transaksi 
							and barang.kode=pembelian.id_product
                            and pembelian.id_transaksi='$_GET[id]'") or die(mysql_error());
		$sql = mysql_fetch_array(mysql_query("SELECT * FROM kd_pemb,supplier WHERE supplier.id=kd_pemb.id_supplier and kd_pemb.kd_pmb='$_GET[id]'")) or die("query data pembelian gagal");
		$rex = mysql_query("SELECT kd_retur.user,sum(kd_retur.total) as total,kd_retur.kd_ret FROM kd_retur,kd_pemb WHERE kd_retur.id_pemb=kd_pemb.kd_pmb and kd_pemb.kd_pmb='$_GET[id]' group by kd_retur.user ") or die(mysql_error());
		$cret = mysql_num_rows($rex);
		$ret = mysql_fetch_array($rex);
		$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal");

		?>
<div class="pagetitle" style="position: relative;">
	<h1>Detail Pembelian</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Jurnal</a></li>
			<li class="breadcrumb-item"><a href="jurnal.pembelian">Jurnal Pembelian</a></li>
			<li class="breadcrumb-item active"><a href="#">Detail Pembelian </a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card p-3">
				<div class="card-header pb-0">
					<div class="row">
						<div class="col-lg-12">
							<div class="d-flex justify-content-between pb-2">
								<div>
									<h3 class="fw-bold" style="color: #6c757d">Detail Pembelian</h3>
									<p style="margin: 0;font-size: .8rem;color: #5e666d;font-weight: 600;"><?= "No. Faktur : $sql[nofaktur]<br>Tanggal Faktur : $sql[tgl_faktur]" ?></p>
								</div>
								<div style="font-size: 1rem; text-align: end; font-weight: 700; font-family: 'Nunito', 'Segoe UI', arial"><?php echo"<span style='font-size: 20px; color: #6c757d'>Nota : $sql[kd_pmb]</span><br>$sql[tanggal]";?></div>
							</div>
							<div class="row mt-4">
								<div class="col-md-12">
									<address class="row">
										<div class="col-6" style="color: #5e666d">
											<strong>Supplier:</strong><br>
											<?php echo"<p style='font-size: .9rem; margin-top: 8px; font-family: \"Nunito\"'>$sql[nm_supplier]<br>$sql[alamat]</p>"; ?>
										</div>
										<div class="col-6" style="color: #5e666d">
											<strong>Billed To:</strong><br>
											<?php echo"<p style='font-size: .9rem; margin-top: 8px; font-family: \"Nunito\"'>$pemilik[nm_perusahaan]<br>$pemilik[alamat]</p>"; ?>
										</div>
									</address>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body mt-3">
					<div class="row">
						<div class="section-badge">Barang Teroder</div>
						<div class="table-responsive">
							<table class="table table-md">
								<thead>
									<tr style="background: #e4e4e4d9;">
										<th class="text-center">No.</th>
										<th class="text-center">Kode Barang</th>
										<th>Nama</th>
										<th class="text-center">Harga</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no=1;
									while($r=mysql_fetch_row($query)){
										echo "<tr class=body>
												<td align='center'>$no</td>
												<td align='center'>$r[1]</td>
												<td>$r[2]</td>
												<td align='center'>";echo"Rp&nbsp".number_format($r[3],2,',','.');echo"</td>
												<td align='center'>$r[4]</td>
												<td align='center'>";echo"Rp&nbsp".number_format($r[5],2,',','.');echo"</td>
											</tr>";
										$no++;
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="row mt-4">
							<div class="col-lg-8">
								<div class="section-badge-success m-0">Metode Pembayaran</div>
								<div class="section-title" style="margin-left: 45px; margin-top: 6px;font-family: 'nunito';color: green;font-weight: 700;font-size: 1.1rem;"><?= "$sql[status]" ?></div>
							</div>
							<div class="col-lg-4 text-right">
								<div class="row">
									<div class="col-6">
										<div class="invoice-detail-item">
											<div class="invoice-detail-name" style="margin-top: .8rem; margin-bottom: .8rem">Total</div>
											<div class="invoice-detail-name" style="margin-top: .8rem; margin-bottom: .8rem">Retur</div>
											<hr class="mt-4 mb-4" style="width: 200%">
											<div class="invoice-detail-name h5">Grand Total</div>
										</div>
									</div>
									<div class="col-6">
										<div class="invoice-detail-item">
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value"><?php echo"Rp&nbsp".number_format($sql['total'],2,'.',','); ?></div>
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value"><?php
											if($cret > 0){
												$grand=$sql[total]-$ret[total];
												echo"Rp&nbsp".number_format($ret[total],2,',','.');echo"</h4>";
												}else{
												echo"Rp&nbsp".number_format($ret[total],2,',','.');echo"</h4>";
													$grand=$sql[total];
												}
										?></div>
											<hr class="mt-4 mb-4" style="opacity: 0;">
											<div style="margin-top: .8rem; margin-bottom: .8rem" class="invoice-detail-value invoice-detail-value-lg"><?php echo"Rp&nbsp".number_format($grand,2,'.',','); ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-between mt-4">
							<?php 
								if($sql[status]=='Tempo'){
									echo"<input type=hidden id='kode' value='$sql[kd_pmb]'>
									<a href='?module=transaksi&act=detailpelunasan&id=$sql[kd_pmb]' style=\"float:right;\" id='lunas' >Pembayaran&nbsp<i class='icon-tags'></i></a>&nbsp";?>
								<?php } else {?>
									<div>
										<button class="btn btn-warning btn-icon icon-left">
											<a href="<?php echo"retur.$_GET[id]";?>" style="color: white">
												<i class="bi bi-arrow-counterclockwise"></i> Retur
											</a>
										</button>
										<button class="btn btn-danger btn-icon icon-left">
											<a href="<?php echo"batalpmb.$_GET[id]";?>" style="color: white">
												<i class="bi bi-x-circle-fill"></i> Batal
											</a>
										</button>
									</div>
									<button class="btn btn-success btn-icon icon-left">
										<a href="<?php echo"batalpmb.$_GET[id]";?>" style="color: white">
											<i class="bi bi-check2-circle"></i> Lunas
										</a>
									</button>
								<?php } ?>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
	<?php
		break;
	case "jurnalhutang":

		//untuk menyelesaikan transaksi
		$p      = new Paging3;
		$batas  = 10;
		$posisi = $p->cariPosisi($batas);

		$query = mysql_query("select * from kd_pemb where status='tempo' limit $posisi,$batas") or die(mysql_error());
		$total = mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where status='tempo' order by tanggal asc"));
		?>
		<!-- DATA TABLE SCRIPTS -->
		<script src="assets/js/dataTables/datatables.min.js"></script>
		<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
		<script>
			$(document).ready(function () {
				$('.datatable-jquery').dataTable({
					sDom: 'lBfrtip',
					columnDefs: [{
						className: 'text-center',
						targets: [0, 1, 2, 3, 4, 5]
					},
					{
						width: "7%",
						targets: [0]
					}],
				});
			});
		</script>

		<div class="pagetitle" style="position: relative;">
			<h1>Laporan Hutang</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Laporan</a></li>
					<li class="breadcrumb-item active"><a href="#">Laporan Hutang</a></li>
				</ol>
			</nav>
		</div><!-- End Page Title -->

		<section class="section">
			<div class="row">
				<div class="col-lg-12">

					<div class="card">
						<div class="card-header">
							<h5 class="card-title p-0" style="display: inline-block">Data Hutang</h5>
						</div>
						<div class="card-body mt-3">
							<div class="table-responsive">
								<table
									class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
									<thead>
										<tr>
											<th scope="col">Tanggal</th>
											<th scope="col">Nomor Transaksi</th>
											<th scope="col">No. Faktur</th>
											<th scope="col">Kode Rekening</th>
											<th scope="col">Keterangan</th>
											<th scope="col">Total</th>
											<th scope="col">Detail</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while($row_tran=mysql_fetch_array($query)){
											?>
											<tr class="body">
												<td><div align="center"><?php echo $row_tran['tanggal'];?></div></td>
												<td><div align="center"><?php echo $row_tran['kd_pmb'];?></div></td>
												<td><div align="center"><?php echo $row_tran['nofaktur'];?></div></td>
												<td>211</td>
												<td align="center">Hutang Dagang</td>
												<td align="right"><?php echo "Rp.&nbsp;" . number_format($row_tran['total'],2,'.',','); ?></td>
												<td><?php echo"<a href=hutang.$row_tran[kd_pmb]><span>Detail</span>"?></td>
											</tr>
											<?php
										}
										?>
									</tbody>
									<tfoot>
										<tr class="footer">
											<td style="background: rgb(221, 255, 221)" colspan="5"><div align="center"><strong>TOTAL TRANSAKSI</strong></div></td>
											<td style="background: rgb(221, 255, 221)" align="right"><strong><?php echo "Rp.&nbsp;" . number_format($total['total'],2,'.',','); ?></strong></td>
											<td></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
	<?php 
	break;
case "jurnalretur":
	?>
<!-- DATA TABLE SCRIPTS -->
<script src="assets/js/dataTables/datatables.min.js"></script>
<!-- <script src="assets/js/dataTables/datatables.min.js"></script> -->
<script>
	$(document).ready(function () {
		$('.datatable-jquery').dataTable({
			sDom: 'lBfrtip',
			columnDefs: [{
				className: 'text-center',
				targets: [0, 1, 2, 3, 4, 5]
			},
			{
				width: "7%",
				targets: [0]
			}],
		});
	});
</script>

<div class="pagetitle" style="position: relative;">
	<h1>Laporan Retur Pembelian</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Laporan</a></li>
			<li class="breadcrumb-item active"><a href="#">Laporan Retur Pembelian</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Data Retur Pembelian</h5>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table
							class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">ID Retur</th>
									<th scope="col">ID Pembelian</th>
									<th scope="col">Waktu</th>
									<th scope="col">Total</th>
									<th scope="col">Cetak</th>
								</tr>
							</thead>
							<tbody>
								<?php	
									$no=1;
									$tampil= mysql_query("SELECT * FROM kd_retur order by tanggal desc");
									while($r = mysql_fetch_array($tampil)){
									$total=number_format($r[total], 2,',','.');
									$grandtotal= $grandtotal+$total;
									echo"
									<tr class='odd gradeX'>
										<td>$no</td>
										<td>$r[kd_ret]</td>
										<td>$r[id_pemb]</td>
										<td>$r[tanggal]</td>
										<td>Rp&nbsp$total</td>
										<td>
											<button class='btn btn-warning btn-sm mr-1'><a style='color: white' href=dataretur.$r[kd_ret]>
												<i class='bi bi-file-earmark-text-fill'></i>
											</a></button>
										</td>
									</tr>";
									$no++;
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<?php
	break;
case "retur":
	?>
<script>
	var thoudelim = ".";
	var decdelim = ",";
	var curr = "Rp ";
	var d = document;


	function format(s, r) {
		s = Math.round(s * Math.pow(10, r)) / Math.pow(10, r);
		s = String(s);
		s = s.split(".");
		var l = s[0].length;
		var t = "";
		var c = 0;
		while (l > 0) {
			t = s[0][l - 1] + (c % 3 == 0 && c != 0 ? thoudelim : "") + t;
			l--;
			c++;
		}
		s[1] = s[1] == undefined ? "0" : s[1];
		for (i = s[1].length; i < r; i++) {
			s[1] += "0";
		}
		return curr + t + decdelim + s[1];
	}

	function calc(t, rel, price, ref) {
		if (t.value == "") {
			t.value = "0";
		}
		if (isNaN(t.value)) {
			t.value = t.value.substr(0, t.value.length - 1);
		} else {
			t.value = parseFloat(t.value);
			var ot = d.getElementById("total");
			var os = d.getElementById("sub" + rel);
			var old_total = ot.getAttribute("price") - os.getAttribute("price");
			var new_sub = parseFloat(t.value) * parseFloat(price);

			var y = d.getElementById("stok" + rel);
			var yp = parseFloat(y.getAttribute("rel")); //stok minim
			var pp = parseFloat(y.getAttribute("ref")); //stok
			var xp = parseInt(t.value);
			var x2p = pp - xp;
			if (xp > pp) {
				alert("Retur melebihi stok");
				form.xp.focus();
				return false;
			} else if (xp < 1) {
				alert("tidak boleh 0");
				return false;
			} else if (x2p < yp) {
				alert("Retur menyisakan kurang dari stok minim");
				return false;

			} else if (x2p <= yp) {
				alert("Retur akan menyisakan stok minim");
				return false;
			}

			os.setAttribute("price", new_sub);
			os.innerHTML = format(new_sub, 2);
			ot.setAttribute("price", old_total + new_sub);
			ot.innerHTML = format(old_total + new_sub, 2);
		}
	}
</script>
<div class="row">
				<div class="col-lg-9">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php
									$query = mysql_query("select kd_pemb.kd_pmb,pembelian.id_product,barang.nama,
								pembelian.harga,pembelian.jumlah,pembelian.subtotal,barang.stok
								from pembelian,kd_pemb,barang
								where kd_pemb.kd_pmb=pembelian.id_transaksi and barang.kode=pembelian.id_product
								and pembelian.id_transaksi='$_GET[id]'") or die(mysql_error());
									$data = mysql_fetch_array(mysql_query("SELECT * FROM kd_pemb,supplier WHERE supplier.id_supplier=kd_pemb.id_supplier and kd_pemb.kd_pmb='$_GET[id]'")) or die(mysql_error());

									$tgl = date('d-m-Y');
									$date = date('Ymd');
									$initial = "RTR";
									$auto = mysql_query("select * from retur order by id desc limit 1");
									$no = mysql_fetch_array($auto);
									$angka = $no['id'] + 1;

									echo "
<form name='trans_pemb' method='POST' action='../apotek/modul/mod_jurnal/aksi_jurnal.php?module=transaksi&act=retur&input=simpan&kode=$initial$angka$date&id=$_GET[id]'>"; ?>
							<input type="button" class="btn" data-toggle="modal" data-target="#penjualan"
								value="Tambah" />
							<?php


			$s = session_id();
			$z = $sql = mysql_query("SELECT * FROM keranjang, barang WHERE id_session='$s' AND keranjang.id_product=barang.kode AND transaksi='retur' ORDER BY id_keranjang") or die(mysql_error());
			$cek = mysql_num_rows($z);

			if ($cek > 0) {
				echo "
<input class='btn'type='submit' value='Simpan'  Onclick=\"return confirm('Apakah Anda yakin akan menyimpan transaksi ini?')\" >";
			} else {
				echo "<input class='btn' type='button' value='Simpan' Onclick=\"alert('Tambahkan data barang dahulu')\" >";
			}
			if ($cek > 0) {
				echo " 
<a href=\"../apotek/modul/mod_jurnal/aksi_jurnal.php?module=transaksi&act=retur&input=hapus&id=$_GET[id]\" Onclick=\"return confirm('Apakah Anda yakin akan membatalkan transaksi ini?')\">
<input class='btn'type='button' value='Batal'></a>
";
			} else {
				echo "

";
			}

			?>
</div>
<div class="panel-heading">

</div>
<div class="panel-body">
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Barang</th>
									<th>satuan</th>
									<th>No. Bacth</th>
									<th>Stok</th>
									<th>Jumlah</th>
									<th>Harga</th>
									<th>Kadaluarsa</th>
									<th>Subtotal</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>

								<?php
	$sid = session_id();
	$no = 1;
	$x = mysql_query("SELECT keranjang.id_product,keranjang.qty,keranjang.id_keranjang,barang.kode,barang.nama,barang.satuan,pembelian.nobacth,barang.stok,barang.stok_minim,pembelian.harga,pembelian.ed
FROM keranjang, pembelian, barang WHERE id_session='$sid' AND keranjang.id_product=barang.kode AND pembelian.id_product=barang.kode AND pembelian.id_transaksi='$_GET[id]' AND transaksi='retur' ORDER BY id_keranjang") or die(mysql_error());
	$cek = mysql_num_rows($x);
	while ($q = mysql_fetch_array($x)) {
		$hrg = $q[harga];
		$jml = $q[qty];
		$subtotal = $hrg * $jml;
		$total = $total + $subtotal;
		//start of isi
		echo "
			<td>$no</td>
			<td>$q[nama]</td>
			<td align='center'>$q[satuan]</td>
			<td align='center'><input type='text' name='bacth[$q[kode]]' value='$q[nobacth]' size='5'></td>
			<td  id=\"stok$no\"ref=\"$q[stok]\" rel=\"$q[stok_minim]\" >$q[stok]</td>
			<td><input class=\"jml\" style=\"background:#fff;\"  type=\"text\" name=\"qty[$q[kode]]\" size=\"1\" value=\"$jml\" onkeyup=\"calc(this,'$no',$hrg);\"></td>
			<td>" . number_format($q[harga], 2, ',', '.');
																				echo "</td>
			<td align='center'><input type='text' name='ed[$q[kode]]' value='$q[ed]' size='8' ></td>
			<td style=\"background:rgb(221, 255, 221);\" id=\"sub$no\"  price=\"$hrg\">" . number_format($subtotal, 2, ',', '.');
																				echo "</td>
			<td align='center'><a href=\"../apotek/modul/mod_jurnal/aksi_jurnal.php?module=transaksi&act=retur&input=delete&id=$_GET[id]&kd=$q[id_keranjang]&kode=$q[id_product]\" Onclick=\"return confirm('Apakah Anda yakin akan menghapus $q[nama]?')\">
					<span><i class='fa fa-pencil'></i></a></td><tr>
					</tr>";
			$no++;
		}

		?>

													</tbody>
													<tfoot>
														<tr>
															<th style="text-align:right" colspan="8">Total
															</th>
															<?php echo "<th id='total' price='$total' style=\"background:rgb(221, 255, 221);\">Rp&nbsp" . number_format($total, 2, ',', '.');
																	echo "</th>"; ?>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							Transaksi Retur
						</div>
						<div class="panel-heading">

						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">

									<div class="form-group">

										<label>Supplier</label> <input type="text"
											value="<?php echo "$data[nm_supplier]"; ?>"
											placeholder="Supplier" class="form-control" disabled>
										<label>No. Faktur</label> <input type="text"
											value="<?php echo "$data[nofaktur]"; ?>"
											placeholder="No. Faktur" class="form-control" disabled>
										<label>Tanggal Faktur</label> <input type="text"
											placeholder="YYYY-MM-DD"
											value="<?php echo "$data[tgl_faktur]"; ?>" class="form-control"
											disabled>
										<label>No. Nota Retur</label> <input type="text"
											value="<?php echo "$initial$angka$date"; ?>"
											class="form-control" disabled>
										<label>Tanggal</label> <input type="text"
											value="<?php echo "$tgl"; ?>" class="form-control" disabled>
										<br>
										<?php
												if ($data['status'] == 'Tunai') {
													echo "
		<label class='checkbox-inline'>
		<input name='status' id='f' type='radio' value='Tunai' checked/>
		</label><label>Tunai</label>
		
		<label class='checkbox-inline'>			
		<input name='status' id='t' type='radio' value='Tempo' disabled/>
		</label><label>Tempo</label>
		";
												} else {
													echo "
		<label class='checkbox-inline'>
		<input name='status' id='f' type='radio' value='Tunai' disabled/>
		</label><label>Tunai</label>
		
		<label class='checkbox-inline'>			
		<input name='status' id='t' type='radio' value='Tempo' checked/>
		</label><label>Tempo</label>";
												}
												?>
										<label>Jatuh Tempo</label>
										<input type="text" name="tgl_tempo" value="" class="form-control"
											disabled>
									</div>
								</div>
							</div>
							</form>
							<div class="modal fade" id="penjualan" tabindex="-1" role="dialog"
								aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"
												aria-hidden="true">&times;</button>
											<h4 class="modal-title" id="myModalLabel">Tambahkan Item Barang
											</h4>
										</div>
										<div class="modal-body">
											<?php
													echo "
										<div class='table-responsive'>
					<table class='table table-hover'>
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama</th>
			<th>Harga</th>
			<th>Stok</th>
			<th>Jumlah</th>
			<th>Subtotal</th>
			<th>Action</th>
		</tr>
	</thead>";
													while ($r = mysql_fetch_row($query)) {
														echo "<tr class=body>
								<td align='center'>$r[1]</td>
								<td>$r[2]</td>
								<td>";
														echo "Rp&nbsp" . number_format($r[3], 2, ',', '.');
														echo "</td>
								<td align='center'>$r[6]</td>
								<td align='center'>$r[4]</td>
								<td>";
														echo "Rp&nbsp" . number_format($r[5], 2, ',', '.');
														echo "</td>
								<td align='center'>
			<a href='../apotek/modul/mod_jurnal/aksi_jurnal.php?module=transaksi&act=retur&input=add&kode=$r[1]&id=$_GET[id]&harga=$r[3]'>
<i class='fa fa-plus'></i></a></td>
							</tr>";
													}
													echo "</table>
								</div>	";
													?>
										</div>
										<div class="modal-footer">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
						<?php
							break;
						case "lihatretur":
							$query = mysql_query("select barang.kode,barang.nama,retur.nobacth,retur.ed,retur.jumlah,retur.harga 
					from barang,kd_retur,retur where barang.kode=retur.id_product and
					kd_retur.kd_ret=retur.id_transaksi and kd_retur.kd_ret='$_GET[id]'") or die(mysql_error());
							$sql = mysql_fetch_array(mysql_query("SELECT * FROM kd_retur,kd_pemb,supplier WHERE kd_pemb.id_supplier=supplier.id_supplier and kd_retur.id_pemb=kd_pemb.kd_pmb and 
						kd_ret='$_GET[id]'")) or die("query gagal");
							$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal");

							?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<?php
												echo "
								<a href=pembelian.$sql[id_pemb]>Detail Pembelian</a>
				<a style=\"float:right;\" 
				onclick=\"window.open('cetakretur.$_GET[id]','Print','menubar=no,navigator=no,width=700,height=450,left=200,top=150,toolbar=no')\";><i class='fa fa-print'></i></a>&nbsp

							";
												?>
									</div>
									<div class="panel-body">
										<div class="panel-group" id="accordion">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="panel-title">
														<a data-toggle="collapse" data-parent="#accordion"
															href="#collapseOne" class="collapsed">
															<?php echo "<p>$pemilik[nm_perusahaan]<br>
													$pemilik[alamat]</p>"; ?>
														</a>
													</h4>
												</div>

											</div>
											<div class="panel panel-default">
												<div class="panel-heading">
													<h4 class="panel-title">
														<div class="row">
															<div class="col-md-6">
																<?php echo "
											Nota : $sql[kd_ret]<span style='margin-left:10px;'></span>$sql[0]";
																		?>
															</div>

															<div class="col-md-6">
																<?php echo "Pembayaran : $sql[status]<span style='margin-left:10px;'></span><br>
											Nota : $sql[kd_pmb]<span style='margin-left:10px;'></span>
											<br>Tanggal Transaksi: $sql[tanggal]"; ?>
															</div>
														</div>
													</h4>
												</div>
												<div id="collapseTwo" class="panel-collapse in" style="height: auto;">
													<div class="panel-body">
														<div class="panel-body">
															<div class="table-responsive table-bordered">
																<table class="table">
																	<thead>
																		<tr>
																			<th>No.</th>
																			<th>Kode Barang</th>
																			<th>Nama</th>
																			<th>NoBacth</th>
																			<th>ED</th>
																			<th>Harga</th>
																			<th>Jumlah</th>
																			<th>Subtotal</th>
																		</tr>
																	</thead>
																	<?php
																			$no = 1;
																			while ($r = mysql_fetch_row($query)) {
																				$h = $r[5] * $r[4];
																				echo "<tr class=body>
											<td align='center'>$no</td>
                                            <td align='center'>$r[0]</td>
                                            <td>$r[1]</td>
											<td align='center'>$r[2]</td>
											<td align='center'>$r[3]</td>
											<td>";
																				echo "Rp&nbsp" . number_format($r[5], 2, ',', '.');
																				echo "</td>
                                            <td>";
																				echo "Rp&nbsp" . number_format($r[4], 2, ',', '.');
																				echo "</td>
                                            <td>";
																				echo "Rp&nbsp" . number_format($h, 2, ',', '.');
																				echo "</td>
                                        </tr>";
																				$no++;
																			}
																			?>

																	<tr class="footer">

																		<td colspan="6">
																			<div align="right"><strong>Total</strong>
																			</div>
																		</td>
																		<td><strong><?php echo "Rp&nbsp" . number_format($sql[3], 2, '.', ','); ?></strong>
																		</td>
																	</tr>


																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="panel panel-default">
												<div class="panel-heading">
													<?php echo "
											<p style='font-size:12pt;>* faktur pembelian<br><i class='icon-user'></i>Petugas :$sql[user] |<i class='icon-user'></i>Petugas Retur :$sql[user] |";
															if ($cret > 0) {
																echo "<a href='dataretur.$ret[kd_ret]'>Detail Retur</a>";
															} else {
																echo "Detail Retur";
															}
															echo "<p>";
															?>

												</div>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
							break;
						case "batalpnj":
							?>
						<script>
							var thoudelim = ".";
							var decdelim = ",";
							var curr = "Rp ";
							var d = document;


							function format(s, r) {
								s = Math.round(s * Math.pow(10, r)) / Math.pow(10, r);
								s = String(s);
								s = s.split(".");

								var l = s[0].length;
								var t = "";
								var c = 0;

								while (l > 0) {
									t = s[0][l - 1] + (c % 3 == 0 && c != 0 ? thoudelim : "") + t;
									l--;
									c++;
								}
								s[1] = s[1] == undefined ? "0" : s[1];
								for (i = s[1].length; i < r; i++) {
									s[1] += "0";
								}
								return curr + t + decdelim + s[1];
							}

							function calc(t, rel, price, ref) {
								if (t.value == "") {
									t.value = "0";
								}
								if (isNaN(t.value)) {
									t.value = t.value.substr(0, t.value.length - 1);
								} else {
									t.value = parseFloat(t.value);
									var ot = d.getElementById("total");
									var os = d.getElementById("sub" + rel);
									var old_total = ot.getAttribute("price") - os.getAttribute("price");
									var new_sub = parseFloat(t.value) * parseFloat(price);

									var y = d.getElementById("stok" + rel);
									var yp = parseFloat(y.getAttribute("rel"));
									var pp = parseFloat(y.getAttribute("ref"));
									var xp = parseInt(t.value);
									var x2p = pp - xp;
									if (xp > pp) {
										alert("Penjualan melebihi stok");
										return false;
									} else if (xp < 1) {
										alert("tidak boleh 0");
										return false;
									} //else if(x2p < yp){
									// alert("penjualan menyisakan kurang dari stok minim");
									// return false;
									//}   else if(x2p <= yp){
									// alert("penjualan akan menyisakan stok minim");
									// return false;
									// }

									os.setAttribute("price", new_sub);
									os.innerHTML = format(new_sub, 2);
									ot.setAttribute("price", old_total + new_sub);
									ot.innerHTML = format(old_total + new_sub, 2);
								}
							}

							function hitung() {
								var ttlbelanja = document.getElementById("totalbelanja").value;
								var tendered = document.getElementById("uang").value;
								var kembali = tendered - ttlbelanja;
								if (kembali < 0) {
									kembali = "Uang Belum Cukup";
								}
								var cash = document.getElementById("kembali").innerHTML = kembali;
							}

							function uangotomatis() {
								var ttlbelanja = document.getElementById("totalbelanja").value;
								var kembali = document.getElementById("uang").value = ttlbelanja;
								document.getElementById("kembali").innerHTML = format(kembali, 2);
							}

							function beli() {
								var hrg = parseInt(documen.getElementById("harga").value);
								var jml = parseInt(documen.getElementById("jml").value);
								var subtotal = hrg * jml;
								if (hrg < 0) {
									alert("tidak boleh nol");
									return false;
								}
								if (jml < 0) {
									alert("tidak boleh nol");
									return false;
								}
								var sub = document.getElementById("subtotal").innerHTML = subotal;
							}
						</script>

						<div class="row">
							<div class="col-lg-9">
								<div class="panel panel-default">
									<div class="panel-heading">
										<?php
												$DataQuery = mysql_query("SELECT * FROM  kd_penj where kd_pjl = '$_GET[id]'") or die("GAGAL Mengambil Data ID Transaksi");
												$DataField = mysql_fetch_array($DataQuery);
												echo "
		 <form name='trans_penj' method='POST' action=\"../apotek/modul/mod_jurnal/aksi_jurnal.php?module=transaksi&act=batalpnj&input=simpan&kode=$_GET[id]\";>";
												?>
										<!--<input type="button" class="btn" data-toggle="modal" data-target="#penjualan" value="Tambah"/>-->
										<?php
												$no = 1;
												$x = $sql = mysql_query("SELECT * FROM penjualan, barang WHERE penjualan.id_product=barang.kode AND id_transaksi = '$_GET[id]'") or die(mysql_error());

												echo "			
			<input class='btn'type='submit' value='Simpan'  Onclick=\"return confirm('Apakah Anda yakin akan menyimpan transaksi ini?')\" >";

												?>
									</div>
									<div class="panel-heading">

									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<div class="panel-body">
														<div class="table-responsive">
															<table class="table table-striped">
																<thead>
																	<tr>
																		<th>No.</th>
																		<th>Item</th>
																		<th>Satuan</th>
																		<th>Stok</th>
																		<th>Jumlah</th>
																		<th>Jumlah Baru</th>
																		<th>Harga</th>
																		<th>Subtotal</th>
																		<th>Aksi</th>
																	</tr>
																</thead>
																<tbody>

																	<?php
																			while ($q = mysql_fetch_array($x)) {
																				$hrg = $q[harga];
																				$jml = 1;
																				$subtotal = $hrg * $jml;
																				$total = $total + $subtotal;
																				echo "
										<tr>
										<td>$no</td>
										<td>$q[nama]</td>
										<td align='center'>$q[satuan]</td>
										<td  id=\"stok$no\"ref=\"$q[stok]\" rel=\"$q[stok_minim]\" >$q[stok]</td>
										<td>$q[jumlah]</td>
										<td><input class=\"form-control\" class=\"jml\" style=\"background:#fff;\"  type=\"text\" name=\"qty[$q[kode]]\" size=\"1\"value=\"$jml\" onkeyup=\"calc(this,'$no',$hrg);\"></td>
										<td>" . number_format($q[hrg_jual], 2, ',', '.');
																				echo "</td>
										<td style=\"background:rgb(221, 255, 221);\" id=\"sub$no\"  price=\"$hrg\">" . number_format($subtotal, 2, ',', '.');
																				echo "</td>
										<td align='center'><a href=\"delp.$q[id_keranjang]&delp.$q[kode]\" Onclick=\"return confirm('Apakah Anda yakin akan menghapus $q[nama]?')\">
										<span><i class='fa fa-pencil'></i></a></td>
										</tr>";
																				$no++;
																			}

																			?>

																</tbody>
																<?php
																		echo "<tfooter>
										<td align='right' colspan='7' style=\"background:rgb(221, 255, 221); padding: 0.25em;\"><b>Total<b></td>
										<td id=\"total\" price=\"$total\" style=\"background:rgb(221, 255, 221);\">Rp&nbsp" . number_format($total, 2, ',', '.');
																		echo "
										</td><td></td></tfoter>";
																		?>
															</table>
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>


							<div class="col-lg-3">
								<div class="panel panel-default">
									<div class="panel-heading">
										Transaksi Penjualan
									</div>
									<div class="panel-heading">

									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12">

												<div class="form-group">
													<label>Dokter</label>
													<select name="nm_dokter" class="form-control">
														<?php
																$tampil = mysql_query("SELECT * FROM dokter ORDER BY nm_dokter") or die(mysql_error());
																if ($DataField[dokter] == 0) {
																	echo "<option value=0 selected>- Pilih Dokter-</option>";
																}
																while ($r = mysql_fetch_array($tampil)) {
																	if ($DataField[dokter] == $r[id_dokter]) {
																		echo "<option value=$r[id_dokter] selected>$r[nm_dokter]</option>";
																	} else {
																		echo "<option value=$r[id_dokter]>$r[nm_dokter]</option>";
																	}
																}
																echo "</select>"; ?>
												</div>
											</div>
										</div>
										</form>
										<div class="row">
											<div class="col-lg-12">

												<div class="form-group">
													<label>No. Nota</label> <input type="text"
														value="<?php echo "$DataField[kd_pjl]"; ?>" class="form-control"
														disabled>
													<label>Tanggal</label> <input type="text"
														value="<?php echo "$DataField[tanggal]"; ?>"
														class="form-control" disabled>
												</div>
											</div>
										</div>
										<div class="modal fade" id="penjualan" tabindex="-1" role="dialog"
											aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal"
															aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Tambahkan Item Barang
														</h4>
													</div>
													<div class="modal-body">
														<?php
																$pg = '';
																if (!isset($_GET['pg'])) {
																	include('modul/mod_transaksi/form.php');
																} else {
																	$pg = $_GET['pg'];
																	$mod = $_GET['mod'];
																	include $mod . '/' . $pg . ".php";
																}
																?>
													</div>
													<div class="modal-footer">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
							break;
					}
						?>