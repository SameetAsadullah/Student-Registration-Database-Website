<!DOCTYPE html>
<html>
<head>
	<title>Admission Form - HAMARAY BACHAY</title>
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
		  <a href="#default" class="logo">HAMARAY BACHAY</a>
		  <div class="header-right">
			    <a class="active" href="insertion.php">Admission Form</a>
			    <a href="feeChallan.php">Course Registration</a>
			    <a href="accompany.php">Accompany</a>
			    <a href="forStaff.php">For Staff</a>
		  </div>
	</div>
	<?php
		//===========================================================================================
		//----------------------------------[ INPUT VALIDATION ]-------------------------------------
		$isValid = true;

		//Error Variables
		$stu_name_err = "";
		$stu_dob_err = "";
		$stu_gen_err = "";

		$m_name_err = "";
		$m_contact_err = "";
		$m_cnic_err = "";
		$m_email_err = "";
		$m_address_err = "";

		$f_name_err = "";
		$f_contact_err = "";
		$f_cnic_err = "";
		$f_email_err = "";
		$f_address_err = "";

		$g_name_err = "";
		$g_contact_err = "";
		$g_cnic_err = "";
		$g_relation_err = "";

		//Student Name should not be null
		if(isset($_POST["Submit"]))
		{
			//-----------------------All Checks on Student
			//Required Name
			if(empty($_POST["STU_NAME"]))
			{
				$stu_name_err = "Name is Required!";
				$isValid = false;
			}
			//Required DOB
			if(empty($_POST["STU_DOB"]))
			{
				$stu_dob_err = "DOB is Required!";
				$isValid = false;
			}
			//Age Above 15
			$_age = floor((time() - strtotime($_POST["STU_DOB"])) / 31556926);
			if($_age > 15)
			{
				$stu_dob_err = "Student Age is not suitable!";
				$isValid = false;
			}
			//Required Gender
			if(empty($_POST["STU_GENDER"]))
			{
				$stu_gen_err = "Gender is Required!";
				$isValid = false;
			}
			//-----------------------All Checks on Mother
			//Required Name
			if(empty($_POST["M_NAME"]))
			{
				$m_name_err = "Name is Required!";
				$isValid = false;
			}
			//Required Contact
			if(empty($_POST["M_CONTACT"]))
			{
				$m_contact_err = "Contact is Required!";
				$isValid = false;
			}
			//Required CNIC
			if(empty($_POST["M_CNIC"]))
			{
				$m_cnic_err = "CNIC is Required!";
				$isValid = false;
			}
			//Length of CNIC == 13
			if(strlen($_POST["M_CNIC"]) != 13)
			{
				$m_cnic_err = "CNIC must be 13 characters!";
				$isValid = false;
			}
			//Required Email
			if(empty($_POST["M_EMAIL"]))
			{
				$m_email_err = "Email is Required!";
				$isValid = false;
			}
			//Required Address
			if(empty($_POST["M_ADDRESS"]))
			{
				$m_address_err = "Address is Required!";
				$isValid = false;
			}
			//-----------------------All Checks on Father
			//Required Name
			if(empty($_POST["F_NAME"]))
			{
				$f_name_err = "Name is Required!";
				$isValid = false;
			}
			//Required Contact
			if(empty($_POST["F_CONTACT"]))
			{
				$f_contact_err = "Contact is Required!";
				$isValid = false;
			}
			//Required CNIC
			if(empty($_POST["F_CNIC"]))
			{
				$f_cnic_err = "CNIC is Required!";
				$isValid = false;
			}
			//Length of CNIC == 13
			if(strlen($_POST["F_CNIC"]) != 13)
			{
				$f_cnic_err = "CNIC must be 13 characters!";
				$isValid = false;
			}
			//Required Email
			if(empty($_POST["F_EMAIL"]))
			{
				$f_email_err = "Email is Required!";
				$isValid = false;
			}
			//Required Address
			if(empty($_POST["F_ADDRESS"]))
			{
				$f_address_err = "Address is Required!";
				$isValid = false;
			}
			//-----------------------All Checks on Guardian
			//Required Name
			if(empty($_POST["G_NAME"]))
			{
				$g_name_err = "Name is Required!";
				$isValid = false;
			}
			//Required Contact
			if(empty($_POST["G_CONTACT"]))
			{
				$g_contact_err = "Contact is Required!";
				$isValid = false;
			}
			//Required CNIC
			if(empty($_POST["G_CNIC"]))
			{
				$g_cnic_err = "CNIC is Required!";
				$isValid = false;
			}
			//Length of CNIC == 13
			if(strlen($_POST["G_CNIC"]) != 13)
			{
				$g_cnic_err = "CNIC must be 13 characters!";
				$isValid = false;
			}
			//Required Relation
			if(empty($_POST["G_RELATION"]))
			{
				$g_relation_err = "Relation is Required!";
				$isValid = false;
			}
		}
	?>
	<?php
		//=========================================================================================
		//--------------------------[ Creating the Database Connection ]---------------------------
		//=========================================================================================
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
	?>
	<h1 align="center">STUDENT ADMISSION FORM</h1>
	<p align="center"><span class="error">* required field</span></p>
	<form action="insertion.php" method="post">
		<h2 align="center">Students Information</h2>
		<table align="center">
			<tr>
				<td>Name</td>
				<td colspan="2"><input type="Text" name="STU_NAME" id="STU_NAME"></td>
				<td><span class="error">* <?php echo $stu_name_err;?></span></td>
			</tr>
			<tr>
				<td>Date of Birth</td>
				<td colspan="2"><input type="Date" name="STU_DOB" id="STU_DOB"></td>
				<td><span class="error">* <?php echo $stu_dob_err;?></span></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td><input type="radio" name="STU_GENDER" id="STU_GENDER" value="M">Male</td>
				<td><input type="radio" name="STU_GENDER" id="STU_GENDER" value="F">Female</td>
				<td><span class="error">* <?php echo $stu_gen_err;?></span></td>
			</tr>
		</table>
		<br>
		<h2 align="center">Parents Information</h2>
		<table align="center">
			<tr>
				<td style="text-align: center;">Mother</td>
				<td style="text-align: center;">Father</td>
			</tr>
			<tr>
				<td>
					<table align="center">
						<tr>
							<td>Name</td>
							<td colspan="2"><input type="Text" name="M_NAME" id="M_NAME"></td>
							<td><span class="error">* <?php echo $m_name_err;?></span></td>
						</tr>
						<tr>
							<td>Contact</td>
							<td colspan="2"><input type="Text" name="M_CONTACT" id="M_CONTACT"></td>
							<td><span class="error">* <?php echo $m_contact_err;?></span></td>
						</tr>
						<tr>
							<td>CNIC</td>
							<td colspan="2"><input type="Text" name="M_CNIC" id="M_CNIC"></td>
							<td><span class="error">* <?php echo $m_cnic_err;?></span></td>
						</tr>
						<tr>
							<td>Email</td>
							<td colspan="2"><input type="Text" name="M_EMAIL" id="M_EMAIL"></td>
							<td><span class="error">* <?php echo $m_email_err;?></span></td>
						</tr>
						<tr>
							<td>Address</td>
							<td colspan="2"><input type="Text" name="M_ADDRESS" id="M_ADDRESS"></td>
							<td><span class="error">* <?php echo $m_address_err;?></span></td>
						</tr>
						<tr>
							<td>Staff Member</td>
							<td><input type="radio" name="M_STAFF" id="M_STAFF" value="1">Yes</td>
							<td><input type="radio" name="M_STAFF" id="M_STAFF" value="0">No</td>
						</tr>
					</table>
				</td>
				<td>
					<table align="center">
						<tr>
							<td>Name</td>
							<td colspan="2"><input type="Text" name="F_NAME" id="F_NAME"></td>
							<td><span class="error">* <?php echo $f_name_err;?></span></td>
						</tr>
						<tr>
							<td>Contact</td>
							<td colspan="2"><input type="Text" name="F_CONTACT" id="F_CONTACT"></td>
							<td><span class="error">* <?php echo $f_contact_err;?></span></td>
						</tr>
						<tr>
							<td>CNIC</td>
							<td colspan="2"><input type="Text" name="F_CNIC" id="F_CNIC"></td>
							<td><span class="error">* <?php echo $f_cnic_err;?></span></td>
						</tr>
						<tr>
							<td>Email</td>
							<td colspan="2"><input type="Text" name="F_EMAIL" id="F_EMAIL"></td>
							<td><span class="error">* <?php echo $f_email_err;?></span></td>
						</tr>
						<tr>
							<td>Address</td>
							<td colspan="2"><input type="Text" name="F_ADDRESS" id="F_ADDRESS"></td>
							<td><span class="error">* <?php echo $f_address_err;?></span></td>
						</tr>
						<tr>
							<td>Staff Member</td>
							<td><input type="radio" name="F_STAFF" id="F_STAFF" value="1">Yes</td>
							<td><input type="radio" name="F_STAFF" id="F_STAFF" value="0">No</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<h2 align="center">Guardians Information</h2>
		<table align="center">
			<tr>
				<td>Name</td>
				<td colspan="2"><input type="Text" name="G_NAME" id="G_NAME"></td>
				<td><span class="error">* <?php echo $g_name_err;?></span></td>
			</tr>
			<tr>
				<td>Contact</td>
				<td colspan="2"><input type="Text" name="G_CONTACT" id="G_CONTACT"></td>
				<td><span class="error">* <?php echo $g_contact_err;?></span></td>
			</tr>
			<tr>
				<td>CNIC</td>
				<td colspan="2"><input type="Text" name="G_CNIC" id="G_CNIC"></td>
				<td><span class="error">* <?php echo $g_cnic_err;?></span></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td><input type="radio" name="G_GENDER" id="G_GENDER" value="M">Male</td>
				<td><input type="radio" name="G_GENDER" id="G_GENDER" value="F">Female</td>
			</tr>
			<tr></tr>
			<tr>
				<td>Relation</td>
				<td colspan="2"><input type="Text" name="G_RELATION" id="G_RELATION"></td>
				<td><span class="error">* <?php echo $g_relation_err;?></span></td>
			</tr>
		</table>
		<br>
		<div style="text-align: center">
			<input type="Submit" value="SIGN UP" name="Submit" align="center" style="position: center">
		</div>
	</form>
	<?php
		//Creating the Database Connection
		if(isset($_POST["Submit"]) && $isValid == true)
		{
			$studentID = 0;
			$motherID = 0;
			$fatherID = 0;
			$guardianID = 0;

			//========================================================================================
			//----------------------------------- [MAIN CHECK CODE] ----------------------------------
			//========================================================================================

			//-----------------Checking for Student

			$sql_select = "SELECT ID FROM STUDENT WHERE NAME = '".$_POST["STU_NAME"]."'";
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				//Empty
			}			
			else
			{
				echo "ERROR: IN SEARCHING FOR STUDENT FROM STUDENT!!!";
			}
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$studentID = $row["ID"];
			}

			//-----------------Checking for Mother

			$sql_select = "SELECT ID FROM GUARDIAN WHERE CNIC = '".$_POST["M_CNIC"]."'";
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				//Empty
			}			
			else
			{
				echo "ERROR: IN SEARCHING FOR MOTHER FROM GUARDIAN!!!";
			}
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$motherID = $row["ID"];
			}

			//-----------------Checking for Father

			$sql_select = "SELECT ID FROM GUARDIAN WHERE CNIC = '".$_POST["F_CNIC"]."'";
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				//Empty
			}			
			else
			{
				echo "ERROR: IN SEARCHING FOR FATHER FROM GUARDIAN!!!";
			}
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$fatherID = $row["ID"];
			}

			//-----------------Checking for Guardian

			$sql_select = "SELECT ID FROM GUARDIAN WHERE CNIC = '".$_POST["G_CNIC"]."'";
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				//Empty
			}			
			else
			{
				echo "ERROR: IN SEARCHING FOR GUARDIAN FROM GUARDIAN!!!";
			}
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$guardianID = $row["ID"];
			}

			//========================================================================================
			//--------------------------------- [MAIN INSERTION CODE] --------------------------------
			//========================================================================================

			//----------------------Inserting the Mother into the Guardian
			if($motherID == 0)
			{
				$sql_update = "INSERT INTO GUARDIAN (NAME, GENDER, CNIC, CONTACT, EMAIL, ADDRESS, IS_PARENT, IS_STAFF)".
							" VALUES('".$_POST["M_NAME"]."','F','".$_POST["M_CNIC"]."','".$_POST["M_CONTACT"].
							"','".$_POST["M_EMAIL"]."','".$_POST["M_ADDRESS"]."', 1, ".$_POST["M_STAFF"].")";

				$query_id = oci_parse($con, $sql_update);
				$runInsert = oci_execute($query_id);			
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN INSERTION OF MOTHER INTO GUARDIAN!!!";
				}
			}

			//----------------------Inserting the Father into the Guardian

			if($fatherID == 0)
			{
				$sql_update = "INSERT INTO GUARDIAN (NAME, GENDER, CNIC, CONTACT, EMAIL, ADDRESS, IS_PARENT, IS_STAFF)".
							" VALUES('".$_POST["F_NAME"]."','M','".$_POST["F_CNIC"]."','".$_POST["F_CONTACT"].
							"','".$_POST["F_EMAIL"]."','".$_POST["F_ADDRESS"]."', 1, ".$_POST["F_STAFF"].")";

				$query_id = oci_parse($con, $sql_update);
				$runInsert = oci_execute($query_id);			
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN INSERTION OF FATHER INTO GUARDIAN!!!";
				}
			}

			//-----------------------Inserting the Guardian into the Guardian

			if($guardianID == 0)
			{
				$sql_update = "INSERT INTO GUARDIAN (NAME, GENDER, CNIC, CONTACT, IS_PARENT, IS_STAFF, RELATION)".
				" VALUES('".$_POST["G_NAME"]."', '".$_POST["G_GENDER"]."', '".$_POST["G_CNIC"]."', '".
				$_POST["G_CONTACT"]."', 0, 0, '".$_POST["G_RELATION"]."')";

				$query_id = oci_parse($con, $sql_update);
				$runInsert = oci_execute($query_id);			
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN INSERTION OF GUARDIAN INTO GUARDIAN!!!";
				}
			}

			//----------------------------------------------------------------------------
			//-----------------[ SEARCHING FOR PARENT AND GUARDIAN IDS ]------------------
			//----------------------------------------------------------------------------

			//----------------Retrieving Mother ID
			if($motherID == 0)
			{
				$sql_select = "SELECT ID FROM GUARDIAN WHERE CNIC = '".$_POST["M_CNIC"]."'";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN SELECTION OF MOTHER_ID";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$motherID = $row["ID"];
				}
			}

			//----------------Retrieving Father ID
			if($fatherID == 0)
			{
				$sql_select = "SELECT ID FROM GUARDIAN WHERE CNIC = '".$_POST["F_CNIC"]."'";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN SELECTION OF FATHER_ID";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$fatherID = $row["ID"];
				}
			}

			//----------------Retrieving Guardian ID
			if($guardianID == 0)
			{
				$sql_select = "SELECT ID FROM GUARDIAN WHERE CNIC = '".$_POST["G_CNIC"]."'";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN SELECTION OF GUARDIAN_ID";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$guardianID = $row["ID"];
				}
			}

			//==============================================================================================
			//-----------------------------[ Inserting Into Student Table ]---------------------------------
			//==============================================================================================

			if($studentID == 0)
			{
				$sql_update = "INSERT INTO STUDENT(NAME, DOB, DATE_ADMITTED, GENDER, FATHER_ID, MOTHER_ID, GUARDIAN_ID)".
				" VALUES('".$_POST["STU_NAME"]."', TO_DATE('".$_POST["STU_DOB"]."', 'YYYY-MM-DD'), ". 
				"TO_DATE('".date("Y/m/d")."', 'YYYY-MM-DD'), '"
				.$_POST["STU_GENDER"]."', ".$fatherID.", ".$motherID.", ".$guardianID.")";

				$query_id = oci_parse($con, $sql_update);
				$runInsert = oci_execute($query_id);
				if($runInsert)
				{
					//Empty
					echo "Student Successfully Registerd!";
					$isSubmit = true;
					echo "<script> location.href='admissionSuccess.php'; </script>";
					exit;
				}			
				else
				{
					echo "ERROR: IN INSERTION OF STUDENT";
				}
			}
			else
			{
				echo "STUDENT ALREADY EXIST IN DATABASE";
			}	
		}		
	?>
</body>
</html>