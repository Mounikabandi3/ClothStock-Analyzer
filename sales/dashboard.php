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
                <li><a href="#" onclick="loadChildContent('child.php?section=children');"><i class="fas fa-child"></i> Children</a></li>
                <li><a href="#" onclick="loadChildContent('child.php?section=gentlemen');"><i class="fas fa-male"></i> Gentlemen</a></li>
                <li><a href="#" onclick="loadChildContent('child.php?section=ladies');"><i class="fas fa-female"></i> Ladies</a></li>
            </ul>
        </nav>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Horizontal Navbar -->
            <header class="horizontal-navbar">
                <form action="#">
                    <div class="search-form">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search icon"></i>
                    </div>
                </form>
            </header>
            
            <!-- Content Container -->
            <div class="container">
                <iframe class="content" id="childFrame" frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <!-- JavaScript to load content into iframe -->
    <script>
        function loadChildContent(url) {
            var iframe = document.getElementById('childFrame');
            console.log('Loading URL: ' + url); // Debugging line
            iframe.src = url;
        }
    </script>
</body>
</html>
