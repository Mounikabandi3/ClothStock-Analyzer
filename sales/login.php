<?php
session_start();
include '../db.php'; // Include your database connection file

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
        $sql = "SELECT id, username, password, status FROM members WHERE username = ?";

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
                    $stmt->bind_result($id, $username, $hashed_password, $status);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            if ($status === 'Approved') {
                                echo json_encode(['status' => 'approved']);
                                exit;
                            } else {
                                $_SESSION["login_request"] = $username; // Temporary store login request
                                echo json_encode(['status' => 'pending']);
                                exit;
                            }
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $username_err = "No account found with that username.";
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Oops! Something went wrong. Please try again later.']);
                exit;
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
    echo json_encode(['status' => 'error', 'username_err' => $username_err, 'password_err' => $password_err]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bandi Brothers</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            display: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form id="loginForm" method="post">
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
        <div class="container" id="waitContainer" style="display: none;">
            Please wait for access...
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            // Add an 'action' parameter to specify the action being performed
            formData += '&action=login';

            $.ajax({
                url: 'login.php', // Replace with actual path to your PHP script
                method: 'POST',
                data: formData,
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        console.log('Response:', data); // Log the response received from the server
                        if (data.status === 'approved') {
                            console.log('Login approved. Redirecting to dashboard...');
                            window.location.href = 'dashboard.php';
                        } else if (data.status === 'pending' ) {
                            console.log('Login pending. Waiting for approval...');
                            $('#loginForm').hide();
                            $('#waitContainer').show();
                            checkApproval(data.username); // Check for approval periodically
                        } else {
                            // Handle errors or other cases
                            if (data.username_err) {
                                alert(data.username_err);
                            }
                            if (data.password_err) {
                                alert(data.password_err);
                            }
                            if (data.message) {
                                alert(data.message);
                            }
                        }
                    } catch (e) {
                        console.error('JSON Parse Error:', e);
                        console.log('Response:', response); // Log the actual response for debugging
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.log('XHR Object:', xhr); // Log the XHR object for further inspection
                }
            });
        });

        // Check every 5 seconds
        function checkApproval(username) {
            setTimeout(function() {
                $.ajax({
                    url: '../Admin/sales/notify_admin.php',
                    method: 'POST',
                    data: { action: 'check_approval', username: username },
                    success: function(response) {
                        try {
                            var data = JSON.parse(response);
                            console.log('Approval Check Response:', data);
                            if (data.status === 'approved') {
                                console.log('Login approved. Redirecting to dashboard...');
                                window.location.href = 'dashboard.php';
                            } else if (data.status === 'pending') {
                                console.log('Approval pending. Checking again in 5 seconds...');
                                checkApproval(username); // Check again after 5 seconds
                            } else {
                                console.log('Error:', data.message);
                            }
                        } catch (e) {
                            console.error('JSON Parse Error:', e);
                            console.log('Response:', response);
                            console.log('Retrying approval check in 5 seconds...');
                            checkApproval(username); // Retry after 5 seconds upon error
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.log('XHR Object:', xhr);
                        console.log('Retrying approval check in 5 seconds...');
                        checkApproval(username); // Retry after 5 seconds upon error
                    }
                });
            }, 5000); // Check every 5 seconds
        }
    });
    </script>
</body>
</html>
