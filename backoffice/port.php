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
                        echo 'user not found';
                    }
                ?></h2>
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
        <main style="padding-left:12%;">
            <div class="head"><p style="padding-left:10%;">Add/Edit/Delete Portfolio Images</p></div>
            <br><br><br><br><br><br>
            <div class="container text-center">
                <div class="row">
                    <div class="col-wd-12 col-md-12 col-sm-12" style="padding-left:10%;">
                        <table class="table table-striped table-bordered table-hover" id="emp-table">
                            <thead>
                                <tr>
                                    <th col-index="1" style="width:500px;">Portfolio Title<br><br><a href="#" class="btn btn-success" onclick="showModal('add')">Add Image</a></th>
                                    <th col-index="2" style="width:470px;">Portfolio image</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $select_stmt = $db->prepare('SELECT * FROM tbi_file WHERE position = :position');
                                    $position = 'ผลงาน';
                                    $select_stmt->bindParam(':position', $position);
                                    $select_stmt->execute();
                                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?></td>                        
                                        <td><img src="upload/<?php echo $row['image']; ?>" width="100px" height="100px" alt=""></td>
                                        <td><a href="edit.php?update_id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a></td>
                                        <td><a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal" id="machineModal" style="display: none;">
            
                <article class="modal-container">
                <span class="close" onclick="hideModal()">×</span>
                    <header class="modal-container-header">
                        <h1 class="modal-container-title">Insert Image
                        </h1>
                    </header>
                    <section class="modal-container-body rtf">
                    <div class="container text-center">
                        <?php if(isset($errorMsg)) { ?>
                            <div class="alert alert-danger">
                                <strong><?php echo $errorMsg; ?></strong>
                            </div>
                        <?php } ?>

                        <?php if(isset($insertMsg)) { ?>
                            <div class="alert alert-success">
                                <strong><?php echo $insertMsg; ?></strong>
                            </div>
                        <?php } ?>

                        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="row center">
                                    <label for="name" class="col-sm-3 control-label">Port name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="txt_name" class="inputbox" placeholder=" Input Product Name">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row center">
                                    <label for="category" class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-9">
                                        <select name="txt_category" class="inputbox">
                                            <option value="">Select category</option>
                                            <option value="portfolio">portfolio</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row center">
                                    <label for="position" class="col-sm-3 control-label">Position of image</label>
                                    <div class="col-sm-9">
                                        <select name="txt_position" class="inputbox">
                                            <option value="">Select image position's</option>
                                            <option value="ผลงาน">ผลงาน</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row center">
                                    <label for="file" class="col-sm-3 control-label">Upload image file</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="txt_file" class="inputbox">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input type="submit" name="btn_insert" class="btn btn-success" value="Insert">
                                    <a href="adddelete.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    </section>

                    </footer>
                </article>
            </div>
            
            <script>
            function showModal(modalType) {
                var modal = document.getElementById('machineModal');
                modal.style.display = 'block';
                document.getElementById('modalTitle').innerText = modalType === 'add' ? 'เพิ่มข้อมูลเครื่องจักร' : 'แก้ไขข้อมูลเครื่องจักร';
                if (modalType === 'add') {
                    document.getElementById('machineForm').reset();
                    document.getElementById('machineForm').action = 'addmachine.php';
                }
            }

            function hideModal() {
                var modal = document.getElementById('machineModal');
                modal.style.display = 'none';
                var modal2 = document.getElementById('machineModal2');
                modal2.style.display = 'none';
            }
        </script>

           
            
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
