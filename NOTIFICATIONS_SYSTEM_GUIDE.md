# 🔔 نظام الإشعارات - دليل شامل

## نظرة عامة

تم إضافة نظام إشعارات متكامل لإعلام المستخدمين عند الإجابة على أسئلتهم. النظام يدعم:
- ✅ إشعارات داخل الموقع (Database Notifications)
- ✅ إشعارات بالبريد الإلكتروني (Email Notifications)
- ✅ أيقونة جرس في الـ navbar مع عداد
- ✅ صفحة مخصصة لعرض جميع الإشعارات

---

## 📋 المكونات الرئيسية

### 1. جدول الإشعارات
**الملف**: `database/migrations/*_create_notifications_table.php`

الجدول يحتوي على:
- `id` - معرف فريد
- `type` - نوع الإشعار (FatwaAnsweredNotification)
- `notifiable_type` - نوع المستخدم (User)
- `notifiable_id` - معرف المستخدم
- `data` - بيانات الإشعار (JSON)
- `read_at` - تاريخ القراءة (nullable)
- `created_at` - تاريخ الإنشاء

### 2. Notification Class
**الملف**: `app/Notifications/FatwaAnsweredNotification.php`

```php
class FatwaAnsweredNotification extends Notification implements ShouldQueue
{
    protected $fatwa;

    public function __construct(Fatwa $fatwa)
    {
        $this->fatwa = $fatwa;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم الإجابة على سؤالك - موقع تمسك')
            ->view('emails.fatwa-answered', ['fatwa' => $this->fatwa]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'fatwa_id' => $this->fatwa->id,
            'fatwa_title' => $this->fatwa->title,
            'scholar_name' => $this->fatwa->scholar->name,
            'scholar_id' => $this->fatwa->scholar_id,
            'message' => 'تم الإجابة على سؤالك: ' . $this->fatwa->title,
            'url' => route('fatwas.show', $this->fatwa->id),
        ];
    }
}
```

**الميزات**:
- ✅ يستخدم `ShouldQueue` للإرسال في الخلفية
- ✅ يرسل إلى قناتين: `database` و `mail`
- ✅ يستخدم قالب بريد إلكتروني مخصص

### 3. NotificationController
**الملف**: `app/Http/Controllers/NotificationController.php`

**الدوال**:
- `index()` - عرض جميع الإشعارات (مع pagination)
- `unread()` - الحصول على الإشعارات غير المقروءة (JSON)
- `markAsRead($id)` - تحديد إشعار كمقروء
- `markAllAsRead()` - تحديد جميع الإشعارات كمقروءة
- `destroy($id)` - حذف إشعار

### 4. المسارات
**الملف**: `routes/web.php`

```php
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
```

### 5. Views

#### صفحة الإشعارات
**الملف**: `resources/views/notifications/index.blade.php`

**الميزات**:
- ✅ عرض جميع الإشعارات مع pagination
- ✅ تمييز الإشعارات غير المقروءة بخلفية خضراء فاتحة
- ✅ عداد للإشعارات غير المقروءة
- ✅ زر "تحديد الكل كمقروء"
- ✅ أزرار: عرض، تحديد كمقروء، حذف
- ✅ حالة فارغة عند عدم وجود إشعارات

#### قالب البريد الإلكتروني
**الملف**: `resources/views/emails/fatwa-answered.blade.php`

**الميزات**:
- ✅ تصميم احترافي responsive
- ✅ ألوان موقع تمسك (#1d8a4e)
- ✅ عرض عنوان الفتوى
- ✅ عرض اسم العالم
- ✅ زر "اقرأ الإجابة الكاملة"
- ✅ روابط سريعة في الفوتر

#### أيقونة الجرس في Navbar
**الملف**: `resources/views/layouts/app.blade.php`

**الميزات**:
- ✅ أيقونة جرس مع عداد أحمر
- ✅ dropdown يعرض آخر 5 إشعارات
- ✅ زر "تحديد الكل كمقروء"
- ✅ رابط "عرض جميع الإشعارات"
- ✅ حالة فارغة عند عدم وجود إشعارات

---

## 🚀 كيفية الاستخدام

### إرسال إشعار عند نشر الإجابة

تم تعديل `ScholarDashboardController` في موضعين:

#### 1. عند تحديث الإجابة ونشرها
```php
public function updateAnswer(Request $request, $id)
{
    // ...
    $wasPublished = $question->is_published;
    
    $question->update([
        'answer' => $request->answer,
        'is_published' => $isPublished,
        // ...
    ]);

    // إرسال إشعار للسائل إذا تم النشر لأول مرة
    if ($isPublished && !$wasPublished && $question->questioner) {
        $question->questioner->notify(new FatwaAnsweredNotification($question));
    }
    // ...
}
```

#### 2. عند نشر مسودة
```php
public function publishAnswer($id)
{
    // ...
    $question->update([
        'is_published' => true,
        'published_at' => now(),
    ]);

    // إرسال إشعار للسائل
    if ($question->questioner) {
        $question->questioner->notify(new FatwaAnsweredNotification($question));
    }
    // ...
}
```

---

## 🧪 الاختبار

### 1. اختبار الإشعار الداخلي

```bash
# في tinker
php artisan tinker

# إرسال إشعار تجريبي
$user = User::find(1);
$fatwa = Fatwa::find(1);
$user->notify(new \App\Notifications\FatwaAnsweredNotification($fatwa));

# التحقق من الإشعارات
$user->notifications;
$user->unreadNotifications;
```

### 2. اختبار البريد الإلكتروني

**تكوين البريد في `.env`**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tamsik.com
MAIL_FROM_NAME="موقع تمسك"
```

**اختبار الإرسال**:
```bash
php artisan tinker

$user = User::find(1);
$fatwa = Fatwa::find(1);
$user->notify(new \App\Notifications\FatwaAnsweredNotification($fatwa));
```

### 3. السيناريو الكامل

1. **تسجيل دخول كعضو** وطرح سؤال
2. **تسجيل دخول كعالم** والإجابة على السؤال ونشره
3. **العودة لحساب العضو**:
   - ✅ يجب أن تظهر نقطة حمراء على أيقونة الجرس
   - ✅ عند النقر على الجرس، يظهر الإشعار
   - ✅ عند النقر على الإشعار، يتم التوجيه للفتوى
   - ✅ يجب أن يصل بريد إلكتروني

---

## 📊 قاعدة البيانات

### استعلامات مفيدة

```sql
-- عرض جميع الإشعارات
SELECT * FROM notifications;

-- عرض الإشعارات غير المقروءة
SELECT * FROM notifications WHERE read_at IS NULL;

-- عرض إشعارات مستخدم معين
SELECT * FROM notifications WHERE notifiable_id = 1;

-- حذف الإشعارات القديمة (أكثر من 30 يوم)
DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

---

## 🎨 التخصيص

### تغيير ألوان البريد الإلكتروني

عدّل الملف `resources/views/emails/fatwa-answered.blade.php`:

```css
.header {
    background: linear-gradient(135deg, #YOUR_COLOR 0%, #YOUR_DARK_COLOR 100%);
}
```

### إضافة أنواع إشعارات جديدة

1. إنشاء Notification جديد:
```bash
php artisan make:notification NewNotificationName
```

2. تعديل الـ class:
```php
public function via(object $notifiable): array
{
    return ['database', 'mail'];
}
```

3. إرسال الإشعار:
```php
$user->notify(new NewNotificationName($data));
```

---

## ⚙️ الإعدادات المتقدمة

### استخدام Queue للإشعارات

الإشعارات تستخدم `ShouldQueue` بالفعل، لكن تحتاج لتشغيل queue worker:

```bash
# تشغيل queue worker
php artisan queue:work

# أو في production
php artisan queue:work --daemon
```

### جدولة حذف الإشعارات القديمة

في `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('notifications:prune --hours=720')->daily();
}
```

---

## 🐛 استكشاف الأخطاء

### الإشعارات لا تظهر

1. تحقق من جدول `notifications`:
```sql
SELECT * FROM notifications WHERE notifiable_id = YOUR_USER_ID;
```

2. تحقق من أن User model يستخدم `Notifiable` trait

3. امسح الـ cache:
```bash
php artisan cache:clear
php artisan view:clear
```

### البريد الإلكتروني لا يُرسل

1. تحقق من إعدادات `.env`
2. تحقق من logs:
```bash
tail -f storage/logs/laravel.log
```

3. اختبر الاتصال:
```bash
php artisan tinker
Mail::raw('Test', function($msg) { $msg->to('test@example.com'); });
```

---

## ✅ الخلاصة

تم إضافة نظام إشعارات متكامل يشمل:

- ✅ جدول notifications في قاعدة البيانات
- ✅ FatwaAnsweredNotification class
- ✅ NotificationController مع 5 دوال
- ✅ 5 مسارات للإشعارات
- ✅ صفحة عرض الإشعارات
- ✅ أيقونة جرس في navbar
- ✅ قالب بريد إلكتروني احترافي
- ✅ إرسال تلقائي عند نشر الإجابة

**النظام جاهز للاستخدام! 🎉**

