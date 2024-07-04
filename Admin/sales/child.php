<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* General styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: #1a1a1a; /* Dark gray text */
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc; /* Adding border for clarity */
            border-radius: 8px; /* Rounded corners */
            background-color: #f9f9f9; /* Light gray background */
        }

        .header {
            background-color: white; /* White background */
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            margin-bottom: 20px; /* Spacing between header and sections */
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: black;
            margin-bottom: 10px;
        }

        .header-nav {
            margin-top: 10px;
            display: flex;
            justify-content: center; /* Center align the buttons */
        }

        .header-link {
            display: inline-block;
            color: black; /* Dark gray */
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-bottom: 2px solid white; /* White bottom border */
            transition: border-bottom-color 0.3s ease, color 0.3s ease;
            flex: 1; /* Distribute available space equally */
            max-width: 650px; /* Max width to prevent excessive stretching */
            text-align: center; /* Center text */
        }

        .header-link:hover,
        .header-link:focus,
        .header-link:active {
            border-bottom-color: #ffd700; /* Change to gold on hover, focus, and active */
            color: black; /* Ensure text color remains black */
        }

        /* Highlight the current section link */
        .header-link.active {
            border-bottom-color: #ffd700; /* Gold bottom border for active section */
        }

        .content-section {
            display: none;
            margin-top: 20px;
        }

        .content-section.active {
            display: block;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 600;
            color: black; /* Gold */
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
        }

        .items {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .item {
            background-color: gold; /* Gold background */
            padding: 10px 20px; /* Padding adjusted for button-like size */
            border-radius: 30px; /* Rounded corners */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Box shadow for depth */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
            text-align: center; /* Center text */
            color: #1a1a1a; /* Dark gray text */
            cursor: pointer; /* Pointer cursor */
            font-size: 1rem; /* Font size */
            font-weight: 600; /* Font weight */
            max-width: 150px; /* Maximum width */
            margin: 0 auto; /* Center horizontally */
            text-decoration: none; /* Remove underline */
        }

        .item:hover {
            transform: translateY(-5px); /* Lift on hover */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); /* Increased shadow on hover */
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .items {
                flex-direction: column;
            }

            .item {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div id="children-container" class="container">
        <!-- Children's Section Container -->
        <header class="header">
            <h3 class="header-title">Children's Section</h3>
            <nav class="header-nav">
                <a href="#" class="header-link active" data-section="boys-section">BOYS</a>
                <a href="#" class="header-link" data-section="girls-section">GIRLS</a>
            </nav>
        </header>

        <section id="boys-section" class="content-section active">
            <h2 class="section-title">Boys Section</h2>
            <div class="items">
                <a href="items.php?category=shirts" class="item">Shirts</a>
                <a href="items.php?category=pants" class="item">Pants</a>
                <a href="items.php?category=t_shirts" class="item">T-Shirts</a>
                <a href="items.php?category=track_pants" class="item">Track-pants</a>
            </div>
        </section>

        <section id="girls-section" class="content-section">
            <h2 class="section-title">Girls Section</h2>
            <div class="items">
                <a href="items.php?category=dresses" class="item">Dresses</a>
                <a href="items.php?category=skirts" class="item">Skirts</a>
            </div>
        </section>
    </div>

    <div id="gentlemen-container" class="container" style="display: none;">
        <!-- Gentlemen's Section Container -->
        <header class="header">
            <h3 class="header-title">Gentlemen's Section</h3>
            <nav class="header-nav">
                <a href="#" class="header-link active" data-section="gentlemen-shirts-section">SHIRTS</a>
                <a href="#" class="header-link" data-section="gentlemen-pants-section">PANTS</a>
            </nav>
        </header>

        <section id="gentlemen-shirts-section" class="content-section active">
            <h2 class="section-title">Shirts</h2>
            <div class="items">
                <a href="Gentlemen_items.php?category=men_shirts" class="item">Shirts</a>
                <a href="Gentlemen_items.php?category=men_t_shirts" class="item">T-Shirts</a>
            </div>
        </section>

        <section id="gentlemen-pants-section" class="content-section">
            <h2 class="section-title">Pants</h2>
            <div class="items">
                <a href="Gentlemen_items.php?category=men_pants" class="item">Pants</a>
                <a href="Gentlemen_items.php?category=men_trousers" class="item">Trousers</a>
            </div>
        </section>
    </div>

    <div id="ladies-container" class="container" style="display: none;">
        <!-- Ladies' Section Container -->
        <header class="header">
            <h3 class="header-title">Ladies Section</h3>
            <nav class="header-nav">
                <a href="#" class="header-link active" data-section="ladies-dresses-section">DRESSES</a>
                <a href="#" class="header-link" data-section="ladies-skirts-section">SAREES</a>
            </nav>
        </header>

        <section id="ladies-dresses-section" class="content-section active">
            <h2 class="section-title"> Dresses</h2>
            <div class="items">
                <a href="Ladies_items.php?category=women_dresses" class="item">Dresses</a>
                <a href="Ladies_items.php?category=women_leggings" class="item">Leggins</a>
                <a href="Ladies_items.php?category=women_jeans" class="item">Jeans</a>
            </div>
        </section>

        <section id="ladies-skirts-section" class="content-section">
            <h2 class="section-title">Sarees</h2>
            <div class="items">
                <a href="Ladies_items.php?category=women_sarees" class="item">Sarees</a>
                <a href="Ladies_items.php?category=women_blouses" class="item">Blouses</a>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section');

            // Section containers
            const childrenContainer = document.getElementById('children-container');
            const gentlemenContainer = document.getElementById('gentlemen-container');
            const ladiesContainer = document.getElementById('ladies-container');

            // Show the correct container based on the 'section' parameter
            if (section === 'children') {
                childrenContainer.style.display = 'block';
                gentlemenContainer.style.display = 'none';
                ladiesContainer.style.display = 'none';
            } else if (section === 'gentlemen') {
                childrenContainer.style.display = 'none';
                gentlemenContainer.style.display = 'block';
                ladiesContainer.style.display = 'none';
            } else if (section === 'ladies') {
                childrenContainer.style.display = 'none';
                gentlemenContainer.style.display = 'none';
                ladiesContainer.style.display = 'block';
            }

            // Add event listeners to header links for switching sections
            const headerLinks = document.querySelectorAll('.header-link');
            headerLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetSection = this.getAttribute('data-section');

                    // Hide all content sections
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.remove('active');
                    });

                    // Remove active class from all header links
                    headerLinks.forEach(link => {
                        link.classList.remove('active');
                    });

                    // Show the target section
                    document.getElementById(targetSection).classList.add('active');
                    // Add active class to clicked link
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
