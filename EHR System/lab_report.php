<?php
session_start();
require_once "config.php";



// variables for patient_history
$lab_test_type = $emp_name = $hospital_id = '';
$temp_patient_ic = $_SESSION["patient_ic"];
$i = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laboratory Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {display: flex; height: 100%; width: 100%; background-color: #89CFF0;}
        .wrapper {top: 10vh; 
		background-color:white;
		border-radius:4px;
		text-align:center;
		position:absolute;width: 650px; height: 500px; padding: 20px; align-items:center;justify-content: center; padding-bottom: 50px;overflow-y:scroll;}
		
	
	.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
    
        
    </style>
    <style>
        table{
			border-collapse: collapse;
			border: 1px solid black;
			table-layout: fixed;
			width: 80%; 
			margin-left: auto;
			margin-right:auto;
			
		} 
		th, td {border: 1px solid black;}
    </style>
</head>

<body>
<header>
    <p><a href="patient_portal.php" class="btn btn-primary">Back</a></p>
</header>
<div class = "container">
<div class= "wrapper">

    
    <h2>Laboratory Report</h2>
    <table>
        <?php
        $sql = "SELECT * FROM lab_report WHERE patient_ic ='$temp_patient_ic' ORDER BY lab_report_id DESC";
        $result = mysqli_query ($link, $sql);
        if(mysqli_num_rows($result) > 0){
            echo "<tr><th width='10%'>No.</th>";
            echo "<th>Staff</th>";
            echo "<th>Date</th>";
            echo "<th>Action</th></tr>";
            while ($row = mysqli_fetch_array ($result)) {
                echo "<tr><td>$i</td>";
                echo "<td>" . $row['emp_name'] . "</td>";
                echo "<td>" . $row['report_submit_dt'] . "</td>";
                echo "<td><a href='lab_report_view.php?lab_report_id=" . $row['lab_report_id'] . "' target='_blank' class='btn btn-primary' title='View'>View</a></td></tr>";
                $i++;
                // mysqli_free_result($result); -> not sure purpose of having it, if using this need to have $result1
            }
        } else {
            echo "<p><em>No records were found.</em></p>";
        }
        ?>
    </table>
</div>
</div>
</div>
</body>
</html>