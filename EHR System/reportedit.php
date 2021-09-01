`<?php require_once "config.php"?>

<?php
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Prepare an insert statement
        $sql = "INSERT INTO lab_report (`patient_ic`, `wbc_count`, `rbc_count`, `hct_test`, `mcv_test`, `mch_test`, `rcdw_test`, `platelet_count`, `mpv`, `polymorphs`, `lymphocytes`, `monocytes`, `eosinophils`, `basophils`, `fT4`, `rheumatoid_factor`, 
		`afp`, `hav`, `ph`, `protein`, `glucose`, `ketone`, `blood`, `colour`, `transparency`, `specific_gravity`, `sodium`, `potassium`, `chloride`, `urea`, `creatinine`, `gfr`, `calcium`, `phosphorus`, `uric_acid`, `syphilis`, `emp_name`, 
		`department`, ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		
		
		
			if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssssssssssssssssss", $patient_ic, $WBCCount, $RBCCount, $HCTTest, $MCVTest, $MCHTest, $RCDWTest, $plateletCount, $MPV, $Polymorphs, $Lymphocytes, $Monocytes, $Eosinophils, $Basophils,
			$FT4, $RheumatoidFactor, $AFP, $HAV, $pH, $Protein, $Glucose, $Ketone, $Blood, $Colour, $Transparency, $SpecificGravity, $Sodium, $Potassium, $Choloride, $Urea, $Creatinine, $GFR, $Calcium, $Phosphorus, $UricAcid, $Syphilis, $emp_name, $department);

            // Set parameters
            $patient_ic = $_SESSION["labIc"];
			$WBCCount = $_POST["WBCCount"];
			$RBCCount = $_POST["RBCCount"];
			$HCTTest = $_POST["HCTTest"];
			$MCVTest = $_POST["MCVTest"];
			$MCHTest = $_POST["MCHTest"];
			$RCDWTest = $_POST["RCDWTest"];
			$plateletCount = $_POST["plateletCount"];
			$MPV = $_POST["MPV"];
			$Polymorphs = $_POST["Polymorphs"];
			$Lymphocytes = $_POST["Lymphocytes"];
			$Monocytes = $_POST["Monocytes"];
			$Eosinophils = $_POST["Eosinophils"];
			$Basophils = $_POST["Basophils"];
			$FT4 = $_POST["FT4"];
			$RheumatoidFactor = $_POST["RheumatoidFactor"];
			$AFP = $_POST["AFP"];
			$HAV = $_POST["HAV"];
			$pH = $_POST["pH"];
			$Protein = $_POST["Protein"];
			$Glucose = $_POST["Glucose"];
			$Ketone = $_POST["Ketone"];
			$Blood = $_POST["Blood"];
			$Colour = $_POST["Colour"];
			$Transparency = $_POST["Transparency"];
			$SpecificGravity = $_POST["SpecificGravity"];
			$Sodium = $_POST["Sodium"];
			$Potassium = $_POST["Potassium"];
			$Choloride = $_POST["Choloride"];
			$Urea = $_POST["Urea"];
			$Creatinine = $_POST["Creatinine"];
			$GFR = $_POST["GFR"];
			$Calcium = $_POST["Calcium"];
			$Phosphorus = $_POST["Phosphorus"];
			$UricAcid = $_POST["UricAcid"];
			$Syphilis = $_POST["Syphilis"];
			$emp_name = $_SESSION["emp_name"];
			$department = $_SESSION["department"];
			
			
			mysqli_stmt_execute($stmt);
			
			
		

            

            // Close statement
            mysqli_stmt_close($stmt);
        
			}
		
	
}
	
	
	
	
	



?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<style>

	.bg-modal{
		width: 100%;
		height: 100%;
		background-color: rgba(0,0,0,0.7);
		position: absolute;
		top:0;
		display: flex;
		justify-content: center;
		align-items: center;
		display:none;
	}
	
	.modal-content{
		width: 50vw; height: 90vh; top: 5vh; background-color: white; text-align: center; padding: 20px; position: relative; height: 80vh; overflow-y:scroll;
		
	}
	
	textarea {resize: none; height: 10em; width: 100%; position: relative;}
	
	.close{
		position:absolute;
		top:0;
		right:14px;
		font-size:42px;
		transform:rotate(45deg);
		cursor:pointer;
	}
</style>

<head>
	<title>reportedit</title>
</head>

<body>
	<input type="button" id="bloodtest" value="bloodtest">
	
	<div class="bg-modal" id="bg-modal">
		<div class="modal-content">
			<div class="close" id="close">+</div>
			
			<form action="reportedit.php" method="POST">
				<label>Haematology</label>
				<p>Full Blood Picture</p>
				<div class="form-group">
					<label>White Blood Cell (WBC) Count</label>
					<input name="WBCCount" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Red Blood Cell (RBC) Count</label>
					<input name="RBCCount" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Hematocrit (Hct) test</label>
					<input name="HCTTest" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Mean corpuscular volume (MCV) test</label>
					<input name="MCVTest" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Mean corpuscular hemoglobin (MCH) test</label>
					<input name="MCHTest" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Red cell distribution width (RCDW) test</label>
					<input name="RCDWTest" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Platelet count</label>
					<input name="plateletCount" class="form-control"required>
				</div>
				<div class="form-group">
					<label>Mean platelet volume (MPV)</label>
					<input name="MPV" class="form-control" required>
				</div>
				<p>White Blood Cell Differential Count</p>
				<div class="form-group">
					<label>Polymorphs %</label>
					<input name="Polymorphs" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Lymphocytes %</label>
					<input name="Lymphocytes" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Monocytes %</label>
					<input name="Monocytes" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Eosinophils %</label>
					<input name="Eosinophils" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Basophils %</label>
					<input name="Basophils" class="form-control" required>
				</div>
				
				<label>Immunology</label>
				<div class="form-group">
					<label>Free T4 (Free Thyroxine)</label>
					<input name="FT4" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Rheumatoid Factor</label>
					<input name="RheumatoidFactor" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Alpha Fetoprotein (AFP)</label>
					<input name="AFP" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Hepatitis A Virus (HAV)</label>
					<input name="HAV" class="form-control" required>
				</div>
				
				<label>Urinalysis</label>
				<p>Urinalysis Strip</p>
				<div class="form-group">
					<label>pH</label>
					<input name="pH" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Protein</label>
					<input name="Protein" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Glucose</label>
					<input name="Glucose" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Ketone</label>
					<input name="Ketone" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Blood</label>
					<input name="Blood" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Colour</label>
					<input name="Colour" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Transparency</label>
					<input name="Transparency" class="form-control"required>
				</div>
				<div class="form-group">
					<label>Specific Gravity</label>
					<input name="SpecificGravity" class="form-control" required>
				</div>
				
				
				<label>Biochemistry</label>
					<div class="form-group">
					<label>Sodium</label>
					<input name="Sodium" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Potassium</label>
					<input name="Potassium" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Choloride</label>
					<input name="Choloride" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Urea</label>
					<input name="Urea" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Creatinine</label>
					<input name="Creatinine" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Glomerular Filtration Rate</label>
					<input name="GFR" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Calcium</label>
					<input name="Calcium" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Phosphorus</label>
					<input name="Phosphorus" class="form-control" required>
				</div>
				<div class="form-group">
					<label>Uric Acid</label>
					<input name="UricAcid" class="form-control" required>
				</div>
				
				<label>Serology</label>
				<div class="form-group">
					<label>Syphilis Serology</label>
					<input name="Syphilis" class="form-control" required>
				</div>
				
				
				<div>
				<input type="submit" name="addReport" value="Add Report">
				</div>
			</form>
			
			
		</div>
	</div>
</body>


</html>

<script>
	document.getElementById('bloodtest').addEventListener('click',()=>{
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