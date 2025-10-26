#!/bin/bash

# ========================================
# سكريبت النشر التلقائي - Tamsik Deployment Script
# ========================================
# الاستخدام: bash deploy.sh
# ========================================

echo "🚀 بدء عملية النشر - Starting Deployment..."
echo "=========================================="

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. التحقق من وجود ملف .env
echo ""
echo "📋 الخطوة 1: التحقق من ملف .env..."
if [ ! -f .env ]; then
    echo -e "${RED}❌ خطأ: ملف .env غير موجود!${NC}"
    echo "قم بنسخ .env.production.example إلى .env وتعديل القيم"
    exit 1
fi
echo -e "${GREEN}✅ ملف .env موجود${NC}"

# 2. التحقق من APP_ENV
echo ""
echo "📋 الخطوة 2: التحقق من البيئة..."
if grep -q "APP_ENV=local" .env; then
    echo -e "${YELLOW}⚠️  تحذير: APP_ENV لا زال 'local'${NC}"
    echo "للإنتاج، يجب تغييره إلى 'production'"
fi

if grep -q "APP_DEBUG=true" .env; then
    echo -e "${YELLOW}⚠️  تحذير: APP_DEBUG لا زال 'true'${NC}"
    echo "للإنتاج، يجب تغييره إلى 'false'"
fi

# 3. تثبيت Dependencies
echo ""
echo "📋 الخطوة 3: تثبيت Dependencies..."
composer install --optimize-autoloader --no-dev
echo -e "${GREEN}✅ تم تثبيت Dependencies${NC}"

# 4. تشغيل Migrations
echo ""
echo "📋 الخطوة 4: تشغيل Migrations..."
php artisan migrate --force
echo -e "${GREEN}✅ تم تشغيل Migrations${NC}"

# 5. تشغيل Seeders (اختياري)
echo ""
echo "📋 الخطوة 5: تشغيل Seeders..."
read -p "هل تريد تشغيل Seeders؟ (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed --force
    echo -e "${GREEN}✅ تم تشغيل Seeders${NC}"
else
    echo -e "${YELLOW}⏭️  تم تخطي Seeders${NC}"
fi

# 6. إنشاء Storage Link
echo ""
echo "📋 الخطوة 6: إنشاء Storage Link..."
php artisan storage:link
echo -e "${GREEN}✅ تم إنشاء Storage Link${NC}"

# 7. Cache Optimization
echo ""
echo "📋 الخطوة 7: تحسين الأداء (Caching)..."
php artisan config:cache
php artisan view:cache
php artisan route:cache
echo -e "${GREEN}✅ تم تحسين الأداء${NC}"

# 8. ضبط الصلاحيات
echo ""
echo "📋 الخطوة 8: ضبط الصلاحيات..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo -e "${GREEN}✅ تم ضبط الصلاحيات${NC}"

# 9. التحقق النهائي
echo ""
echo "=========================================="
echo "📊 التحقق النهائي..."
echo "=========================================="

if grep -q "APP_DEBUG=true" .env; then
    echo -e "${RED}⚠️  APP_DEBUG=true (يجب أن يكون false في الإنتاج)${NC}"
else
    echo -e "${GREEN}✅ APP_DEBUG=false${NC}"
fi

if grep -q "APP_ENV=production" .env; then
    echo -e "${GREEN}✅ APP_ENV=production${NC}"
else
    echo -e "${YELLOW}⚠️  APP_ENV ليس 'production'${NC}"
fi

echo ""
echo "=========================================="
echo -e "${GREEN}🎉 اكتمل النشر بنجاح!${NC}"
echo "=========================================="
echo ""
echo "📝 الخطوات التالية:"
echo "1. تأكد من تكوين Web Server (Nginx/Apache)"
echo "2. تأكد من تثبيت SSL Certificate"
echo "3. اختبر الموقع"
echo "4. راقب الأخطاء في storage/logs/laravel.log"
echo ""
echo "✅ بالتوفيق! 🚀"
