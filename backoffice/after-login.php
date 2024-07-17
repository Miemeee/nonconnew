<?php
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// สร้างการเชื่อมต่อ
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

$stmt->close();
$conn->close();
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
        <!-- Template Main CSS File -->
        <link href="css/bostyle.css" rel="stylesheet">
        <!-- Script file -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        
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
                <h2><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></h2>
            </div><br>
            <ul>
                <li><a href="after-login.php">หน้าหลัก</a></li>
                <li><a href="adddelete.php">เพิ่ม/ลบสินค้าและบริการ</a></li>
                <li><a href="machine.php">เพิ่ม/ลบเครื่องจักร</a></li>
                <li><a href="regis.php">ลงทะเบียนผู้ใช้</a></li>
                <li><a href="logout.php">ออกจากระบบ</a></li>
            </ul>
        </nav>
        <main>
            <div class="head"><p>ยินดีต้อนรับ</p></div>
            
        </main>
    </div>
</body>
</html>