<!DOCTYPE html>
<html>
<head>
	<title>Challan Generator - HAMARAY BACHAY</title>
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
		$name = "";
		$rollno = 0;

		$challanNumber = 0;
		$fee = 0;
		$discount = 0;
		$paid_status = 0;
		$final_amount = 0;

		$alreadyExist = "";
		$err = "";

		$stu_id = 0;
		$courseID = 0;

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
		if(isset($_POST["Submit"]))
		{
			//--------------Get the Student ID based on Name or Roll No
			if(!empty($_POST["ROLLNO"]))
			{
				$sql_select = "SELECT * FROM STUDENT WHERE ROLL_NO = ".$_POST["ROLLNO"];
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$stu_id = $row["ID"];
					$name = $row["NAME"];
					$rollno = $row["ROLL_NO"];
				}
				if($stu_id == 0)
				{
					$err = "Roll Number Not Found!";
				}
			}
			if(!empty($_POST["NAME"]))
			{
				$sql_select = "SELECT * FROM STUDENT WHERE NAME LIKE '".$_POST["NAME"]."%'";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$stu_id = $row["ID"];
					$name = $row["NAME"];
					$rollno = $row["ROLL_NO"];
					$err = "";
				}
				if($stu_id == 0)
				{
					$err = "Name Not Found!";
				}
			}
			//==========================================================================================================
			//--------------------------------------------[ Main Logic Code ]-------------------------------------------
			//==========================================================================================================
			if($err == "")
			{
				//--------------Get the Latest Course ID
				$sql_select = "SELECT MAX(COURSE_ID) AS COURSE_ID FROM COURSE";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$courseID = $row["COURSE_ID"];
				}

				//--------------Check if Challan Already Inserted
				$sql_select = "SELECT * FROM CHALLAN WHERE COURSE_ID = ".$courseID." AND STUDENT_ID = ".$stu_id;
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$challanNumber = $row["CHALLAN_NO"];
					$fee = $row["FEE"];
					$discount = $row["DISCOUNT"];
					$paid_status = $row["PAID_STATUS"];
					$final_amount = $row["FINAL_AMOUNT"];

					$alreadyExist = "Challan Already Existed";
				}


				//--------------Insert the New Challan
				if($challanNumber == 0)
				{
					$sql_select = "INSERT INTO CHALLAN(COURSE_ID, STUDENT_ID, REG_DATE, PAID_STATUS) VALUES(".
									$courseID.", ".$stu_id.", TO_DATE('".date("Y/m/d")."', 'YYYY-MM-DD'), 0)";
					$query_id = oci_parse($con, $sql_select);
					$runInsert = oci_execute($query_id);
				}

				//-------------Retrieving the Data and storing in Variables
				$sql_select = "SELECT * FROM CHALLAN WHERE COURSE_ID = ".$courseID." AND STUDENT_ID = ".$stu_id;
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$challanNumber = $row["CHALLAN_NO"];
					$fee = $row["FEE"];
					$discount = $row["DISCOUNT"];
					$paid_status = $row["PAID_STATUS"];
					$final_amount = $row["FINAL_AMOUNT"];
				} 
			}//End If = Error 	
		}//End If = POST Happened
	?>
	<p align="center" style="color: red"><?php echo $err ?></p>
	<h1 align="center">Generate Challan</h1>
	<p align="center">Search for Student (Can be performed with either Roll No or Name)</p>
	<form action="challan.php" method="post">
		<table align="center">
			<tr>
				<td>
					<input type="Text" name="NAME">
				</td>
				<td>Name</td>
			</tr>
			<tr>
				<td>
					<input type="Number" name="ROLLNO">
				</td>
				<td>Roll No</td>
			</tr>
		</table>
		<br>
		<div style="text-align: center">
			<input type="Submit" name="Submit" value="Generate">
		</div>

		<h1 align="center">Challan Details</h1>
		<p align="center" style="color: red"><?php echo $alreadyExist; ?></p>
	</form>
	<form action="finalizeChallan.php" method="post">
		<table align="center">
			<tr>
				<td>Student Name</td>
				<td></td>
				<td>
					<?php echo $name ?>
				</td>
			</tr>
			<tr>
				<td>Student Roll No</td>
				<td></td>
				<td>
					<?php echo $rollno ?>
				</td>
			</tr>
			<tr>
				<td>Challan Number</td>
				<td></td>
				<td>
					<input type="Number" name="CHALLAN_NO" id="CHALLAN_NO" value="<?php echo $challanNumber ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>Fees</td>
				<td></td>
				<td>
					<input type="Number" name="FEE" id="FEE" value="<?php echo $fee ?>">
				</td>
			</tr>
			<tr>
				<td>Discount</td>
				<td></td>
				<td>
					<input type="Number" name="DISCOUNT" id="DISCOUNT" value="<?php echo $discount ?>">
				</td>
			</tr>
			<tr>
				<td>Paid Status</td>
				<td></td>
				<td>
					<input type="Number" name="PAID_STATUS" id="PAID_STATUS" value="<?php echo $paid_status ?>">
				</td>
			</tr>
			<tr>
				<td>Final Amount</td>
				<td></td>
				<td>
					<input type="Number" name="FINAL_AMOUNT" id="FINAL_AMOUNT" value="<?php echo $final_amount ?>">
				</td>
			</tr>
		</table>
		<br>
		<div style="text-align: center">
			<input type="Submit" name="Submit" value="Finalize">
		</div>
	</form>
</body>
</html>