<!DOCTYPE html>
<html>
<head>
	<title>Challan Finalized - HAMARAY BACHAY</title>
	<style>
	.error {color: #FF0000;}
	</style>
	<style>
		table, tr
		{
			border: 1px solid black;
		}
		tr:hover {background-color:#f5f5f5;}
		body 
		{ 
		  margin: 0;
		  font-family: Arial, Helvetica, sans-serif;
		}
		.header 
		{
		  overflow: hidden;
		  background-color: #f1f1f1;
		  padding: 20px 10px;
		}
		.header a 
		{
		  float: left;
		  color: black;
		  text-align: center;
		  padding: 12px;
		  text-decoration: none;
		  font-size: 18px; 
		  line-height: 25px;
		  border-radius: 4px;
		}
		.header a.logo 
		{
		  font-size: 25px;
		  font-weight: bold;
		}
		.header a:hover 
		{
		  background-color: #ddd;
		  color: black;
		}
		.header a.active 
		{
		  background-color: dodgerblue;
		  color: white;
		}
		.header-right 
		{
		  float: right;
		}
		@media screen and (max-width: 500px) 
		{
		  .header a 
		  {
		    float: none;
		    display: block;
		    text-align: left;
		  }
		  
		  .header-right 
		  {
		    float: none;
		  }
		}
	</style>
</head>
<body>
	<div class="header">
		  <a href="#default" class="logo">HAMRAY BACHAY</a>
		  <div class="header-right">
			    <a href="insertion.php">Admission Form</a>
			    <a href="feeChallan.php">Course Registration</a>
			    <a href="accompany.php">Accompany</a>
			    <a class="active" href="forStaff.php">For Staff</a>
		  </div>
	</div>
	<?php
		$db_sid =    "(DESCRIPTION =
					    (ADDRESS = (PROTOCOL = TCP)(HOST = DESKTOP-AR5Q8KO)(PORT = 1521))
					    (CONNECT_DATA =
					      (SERVER = DEDICATED)
					      (SERVICE_NAME = nabeel)
					    )
					  	)";
		$db_user = "scott";
		$db_pass = "1234";
		$con = oci_connect($db_user, $db_pass, $db_sid);
		if($con)
		{
			//Empty
		}
		else
		{
			die("Could not Connect to Oracle!!!");
		} 

		$err = "";

		$sql_update = "UPDATE CHALLAN SET FEE = ".$_POST["FEE"].", DISCOUNT = ".$_POST["DISCOUNT"].", PAID_STATUS = ".
						$_POST["PAID_STATUS"].", FINAL_AMOUNT = ".$_POST["FINAL_AMOUNT"]." WHERE CHALLAN_NO = ".$_POST["CHALLAN_NO"];
		$query_id = oci_parse($con, $sql_update);
		$runInsert = oci_execute($query_id);
		if($runInsert)
		{
			$err = "Fee Challan Updated Successfully";
		} 
		else
		{
			$err = "Error! in Updating Fee Challan!";
		}
	?>
	<h1 align="center"><?php echo $err; ?></h1>
	<br>
	<p align="center">Use the Challan Number <?php echo $_POST["CHALLAN_NO"] ?> in Registration</p>
</body>
</html>