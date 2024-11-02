<?php
// save_order.php

// إعداد معلومات الاتصال بقاعدة البيانات
$host = "localhost:3307"; // تأكد من أن هذا هو عنوان الخادم الصحيح
$username = "root";
$password = "";
$dbname = "task";

// الاتصال بقاعدة البيانات
$conn = new mysqli($host, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من البيانات المرسلة من النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $city = $conn->real_escape_string($_POST['city']);
    $cartItems = json_decode($_POST['cartItems'], true);
    $totalCost = floatval($_POST['totalCost']); // التأكد من أن التكلفة الكلية عدد

    // إدخال الطلب في قاعدة البيانات
    $sql = "INSERT INTO orders (name, address, phone, city, total_cost, cart_items, order_date)
            VALUES ('$name', '$address', '$phone', '$city', '$totalCost', '".json_encode($cartItems)."', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Order submitted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="icon" type="image/png" href="img/4660619.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 600px; margin-top: 50px; }
        .form-control:invalid { border-color: #dc3545; }
        .form-control:valid { border-color: #28a745; }
        .invalid-feedback { display: none; }
        .is-invalid .invalid-feedback { display: block; }
        .total-price { font-weight: bold; margin-top: 20px; }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4 text-center">Order Form</h1><br>
        <div id="datetime"> </div><br><br>
        <form action="save_order.php" method="POST"  id="orderForm">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid name.</div>
            </div>
            <div class="form-group">
                <label for="address">Delivery Address</label>
                <input type="text" id="address" name="address" class="form-control" required>
                <div class="invalid-feedback">Please enter a delivery address.</div>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" required pattern="^07[0-9]{7,9}$" placeholder="07XXXXXXXX">
                <div class="invalid-feedback">Please enter a valid phone number starting with 00962 followed by 7 to 9 digits.</div>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <select id="city" name="city" class="form-control" required>
                    <option value="">Select a city</option>
                    <option value="Amman">Amman</option>
                    <option value="Irbid">Irbid</option>
                    <option value="Zarqa">Zarqa</option>
                    <option value="Karak">Karak</option>
                </select>
                <div class="invalid-feedback">Please select a city.</div>
            </div>
            <div id="cartItems" class="mt-4"  ></div>
            <div class="total-price" id="totalPrice">
                Total Price: $0
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-4">Submit Order</button>
           
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('orderForm');
            const totalPriceElement = document.getElementById('totalPrice');
            const cartItemsElement = document.getElementById('cartItems');

            function getCartItems() {
                const cartItems = localStorage.getItem('cartItems');
                return cartItems ? JSON.parse(cartItems) : [];
            }

            function getCityAdjustment(city) {
                switch (city) {
                    case 'Amman': return 2;
                    case 'Irbid': return 4;
                    case 'Zarqa': return 3;
                    case 'Karak': return 5;
                    default: return 0;
                }
            }

            function displayCartItems(cart) {
                cartItemsElement.innerHTML = ''; 
                if (cart.length === 0) {
                    cartItemsElement.innerHTML = '<p>Your cart is empty.</p>'; 
                    return 0;
                }
                cart.forEach(item => {
                    const itemHTML = `<li>${item.name} - ${item.price} JD (x${item.quantity})</li>`;
                    cartItemsElement.innerHTML += itemHTML;
                });
                return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            }

            function updateTotalPrice(totalCost, city) {
                const deliveryCost = getCityAdjustment(city);
                const totalWithDelivery = totalCost + deliveryCost;
                totalPriceElement.textContent = `Total Price: ${totalWithDelivery} JD`;
                return totalWithDelivery;
            }

            const cart = getCartItems();
            const cartTotal = displayCartItems(cart);
            let selectedCity = form.city.value;
            let totalCostWithDelivery = updateTotalPrice(cartTotal, selectedCity);

            form.city.addEventListener('change', function() {
                selectedCity = form.city.value;
                totalCostWithDelivery = updateTotalPrice(cartTotal, selectedCity);
            });

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const name = form.name.value;
                const address = form.address.value;
                const phone = form.phone.value;
                const city = form.city.value;
                const cartItems = getCartItems();
                const totalCost = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const totalWithDelivery = totalCost + getCityAdjustment(city);

                const formData = new FormData();
                formData.append('name', name);
                formData.append('address', address);
                formData.append('phone', phone);
                formData.append('city', city);
                formData.append('cartItems', JSON.stringify(cartItems));
                formData.append('totalCost', totalWithDelivery);

                fetch('save_order.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('message').innerHTML = `<div class="alert alert-success">Order submitted successfully!</div>`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('message').innerHTML = `<div class="alert alert-danger">Error submitting the order: ${error.message}</div>`;
                });
            });
        });

        function updateDateTime() {
            var dateTimeElement = document.getElementById("datetime");
            var now = new Date();
            var dateTimeString = "Current Date and Time: " + now.toLocaleDateString() + " " + now.toLocaleTimeString();
            dateTimeElement.textContent = dateTimeString;
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>
</html>
