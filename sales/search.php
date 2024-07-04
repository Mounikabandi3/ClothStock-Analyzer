<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .items-container, .content {
            max-width: 1127px;
            margin: 20px auto;
            padding: 20px;
            padding-bottom: 100px;
            box-sizing: border-box;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            max-width: 500px;
            margin: 90px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            display: none;
            text-align: center;
        }
        .form-container.active {
            display: block;
        }
        .form-container h2 {
            margin-bottom: 80px;
            color: #1a1a1a;
        }
        .form-input {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form-input:focus {
            outline: none;
            border-color: #ffd700;
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
        }
        .form-submit {
            background-color: #ffd700;
            color: #1a1a1a;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .form-submit:hover {
            background-color: #1a1a1a;
            color: #ffd700;
        }
        .add-stock-btn {
            background-color: #ffd700;
            color: #1a1a1a;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .add-stock-btn:hover {
            background-color: #1a1a1a;
            color: #ffd700;
        }
        .items-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 20px;
        }
        .items-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .item {
            width: 200px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-sizing: border-box;
            
            transition: background-color 0.3s ease; 
        }
        .item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }
        .item:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            background-color: #f7f7f7; /* add a light gray background on hover */
            color: #333; /* change the text color to a darker gray on hover */
        }
        .details {
            padding: 10px;
            font-size: 14px;
            color: gold;
            text-align: left;
            background-color: #363636;
            border-radius: 0 0 8px 8px;
        }
        .button-container {
            display: flex;
            align-items: center;
            gap: 20px;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .content {
            width: 1127px;
            margin: 20px auto;
            margin-bottom: 80px;
            padding: 20px;
            padding-bottom: 100px;
            box-sizing: border-box;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <h1>Search Results</h1>

    <?php
    // Include database connection
    include '../db.php';

    // Ensure search term is set
    $search_term = isset($_GET['search_query']) ? $_GET['search_query'] : '';

    $tables = [
        'dresses', 'men_pants', 'men_shirts', 'men_trousers', 'men_t_shirts',
        'pants', 'shirts', 'skirts', 'track_pants', 't_shirts',
        'women_blouses', 'women_dresses', 'women_jeans', 'women_leggings', 'women_sarees'
    ];

    // Columns to search in
    $search_columns = ['id', 'cost_price', 'colour', 'size']; // Include 'image' column

    // Array to hold matched tables
    $matched_tables = [];

    // Array to hold search results
    $search_results = [];

    foreach ($tables as $table) {
        $search_conditions = [];

        foreach ($search_columns as $column) {
            // Check if the column exists in the table
            $result = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
            if ($result && $result->num_rows > 0) {
                $search_conditions[] = "$table.$column LIKE ?";
            }
        }

        if (!empty($search_conditions)) {
            $search_conditions_str = implode(' OR ', $search_conditions);

            // Construct the SQL query
            $sql = "SELECT * FROM $table WHERE $search_conditions_str";

            // Prepare and execute the statement
            $stmt = $conn->prepare($sql);
            $search_param = "%{$search_term}%";
            $params = array_fill(0, count($search_conditions), $search_param);
            $stmt->bind_param(str_repeat('s', count($search_conditions)), ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results and store in search_results array
            while ($row = $result->fetch_assoc()) {
                // Append base path to image filename
                $row['image'] = 'uploads/' . $row['image'];
                $search_results[] = [
                    'table' => $table,
                    'id' => $row["id"],
                    'cost_price' => $row["cost_price"],
                    'colour' => $row["colour"],
                    'size' => $row["size"],
                    'selling_price' => $row["selling_price"],
                    'image' => $row["image"]
                ];
            }

            $stmt->close();
        }
    }

    // Display search results
    if (!empty($search_results)) {
        echo "<div class='items-container'>";
        echo "<div class='items-grid'>";
        foreach ($search_results as $result) {
            echo "<div class='item'>";
            echo "<img src='" . $result['image'] . "' alt='Item Image'>";
            echo "<div class='details'>";
            echo "<p>Table: " . $result['table'] . "</p>";
            echo "<p>Cost Price: $" . $result['cost_price'] . "</p>";
            echo "<p>Colour: " . $result['colour'] . "</p>";
            echo "<p>Size: " . $result['size'] . "</p>";
            echo "<p>Selling Price: $" . $result['selling_price'] . "</p>";
            echo "</div></div>";
        }
        echo "</div></div>";
    } else {
        echo "<p>No results found for search term '$search_term'.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
