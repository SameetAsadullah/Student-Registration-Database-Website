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
	<div class="header">
		  <a href="#default" class="logo">HAMRAY BACHAY</a>
		  <div class="header-right">
			    <a href="insertion.php">Admission Form</a>
			    <a href="feeChallan.php">Course Registration</a>
			    <a href="accompany.php">Accompany</a>
			    <a class="active" href="forStaff.php">For Staff</a>
		  </div>
	</div>
	<h2 align="center">Parent's DataBase</h2>
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


	<body align="middle">
	<hr>
	<b>Search Parent Data </b>
	 <br><br>
	<form action="" method="post">
		<input type="text" name="eNum"/>
		<select name="type1">
	   		<option value="NAME">Name</option>
		    <option value="ID">ID</option>
	   		<option value="CNIC">CNIC</option>

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
		$select2=ucwords($select2);//converts First letter capital
	}
	//If in roll number section a Name is searched
	if(($select1=="ID" || $select1=="CNIC" )  && !is_numeric($select2)){
		die("<b>Please Enter a valid in CNIC/ID.</b>");
	}
	
	if($select1=='NAME')
		$sql_select_up="select ID,CNIC,NAME,GENDER,CONTACT,EMAIL,ADDRESS,RELATION,IS_STAFF FROM GUARDIAN WHERE ($select1  LIKE ('$select2%') and (RELATION= 'FATHER' or RELATION='MOTHER')) ";
	else{
		$sql_select_up="select ID,CNIC,NAME,GENDER,CONTACT,EMAIL,ADDRESS,RELATION,IS_STAFF FROM GUARDIAN WHERE ($select1 ='$select2' and (RELATION= 'FATHER' or RELATION='MOTHER')) ";
	//	echo "agya".$select2;
	}
	$query_id_up = oci_parse($con, $sql_select_up);
	$runselect_up = oci_execute($query_id_up);
	if(!$query_id_up){
		die("wer gay");
	}
	if($runselect_up){}
	else
	{
		echo "No Guardian with given Info Found..";
	}

	$i=0;
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$P_ID[$i]= $row["ID"];
		$P_CNIC[$i]= $row["CNIC"];
		$P_Name[$i]= $row["NAME"];
		$P_CONTACT[$i]=$row["CONTACT"];
		$P_EMAIL[$i]=$row["EMAIL"];
		$P_Gender[$i]=$row["GENDER"];
		$P_Address[$i]=$row["ADDRESS"];
		$P_Relation[$i]=$row["RELATION"];
		if($row["IS_STAFF"])
			$P_IS_STAFF[$i]="Yess";
		else
			$P_IS_STAFF[$i]="Yess";
		$i++;
	}
	if($i==0){
		die("<b>No Match Found</b>");
	}

//Fetching Childersn Data
$totalcount=0;
for($j=0;$j<$i;$j++){
	$sql_select_up="select ID,ROLL_NO,NAME,to_char(DOB,'dd/mm/yyyy') DOB,to_char(DATE_ADMITTED,'dd/mm/yyyy') DATE_ADMITTED,GENDER,AGE,FATHER_ID,MOTHER_ID,GUARDIAN_ID from Student where (FATHER_id = '$P_ID[$j]' OR MOTHER_ID= '$P_ID[$j]' )";
	$query_id_up = oci_parse($con, $sql_select_up);
	$runselect_up = oci_execute($query_id_up);
	if(!$query_id_up){
		die("Wer gay");
	}
	if($runselect_up){}
	else
	{
		echo "No Student with given Info Found..";
	}

	
	$f=0;
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{

	//	echo $row["ID"];
		$C_ID[$totalcount]= $row["ID"];
		$C_ROLL_No[$totalcount]= $row["ROLL_NO"];
		$C_Name[$totalcount]= $row["NAME"];
		$C_DOB[$totalcount]=$row["DOB"];
		$C_DATE_A[$totalcount]=$row["DATE_ADMITTED"];
		$C_Gender[$totalcount]=$row["GENDER"];
		$C_AGE[$totalcount]=$row["AGE"];
		$C_FATHER[$totalcount]=$row["FATHER_ID"];
		$C_MOTHER[$totalcount]=$row["MOTHER_ID"];
		$C_GUARDIAN[$totalcount]=$row["GUARDIAN_ID"];
		$f++;
		$totalcount++;
	}
	$countChild[$j]=$f;
}
//Getting Guardian Info
for($j=0;$j<$totalcount;$j++){
		$sql_select_up="select ID,CNIC,NAME,GENDER,CONTACT,EMAIL,ADDRESS,RELATION from guardian where (ID = '$C_GUARDIAN[$j]')";
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

//Fetchcing about the Clsses

//Fetching current course of all childs
for($j=0;$j<$totalcount;$j++){
	$sql_select_up=" select max(course_id) as COURSE_ID from registered where (student_id='$C_ID[$j]')";
		$query_id_up = oci_parse($con, $sql_select_up);
		$runselect_up = oci_execute($query_id_up);
		if(!$query_id_up){
			die("Didn't Work with MAX Course Query");
		}
		if($runselect_up){}
		else
		{
			die( "No Course MAX Found..");
		}		
	
	while($row = oci_fetch_array($query_id_up, OCI_BOTH+OCI_RETURN_NULLS)) 
	{
		$max_Course[$j]=$row["COURSE_ID"];
	}
}
//Fetching Current Classes of All the Childs
//Fetching Current Class Info	
for($j=0;$j<$totalcount;$j++){
		$sql_select_up="select CLASS_ID from registered where( student_id='$C_ID[$j]' and course_id='$max_Course[$j]')";
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

//Fetvhing Data about All the Current Classes of ALl childs

for($j=0;$j<$totalcount;$j++){
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
		$CC_Section[$j]=$row["SECTION"];
		$CC_Name[$j]=$row["NAME"];
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
			for($j=0;$j<$i;$j++){

					echo "
					<table align="."left"." id="."customers"." >
					<tr>
    					<th  ><b> "."Parent # ".$check."<br></th>

					<tr>
					</table>
					<br><br><hr>
					<table align="."left"."  id="."customers"." >
					<tr>
    					<th><b> ".$P_Name[$j]."'s Personal Information </th>
					<tr>
						<td>Name</td>
						<td> ".$P_Name[$j]." </td>
					</tr>

					<tr>
						<td>ID </td>
						<td>".$P_ID[$j]."</td>
					</tr>
					<tr>
						<td>CNIC</td>
						<td>".$P_CNIC[$j]."</td>
					</tr>			
					<tr>
						<td>Gender</td>
						<td>".$P_Gender[$j]."</td>
						</tr>
					<tr>
						<td>Email</td>
						<td>".$P_EMAIL[$j]."</td>
					</tr>
					<tr>
						<td>Address</td>
						<td>".$P_Address[$j]."</td>
					</tr>			
					<tr>
						<td>Contact</td>
						<td>".$P_CONTACT[$j]."</td>
					</tr>
					<tr>
						<td>Relation </td>
						<td>".$P_Relation[$j]."</td>
					</tr>
					<tr>
						<td>Relation </td>
						<td>".$P_IS_STAFF[$j]."</td>
					</tr>								
				</table>
				<br><hr>

					<table align="."middle"." id="."customers"." >
					<tr>
    					<th  ><b> CHILDREN INFORMATION <br></th>

					<tr>
					</table>
				<br><hr>				
				";
				$childs=1;
				for($L2=0;$L2<$countChild[$j];$L2++){

						echo "
						

						<table align="."left"." id="."customers"." >
						<tr>
    						<th  ><b> "."CHILD # ".$childs."<br></th>

						<tr>	
						</table>
						<br><br><hr>
						<table align="."left"." id="."customers".">
							<td>Name</td>
							<td> ".$C_Name[$counter]." </td>
							</tr>

							<tr>
								<td>Roll_No </td>
							<td>".$C_ROLL_No[$counter]."</td>
							</tr>
							<tr>
								<td>ID</td>
								<td>".$C_ID[$counter]."</td>
							</tr>			
							<tr>
								<td>Gender</td>
								<td>".$C_Gender[$counter]."</td>
								</tr>
							<tr>
									<td>Date of Birth</td>
							<td>".$C_DOB[$counter]."</td>
							</tr>
							<tr>
								<td>Date of Admission</td>
								<td>".$C_DATE_A[$counter]."</td>
							</tr>			
						</table>
						<br><hr>
						<table align="."left"."  id="."customers"." >
							<tr>
	    						<th><b> ".$C_Name[$counter]."'s Guardian Information </th>
							<tr>


								<td>Name</td>
							<td> ".$G_Name[$counter]." </td>
							</tr>

							<tr>
								<td>ID </td>
								<td>".$G_ID[$counter]."</td>
							</tr>
							<tr>
								<td>CNIC</td>
								<td>".$G_CNIC[$counter]."</td>
							</tr>			
							<tr>
								<td>Gender</td>
								<td>".$G_Gender[$counter]."</td>
								</tr>
							<tr>
								<td>Email</td>
								<td>".$G_EMAIL[$counter]."</td>
							</tr>
							<tr>
								<td>Address</td>
								<td>".$G_ADDRESS[$counter]."</td>
							</tr>			
							<tr>
								<td>Contact</td>
								<td>".$G_CONTACT[$counter]."</td>
							</tr>
							<tr>
								<td>Relation </td>
								<td>".$G_RELATION[$counter]."</td>
							</tr>								
						</table>
						<br><hr>
						<table align="."left"." id="."customers".">

							<tr>
    							<th ><b> ".$C_Name[$counter]."'s Current Class </th>
							<tr>
								<td>Name</td>
								<td> ".$CC_Name[$counter]." </td>
							</tr>

							<tr>
								<td>Class ID </td>
								<td>".$CClass_ID[$counter]."</td>
							</tr>
							<tr>
								<td>Course ID</td>
								<td>".$CCourse_ID[$counter]."</td>
							</tr>			
							<tr>
								<td>Class No</td>
								<td>".$CClass_No[$counter]."</td>
							</tr>
							<tr>
								<td>Section</td>
								<td>".$CC_Section[$counter]."</td>
							</tr>
						</table> 
						<br><hr>

						<br><hr><br>				
					";
					$childs++;
					$counter++;
				}

				
				$check++;
			}

		?>