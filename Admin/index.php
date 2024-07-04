<?php

session_start();

$_SESSION['var'] = FAlSE;


if (!isset($_GET['id'])) {
    http_response_code(403);
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Access Forbidden</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .error-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 300px;
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        h1 {
            color: #e74c3c;
            font-size: 28px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        #countdown {
            font-size: 36px;
            font-weight: bold;
            color: #e74c3c;
            animation: pulse 1s infinite alternate;
        }

        @keyframes pulse {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.05);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class='error-container'>
        <h1>Access Forbidden</h1>
        <p>Oops! You don't have permission to access this page.</p>
        <p>Redirecting in <span id='countdown'>6</span> seconds...</p>
    </div>

    <script>
        var countdown = 6;
        var countdownElement = document.getElementById('countdown');

        function redirectToParent() {
            window.location.href = './';
        }

        function updateCountdown() {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown <= 0) {
                redirectToParent();
            } else {
                setTimeout(updateCountdown, 1000); // Update countdown every second
            }
        }

        updateCountdown(); // Start countdown
    </script>
</body>
</html>";
    exit();
}

$correct_id = "SRKRCSE";
$id_from_url = $_GET['id'];

if ($id_from_url !== $correct_id) {
    http_response_code(403);
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Access Forbidden</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
            }
    
            .error-container {
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                padding: 40px;
                width: 300px;
                text-align: center;
                animation: fadeIn 1s ease-out;
            }
    
            h1 {
                color: #e74c3c;
                font-size: 28px;
                margin-bottom: 20px;
                text-transform: uppercase;
            }
    
            p {
                margin-bottom: 20px;
                font-size: 16px;
            }
    
            #countdown {
                font-size: 36px;
                font-weight: bold;
                color: #e74c3c;
                animation: pulse 1s infinite alternate;
            }
    
            @keyframes pulse {
                from {
                    transform: scale(1);
                }
                to {
                    transform: scale(1.05);
                }
            }
    
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <h1>Access Forbidden</h1>
            <p>Oops! You don't have permission to access this page.</p>
            <p>Redirecting in <span id='countdown'>6</span> seconds...</p>
        </div>
    
        <script>
            var countdown = 6;
            var countdownElement = document.getElementById('countdown');
    
            function redirectToParent() {
                window.location.href = '../X';
            }
    
            function updateCountdown() {
                countdown--;
                countdownElement.textContent = countdown;
    
                if (countdown <= 0) {
                    redirectToParent();
                } else {
                    setTimeout(updateCountdown, 1000); // Update countdown every second
                }
            }
    
            updateCountdown(); // Start countdown
        </script>
    </body>
    </html>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
     <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: 
                linear-gradient(45deg, #f0f0f0 25%, #f8f8f8 25%, #f8f8f8 75%, #f0f0f0 75%) 0 0 / 25px 25px,
                linear-gradient(45deg, #f0f0f0 25%, #f8f8f8 25%, #f8f8f8 75%, #f0f0f0 75%) 15px 15px / 30px 30px,
                #f0f0f0; /* Lighter gray background */
            animation: backgroundMove 3s infinite alternate linear; /* Alternate animation for subtle movement */
        }
    
        @keyframes backgroundMove {
            from { background-position: 0 0, 10px 10px; }
            to { background-position: 20px 20px, 30px 30px; }
        }
    
        /* Container styles */
        .container {
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff; /* White background */
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease;
            transform-style: preserve-3d; /* Ensures 3D transformations behave correctly */
            transform: rotateX(5deg) rotateY(5deg); /* Initial rotation for perspective */
        }
    
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px) rotateX(5deg) rotateY(5deg); }
            to { opacity: 1; transform: translateY(0) rotateX(0) rotateY(0); }
        }
    
        /* Heading styles */
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
    
        /* Button styles */
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
    
        .button {
            background-color: #ffd700; /* Gold background */
            border: none;
            color: #1a1a1a; /* Dark gray text */
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            animation: pulse 1s infinite alternate;
        }
    
        .button:hover {
            background-color: #f0c14b; /* Lighter gold on hover */
        }
    
        .button:active {
            background-color: #c7a32b; /* Darker gold on active */
            transform: scale(0.95);
        }
    
        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Admin Login</h1>
        <div class="button-container">
            <button class="button" onclick="redirect()">Click here to access</button>
        </div>
    </div>

    <script>
        function redirect() {
            // PHP session setting
            <?php $_SESSION['var'] = true; ?>

            // Fading out the container
            var container = document.querySelector('.container');
            container.style.transition = 'opacity 0.5s';
            container.style.opacity = '0';

            // Redirecting after delay
            setTimeout(function() {
                window.location.href = 'Admin/login.php';
            }, 500);
        }
    </script>
</body>
</html>
