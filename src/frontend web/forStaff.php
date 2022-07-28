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
	<h1 align="center" style="color: blue;">Forms</h1>
	<h3 align="center"><a href="challan.php">Generate Fee Challan</a></h3>
	<h3 align="center"><a href="changeClass.php">Student Class Change</a></h3>
	<h3 align="center"><a href="addStudent.php">Assign Class to Student</a></h3>
	<h3 align="center"><a href="addCourse.php">Offer Course</a></h3>
	<h1 align="center" style="color: darkblue;">Reports</h1>
	<h3 align="center"><a href="studentManagement.php">Student Management</a></h3>
	<h3 align="center"><a href="classesReport.php">Class Management</a></h3>
	<h3 align="center"><a href="myreport13.php">Dormant Students</a></h3>
	<h3 align="center"><a href="myreport14.php">Student Information Report</a></h3>
	<h3 align="center"><a href="myreport15.php">Guardian Information Report</a></h3>
</body>
</html>