<?php

include 'connectdb.php';

$query = 'SELECT * FROM nurse';
$result = mysqli_query($connection, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' .  htmlspecialchars($row['nurseid']) . '">' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) . '</option>';
    }
} else {
    echo '<option value="">No nurses available</option>';
}
?>

