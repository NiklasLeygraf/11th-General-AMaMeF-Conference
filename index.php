<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to the 11th General AMaMeF Conference</title>
    <link rel="stylesheet" href="index.css">
</head>
<body class="body">
    <h1 id="welcome-header">Welcome to the 11th General AMaMeF Conference</h1>
    <p id="register-information">
        To take part in the 11th General AMaMeF Conference, you have to enter your first and last name along with your University/College.<br>
        We also need your email to send a confirmation message.
    </p>

    <?php 
    // Initialize variables to hold form data and errors
    $firstName = $lastName = $university = $email = $confirmEmail = "";
    $errors = [
        "firstName" => false,
        "lastName" => false,
        "university" => false,
        "email" => false,
        "confirmEmail" => false
    ];

    $isRegistered = false;

    // Form validation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $university = trim($_POST['university']);
        $email = trim($_POST['email']);
        $confirmEmail = trim($_POST['confirm-email']);

        // Validate each field
        if (empty($firstName)) {
            $errors["firstName"] = true;
        }

        if (empty($lastName)) {
            $errors["lastName"] = true;
        }

        if (empty($university)) {
            $errors["university"] = true;
        }

        if (empty($email)) {
            $errors["email"] = true;
        } elseif (!str_contains($email, "@")) {
            $errors["email"] = true;
        }

        if (empty($confirmEmail)) {
            $errors["confirmEmail"] = true;
        } elseif ($email !== $confirmEmail) {
            $errors["confirmEmail"] = true;
        }

        // If there are no errors, proceed with registration
        if (empty(array_filter($errors))) {
            $file = 'registry.txt';

            if (file_exists($file)) {
                // Check if the email is already registered
                $fileContent = file_get_contents($file);
                $lines = explode("\n", trim($fileContent));
                foreach ($lines as $line) {
                    $fields = explode(';', $line);
                    if (isset($fields[3]) && $fields[3] === $email) {
                        $isRegistered = true;
                        break;
                    }
                }
            }

            if ($isRegistered) {
                echo "<div style='text-align: center;'><p style='color: red; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); display: inline-block; background-color: #ddd;'>
                This email is already registered!</p></div>";
            } else {
                // Register the user
                $data = "$firstName;$lastName;$university;$email\n";
                file_put_contents($file, $data, FILE_APPEND | LOCK_EX);

                echo "<div style='text-align: center;'><p style='color: blue; text-align: center; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); display: inline-block; background-color: #ddd;'>
                Registration successful! Thank you, $firstName $lastName.</p></div>";

                // Disable the form after successful registration
                echo "<style>#registerForm { display: none; }</style>";

                // Good practice: confirm email -> mail func
                // for example setup a second page
                // link the second page in the email
                // add a confirmed value to the registry

                // if needed add for further process add a password and store it HASHED!!
                // you can use the password_hash / password_verify func 
                // password will be automatically salted
            }
        }
    }
    ?>

    <div id="form-div">
        <form action="index.php" method="post" id="registerForm">
            <div class="form-group">
                <label for="firstName">First name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" class="<?php echo $errors['firstName'] ? 'error-input' : ''; ?>">
            </div>

            <div class="form-group">
                <label for="lastName">Last name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" class="<?php echo $errors['lastName'] ? 'error-input' : ''; ?>">
            </div>

            <div class="form-group">
                <label for="university">University:</label>
                <input type="text" id="university" name="university" value="<?php echo htmlspecialchars($university); ?>" class="<?php echo $errors['university'] ? 'error-input' : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo $errors['email'] ? 'error-input' : ''; ?>">
            </div>

            <div class="form-group">
                <label for="confirm-email">Confirm E-mail:</label>
                <input type="text" id="confirm-email" name="confirm-email" value="<?php echo htmlspecialchars($confirmEmail); ?>" class="<?php echo $errors['confirmEmail'] ? 'error-input' : ''; ?>">
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>