<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
// Ensure user not logging out without pressing "log out" button or by clicking "back" button
if(isset($_SESSION["emp_role"])){
    // Redirect user to home page
    if ($_SESSION["emp_role"] == 'receptionist'){
        header("location: patient_checkin.php");
    }
    else if ($_SESSION["emp_role"] == 'doctor'){
        header("location: doctorhome.php");
    }
    else if ($_SESSION["emp_role"] == 'professional'){
        header("location: department.php");
    }
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$emp_ic = $password = "";
$emp_ic_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["emp_ic"]))){
        $emp_ic_err = "Please enter your I/C number.";
    } else{
        $emp_ic = trim($_POST["emp_ic"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($emp_ic_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT emp_ic, emp_role, hospital_id, password, emp_name, department, rpi_id FROM employee WHERE emp_ic = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_emp_ic);

            // Set parameters
            $param_emp_ic = $emp_ic;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $emp_ic, $emp_role, $hospital_id, $hashed_password, $emp_name, $department, $rpi_id);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["emp_ic"] = $emp_ic;
                            $_SESSION["hospital_id"] = $hospital_id;
                            $_SESSION["emp_role"] = $emp_role;
							$_SESSION["emp_name"] = $emp_name;
							$_SESSION["department"] = $department;
							$_SESSION["rpi_id"] = $rpi_id;

                            // Redirect user to home page
                            if ($emp_role == 'receptionist'){
                                header("location: patient_checkin.php");
                            }
                            else if ($emp_role == 'doctor'){
                                header("location: doctorhome.php");
                            }
                            else if ($emp_role == 'professional'){
                                header("location: department	.php");
                            }
                            else {
                                echo "Oops, unable to direct to home page.";
                            }

                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $emp_ic_err = "No account found with that I/C number.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{display: flex; height: 100%; width: 100%; background-color: #89CFF0;}
        
		.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
	.wrapper {
		top:100px;
		background-color:white;
		border-radius:15px;
		text-align:center;
	position:relative; padding-top:40px;padding-bottom: 70px; padding-right:70px;padding-left:70px;}
    </style>
</head>
<body>
<div class="container">
<div class="wrapper">
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($emp_ic_err)) ? 'has-error' : ''; ?>">
            <label>Employee I/C Number</label>
            <input type="text" name="emp_ic" class="form-control" value="<?php echo $emp_ic; ?>">
            <span class="help-block"><?php echo $emp_ic_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php"  title="LeftThumb, RightThumb, LeftIndex, RightIndex">Sign up now</a>.</p>
		
    </form>
</div>
</body>
</html>
