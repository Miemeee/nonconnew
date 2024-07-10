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
  <link href="css/style.css" rel="stylesheet">
  <!-- Script file -->
  <script src="../rework/script/script.js" defer></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  
</head>
<body>
    <header>
      <!-- Navbar -->
      <nav>
        <a href="" class="logo">
          <img src=".//image/logore.png" style="width: 100px; padding-top: 20px;">
        </a>
        <ul>
          <li><a href="#first">หน้าหลัก</a></li>
          <li><a href="#second">บริการ</a></li>
          <li><a href="#third">สินค้า</a></li>
          <li><a href="#portfolio">ผลงาน</a></li>
          <li><a href="https://www.civilengineering.co.th/th">เว็บหลัก</a></li>
          <li><a href="#description">ติดต่อ</a></li>
        </ul>
      </nav>
    </header>
    <div class="cookie-consent">
    <?php
        session_start();

        // ตรวจสอบว่าผู้ใช้ยอมรับคุกกี้หรือยัง
        if (!isset($_SESSION['accepted_cookie'])) {
            // ถ้ายังไม่ได้ยอมรับคุกกี้ แสดงปุ่มหรือข้อความให้ผู้ใช้ยอมรับ
            echo 'เว็บไซต์นี้ใช้คุกกี้(Cookie) ในการทำงานหลายส่วนของเว็บไซต์เพื่ออำนวยความสะดวกในการใช้บริการเว็บไซตืของท่าน โดยบริษัทรับประกันการใช้คุกกี้เท่าที่จำเป็นและรับประกันมาตรการรักษาความั่นคงปลอดภัยของข้อมูลของท่านโดยสอดคล้องกับกฎหมายที่เกี่ยวข้อง โดยท่านสามารถศึกษารายละเอียดการใช้คุกกี้ได้ที่ นโยบายการใช้คุกกีี้ ทั้งนี้เมื่อท่านกด "ตกลง" บริษัทจะถือว่าท่านตกลงและรับทราบนโยบายข้อมูลส่วนบุคคลฉบับนี้แล้ว<button><a href="accept_cookie.php" style="text-decoration:none";>ยอมรับคุกกี้</a>';
        } else {
            // ถ้ายอมรับคุกกี้แล้ว แสดงเนื้อหาหน้าเว็บปกติ
            echo 'ยอมรับคุกกี้แล้ว';
        }
        ?>
  </div>
  <script>
      function acceptCookies() {
          document.cookie = "cookiesAccepted=true; max-age=" + 1; 
          document.querySelector('.cookie-consent').style.display = 'none';
          document.querySelector('.content').style.display = 'block';
      }

      window.onload = function() {
          let cookiesAccepted = document.cookie.split('; ').find(row => row.startsWith('cookiesAccepted='));
          if (cookiesAccepted && cookiesAccepted.split('=')[1] === 'true') {
              document.querySelector('.cookie-consent').style.display = 'none';
              document.querySelector('.content').style.display = 'block';
          }
      }
  </script>
      <section id="first" style="padding: 0%;">
      
        <div class="slider">
          <figure>
            <div class="slide">
              <img src="..//rework/image/Carousels/1.jpg" alt="1">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
              </figure>
            </div>
              
            <div class="slide">
              <img src="..//rework/image/Carousels/10.jpg" alt="2">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>
              
            <div class="slide">
              <img src="..//rework/image/Carousels/2.jpg" alt="3">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>
            
            <div class="slide">
              <img src="..//rework/image/Carousels/4.jpg" alt="4">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>

            <div class="slide">
              <img src="..//rework/image/Carousels/6.jpg" alt="5">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>

            <div class="slide">
              <img src="..//rework/image/Carousels/7.jpg" alt="6">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>

            <div class="slide">
              <img src="..//rework/image/Carousels/9.jpg" alt="7">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>

            <div class="slide">
              <img src="..//rework/image/Carousels/8.jpg" alt="8">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>

            <div class="slide">
              <img src="..//rework/image/Carousels/3.jpg" alt="9">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>

            <div class="slide">
              <img src="..//rework/image/Carousels/5.jpg" alt="10">
              <figcaption><h1>Welcome To</h1><br><p>CivilService & Product</p><br><br>
              <a href="#second"><span></span><div class="pos down-arrow"></div></a></figcaption>
            </div>

          </figure>
        </div>
      
        <!-- /Sliding Background -->

        <script>
          var myIndex = 0;
          carousel();
          
          function carousel() {
            var i;
            var x = document.getElementsByClassName("slide");
            for (i = 0; i < x.length; i++) {
              x[i].style.display = "none";  
            }
            myIndex++;
            if (myIndex > x.length) {myIndex = 1}    
            x[myIndex-1].style.display = "block";  
            setTimeout(carousel, 3000); // Change image every 2 seconds
          }
          </script>

      </section>


      <section id="second" >
          
          <div class="row">
                <!-- Title -->
              <div class="col-wd-12 col-md-12 col-sm-12">
                  <p style="text-align: center;"><br><br>เราพร้อมให้บริการลูกค้าอย่างเต็มที่ โดยลูกค้าสามารถมั่นใจได้ว่า บริการของเรา<br>
                    " ส่งมอบด้วยคุณภาพ และมีความประทับใจสูงสุด "</p><br><br><br>
              </div>
                <!-- Product Card -->
              <div class="col-wd-12 col-md-6 col-sm-3">
                <div class="card" onclick="javascript:window.location.href='../rework/html/inner-service1.html' ">
                  <h3 class="blog-title">บริการเช่าเครื่องจักร</h3><br>
                  <p class="description" >
                    เครื่องจักรหลากหลายรายการ เช่น รถแบคโฮ รถเครน(รถปั้นจั่น) รถบดสั่นสะเทือน รถสิบล้อ (ดั้ม-หางเป็ด-เฮี๊ยบ-เทเลอร์) ฯลฯ พร้อมพนักงานขับมืออาชีพ
                    <br><br><br><br><br>
                  </p>
                  <div class="card-img-holder">
                    <img src="../rework/image/Products/เครื่องจักร-1.png" alt="Blog image">
                  </div>
                  <br>
                  <div class="options">
                    <a href="../rework/html/inner-service1.html">คลิกดูเพิ่มเติม</h3> >></a>
                  </div>
                </div><br>
              </div>
              <div class="col-wd-12 col-md-6 col-sm-3">
                  <div class="card" onclick="javascript:window.location.href='../rework/html/service-obj.html' ">
                    <h3 class="blog-title">บริการเช่าอุปกรณ์สำหรับงานก่อสร้าง</h3>
                    <p class="description">
                      เรามีอุปกรณ์ที่มีความหลากหลายและ จำเป็นในงานก่อสร้าง เช่น แผ่นชีทไพล์ขนาด 6-12 เมตร นั่งร้านญี่ปุ่น แบบหล่อต่างๆ เหล็กBeam ฯลฯ พร้อมบริการจัดส่งถึงมือลูกค้าด้วยความรวดเร็วปลอดภัย
                      <br><br><br><br>
                    </p>
                    <div class="card-img-holder">
                      <img src="../rework/image/Products/อุปกรณ์-1.png" alt="Blog image">
                    </div>
                    <br>
                    <div class="options">
                      <a href="../rework/html/service-obj.html">คลิกดูเพิ่มเติม >></a>
                    </div>
                  </div><br>
              </div>
              <div class="col-wd-12 col-md-6 col-sm-3">
                  <div class="card" onclick="javascript:window.location.href='../rework/html/service-office.html' ">
                      <h3 class="blog-title">บริการเช่าพื้นที่สำนักงาน</h3><br>
                      <p class="description" >
                        เรามีพื้นที่ให้เช่าทำเลศักยภาพดีอยู่ติดรถไฟฟ้าสายสีแดงสถานีวัดเสมียนนารี พื้นที่จอดรถกว้างขวาง พร้อมด้วยสิ่งอำนวยความสะดวกและมีระบบความปลอดภัยที่ดี
                      <br><br><br><br><br>
                      </p>
                      <div class="card-img-holder">
                        <img src="../rework/image/Products/อาคารสำนักงาน.png" alt="Blog image">
                      </div>
                      <br>
                      <div class="options">
                        <a href="../rework/html/service-office.html">คลิกดูเพิ่มเติม >></a>
                      </div>
                  </div><br>
              </div>
              <div class="col-wd-12 col-md-6 col-sm-3">
                <div class="card" onclick="javascript:window.location.href='../rework/html/service-oth.html' ">
                  <h3 class="blog-title">บริการด้านอื่นๆ</h3><br>
                  <p class="description" >
                    - รับจ้างปูยาง Asphalt<br>
                    - บริการติดตั้ง Segment<br>
                    - บริการติดตั้ง งานสำรวจ
                          <br><br><br><br><br>
                  </p>
                  <div class="card-img-holder">
                    <img src="../rework/image/Products/Segment-1.png" alt="Blog image">
                  </div>
                  <br>
                  <div class="options">
                    <a href="../rework/html/service-oth.html">คลิกดูเพิ่มเติม >></a>
                  </div>
                </div><br><br><br>
          
          </div>
          
            <!-- <div class="col-wd-12 col-md-12 col-sm-12">
              <center><div class="video-container">
                <video id="myVideo" controls autoplay muted>
                    <source src="../rework/Civil.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
       
            </div></center>
          </div> -->
      </section>

      <section id="third" >
          <div class="row>
            <div class="col-wd-12 col-md-12 col-sm-12">
                <p><br><br>เราเป็นผู้ผลิต-จำหน่าย ผลิตภัณฑ์ที่เกี่ยวข้องด้านงานก่อสร้าง<br>
                  เช่น Segment/ แผ่นSheildคอนกรีต/ คอนกรีตสำเร็จรูปอื่นๆ<br>
                  แอสฟัลท์ติกคอนกรีต หินสำหรับงานก่อสร้าง</p>
            </div><br>
          </div>
        <div class="row" >
          <div class="col-wd-12 col-md-12 col-sm-12">
            <div id="filter-buttons"><center>
              <div class="col-wd-12 col-md-12 col-sm-12">
                <button class="btn mb-2 me-1 active" data-filter="all">ทั้งหมด</button>
                <button class="btn mb-2 mx-1" data-filter="segment">Segment</button>
                <button class="btn mb-2 mx-1" data-filter="sheild">Sheild</button>
                <button class="btn mb-2 mx-1" data-filter="alphalt">แอสฟัลท์</button>
                <button class="btn mb-2 mx-1" data-filter="rock">หิน</button>
                <button class="btn mb-2 mx-1" data-filter="concreet">คอนกรีตสำเร็จรูป</button>
              </div></center>
            </div>
          <br><br><br><br>
          </div>
        </div>
          <center><div class="row">
            <?php
                include 'connection.php';
      
                $sql = "SELECT * FROM `tbi_file` WHERE position IN ('สินค้า')";
                $result = $conn->query($sql);
            
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<div class="col-wd-12 col-md-6 col-sm-4" id="filterable-cards">';
                        echo '<div class="card p-0" data-name="'.$row['category'].'">';
                        echo '    <img class="popup-trigger" src="upload/' . $row['image'] . '" alt="">';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "0 results";
                }
          
                $conn->close();
            ?>
            </center>
            
          

    </section>
        <div class="bg">

        <section id="portfolio">
  <div class="row">
      <div class="col-wd-12 col-md-12 col-sm-12">
        <!-- Title -->
        <h1>ผลงานของเรา</h1><br>
        <p>ตลอดระยะเวลาที่ผ่านมาบริษัทได้ดำเนินการก่อสร้างมากกว่า 1,000 โครงการ<br>
        คิดเป็นมูลค่ากว่า 40,000 ล้านบาท โดยผลงานดังกล่าวล้วนเป็นที่ยอมรับในวงกว้าง<br>
        จากผู้ว่าจ้าง ไม่ว่าจะเป็นหน่วยงานราชการหรือบริษัทเอกชน</p><br><br>
      </div>
    </div>
  <div class="row">
      <?php
      include 'connection.php';
      
      $sql = "SELECT * FROM `tbi_file` WHERE position IN ('ผลงาน')";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo '<div class="col-wd-12 col-md-6 col-sm-4">';
              echo '  <div class="card">';
              echo '    <img class="popup-trigger" src="upload/' . $row['image'] . '" alt="">';
              echo '    <div class="container">';
              echo '      <p>'.$row["name"].'</p>';
              echo '    </div>';
              echo '  </div>';
              echo '</div>';
          }
      } else {
          echo "0 results";
      }

      $conn->close();
      ?>
      <div class="popup-image">
        <span>&times;</span>
        <img src="" alt="">
    </div>
    </div>
  </section></div><br><br>
        <!--contact-->
        <section id="contact" >
          <div>
            <p style="font-size: 30px;"><h2>ช่องทางติดต่อ</h2></p><br>
            <p>ที่อยู่ 68/12 อาคาร CEC ชั้น 7 ถนนกำแพงเพชร 6 แขวงลาดยาว เขตจตุจักร กรุงเทพฯ 10900 </p>
            <p>โทร: 02 589 8882 ถึง 5 ต่อ 147 </p>
            <p>Email: Sales-Marketing@civilengineering.co.th </p>
          </div><!-- End Section Title -->
          <center><div class="row">
            <div class="col-wd-12 col-md-4 col-sm-4">
              <img src="../rework/image/Contact/T.png"   class="center" alt="">
              <p>0962287770</p>
            </div>
            <div class="col-wd-12 col-md-4 col-sm-4">
              <img src="../rework/image/Contact/L.png"  class="center" alt="">
              <p><a href="https://page.line.me/civilproduct" class="stretched-link"> @civilproduct </a></p>
            </div>
            <div class="col-wd-12 col-md-4 col-sm-4">
              <img src="../rework/image/Contact/F.png"   class="center" alt="">
              <p><a href="https://www.facebook.com/profile.php?id=61551204087804" class="stretched-link">CivilServices&Products </a></p>
            </div>
          </div></center><br><br>
            
          
        </section>

      <!-- map-->
      <section id="description" >

      
        <div class="container-box">
          <div class="col-wd-12 col-md-6 col-sm-6">
            <div class="left">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3873.9658893495107!2d100.55430217490783!3d13.841086095220438!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29cedb136881d%3A0xad0a04cf246ce1e8!2z4Lia4Lih4LiILiDguIvguLXguKfguLTguKXguYDguK3guJnguIjguLXguYDguJnguLXguKLguKPguLTguIc!5e0!3m2!1sth!2sth!4v1694770966353!5m2!1sth!2sth" frameborder="0" allowfullscreen></iframe>
              <p style="padding-right: 10%;">การติดต่อในช่องทางนี้ บริษัทอาจได้รับข้อมูลส่วนบุคคลของท่าน ขอให้ท่านศึกษานโยบายคุ้มครองข้อมูลส่วนบุคคลของบริษัทก่อนจะให้ข้อมูลใดๆแก่บริษัท</p>
              <a style="color:#67b0d1;" href="../rework/pdpa1.html"> นโยบายคุ้มครองข้อมูลส่วนบุคคล การติดต่อทางช่องทางออนไลน์</a>
          </div>

          </div>
          <div class="col-wd-12 col-md-6 col-sm-6">
            <div class="right">
              <form id="contactForm">
              <center><h1 style="color:#4b4b4b;">ติดต่อ</h1></center><br>
                <input type="text" class="field" name="name" placeholder="ชื่อของท่าน" required>
                <input type="email" class="field" name="email" placeholder="อีเมลของท่าน" required>
                <br><input type="text" class="field" name="subject" placeholder="เรื่อง" required>
                <br><textarea class="field" placeholder="ข้อความ" name="message" required></textarea>
                <br><center><button type="submit" class="btn" >ส่ง</button></center>
              </form>
            </div>
          </div>
        </div>
        
      </section>
      <script>
        document.getElementById('contactForm').addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(this);

      fetch('sendmsg.php', {
        method: 'POST',
          body: formData,
          headers: {
              'Accept': 'application/json',
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.status === 'success') {
              alert(data.message);
              document.getElementById('contactForm').reset();
          } else {
              alert(data.message);
          }
      })
      .catch(error => {
          alert('เกิดข้อผิดพลาดในการส่งข้อความ');
          console.error('Error:', error);
      });
  });
  document.querySelectorAll('.popup-trigger').forEach(image => {
            image.onclick = () => {
                document.querySelector('.popup-image').style.display = 'block';
                document.querySelector('.popup-image img').src = image.getAttribute('src');
            }
        });

        document.querySelector('.popup-image span').onclick = () => {
            document.querySelector('.popup-image').style.display = 'none';
        }
      </script>

     <!-- ======= Footer ======= -->
<footer id="footer">

  <div class="footer-top">
      <div class="container">
        <h1 style="font-size:35px";>CiviLService&Product</h1>
        <br>
      </div>
     

      <div class="credits-left">
        <p >© สงวนลิขสิทธิ์ การใช้เว็บไซต์นี้หมายความว่าคุณยอมรับ <span style="float: right;"><a href="../rework/pdpa1.html">นโยบายคุ้มครองข้อมูลส่วนบุคคล</a>
  |      <a href="../rework/requirements.html">ข้อกำหนดและเงื่อนไข</a> | <a href="../rework/cookie.html" >นโยบายการใช้คุกกี้</a></span>
  </div>

</footer>
<!-- End Footer -->

    </body>

</body>

</html>