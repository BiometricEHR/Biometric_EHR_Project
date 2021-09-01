<!--view popup and create popup-> only submit, enable save feature in second stage-->
<!-- search for 'search feature php'-->
<?php
session_start();
require_once "config.php";



// variables for patient_history
$symptom = $diagnosis = $description = $prognosis = $emp_name = $department = $hospital_id = '';
$temp_patient_ic = $_SESSION["patient_ic"];
$temp_emp_ic = $_SESSION["emp_ic"];
$i = 1;

//////////////////////////////////////////////////////////////////////////////////////
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $symptom = $_POST['symptom'];
    $diagnosis = $_POST['diagnosis'];
    $description = $_POST['description'];
    $prognosis = $_POST['prognosis'];
    $emp_name = $_SESSION["emp_name"];
	$department = $_SESSION["department"];
	$hospital_id = $_SESSION["hospital_id"];

    $sql = "INSERT INTO medical_report (symptom, diagnosis, description, prognosis, emp_name, department, hospital_id, patient_ic) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssssssss", $symptom, $diagnosis, $description, $prognosis, $emp_name, $department, $hospital_id, $param_patient_ic);
        // Set parameters
        $param_patient_ic = $temp_patient_ic;

        if(mysqli_stmt_execute($stmt)){
            header("location: medical_report.php");
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
    <title>Medical Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {display: flex; height: 100%; width: 100%; background-color: lightskyblue;}
        .popup-content {height: 80vh; overflow-y:scroll;}
        .wrapper {top: 10vh; 
		background-color:white;
		border-radius:5px;
		text-align:center;
		position:absolute;width: 800px; height: ; padding:  20px; align-items:center;justify-content: center;padding-bottom: 50px;}
		
		.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
		
		#create{
			top: 50px;
		}
    </style>
    <style>
        .popup_create {width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); position: absolute; top: 0; display: flex; justify-content: center; align_items: center; display: none;}
        .popup-content {width: 50vw; height: 90vh; top: 5vh; background-color: white; text-align: center; padding: 20px; position: relative;}
        .close {position: absolute; top: 5px; right: 15px; font-size: 30px; transform: rotate(45deg); cursor: pointer;}
        textarea {resize: none; height: 10em; width: 100%; position: relative;}
    </style>
    <style>
        table{
			border-collapse: collapse;
			border: 1px solid black;
			table-layout: fixed;
			width: 90%; 
			margin-top: 50px;
			margin-bottom: 50px;
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
<div class="container">
<div id="medical-report" class="wrapper">
    
    <h2>Medical Report</h2>
    <table>
        <?php
        $sql = "SELECT * FROM medical_report WHERE patient_ic ='$temp_patient_ic' ORDER BY med_report_id DESC";
        $result = mysqli_query ($link, $sql);
        if(mysqli_num_rows($result) > 0){
            echo "<tr><th width='5%'>No.</th>";
            echo "<th>Department</th>";
            echo "<th>Diagnosis</th>";
            echo "<th>Prognosis</th>";
            echo "<th>Clinician</th>";
            echo "<th>Date</th>";
            echo "<th>Action</th></tr>";
            while ($row = mysqli_fetch_array ($result)) {
                echo "<tr><td>$i</td>";
                echo "<td>".$row['department']."</td>";
                echo "<td>".$row['diagnosis']."</td>";
                echo "<td>".$row['prognosis']."</td>";
                echo "<td>".$row['emp_name']."</td>";
                echo "<td>".$row['report_submit_dt']."</td>";
                echo "<td><a href='medical_report_view.php?med_report_id=". $row['med_report_id'] ."' target='_blank' class='btn btn-primary' title='View'>View</a></td></tr>";
                $i++;
                // mysqli_free_result($result); -> not sure purpose of having it, if using this need to have $result1
            }
        } else {
            echo "<p><em>No records were found.</em></p>";
        }
        ?>
    </table>
    <div>
        <input type="create" id="create" class="btn btn-primary" value="Create">
    </div>
</div>
</div>

<!--################################################################################################################################-->
<div id="medical_report_create" class="popup_create">
    <div class="popup-content">
        <div class="close">+</div>
        <h2>Medical Report</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label>Symptom</label>
                <textarea name="symptom" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Diagnosis</label>
                <textarea name="diagnosis" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Prognosis</label>
                <textarea name="prognosis" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <input type="submit" id="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>
</div>
<script>
    //if want to have pop up for view, maybe need to make '.close' for both popup different name to avoid errors
    document.getElementById('create').addEventListener('click', function(){
        document.querySelector('.popup_create').style.display='flex';
    });
    document.querySelector('.close').addEventListener('click', function(){
        document.querySelector('.popup_create').style.display='none';
    });
</script>
</body>
</html>