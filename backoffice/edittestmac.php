<?php
require_once('connection1.php');

if (isset($_REQUEST['update_id'])) {
    try {
        $id = $_REQUEST['update_id'];
        $select_stmt = $db->prepare('SELECT * FROM cover_details WHERE id = :id');
        $select_stmt->bindParam(":id", $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
        
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_REQUEST['btn_update'])) {
    try {
        $cover_title = $_REQUEST['txt_title'];
        $cover_description = $_REQUEST['txt_description'];

        $image_file = $_FILES['txt_file']['name'];
        $type = $_FILES['txt_file']['type'];
        $size = $_FILES['txt_file']['size'];
        $temp = $_FILES['txt_file']['tmp_name'];

        $path = "uploads/" . $image_file;
        $directory = "uploads/"; // set upload folder path for update time previous file remove and new file upload for next use

        if ($image_file) {
            if ($type == "image/jpg" || $type == 'image/jpeg' || $type == "image/png" || $type == "image/gif") {
                if (!file_exists($path)) { // check file not exist in your upload folder path
                    if ($size < 5000000) { // check file size 5MB
                        unlink($directory . $row['cover_image']); // unlink function remove previous file
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
            $image_file = $row['cover_image']; // if you do not select a new image, the previous image remains as it is.
        }

        if (!isset($errorMsg)) {
            $update_stmt = $db->prepare("UPDATE cover_details SET cover_title = :cover_title, cover_image = :cover_image, cover_description = :cover_description WHERE id = :id");
            $update_stmt->bindParam(':cover_title', $cover_title);
            $update_stmt->bindParam(':cover_image', $image_file);
            $update_stmt->bindParam(':cover_description', $cover_description);
            $update_stmt->bindParam(':id', $id);

            if ($update_stmt->execute()) {
                $updateMsg = "File updated successfully...";
                header("refresh:2;testmac.php");
            }
        }

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cover Details</title>
    <link rel="stylesheet" href="testmacstyle.css">
</head>
<body>

    <div class="form-container">
        <h1>Edit Cover Details</h1>
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

        <form id="coverForm" action="" method="post" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <label for="title" class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" name="txt_title" class="form-control" value="<?php echo $cover_title; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="description" class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-9">
                        <input type="text" name="txt_description" class="form-control" value="<?php echo $cover_description; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="file" class="col-sm-3 control-label">Cover Image</label>
                    <div class="col-sm-9">
                        <input type="file" name="txt_file" class="form-control">
                        <p>
                            <img src="<?php echo $cover_image; ?>" height="100" width="100" alt="">
                        </p>
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
