<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Nurse and Doctor Work Details</h1>

    <form method="POST" action="">
        <label for="nurse_input">Select Nurse:</label>
        <select id="nurse_input" name="nurse_input" required>
            <option value="">--Select a Nurse--</option>
            <?php
            include 'connectdb.php';

            // Fetch the list of nurses for the dropdown
            $nurseQuery = "SELECT nurseid, firstname, lastname FROM nurse";
            $nurseResult = mysqli_query($connection, $nurseQuery);

            if ($nurseResult && mysqli_num_rows($nurseResult) > 0) {
                while ($nurseRow = mysqli_fetch_assoc($nurseResult)) {
                    $nurseFullName = htmlspecialchars($nurseRow['firstname'] . ' ' . $nurseRow['lastname']);
                    echo '<option value="' . $nurseRow['nurseid'] . '">' . $nurseFullName . '</option>';
                }
            } else {
                echo '<option value="">No nurses available</option>';
            }
            ?>
        </select>
        <button type="submit">Submit</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nurse_input'])) {
        // Sanitize the input to prevent SQL injection
        $nurse_input = mysqli_real_escape_string($connection, $_POST['nurse_input']);

        $query = "
            SELECT
                a1.firstname,
                a1.lastname,
                a1.nurseid,
                a2.firstname AS supervisor_firstname,
                a2.lastname AS supervisor_lastname,
                dr.firstname AS doc_firstname,
                dr.lastname AS doc_lastname,
                wf.hours
            FROM
                nurse a1
            LEFT JOIN
                nurse a2 ON a1.nurseid = a2.nurseid
            LEFT JOIN
                workingfor wf ON wf.nurseid = a1.nurseid
            LEFT JOIN
                doctor dr ON dr.docid = wf.docid
            WHERE
                a1.nurseid = '$nurse_input';
        ";

        // Debugging: Output the query to check if it's correct
        // Uncomment the line below to see the generated query
        // echo "<pre>$query</pre>";

        $total_hours = 0;
        $super_name = ''; // Initialize variable for the supervisor's name
        $nurse_name = ''; // Initialize variable for the nurse's name

        $result = mysqli_query($connection, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $first_iteration = true; // Flag to check if it's the first loop iteration

                while ($row = mysqli_fetch_assoc($result)) {
                    if ($first_iteration) {
                        // Set the nurse's name and supervisor's name on the first iteration and print them
                        $nurse_name = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
                        $super_name = htmlspecialchars($row['supervisor_firstname'] . ' ' . $row['supervisor_lastname']);

                        // Display nurse and supervisor names
                        echo '<div class="nurse-info">';
                        echo '<h2>Nurse: ' . $nurse_name . '</h2>';
                        echo '<h3>Supervisor: ' . $super_name . '</h3>';
                        echo '</div>';

                        $first_iteration = false; // Reset the flag after the first iteration
                    }

                    // Display the doctor and hours worked
                    if (!empty($row['doc_firstname']) && !empty($row['doc_lastname'])) {
                        $doc_name = htmlspecialchars($row['doc_firstname'] . ' ' . $row['doc_lastname']);
                        $total_hours += $row['hours'];

                        // Display the detailed data
                        echo '<div class="doctor-info">';
                        echo '<p>Doctor: ' . $doc_name . '</p>';
                        echo '<p>Hours: ' . htmlspecialchars($row['hours']) . '</p>';
                        echo '</div>';
                    }
                }

                // Print the total hours at the end
                echo '<div class="total-hours">';
                echo '<h3>Total Hours Worked: ' . htmlspecialchars($total_hours) . '</h3>';
                echo '</div>';
            } else {
                echo '<div class="error">No data available for the selected nurse.</div>';
            }
        } else {
            echo '<div class="error">Query failed: ' . mysqli_error($connection) . '</div>';
        }

        // Close the connection if needed
        mysqli_close($connection);
    }
    ?>

    <br>
    <a href="mainmenu.php">
        <button type="button">Go Back to Main Page</button>
    </a>
</body>
</html>
