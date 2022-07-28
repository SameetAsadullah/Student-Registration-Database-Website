<!DOCTYPE html>
<html>
<head>
	<title>Course Registration - HAMARAY BACHAY</title>
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
			    <a class="active" href="feeChallan.php">Course Registration</a>
			    <a href="accompany.php">Accompany</a>
			    <a href="forStaff.php">For Staff</a>
		  </div>
	</div>
	<h2 align="center">Course Registration</h2>
	<?php
		//=========================================================================================
		//--------------------------[ Creating the Database Connection ]---------------------------
		//=========================================================================================
		$rollNo = "";
		$courseID = "";
		$courseName = "";

		if(isset($_POST["ROLL_NO"]))
		{
			$rollNo = $_POST["ROLL_NO"];
		}
		if(isset($_POST["CNAME"]))
		{
			$courseName = $_POST["CNAME"];
		}

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
		$sql_select = "SELECT * FROM COURSE WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)";
		$query_id = oci_parse($con, $sql_select);
		$runSelect = oci_execute($query_id);
		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			$courseID = $row["COURSE_ID"];
			$courseName = $row["NAME"];
		}

		//Variables
		$err = "";
		$stu_id = 0;
		$motherId = 0;
		$fatherId = 0;
		$isValid = true;
		$isMotherStaff = 0;
		$isFatherStaff = 0;
		$challanNo = 0;
		$classNo = 0;
		$section = "";
		$className = "";

		//If the Register Button is presed do
		if(isset($_POST["Submit1"]))
		{
			//Getting the Student ID
			$sql_select = "SELECT * FROM STUDENT WHERE ROLL_NO = ".$_POST["ROLL_NO"];
			$query_id = oci_parse($con, $sql_select);
			$runSelect = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
				$motherId = $row["MOTHER_ID"];
				$fatherId = $row["FATHER_ID"];
			}
			//If Student not existing
			if($stu_id == 0)
			{
				$err = "Student not Found!";
				$isValid = false;
			}
			//Find if the Student is already registered
			$sql_select = "SELECT STUDENT_ID FROM REGISTERED WHERE COURSE_ID = ".$courseID." AND STUDENT_ID = ".$stu_id;
			$query_id = oci_parse($con, $sql_select);
			$runSelect = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$err = "Student is already registered in this Course";
				$isValid = false;
			}
			if($isValid)
			{
				//Checking for Parent is Guardian or not
				$sql_select = "SELECT IS_STAFF FROM GUARDIAN WHERE ID = ".$motherId;
				$query_id = oci_parse($con, $sql_select);
				$runSelect = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$isMotherStaff = $row["IS_STAFF"];
				}
				$sql_select = "SELECT IS_STAFF FROM GUARDIAN WHERE ID = ".$fatherId;
				$query_id = oci_parse($con, $sql_select);
				$runSelect = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$isFatherStaff = $row["IS_STAFF"];
				}
				//Check if we need a Challan
				if($isMotherStaff == 1 || $isFatherStaff == 1)
				{
					//Create a Challan
					$sql_insert = "INSERT INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES(".
								  $courseID.", ".$stu_id.", TO_DATE('".date("Y/m/d")."', 'YYYY-MM-DD'), 1)";

				    $query_id = oci_parse($con, $sql_insert);
					$runSelect = oci_execute($query_id);	
				}
				else
				{
					//Search for Challan in case of not Staff
					$sql_select = "SELECT CHALLAN_NO FROM CHALLAN WHERE COURSE_ID = ".$courseID." AND STUDENT_ID = ".$stu_id;
					$query_id = oci_parse($con, $sql_select);
					$runSelect = oci_execute($query_id);
					while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
					{
						$challanNo = $row["CHALLAN_NO"];
					}
					if($challanNo == 0)
					{
						$isValid = false;
						$err = "Challan not Paid for Registration. Pay Challan First";
					}
				}

				//------------------------------------MAIN INSERTION CODE---------------------------------------------
				if($isValid)
				{
					//Add the Student to Registered Table
					$sql_insert = "INSERT INTO REGISTERED(STUDENT_ID, COURSE_ID, REG_DATE) VALUES(".$stu_id.", ".$courseID.", ".
					 			  " TO_DATE('".date("Y/m/d")."', 'YYYY-MM-DD'))"; 

					$query_id = oci_parse($con, $sql_insert);
					$runInsert = oci_execute($query_id);
					if($runInsert)
					{
						//Get the Class ID where the Student went
						$sql_select = "SELECT CLASS_ID FROM REGISTERED WHERE STUDENT_ID = ".$stu_id." AND COURSE_ID = ".$courseID;  
						$query_id = oci_parse($con, $sql_select);
						$runInsert = oci_execute($query_id);
						while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
						{
							$classID = $row["CLASS_ID"];
						}
						//Get the Class No, Section, and Name
						$sql_select = "SELECT * FROM CLASS WHERE CLASS_ID = ".$classID;  
						$query_id = oci_parse($con, $sql_select);
						$runInsert = oci_execute($query_id);
						while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
						{
							$classNo = $row["CLASS_NO"];
							$section = $row["SECTION"];
							$className = $row["NAME"];
						}
						//Insert into Class Change Log
						$sql_insert = "INSERT INTO CLASS_LOG(STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES(".$stu_id.
						 			  ", ".$classNo.", '".$section."', 'Registered', 'Management')";	
						$query_id = oci_parse($con, $sql_insert);
						$runInsert = oci_execute($query_id);
						if($runInsert)
						{
							$err = "Student Registered to Class ".$classNo.$section." [".$className."]";
						}
					}
					else
					{
						$err = "Insertion Encountered a Problem!";
					}
				}//End If: is Valid

			}//End IF: is Valid	

		}//End IF: Submit
	?>
	<form action="feeChallan.php" method="post">
		<table align="center">
			<tr>
				<td>Roll Number</td>
				<td><input type="Text" name="ROLL_NO" value="<?php echo $rollNo?>"></td>
			</tr>
			<tr>
				<td>Course</td>
				<td><input type="Text" name="CNAME" value="<?php echo $courseName?>" readonly></td>
			</tr>
		</table>
		<br>
		<div style="text-align: center;">
			<input type="Submit" name="Submit1" id="Submit" value="Register for Course">
		</div>
	</form>
	<p align="center" style="color: red"><?php echo $err ?></p>
</body>
</html>