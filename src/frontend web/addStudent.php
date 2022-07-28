<!DOCTYPE html>
<html>
<head>
	<title>Add Student to Class - HAMARAY BACHAY</title>
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
		//Running the Query to get latest Course
		$courseID = 0;
		$courseName = "";
		$challanNo = 0;
		$err = "";
		$isValid = true;
		$stu_id = 0;
		$classID = 0;
		$classNo = 0;
		$section = "";
		$className = "";

		$sql_select = "SELECT * FROM COURSE WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)";
		$query_id = oci_parse($con, $sql_select);
		$runInsert = oci_execute($query_id);

		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			$courseName = $row["NAME"];
			$courseID = $row["COURSE_ID"];
		}

		//Running the Query for Adding Student in Class
		if(isset($_POST["Submit1"]))
		{
			//Find the Student ID
			$sql_select = "SELECT ID FROM STUDENT WHERE ROLL_NO = ".$_POST["ROLL_NO"];  
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
			}

			//Find the Challan Number First
			$sql_select = "SELECT CHALLAN_NO FROM CHALLAN WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)".
			              " AND STUDENT_ID = ".$stu_id;  
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$challanNo = $row["CHALLAN_NO"];
			}
			if($challanNo == 0)
			{
				$isValid = false;
				$err = "Challan For the Student is not Generated!";
			}
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
			}
		}

	?>
	<h1 align="center">ADD STUDENT TO CLASS</h1>
	<h3 align="center">Course : <?php echo $courseName ?></h3>
	<p align="center">Students that are not Enrolled in this Course</p>
	<p align="center" style="color: red;"><?php echo $err ?></p>
	<?php
		$sql_select = "SELECT * ".
					  " FROM STUDENT".
					  " WHERE ID NOT IN(SELECT STUDENT_ID FROM REGISTERED WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE))".
					  " ORDER BY ID";
		$query_id = oci_parse($con, $sql_select);
		$runInsert = oci_execute($query_id);

		echo '<table align="center">';
		echo '<tr><th style="width: 130px">Roll No</th><th style="width: 130px">Name</th>'.
					 '<th style="width: 130px">Age</th><th style="width: 130px">Gender</th></tr>';

		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			echo '<form method="post">';
			echo '<tr style="text-align: center"><td style="height: 25px"">'.
					 	'<input type="Number" name="ROLL_NO" id="ROLL_NO" value="'.$row["ROLL_NO"].'" readonly>'.
					 	'</td>'.
					'<td style="height: 25px"">'.
						'<input type="Text" name="NAME" id="NAME" value="'.$row["NAME"].'" readonly></td>'.
				    '<td style="height: 25px"">'.
				    	'<input type="Number" name="AGE" id="AGE" value='.$row["AGE"].' readonly></td>'.
					'<td style="height: 25px"">'.
						'<input type="Text" name="GENDER" id="GENDER" value="'.$row["GENDER"].'" readonly></td>'.	
					'<td><input type="submit" name="Submit1" value="ADD TO CLASS" formaction="addStudent.php"></td>'.
				  '</tr>';
			echo '</form>';		 
		}
		echo "</table>";
	?>
</body>
</html>