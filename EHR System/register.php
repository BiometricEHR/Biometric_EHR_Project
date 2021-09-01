<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$emp_name = $password = $confirm_password = $emp_role = $department = $hospital_id = $emp_ic = "";
$emp_name_err = $password_err = $confirm_password_err = $emp_role_err = $department_err = $hospital_id_err = $emp_ic_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate employee name
    if(empty(trim($_POST["emp_name"]))){
        $emp_name_err = "Please enter your full name.";
    } else{
        $emp_name = $_POST["emp_name"];
    }

    // Validate employee i/c (access key)
    if(empty(trim($_POST["emp_ic"]))){
        $emp_ic_err = "Please enter your I/C number.";
    }
    else if(!is_numeric(trim($_POST["emp_ic"]))){
        $emp_ic_err = "Do not include '-' or non-numeric input";
    }
    else if(strlen(trim($_POST["emp_ic"])) !== 12){
        $emp_ic_err = "Password must have 12 numbers.";
    }
    else{
        // Prepare a select statement
        $sql = "SELECT emp_ic FROM employee WHERE emp_ic = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_emp_ic);
            // Set parameters
            $param_emp_ic = trim($_POST["emp_ic"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $emp_ic_err = "This I/C number is registered.";
                } else{
                    $emp_ic = trim($_POST["emp_ic"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } else if(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate employment role //////////////////////////////////////////can be removed due to preselect
    if(empty(trim($_POST["emp_role"]))){
        $emp_role_err = "Please select one role.";
    } else{
        $emp_role = $_POST["emp_role"];
    }

    // Validate department
    if(empty(trim($_POST["department"]))){
        $department_err = "Please enter your department.";
    } else{
        $department = $_POST["department"];
    }

    // Validate hospital ID
    if(empty(trim($_POST["hospital_id"]))){
        $hospital_id_err = "Please enter your company registration ID.";
    } else if(strlen(trim($_POST["hospital_id"])) !== 8){
        $hospital_id_err = "Company registration ID must be 8 characters.";
    } else{
        $hospital_id = trim($_POST["hospital_id"]);
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    // Check input errors before inserting in database
    if(empty($emp_name_err) && empty($emp_ic_err) && empty($password_err) && empty($confirm_password_err) &&
        empty($emp_role_err) && empty($department_err)&& empty($hospital_id_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO employee (emp_name, emp_ic, password, emp_role, department, hospital_id) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $emp_name, $param_emp_ic, $param_password, $emp_role, $department, $hospital_id);

            // Set parameters
            $param_emp_ic = $emp_ic;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{display: flex; height: 100%; width: 100%; background-color: #89CFF0;}
        
		.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
	.wrapper {
		top:50px; 
		background-color:white;
		border-radius:15px;
		text-align:center;
	position:relative; padding-top:40px;padding-bottom: 50px; padding-right:70px;padding-left:70px;}
    </style>
</head>
<body>
<div class="container">
<div class="wrapper">
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($emp_name_err)) ? 'has-error' : ''; ?>">
            <label>Full Name</label>
            <input type="text" name="emp_name" class="form-control" value="<?php echo $emp_name; ?>">
            <span class="help-block"><?php echo $emp_name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($emp_ic_err)) ? 'has-error' : ''; ?>">
            <label>I/C Number</label>
            <input type="text" name="emp_ic" class="form-control" value="<?php echo $emp_ic; ?>" placeholder="e.g.991122334455">
            <span class="help-block"><?php echo $emp_ic_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($emp_role_err)) ? 'has-error' : ''; ?>">
            <label>Employment Role</label><br>
            <input type="radio" name="emp_role" value="receptionist" checked="checked">Receptionist
            <input type="radio" name="emp_role" value="doctor">Doctor
            <input type="radio" name="emp_role" value="professional">Professional
            <span class="help-block"><?php echo $emp_role_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($department_err)) ? 'has-error' : ''; ?>">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="<?php echo $department; ?>" placeholder="e.g.Cardiology">
            <span class="help-block"><?php echo $department_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($hospital_id_err)) ? 'has-error' : ''; ?>">
            <label>Company Registration ID</label>
            <input type="text" name="hospital_id" class="form-control" value="<?php echo $hospital_id; ?>" placeholder="e.g.0000000A">
            <span class="help-block"><?php echo $hospital_id_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>
</div>
</body>
</html>