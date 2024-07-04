<?php
include '../db.php'; // Include your database connection file

// Retrieve category from GET parameter
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Handle form submission to add stock
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cost_price = $_POST['cost_price'];
    $colour = $_POST['colour'];
    $size = $_POST['size'];

    $targetDir = "./uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Determine the table name
                $table = '';
                switch ($category) {
                    case 'men_pants':
                    case 'men_shirts':
                    case 'men_trousers':
                    case 'men_t_shirts':
                        $table = str_replace('-', '_', $category);
                        break;
                    default:
                        echo "Invalid category";
                        exit;
                }
                $sql = "INSERT INTO $table (cost_price, colour, size, image) VALUES ('$cost_price', '$colour', '$size', '$fileName')";
                if ($conn->query($sql) === TRUE) {
                    header("Location: " . $_SERVER["PHP_SELF"] . "?category=$category&success=1");
                    exit;
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        echo "File is not an image.";
    }

    $conn->close();
}

// Call the stored procedure to get items by category
$stmt = $conn->prepare("CALL GetItemsByCategory(?)");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock - <?php echo ucfirst($category); ?></title>
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
<div class="main-container">
    <h2><?php echo ucfirst($category); ?> Stock</h2>

    <div class="button-container">
        <button class="add-stock-btn">Add Stock</button>
    </div>

    <!-- Form container -->
    <div class="form-container" id="addStockForm">
        <h2>Add Stock - <?php echo ucfirst($category); ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="number" name="cost_price" class="form-input" placeholder="Cost Price" required>
            <input type="text" name="colour" class="form-input" placeholder="Colour" required>
            <input type="text" name="size" class="form-input" placeholder="Size" required>
            <input type="file" name="image" class="form-input" required>
            <input type="submit" value="Add Stock" class="form-submit">
        </form>
    </div>

    <!-- Items container -->
    <div class="items-container">
        <div class="items-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="item">
                        <img src="./uploads/<?php echo $row['image'];?>" alt="Item Image">
                        <div class="details">
                            <p>Cost Price: <?php echo $row['cost_price'];?></p>
                            <p>Colour: <?php echo $row['colour'];?></p>
                            <p>Size: <?php echo $row['size'];?></p>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <p style="text-align: center; font-size: 18px; color: #666;">No items are present. Click 'Add Stock' to add items.</p>
                <?php
            }
            ?>
        </div>
    </div>

    <script>
        const addStockBtn = document.querySelector('.add-stock-btn');
        const addStockForm = document.getElementById('addStockForm');

        addStockBtn.addEventListener('click', function() {
            addStockForm.classList.toggle('active');
            if (addStockForm.classList.contains('active')) {
                addStockBtn.textContent = 'Items';
            } else {
                addStockBtn.textContent = 'Add Stock';
            }
        });
    </script>
</div>
</body>
</html>
