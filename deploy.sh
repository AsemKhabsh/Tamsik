#!/bin/bash

# Tamsik Deployment Script
# هذا السكريبت لرفع مشروع تمسيك للاستضافة

echo "🚀 بدء عملية رفع مشروع تمسيك..."

# 1. تحديث Composer dependencies للإنتاج
echo "📦 تحديث Composer dependencies..."
composer install --optimize-autoloader --no-dev

# 2. تحسين التطبيق
echo "⚡ تحسين التطبيق للإنتاج..."
php artisan config:cache
php artisan view:cache
# php artisan route:cache  # معطل بسبب مشكلة في الـ routes

# 3. تشغيل migrations
echo "🗄️ تشغيل migrations..."
php artisan migrate --force

# 4. تشغيل seeders (اختياري)
echo "🌱 تشغيل seeders..."
php artisan db:seed --force

# 5. إنشاء symbolic link للتخزين
echo "🔗 إنشاء symbolic link..."
php artisan storage:link

# 6. تعيين الصلاحيات
echo "🔐 تعيين الصلاحيات..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ تم الانتهاء من عملية الرفع بنجاح!"
echo "🌐 الموقع جاهز للاستخدام"
