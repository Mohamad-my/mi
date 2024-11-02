<?php
ob_start();
session_start();

// إعدادات Supabase
$supabase_url = "https://nlszbtvnyniqdokpubbq.supabase.co"; 
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5sc3pidHZueW5pcWRva3B1YmJxIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzAyMjM5NDYsImV4cCI6MjA0NTc5OTk0Nn0.nXmb3WE-cEZqTrqGANth0yI363S2_s_T812roEKTc4I"; 
$supabaseAuthUrl = $supabase_url . '/auth/v1/token?grant_type=password';

$ADemail = isset($_POST['email']) ? trim($_POST['email']) : '';
$ADpassword = isset($_POST['password']) ? trim($_POST['password']) : '';
$ADadd = isset($_POST['add']) ? $_POST['add'] : '';

if (isset($ADadd)) {
    if (empty($ADemail) || empty($ADpassword)) {
        echo '<p class="alert">الرجاء إدخال البريد الإلكتروني وكلمة السر.</p>';
    } else {
        // إعداد الطلب إلى Supabase للتحقق من المستخدم
        $data = json_encode([
            'email' => $ADemail,
            'password' => $ADpassword
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $supabaseAuthUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "apikey: $supabase_key",
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            echo '<p class="alert">حدث خطأ أثناء الاتصال بـ Supabase: ' . curl_error($ch) . '</p>';
        } else {
            echo '<pre>HTTP Code: ' . $httpCode . "\n";
            echo 'Response: ' . $response . '</pre>';

            $data = json_decode($response, true);

            if ($httpCode === 200 && isset($data['access_token'])) {
                $_SESSION['EMAIL'] = $ADemail;
                header("Location: admainbanel.php");
                exit();
            } else {
                echo '<p class="alert">البريد الإلكتروني أو كلمة المرور غير صحيحة.</p>';
                echo '<pre>' . print_r($data, true) . '</pre>';
                header("Refresh:2; URL=index.php");
                exit();
            }
        }
        curl_close($ch);
    }
}

ob_end_flush();
?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            font-family: 'Cairo', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 400px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            width: 100%;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            input[type="email"], input[type="password"] {
                font-size: 14px;
            }

            button {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<script>
import { createClient } from '@supabase/supabase-js'
const supabaseUrl = 'https://nlszbtvnyniqdokpubbq.supabase.co'
const supabaseKey = process.env.SUPABASE_KEY
const supabase = createClient(supabaseUrl, supabaseKey)

const signUp=async() =>{
    let { data, error } = await supabase.auth.signUp({
  email: $ADemail,
  password: $ADpassword
})
}



</script>
    <main>
        <div class="container">
            <h1>تسجيل الدخول</h1>
            <h3>هذه الصفحة مخصصة للإداري فقط</h3>
            <form action="admin.php" method="post">
                <label for="em">البريد الالكتروني</label>
                <input type="email" name="email" id="signUp" placeholder="أدخل بريدك الإلكتروني" required>
                <br>
                <label for="pass">الرقم السري</label>
                <input type="password" name="password" id="pass" placeholder="أدخل كلمة السر" required>
                <br>
                <button type="submit" name="add">تسجيل الدخول</button>
            </form>
        </div>
    </main>
</body>
</html>
