<?php

echo "🔍 اختبار الاتصال بقاعدة البيانات...\n\n";

try {
    $host = 'localhost';
    $dbname = 'tamsik_db';
    $username = 'root';
    $password = '';
    
    echo "📋 إعدادات الاتصال:\n";
    echo "- Host: $host\n";
    echo "- Database: $dbname\n";
    echo "- Username: $username\n";
    echo "- Password: " . (empty($password) ? 'فارغة' : 'محددة') . "\n\n";
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ تم الاتصال بقاعدة البيانات بنجاح!\n";
    
    // اختبار إنشاء جدول بسيط
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255))");
    echo "✅ تم إنشاء جدول اختبار بنجاح!\n";
    
    // حذف الجدول
    $pdo->exec("DROP TABLE test_table");
    echo "✅ تم حذف جدول الاختبار بنجاح!\n";
    
    echo "\n🎉 قاعدة البيانات جاهزة للاستخدام!\n";
    
} catch (PDOException $e) {
    echo "❌ خطأ في الاتصال بقاعدة البيانات:\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    echo "🔧 الحلول المقترحة:\n";
    echo "1. تأكد من تشغيل MySQL في XAMPP\n";
    echo "2. تأكد من إنشاء قاعدة البيانات 'tamsik_db'\n";
    echo "3. تحقق من اسم المستخدم وكلمة المرور\n";
    echo "4. جرب تغيير localhost إلى 127.0.0.1\n";
}
