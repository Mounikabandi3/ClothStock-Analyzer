<?php
include'../db.php';

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Example query (replace with actual database query)
    $sql = "SELECT * FROM items WHERE item_name LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

    $output = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Construct each item object
            $item = [
                'table_name' => $row['table_name'],
                'id' => $row['id'],
                'cost_price' => $row['cost_price'],
                'colour' => $row['colour'],
                'selling_price' => $row['selling_price'],
                'image' => $row['image'],
                // Add more fields as needed
            ];
            $output[] = $item;
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($output);
}

$conn->close();
?>
