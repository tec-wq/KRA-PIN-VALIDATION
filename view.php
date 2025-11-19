<?php
$file = 'kra_data.txt';
if (!file_exists($file) || filesize($file) == 0) {
    echo "<h2>Saved KRA Records</h2>";
    echo "<p>No records found.</p>";
    echo '<p><a href="index.php">Register New User</a></p>';
    exit; 
}
$records = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$total = count($records);
$letter = isset($_GET['letter']) ? strtoupper($_GET['letter']) : 'A';
$countByLetter = 0;
foreach ($records as $record) {
    $parts = explode(",", $record);
    if (count($parts) === 4) { // Check for valid data
        list($fname, $lname, $email, $pin) = $parts;      
        // Check the first letter of the PIN
        
        if (!empty($pin) && strtoupper($pin[0]) === $letter) {
            $countByLetter++;
        }
    }
}
$freq = array_fill_keys(range('A', 'Z'), 0);
foreach ($records as $record) {
    $parts = explode(",", $record);
    if (count($parts) === 4) { // 
        list($fname, $lname, $email, $pin) = $parts;        
        if (!empty($pin)) {
            $first = strtoupper($pin[0]);
            if (isset($freq[$first])) {
                $freq[$first]++;
            }
        }
    }
}
arsort($freq);
$mostCommonLetter = key($freq); // Get the letter (the array key)
$mostCommonCount = current($freq); // Get the count (the array value)
?>

<!DOCTYPE html>
<html>
<head>
    <title>KRA Records</title>
</head>
<body>

    <h2>Saved KRA Records</h2>

    <p><a href="index.php">Register New User</a></p>

    <hr>

    <h3>Statistics</h3>

    <p><strong>Total submissions:</strong> <?php echo $total; ?></p>

    <form method="GET" action="view.php">
        <label for="letter">Check how many entries start with letter:</label>
        <input type="text" id="letter" name="letter" maxlength="1" size="2" required value="<?php echo htmlspecialchars($letter); ?>">
        <button type="submit">Check</button>
    </form>

    <p>
        Entries starting with <strong><?php echo htmlspecialchars($letter); ?></strong>: 
        <strong><?php echo $countByLetter; ?></strong>
    </p>

    <h4>Most Common Starting Letter</h4>
    <p>
        <strong><?php echo $mostCommonLetter; ?></strong> appears 
        <strong><?php echo $mostCommonCount; ?></strong> times.
    </p>

    <hr>

    <h3>All Records</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>KRA PIN</th>
        </tr>

        <?php
        // Loop through the records again to display them in a table
        foreach ($records as $record) {
            // *** FIX 3: Explode by comma "," and expect 4 fields ***
            $parts = explode(",", $record);
            if (count($parts) === 4) {
                list($fname, $lname, $email, $pin) = $parts;
                
                // *** FIX 4: Combine first and last name for display ***
                echo "<tr>
                        <td>" . htmlspecialchars($fname) . " " . htmlspecialchars($lname) . "</td>
                        <td>" . htmlspecialchars($email) . "</td>
                        <td>" . htmlspecialchars($pin) . "</td>
                      </tr>";
            }
        }
        ?>
    </table>
</body>
</html>