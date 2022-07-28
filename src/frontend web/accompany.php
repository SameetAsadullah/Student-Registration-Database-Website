<!DOCTYPE html>
<html>
<head>
	<title>For Staff Homepage - HAMARAY BACHAY</title>
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
			    <a class="active" href="accompany.php">Accompany</a>
			    <a href="forStaff.php">For Staff</a>
		  </div>
	</div>
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
	<h1 align="center">STUDENT ACCOMPANYING FORM</h1>
	<p align="center"><span class="error">* required field</span></p>
	<form action="accompany.php" method="post">
		<h2 align="center">Students Information</h2>
		<table align="center">
			<tr>
				<td>ID</td>
				<td colspan="2"><input type="Number" name="STU_ID" id="STU_ID" required="required"></td>
				<td><span class="error">*</span></td>
			</tr>
			<tr>
				<td>Name</td>
				<td colspan="2"><input type="Text" name="STU_NAME" id="STU_NAME" required="required"></td>
				<td><span class="error">*</span></td>
			</tr>
			<tr>
				<td>Class</td>
				<td colspan="2"><input type="Number" name="STU_CLASS" id="STU_CLASS" required="required"></td>
				<td><span class="error">*</span></td>
			</tr>
		</table>
		<br>
		<h2 align="center">Accompanying Guardians Information</h2>
		<table align="center">
			<tr>
				<td>ID</td>
				<td colspan="2"><input type="Number" name="G_ID" id="G_ID" required="required"></td>
				<td><span class="error">*</span></td>
			</tr>
			<tr>
				<td>Name</td>
				<td colspan="2"><input type="Name" name="G_NAME" id="G_NAME" required="required"></td>
				<td><span class="error">*</span></td>
			</tr>
			<tr>
				<td>Pregnant</td>
				<td><input type="radio" name="G_PREGNANT" id="G_PREGNANT" value = "1" required="required">Yes</td>
				<td><input type="radio" name="G_PREGNANT" id="G_PREGNANT" value = "0" required="required">No</td>
				<td><span class="error">*</span></td>
			</tr>
			<tr>
				<td>Reason for Parents Absence</td>
				<td colspan = "2"><input type="text" name="G_REASON" id="G_REASON"></td>
			</tr>
		</table>
		<br>
		<div style="text-align: center">
			<input type="Submit" value="Submit" name="Submit" align="center" style="position: center">
		</div>
	</form>
	<?php
		if(isset($_POST["Submit"]))
		{
			$studentName = 0;
			$studentClass = 0;
			$studentClassID = 0;
			$studentID = 0;
			$guardianName = 0;
			$offeredCourseID = 0;
			$courseID = 0;
			$actualGuardianName = 0;
			$guardianGender = 0;
			$studentAge = 0;

			//========================================================================================
			//----------------------------------- [MAIN CHECK CODE] ----------------------------------
			//========================================================================================

			//-----------------Checking for Student

			$sql_select = "SELECT NAME, ID, AGE FROM STUDENT WHERE ROLL_NO = ".$_POST["STU_ID"];
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				//Empty	
			}			
			else
			{
				echo "ERROR: IN SEARCHING FOR STUDENT ID!!!<br>";
			}
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$studentName = $row["NAME"];
				$studentID = $row["ID"];
				$studentAge = $row["AGE"];
			}
		
			//-----------------Checking for Class

			if ($studentID != 0) {
				$sql_select = "SELECT MAX(CLASS_ID) FROM REGISTERED WHERE STUDENT_ID = ".$studentID;
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN SEARCHING FOR CLASS!!!<br>";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$studentClassID = $row["MAX(CLASS_ID)"];
				}
				
				if($studentClassID != 0) {
					$sql_select = "SELECT CLASS_NO FROM CLASS WHERE CLASS_ID = ".$studentClassID;
					$query_id = oci_parse($con, $sql_select);
					$runInsert = oci_execute($query_id);

					if ($runInsert) {
						//Empty
					}
					else {
						echo "ERROR: IN SEARCHING FOR CLASS!!!<br>";
					}
					while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
					{
						$studentClass = $row["CLASS_NO"];
					}
				}

				else {
					echo "<br><center><b><u>YOUR CHILD HASN'T REGISTERED IN ANY COURSE YET!!!</u></b></center>";
				}
			}

			//-----------------Checking for Guardian

			$sql_select = "SELECT NAME FROM GUARDIAN WHERE ID = ".$_POST["G_ID"];
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				//Empty
			}			
			else
			{
				echo "ERROR: IN SEARCHING FOR GUARDIAN ID!!!<br>";
			}
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$guardianName = $row["NAME"];
			}

			//-----------------Getting Actual Guardian Name of given student

			if ($studentID != 0) {
				$sql_select = "SELECT NAME FROM GUARDIAN WHERE ID = (SELECT GUARDIAN_ID FROM STUDENT WHERE ID = ".$studentID.")";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if ($runInsert) {
					//Empty
				}
				else {
					echo "ERROR: CANT GET COURSE_ID OF GIVEN STUDENT!!!<br>";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$actualGuardianName = $row["NAME"];
				}
			}

			//-----------------Getting gender of guardian
			if ($guardianName === $actualGuardianName) {
				$sql_select = "SELECT GENDER FROM GUARDIAN WHERE ID = ".$_POST["G_ID"];
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if($runInsert)
				{
					//Empty
				}			
				else
				{
					echo "ERROR: IN SEARCHING FOR GUARDIAN!!!<br>";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$guardianGender = $row["GENDER"];
				}
			}

			//========================================================================================
			//--------------------------------- [MAIN INSERTION CODE] --------------------------------
			//========================================================================================

			if (($studentName === $_POST["STU_NAME"]) && ($guardianName === $_POST["G_NAME"]) && ($guardianName === $actualGuardianName) && 
				($studentClass === $_POST["STU_CLASS"]) && ($guardianGender === "F") && ($studentAge > 0 && $studentAge <= 6)) {

				//-----------------Getting CourseID of course being offered
				$sql_select = "SELECT MAX(COURSE_ID) FROM COURSE";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if ($runInsert) {
					//Empty
				}
				else {
					echo "ERROR: CANT GET COURSE_ID of course being offered!!!<br>";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$offeredCourseID = $row["MAX(COURSE_ID)"];
				}

				//-----------------Getting courseID of given student and class
				$sql_select = "SELECT MAX(COURSE_ID) FROM REGISTERED WHERE STUDENT_ID = " . $studentID . " AND CLASS_ID = " . $studentClassID;
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				if ($runInsert) {
					//Empty
				}
				else {
					echo "ERROR: CANT GET COURSE_ID OF GIVEN STUDENT!!!<br>";
				}
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$courseID = $row["MAX(COURSE_ID)"];
				}

				if ($courseID != 0 && $offeredCourseID != 0 && $courseID === $offeredCourseID) {
					
					//-----------------Checking if information already exists
					$sql_select = "SELECT * FROM ACCOMPANY WHERE COURSE_ID = ".$courseID." AND CLASS_ID = ".$studentClassID." AND STUDENT_ID = ".$studentID.
					" AND GUARDIAN_ID = ".$_POST["G_ID"];
					$query_id = oci_parse($con, $sql_select);
					$runInsert = oci_execute($query_id);
					if($runInsert)
					{
						$row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS);
						if($row) {
							echo "<br><center><b><u>INFORMATION ALREADY EXISTS IN DATABASE!!!</u></b></center>";
						}	
						else {
							//-----------------Inserting the data
							$sql_select = "INSERT INTO ACCOMPANY (COURSE_ID, CLASS_ID, STUDENT_ID, GUARDIAN_ID, A_DATE, REASON, PREGNANT) VALUES (".
							$courseID.",".$studentClassID.",".$studentID.",".$_POST["G_ID"].", TO_DATE('".date("Y/m/d")."', 'YYYY-MM-DD'),'".
							$_POST["G_REASON"]."', ".$_POST["G_PREGNANT"].")";
							$query_id = oci_parse($con, $sql_select);
							$runInsert = oci_execute($query_id);
							if($runInsert)
							{
								echo "<br><center><b><u>DONE</u></b></center>";
							}			
							else
							{
								echo "ERROR: CANT INSERT DATA!!!<br>";
							}
						}
					}			
					else
					{
						echo "ERROR: CANT ACCESS DATA FOR ACCOMPANY!!!<br>";
					}
				}
				else {
					echo "<br><center><b><u>YOUR CHILD HASN'T REGISTERED IN THE COURSE BEING OFFERED!!!</u></b></center>";
				}
			}

			if ($studentName != $_POST["STU_NAME"] || ($studentClass != $_POST["STU_CLASS"] &&
				$studentClassID != 0) || $actualGuardianName === 0 || $guardianName != $actualGuardianName) {
				echo "<br><center><b><u>INCORRECT STUDENT INFORMATION!!!</u></b></center>";
			}

			else if ($studentAge > 6) {
				echo "<br><center><b><u>YOUR CHILD IS OLD ENOUGH TO COME ALONE!!!</u></b></center>";
			}

			if ($guardianName != $_POST["G_NAME"] || $guardianName === 0) {
				echo "<br><center><b><u>INCORRECT GUARDIAN INFORMATION!!!</u></b></center>";
			}

			if ($guardianGender === "M") {
				echo "<br><center><b><u>MALE GUARDIAN CAN'T ACCOMPANY THE CHILD!!!</u></b></center>";
			}
		}		
	?>
</body>
</html>