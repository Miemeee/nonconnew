<?php
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Initialize $user variable
$user = null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // รับข้อมูลจากฟอร์ม
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $input_username = $_POST['username'];
    $password = $_POST['password'];

    // Initialize $stmt variable
    $stmt = null;

    // Check if username already exists
    $check_username_sql = "SELECT * FROM userinfo WHERE username = ?";
    $check_stmt = $conn->prepare($check_username_sql);
    $check_stmt->bind_param("s", $input_username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('มีชื่อผู้ใช้นี้อยู่ในระบบแล้ว');</script>";
    } elseif (strlen($password) < 8) {
        echo "<script>alert('รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร');</script>";
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
        echo "<script>alert('รหัสผ่านต้องประกอบด้วยตัวอักษรตัวพิมพ์ใหญ่และตัวอักษรตัวพิมพ์เล็กอย่างน้อยหนึ่งตัว');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // เตรียมคำสั่ง SQL
        $insert_sql = "INSERT INTO userinfo (firstname, lastname, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $firstname, $lastname, $input_username, $hashed_password);

        // Execute insert
        if ($stmt->execute()) {
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById("success-popup").style.display = "block";
                    });
                  </script>';
        } else {
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.getElementById("error-popup").innerHTML = "Error: ' . $conn->error . '";
                        document.getElementById("error-popup").style.display = "block";
                    });
                  </script>';
        }
    }

    // Close statements and connection
    $check_stmt->close();
    if ($stmt !== null) {
        $stmt->close();
    }
    $conn->close();
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
    <link href="css/bostyle.css" rel="stylesheet">
    <!-- Script file -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <script src="signin.js"></script>
    <style>
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border: 1px solid #000;
            background-color: #fff;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .popup.success { border-color: green; }
        .popup.error { border-color: red; }
        .popup-close {
            cursor: pointer;
            color: #fff;
            background-color: #000;
            border: none;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
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
            <nav class="menu">
                <ul>
                    <li><a href="after-login.php">หน้าหลัก</a></li>
                    <li><a href="adddelete.php">เพิ่ม/ลบสินค้าและบริการ</a></li>
                    <li><a href="machine.php">เพิ่ม/ลบเครื่องจักร</a></li>
                    <li><a href="regis.php">ลงทะเบียนผู้ใช้</a></li>
                    <li><a href="logout.php">ออกจากระบบ</a></li>
                </ul>
            </nav>
        </aside>
        <main style="padding-left:12%;">
            <div class="head">
                <p style="padding-left:10%;">ลงทะเบียนผู้ใช้</p>
            </div>
            <br><br><br><br><br><br><br><br>
            <div class="col-wd-2 col-md-2 col-sm-2 blank"></div>
            <div class="col-wd-8 col-md-8 col-sm-8">
                <div class="formcover">
                    <form action="#" method="post" class="registration-form">
                        <div class="form-group">
                            <label for="firstname">ชื่อจริง</label>
                            <input type="text" id="firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">นามสกุล</label>
                            <input type="text" id="lastname" name="lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="username">ชื่อผู้ใช้</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">รหัสผ่าน</label>
                            <input type="password" id="password" name="password" required>
                            <div class="error-message">
                                รหัสผ่านของท่านต้องประกอบด้วยพิมพ์เล็กและตัวพิมพ์ใหญ่ และ มีความยาวมากกว่า 8 ตัวอักษร
                            </div>
                        </div><br>
                        <button type="submit" class="submit-btn">ลงทะเบียน</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

        <!-- Success Popup -->
        <div id="success-popup" class="popup success">
            <p>ลงทะเบียนสำเร็จ!</p><br>
            <center><button class="popup-close" onclick="document.getElementById('success-popup').style.display='none'">ปิด</button></center>
        </div>

        <!-- Error Popup -->
        <div id="error-popup" class="popup error">
            <p>Error: ลงทะเบียนไม่สำเร็จ</p><br>
            <center><button class="popup-close" onclick="document.getElementById('error-popup').style.display='none'">ปิด</button></center>
        </div>
    </body>
</html>
