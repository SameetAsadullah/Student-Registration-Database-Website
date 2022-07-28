<!DOCTYPE html>
<html>
<head>
	<title>Student Change Class - HAMARAY BACHAY</title>
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
			    <a href="insertion.php">Admission Form</a>
			    <a href="feeChallan.php">Course Registration</a>
			    <a href="accompany.php">Accompany</a>
			    <a class="active" href="forStaff.php">For Staff</a>
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
		$stu_id = 0;
		$roll_no = 0;
		$name = "";
		$err = "";
		$classID = 0;
		$classno = 0;
		$section = "";
		$isValid = true;

		//==========================================================================================
		//--------------------[ Run the Main Query to Insert into Class Log ]-----------------------
		//==========================================================================================
		if(isset($_POST["Submit1"]))
		{
			//-----------------------------Find the Student ID
			//-----------------------------------------------------------------------
			$roll_no = $_POST["ROLL_NO"];
			$sql_select = "SELECT * FROM STUDENT WHERE ROLL_NO = ".$roll_no;
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
				$name = $row["NAME"];
			}
			if($stu_id == 0)
			{
				$isValid = false;
				$err = "Student Not Found!";
			}

			//-----------------------------Find the Class ID
			//-----------------------------------------------------------------------
			$sql_select = "SELECT CLASS_ID FROM CLASS WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)".
						  " AND CLASS_NO = ".$_POST["CLASS_NO"]." AND SECTION = '".$_POST["SECTION"]."'";
		    $query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$classID = $row["CLASS_ID"];
			}
			if($classID == 0)
			{
				$isValid = false;
				$err = "Class Not Found!";
			}
			if($isValid)
			{
				$sql_insert = "INSERT INTO CLASS_LOG(STUDENT_ID, CLASS_NO, SECTION, REASON, APPROVED) ".
							  "VALUES(".$stu_id.",".$_POST["CLASS_NO"].", '".$_POST["SECTION"]."', '".$_POST["REASON"].
							  "', '".$_POST["APPROVED"]."')";

				$query_id = oci_parse($con, $sql_insert);
				$runInsert = oci_execute($query_id); 
				if($runInsert)
				{
					$err = "Class Change Successful!";
				} 
				else
				{
					$err = "Class Change encountered a Problem!";
				}
			}
		}

		//==========================================================================================		
		//---------------[ Run the Select Query if Came from Student Management GUI ]---------------
		//==========================================================================================
		if(isset($_POST["Submit3"]) || isset($_POST["Submit2"]))
		{
			//Find the Student ID
			$roll_no = $_POST["ROLL_NO"];
			$sql_select = "SELECT * FROM STUDENT WHERE ROLL_NO = ".$roll_no;
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
				$name = $row["NAME"];
			}
			if($stu_id == 0)
			{
				$isValid = false;
				$err = "Student Not Found!";
			}
			if($isValid == true)
			{
				//Find the Class No and Section
				$sql_select = "SELECT CLASS_ID FROM REGISTERED WHERE STUDENT_ID = ".$stu_id." AND COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)";
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$classID = $row["CLASS_ID"];
				}
				$sql_select = "SELECT CLASS_NO, SECTION FROM CLASS WHERE CLASS_ID = ".$classID;
				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					$classno = $row["CLASS_NO"];
					$section = $row["SECTION"];
				}
			}
		}
	?>
	<h1 align="center">Student Change Class Form</h1>
	<p align="center" style="color: red;"><?php echo $err; ?></p>
	<form action="changeClass.php" method="post">
		<table align="center">
			<tr>
				<td>Roll No</td>
				<td colspan="2">
					<input style="width: 98%;" type="Number" name="ROLL_NO" id="ROLL_NO" value="<?php echo $roll_no; ?>">
				</td>
			</tr>
			<tr>
				<td>Name</td>
				<td colspan="2">
					<input style="width: 98%;" type="Text" name="NAME" id="NAME" value="<?php echo $name; ?>" readonly>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="Submit" name="Submit2" id="Submit2" formaction="changeClass.php" value="Search">
				</td>
			</tr>
			<tr>
				<td><br></td>
			</tr>
			<tr>
				<td></td>
				<th>Class</th>
				<th>Section</th>
			</tr>
			<tr>
				<td>Current</td>
				<td>
					<input type="Number" name="CLASS_NO_PREV" id="CLASS_NO_PREV" value="<?php echo $classno; ?>" readonly>
				</td>
				<td>
					<input type="Text" name="SECTION_PREV" id="SECTION_PREV" value="<?php echo $section; ?>" readonly>
				</td>
			</tr>
			<tr>
				<td>New</td>
				<td>
					<input type="Number" name="CLASS_NO" id="CLASS_NO" value="<?php echo $classno; ?>">
				</td>
				<td>
					<input type="Text" name="SECTION" id="SECTION" value="<?php echo $section; ?>">
				</td>
			</tr>
			<tr>
				<td><br><br></td>
			</tr>
			<tr>
				<td>Reason For Change</td>
				<td colspan="2">
					<textarea name="REASON" id="REASON" rows="3" style="resize: none; width: 98%">
					</textarea>
				</td>
			</tr>
			<tr>
				<td><br></td>
			</tr>
			<tr>
				<td>Approved by</td>
				<td colspan="2">
					<input type="Text" name="APPROVED" id="APPROVED" style="width: 98%;">
				</td>
			</tr>
		</table>
		<br>
		<div style="text-align: center">
			<input type="Submit" name="Submit1" id="Submit1" value="Save Changes">
		</div>
	</form>
</body>
</html>