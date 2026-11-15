# 🏥 نظام إدارة العيادات — Clinic ERP System
> PHP 8.2 + Laravel 12 + Bootstrap 5 RTL + MySQL

---

## 📦 محتويات النظام

| الوحدة | الوصف |
|---|---|
| 🧑‍⚕️ المرضى | تسجيل، بحث، ملفات طبية كاملة |
| 📅 المواعيد | حجز، تتبع الحالة، تقويم |
| 👨‍⚕️ الأطباء | إدارة الأطباء وأوقات العمل |
| 📋 السجلات الطبية | الكشف، التشخيص، قياسات الجسم |
| 💊 الصيدلية / المخزن | الأدوية، الروشتات، تنبيهات المخزون |
| 💰 الخزنة | الإيرادات، المصروفات، التقارير المالية |
| 🔐 الأدوار والصلاحيات | مدير، دكتور، استقبال، صيدلاني |

---

## 🛠️ متطلبات التشغيل

| الأداة | الإصدار المطلوب |
|---|---|
| PHP | 8.2 أو أعلى |
| Composer | 2.x |
| MySQL | 8.0 أو أعلى (أو MariaDB 10.6+) |
| Node.js | 18+ (اختياري للـ assets) |

---

## 🚀 طريقة التثبيت الكاملة (من الصفر)

### الخطوة 1: تثبيت PHP 8.2

#### على Windows (باستخدام XAMPP)
1. حمّل **XAMPP** من: https://www.apachefriends.org/download.html
2. اختر الإصدار الذي يحتوي **PHP 8.2**
3. ثبّت وشغّل لوحة XAMPP
4. تأكد من تشغيل **Apache** و **MySQL**
5. افتح CMD وتحقق:
   ```
   php -v
   ```

#### على Windows (باستخدام Herd - الأسهل لـ Laravel)
```
# حمّل من:
https://herd.laravel.com/windows
# ثبّت وسيشتغل تلقائياً مع PHP و Composer
```

#### على Linux (Ubuntu/Debian)
```bash
sudo apt update
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql \
    php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip \
    php8.2-gd php8.2-bcmath php8.2-intl -y
php -v
```

#### على macOS
```bash
brew install php@8.2
brew link php@8.2
php -v
```

---

### الخطوة 2: تثبيت Composer

#### Windows
1. حمّل من: https://getcomposer.org/Composer-Setup.exe
2. شغّل الـ installer وسيضاف تلقائياً للـ PATH
3. تحقق:
   ```
   composer -V
   ```

#### Linux / macOS
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer -V
```

---

### الخطوة 3: تثبيت MySQL

#### Windows (مع XAMPP)
MySQL مدمج — شغّله من لوحة XAMPP

#### Linux
```bash
sudo apt install mysql-server -y
sudo systemctl start mysql
sudo mysql_secure_installation
```

#### macOS
```bash
brew install mysql
brew services start mysql
```

---

### الخطوة 4: تثبيت المشروع

```bash
# 1. انسخ أو استخرج المشروع
cd /path/to/projects
# استخرج clinic-erp.zip هنا
cd clinic-erp

# 2. ثبّت الحزم
composer install

# 3. انسخ ملف البيئة
cp .env.example .env

# 4. ولّد مفتاح التطبيق
php artisan key:generate
```

---

### الخطوة 5: إعداد قاعدة البيانات

#### إنشاء قاعدة البيانات
```sql
-- افتح MySQL:
mysql -u root -p

-- أنشئ قاعدة البيانات:
CREATE DATABASE clinic_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### تعديل ملف .env
افتح ملف `.env` وعدّل:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_erp
DB_USERNAME=root
DB_PASSWORD=       ← كلمة مرور MySQL الخاصة بك
```

#### تشغيل الـ Migrations والـ Seeders
```bash
# إنشاء الجداول
php artisan migrate

# إضافة البيانات التجريبية (مرضى، أطباء، أدوية)
php artisan db:seed
```

---

### الخطوة 6: تشغيل السيرفر

```bash
php artisan serve
```

ثم افتح المتصفح على:
```
http://localhost:8000
```

---

## 🔑 بيانات الدخول التجريبية

| الدور | البريد الإلكتروني | كلمة المرور |
|---|---|---|
| مدير النظام | admin@admin.com | admin |
| طبيب 1 | dr.ahmed@admin.com | admin |
| طبيب 2 | dr.sara@admin.com | admin |

---

## 📁 هيكل المشروع

```
clinic-erp/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          ← المدير والاستقبال
│   │   │   ├── DashboardController.php
│   │   │   ├── PatientController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── PharmacyController.php
│   │   │   └── TreasuryController.php
│   │   ├── Doctor/         ← وحدة الطبيب
│   │   │   └── MedicalRecordController.php
│   │   └── Auth/
│   │       └── LoginController.php
│   └── Models/
│       ├── User.php
│       ├── Doctor.php
│       ├── Patient.php
│       ├── Appointment.php
│       ├── MedicalRecord.php
│       ├── Prescription.php
│       ├── PrescriptionItem.php
│       ├── Medicine.php
│       ├── MedicineCategory.php
│       └── TreasuryTransaction.php
├── database/
│   ├── migrations/         ← 8 ملفات migration
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/login.blade.php
│   ├── dashboard/index.blade.php
│   ├── patients/
│   │   ├── index.blade.php
│   │   └── create.blade.php
│   └── appointments/
│       └── create.blade.php
├── routes/web.php
├── composer.json
└── .env.example
```

---

## ⚡ أوامر مفيدة

```bash
# مسح الكاش
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# إعادة تشغيل الـ migrations من البداية (يمسح البيانات)
php artisan migrate:fresh --seed

# عرض كل الـ routes
php artisan route:list

# إنشاء controller جديد
php artisan make:controller Admin/ReportController

# إنشاء model مع migration
php artisan make:model ModelName -m
```

---

## 🔒 الأدوار والصلاحيات (Spatie Permissions)

الـ package **spatie/laravel-permission** مضاف في `composer.json`.
بعد `composer install`:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### الأدوار المقترحة:
| الدور | الصلاحيات |
|---|---|
| `admin` | كل شيء |
| `doctor` | المواعيد + السجلات الطبية + الروشتات |
| `receptionist` | المرضى + المواعيد + الدفع |
| `pharmacist` | الصيدلية + الروشتات |
| `accountant` | الخزنة + التقارير |

---

## 📊 قاعدة البيانات — العلاقات الرئيسية

```
users ──► doctors
                └──► appointments ──► medical_records ──► prescriptions
patients ─────────┘                                           └──► prescription_items ──► medicines
                                                                                           ↑
                                                           medicine_categories ────────────┘

treasury_transactions (مرتبطة بـ appointments و prescriptions)
```

---

## 🆘 مشاكل شائعة وحلولها

| المشكلة | الحل |
|---|---|
| `Class not found` | شغّل `composer dump-autoload` |
| `SQLSTATE Connection refused` | تأكد أن MySQL شغّال، وإعدادات `.env` صحيحة |
| `Key too long` error | أضف في `AppServiceProvider`: `Schema::defaultStringLength(191)` |
| الصفحات لا تظهر بالعربي | تأكد من `APP_LOCALE=ar` في `.env` |
| `permission denied` على storage | شغّل: `chmod -R 775 storage bootstrap/cache` |

---

## 📞 للدعم والتطوير

تم تصميم النظام ليكون قابلاً للتوسعة.
يمكن إضافة:
- وحدة X-Ray والمختبر
- نظام تأمين صحي
- تطبيق موبايل (API جاهز عبر Laravel Sanctum)
- التقارير المتقدمة بـ PDF (باستخدام barryvdh/laravel-dompdf)
