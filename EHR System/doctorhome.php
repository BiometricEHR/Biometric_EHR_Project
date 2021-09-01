<?php require_once "config.php";?>

<?php
session_start();

$patient_ic = "";
$patient_ic_err = "";
$login_patient_ic = "";
$login_patient_ic_err = "";

if(isset($_POST["registerpatient"])){

	
	//validate patient ic
	if(empty(trim($_POST["patient_ic"]))){
        $patient_ic_err = "Please enter patient IC number.";
    } elseif(strlen(trim($_POST["patient_ic"]))!= 12){
		$patient_ic_err = "Invalid IC number.";
	}else{
		
		$patient_ic = trim($_POST["patient_ic"]);
	}
		
	if(empty($patient_ic_err)){
        // Prepare a select statement
        $sql = "SELECT patient_ic FROM doctor_patient WHERE patient_ic = ?";
		
		if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_patient_ic);

            // Set parameters
            $param_patient_ic = trim($_POST["patient_ic"]);
			
			  // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $patient_ic_err = "This username is already taken.";
                } else{
                    $patient_ic = trim($_POST["patient_ic"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
			
			
		}
		
		
		
		
		// Check input errors before inserting in database
		if(empty($patient_ic_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO doctor_patient (patient_ic, emp_ic) VALUES (?, ?)";
		
		
		
			if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_patient_ic, $param_emp_ic);

            // Set parameters
            $param_patient_ic = $patient_ic;
			$param_emp_ic = $_SESSION["emp_ic"];
			
			mysqli_stmt_execute($stmt);
			
            // Close statement
            mysqli_stmt_close($stmt);
			
			header("location:doctorhome.php");
        
			}
		}	
	}
	
	


}

if(isset($_POST["patientbutton"])){
//fot patient list part
	if(empty(trim($_POST["login_patient_ic"]))){
        $login_patient_ic_err = "Please enter patient IC number.";
    } else{
		$login_patient_ic = trim($_POST["login_patient_ic"]);
	}
		
	if(empty($login_patient_ic_err)){
		//check whether the patient is check in
		$sql = "SELECT hospital_id FROM patient WHERE patient_ic = ?";
		
		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $param_patient_ic);
			$param_patient_ic = trim($_POST["login_patient_ic"]);
			
			if(mysqli_stmt_execute($stmt)){	
				mysqli_stmt_store_result($stmt);
				mysqli_stmt_bind_result($stmt, $hospital_id);
				if (mysqli_stmt_fetch($stmt)){
					$hospital_id = $hospital_id;
				

					if(strlen($hospital_id)>0){
						// Prepare a select statement
						$sql = "SELECT patient_ic FROM doctor_patient WHERE patient_ic = ?";
						
						if($stmt = mysqli_prepare($link, $sql)){
							// Bind variables to the prepared statement as parameters
							mysqli_stmt_bind_param($stmt, "s", $param_patient_ic);

							// Set parameters
							$param_patient_ic = trim($_POST["login_patient_ic"]);
							
							  // Attempt to execute the prepared statement
							if(mysqli_stmt_execute($stmt)){
								/* store result */
								mysqli_stmt_store_result($stmt);

								if(mysqli_stmt_num_rows($stmt) == 1){
									$_SESSION["patient_ic"] = $_POST["login_patient_ic"];
									
									header ("location: patient_portal.php");
								} 
							} else{
								
							}
								// Close statement
							mysqli_stmt_close($stmt);
							
						}
					
					} else{
						// Display an error message if username doesn't exist
						$login_patient_ic_err = "No account found with that username.";
					}
				}
			}
		}
	}
	

}
?>

<?php 


?>

<!DOCTYPE html>
<html>


<head>
	<title>doctor homepage</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	
	<style type= "text/css">
	body{display: flex; height: 100%; width: 100%; background-color: #89CFF0;}
	
	.bg-modal{
		width: 100%;
		height: 100%;
		background-color: rgba(0,0,0,0.7);
		position: absolute;
		top:0;
		justify-content: center;
		align-items: center;
		display:none;
	}
	
	.modal-content{
		width:500px;
		height:300px;
		background-color:white;
		border-radius:4px;
		text-align:center;
		padding:20px;
		position:relative;
		
	}
	
	.close{
		
		transform:rotate(45deg);
		cursor:pointer;
	}
	.form-control2 p {
		
		visibility:hidden;
	}
	
	.form-control2.error p {
		visibility:visible;
	}
	
	
	.container{display:flex; background-color: rgba(0,0,0,0);align-items:center;justify-content: center;}
	.wrapper {
		top:100px;
		background-color:white;
		border-radius:15px;
		text-align:center;
	position:relative; padding-top:40px;padding-bottom: 70px; padding-right:70px;padding-left:70px;}
	
	.registerButton{
		background-color: #4CAF50;
			border: none;
			outline:none;
			border-radius:15px;
			color: white;
			width:150px;
			padding: 15px ;
			text-align: center;
			text-decoration: none !important;
			display: inline-block;
			font-size: 20px;
			margin: 2px;
			cursor: pointer;
			position:relative;
			
	}
	
	.patientListButton{
		background-color: #4CAF50;
			border: none;
			outline:none;
			border-radius:15px;
			color: white;
			width:150px;
			padding: 15px ;
			text-align: center;
			text-decoration: none !important;
			display: inline-block;
			font-size: 20px;
			margin: 2px;
			cursor: pointer;
			position:relative;
			top: 10px;		
	}
	
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

<body >
	<a href="logout.php" class="logOutButton">Logout</a>
	<div class="container">
	<div class="wrapper">
	<form action=""method="post" class="">
		<h2>Doctor Homepage</h2>
        <div class="form-group <?php echo (!empty($patient_ic_err)) ? 'has-error' : ''; ?>">
            <label>Patient IC:</label>
            <input type="text" name="patient_ic" class="form-control" value="<?php echo $patient_ic; ?>">
            <span class="help-block"><?php echo $patient_ic_err; ?></span>
        </div>
		<div class="form-group">
            <button id="registerpatient" name="registerpatient" class="registerButton">Register</button>
        </div>
		<div>
			<button type= "button" id= "button" class="patientListButton">Patient List</button>
		</div>
		
    </form>
	</div>
	</div>
	<div class= "bg-modal" id=bg-modal>
		<div class= "modal-content">
			<div class="close" id="close">+</div>
			
			<form method="post" id="patientform" class="form-control2">
			
				<div class="">
				<label>Patient IC:</label>
				<input type="text" name="login_patient_ic" id="login_patient_ic" placeholder= "eg. 991220015483" required>
				<p>Error Message</p>
				</div>
				
				<div class="form-group"><input type="submit" name="patientbutton"></div>
			</form>
			
			<script>
			const login_patient_ic = document.getElementById('login_patient_ic');
			const patientform = document.getElementById('patientform');
	
			//Event listerners
			patientform.addEventListener('submit', (e)=>{
				errorchecking="";
				
				checkInputs();
				
				if(errorchecking === ''){
				e.preventDefault();
				}
			})
			
			function checkInputs(){
				//get the values from the inputs
				const loginpatienticValue = login_patient_ic.value.trim();
				
				
				if(loginpatienticValue.length != 12){
					//show error 
					//add arror class
					setErrorFor(login_patient_ic, 'Invalid IC number');
					
				}else{
					//add success class
					setSuccessFor(login_patient_ic);
					errorchecking="no error";
				}
			}
			
			function setErrorFor(input,message){
				const formControl2 = input.parentElement;
				const p = formControl2.querySelector('p');
				
				//add error message inside p
				p.innerText = message;
				
				//add error class
				formControl2.className = "form-control2 error";
			}
			
			function setSuccessFor(input){
				const formControl2 = input.parentElement;
				formControl2.className = 'form-control2 success';
			}

			</script>
		</div>
	</div>
	
	
	
</body>
</html>


<script>
	document.getElementById('button').addEventListener('click',()=>{
		document.querySelector('.bg-modal').style.display = 'flex';
	});
		
	document.getElementById('close').addEventListener('click',()=>{
		document.querySelector('.bg-modal').style.display = 'none';
	});
	
	//click anywhere outside the modal to close	
	var modal = document.getElementById("bg-modal");
	window.onclick = function(event) {
		if (event.target == modal) {
		modal.style.display = "none";}
	}
		
	
		
		
</script>

















