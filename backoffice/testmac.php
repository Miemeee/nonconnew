<?php 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

require_once('connection1.php');

$userId = $_SESSION['userId'];

// Fetch user information
$sql = "SELECT userId, firstname, lastname FROM userinfo WHERE userId = :userId";
$stmt = $db->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle deletion request
if (isset($_REQUEST['delete_id'])) {
    $id = $_REQUEST['delete_id'];

    // Fetch the modal value associated with the given cover_details id
    $select_stmt = $db->prepare('SELECT * FROM cover_details WHERE id = :id');
    $select_stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $modal = $row['modal'];

        // Delete the cover image file
        if (!empty($row['cover_image']) && file_exists($row['cover_image'])) {
            unlink($row['cover_image']);
        }

        // Fetch and delete all related product images and data
        $selectpopup_stmt = $db->prepare('SELECT * FROM product WHERE modal = :modal');
        $selectpopup_stmt->bindParam(':modal', $modal, PDO::PARAM_STR);
        $selectpopup_stmt->execute();

        while ($rowpopup = $selectpopup_stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!empty($rowpopup['product_image']) && file_exists($rowpopup['product_image'])) {
                unlink($rowpopup['product_image']); // Delete the product image
            }
        }

        // Delete records from the product table
        $deletepopup_stmt = $db->prepare('DELETE FROM product WHERE modal = :modal');
        $deletepopup_stmt->bindParam(':modal', $modal, PDO::PARAM_STR);
        $deletepopup_stmt->execute();

        // Delete the record from cover_details
        $delete_stmt = $db->prepare('DELETE FROM cover_details WHERE id = :id');
        $delete_stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $delete_stmt->execute();
    }

    header("Location: adddelete.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CivilService&Product</title>
    <link href="../rework/image/logo.png" rel="icon">
    <link href="https://fonts.googleapis.com/css?family=Mitr:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="../rework/css/bostyle.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="../rework/script/scriptinner.js" defer></script>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <div class="row logo">
                <div class="col-wd-4 col-md-4 col-sm-4">
                    <img src="../rework/LINE_ALBUM_Draft_240703_1-removebg-preview.png">
                </div>
                <div class="col-wd-8 col-md-8 col-sm-8">
                    <p>SERVICE & PRODUCT</p>
                </div>
            </div>
            <br><br>
            <div class="user-info">
                <h2><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h2>
            </div><br>
            <ul>
                <li><a href="after-login.php">Home</a></li>
                <li><a href="adddelete.php">Product</a></li>
                <li><a href="testmac.php">Postmachine</a></li>
                <li><a href="port.php">Postportfolio</a></li>
                <li><a href="regis.php">Register</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <main id="second">
            <div class="head"><p style="padding-left:20%;">Add/Edit/Delete Portfolio Images</p></div>
            <br><br><br><br><br><br>
            <div class="container text-center" style="padding-left:20%;">
            <div style="left:50;"><button onclick="window.location.href='add_product.php';" class="button-38" role="button" style="background-color: green; width: 150px;">Add Machine</button>
            </div><br><br>
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM `cover_details`";
                    $sqlpopup = "SELECT * FROM product ORDER BY id";

                    $cover = $db->query($sql);
                    $popup = $db->query($sqlpopup);
                    
                    $Image_stmt = $db->prepare('SELECT * FROM cover_details');
                    $Image_stmt->execute();
                    $rowImage = $Image_stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $popupData = [];
                    if ($popup->rowCount() > 0) {
                        while ($popupRow = $popup->fetch(PDO::FETCH_ASSOC)) {
                            $popupData[$popupRow['modal']][$popupRow['column_number']][] = $popupRow;
                        }
                    }

                    if ($cover->rowCount() > 0) {
                        while ($row = $cover->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="col-wd-12 col-md-6 col-sm-4">';
                            echo '<button class="button-38" role="button">';
                            echo '    <a href="?delete_id=' . $row['id'] . '" class="btn btn-danger">Delete</a>';
                            echo '</button>';
                            echo '  <div id="popup">';
                            echo '      <div class="card">';
                            echo '          <div class="card-img-holder">';
                            echo '              <img src="' . $row['cover_image'] . '" alt="">';
                            echo '          </div>';
                            echo '          <h3 class="blog-title">' . htmlspecialchars($row['cover_title']) . '</h3>';
                            echo '          <p class="description" style="padding:10px;">' . htmlspecialchars($row['cover_description']) . '</p>';
                            echo '          <br><div class="interior"><a class="btn" href="#open-modal' . $row['modal'] . '"><h3>คลิกดูเพิ่มเติม >></h3></a></div>';
                            echo '          <div class="container"></div>';
                            echo '      </div>';
                            echo '      <div id="open-modal' . $row['modal'] . '" class="modal-window">';

                            // Determine modal content width and padding based on column count
                            $modalWidth = '90%'; // Default to 90%
                            // if (isset($popupData[$row['modal']])) {
                            //     $uniqueCols = count(array_keys($popupData[$row['modal']]));
                            //     if ($uniqueCols == 1) {
                            //         $modalWidth = '60%';
                            //     } elseif ($uniqueCols == 2) {
                            //         $modalWidth = '80%';
                            //     }
                            // }

                            echo '          <div class="modal-content" style="width:' . $modalWidth . ';">';
                            echo '              <a href="#" class="modal-close">&times;</a>';
                            echo '              <div class="row">';
                            if (isset($popupData[$row['modal']])) {
                                $firstItem = reset($popupData[$row['modal']]); // Get the first item from the array
                                $firstPopupItem = reset($firstItem); // Get the first popup item

                                echo '<div class="col-wd-12 col-md-12 col-sm-12">';
                                echo '  <div class="col-wd-12 col-md-12 col-sm-12">';
                                echo '      <table class="table table-striped table-bordered table-hover" id="emp-table">';
                                echo '          <thead>';
                                echo '              <tr>';
                                echo '                  <th>Cover Image</th>';
                                echo '                  <th>Cover Title</th>';
                                echo '                  <th>Cover Description</th>';
                                echo '                  <th>Edit</th>';
                                echo '                  <th>Delete</th>';
                                echo '              </tr>';
                                echo '          </thead>';
                                echo '              <tr>';
                                echo '              <td><img style="width:100px"; src="' . htmlspecialchars($row['cover_image'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($row['cover_title'], ENT_QUOTES, 'UTF-8') . '"></td>';
                                echo '              <td>' . htmlspecialchars($row['cover_title'], ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '              <td>' . htmlspecialchars($row['cover_description'], ENT_QUOTES, 'UTF-8') . '</td>';
                                echo '              <td><a style="background-color: #ffc107"; href="edittestmac.php?update_id=' . $row['id'] . '" class="btn btn-warning">Edit</a></td>';
                                echo '              <td><a style="background-color: #dc3545"; href="?delete_id=' . $row['id'] . '" class="btn btn-danger">Delete</a></td>';
                                echo '          </tr>';
                                echo '      </table>';
                                echo '  </div>';

                                // Display the grouped data for the current modal
                                if (isset($popupData[$row['modal']])) {
                                    $displayedItems = []; // To track displayed items
                                    $uniqueCols = count(array_keys($popupData[$row['modal']]));

                                    $colClass = 'col-wd-12 col-md-12 col-sm-12'; // Default to full-width

                                    foreach ($popupData[$row['modal']] as $colNum => $items) {
                                        echo '<div class="' . $colClass . '">';
                                        echo '  <div class="col-wd-12 col-md-12 col-sm-12">';
                                        echo '      <table class="table table-striped table-bordered table-hover" id="emp-table">';
                                        echo '          <thead>';
                                        echo '              <tr>';
                                        echo '                  <th>Product Name</th>';
                                        echo '                  <th>Product Image</th>';
                                        echo '                  <th>Product Size</th>';
                                        echo '                  <th>Product Type</th>';
                                        echo '                  <th>Column</th>';
                                        echo '                  <th>Edit</th>';
                                        echo '                  <th>Delete</th>';
                                        echo '              </tr>';
                                        echo '          </thead>';

                                        // Display each item in the column
                                        foreach ($items as $popupItem) {
                                            if (!in_array($popupItem['id'], $displayedItems)) {
                                                $displayedItems[] = $popupItem['id']; // Track displayed items
                                                $imagePath = "" . htmlspecialchars($popupItem['product_image']);
                                                echo '<tr>';
                                                echo '              <td>' . htmlspecialchars($popupItem['product_name'], ENT_QUOTES, 'UTF-8') . '</td>';
                                                echo '              <td><img style="width:100px"; src="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($popupItem['product_name'], ENT_QUOTES, 'UTF-8') . '"></td>';
                                                echo '              <td>' . htmlspecialchars($popupItem['size'], ENT_QUOTES, 'UTF-8') . '</td>';
                                                echo '              <td>' . htmlspecialchars($popupItem['type'], ENT_QUOTES, 'UTF-8') . '</td>';
                                                echo '              <td>' . htmlspecialchars($popupItem['column_number'], ENT_QUOTES, 'UTF-8') . '</td>';
                                                echo '              <td><a style="background-color: #ffc107"; href="editmachine.php?update_id=' . $popupItem['id'] . '" class="btn btn-warning">Edit</a></td>';
                                                echo '              <td><a style="background-color: #dc3545"; href="?delete_id=' . $popupItem['id'] . '" class="btn btn-danger">Delete</a></td>';
                                                echo '          </tr>';
                                            }
                                        }

                                        echo '      </table>';
                                        echo '  </div>';
                                        echo '</div>';
                                    }
                                }

                                echo '                  </div>';
                                echo '              </div>';
                            }

                            echo '          </div>';
                            echo '      </div>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>
    <script>
        function showImage(imageSrc) {
            var popupImage = document.getElementById("popupImage");
            var imagePopup = document.getElementById("imagePopup");
            popupImage.src = imageSrc;
            imagePopup.style.display = "block";
        }

        function closeImagePopup() {
            var imagePopup = document.getElementById("imagePopup");
            imagePopup.style.display = "none";
        }
    </script>
</body>
</html>
