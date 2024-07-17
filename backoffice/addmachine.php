<?php 

require_once('connection1.php');

if (isset($_REQUEST['btn_insert'])) {
    try {
        $productName = $_REQUEST['txt_productName'];
        $category = $_REQUEST['txt_category'];
        $size = $_REQUEST['txt_size'];

        if (empty($productName)) {
            $errorMsg = "Please Enter product name";
        } else if (empty($category)) {
            $errorMsg = "Please Enter category";
        } 

        if (!isset($errorMsg)) {
            $type = '';
            $modal = 0;
            $col = 0;

            if ($category == 'รถแบคโฮ ล้อแทร๊ค' || $category == 'รถแบคโฮ บูมยาว 12-15 เมตร' || $category == 'รถแบคโฮ ล้อยาง' || $category == 'รถแบคโฮ ติดอุปกรณ์เสริมเศษ' ) {
                $type = 'รถแบคโฮ';
                $modal = 1;
                if ($size == 'ขนาด 3-7.5 ตัน') {
                    $col = 1;
                } else if ($size == 'ขนาด 14-23 ตัน') {
                    $col = 2;
                } else if ($size == 'ขนาด 29-38 ตัน') {
                    $col = 3;
                } else if ($size == 'ติดอุปกรณ์เสริมพิเศษ') {
                    $col = 4;
                }
            } else if ($category == 'รถบรรทุก 10 ล้อดั้มพ์') {
                $type = 'รถบรรทุก';
                $modal = 2;
                $col = 1;
            } else if ($category == 'รถบรรทุก 10 ล้อติดเครน') {
                $type = 'รถบรรทุก';
                $modal = 2;
                $col = 2;
            } else if ($category == 'รถเกรดเดอร์') {
                $type = 'รถเกรดเดอร์';
                $modal = 3;
                $col = 1;
            } else if ($category == 'รถเครน ขนาด 4 ล้อ') {
                $type = 'รถเครน';
                $modal = 4;
                $col = 1;
            } else if ($category == 'รถบดประเภท ล้อเรียบ - เปลือกหนาม' || $category == 'รถบดประเภท ล้อหนาม') {
                $type = 'รถบด';
                $modal = 10;
                $col = 1;
            } else if ($category == 'รถบดประเภท ล้อยาง 9 ล้อ') {
                $type = 'รถบด';
                $modal = 10;
                $col = 2;
            } 
              
            else if ($category == 'รถกระเช้า ขนาด 20m' ) {
                $type = 'รถกระเช้า';
                $modal = 6;
                $col = 1;
            } else if ($category == 'รถกระเช้า ขนาด 40m' ) {
                $type = 'รถกระเช้า';
                $modal = 6;
                $col = 2;
            } else if ($category == 'รถแทรกเตอร์') {
                $type = 'รถแทรกเตอร์';
                $modal = 7;
                $col = 1;
            } else if ($category == 'รถกระบะตอนครึ่ง') {
                $type = 'รถกระบะ';
                $modal = 8;
                $col = 1;
            } else if ( $category == 'รถกระบะ 4 ประตู') {
                $type = 'รถกระบะ';
                $modal = 8;
                $col = 2;
            } else if ($category == 'รถบรรทุกน้ำ') {
                $type = 'รถบรรทุกน้ำ';
                $modal = 9;
                $col = 1;
            }

            $insert_stmt = $db->prepare('INSERT INTO machinedes(productName, position, additional, type, modal, col) VALUES (:fproductName, :fcategory, :fsize, :ftype, :fmodal, :fcol)');
            $insert_stmt->bindParam(':fproductName', $productName);
            $insert_stmt->bindParam(':fcategory', $category);
            $insert_stmt->bindParam(':ftype', $type);
            $insert_stmt->bindParam(':fmodal', $modal);
            $insert_stmt->bindParam(':fcol', $col);

            if (empty($size)) {
                $insert_stmt->bindValue(':fsize', null, PDO::PARAM_NULL);
            } else {
                $insert_stmt->bindParam(':fsize', $size);
            }

            if ($insert_stmt->execute()) {
                $insertMsg = "Uploaded successfully...";
                header('refresh:2;machine.php');
            }
        }

    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <div class="container text-center">
        <h1>เพิ่มข้อมูลเครื่องจักร</h1>
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
                <div class="row">
                    <label for="productName" class="col-sm-3 control-label">ชื่อเครื่องจักร</label>
                    <div class="col-sm-9">
                        <input type="text" name="txt_productName" class="form-control" placeholder="กรอกชื่อเครื่องจักร">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="category" class="col-sm-3 control-label">ประเภทของเครื่องจักร</label>
                    <div class="col-sm-9">
                        <select name="txt_category" class="form-control">
                            <option value="">เลือกประเภทของเครื่องจักร</option>
                            <option value="รถแบคโฮ ล้อแทร๊ค">รถแบคโฮ ล้อแทร๊ค</option>
                            <option value="รถแบคโฮ บูมยาว 12-15 เมตร">รถแบคโฮ บูมยาว 12-15 เมตร</option>
                            <option value="รถแบคโฮ ล้อยาง">รถแบคโฮ ล้อยาง</option>
                            <option value="รถแบคโฮ ติดอุปกรณ์เสริมเศษ">รถแบคโฮ ติดอุปกรณ์เสริมเศษ</option>
                            <option value="รถบรรทุก 10 ล้อดั้มพ์">รถบรรทุก 10 ล้อดั้มพ์</option>
                            <option value="รถบรรทุก 10 ล้อติดเครน">รถบรรทุก 10 ล้อติดเครน</option>
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
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="size" class="col-sm-3 control-label">ขนาดของเครื่องจักร</label>
                    <div class="col-sm-9">
                        <select name="txt_size" class="form-control">
                            <option value="">NULL</option>
                            <option value="ขนาด 3-7.5 ตัน">ขนาด 3-7.5 ตัน</option>
                            <option value="ขนาด 14-23 ตัน">ขนาด 14-23 ตัน</option>
                            <option value="ขนาด 29-38 ตัน">ขนาด 29-38 ตัน</option>
                            <option value="ติดอุปกรณ์เสริมพิเศษ">ติดอุปกรณ์เสริมพิเศษ</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" name="btn_insert" class="btn btn-success" value="Insert">
                    <a href="machine.php" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
