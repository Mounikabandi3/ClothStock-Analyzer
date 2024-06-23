<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Bandi Brothers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <div class="form-container">
        <?php
        // Include database connection
        include '../db.php';

        // Define variables and initialize with empty values
        $username = $password = "";
        $username_err = $password_err = "";

        // Processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate username
            if (empty(trim($_POST["username"]))) {
                $username_err = "Please enter a username.";
            } else {
                // Prepare a select statement to check if username already exists
                $sql = "SELECT id FROM members WHERE username = ?";
                
                if ($stmt = $conn->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("s", $param_username);
                    
                    // Set parameters
                    $param_username = trim($_POST["username"]);
                    
                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // Store result
                        $stmt->store_result();
                        
                        if ($stmt->num_rows == 1) {
                            $username_err = "This username is already taken.";
                        } else {
                            $username = trim($_POST["username"]);
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    $stmt->close();
                }
            }

            // Validate password
            if (empty(trim($_POST["password"]))) {
                $password_err = "Please enter a password.";     
            } elseif (strlen(trim($_POST["password"])) < 6) {
                $password_err = "Password must have at least 6 characters.";
            } else {
                $password = trim($_POST["password"]);
            }

            // Check input errors before inserting into database
            if (empty($username_err) && empty($password_err)) {
                // Prepare an insert statement
                $sql = "INSERT INTO members (username, password) VALUES (?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("ss", $param_username, $param_password);
                    
                    // Set parameters
                    $param_username = $username;
                    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // Redirect to login page after successful signup
                        header('Location: login.php');
                        exit;
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }

                    // Close statement
                    $stmt->close();
                }
            }
            
            // Close connection
            $conn->close();
        }
        ?>

        <form id="signup-form" method="post">
            <h2>Signup</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <button type="submit">Signup</button>
        </form>
    </div>
</body>
</html>
