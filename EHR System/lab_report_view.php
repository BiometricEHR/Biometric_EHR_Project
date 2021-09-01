<?php
// NOTE: BETTER USE INT or whatever numeric type for lab test data that should only be numbers

$emp_name = $department = $hospital_id = $report_submit_dt = '';
$temp_patient_ic = '990820075629';
$temp_emp_ic = '991122334455';

// Check existence of id parameter before processing further
if(isset($_GET["lab_report_id"]) && !empty(trim($_GET["lab_report_id"]))){
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM lab_report WHERE lab_report_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["lab_report_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $emp_name = $row['emp_name'];
                $department = $row['department'];
                $hospital_id = $row['hospital_id'];
                $report_submit_dt = $row['report_submit_dt'];
                $lab_test_type = $row['lab_test_type'];

                $wbc_count = $row['wbc_count'];
                $rbc_count = $row['rbc_count'];
                $hct_test = $row['hct_test'];
                $mcv_test = $row['mcv_test'];
                $mch_test = $row['mch_test'];
                $rcdw_test = $row['rcdw_test'];
                $platelet_count = $row['platelet_count'];
                $mpv = $row['mpv'];
                $polymorphs = $row['polymorphs'];
                $lymphocytes = $row['lymphocytes'];
                $monocytes = $row['monocytes'];
                $eosinophils = $row['eosinophils'];
                $basophils = $row['basophils'];
                $ft4 = $row['ft4'];
                $rheumatoid_factor = $row['rheumatoid_factor'];
                $afp = $row['afp'];
                $hav = $row['hav'];
                $ph = $row['ph'];
                $protein = $row['protein'];
                $glucose = $row['glucose'];
                $ketone = $row['ketone'];
                $blood = $row['blood'];
                $colour = $row['colour'];
                $transparency = $row['transparency'];
                $specific_gravity = $row['specific_gravity'];
                $sodium = $row['sodium'];
                $potassium = $row['potassium'];
                $chloride = $row['chloride'];
                $urea = $row['urea'];
                $creatinine = $row['creatinine'];
                $gfr = $row['gfr'];
                $calcium = $row['calcium'];
                $phosphorus = $row['phosphorus'];
                $uric_acid = $row['uric_acid'];
                $syphilis = $row['syphilis'];

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
                    <label>Laboratorian</label>
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
                    <label>Haematology</label>
                    <table>
                        <tr><th width='60%'>Test Name</th><th width='10%'>Result</th><th width='10%'>Unit</th></tr>
                        <tr><td><u>Full Blood Picture</u></td></tr>
                        <tr><td>WBC Count</td><td><?php echo $wbc_count; ?></td><td>x10^9 /L</td></tr>
                        <tr><td>RBC Count</td><td><?php echo $rbc_count; ?></td><td>x10^9 /L</td></tr>
                        <tr><td>HCT Test</td><td><?php echo $hct_test; ?></td><td></td></tr>
                        <tr><td>MCV Test</td><td><?php echo $mcv_test; ?></td><td>fl</td></tr>
                        <tr><td>MCH Test</td><td><?php echo $mch_test; ?></td><td>pg</td></tr>
                        <tr><td>RCDW Test</td><td><?php echo $rcdw_test; ?></td><td></td></tr>
                        <tr><td>Platelet Count</td><td><?php echo $platelet_count; ?></td><td>x10^9 /L</td></tr>
                        <tr><td>MPV</td><td><?php echo $mpv; ?></td><td></td></tr>
                        <tr><td><u>White Blood Cell Differential Count</u></td></tr>
                        <tr><td>Polymorphs</td><td><?php echo $polymorphs; ?></td><td>x10^9 /L</td></tr>
                        <tr><td>Lymphocytes</td><td><?php echo $lymphocytes; ?></td><td>x10^9 /L</td></tr>
                        <tr><td>Monocytes</td><td><?php echo $monocytes; ?></td><td>x10^9 /L</td></tr>
                        <tr><td>Eosinophils</td><td><?php echo $eosinophils; ?></td><td>x10^9 /L</td></tr>
                        <tr><td>Basophils</td><td><?php echo $basophils; ?></td><td>x10^9 /L</td></tr>
                    </table>
                </div>
                <div class="form-group">
                    <label>Immunology</label>
                    <table>
                        <tr><th width='60%'>Test Name</th><th width='10%'>Result</th><th width='10%'>Unit</th></tr>
                        <tr><td>FT4</td><td><?php echo $ft4; ?></td><td>pmol/L</td></tr>
                        <tr><td>Rheumatoid Factor</td><td><?php echo $rheumatoid_factor; ?></td><td>IU/mL</td></tr>
                        <tr><td>AFP</td><td><?php echo $afp; ?></td><td>IU/mL</td></tr>
                        <tr><td>HAV</td><td><?php echo $hav; ?></td><td></td></tr>
                    </table>
                </div>
                <div class="form-group">
                    <label>Urinalysis</label>
                    <table>
                        <tr><th width='60%'>Test Name</th><th width='10%'>Result</th><th width='10%'>Unit</th></tr>
                        <tr><td>pH</td><td><?php echo $ph; ?></td><td></td></tr>
                        <tr><td>Protein</td><td><?php echo $protein; ?></td><td></td></tr>
                        <tr><td>Glucose</td><td><?php echo $glucose; ?></td><td></td></tr>
                        <tr><td>Ketone</td><td><?php echo $ketone; ?></td><td></td></tr>
                        <tr><td>Blood</td><td><?php echo $blood; ?></td><td></td></tr>
                        <tr><td>Colour</td><td><?php echo $colour; ?></td><td></td></tr>
                        <tr><td>Transparency</td><td><?php echo $transparency; ?></td><td></td></tr>
                        <tr><td>Specific Gravity</td><td><?php echo $specific_gravity; ?></td><td></td></tr>
                    </table>
                </div>
                <div class="form-group">
                    <label>Biochemistry</label>
                    <table>
                        <tr><th width='60%'>Test Name</th><th width='10%'>Result</th><th width='10%'>Unit</th></tr>
                        <tr><td>Sodium</td><td><?php echo $sodium; ?></td><td>mmol/L</td></tr>
                        <tr><td>Potassium</td><td><?php echo $potassium; ?></td><td>mmol/L</td></tr>
                        <tr><td>Chloride</td><td><?php echo $chloride; ?></td><td>mmol/L</td></tr>
                        <tr><td>Urea</td><td><?php echo $urea; ?></td><td>mmol/L</td></tr>
                        <tr><td>Creatinine</td><td><?php echo $creatinine; ?></td><td>umol/L</td></tr>
                        <tr><td>Glomerular Filtration Rate</td><td><?php echo $gfr; ?></td><td>mL/min/1.7</td></tr>
                        <tr><td>Calcium</td><td><?php echo $calcium; ?></td><td>mmol/L</td></tr>
                        <tr><td>Phosphorus</td><td><?php echo $phosphorus; ?></td><td>mmol/L</td></tr>
                        <tr><td>Uric Acid</td><td><?php echo $uric_acid; ?></td><td>umol/L</td></tr>
                    </table>
                </div>
                <div class="form-group">
                    <label>Serology</label>
                    <table>
                        <tr><th width='60%'>Test Name</th><th width='10%'>Result</th><th width='10%'>Unit</th></tr>
                        <tr><td>Syphilis</td><td><?php echo $syphilis; ?></td><td></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>