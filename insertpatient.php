<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register Patient - DK's Clinic</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to the CSS file -->
</head>
<body>

<h1>Welcome to DK's Clinic</h1>

<?php
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && 
    !empty($_POST['firstname_input']) && 
    !empty($_POST['lastname_input']) && 
    !empty($_POST['ohip_input']) && 
    !empty($_POST['birthday_input']) && 
    !empty($_POST['doctor_input']) && 
    !empty($_POST['weight_input']) && 
    !empty($_POST['height_input'])) {
    
    // Check if OHIP number already exists
    $search_query = 'SELECT * FROM patient WHERE ohip = "' . mysqli_real_escape_string($connection, $_POST["ohip_input"]) . '"';
    $result = mysqli_query($connection, $search_query);
    if (mysqli_num_rows($result) > 0) {
        die('OHIP number already taken');
    }

    // Insert new patient
    $query = "INSERT INTO patient (ohip, firstname, lastname, weight, birthdate, height, treatsdocid) VALUES (
        '" . mysqli_real_escape_string($connection, $_POST["ohip_input"]) . "',
        '" . mysqli_real_escape_string($connection, $_POST["firstname_input"]) . "',
        '" . mysqli_real_escape_string($connection, $_POST["lastname_input"]) . "',
        '" . mysqli_real_escape_string($connection, $_POST["weight_input"]) . "',
        '" . mysqli_real_escape_string($connection, $_POST["birthday_input"]) . "',
        '" . mysqli_real_escape_string($connection, $_POST["height_input"]) . "',
        '" . mysqli_real_escape_string($connection, $_POST["doctor_input"]) . "'
    )";

    $result = mysqli_query($connection, $query);
    if (!$result) {
        die('Error with query: ' . mysqli_error($connection));
    }
    
    echo '<p class="success">Patient ' . htmlspecialchars($_POST["firstname_input"]) . ' added to the database.</p>';
}
?>

<form action="insertpatient.php" method="post">
    <h3>First Name</h3>
    <input type="text" name="firstname_input" required><br>
    <h3>Last Name</h3>
    <input type="text" name="lastname_input" required><br>
    <h3>OHIP Number</h3>
    <input type="text" name="ohip_input" required><br>
    <h3>Birthdate (YYYY-MM-DD)</h3>
    <input type="text" name="birthday_input" required><br>
    <h3>Weight in Kilograms</h3>
    <input type="text" name="weight_input" required><br>
    <h3>Height in Metres</h3>
    <input type="text" name="height_input" required><br>

    <h3>Choose a Doctor</h3>
    <select id="doctors" name="doctor_input" required>
        <?php
        include 'doctorslist.php'; // Assuming this file outputs the <option> tags
        ?>
    </select><br>
    <input type="submit" value="Register Patient">
</form>

<!-- Button to go back to the main menu -->
<form action="mainmenu.php" method="post">
    <input type="submit" value="Back to Main Menu" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 20px; transition: background-color 0.3s ease;">
</form>

<footer>
    &copy; 2024 DK's Clinic. All rights reserved.
</footer>

</body>
</html>
