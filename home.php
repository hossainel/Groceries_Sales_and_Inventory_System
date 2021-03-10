<style>
   
</style>

<div class="containe-fluid">

	<div class="row">
		<div class="col-lg-12">
			
		</div>
	</div>

	<div class="row mt-3 ml-3 mr-3">
			<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
				<?php echo "Welcome back ".$_SESSION['login_name']."!"  ?>
									
				</div>
				<hr>
				<div class="row md-3 ml-3">
					<div class="alert alert-success col-md-3 ml-3">
						<p><b><large>Total Sales Today</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where date(date_updated)= '".date('Y-m-d')."'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";

						 ?></large></b></p>
					</div>
					<div class="alert alert-success col-md-3 ml-3">
						<p><b><large>Total Sales This Month</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where date(date_updated)>= '".date('Y-m')."-01'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 //date('Y')."-".(date('m')-1)."-".date('d')
						 ?></large></b></p>
					</div>
					<div class="alert alert-danger col-md-3 ml-3">
						<p><b><large>Total Expence This Month</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM receiving_list where date(date_added)>= '".date('Y-m')."-01'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 //date('Y')."-".(date('m')-1)."-".date('d')
						 ?></large></b></p>
					</div>
					<div class="alert alert-primary col-md-3 ml-3">
						<p><b><large>Total Sales Last Month</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where date(date_updated)>= '".date('Y')."-".(date('m')-1)."-01' and date(date_updated)<= '".date('Y-m')."-01'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 ?></large></b></p>
					</div>
					<div class="alert alert-success col-md-3 ml-3">
						<p><b><large>Total Sales This Year</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where date(date_updated)>= '".date('Y')."-01-01'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 ?></large></b></p>
					</div>
					<div class="alert alert-danger col-md-3 ml-3">
						<p><b><large>Total Expense This Year</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM receiving_list where date(date_added)>= '".date('Y')."-01-01'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 //date('Y')."-".(date('m')-1)."-".date('d')
						 ?></large></b></p>
					</div>
					<div class="alert alert-primary col-md-3 ml-3">
						<p><b><large>Total Sales Last Year</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where date(date_updated)>= '".(date('Y')-1)."-01-01' and date(date_updated)<= '".date('Y')."-01-01'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 ?></large></b></p>
					</div>
					<div class="alert alert-danger col-md-3 ml-3">
						<p><b><large>Total Expense Last Year</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM receiving_list where date(date_added)<= '".date('Y')."-01-01' and date(date_added)>= '".(date('Y')-1)."-01-01'");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 ?></large></b></p>
					</div>
					<div class="alert alert-info col-md-3 ml-3">
						<p><b><large>Total Sales All Time</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where 1");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 ?></large></b></p>
					</div>
					<!-- row table -->
					<div class="alert alert-primary col-md-6 ml-3">
						<p><b><large>Select Date or Range</large></b></p>
					<hr>						
						<form action="" id="manage-sell_date">
							<div class="row md-3 ml-3">
							<div class="form-group">
								<label class="control-label">Start Date</label>
								<input type="date" class="form-control" name="s_date">
							</div>	
							<div class="form-group">
								<label class="control-label">End Date</label>
								<input type="date" class="form-control" name="e_date">
							</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Search</button>
									<button class="btn btn-sm btn-danger col-sm-3" type="button" onclick="$('#manage-supplier').get(0).reset()"> Reset</button>
								</div>
							</div>
						</form>
						
						<p class="text-center" id="m_amount" name="m_amount"><b><large></large></b></p>
					</div> <!-- -->
				</div>
			</div>
			
		</div>
		</div>
	</div>

</div>
<script>

</script>