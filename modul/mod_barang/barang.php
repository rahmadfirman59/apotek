<?php
$aksi = "../modul/mod_barang/proses.php";
$js = "";
$p = isset($_GET['act']) ? $_GET['act'] : null;
switch ($p) {
	default:
?>



	<?php

		break;
	case "pengeluaran":
		echo "<h2><img src='../img/icons/16x16_0320/database.png'>&nbspPengeluaran Data Barang</h2><hr>
  <span style='margin-left:10px'></span><button onClick=\"window.location.href='barang';\" class='btn'>Kembali</button><br><br><br>";
		$query_tanggal = mysql_fetch_array(mysql_query("select min(tanggal_transaksi) as tanggal_pertama from master_transaksi"));
		$tanggal_pertama = $query_tanggal['tanggal_pertama'];

		//untuk menyelesaikan transaksi
		$p      = new Paging5;
		$batas  = 20;
		$posisi = $p->cariPosisi($batas);
		if (isset($_POST['report'])) {

			//tanggal periode laporan
			$tanggal1 = $_POST[thn_1] . '-' . $_POST[bln_1] . '-' . $_POST[tgl_1];
			$tanggal2 = $_POST[thn_2] . '-' . $_POST[bln_2] . '-' . $_POST[tgl_2];

			$sql = mysql_query("select barang.nama, sum(penjualan.jumlah) from penjualan, barang where
											penjualan.id_product=barang.kode and penjualan.tanggal between '$tanggal1' and '$tanggal2' 
											group by barang.nama order by barang.nama asc limit $posisi,$batas");
		} else {

			$sql = mysql_query("select barang.nama, sum(penjualan.jumlah) from penjualan, barang where
											penjualan.id_product=barang.kode and penjualan.tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang'

											group by barang.nama order by barang.nama asc  limit $posisi,$batas") or die (mysql_error());
				
				unset($_POST['report']);
			}
			?>
<div id="jurnal">
	<fieldset>
		<legend style='float:right'>
			<form action="barang.keluar" method="post" name="postform">
				Periode<span style='margin-left:10px'></span><?php 
					if(empty($_POST['tanggal1'])){ 
						echo 	combotgl(1,31,'tgl_1',1);
								combonamabln(1,12,'bln_1',$bln_sekarang);
								combothn(2000,$thn_sekarang,'thn_1',$thn_sekarang);
					}else{	
						echo 	combotgl(1,31,'tgl_1',$_POST[tgl_1]);
								combonamabln(1,12,'bln_1',$_POST[bln_1]);
								combothn(2000,$thn_sekarang,'thn_1',$_POST[thn_1]); 
					}?>

				S/D
				<?php 
					if(empty($_POST['tanggal2'])){ 
						echo 	combotgl(1,31,'tgl_2',$tgl_skrg);
								combonamabln(1,12,'bln_2',$bln_sekarang);
								combothn(2000,$thn_sekarang,'thn_2',$thn_sekarang);
					}else{ 
						echo 	combotgl(1,31,'tgl_2',$_POST[tgl_2]);
								combonamabln(1,12,'bln_2',$_POST[bln_2]);
								combothn(2000,$thn_sekarang,'thn_2',$_POST[thn_2]);  }?>

				<input type="submit" name="report" class="btn-success" value="tampilkan" />
			</form>
		</legend>
		<table class="jurnal">
			<tr>
				<th>No.</th>
				<th>Nama Obat</th>
				<th>Jumlah</th>
			</tr>
			<?php $no=1;
				while($r=mysql_fetch_array($sql)){?>
			<tr class="body">
				<td>
					<div align="center"><?php echo $no?></div>
				</td>
				<td>
					<div align="left"><?php echo $r[0]?></div>
				</td>
				<td>
					<div align="center"><?php echo $r[1]?></div>
				</td>
			</tr>
			<?php
				$no++;
			}
			?>

		</table>
		<?php 	$jmldata = mysql_num_rows(mysql_query("select barang.nama, sum(penjualan.jumlah) from penjualan, barang where
											penjualan.id_product=barang.kode and penjualan.tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' group by barang.nama order by barang.nama asc"));
		$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		$linkHalaman = $p->navHalaman($_GET[bacth], $jmlhalaman);

		echo "<div id=paging>Hal: $linkHalaman</div><br></fieldset></div>";
		break;
	case "trackbarang":
		echo "<h2><img src='../img/icons/16x16_0320/database.png'>&nbspPengeluaran Data Barang</h2><hr>
  <input class='btn'type=button value='Kembali' onClick=\"window.location.href='barang';\"></br></br>";
		$query = mysql_query("select penjualan.tanggal,penjualan.id_transaksi,barang.nama,penjualan.jumlah from barang, penjualan
							where barang.kode=penjualan.id_product and barang.kode='$_GET[id]'") or die(mysql_error());
		echo "<div id='nota'><fieldset>
				<div class='kepala-nota'>
				<p>APOTEK AZKA Pekalongan<br>
				Jl.Keradenan Besar Pekalongan Selatan No.26</p><hr><hr style='color:#000;'></div>
				<div class='kanan-nota'>
                            </div>";
		echo "<div id='scroll'><table class='nota'>
                                <thead>
                                    <tr>
										<th>No.</th>
                                        <th>Tanggal</th>
										<th>Kode transaksi</th>
                                        <th>Nama</th>
                                        <th>Jumlah</th>
                                         </tr>
                                </thead>";
		$no = 1;
		while ($r = mysql_fetch_row($query)) {
			echo "<tr class=body>
											<td align='center'>$no</td>
                                            <td align='center'>$r[0]</td>
                                            <td align='center'>$r[1]</td>
                                            <td align='center'>$r[2]</td>
                                            <td align='center'>$r[3]</td>
                                            <td align='center'>$r[4]</td>
                                             </tr>";
			$no++;
		}
		echo "<tr>
                                        <td colspan='5'><h4 align='right'></h4></td>
                                        <td colspan='5'><h4></h4></td>
                                    </tr>
                                    </table></div><h4 style='float:right; margin-right:20px;'></h4>
									<p style='font-size:8pt;float:left; padding-top:15px;'>*tracking barang <p></fieldset></div>";
}

?>

