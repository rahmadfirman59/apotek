<?php
switch($_GET[act]){
	default:
		
		$pemilik=mysql_fetch_array(mysql_query("select nm_perusahaan,alamat from bigbook_perusahaan")) or die ("gagal");
?>
<!-- Reports -->
<div class="pagetitle" style="position: relative;">
    <h1>Grafik</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Grafik</a></li>
            <li class="breadcrumb-item active"><a href="#">Grafik Keuangan</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<div class="col-12">
    <div class="card">

    <div class="card-body">
        <h5 class="card-title">Grafik Keuangan Apotek</span></h5>

        <!-- Line Chart -->
        <div id="reportsChart"></div>

        <script>
        document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#reportsChart"), {
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                show: false
                },
            },
            markers: {
                size: 4
            },
            colors: ['#4154f1', '#2eca6a', '#ff771d', '#dc3545'],
            fill: {
                type: "gradient",
                gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.4,
                stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                 categories: [ 'Jun', 'Jul', 'Aug','Sep','Oct', 'Nov', 'Dec','Jan', 'Feb', 'Mar','Apr','May',  ]
            },
            yaxis: {
                title: {
                    text: 'Jumlah'
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+'Rp.'+ this.y ;
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            series: [<?php
			//penjualan
		  $pertama=$thn_sekarang.'-'.$bln_sekarang.'-'."01";
		  $penj=mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between 
			'$pertama' and '$tgl_sekarang' order by tanggal asc"))or die (mysql_error());
		  //pembelian
		  $pertama=$thn_sekarang.'-'.$bln_sekarang.'-'."01";
		  $pemb=mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between 
			'$pertama' and '$tgl_sekarang' and status='Tunai' order by tanggal asc"))or die (mysql_error());
		  //laba
				$pjl=mysql_fetch_array(mysql_query("select sum(total) as total from kd_penj where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die (mysql_error());
				$pbl=mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' ")) or die (mysql_error());
				$psd=mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='113'"));
				$ret=mysql_fetch_array(mysql_query("select jumlah from rekening where kd_rek='511'"));
				$biaya=mysql_fetch_array(mysql_query("select sum(jumlah) as jumlah from rekening where kd_rek between '611' and '699'"));
				$pot=mysql_fetch_array(mysql_query("select sum(disc) as pot from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' "));
				$hutang=mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where tanggal between '$thn_sekarang-$bln_sekarang-1' and '$tgl_sekarang' and status='Tempo'")) or die (mysql_error());
				
					$tampil=mysql_query("SELECT * FROM barang ORDER BY nama");
		while($r = mysql_fetch_array($tampil)){
		$jml=$r[stok];
		$hrg=$r[hrg_beli];
		$subtotal= $jml * $hrg;
		$total1= $total1 + $subtotal;}
		$x = $pbl[total]-$ret[jumlah]-$pot[pot];
		$x2 = $x + $psd[jumlah];
		$hpp = $x2 - $total1;
		$kotor = $pjl[total]-$hpp;
		$pajak= $kotor * 10/100;
		$beban= $biaya[jumlah]+$pajak;
		$laba = $kotor-$beban;
		
				$pj = $penj[total]; 
				$pb = $pemb[total]; 
				$hutang1 = $hutang[total];
				
			$result =  mysql_query("select * from master_laporan order by tahun");
			while ($row = mysql_fetch_array($result)) {
				$data[] = $row['penjualan'];
				$lab[] = $row['laba'];
				$pem[] = $row['pembelian'];
				$hut[] = $row['hutang'];
			}
			?>{
                name: 'Penjualan',
                data: [<?php echo join($data,',') ?>,<?php echo $pj ?>]
            }, {
                name: 'Pembelian',
                data: [<?php echo join($pem,',') ?>,<?php echo $pb ?>]
            },{
                name: 'Laba',
                data: [<?php echo join($lab, ',') ?>,<?php echo $laba ?>]
            },{
                name: 'Hutang',
                data: [<?php echo join($hut, ',') ?>,<?php echo $hutang1 ?>]
            }]
            }).render();
        });
        </script>
        <!-- End Line Chart -->

    </div>

    </div>
</div><!-- End Reports -->

<?php
	echo"";
}
?> 