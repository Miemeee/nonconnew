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
        <h1>เพิ่มรูปภาพ</h1>
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
                    <label for="name" class="col-sm-3 control-label">ชื่อภาพ</label>
                    <div class="col-sm-9">
                        <input type="text" name="txt_name" class="form-control" placeholder="กรอกชื่อภาพ">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="category" class="col-sm-3 control-label">ประเภทของภาพ</label>
                    <div class="col-sm-9">
                        <select name="txt_category" class="form-control">
                            <option value="">เลือกประเภทของภาพ</option>
                            <option value="segment">segment</option>
                            <option value="sheild">sheild</option>
                            <option value="alphalt">alphalt</option>
                            <option value="rock">rock</option>
                            <option value="concreet">concreet</option>
                            <option value="portfolio">portfolio</option>
                            <option value="เครื่องจักร">เครื่องจักร</option>
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
                            <option value="สินค้า">สินค้า</option>
                            <option value="ผลงาน">ผลงาน</option>
                            <option value="ปกเครื่องจักร">ปกเครื่องจักร</option>
                            <option value="รถแบคโฮ">รถแบคโฮ</option>
                            <option value="รถบรรทุก">รถบรรทุก</option>
                            <option value="รถเกรดเดอร์">รถเกรดเดอร์</option>
                            <option value="รถเครน">รถเครน</option>
                            <option value="รถบด">รถบด</option>
                            <option value="รถกระเช้า">รถกระเช้า</option>
                            <option value="รถแทรกเตอร์">รถแทรกเตอร์</option>
                            <option value="รถกระบะ">รถกระบะ</option>
                            <option value="รถน้ำ">รถน้ำ</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="file" class="col-sm-3 control-label">อัพโหลดไฟล์ภาพ</label>
                    <div class="col-sm-9">
                        <input type="file" name="txt_file" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" name="btn_insert" class="btn btn-success" value="Insert">
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
