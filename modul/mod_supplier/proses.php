<?php
include "../../Inc/koneksi.php";
$op=isset($_GET['op'])?$_GET['op']:null;

if($op=='kode'){
    echo"<option>Kode supplier</option>";
    while($r=mysql_fetch_array($data)){
        echo "<option value='$r[kode]'>$r[kode]</option>";
    }
}elseif($op=='supplier'){
    echo'<table id="supplier" class="table table-hover">
    <thead>
            <tr>
                <Td colspan="5"><a href="?page=supplier&act=tambah" class="btn btn-primary">Tambah supplier</a></td>
            </tr>
            <tr>
                <td>Kode supplier</td>
                <td>Nama supplier</td>
                <td>Harga Beli</td>
                <td>Harga Jual</td>
                <td>Stok</td>
            </tr>
        </thead>';
	while ($b=mysql_fetch_array($data)){
        echo"<tr>
                <td>$b[kode]</td>
                <td>$b[nama]</td>
                <td>$b[hrg_beli]</td>
                <td>$b[hrg_jual]</td>
                <td>$b[stok]</td>
            </tr>";
        }
    echo "</table>";
}elseif($op=='ambildata'){
    $kode=$_GET['kode'];
    $dt=mysql_query("select * from supplier where kode='$kode'");
    $d=mysql_fetch_array($dt);
    echo $d['nama']."|".$d['hrg_beli']."|".$d['hrg_jual']."|".$d['stok'];
}elseif($op=='cek'){
    $kd=$_GET['kd'];
    $sql=mysql_query("select * from supplier where id_supplier='$kd'");
    $cek=mysql_num_rows($sql);
    echo $cek;
}elseif($op=='update'){
    $kode=$_GET['kode'];
    $nama=htmlspecialchars($_GET['nm_supplier']);
    $kota=htmlspecialchars($_GET['kota']);
    $alamat=htmlspecialchars($_GET['alamat']);
    $no_hp=htmlspecialchars($_GET['no_hp']);
    
    $update=mysql_query("update supplier set nm_supplier='$nama',
											 kota='$kota',
											 alamat='$alamat',
											 no_hp='$no_hp'
											 where id_supplier='$kode'");
    if($update){
        echo "sukses";
    }else{
        echo "ERROR. . .";
    }
}elseif($op=='delete'){
    $kode=$_GET['kode'];
    $del=mysql_query("delete from supplier where kode='$kode'");
    if($del){
        echo "sukses";
    }else{
        echo "ERROR";
    }
}elseif($op=='simpan'){
    $kode=$_GET['kode'];
    $nama=htmlspecialchars($_GET['nm_supplier']);
    $kota=htmlspecialchars($_GET['kota']);
    $alamat=htmlspecialchars($_GET['alamat']);
    $no_hp=htmlspecialchars($_GET['no_hp']);
    
    
    $tambah=mysql_query("insert into supplier (id_supplier,nm_supplier,kota,alamat,no_hp)
                        values ('$kode','$nama','$kota','$alamat','$no_hp')");
    if($tambah){
        echo "sukses";
    }else{
        echo "error";
    }
}
?>