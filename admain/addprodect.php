<?php
session_start();

$host="localhost:3307";
$username="root";
$password="";
$dbname="task";

$conn=mysqli_connect($host, $username, $password, $dbname);

if(!$conn) {
    echo "لم يتم الاتصال بقاعدة البيانات";
} // Correct path
?>
<?php
$proname = $_POST['proname'] ?? null;
$proprice = $_POST['proprice'] ?? null;
$prodescipr = $_POST['prodescipr'] ?? null;
$proquentity = $_POST['proquentity'] ?? null;
$prouny = $_POST['prouny'] ?? null;
$video_link = $_POST['video_link'] ?? null;
$proadd = $_POST['proadd'] ?? null;

if (isset($proadd)) {
    // Validate fields are not empty
    if (empty($proname) || empty($proprice) || empty($prodescipr) || empty($proquentity) || empty($prouny) || empty($video_link)) {
        echo '<script>alert("الرجاء ملىء جميع الحقول ");</script>';
    } else {
        $proimg = null;
        $img_1 =null;
        $img_2 =null;
        $img_3 =null; // Initialize image variable

       // دالة لتحميل الصورة
function uploadImage($fileFieldName) {
    if (isset($_FILES[$fileFieldName]) && $_FILES[$fileFieldName]['error'] == 0) {
        $imageName = $_FILES[$fileFieldName]['name'];
        $imageTmp = $_FILES[$fileFieldName]['tmp_name'];
        $uniqueImageName = rand(0, 5000) . "_" . $imageName;

        // حاول نقل الصورة إلى المجلد
        if (move_uploaded_file($imageTmp, "../uploads/img/" . $uniqueImageName)) {
            return $uniqueImageName; // إرجاع اسم الصورة الفريدة
        } else {
            echo '<script>alert("فشل في نقل الصورة: ' . $imageName . '");</script>';
        }
    } else {
        echo '<script>alert("لم يتم تحميل الصورة: ' . $fileFieldName . '");</script>';
    }
    return null; // إرجاع null إذا فشل التحميل
}

// معالجة تحميل الصور
$proimg = uploadImage('proimg');
$img_1 = uploadImage('img_1');
$img_2 = uploadImage('img_2');
$img_3 = uploadImage('img_3');

// الآن يمكنك استخدام المتغيرات $proimg, $img_1, $img_2, $img_3 حسب الحاجة


        // Ensure the $proimg variable is not empty before inserting
        if ($proimg) {
            // Insert product into database
            $query = "INSERT INTO product (proname, proprice, prodescipr, proquentity, prouny, video_link, proimg , img_1 , img_2 , img_3) 
                      VALUES ('$proname', '$proprice', '$prodescipr', '$proquentity', '$prouny', '$video_link', '$proimg','$img_1','$img_2','$img_3')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                // إعادة التوجيه بعد الإضافة لمنع التكرار عند إعادة تحميل الصفحة
                header("Location: addprodect.php?success=1");
                exit();
            } else {
                echo '<script>alert("لم يتم اضافة المنتج");</script>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة منتج</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
    <center>
        <main>
            <div class="dorm-product">
                <h1>إضافة منتج</h1>

                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">تمت إضافة المنتج بنجاح!</div>
                <?php endif; ?>

                <form action="addprodect.php" method="POST" enctype="multipart/form-data">
                    <label for="proname">اسم المنتج:</label>
                    <input type="text" id="proname" name="proname" required><br><br>

                    <label for="proimg">اختر صورة الرئيسية المنتج:</label>
                    <input type="file" name="proimg" id="proimg" required><br><br>

                    <label for="img_1">اختر صورة الاولة المنتج:</label>
                    <input type="file" name="img_1" id="img_1" required><br><br>

                    <label for="img_2">اختر صورة الثانية المنتج:</label>
                    <input type="file" name="img_2" id="img_2" required><br><br>

                    <label for="img_3">اختر صورة الثالثة المنتج:</label>
                    <input type="file" name="img_3" id="img_3" required><br><br>

                    <label for="proprice">سعر المنتج:</label>
                    <input type="text" id="proprice" name="proprice" required><br><br>

                    <label for="prodescipr">وصف المنتج:</label>
                    <textarea id="prodescipr" name="prodescipr" required></textarea><br><br>

                    <label for="proquentity">كمية المنتج:</label>
                    <input type="number" id="proquentity" name="proquentity" required><br><br>

                    <label for="prouny">توفر المنتج:</label>
                    <input type="text" id="prouny" name="prouny" required><br><br>

                    <label for="link">رابط الفيديوا:</label>
                    <input type="text" name="video_link" id="video"><br><br>

                    <button class="button" type="submit" name="proadd">إضافة المنتج</button>
                </form>
            </div>
        </main>
    </center>
</body>
</html>
