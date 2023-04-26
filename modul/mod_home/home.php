    <div class="pagetitle" style="position: relative;">
    	<h1>Dashboard</h1>
    	<nav>
    		<ol class="breadcrumb">
    			<li class="breadcrumb-item active"><a href="#">Home</a></li>
    		</ol>
    	</nav>
		<h4 style="position: absolute; top: 10px; right: 10px">
			<?php				
			echo"
				$hari_ini,";
				echo tgl_indo(date("Y m d")); 
				echo " | "; 
				echo '<span class="timeNow">' . date("H:i:s") . '</span>';
				echo " WIB";
			?>
		</h4>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    	<div class="row">

    		<!-- Left side columns -->
    		<div class="col-lg-12">
    			<div class="row">

    				<!-- Penjualan Card -->
    				<div class="col-xxl-3 col-md-3">
    					<div class="card info-card penjualan-card">

    						<div class="card-body">
    							<h5 class="card-title">Transaksi</h5>

    							<div class="d-flex align-items-center">
    								<div
    									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
    									<i class="bi bi-cart"></i>
    								</div>
    								<div class="ps-3">
    									<h6 style="font-size: 1.4rem"><a href="transaksi.penjualan" class="penjualan-href">Penjualan</a></h6>

    								</div>
    							</div>
    						</div>

    					</div>
    				</div><!-- End Penjualan Card -->

					<!-- Pembelian Card -->
    				<div class="col-xxl-3 col-md-3">
    					<div class="card info-card pembelian-card">

    						<div class="card-body">
    							<h5 class="card-title">Transaksi</h5>

    							<div class="d-flex align-items-center">
    								<div
    									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
										<i class="bi bi-currency-dollar"></i>
    								</div>
    								<div class="ps-3">
    									<h6 style="font-size: 1.4rem"><a href="transaksi.pembelian" class="pembelian-href">Pembelian</a></h6>
    								</div>
    							</div>
    						</div>

    					</div>
    				</div><!-- End Pembelian Card -->

					<!-- Sales Card -->
    				<div class="col-xxl-3 col-md-3">
    					<div class="card info-card laporan-card">

    						<div class="card-body">
    							<h5 class="card-title">Laporan</h5>

    							<div class="d-flex align-items-center">
    								<div
    									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
										<i class="bi bi-book"></i>
    								</div>
    								<div class="ps-3">
    									<h6 style="font-size: 1.4rem"><a href="llb" class="llb-href">Rugi/Laba</a></h6>
    								</div>
    							</div>
    						</div>

    					</div>
    				</div><!-- End Sales Card -->

					<!-- Sales Card -->
    				<div class="col-xxl-3 col-md-3">
    					<div class="card info-card grafik-card">

    						<div class="card-body">
    							<h5 class="card-title">Grafik</h5>

    							<div class="d-flex align-items-center">
    								<div
    									class="card-icon rounded-circle d-flex align-items-center justify-content-center">
										<i class="bi bi-bar-chart-line-fill"></i>
    								</div>
    								<div class="ps-3">
    									<h6 style="font-size: 1.4rem"><a href="grafik" class="grafik-href">Grafik</a></h6>
    								</div>
    							</div>
    						</div>

    					</div>
    				</div><!-- End Sales Card -->

    				<!-- Operasional Table -->
    				<div class="col-md-5 col-sm-12 col-xs-12">
    					<div class="card recent-sales overflow-auto">

    						<!-- <div class="filter">
    							<a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
    							<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
    								<li class="dropdown-header text-start">
    									<h6>Filter</h6>
    								</li>

    								<li><a class="dropdown-item" href="#">Today</a></li>
    								<li><a class="dropdown-item" href="#">This Month</a></li>
    								<li><a class="dropdown-item" href="#">This Year</a></li>
    							</ul>
    						</div> -->

    						<div class="card-body">
    							<h5 class="card-title">Biaya Operasional</h5>

    							<table class="table table-borderless tb-false datatable-primary">
    								<thead>
    									<tr>
    										<th scope="col" class="text-center">Kode Rekening</th>
    										<th scope="col">Keterangan</th>
    										<th scope="col">Masa</th>
    										<th scope="col">Jumlah</th>
    									</tr>
    								</thead>
    								<tbody>
										<?php
											$p      = new Paging;
											$batas  = 5;
											$posisi = $p->cariPosisi($batas);
											$sql = mysql_query("select * from rekening where kd_rek between '611' and '666'
											order by kd_rek asc limit $posisi,$batas") or die (mysql_error());
											$total=mysql_fetch_array(mysql_query("select sum(jumlah) as total from rekening where kd_rek between '611' and '666'")) or die (mysql_error());
										while($r=mysql_fetch_array($sql)){
											echo"	<tr class=\"body\">
												<td><div align=\"center\">$r[kd_rek]</div></td>
												<td><div align=\"center\"></div>$r[nama_rekening]</td>
												<td><div align=\"center\">1 BLN</div></td>
												<td><div align=\"left\">Rp.&nbsp".number_format($r[jumlah],2,'.',',');echo"</div></td>
											</tr>";
											}
										?>  
										<tr>
											<td colspan="3"><div align="center"><strong>TOTAL Penyesuaian</strong></div></td>
											<td style="display: none"></td>
											<td style="display: none"></td>
											<td><strong><?php echo 'Rp.&nbsp' . number_format($total['total'],2,'.',','); ?></strong></td>
										</tr>
									</tbody>
    							</table>

    						</div>

    					</div>
    				</div><!-- End Operasional Table -->

					<!-- Hutang Table -->
    				<div class="col-md-7 col-sm-12 col-xs-12">
    					<div class="card recent-sales overflow-auto">

    						<!-- <div class="filter">
    							<a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
    							<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
    								<li class="dropdown-header text-start">
    									<h6>Filter</h6>
    								</li>

    								<li><a class="dropdown-item" href="#">Today</a></li>
    								<li><a class="dropdown-item" href="#">This Month</a></li>
    								<li><a class="dropdown-item" href="#">This Year</a></li>
    							</ul>
    						</div> -->

    						<div class="card-body">
    							<h5 class="card-title">Hutang Dagang</h5>

    							<table class="table table-borderless datatable datatable-primary">
    								<thead>
    									<tr>
    										<th scope="col">Tanggal</th>
    										<th scope="col">No. Faktur</th>
    										<th scope="col">Keterangan</th>
    										<th scope="col">Total</th>
    										<th scope="col">Bayar</th>
    									</tr>
    								</thead>
    								<tbody>
									<?php
										$p      = new Paging;
										$batas  = 5;
										$posisi = $p->cariPosisi($batas);
										
										$query=mysql_query("select * from kd_pemb where status='tempo' limit $posisi,$batas") or die (mysql_error());
										$total=mysql_fetch_array(mysql_query("select sum(total) as total from kd_pemb where status='tempo' order by tanggal asc"));
										
										if(mysql_num_rows($query)){
											while($row_tran=mysql_fetch_array($query)){?>
												<tr>
													<td><div align="center"><?php echo $row_tran['tanggal'];?></div></td>
													<td><div align="center"><?php echo $row_tran['nofaktur'];?></div></td>
													<td align="center">Hutang Dagang</td>
													<td align="right"><?php echo number_format($row_tran['total'],2,'.',','); ?></td>
													<td><?php echo"<a href=hutang.$row_tran[kd_pmb]><span>Detail</span>"?></td>
												</tr>
											<?php } ?>
											<tr>
												<td colspan="3"><div align="center"><strong>TOTAL Hutang</strong></div></td>
												<td style="display: none"></td>
												<td style="display: none"></td>
												<td align="right"><strong><?php echo number_format($total['total'],2,'.',','); ?></strong></td>
												<td style="display: none"></td>
											</tr>    
											<?php } else { ?>
											<?php
											}
											?>  
										
										</tbody>
    							</table>

    						</div>

    					</div>
    				</div><!-- End Hutang Table -->

    			</div>
    		</div><!-- End Left side columns -->

    	</div>
    </section>

	<script>
	function LiveTime() {
        $.ajax({
            url: 'Inc/timestamp.php',
            success: function(data) {
                $('.timeNow').html(data);
            },
        });
    }
	
	setInterval(LiveTime, 1000);
	const tbFalse = document.querySelector('.tb-false')
    new simpleDatatables.DataTable(tbFalse, {
        'sortable' : false
    });
	</script>