<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors with No Patients</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Doctors with No Patients at DK's Clinic</h1>

    <?php
    include 'connectdb.php';

    // Query to get doctors with no patients
    $query = 'SELECT firstname, lastname FROM doctor WHERE docid NOT IN (SELECT treatsdocid FROM patient)';
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('<div class="error">Error with query: ' . mysqli_error($connection) . '</div>');
    }

    // Display the list of doctors with no patients
    echo "<h2>Doctors with No Patients:</h2><ol>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) . '</li>';
        }
    } else {
        echo '<li>No doctors found with no patients.</li>';
    }
    echo '</ol>';

    mysqli_close($connection);
    ?>

    <br>
    <a href="mainmenu.php">
        <button type="button">Go Back to Main Page</button>
    </a>
</body>
</html>
