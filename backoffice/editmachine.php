<?php 

require_once('connection1.php');

if (isset($_REQUEST['update_id'])) {
    try {
        $id = $_REQUEST['update_id'];
        $select_stmt = $db->prepare('SELECT * FROM product WHERE id = :id');
        $select_stmt->bindParam(":id", $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $product_name = $row['product_name'];
            $type = $row['type'];
            $size = $row['size'];
        }
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
}   

if (isset($_REQUEST['btn_update'])) {
    try {
        $product_name = $_REQUEST['txt_product_name'];
        $type = $_REQUEST['txt_type'];
        $size = $_REQUEST['txt_size'];

        if (!isset($errorMsg)) {
            $update_stmt = $db->prepare("UPDATE product SET product_name = :product_name_up, type = :type_up, size = :size_up WHERE id = : id");
            $update_stmt->bindParam(':product_name_up', $product_name);
            $update_stmt->bindParam(':type_up', $type);
            $update_stmt->bindParam(':size_up', $size);
            $update_stmt->bindParam(':id', $id);

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
    <link rel="stylesheet" href="testmacstyle.css">
</head>
<body>

    <div class="form-container container text-center">
        <h1>Edit Machine</h1>
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

        <form id="productForm" action="" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <label for="productName" class="col-sm-3 control-label">Machine Name</label>
                    <div class="col-sm-9">
                        <input type="text" name="txt_productName" class="form-control" value="<?php echo $product_name; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="type" class="col-sm-3 control-label">Machine Type</label>
                    <div class="col-sm-9">
                        <select name="txt_type" class="form-control">
                            <option value="">Select Machine Type</option>
                            <option value="รถแบคโฮ ล้อแทร๊ค" <?php if ($type == "รถแบคโฮ ล้อแทร๊ค") echo 'selected'; ?>>รถแบคโฮ ล้อแทร๊ค</option>
                            <option value="รถแบคโฮ บูมยาว 12-15 เมตร" <?php if ($type == "รถแบคโฮ บูมยาว 12-15 เมตร") echo 'selected'; ?>>รถแบคโฮ บูมยาว 12-15 เมตร</option>
                            <option value="รถแบคโฮ ล้อยาง" <?php if ($type == "รถแบคโฮ ล้อยาง") echo 'selected'; ?>>รถแบคโฮ ล้อยาง</option>
                            <option value="รถแบคโฮ ติดอุปกรณ์เสริมเศษ" <?php if ($type == "รถแบคโฮ ติดอุปกรณ์เสริมเศษ") echo 'selected'; ?>>รถแบคโฮ ติดอุปกรณ์เสริมเศษ</option>
                            <option value="รถบรรทุก 10 ล้อดั้มพ์" <?php if ($type == "รถบรรทุก 10 ล้อดั้มพ์") echo 'selected'; ?>>รถบรรทุก 10 ล้อดั้มพ์</option>
                            <option value="รถบรรทุก 10 ล้อติดเครน" <?php if ($type == "รถบรรทุก 10 ล้อติดเครน") echo 'selected'; ?>>รถบรรทุก 10 ล้อติดเครน</option>
                            <option value="รถเกรดเดอร์" <?php if ($type == "รถเกรดเดอร์") echo 'selected'; ?>>รถเกรดเดอร์</option>
                            <option value="รถเครน ขนาด 4 ล้อ" <?php if ($type == "รถเครน ขนาด 4 ล้อ") echo 'selected'; ?>>รถเครน ขนาด 4 ล้อ</option>
                            <option value="รถบดประเภท ล้อเรียบ - เปลือกหนาม" <?php if ($type == "รถบดประเภท ล้อเรียบ - เปลือกหนาม") echo 'selected'; ?>>รถบดประเภท ล้อเรียบ - เปลือกหนาม</option>
                            <option value="รถบดประเภท ล้อหนาม" <?php if ($type == "รถบดประเภท ล้อหนาม") echo 'selected'; ?>>รถบดประเภท ล้อหนาม</option>
                            <option value="รถบดประเภท ล้อยาง 9 ล้อ" <?php if ($type == "รถบดประเภท ล้อยาง 9 ล้อ") echo 'selected'; ?>>รถบดประเภท ล้อยาง 9 ล้อ</option>
                            <option value="รถกระเช้า ขนาด 20m" <?php if ($type == "รถกระเช้า ขนาด 20m") echo 'selected'; ?>>รถกระเช้า ขนาด 20m</option>
                            <option value="รถกระเช้า ขนาด 40m" <?php if ($type == "รถกระเช้า ขนาด 40m") echo 'selected'; ?>>รถกระเช้า ขนาด 40m</option>
                            <option value="รถแทรกเตอร์" <?php if ($type == "รถแทรกเตอร์") echo 'selected'; ?>>รถแทรกเตอร์</option>
                            <option value="รถกระบะตอนครึ่ง" <?php if ($type == "รถกระบะตอนครึ่ง") echo 'selected'; ?>>รถกระบะตอนครึ่ง</option>
                            <option value="รถกระบะ 4 ประตู" <?php if ($type == "รถกระบะ 4 ประตู") echo 'selected'; ?>>รถกระบะ 4 ประตู</option>
                            <option value="รถบรรทุกน้ำ" <?php if ($type == "รถบรรทุกน้ำ") echo 'selected'; ?>>รถบรรทุกน้ำ</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="size" class="col-sm-3 control-label">Machine Size</label>
                    <div class="col-sm-9">
                        <select name="txt_size" class="form-control">
                            <option value="">Not Displayed</option>
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
                    <a href="testmac.php" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>