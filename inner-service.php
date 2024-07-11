<?php
    require_once('connection.php');

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];
    
        $select_stmt = $conn->prepare('SELECT * FROM tbi_file WHERE id = ?');
        $select_stmt->bind_param('i', $id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        $row = $result->fetch_assoc();
        unlink("upload/" . $row['image']); // unlink function permanently removes your file
    
        // delete the original record from db
        $delete_stmt = $conn->prepare('DELETE FROM tbi_file WHERE id = ?');
        $delete_stmt->bind_param('i', $id);
        $delete_stmt->execute();
    
        header("Location: gallery.php");
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
        <!-- Template Main CSS File -->
        <link href="../rework/css/innerstyle.css" rel="stylesheet">
        <!-- Script file -->
        <script src="../script/scriptinner.js" defer></script>
      
      </head>
      <body>
        <header>
            <!-- Navbar -->
            <nav>
              <a href="../index.php" class="logo">
                <img src="../rework/image/logore.png" style="width: 100px; padding-top: 20px;">
              </a>
              <ul>
                <li><a href="../index.php">หน้าหลัก</a></li>
                <li><a href="../index.php#contact">ติดต่อ</a></li>
              </ul>
            </nav>
          </header>
    
          <section id="banner" style="padding: 0%;">
            <div class="row">
                <div class="col-wd-12 col-md-12 col-sm-12 parentContainer">
                  <figure class="image">
                    <img src="../rework/image/Carousels/2.jpg" style="width: 100%;">
                    <figcaption><h1>บริการเช่าเครื่องจักร</h1><br><p>เรามีบริการเช่าเครื่องจักรที่สำคัญหลากหลายหลายรายการ

                        พร้อมพนักงานขับมืออาชีพ อาทิ เช่น
                        
                        รถแบคโฮ รถขุด ขนาดเล็ก กลาง ใหญ่</p></figcaption>
                  </figure><br>
              </div>
          </section>
        <section id="second">
            <div class="row">
                <?php
                    include 'connection.php';

                    $sql = "SELECT * FROM `machine`";
                    $result = $conn->query($sql);

                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            echo '<div class="col-wd-12 col-md-6 col-sm-4" >';
                            echo '  <div id="popup">';
                            echo '      <div class="card" >';
                            echo '          <div class="card-img-holder">';
                            echo '              <img src="upload/'. $row['image'] . '" alt="">';
                            echo '          </div>';
                            echo '      <h3 class="blog-title">'.$row["name"].'</h3>';
                            echo '      <p class="description">'.$row["description"].'</p>';
                            echo '      <br><div class="interior"></div>';
                            
                        }
                    } else {
                      echo "0 result";
                    }

                $conn->close();
                
                ?>
            </div>
        </section>
    </body>
</html>