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
    <title>Radiology Report</title>
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


        <h2>Radiology Report</h2>
        <table>
            <tr><th width='10%'>No.</th>
                <th>Staff</th>
                <th>Date</th>
                <th>Action</th></tr>
            <tr><td>1</td>
                <td>Koo Jia Cheng</td>
                <td>2021-08-22 15:34:35</td>
                <td><a href='radiology_report_view.php' target='_blank' class='btn btn-primary' title='View'>View</a></td></tr>
        </table>
    </div>
</div>
</div>
</body>
</html>