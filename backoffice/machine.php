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
    $productId = $_REQUEST['delete_id'];

    $select_stmt = $db->prepare('SELECT * FROM machinedes WHERE productId = :productId');
    $select_stmt->bindParam(':productId', $productId);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

    // delete the original record from db
    $delete_stmt = $db->prepare('DELETE FROM machinedes WHERE productId = :productId');
    $delete_stmt->bindParam(':productId', $productId);
    $delete_stmt->execute();

    header("Location: machine.php");
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
    <link href="https://fonts.googleapis.com/css?family=Mitr:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Template Main CSS File -->
    <link href="../rework/css/bostyle.css" rel="stylesheet">
    <!-- Script file -->
   
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
            <div class="head"><p style="padding-left:10%;">เพิ่ม/ลบเครื่องจักร</p></div>
            <br><br><br><br><br><br>
            <div class="container text-center">
                <div class="row">
                    <div class="col-wd-12 col-md-12 col-sm-12" style="padding-left:10%;">
                        <table class="table table-striped table-bordered table-hover" id="emp-table">
                            <thead>
                                <tr>
                                    <th col-index="1" style="width:500px;">ชื่อเครื่องจักร<br><br><a href="addmachine.php" class="btn btn-success">เพิ่มข้อมูลเครื่องจักร</a></th>
                                    <th col-index="2" style="width:250px;">ประเภทของเครื่องจักร
                                        <select class="table-filter" onchange="filter_rows()">
                                            <option value="all">All</option>
                                        </select>
                                    </th>
                                    <th col-index="3" style="width:200px;">ขนาดของเครื่องจักร
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
                                    $select_stmt = $db->prepare('SELECT * FROM machinedes'); 
                                    $select_stmt->execute();
    
                                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row['productName']; ?></td>                        
                                        <td><?php echo $row['position']; ?></td>
                                        <td><?php echo $row['additional']; ?></td>
                                        <td><a href="editmachine.php?update_id=<?php echo $row['productId']; ?>" class="btn btn-warning">แก้ไข</a></td>
                                        <td><a href="?delete_id=<?php echo $row['productId']; ?>" class="btn btn-danger">ลบ</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script>
                function getUniqueValuesFromColumn() {
                    var unique_col_values_dict = {};

                    var allFilters = document.querySelectorAll(".table-filter");

                    allFilters.forEach((filter_i) => {
                        var col_index = filter_i.parentElement.getAttribute("col-index");

                        const rows = document.querySelectorAll("#emp-table > tbody > tr");

                        rows.forEach((row) => {
                            var cell_value = row.querySelector("td:nth-child(" + col_index + ")").innerHTML.trim();

                            if (col_index in unique_col_values_dict) {
                                if (!unique_col_values_dict[col_index].includes(cell_value)) {
                                    unique_col_values_dict[col_index].push(cell_value);
                                }
                            } else {
                                unique_col_values_dict[col_index] = [cell_value];
                            }
                        });
                    });

                    updateSelectOptions(unique_col_values_dict);
                }

                function updateSelectOptions(unique_col_values_dict) {
                    var allFilters = document.querySelectorAll(".table-filter");

                    allFilters.forEach((filter_i) => {
                        var col_index = filter_i.parentElement.getAttribute('col-index');

                        filter_i.innerHTML = '<option value="all">All</option>';

                        unique_col_values_dict[col_index].forEach((value) => {
                            filter_i.innerHTML += `<option value="${value}">${value}</option>`;
                        });
                    });
                }

                function filter_rows() {
                    var allFilters = document.querySelectorAll(".table-filter");
                    var filter_value_dict = {};

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

                        allFilters.forEach((filter_i) => {
                            var col_index = filter_i.parentElement.getAttribute('col-index');
                            var row_cell_value = row.querySelector("td:nth-child(" + col_index + ")").innerHTML.trim();

                            if (col_index in filter_value_dict && filter_value_dict[col_index] !== "all") {
                                if (!row_cell_value.includes(filter_value_dict[col_index])) {
                                    display_row = false;
                                }
                            }
                        });

                        row.style.display = display_row ? "table-row" : "none";
                    });
                }

                window.onload = function() {
                    getUniqueValuesFromColumn();
                };
            </script>
        </main>
    </div>
</body>
</html>
