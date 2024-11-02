<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>جدول الطلبات</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>قائمة الطلبات</h2>
        
        <?php
        $host = "localhost:3307";
        $username = "root";
        $password = "";
        $dbname = "task";

        // الاتصال بقاعدة البيانات
        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
        }

        // التحقق من استلام بيانات النموذج وحفظها في قاعدة البيانات
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_id'])) {
                // حذف الطلب
                $delete_id = $_POST['delete_id'];
                $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
                $stmt->bind_param("i", $delete_id);
                
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>تم حذف الطلب بنجاح!</div>";
                } else {
                    echo "<div class='alert alert-danger'>فشل في حذف الطلب: " . $conn->error . "</div>";
                }

                $stmt->close();
            } else {
                // إضافة الطلب
                $name = $_POST['name'];
                $address = $_POST['address'];
                $phone = $_POST['phone'];
                $city = $_POST['city'];
                $total_cost = $_POST['totalCost'];
                $cart_items = $_POST['cartItems'];
                $order_date = date("Y-m-d H:i:s");

                // استعلام الإدخال
                $stmt = $conn->prepare("INSERT INTO orders (name, address, phone, city, total_cost, cart_items, order_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssiss", $name, $address, $phone, $city, $total_cost, $cart_items, $order_date);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>تم إضافة الطلب بنجاح!</div>";
                } else {
                    echo "<div class='alert alert-danger'>فشل في إضافة الطلب: " . $conn->error . "</div>";
                }

                $stmt->close();
            }
        }

        // استعلام جلب الطلبات لعرضها في الجدول
        $result = $conn->query("SELECT * FROM orders");

        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered'>";
            echo "<thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>العنوان</th>
                        <th>الهاتف</th>
                        <th>المدينة</th>
                        <th>التكلفة الإجمالية</th>
                        <th>عناصر السلة</th>
                        <th>تاريخ الطلب</th>
                        <th>حذف الطلب</th>
                    </tr>
                  </thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['city']}</td>
                        <td>{$row['total_cost']}</td>
                        <td>{$row['cart_items']}</td>
                        <td>{$row['order_date']}</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                <button type='submit' class='btn btn-danger btn-sm'>حذف</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<div class='alert alert-info'>لا توجد طلبات بعد.</div>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
