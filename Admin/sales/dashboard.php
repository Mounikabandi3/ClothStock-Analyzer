<?php
session_start(); // Start or resume session

// Check if user is logged in or authorized (implement your own logic)
if (!isset($_SESSION['admin'])) {
    // Redirect or handle unauthorized access
  
}

// HTML structure for your dashboard page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Container to hold both navbars */
        .navbar-container {
            display: flex;
            width: 100%;
            height: 100vh; /* Ensure the container takes full viewport height */
            box-sizing: border-box;
        }

        /* Vertical navbar styles */
        .vertical-navbar {
            width: 300px; /* Fixed width */
            background-color: #333; /* Example background color */
            color: gold;
            padding: 20px;
            box-sizing: border-box;
            flex-shrink: 0; /* Prevent shrinking */
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo img {
            width: 150px; /* Adjust as needed */
            height: 120px; /* Adjust as needed */
            border-radius: 50%;
        }
        
        .logo .tagline {
            font-size: 20px;
            color: gold;
        }
        
        .menu-items {
            list-style-type: none;
            padding: 0;
        }
        
        .menu-items li {
            margin-bottom: 10px;
        }
        
        .menu-items a {
            display: block;
            padding: 10px;
            font-size: 20px;
            text-decoration: none;
            color: gold; /* Adjust text color */
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .menu-items a:hover {
            background-color: gold;
            border-radius: 20px;
        }
        
        /* Main content container */
        .main-content {
            display: flex;
            flex-direction: column; /* Arrange children vertically */
            flex: 1; /* Take up remaining space */
            box-sizing: border-box;
        }

        /* Horizontal navbar styles */
        .horizontal-navbar {
            width: 100%; /* Take full width of main content */
            background-color: white;
            color: gold;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            align-items: center; /* Align items vertically in the center */
            justify-content: flex-end; /* Align content to the right within the horizontal navbar */
        }

        .horizontal-navbar .search-form {
            display: flex;
            align-items: center;
        }

        .horizontal-navbar .search-form input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .horizontal-navbar .search-form .icon {
            margin-left: 5px;
            cursor: pointer;
        }

        /* Main content area styles */
        .container {
            flex: 1; /* Take up remaining space */
            background-color: white;
            padding: 20px;
            box-sizing: border-box;
        }

        .content {
            width: 100%;
            height: calc(100vh - 80px); /* Adjust height based on the horizontal navbar height */
            border: none;
        }

        /* Styles for sections */
        .section {
            display: none;
            padding: 20px;
        }

        .section.active-section {
            display: block;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .approve, .ignore {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .approve {
            background-color: green;
            color: white;
        }

        .ignore {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body class="dashboard-page">
    <div class="navbar-container">
        <!-- Vertical Navbar -->
        <nav class="vertical-navbar">
            <div class="logo">
                <img src="images/logo.png" alt="Logo">
                <p class="tagline">Bandi Brothers</p>
            </div>
            <ul class="menu-items">
                <li><a href="#" onclick="toggleSection('children');"><i class="fas fa-child"></i> Children</a></li>
                <li><a href="#" onclick="toggleSection('gentlemen');"><i class="fas fa-male"></i> Gentlemen</a></li>
                <li><a href="#" onclick="toggleSection('ladies');"><i class="fas fa-female"></i> Ladies</a></li>
                <li><a href="#" onclick="toggleSection('requests');"><i class="fas fa-tasks"></i> Requests</a></li>
            </ul>
        </nav>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Horizontal Navbar -->
            <header class="horizontal-navbar">
                <form id="searchForm" class="search-form">
                    <div class="search-form">
                        <input type="text" id="searchQuery" name="search_query" placeholder="Search by color, ID, or size...">
                    </div>
                </form>
            </header>
            
            <!-- Content Containers for Sections and Search Results -->
            <div id="childrenSection" class="section">
                <iframe id="childrenFrame" class="content" frameborder="0"></iframe>
            </div>
            <div id="gentlemenSection" class="section">
                <iframe id="gentlemenFrame" class="content" frameborder="0"></iframe>
            </div>
            <div id="ladiesSection" class="section">
                <iframe id="ladiesFrame" class="content" frameborder="0"></iframe>
            </div>
            <div id="requestsSection" class="section">
                <div id="requestsContent">
                    <!-- Requests content will be dynamically loaded here -->
                </div>
            </div>
            <div id="searchResults" class="section">
                <!-- Search results will be dynamically loaded here -->
            </div>
        </div>
        <div id="requestsSection" class="section">
    <div id="requestsContent">
        <!-- Already Approved Requests -->
        <div id="approvedRequests">
            <h2>Already Approved Requests</h2>
            <ul id="approvedRequestsList" class="request-list">
                <!-- Approved requests will be dynamically loaded here -->
            </ul>
        </div>

        <!-- Pending Requests -->
        <div id="pendingRequests">
            <h2>Pending Requests</h2>
            <ul id="pendingRequestsList" class="request-list">
                <!-- Pending requests will be dynamically loaded here -->
            </ul>
        </div>
    </div>
</div>
    </div>

    <!-- jQuery for AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Intercept form submission using jQuery
            $('#searchForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                var searchQuery = $('#searchQuery').val().trim(); // Get the search query from input

                // AJAX request to fetch search results
                $.ajax({
                    type: 'GET',
                    url: 'search.php', // PHP file to handle search
                    data: {
                        search_query: searchQuery
                    },
                    dataType: 'html', // Expect HTML response
                    success: function(response) {
                        // Hide all sections except search results
                        $('.section').removeClass('active-section');
                        $('#searchResults').addClass('active-section');

                        // Update search results container
                        $('#searchResults').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Log any errors to console
                    }
                });
            });

            function loadRequests() {
        // Fetch requests
        $.ajax({
            url: 'notify_admin.php', // Path to PHP handler script
            method: 'POST',
            dataType: 'json',
            data: { action: 'fetch_requests' }, // Specify action to fetch requests
            success: function(response) {
                if (response.length > 0) {
                    // Build HTML for requests list
                    var requestHTML = '<ul class="request-list">';
                    $.each(response, function(index, request) {
            requestHTML += '<li>';
            requestHTML += 'Staff login request for username: ' + request.username;
            requestHTML += '<div class="buttons">';
            requestHTML += '<button class="approve" onclick="approveRequest(\'' + request.username + '\')">Approve</button>';
            requestHTML += '<button class="ignore" onclick="ignoreRequest(\'' + request.username + '\')">Ignore</button>';
            requestHTML += '</div>';
            requestHTML += '</li>';
        });
                    requestHTML += '</ul>';
                    $('#requestsContent').html(requestHTML); // Display requests list
                } else {
                    // No requests available message
                    $('#requestsContent').html('<p>No requests available.</p>');
                }
            },
            error: function() {
                // Error handling
                $('#requestsContent').html('<p>Error loading requests.</p>');
            }
        });
    }

    // Call loadRequests() on document ready to load requests initially
    loadRequests();

     // Clear requests
     function clearRequests() {
                $.ajax({
                    url: 'notify_admin.php',
                    method: 'POST',
                    data: { action: 'clear_requests' }, // Clear requests action
                    success: function() {
                        loadRequests(); // Refresh requests list
                    },
                    error: function() {
                        console.error('Error clearing requests.');
                    }
                });
            }

            // Notify admin function
            function notifyAdmin(username) {
                $.ajax({
                    url: 'notify_admin.php',
                    method: 'POST',
                    data: { action: 'notify_admin', username: username }, // Notify admin action
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            console.log('Admin notified for login request: ' + username);
                            // Optionally handle UI update or further actions upon success
                        } else {
                            console.error('Failed to notify admin.');
                        }
                    },
                    error: function() {
                        console.error('Error notifying admin.');
                    }
                });
            }

         
            function approveRequest(username) {
    $.ajax({
        url: 'notify_admin.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'approve_request',
            username: username
        },
        success: function(response) {
            console.log('Response:', response); // Log response for debugging
            if (response.status === 'success') {
                console.log('Request approved successfully for username:', username);
                alert('Request approved successfully.'); // Display success message
                
                // Optionally, update UI based on response
                if (response.username && response.status === 'approved') {
                    // Example: Update UI elements
                    $('#approval_status').text('Approved for ' + response.username);
                }
            } else {
                //alert('Error: ' + response.message); // Display error message
            }
        },
        error: function(xhr, status, error) {
            console.error('Error approving request:', error); // Log AJAX error
           // alert('Error approving request. Please try again.'); // Display error message
        }
    });
}


// Function to ignore request
function ignoreRequest(username) {
    $.ajax({
        url: 'notify_admin.php',
        method: 'POST',
        data: {
            action: 'ignore_request',
            username: username
        },
        success: function() {
            console.log('Request ignored successfully for username:', username);
            loadRequests(); // Refresh requests list after ignoring
        },
        error: function(xhr, status, error) {
            console.error('Error ignoring request:', error); // Log AJAX error
           // alert('Error ignoring request. Please try again.'); // Display error message
        }
    });
}
function checkApproval() {
    $.ajax({
        url: 'notify_admin.php',
        type: 'POST',
        data: { action: 'check_approval', username: username },
        dataType: 'json',
        success: function(response) {
            console.log('Approval Check Response:', response);
            if (response.status === 'success') {
                var approvalStatus = response.approval_status;
                if (approvalStatus === 'Approved') {
                    //alert('Login request approved for ' + username);
                    // Handle UI update or redirect as needed
                } else if (approvalStatus === 'Pending') {
                    console.log('Request still pending...');
                    // Retry or continue checking
                } else {
                    console.error('Unknown approval status');
                    // Handle other errors
                }
            } else {
                console.error('Error:', response.message);
                // Handle error responses
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            // Handle AJAX error
        }
    });
}


            // Expose functions globally
            window.approveRequest = approveRequest;
            window.ignoreRequest = ignoreRequest;

            // Call loadRequests initially
            loadRequests();
        });

        // Function to toggle sections
        function toggleSection(sectionId) {
            // Hide all sections
            $('.section').removeClass('active-section');

            // Show the selected section
            $('#' + sectionId + 'Section').addClass('active-section');

            // Load the appropriate iframe content based on sectionId
            var iframeSrc = 'child.php?section=' + sectionId;
            $('#' + sectionId + 'Frame').attr('src', iframeSrc);
        }
    </script>
</body>
</html>
