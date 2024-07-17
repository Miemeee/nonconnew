<?php 

require_once('connection1.php');

if (isset($_REQUEST['update_id'])) {
    try {
        $id = $_REQUEST['update_id'];
        $select_stmt = $db->prepare('SELECT * FROM tbi_file WHERE id = :id');
        $select_stmt->bindParam(":id", $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
    } catch(PDOException $e) {
        $e->getMessage();
    }
}   

if (isset($_REQUEST['btn_update'])) {
    try {
        $name = $_REQUEST['txt_name'];
        $category = $_REQUEST['txt_category'];
        $position = $_REQUEST['txt_position'];

        $image_file = $_FILES['txt_file']['name'];
        $type = $_FILES['txt_file']['type'];
        $size = $_FILES['txt_file']['size'];
        $temp = $_FILES['txt_file']['tmp_name'];

        $path = "upload/" . $image_file;
        $directory = "upload/"; // set upload folder path for update time previous file remove and new file upload for next use

        if ($image_file) {
            if ($type == "image/jpg" || $type == 'image/jpeg' || $type == "image/png" || $type == "image/gif") {
                if (!file_exists($path)) { // check file not exist in your upload folder path
                    if ($size < 5000000) { // check file size 5MB
                        unlink($directory . $row['image']); // unlink function remove previous file
                        move_uploaded_file($temp, $path); // move upload file temporary directory to your upload folder
                    } else {
                        $errorMsg = "Your file is too large. Please upload a file smaller than 5MB.";
                    }
                } else {
                    $errorMsg = "File already exists... Check upload folder";
                }
            } else {
                $errorMsg = "Upload JPG, JPEG, PNG & GIF formats...";
            }
        } else {
            $image_file = $row['image']; // if you do not select a new image, the previous image remains as it is.
        }

        if (!isset($errorMsg)) {
            $update_stmt = $db->prepare("UPDATE tbi_file SET name = :name_up, image = :file_up, category = :category_up, position = :position_up WHERE id = :id");
            $update_stmt->bindParam(':name_up', $name);
            $update_stmt->bindParam(':file_up', $image_file);
            $update_stmt->bindParam(':category_up', $category);
            $update_stmt->bindParam(':position_up', $position);
            $update_stmt->bindParam(':id', $id);

            if ($update_stmt->execute()) {
                $updateMsg = "File updated successfully...";
                header("refresh:2;test.php");
            }
        }

    } catch(PDOException $e) {
        $e->getMessage();
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
        <h1>แก้ไขรูปภาพ</h1>
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
                    <label for="name" class="col-sm-3 control-label">ชื่อภาพ</label>
                    <div class="col-sm-9">
                        <input type="text" name="txt_name" class="form-control" value="<?php echo $name; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="category" class="col-sm-3 control-label">ประเภทของภาพ</label>
                    <div class="col-sm-9">
                        <select name="txt_category" class="form-control">
                            <option value="">เลือกประเภทของภาพ</option>
                            <option value="segment" <?php if($category == 'segment') echo 'selected'; ?>>segment</option>
                            <option value="sheild" <?php if($category == 'sheild') echo 'selected'; ?>>sheild</option>
                            <option value="alphalt" <?php if($category == 'alphalt') echo 'selected'; ?>>alphalt</option>
                            <option value="rock" <?php if($category == 'rock') echo 'selected'; ?>>rock</option>
                            <option value="concreet" <?php if($category == 'concreet') echo 'selected'; ?>>concreet</option>
                            <option value="portfolio" <?php if($category == 'portfolio') echo 'selected'; ?>>portfolio</option>
                            <option value="เครื่องจักร" <?php if($category == 'เครื่องจักร') echo 'selected'; ?>>เครื่องจักร</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="position" class="col-sm-3 control-label">ตำแหน่งที่ต้องการแสดงภาพ</label>
                    <div class="col-sm-9">
                        <select name="txt_position" class="form-control">
                            <option value="">เลือกตำแหน่งของภาพ</option>
                            <option value="สินค้า" <?php if($position == 'สินค้า') echo 'selected'; ?>>สินค้า</option>
                            <option value="ผลงาน" <?php if($position == 'ผลงาน') echo 'selected'; ?>>ผลงาน</option>
                            <option value="ปกเครื่องจักร" <?php if($position == 'ปกเครื่องจักร') echo 'selected'; ?>>ปกเครื่องจักร</option>
                            <option value="รถแบคโฮ" <?php if($position == 'รถแบคโฮ') echo 'selected'; ?>>รถแบคโฮ</option>
                            <option value="รถบรรทุก" <?php if($position == 'รถบรรทุก') echo 'selected'; ?>>รถบรรทุก</option>
                            <option value="รถเกรดเดอร์" <?php if($position == 'รถเกรดเดอร์') echo 'selected'; ?>>รถเกรดเดอร์</option>
                            <option value="รถเครน" <?php if($position == 'รถเครน') echo 'selected'; ?>>รถเครน</option>
                            <option value="รถบด" <?php if($position == 'รถบด') echo 'selected'; ?>>รถบด</option>
                            <option value="รถกระเช้า" <?php if($position == 'รถกระเช้า') echo 'selected'; ?>>รถกระเช้า</option>
                            <option value="รถแทรกเตอร์" <?php if($position == 'รถแทรกเตอร์') echo 'selected'; ?>>รถแทรกเตอร์</option>
                            <option value="รถกระบะ" <?php if($position == 'รถกระบะ') echo 'selected'; ?>>รถกระบะ</option>
                            <option value="รถน้ำ" <?php if($position == 'รถน้ำ') echo 'selected'; ?>>รถน้ำ</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="file" class="col-sm-3 control-label">อัพโหลดไฟล์ภาพ</label>
                    <div class="col-sm-9">
                        <input type="file" name="txt_file" class="form-control">
                        <p>
                            <img src="upload/<?php echo $image; ?>" height="100" width="100" alt="">
                        </p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" name="btn_update" class="btn btn-primary" value="Update">
                    <a href="adddelete.php" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
