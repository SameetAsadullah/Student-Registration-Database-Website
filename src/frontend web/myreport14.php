<html>
<head>
	<title>Report- HAMARAY BACHAY</title>
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
		  </div>
	</div>
	<?php  // creating a database connection 
	// example 2.1 ..creating a database connection
	$db_sid =    "(DESCRIPTION =
					    (ADDRESS = (PROTOCOL = TCP)(HOST = DESKTOP-AR5Q8KO)(PORT = 1521))
					    (CONNECT_DATA =
					      (SERVER = DEDICATED)
					      (SERVICE_NAME = nabeel)
					    )
					  	)"; 
  
   $db_user = "scott";   // Oracle username e.g "scott"
   $db_pass = "1234";    // Password for user e.g "1234"
   $con = oci_connect($db_user,$db_pass,$db_sid); 

	if($con) 
	{ 
	} 
	else 
    {
		die('Could not connect to Oracle: '); 
	} 
  
?>
	<div class="header">
		  <a href="#default" class="logo">HAMRAY BACHAY</a>
		  <div class="header-right">
			    <a href="insertion.php">Admission Form</a>
			    <a href="feeChallan.php">Course Registration</a>
			    <a href="accompany.php">Accompany</a>
			    <a class="active" href="forStaff.php">For Staff</a>
		  </div>
	</div>
	<body align="middle">
	<hr>
	<h2 align="center">Student DataBase</h2>
	<b>Search Student Data </b>
	 <br><br>
	<form action="" method="post">
		<input type="text" name="eNum"/>
		<select name="type1">
		    <option value="ROLL_NO">Roll Number</option>
	   		<option value="NAME">Name</option>
  	</select>
  	<br><br><br>
  	<input type="submit" name="submit" value="Search"/>
	</form>

<?php
	
	//------------Variables Needed----------------------------//
	//--------------------------------------------------------//
	$select1="";
	$select2="";
	if(isset($_POST['type1'])){
	    $select1 = $_POST['type1'];
	//    echo  "Student's ".$select1;
	}
	if(isset($_POST['eNum'])){
	    $select2 = $_POST['eNum'];
	//    echo  " is...".$select2;
	}


	//If nothing is slected yet
	if($select2==""){
		die("");
	}
	if($select1=="NAME"){
		$select2=ucfirst($select2);//converts First letter capital
	}
	//If in roll number section a Name is searched
	if($select1=="ROLL_NO" && !is_numeric($select2)){
		die("<b>Please Enter a valid in Roll_No.</b>");
	}
	

	$sql_select_up="select ID,ROLL_NO,NAME,to_char(DOB,'dd/mm/yyyy') DOB,to_char(DATE_ADMITTED,'dd/mm/yyyy') DATE_ADMITTED,GENDER,AGE,FATHER_ID,MOTHER_ID,GUARDIAN_ID from Student where ( $select1 = '$select2')";
	$query_id_up = oci_parse($con, $sql_select_up);
	$runselect_up = oci_execute($query_id_up);
	if(!$query_id_up){
		die("");
	}
	if($runselect_up){}
	else
	{
		echo "No Student with given Info Found..";
	}

	$i=0;
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$ID[$i]= $row["ID"];
		$ROLL_No[$i]= $row["ROLL_NO"];
		$Name[$i]= $row["NAME"];
		$DOB[$i]=$row["DOB"];
		$DATE_A[$i]=$row["DATE_ADMITTED"];
		$Gender[$i]=$row["GENDER"];
		$AGE[$i]=$row["AGE"];
		$FATHER[$i]=$row["FATHER_ID"];
		$MOTHER[$i]=$row["MOTHER_ID"];
		$GUARDIAN[$i]=$row["GUARDIAN_ID"];
		$i++;
	}
	if($i==0){
		die("<b>No Match Found</b>");
	}


//Gettings Siblings Info
$total_siblings=0;	
for($j=0;$j<$i;$j++){
		$sql_select_up="select ID,ROLL_NO,NAME,to_char(DOB,'dd/mm/yyyy') DOB,to_char(DATE_ADMITTED,'dd/mm/yyyy') DATE_ADMITTED,GENDER,AGE,GUARDIAN_ID from student where(Father_ID ='$FATHER[$j]'
		 or Mother_Id = '$MOTHER[$j]' ) ";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with Guardian Query");
		}
		if($runselect_up){}
		else
		{
			echo "No SIBLINGS with given Info Found..";
		}		
	
	$f=0;
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$S_ID[$total_siblings]= $row["ID"];
		$S_ROLL_No[$total_siblings]= $row["ROLL_NO"];
		$S_Name[$total_siblings]= $row["NAME"];
		$S_DOB[$total_siblings]=$row["DOB"];
		$S_DATE_A[$total_siblings]=$row["DATE_ADMITTED"];
		$S_Gender[$total_siblings]=$row["GENDER"];
		$S_AGE[$total_siblings]=$row["AGE"];
		$S_GUARDIAN[$total_siblings]=$row["GUARDIAN_ID"];
		$f++;
		$total_siblings++;
	}
	$sibling_count[$j]=$f;
}


//Getting Guardian Info
//For Student
for($j=0;$j<$i;$j++){
		$sql_select_up="select ID,CNIC,NAME,GENDER,CONTACT,EMAIL,ADDRESS,RELATION from guardian where (ID='$GUARDIAN[$j]')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with Guardian Query");
		}
		if($runselect_up){}
		else
		{
			echo "No Guardian with given Info Found..";
		}		
	

	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$G_ID[$j]= $row["ID"];
		$G_CNIC[$j]= $row["CNIC"];
		$G_Name[$j]= $row["NAME"];
		$G_CONTACT[$j]=$row["CONTACT"];
		$G_EMAIL[$j]=$row["EMAIL"];
		$G_Gender[$j]=$row["GENDER"];
		$G_ADDRESS[$j]=$row["ADDRESS"];
		$G_RELATION[$j]=$row["RELATION"];
	}
}
//For Siblings
for($j=0;$j<$total_siblings;$j++){
		$sql_select_up="select ID,CNIC,NAME,GENDER,CONTACT,EMAIL,ADDRESS,RELATION from guardian where (ID='$S_GUARDIAN[$j]')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with Guardian Query");
		}
		if($runselect_up){}
		else
		{
			echo "No Guardian with given Info Found..";
		}		
	

	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$SG_ID[$j]= $row["ID"];
		$SG_CNIC[$j]= $row["CNIC"];
		$SG_Name[$j]= $row["NAME"];
		$SG_CONTACT[$j]=$row["CONTACT"];
		$SG_EMAIL[$j]=$row["EMAIL"];
		$SG_Gender[$j]=$row["GENDER"];
		$SG_ADDRESS[$j]=$row["ADDRESS"];
		$SG_RELATION[$j]=$row["RELATION"];
	}
}
//Getting Father's Info
for($j=0;$j<$i;$j++){
		$sql_select_up="select ID,CNIC,NAME,GENDER,CONTACT,EMAIL,ADDRESS from guardian where (ID='$FATHER[$j]')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with FATHER Query");
		}
		if($runselect_up){}
		else
		{
			echo "No FATHER with given Info Found..";
		}		
	
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$F_ID[$j]= $row["ID"];
		$F_CNIC[$j]= $row["CNIC"];
		$F_Name[$j]= $row["NAME"];
		$F_CONTACT[$j]=$row["CONTACT"];
		$F_EMAIL[$j]=$row["EMAIL"];
		$F_Gender[$j]=$row["GENDER"];
		$F_ADDRESS[$j]=$row["ADDRESS"];
	}
}
	//Getting Mother's Info
for($j=0;$j<$i;$j++){
		$sql_select_up="select ID,CNIC,NAME,GENDER,CONTACT,EMAIL,ADDRESS from guardian where (ID='$MOTHER[$j]')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with MOTHER Query");
		}
		if($runselect_up){}
		else
		{
			echo "No MOTHER with given Info Found..";
		}		

	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{

		$M_ID[$j]= $row["ID"];
		$M_CNIC[$j]= $row["CNIC"];
		$M_Name[$j]= $row["NAME"];
		$M_CONTACT[$j]=$row["CONTACT"];
		$M_EMAIL[$j]=$row["EMAIL"];
		$M_Gender[$j]=$row["GENDER"];
		$M_ADDRESS[$j]=$row["ADDRESS"];
	}
}
//Fectching Data about Classes and COurses
	$sql_select_up="select MAX(Course_ID) Course_ID from Course";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with MAX Course Query");
		}
		if($runselect_up){}
		else
		{
			echo "No Course MAX Found..";
		}		
	
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$max_Course=$row["COURSE_ID"];
	}
//Fetching Current Class Info	
for($j=0;$j<$i;$j++){
		$sql_select_up="select CLASS_ID from registered where( student_id='$ID[$j]' and course_id='$max_Course')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with Current ID Query");
		}
		if($runselect_up){}
		else
		{
			echo "No Current Class ID Found..";
		}		
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$Current_Class[$j]=$row["CLASS_ID"];
	}
}
//siblings
for($j=0;$j<$total_siblings;$j++){
		$sql_select_up="select CLASS_ID from registered where( student_id='$S_ID[$j]' and course_id='$max_Course')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with Current ID Query");
		}
		if($runselect_up){}
		else
		{
			echo "No Current Class ID Found..";
		}		
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$S_Current_Class[$j]=$row["CLASS_ID"];
	}
}

//Fetching CUrrent CLass WHole Info
//Students	
for($j=0;$j<$i;$j++){
		$sql_select_up="select Class_ID,COURSE_ID,CLASS_NO,SECTION,NAME from class where (class_id='$Current_Class[$j]')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with C.class Info Query");
		}
		if($runselect_up){}
		else
		{
			echo "No  C.class Info Info Found..";
		}		
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{

		$CClass_ID[$j]= $row["CLASS_ID"];
		$CCourse_ID[$j]= $row["COURSE_ID"];
		$CClass_No[$j]= $row["CLASS_NO"];
		$C_Section[$j]=$row["SECTION"];
		$C_Name[$j]=$row["NAME"];
	}
}

//Fetching Old CLasses InFO
$s=0;
for($j=0;$j<$i;$j++){
		$sql_select_up="select class_id from registered where( student_id='$ID[$j]' and course_id!='$max_Course')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with old.class Info Query");
		}
		if($runselect_up){}
		else
		{
			echo "No  old.class Info Info Found..";
		}		


	$f=0;

	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{

		$Old_Class_ID[$s]= $row["CLASS_ID"];
		$f++;
		$s++;
	}
	$oldcount[$j]=$f;
}
//Fetching Old CLasses Full Info
	
for($j=0;$j<$s;$j++){
		$sql_select_up="select Class_ID,COURSE_ID,CLASS_NO,SECTION,NAME from class where (class_id='$Old_Class_ID[$j]')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with C.class Info Query");
		}
		if($runselect_up){}
		else
		{
			echo "No  C.class Info Info Found..";
		}		
	
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{

		$OClass_ID[$j]= $row["CLASS_ID"];
		$OCourse_ID[$j]= $row["COURSE_ID"];
		$OClass_No[$j]= $row["CLASS_NO"];
		$O_Section[$j]=$row["SECTION"];
		$O_Name[$j]=$row["NAME"];
	}
}
?>




<head>
<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
</head>

	


		
		<?php

			$check=1;
			$counter=0;
			$counter2=0;
			for($j=0;$j<$i;$j++){

					echo "
					<table align="."left"." id="."customers".">
					<tr>
    					<th ><b> "."Student # ".$check."<br></th>
					<tr>

					<tr>
    					<th><b> ".$Name[$j]."'s Personal Information </th>
					<tr>
						<td>Name</td>
						<td> ".$Name[$j]." </td>
					</tr>

					<tr>
						<td>Roll_No </td>
						<td>".$ROLL_No[$j]."</td>
					</tr>
					<tr>
						<td>ID</td>
						<td>".$ID[$j]."</td>
					</tr>			
					<tr>
						<td>Gender</td>
						<td>".$Gender[$j]."</td>
						</tr>
					<tr>
						<td>Date of Birth</td>
						<td>".$DOB[$j]."</td>
					</tr>
					<tr>
						<td>Date of Admission</td>
						<td>".$DATE_A[$j]."</td>
					</tr>			
				</table>
				<br><hr>


				<table align="."left"." id="."customers".">

					<tr>
    					<th width=70%><b> ".$Name[$j]."'s Guardian's Information </th>
					<tr>
						<td>Name</td>
						<td> ".$G_Name[$j]." </td>
					</tr>

					<tr>
						<td>ID </td>
						<td>".$G_ID[$j]."</td>
					</tr>
					<tr>
						<td>CNIC</td>
						<td>".$G_CNIC[$j]."</td>
					</tr>			
					<tr>
						<td>Email Address</td>
						<td>".$G_EMAIL[$j]."</td>
						</tr>
					<tr>
						<td>Home Address</td>
						<td>".$G_ADDRESS[$j]."</td>
					</tr>
					<tr>
						<td>Relation with Student</td>
						<td>".$G_RELATION[$j]."</td>
					</tr>
				</table>
				<br><hr>
				<table align="."left"." id="."customers".">

					<tr>
    					<th width=70%><b> ".$Name[$j]."'s Father's Information </th>
					<tr>
						<td>Name</td>
						<td> ".$F_Name[$j]." </td>
					</tr>

					<tr>
						<td>ID </td>
						<td>".$F_ID[$j]."</td>
					</tr>
					<tr>
						<td>CNIC</td>
						<td>".$F_CNIC[$j]."</td>
					</tr>			
					<tr>
						<td>Email Address</td>
						<td>".$F_EMAIL[$j]."</td>
						</tr>
					<tr>
						<td>Home Address</td>
						<td>".$F_ADDRESS[$j]."</td>
					</tr>
				</table>
				<br><hr><br>
				<table align="."left"." id="."customers".">

					<tr>
    					<th width=70%><b> ".$Name[$j]."'s Mother's Information </th>
					<tr>
						<td>Name</td>
						<td> ".$M_Name[$j]." </td>
					</tr>

					<tr>
						<td>ID </td>
						<td>".$M_ID[$j]."</td>
					</tr>
					<tr>
						<td>CNIC</td>
						<td>".$M_CNIC[$j]."</td>
					</tr>			
					<tr>
						<td>Email Address</td>
						<td>".$M_EMAIL[$j]."</td>
						</tr>
					<tr>
						<td>Home Address</td>
						<td>".$M_ADDRESS[$j]."</td>
					</tr>
				</table>
				<br><hr><br>
				<table align="."left"." id="."customers".">
					<tr>
    					<th width=70%><b> ".$Name[$j]."'s Academic Information </th>
					<tr>
				</table>
				<br><hr>
				<table align="."left"." id="."customers".">

					<tr>
    					<th width=70%><b> ".$Name[$j]."'s Current Class </th>
					<tr>
						<td>Name</td>
						<td> ".$C_Name[$j]." </td>
					</tr>

					<tr>
						<td>Class ID </td>
						<td>".$CClass_ID[$j]."</td>
					</tr>
					<tr>
						<td>Course ID</td>
						<td>".$CCourse_ID[$j]."</td>
					</tr>			
					<tr>
						<td>Class No</td>
						<td>".$CClass_No[$j]."</td>
						</tr>
					<tr>
						<td>Section</td>
						<td>".$C_Section[$j]."</td>
					</tr>
				</table> 
				<br><hr>

				<table align="."left"." id="."customers".">

				<tr>
   						<th width=70%><b> ".$Name[$j]."'s Previous Classes </th>
				<tr>
				</table>
				<br><hr><br>";
				$fun=1;
				for($L2=0;$L2<$oldcount[$j];$L2++){
							
					
					echo "<table align="."left"." id="."customers".">

						<tr>
    						<th width=70%><b> # ".$fun." </th>
						</tr>
						<tr>
							<td>Name</td>
							<td> ".$O_Name[$counter]." </td>
						</tr>

						<tr>
							<td>Class ID </td>
							<td>".$OClass_ID[$counter]."</td>
						</tr>
						<tr>
							<td>Course ID</td>
							<td>".$OCourse_ID[$counter]."</td>
						</tr>			
						<tr>
							<td>Class No</td>
							<td>".$OClass_No[$counter]."</td>
							</tr>
						<tr>
							<td>Section</td>
							<td>".$O_Section[$counter]."</td>
						</tr>
					</table>
					<br><hr><br>			
					";
					$counter++;
					$fun++;
				}

				echo "<table align="."left"." id="."customers".">

				<tr>
   						<th width=70%><b> ".$Name[$j]."'s Siblings Info </th>
				<tr>
				</table>
				<br><hr><br>";
				
				$fun=1;
				for($L2=0;$L2<$sibling_count[$j];$L2++){
							
					
					echo "<table align="."left"." id="."customers".">

						<tr>
    						<th width=70%><b> Sibling#".$fun."</th>
						</tr>
						<tr>
							<td>Name</td>
							<td> ".$S_Name[$counter2]." </td>
						</tr>

						<tr>
							<td>Class ID </td>
							<td>".$S_Current_Class[$counter2]."</td>
						</tr>
						<tr>
							<td>Guardian Name</td>
							<td>".$SG_Name[$counter2]."</td>
						</tr>			
						<tr>
							<td>Guardian ID</td>
							<td>".$SG_ID[$counter2]."</td>
							</tr>
						<tr>
							<td>Age</td>
							<td>".$S_AGE[$counter2]."</td>
						</tr>
						<tr>
							<td>Gender</td>
							<td>".$S_Gender[$counter2]."</td>
						</tr>
					</table>
					<br><hr><br>			
					";
					$counter2++;
					$fun++;
				}
				
				 echo "<br><hr><br><br><br><hr>";
				$check++;
			}

		?>