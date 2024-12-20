<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Patient</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Delete Patient from DK's Clinic</h1>
    
    <form action='' method='post' onsubmit="return confirmDelete();">
        <h2>Enter OHIP Number:</h2>
        <input type='text' name='ohipdelete_input' required placeholder="Enter OHIP Number"><br>
        <input type='submit' value='Delete Patient'>
    </form>

    <?php
    include 'connectdb.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ohipdelete_input'])) {
        $ohipToDelete = mysqli_real_escape_string($connection, $_POST['ohipdelete_input']);

        // Check if the OHIP number exists
        $search_query = "SELECT * FROM patient WHERE ohip='$ohipToDelete'";
        $result = mysqli_query($connection, $search_query);

        if (mysqli_num_rows($result) > 0) {
            // If the OHIP number exists, delete the patient
            $query = "DELETE FROM patient WHERE ohip='$ohipToDelete'";
            $deleteResult = mysqli_query($connection, $query);

            if (!$deleteResult) {
                die('<div class="error">Error with query: ' . mysqli_error($connection) . '</div>');
            }

            echo '<div class="success">Deleted patient from database</div>';
        } else {
            echo '<div class="error">No patient found with that OHIP number</div>';
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo '<div class="error">Please input an OHIP number</div>';
    }

    mysqli_close($connection);
    ?>

    <br>
    <a href="mainmenu.php">
        <button type="button">Go Back to Main Page</button>
    </a>

    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this patient?");
    }
    </script>
</body>
</html>

