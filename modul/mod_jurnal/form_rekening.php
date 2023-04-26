	<script>
					$("#idrekening").change(function(){
						
						var kd_rek   = $("#idrekening").val();	
						var main_rek = "http://localhost/apotek/modul/mod_jurnal/form_rekening.php";					
						
						
							$.post(main_rek, {id: kd_rek} ,function(data) {
								$("#data-rekening").html(data).show();
							});
                    });
					
					$("#idrekening").change(function(){
                        var kd=$("#idrekening").val();
						
                        $.ajax({
                            url:"http://localhost/apotek/modul/mod_setup/aksi_setup.php",
                            data:"op=cek_kd&kd="+kd,
                            success:function(data){
                                if(data==0){
                                    $("#pesan").html('<div class="alert alert-danger"><strong>Data Tidak di Temukan</strong></div>');
                                    $("#idrekening").css('border','3px #c33 solid');
                                }else{
                                    $("#pesan").html('<div class="alert alert-success"><strong>Data di Temukan</strong></div>');
                                    $("#idrekening").css('border','3px #090 solid');
                                }
                            }
                        });
                    });
					
					$("#update_rekening").click(function(){
                        idby=$("#idbiaya").val();
                        kode=$("#idrekening").val();
                        nama=$("#nm_rekening").val();
                        jenis=$("#jenis").val();
                        jumlah=$("#jumlah").val();
						
						if(kode==""){
                            alert("Kode rekening Harus diisi");
                            exit();
                        }
						if(nama==""){
                            alert("nama rekening Harus diisi");
                            exit();
                        }
						
						var answer = confirm("Apakah anda yakin ?");
						
						
						
                        //tampilkan status update
                        $("#status").html('sedang diupdate. . .');
                        $("#loading").show();
                        $("#back").show();
                     if (answer) {
                       $.ajax({
                            url:"http://localhost/apotek/modul/mod_setup/aksi_setup.php",
                            data:"op=updatepost&kode="+kode+"&nama="+nama+"&jenis="+jenis+"&jumlah="+jumlah+"&idby="+idby,
                            cache:false,
                            success:function(msg){
								if(msg=='Sukses'){
                                   $("#status").html("<i class='icon-ok' style='margin-right:10px;'></i>Berhasil disimpan");
										window.location.href='jurnal.penyesuaian';
                                }else{
                                    $("#status").html("<i class='icon-warning-sign' style='margin-right:10px;'></i>Gagal disimpan");
                                }
                                
                                $("#loading").fadeOut(2000);
                                $("#back").fadeOut(2000);
								$("#id_rekening").val("");
                                $("#nm_rekening").val("");
                                $("#jenis").val("");
                                $("#jumlah").val("");
                                
                            }
                        });
							}
                    });
					
					
	</script>
   <?php
	//koneksi
	include "../../Inc/koneksi.php";
	include "../../Inc/library.php";
	include "../../Inc/fungsi_indotgl.php";
	include "../../Inc/fungsi_autolink.php";
	include "../../Inc/fungsi_combobox.php";
	include "../../Inc/fungsi_kalender.php";
	include "../../Inc/class_paging.php";
	include "../../Inc/fungsi_rupiah.php";
	
	// tangkap variabel kd_rek
	$kd_rek = $_POST['id'];

	// query untuk menampilkan rekening berdasarkan kd_rek
		$data = mysql_fetch_array(mysql_query("SELECT * FROM rekening WHERE kd_rek = ".$kd_rek." "));
		
	$tgl=date('d-m-Y');
	$date=date('Ymd');
	$initial="BYA";
	$auto=mysql_query("select * from biaya order by id_biaya desc limit 1");
	$no=mysql_fetch_array($auto);
	$angka=$no['id_biaya']+1;
	
	// jika kd_rek > 0 / form ubah data
	if($kd_rek> 0) 
	{ 
		$kode = $data['kd_rek'];
		$nama = $data['nama_rekening'];
		$jenis = $data['jenis'];
		$jumlah = $data['jumlah']; 
	
		if($data['jenis']==1) 
		{
			$status = "Debit";
		} else {
			$status = "Kredit";
		}

		//form tambah data
	}
		else 
	{
		$kode = "";
		$nama = "";
		$jenis = "";
		$jumlah = "";

		
	}
	
	?>
				
<div class="pagetitle" style="position: relative;">
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Jurnal</a></li>
			<li class="breadcrumb-item"><a href="jurnal.penyesuaian">Jurnal Penyesuaian</a></li>
			<li class="breadcrumb-item active"><a href="#">Tambah Biaya</a></li>
		</ol>
	</nav>
</div><!-- End Page Title -->

<section class="section">
	<div class="row">
		<div class="col-lg-10">

			<div class="card">
				<div class="card-header">
					<h5 class="card-title p-0" style="display: inline-block">Tambah Biaya</h5>
				</div>
				<div id="pesan"></div>
				<div class="card-body mt-3">
					<div class="row" style="gap: 11px 0">
						<div class="panel-heading">
							<?php echo"$datarek";?>
						</div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Kode Transaksi Biaya</label>
                                <input type="text" name="idbiaya" id="idbiaya" value="<?php echo"$initial$angka$date";?>" disabled class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Kode Rekening</label>
                                <input type="text" name="idrekening" value="<?php echo"$kode";?>" id="idrekening" class="form-control">
                            </div>
                        </div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Nama Rekening</label>
                			    <input type="text" value="<?php echo"$nama";?>" id="nm_rekening" disabled class="form-control" readonly>
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Jenis</label>
								<select id='jenis' class='form-control'>
									<?php 
										// tampilkan untuk form ubah rekening
									if($kd_rek > 0) 
									{ 
									?>
										<option value="<?php echo $jenis ?>"><?php echo $status ?></option>
									<?php 
									} 
									?>
											<option value='1'>Debit</option>
											<option value='2'>Kredit</option>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label>Jumlah</label>
								<input type="text" value="<?php echo"$jumlah";?>" id="jumlah" class="form-control">
							</div>
						</div>
						<p style="font-size: .8rem; color: red; margin-top: 10px">
							*nb. <br>biaya yang sudah di posting di jurnal umum tidak bisa di ubah
						</p>
                    </div>
				</div>
				<div class="modal-footer bg-whitesmoke p-3" style="border: 1px solid #ebeef4; gap: 10px">
                    <button type="button" class="btn btn-secondary" onClick="self.history.back()">Batal</button>
                    <button type="button" id="update_rekening" class="btn btn-warning">Simpan</button>
                </div>
			</div>
		</div>
	</div>
</section>