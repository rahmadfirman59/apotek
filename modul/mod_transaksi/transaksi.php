<?php
$aksi = "modul/mod_transaksi/aksi_transaksi.php";
switch ($_GET[act]) {
	default:

		break;
	case "trans_penj":
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

			function validasi(form) {

				if (form.nm_dokter.value == "") {
					alert("Anda belum memilih nama dokter");
					return (false);
				}
				return (true);
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
						t.value = '';
						return false;
					} else if (xp < 1) {
						alert("tidak boleh 0");
						return false;

					}

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

<div class="pagetitle" style="position: relative;">
	<h1>Transaksi Penjualan</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Transaksi</a></li>
			<li class="breadcrumb-item active"><a href="#">Transaksi Penjualan</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header d-flex">
					<?php
					$tgl=date('d-m-Y');
					$date=date('Ymd');
					$initial="PJL";
					$auto=mysql_query("select * from penjualan order by id desc limit 1");
					$no=mysql_fetch_array($auto);
					$angka=$no['id']+1;
					
					$sid = session_id();
					$no = 1;
					$x=$sql=mysql_query("SELECT * FROM keranjang, barang WHERE id_session='$sid' AND keranjang.id_product=barang.kode AND transaksi='jual' ORDER BY id_keranjang") or die(mysql_error());
					$cek = mysql_num_rows($x);
					echo"
					<form name='trans_penj' method='POST' style='width: 100%'  action='$aksi?module=transaksi&act=trans_penj&input=simpan&kode=$initial$angka$date' Onsubmit=\"return validasi(this)\">";
						?>				
					<button type='button'
						style='margin-right: 10px;'
						class='btn btn-warning' value='simpan' data-toggle="modal" data-target="#penjualan" value="Tambah"><i
							class='fa fa-plus mr-1'></i>
						Tambah Barang</button>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table class="table table-striped table-hover datatable-primary datatable-jquery table-center" width="100%" style="font-size: .8rem;">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">Item</th>
									<th scope="col">Satuan</th>
									<th scope="col">Stok</th>
									<th scope="col">Jumlah</th>
									<th scope="col">Harga</th>
									<th scope="col">Subtotal</th>
									<th scope="col" style="width: 15%;">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
									while($q=mysql_fetch_array($x)){
									$hrg = $q[hrg_jual];
									$jml = $q[qty];
									$subtotal = $hrg * $jml;
									$total = $total+$subtotal;
								echo"
									<tr>
									<td>$no</td>
									<td>$q[nama]</td>
									<td align='center'>$q[satuan]</td>
									<td  id=\"stok$no\"ref=\"$q[stok]\" rel=\"$q[stok_minim]\" >$q[stok]</td>
									<td><input class=\"form-control\" class=\"jml\" style=\"background:#fff;\"  type=\"text\" name=\"qty[$q[kode]]\" size=\"1\"value=\"$jml\" onkeyup=\"calc(this,'$no',$hrg);\"></td>
									<td>".number_format($q[hrg_jual],2,',','.');echo"</td>
									<td style=\"background:rgb(221, 255, 221);\" id=\"sub$no\"  price=\"$hrg\">".number_format($subtotal,2,',','.');echo"</td>
									<td align='center'>
										<a href=\"delp.$q[id_keranjang]&delp.$q[kode]\" class='btn btn-danger' style='color: white' Onclick=\"return confirm('Apakah Anda yakin akan menghapus $q[nama]?')\"><i class='bi bi-trash-fill'></i></a>
									</td>
									</tr>";
										$no++;
								}
									
								?>
							</tbody>
							<tfooter>
								<?php
								echo"
									<td align='right' colspan='6' style=\"background:rgb(221, 255, 221); padding: 0.25em;\"><b>Total<b></td>
									<td id=\"total\" price=\"$total\" style=\"background:rgb(221, 255, 221);\">Rp&nbsp".number_format($total,2,',','.');echo"
									</td><td></td>";
								?>
							</tfooter>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Kasir</h5>
				</div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Total Belanja</label>
                                <input type="text" name="totalbelanja" id="totalbelanja" onkeyup="uangotomatis()" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Uang</label>
                                <input type="text" id="uang" name="uang" onkeyup="hitung()" class="form-control">
                            </div>
                        </div>
						<div class="d-flex fw-bold" style="font-size: 14px; padding-top: 15px;border-top: 1px solid #d8d1d1;margin-top: 10px;">
							<label>Kembali</label>
							<span id="kembali" style='margin-left:10px'><span style='margin-left:15px'></span>Rp.0</span>
						</div>
                    </div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Transaksi Penjualan</h5>
				</div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Dokter</label>
                                <select name="nm_dokter" class="form-control">
								<option value="" selected>- Pilih Dokter -</option>
								<?php
									$tampil=mysql_query("SELECT * FROM dokter ORDER BY nm_dokter");
									while($r=mysql_fetch_array($tampil)){
									echo "<option value=$r[id_dokter]>$r[nm_dokter]</option>";
									}echo"</select>";?>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>No. Nota</label>
                                <input type="text" value="<?php echo"$initial$angka$date";?>" class="form-control" disabled>
                            </div>
                        </div>
						<div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" value="<?php echo"$tgl";?>" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
			<div class="d-flex">
			<?php
				if($cek > 0){ 
				echo"
				<button type='submit'
					style='width: 100%'
					class='py-2 btn btn-success' value='Simpan' Onclick=\"alert('Apakah Anda yakin akan menyimpan transaksi ini?')\"'>
					Simpan</button>";	
				} else {
				echo "
				<button type='button'
					style='width: 100%'
					class='py-2 btn btn-success' value='Simpan' Onclick=\"alert('Tambahkan data barang dahulu')\"'>
					Simpan</button>";	
				} 		
				echo"</form>"
			?>
			</div>
		</div>
	</div>
</section>
<div class="modal fade" role="dialog" id="penjualan" tabindex="-1" data-keyboard="false" data-backdrop="static">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog modal-lg vertical-align-center" role="document">
			<div class="modal-content">
				<div class="modal-header br">
					<h5 class="modal-title">Tambahkan Item Barang </h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<?php
						$pg = '';
						if(!isset($_GET['pg'])) {
							include ('modul/mod_transaksi/form.php');
						}	else {
							$pg = $_GET['pg'];
							$mod = $_GET['mod'];
							include $mod . '/' . $pg . ".php";
						}		
							?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<?php
		break;
	case "trans_pemb":
	?>
		<script>
			function calc(no) {
				var jumlah = $('#jumlah' + no).val();
				var harga = $('#harga' + no).val();
				var subTotal = jumlah * harga;
				var disk = $('#potongan').val();
				$('#sub' + no).html(subTotal);

				var total = 0;
				if (disk != "") {
					jQuery.each($('.subtotal'), function(indexArr, element) {
						console.log(element.id);
						var subtotal = $('#' + element.id).html();
						total += parseInt(subtotal);
						pot = total - parseInt(disk);
					});
					$('#total').html(pot);
				} else {
					jQuery.each($('.subtotal'), function(indexArr, element) {
						console.log(element.id);
						var subtotal = $('#' + element.id).html();
						total += parseInt(subtotal);
						pot = total + parseInt(disk);
					});
					$('#total').html(pot);
				}
			}
		</script>
	<div class="pagetitle" style="position: relative;">
		<h1>Transaksi Pembelian</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Transaksi</a></li>
				<li class="breadcrumb-item active"><a href="#">Transaksi Pembelian</a></li>
			</ol>
		</nav>
	</div><!-- End Page Title -->

	<section class="section">
		<div class="row">
			<div class="col-lg-9">
				<div class="card">
					<div class="card-header d-flex">
						<?php
							$s = session_id();
							$z=$sql=mysql_query("SELECT * FROM keranjang, barang WHERE id_session='$s' AND keranjang.id_product=barang.kode AND transaksi='beli' ORDER BY id_keranjang") or die(mysql_error());
							$tgl=date('d-m-Y');
							$date=date('Ymd');
							$initial="PMB";
							$auto=mysql_query("select * from pembelian order by id_transaksi desc limit 1");
							$no=mysql_fetch_array($auto);
							$angka=$no['id']+1;
							echo"
							<form name='trans_pemb' method='POST' action=\"$aksi?module=transaksi&act=trans_pemb&input=simpan&kode=$initial$angka$date\";>";
							?>				
						<button type='button'
							style='margin-right: 10px;'
							class='btn btn-warning' value='simpan' data-toggle="modal" data-target="#penjualan" value="Tambah"><i
								class='fa fa-plus mr-1'></i>
							Tambah Barang</button>
					</div>
					<div class="card-body mt-3">
						<div class="table-responsive">
							<table class="table table-striped table-hover datatable-primary datatable-jquery table-center" width="100%" style="font-size: .8rem;">
								<thead>
									<tr>
										<th scope="col">No.</th>
										<th scope="col" style="width: 20%;">Nama Barang</th>
										<th scope="col">Satuan</th>
										<th scope="col">No. Batch Dirubah</th>
										<th scope="col">Jumlah</th>
										<th scope="col">Harga</th>
										<th scope="col">Kadaluarsa</th>
										<th scope="col">Subtotal</th>
										<th scope="col" style="width: 10%;">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sid = session_id();
									$no = 1;
									$x=$sql=mysql_query("SELECT * FROM keranjang, barang WHERE id_session='$sid' AND keranjang.id_product=barang.kode AND transaksi='beli' ORDER BY id_keranjang") or die(mysql_error());
									$cek = mysql_num_rows($x);
									while($q=mysql_fetch_array($x)){
									//start of isi
									echo"
									<td>$no</td> 
									<td>$q[nama]</td>
									<td align='center'>$q[satuan]</td>
									<td align='center'><input type='text' name=\"bacth[$q[kode]]\" class=\"form-control\" value=''/></td>
									<td><input id=\"jumlah$no\" class=\"form-control\" type=\"text\" name=\"qty[$q[kode]]\" onkeyup=\"calc($no);\"size=\"1\"value=\"1\"></td>
									<td><input type='text' class=\"form-control\" id=\"harga$no\" size='12' name=\"harga[$q[kode]]\" onkeyup=\"calc($no);\"></td>
									<td><input type='text' name=\"tgl_kd[$q[kode]]\" class=\"form-control\" value=''/></td>
									<td id=\"sub$no\" class=\"subtotal\" ></td>";
									//end of isi
									echo"
									<td align='center'>
										<a href=\"$aksi?module=transaksi&act=trans_pemb&input=delete&id=$q[id_keranjang]&kode=$q[kode]\" class='btn btn-danger' style='color: white' Onclick=\"return confirm('Apakah Anda yakin akan menghapus $q[nama]?')\"><i class='bi bi-trash-fill'></i></a>
									</td></tr>";
										$no++;
									}				
									?>
								</tbody>
								<tfooter>
									<tr> 
										<th style="text-align:right" colspan="7">Potongan</th> 
										<th id="tot_bar" colspan="2"><input type="text" name='potongan' id='potongan' onkeyup="calc();"  value='0'  class="form-control"></th>
									</tr> 
									<tr> 
										<th style="text-align:right" colspan="7">Total</th> 
										<th id="tot_bar" colspan="2"><span id="total"></span></th>
									</tr> 
								</tfooter>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title p-0" style="display: inline-block">Transaksi Pembelian</h5>
					</div>
					<div class="card-body mt-3">
						<div class="row" style="gap: 11px 0">
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label>Supplier</label>
									<select name="nm_supplier" class="form-control">
									<option value="0" selected>- Pilih Supplier -</option>
									<?php
										$tampil=mysql_query("SELECT * FROM supplier ORDER BY nm_supplier");
										while($r=mysql_fetch_array($tampil)){
										echo "<option value=$r[id_supplier]>$r[nm_supplier]</option>";
										}echo"</select>";
									?>
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label>No. Faktur</label>
									<input type="text" id="no_fak_sup" name="no_fak_sup" value="" class="form-control">
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label>Tanggal Faktur</label>
									<input type="date" placeholder="YYYY-MM-DD" id="tgl_fak" name="tgl_fak" value="" class="form-control">
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label>No. Nota</label>
									<input type="text" disabled value="<?php echo"$initial$angka$date";?>" class="form-control">
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label>Tanggal</label>
									<input type="text" disabled value="<?php echo"$tgl";?>" class="form-control">
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group d-flex justify-content-around">
									<div>
										<label class="checkbox-inline"><input name='status' id="tunai" type='radio' value='Tunai'></label>
										<label>Tunai</label>
									</div>
									<div>
										<label class="checkbox-inline"><input name='status' id="tempo" type='radio' value='Tempo'></label>
										<label>Tempo</label>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
									<label>Jatuh Tempo</label>
									<input type="date" name="tgl_tempo" disabled id="tgl_tempo" value="" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex" style="flex-direction: column; gap: 15px">
				<?php
					$cek = mysql_num_rows($z);
					if($cek > 0){ 
					echo"
					<button type='submit'
						style='width: 100%'
						class='py-2 btn btn-success' value='simpan' Onclick=\"alert('Apakah Anda yakin akan menyimpan transaksi ini?')\"'>
						Simpan</button>";	
					} else {
					echo "
					<button type='button'
						style='width: 100%'
						class='py-2 btn btn-success' value='simpan' Onclick=\"alert('Tambahkan data barang dahulu')\"'>
						Simpan</button>";	
					} 		
					if($cek > 0){
					echo" 
					<a href=\"$aksi?module=transaksi&act=trans_pemb&input=hapus&status=beli\" Onclick=\"return confirm('Apakah Anda yakin akan membatalkan transaksi ini?')\">
					<input class='btn btn-secondary py-2' style='width: 100%' type='button' value='Batal'></a>";
					} else {
					echo"
					";
					} 
					echo"</form>"
				?>
				</div>
			</div>
		</div>
	</section>
	<div class="modal fade" role="dialog" id="penjualan" tabindex="-1" data-keyboard="false" data-backdrop="static">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-lg vertical-align-center" role="document">
				<div class="modal-content">
					<div class="modal-header br">
						<h5 class="modal-title">Tambahkan Item Barang </h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<?php
								$pg = '';
								if(!isset($_GET['pg'])) {
									include ('modul/mod_transaksi/formpemb.php');
								}	else {
									$pg = $_GET['pg'];
									$mod = $_GET['mod'];
									include $mod . '/' . $pg . ".php";
								}		
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		break;
	case "detailpelunasan":
		$query = mysql_query("select kd_pemb.kd_pmb,pembelian.id_product,barang.nama,pembelian.ed,pembelian.nobacth,
                                           pembelian.harga,pembelian.jumlah,pembelian.subtotal
                                           from pembelian,kd_pemb,barang
                                           where kd_pemb.kd_pmb=pembelian.id_transaksi and barang.kode=pembelian.id_product
                                           and pembelian.id_transaksi='$_GET[id]'") or die(mysql_error());
		$sup = mysql_fetch_array(mysql_query("SELECT * FROM kd_pemb, supplier WHERE supplier.id_supplier=kd_pemb.id_supplier and status='tempo' and kd_pemb.kd_pmb='$_GET[id]'")) or die("query gagal");
		$sql = mysql_fetch_array(mysql_query("SELECT * FROM kd_pemb WHERE kd_pmb='$_GET[id]'")) or die("query gagal");
		$pemilik = mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die("gagal");

	?>
		<div class="col-md-12 col-sm-12">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="row">
						<?php
						if ($sql[status] == 'Tempo') {
							echo "<input type=hidden id='kode' value='$sql[kd_pmb]'>
										<input type=hidden id='tot' value='$sql[total]' Onclick=\"return confirm('Apakah Anda yakin akan nelunasi transaksi ini?')\"><a style=\"float:right;\" id='lunas' >Bayar&nbsp<i class='icon-tags'></i></a>&nbsp";
						} else {
							echo "<a style=\"float:right;\">Lunas&nbsp<i class='icon-ok'></i></a>&nbsp";
						}
						?>
						<div class="col-md-6">
							<?php
							echo "
									$pemilik[nm_perusahaan]<br>
									$pemilik[alamat]<br><br>
									
									<label>Nota :<span style='margin-left:5px;'>$sql[kd_pmb]</label><br>
									<label>Pembayaran : <span style='margin-left:5px;'>$sql[status]</label><br>
									<label>Jatuh Tempo : <span style='margin-left:5px;'>$sql[tgl_tempo]</label><br>
									<label>Tanggal: <span style='margin-left:5px;'>$sql[tanggal]</label><br>";

							?>
						</div>

						<div class="col-md-6">
							<?php
							echo "
									<label>Supplier : <span style='margin-left:5px;'>$sup[nm_supplier]</label><br>
									<label>No.faktur : <span style='margin-left:5px;'>$sup[nofaktur]</label><br>
									<label>Tanggal Faktur: <span style='margin-left:5px;'>$sup[tgl_faktur]</label><br>
						
												";
							?>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>No.</th>
										<th>Kode Barang</th>
										<th>No.Batch</th>
										<th>Tanggal ED</th>
										<th>Nama</th>
										<th>Harga</th>
										<th>Jumlah</th>
										<th>Subtotal</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									while ($r = mysql_fetch_row($query)) {
										echo "<tr>
											<td align='center'>$no</td>
                                            <td align='center'>$r[1]</td>
                                            <td align='center'>$r[4]</td>
                                            <td align='center'>$r[3]</td>
                                            <td>$r[2]</td>
                                            <td>";
										echo "Rp&nbsp" . number_format($r[5], 2, ',', '.');
										echo "</td>
                                            <td align='center'>$r[6]</td>
                                            <td>";
										echo "Rp&nbsp" . number_format($r[7], 2, ',', '.');
										echo "</td>
                                        </tr>";
										$no++;
									} ?>
									<tr>
										<td colspan="7" style="text-align:right">Total</td>
										<td><?php
											echo "Rp&nbsp" . number_format($sql[total], 2, ',', '.');
											?></td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>
					<div class="panel-footer">
						<?php
						echo "* Faktur Pembelian Petugas :$sql[user]";
						?>
					</div>
				</div>
			</div>
	<?php
		break;
}
	?>