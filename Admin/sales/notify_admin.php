<?php
session_start();
include '../../db.php'; // Adjust the path to your database connection file as needed
error_log('Session Data: ' . print_r($_SESSION, true));

// Handle actions based on POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Action: fetch_requests
        if ($action === 'fetch_requests') {
            $response = [];

            if (isset($_SESSION['login_request'])) {
                $username = $_SESSION['login_request'];

                // Check the approval status
                $sql = "SELECT status FROM members WHERE username = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("s", $username);
                    if ($stmt->execute()) {
                        $stmt->store_result();
                        if ($stmt->num_rows == 1) {
                            $stmt->bind_result($status);
                            if ($stmt->fetch() && $status === 'Approved') {
                                echo json_encode(['status' => 'approved', 'username' => $username]);
                                exit;
                            }
                        }
                    }
                    $stmt->close();
                }
                $response = [['username' => $username, 'status' => 'pending']];
            }

            echo json_encode($response);
        }
        // Action: notify_admin
        elseif ($action === 'notify_admin' && isset($_POST['username'])) {
            $username = $_POST['username'];
            $_SESSION['login_request'] = $username; // Store in session
            echo json_encode(['status' => 'success', 'message' => 'Login request sent to admin']);
        }
        // Action: approve_request
        elseif ($action === 'approve_request' && isset($_POST['username'])) {
            $username = $_POST['username'];

            // Ensure session matches username
            if (isset($_SESSION['login_request']) && $_SESSION['login_request'] === $username) {
                // Update status in members table
                $sql = "UPDATE members SET status = 'Approved' WHERE username = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->close();

                    // Check the approval status after updating
                    $sql_check = "SELECT status FROM members WHERE username = ?";
                    if ($stmt_check = $conn->prepare($sql_check)) {
                        $stmt_check->bind_param("s", $username);
                        if ($stmt_check->execute()) {
                            $stmt_check->store_result();
                            if ($stmt_check->num_rows == 1) {
                                $stmt_check->bind_result($status);
                                if ($stmt_check->fetch()) {
                                    if ($status === 'Approved') {
                                        echo json_encode(['status' => 'approved', 'username' => $username]);
                                    } else {
                                        echo json_encode(['status' => 'pending', 'username' => $username]);
                                    }
                                } else {
                                    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch approval status']);
                                }
                            } else {
                                echo json_encode(['status' => 'error', 'message' => 'User not found']);
                            }
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Failed to execute query']);
                        }
                        $stmt_check->close();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to approve request']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No login request to approve']);
            }
        }
        // Action: deny_request
        elseif ($action === 'deny_request' && isset($_POST['username'])) {
            $username = $_POST['username'];
            
            // Delete the login request from the database (if applicable)
            // Since we are not using login_requests table anymore, adjust accordingly

            echo json_encode(['status' => 'error', 'message' => 'Deny request action not implemented']);
        }
        // Action: check_approval
        elseif ($action === 'check_approval' && isset($_SESSION['login_request'])) {
            $username = $_SESSION['login_request'];
            
            // Check the approval status from the database
            $sql = "SELECT status FROM members WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($status);
                    $stmt->fetch();
                    if ($status === 'Approved') {
                        echo json_encode(['status' => 'approved', 'username' => $username]);
                    } else {
                        echo json_encode(['status' => 'pending', 'username' => $username]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No login request found']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database execution error']);
            }
            $stmt->close();
        }
        // Handle other actions or errors
        else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action parameter not provided.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Close the database connection at the end of your script
$conn->close();
