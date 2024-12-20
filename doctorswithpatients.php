<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors with Patients</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Doctors and Their Patients at DK's Clinic</h1>

    <?php
    include 'connectdb.php';

    // Query to get all doctors and their corresponding patients
    $query = '
        SELECT doctor.firstname AS doctor_firstname, doctor.lastname AS doctor_lastname,
               patient.firstname AS patient_firstname, patient.lastname AS patient_lastname
        FROM doctor
        LEFT JOIN patient ON doctor.docid = patient.treatsdocid
        ORDER BY doctor.firstname, doctor.lastname
    ';

    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if (!$result) {
        die('<div class="error">Query failed: ' . mysqli_error($connection) . '</div>');
    }

    // Initialize variable to keep track of the current doctor
    $currentDoctorFullName = '';

    // Fetch and display results
    while ($row = mysqli_fetch_assoc($result)) {
        $doctorFullName = htmlspecialchars($row['doctor_firstname'] . ' ' . $row['doctor_lastname']);

        // Check if we're still on the same doctor
        if ($currentDoctorFullName !== $doctorFullName) {
            // Close the previous doctor's list if it exists
            if ($currentDoctorFullName !== '') {
                echo "</ul>";
            }

            // Update the current doctor
            $currentDoctorFullName = $doctorFullName;
            echo "<h3>Doctor: $doctorFullName</h3>";
            echo "<ul>"; // Start a new list for the patients
        }

        // Output the patient's name, if exists
        if (!is_null($row['patient_firstname']) && !is_null($row['patient_lastname'])) {
            echo "<li>" . htmlspecialchars($row['patient_firstname'] . ' ' . $row['patient_lastname']) . "</li>";
        }
    }

    // Close the last doctor's list if it exists
    if ($currentDoctorFullName !== '') {
        echo "</ul>";
    }

    // Close the database connection
    mysqli_close($connection);
    ?>

    <br>
    <a href="mainmenu.php">
        <button type="button">Go Back to Main Page</button>
    </a>
</body>
</html>
