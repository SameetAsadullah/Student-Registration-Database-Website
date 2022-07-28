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
		if(isset($_POST["Submit"]))
		{
			//Insert a new course
			$sql_select = "INSERT INTO COURSE(NAME) VALUES('".$_POST["NAME"]."')";
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				$err = "Course Offered";
			}
			else
			{
				$err = "Error in Insertion!";
			}
		}
	?>
	<h1 align="center">Courses Information</h1>
	<p align="center" style="color: red"><?php echo $err ?></p>
	<h5 align="center">Offer a New Course</h5>
	<form action="addCourse.php" method="post">
		<table align="center">
			<tr>
				<td>Name</td>
				<td><input type="Text" name="NAME" id="NAME"></td>
			</tr>
		</table>
		<br>
		<div style="text-align: center;">
			<input type="Submit" name="Submit" value="Offer Course">
		</div>
	</form>
	<p align="center">Course Offer History</p>
	<div>
	<?php 

		echo '<table align="center"><tr><th>ID</th><th>Name</th></tr>';
		//Getting the Course Information and Showing here
		$sql_select = "SELECT * FROM COURSE ORDER BY COURSE_ID";
		$query_id = oci_parse($con, $sql_select);
		$runInsert = oci_execute($query_id);
		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			echo '<tr><td><input type="Number" name="COURSE_ID" value='.$row["COURSE_ID"].' readonly></td>';
			echo '<td><input type="Text" name="COURSE_ID" value='.$row["NAME"].' readonly></td>';
		} 
	?>
	</div>

</body>
</html>