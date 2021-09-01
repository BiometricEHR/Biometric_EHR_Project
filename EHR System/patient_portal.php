<?php
session_start();
require_once "config.php";

/*NOTE - TO DO*/
// CSS IS A MESS! ESPECIALLY POSITIONING /* better use flex/grid instead of hard-coding the px distance */
// header for back, logout, and patient name
// footer incomplete
// sweet alert for mysqli_stmt_execute($stmt) instead of heading to p-p2
//// straight use SESSION instead of temp variable
/// back and logout button


// variables for patient_history
$chief_complaint = $present_history = $past_history = $medication = $allergy = $family_history = $social_history = $emp_name = "";
$temp_patient_ic = $_SESSION["patient_ic"];
$temp_emp_ic = $_SESSION["emp_ic"];

// create entry if patient is new to patient_history
$query = mysqli_query($link,"SELECT * FROM patient_history WHERE patient_ic = '$temp_patient_ic' ");
$rowCheck = mysqli_num_rows($query);
if (!$rowCheck>0) {
    $query = mysqli_query($link,"INSERT INTO patient_history (patient_ic) VALUES('$temp_patient_ic')");
}

// save upload data to database
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $chief_complaint = $_POST['chief_complaint'];
    $present_history = $_POST['present_history'];
    $past_history = $_POST['past_history'];
    $medication = $_POST['medication'];
    $allergy = $_POST['allergy'];
    $family_history = $_POST['family_history'];
    $social_history = $_POST['social_history'];

    $sql = "SELECT emp_name FROM employee WHERE emp_ic = '$temp_emp_ic'";
    $query = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($query)) {
        $emp_name = $row['emp_name'];
    }

    $sql = "UPDATE patient_history 
            SET chief_complaint = ?,
                present_history = ?,
                past_history = ?,
                medication = ?,
                allergy = ?,
                family_history = ?,
                social_history = ?,
                emp_name = ?,
                history_modi_dt = current_timestamp()
            WHERE patient_ic = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssssssss", $chief_complaint, $present_history, $past_history,
            $medication, $allergy, $family_history, $social_history, $emp_name, $param_patient_ic);
        // Set parameters
        $param_patient_ic = $temp_patient_ic;

        if(mysqli_stmt_execute($stmt)){
            header("location: patient_portal.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Portal</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {display: flex; height: 100%; width: 100%; background-color: #89CFF0;}
        #report_button {position: relative; left:30vw;}
        .popup-content {height: 80vh; overflow-y:scroll;}
        .wrapper {top: 60px; 
		background-color:white;
		border-radius:4px;
		text-align:center;
		position:absolute;width: 45vw; height: 85vh; padding: 30px 20px; overflow-y:scroll;}
		
		.wrapper2 {top: 60px; left: 50px; 
		background-color:white;
		border-radius:20px;
		position:absolute; padding: 30px 30px;}
		
		.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
		
		.button {
			background-color: #4CAF50;
			border: none;
			border-radius:20px;
			color: white;
			width:200px;
			padding: 15px ;
			text-align: center;
			text-decoration: none !important;
			display: inline-block;
			font-size: 20px;
			margin: 5px;
			cursor: pointer;
			position: relative;
			left:80px;
			top: 60px;
		}
		
		.backButton{
			color: #fff;
			background-color: #337ab7;
			border-color: #2e6da4;
			border-radius:18px;
			text-align:center;
			padding:10px 20px;
			position:absolute;
			text-decoration:none !important;
			left:200px;
		}
			

    </style>
    <style>
        .popup {width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); position: absolute; top: 0; display: flex; justify-content: center; align_items: center; display: none;}
        .popup-content {width: 50vw; height: 90vh; top: 5vh; background-color: white; text-align: center; padding: 20px; position: relative;}
        .close {position: absolute; top: 5px; right: 15px; font-size: 30px; transform: rotate(45deg); cursor: pointer;}
        textarea {resize: none; height: 10em; width: 100%; position: relative;}
    </style>
</head>

<body>
<header>
    <a href="doctorhome.php" class="btn btn-primary">Back</a>
</header>

<div id="patient-info" class="wrapper2">
		
    <?php
    $sql = "SELECT * FROM patient WHERE patient_ic ='$temp_patient_ic'";
    $result = mysqli_query ($link, $sql);
    while ($row = mysqli_fetch_array ($result)){
        ?>
        <!-- haven't solve patient image -->
        
        <table>
            <tr>
                <th>Name:</th>
                <th><?php echo $row ['patient_name']; ?></th>
            </tr>
            <tr>
                <th>Gender:</th>
                <th><?php echo $row ['gender']; ?></th>
            </tr>
            <tr>
                <th>Date of Birth:</th>
                <th><?php echo $row ['date_of_birth']; ?></th>
            </tr>
            <tr>
                <th>Ethnicity:</th>
                <th><?php echo $row ['ethnicity']; ?></th>
            </tr>
            <tr>
                <th>Phone:</th>
                <th><?php echo $row ['phone']; ?></th>
            </tr>
            <tr>
                <th>Blood Type:</th>
                <th><?php echo $row ['blood_type']; ?></th>
            </tr>
        </table>
        <?php
    }
    ?>
</div>

<!--################################################################################################################################-->
<div class="container">
<div class="wrapper">
<div id="patient-history" class="">
    <h2>Patient History</h2>
    <?php
    $sql = "SELECT * FROM patient_history WHERE patient_ic ='$temp_patient_ic'";
    $result = mysqli_query ($link, $sql);
    while ($row = mysqli_fetch_array ($result)){
        ?>
        <form action="" class="">
            <div class="form-group">
                <label>Summary of Patient History</label>
                <textarea name="summary_of_patient_history" class="form-control" readonly>Overall is okay, SpO2 returned to 97%, can prepare to discharge</textarea>
            </div>
            <div class="form-group">
                <label>Chief Complaint</label>
                <textarea name="chief_complaint" class="form-control" readonly><?php echo $row ['chief_complaint']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Present History</label>
                <textarea name="present_history" class="form-control" readonly><?php echo $row ['present_history']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Past History</label>
                <textarea name="past_history" class="form-control" readonly><?php echo $row ['past_history']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Medication</label>
                <textarea name="medication" class="form-control" readonly><?php echo $row ['medication']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Allergy</label>
                <textarea name="allergy" class="form-control" readonly><?php echo $row ['allergy']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Family History</label>
                <textarea name="family_history" class="form-control" readonly><?php echo $row ['family_history']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Social History</label>
                <textarea name="social_history" class="form-control" readonly><?php echo $row ['social_history']; ?></textarea>
            </div>
            <p>Updated by: Dr. <?php echo $row ['emp_name']; ?></p>
            <p>Updated on: <?php echo $row ['history_modi_dt']; ?></p>
            <div class="form-group">
                <input type="edit" id="edit" class="btn btn-primary" value="Edit">
            </div>
        </form>
        <?php
    }
    ?>
</div>
</div>
<div id="report_button" class="">
    <a href="medical_report.php" class="button">Medical Report</a>
    <br>
    <a href="lab_report.php" class="button">Laboratory Report</a>
    <br>
    <a href="radiology_report.php" class="button">Radiology Report</a>
</div>
</div>
<!--################################################################################################################################-->

<div id="patient-history-edit" class="popup">
    <div class="popup-content">
        <div class="close">+</div>
        <h2>Patient History</h2>
        <?php
        $sql = "SELECT * FROM patient_history WHERE patient_ic ='$temp_patient_ic'";
        $result = mysqli_query ($link, $sql);
        while ($row = mysqli_fetch_array ($result)){
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label>Summary of Patient History</label>
                    <textarea name="summary_of_patient_history" class="form-control">Overall is okay, SpO2 returned to 97%, can prepare to discharge</textarea>
                </div>
                <div class="form-group">
                    <label>Chief Complaint</label>
                    <textarea name="chief_complaint" class="form-control"><?php echo $row ['chief_complaint']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Present History</label>
                    <textarea name="present_history" class="form-control"><?php echo $row ['present_history']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Past History</label>
                    <textarea name="past_history" class="form-control"><?php echo $row ['past_history']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Medication</label>
                    <textarea name="medication" class="form-control"><?php echo $row ['medication']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Allergy</label>
                    <textarea name="allergy" class="form-control"><?php echo $row ['allergy']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Family History</label>
                    <textarea name="family_history" class="form-control"><?php echo $row ['family_history']; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Social History</label>
                    <textarea name="social_history" class="form-control"><?php echo $row ['social_history']; ?></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" id="submit" class="btn btn-primary" value="Submit">
                </div>
            </form>
            <?php
        }
        ?>
    </div>
</div>

<script>
    document.getElementById('edit').addEventListener('click', function(){
        document.querySelector('.popup').style.display='flex';
    });
    document.querySelector('.close').addEventListener('click', function(){
        document.querySelector('.popup').style.display='none';
    });
</script>


<footer>
    <!--editor and date-->
</footer>
</body>
</html>