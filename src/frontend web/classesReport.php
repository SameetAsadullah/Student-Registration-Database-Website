<!DOCTYPE html>
<html>
<head>
	<title>Classes Report - HAMARAY BACHAY</title>
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
			//echo "connected";
		}
		else
		{
			die("Could not Connect to Oracle!!!");
		}

		$sucess = "";
		?>
			</br></br></br></br></br></br>
			<h1 align="center">CLASSES  REPORT</h1>
			</br></br></br>
		<?php
		
		$sql_total =  " SELECT COUNT(*) AS TOTAL, C.CLASS_NO AS CLASS, C.SECTION AS SECTION, C.CLASS_ID AS ID".
					  " FROM STUDENT S, REGISTERED R, CLASS C ".
					  " WHERE S.ID = R.STUDENT_ID AND R.CLASS_ID = C.CLASS_ID AND R.COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE) ".
					  " GROUP BY C.CLASS_NO,C.SECTION,C.CLASS_ID ".
					  " ORDER BY C.CLASS_NO";

					  
		
		$query_id1 = oci_parse($con, $sql_total);
		$run1 = oci_execute($query_id1);
		
		$stu_total = array();
		$stu_class = array();
		$tot_index = 0;
		while($row = oci_fetch_array($query_id1, OCI_BOTH + OCI_RETURN_NULLS))
		{
			 $stu_total[$tot_index] = $row['TOTAL'];
			 $stu_class[$tot_index] = $row['ID'];
			 ++$tot_index;
		}
		
		$sql_female = " SELECT COUNT(*) AS TOTAL, C.CLASS_ID AS CLASS ".
					  " FROM STUDENT S, REGISTERED R, CLASS C ".
					  " WHERE S.GENDER = 'F' AND S.ID = R.STUDENT_ID AND R.CLASS_ID = C.CLASS_ID AND R.COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE) ".
					  " GROUP BY C.CLASS_ID ".
					  " ORDER BY C.CLASS_ID";
		$query_id2 = oci_parse($con, $sql_female);
		$run2 = oci_execute($query_id2);
		
		$stu_female = array();
		$fe_class = array();
		$fem_index = 0;
		while($row = oci_fetch_array($query_id2, OCI_BOTH + OCI_RETURN_NULLS))
		{
			 $stu_female[$fem_index] = $row['TOTAL'];
			 $fe_class[$fem_index] = $row['CLASS'];
			 ++$fem_index;
		}
		$a = 0;
		$fe_total = array();
		for($x = 0; $x < $tot_index; ++$x){
			if($a < $fem_index && $stu_class[$x] == $fe_class[$a]){
				$fe_total[$x] = $stu_female[$a];
				++$a;
			}	
			else {
				$fe_total[$x] = 0;
			}
			
		}
		
		$sql_male =   " SELECT COUNT(*) AS TOTAL, C.CLASS_ID AS CLASS ".
					  " FROM STUDENT S, REGISTERED R, CLASS C ".
					  " WHERE S.GENDER = 'M' AND S.ID = R.STUDENT_ID AND R.CLASS_ID = C.CLASS_ID AND R.COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE) ".
					  " GROUP BY C.CLASS_ID ".
					  " ORDER BY C.CLASS_ID";
		$query_id3 = oci_parse($con, $sql_male);
		$run3 = oci_execute($query_id3);
		
		$stu_male = array();
		$me_class = array();
	
		$me_index = 0;
		while($row = oci_fetch_array($query_id3, OCI_BOTH + OCI_RETURN_NULLS))
		{
			 $stu_male[$me_index] = $row['TOTAL'];
			 $me_class[$me_index] = $row['CLASS'];
			 ++$me_index;
		}
	
		$b = 0;
		$me_total = array();
		for($x = 0; $x < $tot_index; ++$x){
			if($b < $me_index && $stu_class[$x] == $me_class[$b]){
				$me_total[$x] = $stu_male[$b];
				++$b;
			}	
			else {
				$me_total[$x] = 0;
			}
			
		}
		
		$sql_select = "SELECT UNIQUE C.CLASS_ID, C.CLASS_NO, C.SECTION, C.NAME, C.GROUPING". 
					  " FROM CLASS C, REGISTERED R ".
					  " WHERE C.CLASS_ID = R.CLASS_ID".
					  " AND R.COURSE_ID = (SELECT MAX(COURSE_ID) FROM COURSE)".
					  " ORDER BY C.CLASS_NO";
		
		
		$query_id = oci_parse($con, $sql_select);
		$run = oci_execute($query_id);
		
		
			echo '<table align="center">';
		echo '<tr><th style="width: 130px">Class No</th>'.
					 '<th style="width: 130px">Class Name</th><th style="width: 130px">Section</th><th style="width: 130px">Total Students</th>'.
					 '<th style="width: 150px">Total Female Students</th><th style="width: 150px">Total Male Students</th></tr>';	
		$i = 0;
		$j = 0;
		while($row = oci_fetch_array($query_id, OCI_BOTH + OCI_RETURN_NULLS))
			{	
					
					
			echo '<form method="post">';
			echo '<tr style="text-align: center">'.
			 	
				'<td style="height: 25px"">'.
				'<input type="Text" name="CLASS NO" id="CLASS_NO" value="'.$row['CLASS_NO'].'" readonly></td>'.
				    '<td style="height: 25px"">'.
			   	'<input type="Text" name="NAME" id="NAME" value='.$row["NAME"].'></td>'.
					'<td style="height: 25px"">'.
				'<input type="Text" name="SECTION" id="SECTION" value="'.$row["SECTION"].'" readonly></td>'.
					'<td style="height: 25px"">'.
				'<input type="Number" name="TOTAL" id="TOTAL" value="'.$stu_total[$i].'" readonly></td>'.
					'<td style="height: 25px"">'.
				'<input type="Number" name="fe" id="FEMALE" value="'.$fe_total[$i].'" readonly></td>'.
					'<td style="height: 25px"">'.
				'<input type="Number" name="Me" id="MALE" value="'.$me_total[$i].'" readonly></td>'.
				
				  '</tr>';
			echo '</form>';
			$i++;
		//	$j++;
			}
		
		echo "</table><br><br><br>"
	
		?>
</body>
</html>