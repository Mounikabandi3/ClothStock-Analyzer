<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bandi Brothers</title>
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
                $username = trim($_POST["username"]);
            }

            // Validate password
            if (empty(trim($_POST["password"]))) {
                $password_err = "Please enter your password.";
            } else {
                $password = trim($_POST["password"]);
            }

            // Check input errors before querying the database
            if (empty($username_err) && empty($password_err)) {
                // Prepare a select statement
                $sql = "SELECT id, username, password FROM members WHERE username = ?";

                if ($stmt = $conn->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("s", $param_username);

                    // Set parameters
                    $param_username = $username;

                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        // Store result
                        $stmt->store_result();

                        // Check if username exists, if yes then verify password
                        if ($stmt->num_rows == 1) {
                            // Bind result variables
                            $stmt->bind_result($id, $username, $hashed_password);
                            if ($stmt->fetch()) {
                                if (password_verify($password, $hashed_password)) {
                                    // Password is correct, start a new session
                                    session_start();

                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["username"] = $username;

                                    // Redirect user to welcome page
                                    header("location: dashboard.php");
                                } else {
                                    // Display an error message if password is not valid
                                    $password_err = "The password you entered was not valid.";
                                }
                            }
                        } else {
                            // Display an error message if username doesn't exist
                            $username_err = "No account found with that username.";
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    $stmt->close();
                }
            }

            // Close connection
            $conn->close();
        }
        ?>

        <form id="login-form" method="post">
            <h2>Login</h2>
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
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
