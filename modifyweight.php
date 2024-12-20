<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Patient Weight</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Modify Patient Weight at DK's Clinic</h1>

    <form action='' method='post'>
        <h2>Enter Patient Details:</h2>
        <label for='modifyohip_input'>OHIP Number:</label>
        <input type='text' name='modifyohip_input' required placeholder="Enter OHIP Number"><br>

        <label for='modifyweight_input'>New Weight:</label>
        <input type='text' name='modifyweight_input' required placeholder="Enter New Weight"><br>
       <select id="measure" name="measure" required>
	 <option value="pounds">Pounds(lbs)</option>
  	<option value="kilograms">Kilograms(kg)</option>
   	</select><br>
 
        <input type='submit' value='Modify Weight'>
    </form>

    <?php
    include 'connectdb.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['modifyweight_input']) && !empty($_POST['modifyohip_input'])) {
        $ohipToUpdate = mysqli_real_escape_string($connection, $_POST['modifyohip_input']);

        $weight = floatval($_POST["modifyweight_input"]);
	$unit = $_POST["measure"];
	$convertedWeight = $weight;
        // Perform the conversion
        if ($unit == "pounds") {
		$convertedWeight = $weight * 0.453592;
	}

        // Check if the OHIP number exists
        $search_query = "SELECT * FROM patient WHERE ohip='$ohipToUpdate'";
        $result = mysqli_query($connection, $search_query);

        if (mysqli_num_rows($result) > 0) {
            // If the OHIP number exists, update the patient's weight
            $query = "UPDATE patient SET weight='$convertedWeight' WHERE ohip='$ohipToUpdate'";
            $updateResult = mysqli_query($connection, $query);

            if (!$updateResult) {
                die('<div class="error">Error with query: ' . mysqli_error($connection) . '</div>');
            }

            echo '<div class="success">Updated weight of patient</div>';
        } else {
            echo '<div class="error">No patient found with that OHIP number</div>';
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo '<div class="error">Please input an OHIP number and weight</div>';
    }

    mysqli_close($connection);
    ?>

    <br>
    <a href="mainmenu.php">
        <button type="button">Go Back to Main Page</button>
    </a>
</body>
</html>
