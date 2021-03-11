<?php  
 $connect = mysqli_connect("localhost", "root", "", "sales_inventory_db");  
  
 
 ?>  
<style>
.m_amount {
	text-align: right;
}
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
					<div class="alert alert-warning col-md-3 ml-3">
						<p><b><large>Total Sales All Time</large></b></p>
					<hr>
						<p class="text-right"><b><large><?php 
						include 'db_connect.php';
						$sales = $conn->query("SELECT SUM(total_amount) as amount FROM sales_list where 1");
						echo $sales->num_rows > 0 ? number_format($sales->fetch_array()['amount'],2) : "0.00";
						 ?></large></b></p>
					</div>
					<!-- row table -->
					<div class="alert alert-info col-md-6 ml-3">
						<p><b><large>Select Date or Range</large></b></p>
					<hr>					
						<p class="m_amount" id="m_amount" name="m_amount"><b><large></large></b></p>					
						<form id="manage-sell_date">
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
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3" name="filter" id="filter"> Search</button>
									<button class="btn btn-sm btn-danger col-sm-3" type="button" onclick="$('#manage-sell_date').get(0).reset();$('#m_amount large').html('')"> Reset</button>
								</div>
							</div>
						</form>
					</div> <!-- -->
				</div>
			</div>
			
		</div>
		</div>
	</div>

</div>

<script>  
	$('#manage-sell_date').submit(function(e){
		e.preventDefault()
		$('#manage-sell_date button[type="button"]').attr('disabled',true);
		if($(this).find('.m_amount').length > 0 )
			$(this).find('.m_amount').remove();
		$.ajax({
			url:'ajax.php?action=select_date',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#manage-sell_date button[type="button"]').removeAttr('disabled').html('filter');

			},
			success:function(resp){
				$('#manage-sell_date button[type="button"]').removeAttr('disabled').html('Reset');
				$('#m_amount large').html(resp);
			}
		})
	})
 </script>
