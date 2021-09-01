<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$patient_ic = $encoding = "";
$patient_ic_err = $encoding_err = "";
$temp_rpi_id = $_SESSION["rpi_id"];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if patient_ic is empty
    if (empty(trim($_POST["patient_ic"]))) {
        $patient_ic_err = "Please enter IC Number.";
    } else {
        $patient_ic = trim($_POST["patient_ic"]);
    }

    // If choosing check in option
    if (isset($_POST["check_in"])) {
        // Validate credentials
        if (empty($patient_ic_err)) {
            // Prepare a select statement
            $sql = "SELECT patient_ic, hospital_id FROM patient WHERE patient_ic = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_patient_ic);

                // Set parameters
                $param_patient_ic = $patient_ic;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Store result
                    mysqli_stmt_store_result($stmt);

                    // Verify if patient_ic exists
                    if (mysqli_stmt_num_rows($stmt) == 1) {

                        // workable, but the below one is better and shorter (this is just for back-up)
                        /*
                        $sql = "UPDATE patient SET hospital_id = ? WHERE patient_ic = ?";
                        if($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ss",  $hospital_id,$param_patient_ic);
                            $param_patient_ic = $patient_ic;
                            # $hospital_id = $_SESSION["hospital_id"]; >> we use RPi to get the hospital_id
                            $hospital_id = "0";

                            // for hardware to prompt the input
                            if(mysqli_stmt_execute($stmt)){
                                    $sql =" UPDATE hardware SET patient_ic = ?, operation = ? WHERE rpi_id = '$temp_rpi_id'";
                                    if ($stmt = mysqli_prepare($link, $sql)) {
                                        mysqli_stmt_bind_param($stmt,"ss", $param_patient_ic, $operation);
                                        $param_patient_ic = $patient_ic;
                                        $operation = "ci";

                                        if(mysqli_stmt_execute($stmt)){
                                            // to display the success pop up alert
                                            $message = "wrong answer";
                                            echo "<script type='text/javascript'>
                                            alert('Please scan your fingerprint to complete the check in process'); </script>";
                                        }
                                    }
                            }
                        }
                        */

                        $sql = "UPDATE hardware SET patient_ic = ?, operation = ? WHERE rpi_id = '$temp_rpi_id'";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ss", $param_patient_ic, $operation);
                            $param_patient_ic = $patient_ic;
                            $operation = "ci";

                            if (mysqli_stmt_execute($stmt)) {
                                // to display the success pop up alert
                                $message = "wrong answer";
                                echo "<script type='text/javascript'> 
                            alert('Please scan your fingerprint to complete the check in process'); </script>";
                            }
                        }
                    }

                } else {
                    // Display an error message if patient_ic doesn't exist
                    $patient_ic_err = "No account found with that IC Number.";
                }
            }
        }
    }
}

    // If choosing check out option
    if(isset($_POST["check_out"])){
        // Validate credentials
        if(empty($patient_ic_err)){
            // Prepare a select statement
            $sql = "SELECT patient_ic, hospital_id FROM patient WHERE patient_ic = ?";

            if($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_patient_ic);

                // Set parameters
                $param_patient_ic = $patient_ic;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Store result
                    mysqli_stmt_store_result($stmt);

                    // Verify if patient_ic exists
                    if (mysqli_stmt_num_rows($stmt) == 1) {

                        // workable, but the below one is better and shorter (this is just for back-up)
                        /*
                        $sql = "UPDATE patient SET hospital_id = ? WHERE patient_ic = ?";
                        if($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ss",  $hospital_id,$param_patient_ic);
                            $param_patient_ic = $patient_ic;
                            $hospital_id = "";

                            //make sure the variable is change to session variable
                            if(mysqli_stmt_execute($stmt)){
								$sql =" UPDATE hardware SET patient_ic = ? WHERE 1";
								if ($stmt = mysqli_prepare($link, $sql)) {
									mysqli_stmt_bind_param($stmt,"s", $param_patient_ic);
									$param_patient_ic = $patient_ic;
					
									if(mysqli_stmt_execute($stmt)){
										$message = "wrong answer";
										echo "<script type='text/javascript'>
										window.alert('successfully check out!'); </script>";
									}
								}
							}
                        }
                        */

                        $sql = "UPDATE hardware SET patient_ic = ?, operation = ? WHERE rpi_id = '$temp_rpi_id'";
                        if($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ss",  $param_patient_ic, $operation);
                            $param_patient_ic = $patient_ic;
                            $operation = "co";

                            if(mysqli_stmt_execute($stmt)){
                                // to display the success pop up alert
                                $message = "wrong answer";
                                echo "<script type='text/javascript'> 
                            alert('Please scan your fingerprint to complete the check out process'); </script>";
                            }
                        }
                    }
                    else {
                    // Display an error message if patient_ic doesn't exist
                        $patient_ic_err = "No account found with this IC Number.";


                }

                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }
// Close connection
    mysqli_close($link);

if(isset($_POST["register"])) {
    header("location: patient_register.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {display: flex; height: 100%; width: 100%; background-color: #89CFF0;}
        .wrapper {top: 100px; 
		background-color:white;
		border-radius:5px;
		text-align:center;
		position:absolute; padding-left: 50px;padding-right: 50px; align-items:center;justify-content: center; padding-top: 20px; padding-bottom: 50px;}
		.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
		.logOutButton{
			color: #fff;
			background-color: #337ab7;
			border-color: #2e6da4;
			border-radius:8px;
			text-align:center;
			padding:10px 20px;
			position:absolute;
			text-decoration:none !important;
			left:10vw;
		}
    </style>
</head>
<body>
<a href="logout.php" class="logOutButton">Logout</a>
<div class="container">
<div class="wrapper">
    <h2>Patient Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form method="post">
        <div class="form-group <?php echo (!empty($patient_ic_err)) ? 'has-error' : ''; ?>">
            <label>Patient IC Number: </label>
            <input type="text" name="patient_ic" class="form-control" value="<?php echo $patient_ic; ?>">
            <span class="help-block"><?php echo $patient_ic_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="check_in" class="btn btn-primary" value="Check In">
            <input type="submit" name="check_out" class="btn btn-primary" value="Check Out">
            <input type="submit" name="register" class="btn btn-primary" value="Register">
        </div>
		<br>
		<a href="" title="LeftThumb, RightThumb, LeftIndex, RightIndex, LeftMiddle, RightMiddle">Finger Priority</a>
    </form>
</div>
</body>
</html>

