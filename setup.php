<?php
/**
 * ملف إعداد مؤقت لموقع تمسيك على Hostinger
 * 
 * هذا الملف يساعد في إعداد الموقع تلقائياً على الاستضافة
 * يجب حذفه فور الانتهاء من الإعداد لأسباب أمنية
 */

// التحقق من وجود Laravel
if (!file_exists('vendor/autoload.php')) {
    die('❌ خطأ: ملفات Laravel غير موجودة. تأكد من رفع جميع ملفات المشروع.');
}

require_once 'vendor/autoload.php';

// التحقق من وجود ملف .env
if (!file_exists('.env')) {
    die('❌ خطأ: ملف .env غير موجود. تأكد من نسخ .env.production إلى .env وتعديل الإعدادات.');
}

try {
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "<!DOCTYPE html>";
    echo "<html lang='ar' dir='rtl'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>إعداد موقع تمسيك</title>";
    echo "<style>";
    echo "body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 40px; background: #f5f5f5; }";
    echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
    echo ".step { margin: 20px 0; padding: 15px; border-left: 4px solid #2c5aa0; background: #f8f9fa; }";
    echo ".success { border-left-color: #28a745; background: #d4edda; }";
    echo ".error { border-left-color: #dc3545; background: #f8d7da; }";
    echo ".warning { border-left-color: #ffc107; background: #fff3cd; }";
    echo "h1 { color: #2c5aa0; text-align: center; }";
    echo "h2 { color: #495057; }";
    echo ".emoji { font-size: 1.2em; margin-left: 10px; }";
    echo "</style>";
    echo "</head>";
    echo "<body>";
    
    echo "<div class='container'>";
    echo "<h1>🕌 إعداد موقع تمسيك</h1>";
    echo "<p style='text-align: center; color: #6c757d;'>جاري إعداد الموقع على استضافة Hostinger...</p>";
    
    // الخطوة 1: اختبار الاتصال بقاعدة البيانات
    echo "<div class='step'>";
    echo "<h2><span class='emoji'>🔗</span>اختبار الاتصال بقاعدة البيانات</h2>";
    
    try {
        $pdo = new PDO(
            'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
        echo "<p style='color: green;'>✅ تم الاتصال بقاعدة البيانات بنجاح</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ فشل الاتصال بقاعدة البيانات: " . $e->getMessage() . "</p>";
        echo "<p><strong>تأكد من:</strong></p>";
        echo "<ul>";
        echo "<li>صحة معلومات قاعدة البيانات في ملف .env</li>";
        echo "<li>إنشاء قاعدة البيانات في Hostinger hPanel</li>";
        echo "<li>صحة اسم المستخدم وكلمة المرور</li>";
        echo "</ul>";
        echo "</div></div></body></html>";
        exit;
    }
    echo "</div>";
    
    // الخطوة 2: تشغيل Migrations
    echo "<div class='step'>";
    echo "<h2><span class='emoji'>📊</span>إنشاء جداول قاعدة البيانات</h2>";
    
    try {
        ob_start();
        $kernel->call('migrate', ['--force' => true]);
        $output = ob_get_clean();
        echo "<p style='color: green;'>✅ تم إنشاء جداول قاعدة البيانات بنجاح</p>";
        echo "<details><summary>تفاصيل العملية</summary><pre>" . htmlspecialchars($output) . "</pre></details>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ خطأ في إنشاء الجداول: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    
    // الخطوة 3: تشغيل Seeders
    echo "<div class='step'>";
    echo "<h2><span class='emoji'>🌱</span>إضافة البيانات التجريبية</h2>";
    
    try {
        ob_start();
        $kernel->call('db:seed', ['--force' => true]);
        $output = ob_get_clean();
        echo "<p style='color: green;'>✅ تم إضافة البيانات التجريبية بنجاح</p>";
        echo "<p><strong>تم إضافة:</strong></p>";
        echo "<ul>";
        echo "<li>5 محاضرات تجريبية</li>";
        echo "<li>4 مقالات تجريبية</li>";
        echo "<li>حسابات مستخدمين للاختبار</li>";
        echo "</ul>";
        echo "<details><summary>تفاصيل العملية</summary><pre>" . htmlspecialchars($output) . "</pre></details>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ تحذير: " . $e->getMessage() . "</p>";
        echo "<p>يمكن تجاهل هذا الخطأ إذا كانت البيانات موجودة مسبقاً</p>";
    }
    echo "</div>";
    
    // الخطوة 4: إنشاء Storage Link
    echo "<div class='step'>";
    echo "<h2><span class='emoji'>🔗</span>إنشاء روابط التخزين</h2>";
    
    try {
        ob_start();
        $kernel->call('storage:link');
        $output = ob_get_clean();
        echo "<p style='color: green;'>✅ تم إنشاء روابط التخزين بنجاح</p>";
        echo "<details><summary>تفاصيل العملية</summary><pre>" . htmlspecialchars($output) . "</pre></details>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ تحذير: " . $e->getMessage() . "</p>";
        echo "<p>قد يكون الرابط موجود مسبقاً</p>";
    }
    echo "</div>";
    
    // الخطوة 5: تحسين التطبيق
    echo "<div class='step'>";
    echo "<h2><span class='emoji'>⚡</span>تحسين الأداء</h2>";
    
    try {
        $kernel->call('config:cache');
        $kernel->call('view:cache');
        echo "<p style='color: green;'>✅ تم تحسين الأداء بنجاح</p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ تحذير في التحسين: " . $e->getMessage() . "</p>";
    }
    echo "</div>";
    
    // النتيجة النهائية
    echo "<div class='step success'>";
    echo "<h2><span class='emoji'>🎉</span>تم الانتهاء من الإعداد!</h2>";
    echo "<p><strong>موقع تمسيك جاهز للاستخدام!</strong></p>";
    
    echo "<h3>🔗 روابط مهمة:</h3>";
    echo "<ul>";
    echo "<li><a href='/' target='_blank'>الصفحة الرئيسية</a></li>";
    echo "<li><a href='/sermons' target='_blank'>صفحة الخطب</a></li>";
    echo "<li><a href='/lectures' target='_blank'>صفحة المحاضرات</a></li>";
    echo "<li><a href='/thinkers' target='_blank'>صفحة المفكرون</a></li>";
    echo "<li><a href='/login' target='_blank'>تسجيل الدخول</a></li>";
    echo "</ul>";
    
    echo "<h3>👥 حسابات الاختبار:</h3>";
    echo "<ul>";
    echo "<li><strong>المدير:</strong> admin@tamsik.com / admin123</li>";
    echo "<li><strong>الخطيب:</strong> preacher@tamsik.com / preacher123</li>";
    echo "<li><strong>العضو:</strong> member@tamsik.com / member123</li>";
    echo "</ul>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin-top: 20px;'>";
    echo "<h3 style='color: #856404;'>🚨 مهم جداً:</h3>";
    echo "<p style='color: #856404;'><strong>احذف هذا الملف (setup.php) الآن لأسباب أمنية!</strong></p>";
    echo "<p style='color: #856404;'>يمكنك حذفه من File Manager في Hostinger hPanel</p>";
    echo "</div>";
    
    echo "</div>";
    
    echo "</div>";
    echo "</body>";
    echo "</html>";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 20px; background: #f8d7da; border-radius: 5px;'>";
    echo "<h2>❌ خطأ في الإعداد</h2>";
    echo "<p><strong>رسالة الخطأ:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>الملف:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>السطر:</strong> " . $e->getLine() . "</p>";
    echo "<h3>الحلول المقترحة:</h3>";
    echo "<ul>";
    echo "<li>تأكد من رفع جميع ملفات المشروع</li>";
    echo "<li>تأكد من صحة إعدادات .env</li>";
    echo "<li>تأكد من تفعيل PHP 8.1+ في Hostinger</li>";
    echo "<li>تأكد من تفعيل Extensions المطلوبة</li>";
    echo "</ul>";
    echo "</div>";
}
?>
