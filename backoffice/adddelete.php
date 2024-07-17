<?php 
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

require_once('connection1.php');

$userId = $_SESSION['userId'];

// เขียน SQL query เพื่อดึงข้อมูล
$sql = "SELECT userId, firstname, lastname FROM userinfo WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบการ query
if ($result === FALSE) {
    die("Error: " . $conn->error);
}
// ดึงข้อมูลแถวแรกจากผลลัพธ์
$user = $result->fetch_assoc();

if (isset($_REQUEST['delete_id'])) {
    $id = $_REQUEST['delete_id'];

    $select_stmt = $db->prepare('SELECT * FROM tbi_file WHERE id = :id');
    $select_stmt->bindParam(':id', $id);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    unlink("upload/".$row['image']); // unlink function permanently removes your file

    // delete the original record from db
    $delete_stmt = $db->prepare('DELETE FROM tbi_file WHERE id = :id');
    $delete_stmt->bindParam(':id', $id);
    $delete_stmt->execute();

    header("Location: adddelete.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>CivilService&Product</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../rework/image/logo.png" rel="icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Mitr:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="../rework/css/bostyle.css" rel="stylesheet">
    <!-- Script file -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="../rework/boscript.js">
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
                <h2><?php 
                    if ($user) {
                        echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
                    } else {
                        echo 'ผู้ใช้ไม่พบ';
                    }
                ?></h2>
            </div><br>
            <ul>
                <li><a href="after-login.php">หน้าหลัก</a></li>
                <li><a href="adddelete.php">เพิ่ม/ลบสินค้าและบริการ</a></li>
                <li><a href="machine.php">เพิ่ม/ลบเครื่องจักร</a></li>
                <li><a href="regis.php">ลงทะเบียนผู้ใช้</a></li>
                <li><a href="logout.php">ออกจากระบบ</a></li>
            </ul>
        </nav>
        <main style="padding-left:12%;">
            <div class="head"><p style="padding-left:10%;">เพิ่ม/ลบสินค้าและบริการ</p></div>
            <br><br><br><br><br><br>
            <div class="container text-center">
                <div class="row">
                    <div class="col-wd-12 col-md-12 col-sm-12" style="padding-left:10%;">
                        <table class="table table-striped table-bordered table-hover" id="emp-table">
                            <thead>
                                <tr>
                                    <th col-index="1" style="width:300px;">ชื่อรูปภาพ<br><br><a href="add.php" class="btn btn-success">เพิ่มรูปภาพ</a></th>
                                    <th col-index="2" style="width:300px;">รูปภาพ</th>
                                    <th col-index="3" style="width:165px;">ประเภท
                                        <select class="table-filter" onchange="filter_rows()">
                                            <option value="all">All</option>
                                        </select>
                                    </th>
                                    <th col-index="4">ตำแหน่ง
                                        <select class="table-filter" onchange="filter_rows()">
                                            <option value="all">All</option>
                                        </select>
                                    </th>
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $select_stmt = $db->prepare('SELECT * FROM tbi_file'); 
                                    $select_stmt->execute();
                                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>                        
                                        <td><img src="upload/<?php echo $row['image']; ?>" width="100px" height="100px" alt=""></td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $row['position']; ?></td>
                                        <td><a href="edit.php?update_id=<?php echo $row['id']; ?>" class="btn btn-warning">แก้ไข</a></td>
                                        <td><a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger">ลบ</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           
            
            <script>
                // Function to get unique values from each column and store them in a dictionary
                function getUniqueValuesFromColumn() {
                    var unique_col_values_dict = {};

                    // Select all elements with the class 'table-filter'
                    var allFilters = document.querySelectorAll(".table-filter");

                    allFilters.forEach((filter_i) => {
                        // Get the column index from the parent element's attribute
                        var col_index = filter_i.parentElement.getAttribute("col-index");

                        // Select all rows in the table body
                        const rows = document.querySelectorAll("#emp-table > tbody > tr");

                        rows.forEach((row) => {
                            // Get the cell value for the current column index
                            var cell_value = row.querySelector("td:nth-child("+col_index+")").innerHTML.trim();

                            // Check if the column index already exists in the dictionary
                            if (col_index in unique_col_values_dict) {
                                // Add the cell value to the array if it's not already present
                                if (!unique_col_values_dict[col_index].includes(cell_value)) {
                                    unique_col_values_dict[col_index].push(cell_value);
                                }
                            } else {
                                // Initialize the array with the first cell value
                                unique_col_values_dict[col_index] = [cell_value];
                            }
                        });
                    });

                    // Update the select options with the unique values
                    updateSelectOptions(unique_col_values_dict);
                }

                // Function to add <option> tags to the select elements based on unique values
                function updateSelectOptions(unique_col_values_dict) {
                    var allFilters = document.querySelectorAll(".table-filter");

                    allFilters.forEach((filter_i) => {
                        // Get the column index from the parent element's attribute
                        var col_index = filter_i.parentElement.getAttribute('col-index');

                        // Clear existing options (except the "all" option)
                        filter_i.innerHTML = '<option value="all">All</option>';

                        // Add new options based on unique values
                        unique_col_values_dict[col_index].forEach((value) => {
                            filter_i.innerHTML += `<option value="${value}">${value}</option>`;
                        });
                    });
                }

                // Function to filter rows based on selected filter values
                function filter_rows() {
                    var allFilters = document.querySelectorAll(".table-filter");
                    var filter_value_dict = {};

                    // Create a dictionary of selected filter values
                    allFilters.forEach((filter_i) => {
                        var col_index = filter_i.parentElement.getAttribute('col-index');
                        var value = filter_i.value;
                        if (value != "all") {
                            filter_value_dict[col_index] = value;
                        }
                    });

                    const rows = document.querySelectorAll("#emp-table tbody tr");

                    rows.forEach((row) => {
                        var display_row = true;

                        // Check each row's cell values against the selected filter values
                        allFilters.forEach((filter_i) => {
                            var col_index = filter_i.parentElement.getAttribute('col-index');
                            var row_cell_value = row.querySelector("td:nth-child(" + col_index + ")").innerHTML.trim();

                            if (col_index in filter_value_dict && filter_value_dict[col_index] !== "all") {
                                if (!row_cell_value.includes(filter_value_dict[col_index])) {
                                    display_row = false;
                                }
                            }
                        });

                        // Show or hide the row based on the filter match
                        row.style.display = display_row ? "table-row" : "none";
                    });
                }

                // Initialize unique values and populate filter options on page load
                window.onload = function() {
                    getUniqueValuesFromColumn();
                };
            </script>
        </main>
    </div>
</body>
</html>
