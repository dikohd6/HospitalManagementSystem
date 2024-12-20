<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DK's Clinic</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to the CSS file -->
</head>
<body>

<h1>Welcome to DK's Clinic</h1>

<?php
include 'connectdb.php';

// Form to input order criteria if not already submitted
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['order_by']) || !isset($_POST['display_ord'])) {
    // Display the form for ordering patients
    echo "
    <form action='getpatients.php' method='post'>
        <h2>List all the patients:</h2>
        <h3>Choose to order by first name or last name</h3>
        <input type='radio' id='fname' name='order_by' value='firstname'>First Name<br>
        <input type='radio' id='lname' name='order_by' value='lastname'>Last Name<br>
        <h3>Choose to display by ascending or descending order</h3>
        <input type='radio' id='display_asc' name='display_ord' value='ASC'>Ascending<br>
        <input type='radio' id='display_desc' name='display_ord' value='DESC'>Descending<br>
        <input type='submit' value='Get Patients'>
    </form>
    ";
    exit();
}

// Handle the form submission and display the data
if (isset($_POST['order_by']) && isset($_POST['display_ord'])) {
    // Sanitize and prepare the order_by and display_ord values
    $order_by = 'patient.' . mysqli_real_escape_string($connection, $_POST['order_by']);
    $display_ord = mysqli_real_escape_string($connection, $_POST['display_ord']);

    // Construct the query
    $query = "SELECT patient.*, doctor.firstname AS doctor_firstname, doctor.lastname AS doctor_lastname
              FROM patient
              JOIN doctor ON doctor.docid = patient.treatsdocid
              ORDER BY $order_by $display_ord";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check for query errors
    if (!$result) {
        die("Database query failed: " . mysqli_error($connection)); // Show the actual error
    }

    // Display the patients in an ordered list
    echo "<ol>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>
                <h4>" . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']) . "</h4>
                <p>OHIP Number: " . htmlspecialchars($row['ohip']) . "<br>
                Weight in Metric: " . htmlspecialchars($row['weight']) . " KG<br>
                Weight in Pounds: " . round((float) htmlspecialchars($row['weight']) * 2.20462, 2) . " lbs<br>
                Height in Metric: " . htmlspecialchars($row['height']) . " m<br>
                Height in Imperial: " . floor((float) htmlspecialchars($row['height']) * 39.3701 / 12) . " ft " . round((float) htmlspecialchars($row['height']) * 39.3701 % 12, 2) . " in<br>
                Doctor: " . htmlspecialchars($row['doctor_firstname']) . " " . htmlspecialchars($row['doctor_lastname']) . "</p>
              </li>";
    }
    echo "</ol>";

    // Close the database connection
    mysqli_close($connection);
} else {
    echo '<h3>Please select an option</h3>';
    die();
}
?>

<!-- Button to go back to the main menu -->
<form action="mainmenu.php" method="post">
    <input type="submit" value="Back to Main Menu" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 20px; transition: background-color 0.3s ease;">
</form>

<footer>
    &copy; 2024 DK's Clinic. All rights reserved.
</footer>

</body>
</html>
