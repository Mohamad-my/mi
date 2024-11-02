<?php
session_start();
if(isset($_SESSION["name"])){
    $uuname=$_SESSION['name'];
}
else{
    header("refresf:../header.php");
}
$name=$_GET['n'];
$p=$_Get['p'];

$conn=mysqli_connect("localhost","root","","task");

$query=mysqli_query($conn,"insert into product(proname,proprice)values('$name','$p','$uuname'");

if($query){
    header('Localtion:index,php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>سلة التسوق</title>
    
</head>
<body>

<div class="cart">
    <h2>سلة التسوق</h2>

    <!-- عرض المنتجات في السلة -->
    <?php if (!empty($cart_items)) { ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>المجموع</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item) { 
                    $total_price += $item['price'] * $item['quantity']; ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo $item['price']; ?> JD</td>
                        <td><?php echo $item['price'] * $item['quantity']; ?> JD</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- إجمالي السعر -->
        <div class="cart-total">
            <h3>الإجمالي: <?php echo $total_price; ?> JD</h3>
        </div>

        <!-- أزرار تأكيد وإفراغ السلة -->
        <div class="cart-buttons">
            <form method="POST" action="process_cart.php" style="display: inline-block;">
                <button type="submit" name="confirm" class="btn-confirm">Confirm</button>
            </form>

            <form method="POST" action="process_cart.php" style="display: inline-block;">
                <button type="submit" name="empty" class="btn-empty">Empty Cart</button>
            </form>
        </div>
    <?php } else { ?>
        <p>سلة التسوق فارغة.</p>
    <?php } ?>
</div>

</body>
</html>
