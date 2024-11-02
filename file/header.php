<?php

// إعدادات الاتصال بـ Supabase
$supabase_url = "https://nlszbtvnyniqdokpubbq.supabase.co"; 
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5sc3pidHZueW5pcWRva3B1YmJxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzAyMjM5NDYsImV4cCI6MjA0NTc5OTk0Nn0.nXmb3WE-cEZqTrqGANth0yI363S2_s_T812roEKTc4I";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="website icon" type="png"href="img/4660619.png">
    <title>منتجات</title>
    <style>
    .navbar {
        background-color: #333;
        padding: 8px !important;
    }

    .navbar-brand {
        color: #fff;
        font-size: 28px !important;
        font-weight: bold;
    }

    .navbar-nav .nav-link {
        color: #fff;
        margin-right: 20px;
        font-size: 18px;
    }

    .navbar-nav .nav-link:hover {
        color: gray;
    }

    .navbar-toggler:hover {
    background-color: transparent; /* أو أي لون تريده */
    border-color: transparent; /* لإزالة الحدود إذا كانت موجودة */
}
    .last-post {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        background-color: #e9ecef;
        padding: 10px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }


    .cart ul {
        list-style-type: none;
        display: flex;
        gap: 15px;
        margin: 0;
        padding: 0;
    }

    .cart ul li {
        display: inline-block;
        position: relative;
    }

    .cart ul li a {
        color: #343a40;
        font-size: 1.5rem;
        text-decoration: none;
    }

    .cart ul li .cart-icon {
        position: relative;
    }

    .cart ul li .cart-icon .cart-count {
        position: absolute;
        top: -8px;
        right: -12px;
        background-color: #dc3545;
        color: white;
        font-size: 0.8rem;
        padding: 3px 6px;
        border-radius: 50%;
    }

    body {
        background-color: #f4f4f9;
        font-family: 'Tajawal', sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }

    main {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        padding: 2rem;
        gap: 1.5rem;
        background-color: #e9ecef;
    }

   
/* تنسيق عام للكرت */
.product {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    text-align: center;
    background-color: #ffffff;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    color: #000;
}

.product:hover {
    transform: translateY(-10px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

/* تنسيق صورة المنتج */
.product-img img {
    width: 100%;
    height: 180px; 
    object-fit: contain; 
    border-radius: 10px;
}

/* تنسيق النصوص داخل الكرت */
.product-name, .product-price{
    margin: 12px 0;
    font-size: 1.1rem;
    color: #000; 
}
 .product-description {
    margin: 12px 0;
    font-size: 1.1rem;
    color:darkslategray;
 }


.product-name a, .product-price a{
    color: #000; 
    text-decoration: none;
}
 .product-description a {
    color:#898989;
    text-decoration: none;
 }

.product-name a:hover, .product-price a:hover {
    color: #000; 
}
.product-description a:hover{
    color:#ccc;
}
/* تنسيق للكمية */
.qty-input input {
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 80px;
    text-align: center;
}

/* زر الإضافة إلى السلة */
.addto-cart {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.addto-cart:hover {
    background-color: #218838;
    transform: scale(1.05);
}


.cart-count{
color:red;
position: absolute;
top: -8px;
right: -12px;
background-color: #dc3545;
color: white;
font-size: 0.8rem;
padding: 3px 6px;
 border-radius: 50%;
}
.cart-modal {
    display: flex;
    justify-content: flex-end;
    align-items: flex-start; 
    position: fixed; 
    z-index: 1000; 
    top: 0; 
    right: 0; 
    width: 400px; 
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.6); 
}

.cart-modal-content {
    background: #fff; 
    padding: 20px;
    border-radius: 4px; 
    width: 100%; 
    height: 100%;
    max-width: 100%;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2); 
    position: relative; 
}

.close {
    position: absolute; 
    top: 10px;
    left: 15px; 
    font-size: 24px;
    color: #333; 
    cursor: pointer; 
}

.close:hover {
    color: #ff0000; 
}

h2 {
    text-align: center; 
    color: #007bff; 
}

.cart-items {
    list-style: none; 
    padding: 0; 
    max-height: 200px; 
    overflow-y: auto; 
}

.cart-items li {
    background: #f9f9f9; 
    margin-bottom: 10px; 
    padding: 10px; 
    border-radius: 5px; 
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); 
}

.cart-summary {
    text-align: center; 
    margin-top: 20px; 
}

button {
    background-color:#218838; 
    color: white; 
    border: none;
    border-radius: 5px; 
    padding: 10px 15px; 
    cursor: pointer; 
    transition: background-color 0.3s; 
    margin: 5px; 
}

button:hover {
    background-color:#28a745; 
}
.navbar-title:hover {
    color:#898989;
}

    @media (max-width: 768px) {
        .navbar-brand {
            font-size: 22px;
        }

        .navbar-nav .nav-link {
            font-size: 16px;
        }

        .product-price a {
            font-size: 16px;
        }

        .cart-summary button {
            font-size: 1rem;
        }
        .navbar-toggleCart-icon span:hover{
    color: #000;
        }
    }

   

</style>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(43, 42, 42);">
    <div class="container">
        <a class="navbar-brand" href="#" style="display: flex; align-items: center;">
        <span class="navbar-title" style="font-family:monospace">Elderlymed Devices</span>
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
        </div>
    </div>
</nav>
     <!---------- cart start------------>
     <div class="last-post">
     <div class="cart">
    <ul>
    <li class="hidden"><a href="admain/admin.php"><i class="bi bi-person-circle"></i></a></li>
        <li class="cart-icon">
           <a href="#" onclick="toggleCart()"><i class="bi bi-bag-fill"></i></a>
            <div class="cart-summary">
    <span class="cart-count">0</span> 
</div>
        </li>
    </ul>
   

<!-- نافذة السلة -->
<div class="cart-modal" id="cartModal" style="display: none;">
    <div class="cart-modal-content">
        <span class="close" onclick="toggleCart()">&times;</span>
        <h2>عناصر السلة</h2>
        <ul class="cart-items"></ul> <!-- هذه القائمة سيتم تعبئتها بعناصر السلة -->
        <div class="cart-summary">
            <p>إجمالي العناصر: 
                <span id="totalItems"></span></p> <!-- إجمالي العناصر -->
            <button onclick="emptyCart()">إفراغ السلة</button>
            <button onclick="confermcart()">تأكيد الطلب</button>
        </div>
    </div>
</div>



<script>
    function toggleCart() {
    const cartModal = document.getElementById('cartModal');
    
    // عرض أو إخفاء السلة بناءً على حالتها الحالية
    if (cartModal.style.display === 'none' || cartModal.style.display === '') {
        cartModal.style.display = 'block'; // إظهار السلة
    } else {
        cartModal.style.display = 'none'; // إخفاء السلة
    }
}


    </script>


</div>
        <!---------- cart end------------>

    </div>

    