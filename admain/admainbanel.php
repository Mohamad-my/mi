<?php
session_start();

$host="localhost:3307";
$username="root";
$password="";
$dbname="task";

$conn=mysqli_connect($host, $username, $password, $dbname);

if(!$conn) {
    echo "لم يتم الاتصال بقاعدة البيانات";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>لوحة التحكم</title>
</head>
<body>

<?php
if (!isset($_SESSION['EMAIL'])) {
    header('Location: ../index.php');
    exit(); // تأكد من إيقاف التنفيذ بعد التوجيه
} else {
    // التحقق من إرسال النموذج وإدخال البيانات بشكل صحيح
    if (isset($_POST['secadd'])) {
        $sectionname = mysqli_real_escape_string($conn, $_POST['sectionname']); // استخدم هذه الطريقة لتفادي الاختراقات

        if (empty($sectionname)) {
            echo '<script>alert("الحقل فارغ الرجاء ملىء الحقل");</script>';
        } elseif (strlen($sectionname) > 50) { // استخدم strlen للتحقق من طول النص
            echo '<script>alert("اسم القسم طويل جدا");</script>';
        } else {
            $query = "INSERT INTO section (sectionname) VALUES ('$sectionname')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo '<script>alert("تمت إضافة القسم بنجاح"); window.location.href="admainbanel.php";</script>'; // استخدم window.location لإعادة التوجيه بعد الـ alert
                exit();
            } else {
                echo '<script>alert("حدث خطأ أثناء إضافة القسم");</script>';
            }
        }
    }
?>
<?php
   @$id =$_GET['id'];
   if(isset($id)){
    $query="DELETE FROM section WHERE id='$id' ";
    $delet =mysqli_query($conn,$query);
    if(isset($delet)){
        echo '<script> alert("تم الحذف بنجاح")</script>';
    }else{
        echo '<script>alert("لم يتم حذف القسم")</script>';
    }
   }

?>
<!---sidebar start--->
<div class="sidebar-container">
    <div class="sidebar">
        <h1>لوحة تحكم </h1>
        <ul>
            <li><a href="../index.html" target="_blank">الصفحه الرئيسية<i class="bi bi-house-fill"></i></a></li>
            <li><a href="product.php" target="_blank">الصفحه المنتجات <i class="bi bi-clipboard2-pulse-fill"></i></a></li>
            <li><a href="addprodect.php" target="_blank">اضافة منتج <i class="bi bi-folder-plus"></i></a></li>
            <li><a href="../index.php" target="_blank">معلومات الاعضاء <i class="bi bi-people-fill"></i></a></li>
            <li><a href="save_order.php" target="_blank">طلبات الزبائن <i class="bi bi-folder2-open"></i></a></li>
            <li><a href="logout.php" target="_blank">تسجيل الخروج <i class="bi bi-box-arrow-in-right"></i></a></li>
        </ul>
    </div>

    <!--section start-->
    <div class="content-sec">
        <form action="admainbanel.php" method="post">
            <label for="section">اضافة قسم جديد</label>
            <input type="text" name="sectionname" id="section" required>
            <br>
            <button class="add" type="submit" name="secadd">اضافة جهاز</button>
        </form>
        <br>

        <table dir="rtl">
            <tr>
                <th>الرقم التسلسلي</th>
                <th>اسم القسم</th>
                <th>حذف القسم</th>
            </tr>
            <?php
            $query = "SELECT * FROM section";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?> </td>
                    <td><?php echo $row['sectionname']; ?></td>
                    <td><a href="admainbanel.php?id=<?php echo $row['id']; ?>">
                        <button type="button" class="delet">حذف الجهاز</button></a></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <!--section end-->
</div>
<!---sidebar end--->
<?php
} // نهاية else
?>
</body>
</html>
