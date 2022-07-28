<!DOCTYPE html>
<html>
<head>
	<title>Admission Success - HAMARAY BACHAY</title>
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
			    <a class="active" href="insertion.php">Admission Form</a>
			    <a href="feeChallan.php">Course Registration</a>
			    <a href="accompany.php">Accompany</a>
			    <a href="forStaff.php">For Staff</a>
		  </div>
	</div>
	<h2 align="center">Student Successfully Registered!</h2>
	<?php
		//=========================================================================================
		//--------------------------[ Creating the Database Connection ]---------------------------
		//=========================================================================================
		$currCourse = "";
		$currCourseID = "";

		$name = "";
		$rollno = "";
		$gender = "";
		$age = "";
		$dob = "";

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

		$sql_select = "SELECT * FROM STUDENT WHERE ID = (SELECT MAX(ID) FROM STUDENT)";
		$query_id = oci_parse($con, $sql_select);
		$runSelect = oci_execute($query_id);
		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			$name = $row["NAME"];
			$rollno = $row["ROLL_NO"];
			$gender = $row["GENDER"];
			$age = $row["AGE"];
			$dob = $row["DOB"];
		}

		$sql_select = "SELECT * FROM COURSE WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)";
		$query_id = oci_parse($con, $sql_select);
		$runSelect = oci_execute($query_id);
		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			$currCourse = $row["NAME"];
			$currCourseID = $row["COURSE_ID"];
		}
	?>
	<br>
	<h1 align="center">Students Data</h1>
	<form action="feeChallan.php" method="post">
		<table align="center">
			<tr>
				<td>Name</td>
				<td><input type="Text" name="NAME" id="NAME" value="<?php echo $name ?>" readonly></td>
			</tr>
			<tr>
				<td>Roll No</td>
				<td><input type="Text" name="ROLL_NO" id="ROLL_NO" value="<?php echo $rollno ?>" readonly></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td><input type="Text" name="GENDER" id="GENDER" value="<?php echo $gender ?>" readonly></td>
			</tr>
			<tr>
				<td>Age</td>
				<td><input type="Text" name="AGE" id="AGE" value="<?php echo $age ?>" readonly></td>
			</tr>
			<tr>
				<td>DOB</td>
				<td><input type="Text" name="DOB" id="DOB" value="<?php echo $dob ?>" readonly></td>
			</tr>
		</table>
		<br><br><br>
		<h1 align="center">We are offering the Course</h1>
		<table align="center">
			<tr>
				<td><input type="Text" name="COURSE_ID" id="COURSE_ID" value="<?php echo $currCourseID ?>" readonly></td>
				<td><input type="Text" name="CNAME" id="CNAME" value="<?php echo $currCourse ?>" readonly></td>
			</tr>
		</table>
			<div style="text-align: center">
				<input type="Submit" name="Submit" value="Proceed to Course Registration">
			</div>
	</form>
</body>
</html>