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

