<?php
switch($_GET['act']){
	default:
	if($_SESSION['leveluser']=='admin'){
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
				targets: [0, 3, 4, 5, 7]
			},
			{
				width: "7%",
				targets: [0]
			},
			{
				orderable: false,
				targets: [7]
			}],
		});
	});
</script>

<div class="pagetitle" style="position: relative;">
	<h1>Tabel Modul</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
			<li class="breadcrumb-item active"><a href="#">Plugin</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-12">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Tabel Modul</h5>
					<button type="button" style="margin-right: 10px; position: absolute; right: 12px; top: 13px; box-shadow: 0 2px 6px #ffc473; background: #ffa426; color: white; font-size: 13px" class="btn btn-warning"
						value='Tambah Modul' onclick="window.location.href='tambahmodul';"><i class="fa fa-plus mr-1"></i>
						Tambah Modul</button>
				</div>
				<div class="card-body mt-3">
					<div class="table-responsive">
						<table
							class="table table-striped table-hover datatable-primary datatable-jquery" width="100%">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">Nama Modul</th>
									<th scope="col">Link Modul</th>
									<th scope="col">Publis</th>
									<th scope="col">Aktif</th>
									<th scope="col">Status</th>
									<th scope="col">Install | Update</th>
									<th scope="col" style="width: 13%;">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php		
									$tampil=mysql_query("SELECT * FROM modul ORDER BY urutan");
									while($r = mysql_fetch_array($tampil)){
										echo"<tr><td>$r[urutan]</td>
										<td>$r[nama_modul]</td>
										<td>$r[link]</td>
										<td>$r[publish]</td>
										<td>$r[aktif]</td>
										<td>$r[status]</td>
										<td>$r[tgl]</td>
										<td>
											<button class='btn btn-info btn-sm mr-1'><a style='color: white;' href=editmodul.$r[id_modul]><i class='fa fa-edit'></i></a></button>
                							<button class='btn btn-danger btn-sm'><a style='color: white'href=hapusmodul.$r[id_modul]  Onclick=\"return confirm('Apakah Anda yakin akan menghapus modul $r[nama_modul] dari daftar?')\"><i class='bi bi-trash-fill'></i></a></button>
										</td>
										</tr>";
									
									}
								?>	
							</tbody>
							<tfoot>
								<tr>
									<th style="text-align:right" colspan="5">Total persediaan:</th>
									<th id="tot_bar" colspan="4"><?php echo"Rp&nbsp".number_format($total,2,',','.');?>
								</th>
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
	}else{
			echo"<div id='peretas'><br><center><img src='../img/icons/32x32_0400/exclamation.png'><ss><p>$_SESSION[namalengkap]&nbspdilarang mengakses halaman ini.
			<br>TINDAKAN MERTAS SISTEM </p></ss></center></div>";
		}
	break;
	case "tambahmodul":
	if($_SESSION['leveluser']=='admin'){
    ?>

<div class="pagetitle" style="position: relative;">
	<h1>Tambah Modul</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
			<li class="breadcrumb-item"><a href="pengaturan">Plugin</a></li>
			<li class="breadcrumb-item active"><a href="#">Tambah Modul</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-6">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Tambah Modul</h5>
				</div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Modul</label>
                                <input type="text" name="nama_modul" id="nama_modul" class="form-control">
                            </div>
                        </div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Link</label>
								<input type="text" name="link" id="link" class="form-control">
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Status</label>
								<select id="akses" class="form-control">
									<option selected>-Pilih-</option>
									<option value="user">User</option>
									<option value="admin">Admin</option>
								</select>	
							</div>
						</div>
                    </div>
				</div>
				<div class="modal-footer bg-whitesmoke p-3" style="border: 1px solid #ebeef4; gap: 10px">
                    <button type="button" class="btn btn-secondary" onClick="self.history.back()">Batal</button>
                    <button type="button" id="simpan_modul" class="btn btn-warning">Simpan</button>
                </div>
			</div>

		</div>
	</div>
</section>
	<?php		
		 }
		 else { 	
			echo"<div id='peretas'><center><img src='../img/icons/32x32_0400/exclamation.png'><ss><p>$_SESSION[namalengkap]&nbspdilarang mengakses halaman ini.
			<br>TINDAKAN MERTAS SISTEM </p></ss></center></div>";
		}
     break;
	 case "editmodul":	
    $edit = mysql_query("SELECT * FROM modul WHERE id_modul='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
	if($_SESSION[leveluser]=='admin'){
		?>
			<div class="pagetitle" style="position: relative;">
	<h1>Edit Modul</h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
			<li class="breadcrumb-item"><a href="pengaturan">Plugin</a></li>
			<li class="breadcrumb-item active"><a href="#">Edit Modul</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-6">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Edit Modul</h5>
				</div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
						<input type="hidden" id="kode" value='<?php echo"$r[id_modul]";?>'>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Nama Modul</label>
                                <input type="text" name="nama_modul" <?php echo"value=$r[nama_modul]";?> id="nama_modul" class="form-control">
                            </div>
                        </div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Link</label>
								<input type="text" name="link" id="link" <?php echo"value=$r[link]";?> class="form-control">
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Status</label>
								<?php
									if ($r[status]=='user'){
										$user = "selected";
									} elseif ($r[status]=='admin') {
										$admin = "selected";
									} else {
										$status = "selected";
									}
								?>
								<select id="akses" class="form-control">
									<option <?php echo"$status ";?>>-Pilih-</option>
									<option value="user" <?php echo"$user";?>>User</option>
									<option value="admin" <?php echo"$admin";?>>Admin</option>
								</select>	
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Aktif</label>
								<?php
									if ($r[aktif]=='Y'){
										$aktif = "selected";
									} elseif ($r[aktif]=='T') {
										$nonaktif = "selected";
									} else {
										$akses = "selected";
									}
								?>
								<select id="aktif" class="form-control">
									<option <?php echo"$akses ";?>>-Pilih-</option>
									<option value="Y" <?php echo"$aktif ";?>>Aktif</option>
									<option value="T" <?php echo"$nonaktif ";?>>Non Aktif</option>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Publish</label>
								<?php
									if ($r[publish]=='Y'){
										$publish = "selected";
									} elseif ($r[publish]=='T') {
										$nonpublish = "selected";
									} else {
										$publis = "selected";
									}
								?>
								<select id="publis" class="form-control">
									<option <?php echo"$publis ";?>>-Pilih-</option>
									<option value="Y" <?php echo"$publish";?>>Aktif</option>
									<option value="T" <?php echo"$nonpublish";?>>Non Aktif</option>
								</select>	
							</div>
						</div>
                    </div>
				</div>
				<div class="modal-footer bg-whitesmoke p-3" style="border: 1px solid #ebeef4; gap: 10px">
                    <button type="button" class="btn btn-secondary" onClick="self.history.back()">Batal</button>
                    <button type="button" id="simpan_modul" class="btn btn-warning">Simpan</button>
                </div>
			</div>

		</div>
	</div>
</section>
		
		<?php
	}
	 else { 	
			echo"<br><center><img src='../img/64/25.png'><ss><p>$_SESSION[namalengkap]&nbspdilarang mengakses halaman ini.
			<br>TINDAKAN MERTAS SISTEM </p></ss></center>";
		}
    break; 
}
?>