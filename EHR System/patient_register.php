<?php
//Register
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$patient_name = $date_of_birth = $patient_ic = $fingerprint_encoding = $gender = $blood_type=$nationality=$ethnicity=$phone=$hospital_id="";
$patient_name_err = $date_of_birth_err = $patient_ic_err = $fingerprint_encoding_err =$gender_err = $blood_type_err=$nationality_err=$ethnicity_err=$phone_err=$hospital_id_err= "";
$temp_rpi_id = $_SESSION["rpi_id"];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate patient name
    if (empty(trim($_POST["patient_name"]))) {
        $patient_name_err = "Please enter your name.";
    } else {
        $patient_name = $_POST["patient_name"];
    }

    // Validate date of birth
    if (empty(trim($_POST["date_of_birth"]))) {
        $date_of_birth_err = "Please enter your birthdate.";
    } else {
        $date_of_birth = $_POST["date_of_birth"];
    }

    // Validate patient ic
    if (empty(trim($_POST["patient_ic"]))) {
        $patient_ic_err = "Please enter a patient IC.";
    } else if (strlen(trim($_POST["patient_ic"]))!== 12){
		$patient_ic_err = "Invalid IC number.";
	}else{
        // Prepare a select statement
        $sql = "SELECT patient_ic FROM patient WHERE patient_ic = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_patient_ic);

            // Set parameters
            $param_patient_ic = trim($_POST["patient_ic"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $patient_ic_err = "This patient IC is already taken.";
                } else {
                    $patient_ic = trim($_POST["patient_ic"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate gender
    if (empty(trim($_POST["gender"]))) {
        $gender_err = "Please enter your name.";
    } else {
        $gender = $_POST["gender"];
    }

    // Validate blood type
    if (empty(trim($_POST["blood_type"]))) {
        $blood_type_err = "Please enter your blood type.";
    } else {
        $blood_type = $_POST["blood_type"];
    }

    // Validate nationality
    if (empty(trim($_POST["nationality"]))) {
        $nationality_err = "Please enter your nationality.";
    } else {
        $nationality = $_POST["nationality"];
    }

    // Validate ethnicity
    if (empty(trim($_POST["ethnicity"]))) {
        $ethnicity_err = "Please enter your ethnicity.";
    } else {
        $ethnicity = $_POST["ethnicity"];
    }

    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = $_POST["phone"];
    }


    // Check input errors before inserting in database
    if (empty($patient_name_err) && empty($date_of_birth_err) && empty($patient_ic_err) && empty($fingerprint_encoding_err) &&
        empty($gender_err) && empty($blood_type_err) && empty($nationality_err) && empty($ethnicity_err) && empty($phone_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO patient (patient_ic, fingerprint_encoding, patient_name, gender, date_of_birth, blood_type, nationality, ethnicity, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssss", $param_patient_ic, $param_fingerprint_encoding, $param_patient_name, $param_gender, $param_date_of_birth, $param_blood_type, $param_nationality, $param_ethnicity, $param_phone);

            // Set parameters
            $param_patient_ic = $patient_ic;
            $param_fingerprint_encoding = password_hash($fingerprint_encoding, PASSWORD_DEFAULT); // Creates a fingerprint encoding hash
            $param_patient_name= $patient_name;
            $param_gender=$gender;
            $param_date_of_birth=$date_of_birth;
            $param_blood_type=$blood_type;
            $param_nationality=$nationality;
            $param_ethnicity=$ethnicity;
            $param_phone=$phone;

            // Attempt to execute the prepared statement
			// mysqli_stmt_execute($stmt);
                // Redirect to login page
            if(mysqli_stmt_execute($stmt)){
                $sql =" UPDATE hardware SET patient_ic = ?, operation = ? WHERE rpi_id = '$temp_rpi_id'";
				if ($stmt = mysqli_prepare($link, $sql)) {
					mysqli_stmt_bind_param($stmt,"ss", $param_patient_ic, $operation);
					$param_patient_ic = $patient_ic;
                    $operation = "rg";
					
					if(mysqli_stmt_execute($stmt)){
						echo "<script type='text/javascript'>
						alert('Please scan your fingerprint to complete the registration process');
						location.href = 'patient_checkin.php';
						</script>";
						
					}
				}
            } else{
                echo "Something went wrong. Please try again later.";
            }

            echo "Something went really wrong. Please try again later.";

            
        }
		
		
		
		mysqli_stmt_close($stmt);
			// header("location: patient_login.php");
    } else {
		echo "wrong";
	}
	
	

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ display: flex; height: 100%; width: 100%; background-color: #89CFF0;}
		.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
        .wrapper{top:20px;
		background-color:white;
		border-radius:15px;
		text-align:center;
	position:relative; padding-top:20px;padding-bottom: 70px; padding-right:70px;padding-left:70px;}
    </style>
</head>
<body>
<div>
<input type="button" id="backButton" value="Back"class="btn btn-primary">
</div>
<div class="container">
<div class="wrapper">
    <h2>Patient Registration</h2>
    <p>Please fill this form to register patient under system.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($patient_name_err)) ? 'has-error' : ''; ?>">
            <label>Patient Full Name: </label>
            <input type="text" name="patient_name" class="form-control" value="<?php echo $patient_name; ?>">
            <span class="help-block"><?php echo $patient_name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($date_of_birth_err)) ? 'has-error' : ''; ?>">
            <label>Date of Birth (dd-mm-yyyy): </label>
            <input type="date" name="date_of_birth" class="form-control" value="<?php echo $date_of_birth; ?>">
            <span class="help-block"><?php echo $date_of_birth_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($patient_ic_err)) ? 'has-error' : ''; ?>">
            <label>Patient IC Number: </label>
            <input type="text" name="patient_ic" class="form-control" value="<?php echo $patient_ic; ?>">
            <span class="help-block"><?php echo $patient_ic_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
            <label>Gender: </label>
            <input type="text" name="gender" class="form-control" value="<?php echo $gender; ?>">
            <span class="help-block"><?php echo $gender_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($blood_type_err)) ? 'has-error' : ''; ?>">
            <label>Blood Type: </label>
            <input type="text" name="blood_type" class="form-control" value="<?php echo $blood_type; ?>">
            <span class="help-block"><?php echo $blood_type_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($nationality_err)) ? 'has-error' : ''; ?>">
            <label>Nationality: </label>
            <input type="text" name="nationality" class="form-control" value="<?php echo $nationality; ?>">
            <span class="help-block"><?php echo $nationality_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($ethnicity_err)) ? 'has-error' : ''; ?>">
            <label>Ethnicity: </label>
            <input type="text" name="ethnicity" class="form-control" value="<?php echo $ethnicity; ?>">
            <span class="help-block"><?php echo $ethnicity_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
            <label>Phone: </label>
            <input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
            <span class="help-block"><?php echo $phone_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit"/>
            <input type="reset" class="btn btn-default" value="Reset">
            
        </div>
		
		<a href="" title="LeftThumb, RightThumb, LeftIndex, RightIndex, LeftMiddle, RightMiddle">Finger Priority</a>	
    </form>
</div>
</div>
<script>
    document.getElementById("backButton").onclick = function () {
        location.href = "patient_checkin.php";
    };
</script>
</body>
</html>