<!DOCTYPE html>
<html>
<head>
	<title>Search - HAMARAY BACHAY</title>
	<style>
	.error {color: #FF0000;}
	</style>
	<style>
		table, tr
		{
			border: 1px solid black;
		}
		tr:hover {background-color:#ECF0F1;}
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
	<style>
		table#t01 {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}
		table#t01 td,th {
			border: 1px solid #dddddd;
			text-align: center;
			padding: 8px;
		}
		table#t01 tr:nth-child(1) {
			background-color: #D0D3D4;
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
	
	<h1 align="center">SEARCHING STUDENTS</h1>
	<h3 align="center">Who have been dormant for given number of months/years</h3>
	
	<script>
		function disableYearInput() {
			document.getElementById("YEARS").disabled = true;
		}
		function disableMonthInput() {
			document.getElementById("MONTHS").disabled = true;
		}
	</script>
	
	<form action="myreport13.php" method="post">			
		<table align="center">
			<tr>
				<th>Months</th>
				<td>
					<input onclick="disableYearInput()" type="number" name="MONTHS" id="MONTHS" required="required">
				</td>
			</tr>
			<tr>
				<th>Years</th>
				<td>
					<input onclick="disableMonthInput()" type="Number" name="YEARS" id="YEARS" required="required">
				</td>
			</tr>
		</table>
		<div style="text-align: center;">
			<input type="Submit" name="SUBMIT" id="SUBMIT" value="Search">
		</div>
	</form>

	<?php
		if (isset($_POST["SUBMIT"])) {

			//-------------Defining and initializing variables
			$i = 0;
			$studentId;
			$divisor;
			$compareWith;

			//--------------If user has entered years
			if (isset($_POST["YEARS"])) {
				$divisor = 365;
				$compareWith = $_POST["YEARS"];
			}
	
			//-------------else if user has entered months
			else if (isset($_POST["MONTHS"])) {
				$divisor = 30.417;
				$compareWith = $_POST["MONTHS"];
			}

			//-------------Query for searching required students
			$sql_select = "SELECT DISTINCT STUDENT_ID FROM REGISTERED WHERE STUDENT_ID ".
			"NOT IN (SELECT STUDENT_ID FROM REGISTERED WHERE COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)) ".
			"AND (SYSDATE-REG_DATE)/".$divisor." <= ".$compareWith;
			$query_id = oci_parse($con, $sql_select);
			$runInsert = oci_execute($query_id);
			if($runInsert) {
				while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS)) {
					$studentId[$i] = $row["STUDENT_ID"];
					$i++;
				}
			}			
			else {
				echo "ERROR: IN SEARCHING FOR STUDENTS!!!<br>";
			}

			if ($i !== 0) {
				$fatherName;
				$motherName;
				$guardianName;

				echo "<br>
					<table id="."t01".">
						<tr>
							<th>Roll No</th>
							<th>Name</th>
							<th>Gender</th>
							<th>DOB</th>
							<th>Age</th>
							<th>Father Name</th>
							<th>Mother Name</th>
							<th>Guardian Name</th>
						</tr>";

				for ($j = 0; $j < $i; ++$j) {
					//-------------Searching Students information and displaying
					$sql_select = "SELECT * FROM STUDENT WHERE ID = ".$studentId[$j];
					$query_id = oci_parse($con, $sql_select);
					$runInsert = oci_execute($query_id);
					if($runInsert) {
						while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS)) {
							//-------------Searching Parents and Guardian name of the student
							$sql_select = "SELECT * FROM GUARDIAN WHERE ID = ".$row["FATHER_ID"].
							"OR ID = ".$row["MOTHER_ID"]. "OR ID = ".$row["GUARDIAN_ID"];
							$query_id = oci_parse($con, $sql_select);
							$runInsert = oci_execute($query_id);
							if($runInsert) {
								while($row1 = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS)) {
									//-------------Storing father name
									if ($row1["ID"] === $row["FATHER_ID"]){
										$fatherName = $row1["NAME"];
									}

									//-------------Storing mother name
									else if ($row1["ID"] === $row["MOTHER_ID"]){
										$motherName = $row1["NAME"];
									}

									//-------------Storing guardian name
									else if ($row1["ID"] === $row["GUARDIAN_ID"]){
										$guardianName = $row1["NAME"];
									}
								}
							}			
							else {
								echo "ERROR: IN SEARCHING FOR STUDENT PARENTS AND GUARDIAN!!!<br>";
							}

							echo "
								<tr>
									<td>".$row["ROLL_NO"]."</td>
									<td>".$row["NAME"]."</td>
									<td>".$row["GENDER"]."</td>
									<td>".$row["DOB"]."</td>
									<td>".$row["AGE"]."</td>
									<td>".$fatherName."</td>
									<td>".$motherName."</td>
									<td>".$guardianName."</td>
								</tr>		
							";
							
						}
					}			
					else {
						echo "ERROR: IN SEARCHING FOR STUDENTS!!!<br>";
					}
				}
			}
			else {
				echo "<br><center><b><u>NO DATA FOUND!!!</u></b></center>";
			}
		}
	?>
</body>
</html>
<!DOCTYPE html>