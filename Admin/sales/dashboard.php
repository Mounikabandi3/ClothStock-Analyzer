<?php
session_start(); // Start or resume session

// Check if user is logged in or authorized (implement your own logic)
if (!isset($_SESSION['admin'])) {
    // Redirect or handle unauthorized access
}
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

        .approve, .ignore, .remove {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .approve {
            background-color: green;
            color: white;
        }

        .ignore, .remove {
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
                <div id="approvedRequests">
                    <h2>Approved Requests</h2>
                    <ul id="approvedRequestsList" class="request-list">
                        <!-- Approved requests will be dynamically loaded here -->
                    </ul>
                </div>
            </div>
            <div id="searchResults" class="section">
                <!-- Search results will be dynamically loaded here -->
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
    console.log("Fetching requests..."); // Log that the function has started

    // Fetch requests
    $.ajax({
        url: 'notify_admin.php', // Path to PHP handler script
        method: 'POST',
        dataType: 'json',
        data: { action: 'fetch_requests' }, // Specify action to fetch requests
        success: function(response) {
            console.log("Response received:", response); // Log the received response

            if (response.length > 0) {
                console.log("Building HTML for requests..."); // Log that HTML is being built
                // Build HTML for requests list
                var requestHTML = '<ul class="request-list">';
                $.each(response, function(index, request) {
                    console.log("Processing request for:", request.username); // Log each request's username
                    requestHTML += '<li>';
                    requestHTML += 'Staff login request for username: ' + request.username;
                    requestHTML += '<div class="buttons">';
                    requestHTML += '<button class="approve" onclick="approveRequest(\'' + request.username + '\', this)">Approve</button>';
                    requestHTML += '<button class="ignore" onclick="ignoreRequest(\'' + request.username + '\')">Ignore</button>';
                    requestHTML += '</div>';
                    requestHTML += '</li>';
                });
                requestHTML += '</ul>';

                console.log("HTML built for requests:", requestHTML); // Log the generated HTML

                // Update requests section with generated HTML
                $('#requestsContent').html(requestHTML);
                console.log("Requests section updated."); // Log that the requests section has been updated
            } else {
                console.log("No new requests."); // Log that no requests were found
                $('#requestsContent').html('<p>No new requests.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error); // Log any errors to console
        }
    });
}


    function loadApprovedRequests() {
        $.ajax({
            url: 'notify_admin.php',
            method: 'POST',
            dataType: 'json',
            data: { action: 'fetch_approved_requests' }, // Action to fetch approved requests
            success: function(response) {
                if (response.length > 0) {
                    var approvedHTML = '<ul class="request-list">';
                    $.each(response, function(index, request) {
                        approvedHTML += '<li>';
                        approvedHTML += 'Approved request for username: ' + request.username;
                        approvedHTML += '<button class="remove" onclick="removeApprovedRequest(\'' + request.username + '\')">Remove</button>';
                        approvedHTML += '</li>';
                    });
                    approvedHTML += '</ul>';

                    $('#approvedRequestsList').html(approvedHTML);
                } else {
                    $('#approvedRequestsList').html('<p>No approved requests.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error); // Log any errors to console
            }
        });
    }

    loadRequests(); // Load requests on page load
    loadApprovedRequests(); // Load approved requests on page load

    // Approve request function
    window.approveRequest = function(username, button) {
        $.ajax({
            url: 'notify_admin.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'approve_request', // Action to approve request
                username: username // Username to approve
            },
            success: function(response) {
                if (response.success) {
                    alert('Request approved.');
                    loadRequests(); // Reload requests after approving
                    loadApprovedRequests(); // Reload approved requests after approving
                } else {
                    alert('Failed to approve request.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error); // Log any errors to console
            }
        });
    }

    // Ignore request function
    window.ignoreRequest = function(username) {
        $.ajax({
            url: 'notify_admin.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'ignore_request', // Action to ignore request
                username: username // Username to ignore
            },
            success: function(response) {
                if (response.success) {
                    alert('Request ignored.');
                    loadRequests(); // Reload requests after ignoring
                } else {
                    alert('Failed to ignore request.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error); // Log any errors to console
            }
        });
    }

    // Remove approved request function
    window.removeApprovedRequest = function(username) {
        $.ajax({
            url: 'notify_admin.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'remove_access', // Action to remove approved request
                username: username // Username to remove
            },
            success: function(response) {
                if (response.success) {
                    alert('Approved request removed.');
                    loadApprovedRequests(); // Reload approved requests after removal
                } else {
                    alert('Failed to remove approved request.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error); // Log any errors to console
            }
        });
    }
});


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
