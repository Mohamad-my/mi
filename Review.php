<?php
// إعدادات الاتصال بـ Supabase
$supabase_url = "https://nlszbtvnyniqdokpubbq.supabase.co"; 
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5sc3pidHZueW5pcWRva3B1YmJxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzAyMjM5NDYsImV4cCI6MjA0NTc5OTk0Nn0.nXmb3WE-cEZqTrqGANth0yI363S2_s_T812roEKTc4I";

// إضافة مراجعة جديدة
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $text = trim($_POST['text']);
    $rating = (int)$_POST['rating'];

    if (empty($name) || empty($text) || $rating < 1 || $rating > 5) {
        echo "يرجى ملء جميع الحقول بشكل صحيح.";
        exit;
    }

    $data = [
        "name" => $name,
        "text" => $text,
        "rating" => $rating
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$supabase_url/rest/v1/reviews");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $supabase_key",
        "apikey: $supabase_key",
        "Prefer: return=representation"
    ]);

    $result = curl_exec($ch);
    if ($result === false) {
        echo "حدث خطأ أثناء إضافة المراجعة: " . curl_error($ch);
    } else {
        echo "تم إضافة المراجعة بنجاح!";
        // إعادة التوجيه إلى نفس الصفحة لمنع إعادة الإرسال عند إعادة التحميل
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    curl_close($ch);
}

// استرجاع المراجعات من قاعدة البيانات
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$supabase_url/rest/v1/reviews?select=*");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $supabase_key",
    "apikey: $supabase_key"
]);

$result = curl_exec($ch);
if ($result === false) {
    echo "حدث خطأ أثناء استرجاع المراجعات: " . curl_error($ch);
    $reviews = [];
} else {
    $reviews = json_decode($result, true);
}
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة الآراء والتعليقات</title>
    <link rel="icon" href="img/4660619.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMwKcIkRb2tK4i4cChmEykV/Gi+vI2Dg8qZZT5" crossorigin="anonymous">

    <style>
          body {
    background-color: #f5f5f5;
    font-family: 'Arial', sans-serif;
    color: #333;
}

.navbar {
            background-color: #333;
            padding: 8px;
        }

        .navbar-brand {
            color:#fff !important;
            font-size: 28px;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #fff;
            margin-right: 20px;
            font-size: 18px;
        }

        .navbar-nav .nav-link:hover {
            color:gray;
        }

    .navbar-toggler:hover {
    background-color: transparent; /* أو أي لون تريده */
    border-color: transparent; /* لإزالة الحدود إذا كانت موجودة */
}

.form-group label {
    font-size: 18px;
    font-weight: bold;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 16px;
    transition: border-color 0.3s; /* تحسين تجربة المستخدم عند التركيز */
}

.form-control:focus {
    border-color: #28a745; /* تغيير لون الحدود عند التركيز */
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); /* ظل خفيف عند التركيز */
}

.star-rating {
    direction: rtl;
    display: inline-flex;
    justify-content: flex-end;
    margin-bottom: 10px; /* إضافة مسافة أسفل التقييم */
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    font-size: 30px;
    color: #ccc;
    cursor: pointer;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #f5b301;
}

.btn-primary {
    background-color: #28a745;
    border: none;
    padding: 10px 20px;
    font-size: 18px;
    border-radius: 10px;
    transition: background-color 0.3s; /* تحسين تجربة المستخدم */
}

.btn-primary:hover {
    background-color: #218838;
}

.reviews-container {
    margin-top: 30px;
    max-width: 600px; /* التحكم في عرض قسم المراجعات */
    margin-left: auto;
    margin-right: auto; /* مركز القسم في الصفحة */
}

.review-card {
    margin-bottom: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s; /* تحسين تجربة المستخدم */
}

.review-card:hover {
    transform: scale(1.02); /* تأثير تكبير عند التمرير */
}

.review-card .card-header {
    background-color: #333;
    color: #fff;
    font-weight: bold;
    font-size: 18px;
    border-radius: 10px 10px 0 0;
    padding: 15px;
}

.review-card .card-header span {
    color: #f5b301;
}

.review-card .card-body {
    padding: 20px;
    font-size: 16px;
}

.card-text {
    margin-bottom: 10px;
    color: #555;
}

.card .card-body .rating {
    font-size: 20px;
    color: #f5b301;
}

h2 {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 20px;
}

.navbar-title:hover {
    color: #898989;
}

.review-form-container {
    margin-top: 30px; /* المسافة من الأعلى */
    margin-left: auto;
    margin-right: auto; /* مركز القسم في الصفحة */
    width: 100%; /* اجعل العرض 100% */
    max-width: 500px; /* عرض أقصى للنموذج */
}

@media (max-width: 768px) {
    .review-card {
        width: 100%; /* اجعل البطاقة تأخذ كامل عرض الشاشة */
    }

    .review-rating {
        font-size: 18px; /* ضبط حجم الخط للتقييمات */
    }

    .review-author {
        font-size: 16px; /* ضبط حجم الخط للاسم */
    }
}

        .form-group label {
            font-size: 18px;
            font-weight: bold;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 16px;
        }

        .star-rating {
            direction: rtl;
            display: inline-flex;
            justify-content: flex-end;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f5b301;
        }

        .btn-primary {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .reviews-container {
            margin-top: 30px;
            max-width: 600px; /* التحكم في عرض قسم المراجعات */
            margin-left: auto;
            margin-right: auto; /* مركز القسم في الصفحة */
        }

        .review-card {
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .review-card .card-header {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            border-radius: 10px 10px 0 0;
            padding: 15px;
        }
        .review-card .card-header  span{
            color:#f5b301;
        }

        .review-card .card-body {
            padding: 20px;
            font-size: 16px;
        }

        .card-text {
            margin-bottom: 10px;
            color: #555;
        }

        .card .card-body .rating {
            font-size: 20px;
            color:#f5b301;
        }

        h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .navbar-title:hover {
            color: #898989;
        }

        .review-form-container {
        margin-top: 30px; /* المسافة من الأعلى */
        margin-left: auto;
        margin-right: auto; /* مركز القسم في الصفحة */
        width: 100%; /* اجعل العرض 100% */
        max-width: 500px; /* عرض أقصى للنموذج */
    }
       

        </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

   <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(43, 42, 42);">
    <div class="container">
        <a class="navbar-brand" href="#" style="display: flex; align-items: center;">
            <span class="navbar-title" style="font-family: monospace">Elderlymed Devices</span>
            <img src="img/p1.jpg" alt="Logo" style="width: 80px; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-left: 10px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="About_us.html">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Review.php">Review</a>
                </li>
            </ul>
            <div class="ms-3"> <!-- إضافة أيقونات التواصل الاجتماعي هنا -->
                <a href="https://facebook.com" target="_blank" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="https://wa.me/" target="_blank" class="text-white"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</nav>


    <!-- Display Reviews -->
    <div class="container my-5">
        <h2>Reviews</h2>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <?php echo htmlspecialchars($review['name']); ?> 
                        <span><?php echo str_repeat('★', (int)$review['rating']); ?></span>
                    </div>
                    <div class="card-body">
                        <p><?php echo htmlspecialchars($review['text']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>لا توجد مراجعات بعد.</p>
        <?php endif; ?>
    </div><br>

    <!-- Review Form -->
    <div class="container">
        <form action="Review.php" method="POST">
            <div class="form-group">
                <label for="reviewerName">Your Name</label>
                <input type="text" name="name" id="reviewerName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reviewText">Your Review</label>
                <textarea name="text" id="reviewText" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
