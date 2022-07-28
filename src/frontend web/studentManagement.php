<!DOCTYPE html>
<html>
<head>
	<title>Student Management - HAMARAY BACHAY</title>
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

		$sucess = "";
		//------------------------MAIN DELETION CODE
		if(isset($_POST["Submit2"]))
		{
			$stu_id = 0;
			
			//---------------------Finding the Correct Student ID
			$sql_select = 'SELECT ID FROM STUDENT WHERE ROLL_NO = '.$_POST["ROLL_NO"];
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
			}

			//---------------------Actual Deletion
			$sql_delete = 'DELETE FROM REGISTERED'. 
						  ' WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)'.
						  ' AND STUDENT_ID = '.$stu_id;

		  	$query_id = oci_parse($con, $sql_delete);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				$sucess = "Registration Deleted!";
			}
		}
		//------------------------MAIN UPDATE CODE
		if(isset($_POST["Submit1"]))
		{
			$stu_id = 0;
			
			//---------------------Finding the Correct Student ID
			$sql_select = 'SELECT ID FROM STUDENT WHERE ROLL_NO = '.$_POST["ROLL_NO"];
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
			}

			//---------------------Actual Deletion
			$sql_update = 'UPDATE STUDENT SET ROLL_NO = '.$_POST["ROLL_NO"].", NAME = '".$_POST["NAME"]."'".
						  ', AGE = '.$_POST["AGE"].
						  ", GENDER = '".$_POST["GENDER"]."'".
						  ' WHERE ID = '.$stu_id;			  

		  	$query_id = oci_parse($con, $sql_update);
			$runInsert = oci_execute($query_id);
			if($runInsert)
			{
				$sucess = "Registration Updated!";
			}
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

		$sql_select = "SELECT * FROM COURSE WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)";
		$query_id = oci_parse($con, $sql_select);
		$runInsert = oci_execute($query_id);

		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			$courseName = $row["NAME"];
			$courseID = $row["COURSE_ID"];
		}
	?>
	<h1 align="center">STUDENT MANAGEMENT</h1>
	<h3 align="center">Course : <?php echo $courseName ?></h3>
	<form action="addStudent.php" method="post"> 
		<div style="text-align: center;">
			<input type="Submit" name="Submit4" id="Submit4" value="ADD STUDENT" formaction="addStudent.php">
		</div>
	</form>
	<br>
	<form action="studentManagement.php" method="post">
		<table align="center">
			<tr>
				<th>Roll No</th>
				<td>
					<input type="Number" name="ROLL_NO" id="ROLL_NO">
				</td>
			</tr>
			<tr>
				<th>Name</th>
				<td>
					<input type="Text" name="NAME" id="NAME" value="">
				</td>
			</tr>
		</table>
		<div style="text-align: center;">
			<input type="Submit" name="Submit5" id="Submit5" value="Search">
		</div>
	</form>
	<h5 align="center" style="color: red"><?php echo $sucess ?></h5>
	<?php
		//----------------------------------First Query to get All the Class IDs
		$classIDS = array();
		$index = 0;
		$sql_select = "SELECT UNIQUE C.CLASS_ID, C.CLASS_NO, C.SECTION, C.NAME, C.GROUPING". 
					  " FROM CLASS C, REGISTERED R ".
					  " WHERE C.CLASS_ID = R.CLASS_ID".
					  " AND R.COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)";
		$stu_id = 0;

		//==============================================================================================================
		//--------------------------------------------------------------------------------------------------------------

		if(isset($_POST["Submit5"]) && !empty($_POST["ROLL_NO"]))
		{
			$sql_insert = "SELECT ID FROM STUDENT WHERE ROLL_NO = ".$_POST["ROLL_NO"];
			$query_id = oci_parse($con, $sql_insert);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
			}
			if($stu_id != 0)
			{
				$sql_select .= " AND R.STUDENT_ID = ".$stu_id;
			}
		}
		if(isset($_POST["Submit5"]) && !empty($_POST["NAME"]))
		{
			$sql_insert = "SELECT ID FROM STUDENT WHERE NAME LIKE '".$_POST["NAME"]."'";
			$query_id = oci_parse($con, $sql_insert);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$stu_id = $row["ID"];
			}
			if($stu_id != 0)
			{
				$sql_select .= " AND R.STUDENT_ID = ".$stu_id;
			}
		}
		$sql_select .= " ORDER BY C.CLASS_NO, C.SECTION";

		//==============================================================================================================
		//--------------------------------------------------------------------------------------------------------------

		$query_id = oci_parse($con, $sql_select);
		$runInsert = oci_execute($query_id);
		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
		{
			$classIDS[$index] = $row["CLASS_ID"];
			++$index;
		}
		//---------------------------------Main Code to Loop
		for($x = 0; $x < count($classIDS); ++$x)
		{
			$number = 0;
			$section = "";
			$name = "";
			$count = "";
			$grouping = "";
			$groupNum = 1;

			//----------------------------This Query for Class Information
			$sql_select = "SELECT * FROM CLASS WHERE CLASS_ID = ".$classIDS[$x];
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$number = $row["CLASS_NO"];
				$section = $row["SECTION"];
				$name = $row["NAME"];
				$grouping = $row["GROUPING"];
			}

			//----------------------------Getting Class Count
			$sql_select = "SELECT COUNT(*) AS COUNT FROM REGISTERED WHERE CLASS_ID = ".$classIDS[$x].
						  " AND COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)";
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{
				$count = $row["COUNT"];
			}  	

			echo '<table align="center"><tr><th>Class '.$number.$section.' ['.$name.']</th>';
			echo '<th>('.$count.') total</th></tr>';

			$exitGroupingLoop = false;

			while(!$exitGroupingLoop)
			{
				if($grouping == 'YES')
				{
					echo '<tr><th>Group '.$groupNum.'</th></tr>';
				}

				echo '<tr><th style="width: 130px">Roll No</th><th style="width: 130px">Name</th>'.
					 '<th style="width: 130px">Age</th><th style="width: 130px">Gender</th></tr>';	

				//==========================================================================================================	 
				//-----------------------------This Query for Rows of Data

				$sql_select = "SELECT S.ROLL_NO, S.NAME, S.AGE, S.GENDER FROM STUDENT S, REGISTERED R WHERE R.STUDENT_ID = S.ID AND".
							  "  R.COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE) AND CLASS_ID = ".$classIDS[$x];

				if(isset($_POST["Submit5"]) && $stu_id != 0)
				{
					$sql_select .= " AND S.ID = ".$stu_id;
				}	
				else
				{	  
					if($grouping == 'YES' && $groupNum == 1)
					{
						$sql_select .= "  AND S.GENDER = 'M'";
					}
					else if($grouping == 'YES' && $groupNum == 2)
					{
						$sql_select .= "  AND S.GENDER = 'F'";
					}
				}

				//==========================================================================================================

				$query_id = oci_parse($con, $sql_select);
				$runInsert = oci_execute($query_id);

				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
				{
					echo '<form method="post">';
					echo '<tr style="text-align: center"><td style="height: 25px"">'.
							 	'<input type="Number" name="ROLL_NO" id="ROLL_NO" value="'.$row["ROLL_NO"].'" readonly>'.
							 	'</td>'.
							'<td style="height: 25px"">'.
								'<input type="Text" name="NAME" id="NAME" value="'.$row["NAME"].'"></td>'.
						    '<td style="height: 25px"">'.
						    	'<input type="Number" name="AGE" id="AGE" value='.$row["AGE"].'></td>'.
							'<td style="height: 25px"">'.
								'<input type="Text" name="GENDER" id="GENDER" value="'.$row["GENDER"].'"></td>'.
							'<td><input type="submit" name="Submit1" value="Edit" formaction="studentManagement.php"></td>'.
							'<td><input type="submit" name="Submit2" value="Delete" formaction="studentManagement.php"></td>'.
							'<td><input type="submit" name="Submit3" value="Change Class" formaction="changeClass.php"></td>'.
						  '</tr>';
					echo '</form>';		 
				}
				if($grouping == 'YES' && $groupNum < 2)
				{
					++$groupNum;
					$exitGroupingLoop = false;
				}
				else
				{
					$exitGroupingLoop = true;
				}
			}//End While = Grouping Loop
			echo "<br><br>";
		}//End For loop = Classes
		echo "</table><br><br><br>"
	?>
</body>
</html>