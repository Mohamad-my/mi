<?php
include('file/header.php'); // تأكد من تضمين الاتصال بقاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $change = $_POST['change']; // الكمية المضافة أو المزالة

    // استعلام لجلب كمية المنتج الحالية
    $query = "SELECT prouny FROM product WHERE id='$product_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        $current_quantity = $product['prouny'];

        // تحديث الكمية
        $new_quantity = $current_quantity + $change;
        $updateQuery = "UPDATE product SET prouny='$new_quantity' WHERE id='$product_id'";
        mysqli_query($conn, $updateQuery);

        echo "تم تحديث الكمية بنجاح";
    } else {
        echo "لم يتم العثور على المنتج.";
    }
}
?>
