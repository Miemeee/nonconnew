<?php 

require_once('connection1.php');

if (isset($_REQUEST['update_id'])) {
    try {
        $productId = $_REQUEST['update_id'];
        $select_stmt = $db->prepare('SELECT * FROM machinedes WHERE productId = :productId');
        $select_stmt->bindParam(":productId", $productId);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $productName = $row['productName'];
            $category = $row['position'];
            $size = $row['additional'];
        }
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}   

if (isset($_REQUEST['btn_update'])) {
    try {
        $productName = $_REQUEST['txt_productName'];
        $category = $_REQUEST['txt_category'];
        $size = $_REQUEST['txt_size'];

        if (!isset($errorMsg)) {
            $update_stmt = $db->prepare("UPDATE machinedes SET productName = :productName_up, position = :category_up, additional = :size_up WHERE productId = :productId");
            $update_stmt->bindParam(':productName_up', $productName);
            $update_stmt->bindParam(':category_up', $category);
            $update_stmt->bindParam(':size_up', $size);
            $update_stmt->bindParam(':productId', $productId);

            if ($update_stmt->execute()) {
                $updateMsg = "File updated successfully...";
                header("refresh:2;machine.php");
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
    <title>Edit Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>

    <div class="container text-center">
        <h1>แก้ไขข้อมูลเครื่องจักร</h1>
        <?php if(isset($errorMsg)) { ?>
            <div class="alert alert-danger">
                <strong><?php echo $errorMsg; ?></strong>
            </div>
        <?php } ?>

        <?php if(isset($updateMsg)) { ?>
            <div class="alert alert-success">
                <strong><?php echo $updateMsg; ?></strong>
            </div>
        <?php } ?>

        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <label for="productName" class="col-sm-3 control-label">ชื่อเครื่องจักร</label>
                    <div class="col-sm-9">
                        <input type="text" name="txt_productName" class="form-control" value="<?php echo $productName; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="category" class="col-sm-3 control-label">ประเภทของเครื่องจักร</label>
                    <div class="col-sm-9">
                        <select name="txt_category" class="form-control">
                            <option value="">เลือกประเภทของเครื่องจักร</option>
                            <option value="รถแบคโฮ ล้อแทร๊ค" <?php if ($category == "รถแบคโฮ ล้อแทร๊ค") echo 'selected'; ?>>รถแบคโฮ ล้อแทร๊ค</option>
                            <option value="รถแบคโฮ บูมยาว 12-15 เมตร" <?php if ($category == "รถแบคโฮ บูมยาว 12-15 เมตร") echo 'selected'; ?>>รถแบคโฮ บูมยาว 12-15 เมตร</option>
                            <option value="รถแบคโฮ ล้อยาง" <?php if ($category == "รถแบคโฮ ล้อยาง") echo 'selected'; ?>>รถแบคโฮ ล้อยาง</option>
                            <option value="รถแบคโฮ ติดอุปกรณ์เสริมเศษ" <?php if ($category == "รถแบคโฮ ติดอุปกรณ์เสริมเศษ") echo 'selected'; ?>>รถแบคโฮ ติดอุปกรณ์เสริมเศษ</option>
                            <option value="รถบรรทุก 10 ล้อดั้มพ์" <?php if ($category == "รถบรรทุก 10 ล้อดั้มพ์") echo 'selected'; ?>>รถบรรทุก 10 ล้อดั้มพ์</option>
                            <option value="รถบรรทุก 10 ล้อติดเครน" <?php if ($category == "รถบรรทุก 10 ล้อติดเครน") echo 'selected'; ?>>รถบรรทุก 10 ล้อติดเครน</option>
                            <option value="รถเกรดเดอร์" <?php if ($category == "รถเกรดเดอร์") echo 'selected'; ?>>รถเกรดเดอร์</option>
                            <option value="รถเครน ขนาด 4 ล้อ" <?php if ($category == "รถเครน ขนาด 4 ล้อ") echo 'selected'; ?>>รถเครน ขนาด 4 ล้อ</option>
                            <option value="รถบดประเภท ล้อเรียบ - เปลือกหนาม" <?php if ($category == "รถบดประเภท ล้อเรียบ - เปลือกหนาม") echo 'selected'; ?>>รถบดประเภท ล้อเรียบ - เปลือกหนาม</option>
                            <option value="รถบดประเภท ล้อหนาม" <?php if ($category == "รถบดประเภท ล้อหนาม") echo 'selected'; ?>>รถบดประเภท ล้อหนาม</option>
                            <option value="รถบดประเภท ล้อยาง 9 ล้อ" <?php if ($category == "รถบดประเภท ล้อยาง 9 ล้อ") echo 'selected'; ?>>รถบดประเภท ล้อยาง 9 ล้อ</option>
                            <option value="รถกระเช้า ขนาด 20m" <?php if ($category == "รถกระเช้า ขนาด 20m") echo 'selected'; ?>>รถกระเช้า ขนาด 20m</option>
                            <option value="รถกระเช้า ขนาด 40m" <?php if ($category == "รถกระเช้า ขนาด 40m") echo 'selected'; ?>>รถกระเช้า ขนาด 40m</option>
                            <option value="รถแทรกเตอร์" <?php if ($category == "รถแทรกเตอร์") echo 'selected'; ?>>รถแทรกเตอร์</option>
                            <option value="รถกระบะตอนครึ่ง" <?php if ($category == "รถกระบะตอนครึ่ง") echo 'selected'; ?>>รถกระบะตอนครึ่ง</option>
                            <option value="รถกระบะ 4 ประตู" <?php if ($category == "รถกระบะ 4 ประตู") echo 'selected'; ?>>รถกระบะ 4 ประตู</option>
                            <option value="รถบรรทุกน้ำ" <?php if ($category == "รถบรรทุกน้ำ") echo 'selected'; ?>>รถบรรทุกน้ำ</option>
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
                            <option value="ขนาด 3-7.5 ตัน" <?php if ($size == "ขนาด 3-7.5 ตัน") echo 'selected'; ?>>ขนาด 3-7.5 ตัน</option>
                            <option value="ขนาด 14-23 ตัน" <?php if ($size == "ขนาด 14-23 ตัน") echo 'selected'; ?>>ขนาด 14-23 ตัน</option>
                            <option value="ขนาด 29-38 ตัน" <?php if ($size == "ขนาด 29-38 ตัน") echo 'selected'; ?>>ขนาด 29-38 ตัน</option>
                            <option value="ติดอุปกรณ์เสริมพิเศษ" <?php if ($size == "ติดอุปกรณ์เสริมพิเศษ") echo 'selected'; ?>>ติดอุปกรณ์เสริมพิเศษ</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" name="btn_update" class="btn btn-primary" value="Update">
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
