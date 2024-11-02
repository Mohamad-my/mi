<?php
session_start();

// التحقق مما إذا تم إرسال الطلب عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // جلب بيانات المنتج من النموذج
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];

    // التحقق مما إذا كانت الجلسة تحتوي على سلة تسوق
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];  // إنشاء السلة إذا لم تكن موجودة
    }

    // التحقق مما إذا كان المنتج موجودًا في السلة بالفعل
    $product_found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $product_quantity;  // زيادة الكمية
            $product_found = true;
            break;
        }
    }

    // إذا لم يكن المنتج موجودًا في السلة، أضفه
    if (!$product_found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity
        ];
    }

    // إعادة توجيه المستخدم إلى صفحة السلة أو الصفحة السابقة
    header('Location: cart/addcart.php');  // يمكنك إعادة التوجيه إلى صفحة السلة
    exit();
}
