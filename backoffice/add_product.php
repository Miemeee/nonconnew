<?php
require_once('connection1.php'); // Replace with your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Handle cover details
        $coverImage = isset($_FILES['cover_image']) ? $_FILES['cover_image'] : [];
        $coverTitle = isset($_POST['cover_title']) ? $_POST['cover_title'] : '';
        $coverDescription = isset($_POST['cover_description']) ? $_POST['cover_description'] : '';

        if (isset($coverImage['name']) && $coverImage['name']) {
            // Handle file upload for cover image
            $uploadDir = 'uploads/'; // Directory for uploading images
            $uploadedCoverImagePath = $uploadDir . basename($coverImage["name"]);

            if (!move_uploaded_file($coverImage["tmp_name"], $uploadedCoverImagePath)) {
                throw new Exception('Failed to upload cover image');
            }

            // Insert cover details into the database
            $cover_stmt = $db->prepare("INSERT INTO cover_details (cover_image, cover_title, cover_description) VALUES (:cover_image, :cover_title, :cover_description)");
            $cover_stmt->bindValue(':cover_image', $uploadedCoverImagePath, PDO::PARAM_STR);
            $cover_stmt->bindValue(':cover_title', $coverTitle, PDO::PARAM_STR);
            $cover_stmt->bindValue(':cover_description', $coverDescription, PDO::PARAM_STR);
            $cover_stmt->execute();

            // Get the last inserted ID
            $coverId = $db->lastInsertId();

            // Update modal column in cover_details
            $update_modal_stmt = $db->prepare("UPDATE cover_details SET modal = :modal WHERE id = :id");
            $update_modal_stmt->bindValue(':modal', $coverId, PDO::PARAM_INT);
            $update_modal_stmt->bindValue(':id', $coverId, PDO::PARAM_INT);
            $update_modal_stmt->execute();
        } else {
            throw new Exception('No cover image uploaded');
        }

        // Handle product details
        $numColumns = isset($_POST['numColumns']) ? (int)$_POST['numColumns'] : 0;

        for ($i = 0; $i < $numColumns; $i++) {
            // Check if form data exists before accessing it
            $size = isset($_POST["size$i"]) ? $_POST["size$i"] : null; // Allow null values
            $size_other = isset($_POST["size_other$i"]) ? $_POST["size_other$i"] : null;
            $num_products = isset($_POST["num_products$i"]) ? (int)$_POST["num_products$i"] : 0;
            $product_images = isset($_FILES["product_image$i"]) ? $_FILES["product_image$i"] : [];

            if (isset($product_images['name']) && $product_images['name']) {
                // Handle file upload for product image
                $uploadedFilePath = $uploadDir . basename($product_images["name"]);

                if (move_uploaded_file($product_images["tmp_name"], $uploadedFilePath)) {
                    for ($j = 0; $j < $num_products; $j++) {
                        $product_name = isset($_POST["productName$i-$j"]) ? $_POST["productName$i-$j"] : null; // Allow null values
                        $type = isset($_POST["productType$i-$j"]) ? $_POST["productType$i-$j"] : null;

                        // Prepare statement for inserting product data
                        $insert_stmt = $db->prepare("INSERT INTO product (column_number, size, size_other, product_name, type, product_image, modal) VALUES (:column_number, :size, :size_other, :product_name, :type, :product_image, :modal)");

                        $insert_stmt->bindValue(':column_number', $i + 1, PDO::PARAM_INT);
                        $insert_stmt->bindValue(':size', $size, PDO::PARAM_STR);
                        $insert_stmt->bindValue(':size_other', $size_other, PDO::PARAM_STR);
                        $insert_stmt->bindValue(':product_name', $product_name, PDO::PARAM_STR);
                        $insert_stmt->bindValue(':type', $type, PDO::PARAM_STR);
                        $insert_stmt->bindValue(':product_image', $uploadedFilePath, PDO::PARAM_STR);
                        $insert_stmt->bindValue(':modal', $coverId, PDO::PARAM_INT);

                        $insert_stmt->execute();
                    }
                } else {
                    throw new Exception('Failed to upload image for column ' . ($i + 1));
                }
            } else {
                throw new Exception('No image uploaded for column ' . ($i + 1));
            }
        }

        $successMsg = "Data saved successfully.";
    } catch (PDOException $e) {
        $errorMsg = "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="testmacstyle.css">
</head>
<body>
    <div class="form-container">
        <h1>Add Product Information</h1>
        <?php if (isset($errorMsg)) { ?>
            <div class="alert alert-danger">
                <strong><?php echo $errorMsg; ?></strong>
            </div>
        <?php } ?>

        <?php if (isset($successMsg)) { ?>
            <div class="alert alert-success">
                <strong><?php echo $successMsg; ?></strong>
            </div>
        <?php } ?>

        <form id="productForm" method="post" enctype="multipart/form-data">
            <!-- Cover Details -->
            <div class="form-group">
                <label for="cover_image">Cover Image:</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="cover_title">Cover Title:</label>
                <input type="text" id="cover_title" name="cover_title" required>
            </div>

            <div class="form-group">
                <label for="cover_description">Cover Description:</label>
                <input type="text" id="cover_description" name="cover_description" rows="4" required>
            </div>

            <!-- Product Details -->
            <div class="form-group">
                <label for="numColumns">Number of Columns (max 4):</label>
                <input type="number" id="numColumns" name="numColumns" min="1" max="4" required>
            </div>

            <div id="columnData"></div>

            <button type="button" onclick="generateFormFields()">Enter Information</button>
            <button style="background-color:green;" type="submit">Submit</button>
        </form>
    </div>
    <script>
    function generateFormFields() {
        const numColumns = document.getElementById('numColumns').value;
        const columnData = document.getElementById('columnData');

        columnData.innerHTML = ''; // Clear previous columns

        for (let i = 0; i < numColumns; i++) {
            columnData.innerHTML += `
                <div class="column-group">
                    <h3>Column ${i + 1}</h3>
                    <div class="form-group">
                        <label for="size${i}">Size of Product:</label>
                        <select id="size${i}" name="size${i}" onchange="toggleOtherInput(${i})">
                            <option value="">Not Displayed</option>
                            <option value="3-7.5 ตัน">3-7.5 ตัน</option>
                            <option value="14-23 ตัน">14-23 ตัน</option>
                            <option value="29-38 ตัน">29-38 ตัน</option>
                            <option value="ติดอุปกรณ์พิเศษ">ติดอุปกรณ์พิเศษ</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                        <input type="text" id="size_other${i}" name="size_other${i}" placeholder="Specify Size" style="display:none;">
                    </div>
                    <div class="form-group">
                        <label for="num_products${i}">Number of Products:</label>
                        <input type="number" id="num_products${i}" name="num_products${i}" min="1" onchange="generateProductFields(${i})" required>
                    </div>
                    <div id="productFields${i}"></div>
                    <div class="form-group">
                        <label for="product_image${i}">Product Image:</label>
                        <input type="file" id="product_image${i}" name="product_image${i}" accept="image/*" required>
                    </div>
                </div>
            `;
        }
    }

    function toggleOtherInput(columnIndex) {
        const sizeSelect = document.getElementById(`size${columnIndex}`);
        const sizeOtherInput = document.getElementById(`size_other${columnIndex}`);
        
        sizeOtherInput.style.display = (sizeSelect.value === 'อื่นๆ') ? 'block' : 'none';
    }

    function generateProductFields(columnIndex) {
        const numProducts = document.getElementById(`num_products${columnIndex}`).value;
        const productFieldsContainer = document.getElementById(`productFields${columnIndex}`);
        
        productFieldsContainer.innerHTML = ''; // Clear previous product fields

        for (let j = 0; j < numProducts; j++) {
            productFieldsContainer.innerHTML += `
                <div class="product-group">
                    <div class="form-group">
                        <label for="productName${columnIndex}-${j}">Product Name ${j + 1}:</label>
                        <input type="text" id="productName${columnIndex}-${j}" name="productName${columnIndex}-${j}" required>
                    </div>
                    <div class="form-group">
                        <label for="productType${columnIndex}-${j}">Product Type ${j + 1}:</label>
                        <select id="productType${columnIndex}-${j}" name="productType${columnIndex}-${j}" required>
                            <option value="รถแบคโฮ ล้อแทร๊ค">รถแบคโฮ ล้อแทร๊ค</option>
                            <option value="รถแบคโฮ บูมยาว 12-15 เมตร">รถแบคโฮ บูมยาว 12-15 เมตร</option>
                            <option value="รถแบคโฮ ล้อยาง">รถแบคโฮ ล้อยาง</option>
                            <option value="รถแบคโฮ ติดอุปกรณ์เสริมเศษ">รถแบคโฮ ติดอุปกรณ์เสริมเศษ</option>
                            <option value="รถบรรทุก 10 ล้อดั้มพ์">รถบรรทุก 10 ล้อดั้มพ์</option>
                            <option value="รถบรรทุก 10 ล้อติดเครน">รถบรรทุก 10 ล้อติดเครน</option>
                            <option value="รถบรรทุก 6 ล้อดั้มพ์">รถบรรทุก 6 ล้อดั้มพ์</option>
                            <option value="รถเกรดเดอร์">รถเกรดเดอร์</option>
                            <option value="รถเครน ขนาด 4 ล้อ">รถเครน ขนาด 4 ล้อ</option>
                            <option value="รถบดประเภท ล้อเรียบ - เปลือกหนาม">รถบดประเภท ล้อเรียบ - เปลือกหนาม</option>
                            <option value="รถบดประเภท ล้อหนาม">รถบดประเภท ล้อหนาม</option>
                            <option value="รถบดประเภท ล้อยาง 9 ล้อ">รถบดประเภท ล้อยาง 9 ล้อ</option>
                            <option value="รถกระเช้า ขนาด 20m">รถกระเช้า ขนาด 20m</option>
                            <option value="รถกระเช้า ขนาด 40m">รถกระเช้า ขนาด 40m</option>
                            <option value="รถแทรกเตอร์">รถแทรกเตอร์</option>
                            <option value="รถกระบะตอนครึ่ง">รถกระบะตอนครึ่ง</option>
                            <option value="รถกระบะ 4 ประตู">รถกระบะ 4 ประตู</option>
                            <option value="รถบรรทุกน้ำ">รถบรรทุกน้ำ</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                </div>
            `;
        }
    }
    </script>
</body>
</html>
