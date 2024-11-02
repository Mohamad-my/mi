<?php
// بدء جلسة php
session_start();

//حذف لكل السشن التي تم حفظها داخل المتصفح
session_unset();

//تدمير او تحطيم الجلسة 
session_destroy();

//اعادة توجيه ل المستخدم
header('location:admin.php');
?>