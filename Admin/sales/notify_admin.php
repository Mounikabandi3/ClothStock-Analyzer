<?php
session_start();
include '../../db.php'; // Adjust the path to your database connection file as needed

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle actions based on POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Action: fetch_requests
        if ($action === 'fetch_requests') {
            $response = [];

            // Fetch requests from the database or session
            if (isset($_SESSION['login_request'])) {
                $username = $_SESSION['login_request'];

                // Check the approval status
                $sql = "SELECT status FROM members WHERE username = ? ";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("s", $username);
                    if ($stmt->execute()) {
                        $stmt->store_result();
                        if ($stmt->num_rows == 1) {
                            $stmt->bind_result($status);
                            if ($stmt->fetch()) {
                                if ($status === 'Approved') {
                                    echo json_encode(['status' => 'approved', 'username' => $username]);
                                    exit;
                                } else {
                                    $response = [['username' => $username, 'status' => 'pending']];
                                }
                            }
                        }
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
                        exit;
                    }
                    $stmt->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
                    exit;
                }
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
                    if ($stmt->execute()) {
                        $stmt->close();

                        // Return success response for redirect
                        echo json_encode(['status' => 'approved', 'username' => $username]);
                        exit;
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
                        exit;
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No matching login request']);
                exit;
            }
        }

        // Action: ignore_request
        elseif ($action === 'ignore_request' && isset($_POST['username'])) {
            $username = $_POST['username'];

            // Update status in members table
            $sql = "UPDATE members SET status = 'Denied' WHERE username = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute()) {
                    $stmt->close();
                    echo json_encode(['status' => 'success', 'message' => 'Login request denied']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
        }

        // Action: fetch_denied_requests
        elseif ($action === 'fetch_denied_requests') {
            $response = [];

            $sql = "SELECT username FROM members WHERE status = 'Denied'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $response[] = $row;
                }
            }

            echo json_encode($response);
        }

        // Action: restore_request
        elseif ($action === 'restore_request' && isset($_POST['username'])) {
            $username = $_POST['username'];

            // Update status in members table
            $sql = "UPDATE members SET status = 'Pending' WHERE username = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute()) {
                    $stmt->close();
                    echo json_encode(['status' => 'success', 'message' => 'Request restored to pending']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
        }

        // Action: check_approval
        elseif ($action === 'check_approval' && isset($_SESSION['login_request'])) {
            $username = $_SESSION['login_request'];

            // Check the approval status from the database
            $sql = "SELECT status FROM members WHERE username = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($status);
                        if ($stmt->fetch()) {
                            if ($status === 'Approved') {
                                echo json_encode(['status' => 'approved', 'username' => $username]);
                            } else {
                                echo json_encode(['status' => 'pending', 'username' => $username]);
                            }
                        }
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'No login request found']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
        }

        // Action: check_ignoral
           elseif ($action === 'check_ignoral' && isset($_SESSION['login_request'])) {
            $username = $_SESSION['login_request'];

            // Check the approval status from the database
            $sql = "SELECT status FROM members WHERE username = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($status);
                        if ($stmt->fetch()) {
                            if ($status === 'Denied') {
                                echo json_encode(['status' => 'Denied', 'username' => $username]);
                            } else {
                                echo json_encode(['status' => 'pending', 'username' => $username]);
                            }
                        }
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'No login request found']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
        }

        // Action: fetch_approved_requests
        elseif ($action === 'fetch_approved_requests') {
            $sql = "SELECT username FROM members WHERE status = 'Approved'";
            $result = $conn->query($sql);

            $approvedRequests = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $approvedRequests[] = ['username' => $row['username']];
                }
            }

            echo json_encode($approvedRequests);
        }

          // Action: check_removal
          elseif ($action === 'check_removal' && isset($_SESSION['login_request'])) {
            $username = $_SESSION['login_request'];

            $query = "SELECT status FROM members WHERE username = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows === 1) {
                        $stmt->bind_result($status);
                        if ($stmt->fetch()) {
                            if ($status === 'pending') {
                                $response = ['status' => 'pending', 'username' => $username];
                            } else {
                                $response = ['status' => $status, 'username' => $username];
                            }
                        }
                    } else {
                        $response['message'] = 'No record found';
                    }
                } else {
                    $response['message'] = 'Failed to execute statement: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = 'Failed to prepare statement: ' . $conn->error;
            }

            echo json_encode($response);
        }

        // Action: remove_access
        elseif ($action === 'remove_access' && isset($_POST['username'])) {
            $username = $_POST['username'];

            // Remove access by updating status in the members table
            $sql = "UPDATE members SET status = 'pending' WHERE username = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $username);
                if ($stmt->execute()) {
                    $stmt->close();
                    echo json_encode(['status' => 'success', 'message' => 'Access removed for ' . $username]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            }
        }

        // Action: logout
        elseif ($action === 'logout') {
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
        
                // Prepare and execute the SQL statement
                $stmt = $conn->prepare("UPDATE members SET status = 'pending' WHERE username = ?");
                $stmt->bind_param("s", $username);
        
                if ($stmt->execute()) {
                    // Destroy the session
                    session_destroy();
                    echo json_encode(['status' => 'success', 'message' => 'Status set to pending and logged out successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
                }
        
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No user logged in']);
            }
        }
    }
}
?>
