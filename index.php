<?php
$success_message = "";
$error_messages = []; // An array to store all error messages
$first_name = "";
$last_name = "";
$email = "";
$kra_pin = "";

$data_file = "kra_data.txt";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $kra_pin = trim($_POST['kra_pin']);


    // Rule 1: Check if fields are empty
    if (empty($first_name)) {
        $error_messages[] = "First Name is required.";
    }
    if (empty($last_name)) {
        $error_messages[] = "Last Name is required.";
    }
    if (empty($email)) {
        $error_messages[] = "Email is required.";
    }
    if (empty($kra_pin)) {
        $error_messages[] = "KRA PIN is required.";
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Email format is incorrect.";
    }

    $kra_pin_regex = "/^[A-Z][0-9]{9}[A-Z]$/";

    if (!empty($kra_pin) && !preg_match($kra_pin_regex, $kra_pin)) {
        $error_messages[] = "KRA PIN format is incorrect. It must be 1 letter, 9 digits, 1 letter (e.g., A123456789B).";
    }

    // 3. SAVE THE DATA (if there are no errors)
    if (empty($error_messages)) {
        
        $data_to_save = "$first_name,$last_name,$email,$kra_pin\n";


        if (file_put_contents($data_file, $data_to_save, FILE_APPEND | LOCK_EX)) {
            $success_message = "Success! Your registration has been saved.";
            
            // Clear the form fields after successful submission
            $first_name = "";
            $last_name = "";
            $email = "";
            $kra_pin = "";

        } else {
            $error_messages[] = "Error: Could not save data to the file. Please check file permissions.";
        }
    }

} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRA Registration</title>
</head>
<body>

    <h2>KRA PIN Registration Form</h2>
    <p>Please enter your details below.</p>

    <?php
    // --- DISPLAY MESSAGES ---

    // If there are any errors, display them
    if (!empty($error_messages)) {
        echo '<div>';
        echo '<strong>Please fix the following errors:</strong>';
        echo '<ul>';
        foreach ($error_messages as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul>';
        echo '</div>';
    }

    // If there is a success message, display it
    if (!empty($success_message)) {
        echo '<div>';
        echo '<strong>' . $success_message . '</strong>';
        echo '</div>';
    }
    ?>
    <br>

    <form action="index.php" method="POST">
        
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
        </div>
        <br>

        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
        </div>
        <br>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        </div>
        <br>

        <div>
            <label for="kra_pin">KRA PIN:</label>
            <input type="text" id="kra_pin" name="kra_pin" placeholder="e.g., A123456789B" value="<?php echo htmlspecialchars($kra_pin); ?>">
        </div>
        <br>

        <div>
            <button type="submit">Register</button>
        </div>

    </form>

</body>
</html>