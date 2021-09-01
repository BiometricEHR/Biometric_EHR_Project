<?php

$symptom = $diagnosis = $description = $prognosis = $emp_name = $department = $hospital_id = $report_submit_dt = '';
$temp_patient_ic = '990820075629';
$temp_emp_ic = '991122334455';

// Check existence of id parameter before processing further
if(isset($_GET["med_report_id"]) && !empty(trim($_GET["med_report_id"]))){
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM medical_report WHERE med_report_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["med_report_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $symptom = $row['symptom'];
                $diagnosis = $row['diagnosis'];
                $description = $row['description'];
                $prognosis = $row['prognosis'];
                $report_submit_dt = $row['report_submit_dt'];
                $emp_name = $row['emp_name'];
                $department = $row['department'];
                $hospital_id = $row['hospital_id'];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Clinician</label>
                    <input type="text" name="emp_name" class="form-control" value="<?php echo $emp_name; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" name="department" class="form-control" value="<?php echo $department; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Hospital</label>
                    <input type="text" name="hospital_id" class="form-control" value="<?php echo $hospital_id; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="text" name="report_submit_dt" class="form-control" value="<?php echo $report_submit_dt; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Symptom</label>
                    <textarea name="symptom" class="form-control" readonly><?php echo $symptom; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Diagnosis</label>
                    <textarea name="diagnosis" class="form-control" readonly><?php echo $diagnosis; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" readonly><?php echo $description; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Prognosis</label>
                    <textarea name="prognosis" class="form-control" readonly><?php echo $prognosis; ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>