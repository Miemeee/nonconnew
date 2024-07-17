<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // รับข้อมูลจากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];

    // เตรียมคำสั่ง SQL
    $sql = "SELECT * FROM userinfo WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // ล็อกอินสำเร็จ
      $row = $result->fetch_assoc();
      
      $_SESSION['loggedin'] = true;
      $_SESSION['userId'] = $row['userId'];
      $_SESSION['username'] = $row["username"];
      $_SESSION['firstname'] = $row['firstname'];
      $_SESSION['lastname'] = $row['lastname'];
      header("Location: after-login.php");
      exit();
    } else {
        // ล็อกอินไม่สำเร็จ
        echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');</script>";
    }

    $stmt->close();
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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../rework/css/loginstyle.css">
</head>
<body>
<div class="box-form">
  <div class="left">
    <div class="overlay">
      <!-- <h1>Welcome To</h1>
      <h3>Civil Product&Service</h3> -->
    </div>
  </div>
  
  <div class="right">
    <center><img src="../rework/image/logo.png"></center>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <div class="inputs">
        <input type="username" name="username" placeholder="Username" required>
        <br>
        <input type="password" name="password" placeholder="Password" required>
      </div>
      
      <br><br>
      
      <div class="remember-me--forget-password">
        <label>
          <input type="checkbox" name="remember" checked/>
          <span class="text-checkbox">Remember me</span>
        </label>
        <p><a href="#">Forget password?</a></p>
      </div>
      
      <br>
      <input type="submit" value="Login">
    </form>
  </div>
</div>
</body>
</html>
