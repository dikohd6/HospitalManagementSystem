<?php
include 'connectdb.php'; // Ensure the database connection is established

$query = 'SELECT * FROM doctor';
$result = mysqli_query($connection, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . htmlspecialchars($row['docid']) . '">' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) . '</option>';
    }
} else {
    echo '<option value="">No doctors available</option>';
}
?>
