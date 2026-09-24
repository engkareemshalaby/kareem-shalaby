# Kareem Shalaby — Personal Website

الموقع الشخصي والمهني لكريم شلبي: مساحة ثنائية اللغة لنشر المقالات، عرض الخبرات والمشاريع، وبناء ملف مهني يمكن مشاركته مع الشركات والعملاء.

المشروع مبني بـLaravel، ويحتوي على لوحة تحكم مخصصة وخفيفة دون الاعتماد على نظام إدارة محتوى خارجي.

## المزايا الرئيسية

- موقع عربي وإنجليزي مع دعم RTL وLTR.
- مقالات عربية، إنجليزية، أو ثنائية اللغة.
- محرر Markdown سهل باستخدام EasyMDE.
- أقسام ووسوم قابلة للإضافة والتعديل والإخفاء.
- ملف مهني مستقل للخبرات والمحتوى التقني والتجاري.
- معرض مشاريع كامل مع صورة غلاف ولقطات داخلية.
- إدارة مرنة لوسائل التواصل مثل GitHub وLinkedIn وYouTube وفيسبوك.
- وضع فاتح وداكن بهوية موحدة: Navy، Gold، وWarm White.
- SEO metadata وCanonical URLs وhreflang وSchema.org وSitemap.
- تكامل اختياري مع Google Analytics.
- إحصائيات داخلية للزيارات والصفحات الأكثر مشاهدة وبيانات User Agent.
- لوحة تحكم محمية بصلاحيات Admin.

## أقسام المحتوى

الأقسام الافتراضية قابلة للتعديل من لوحة التحكم:

- `software` — البرمجة، Laravel، Architecture، Servers وProduction.
- `business` — الإدارة، تأسيس الشركات، التسويق وإدارة الفرق.
- `islam` — المقالات والدروس الشرعية مع العناية بالمصادر.
- `arabic` — اللغة العربية، النحو والبلاغة.
- `family` — ما أتعلمه وأطبقه في التربية والأسرة.
- `thoughts` — الكتب، التجارب والأفكار العامة.

يمكن تصنيف المقال داخل قسم واحد وإضافة عدة وسوم إليه، مثل `Laravel` و`WordPress`.

## نظام المشاريع

يدعم كل مشروع البيانات التالية:

- اسم ووصف بالعربية والإنجليزية.
- رابط ثابت `slug`.
- المجال: تعليمي، تجاري، طبي، خدمي، حكومي أو غير ربحي.
- نوع النظام: LMS، E-commerce، ERP، CRM، SaaS أو غيرها.
- العميل أو الجهة المالكة.
- الجمهور أو الفئة المستفيدة.
- التقنيات ولغات البرمجة المستخدمة.
- تاريخ الإنجاز.
- رابط المشروع ورابط المستودع البرمجي.
- صورة غلاف اختيارية.
- حتى 12 لقطة داخلية اختيارية.
- التحكم في الظهور والتمييز والترتيب.

تُحفظ الصور في `storage/app/public/projects`، وتُحذف الملفات المرتبطة تلقائيًا عند حذف المشروع.

## التقنيات

- PHP 8.3+
- Laravel 13
- SQLite افتراضيًا، مع إمكانية استخدام MySQL
- Blade
- Tailwind CSS 4
- Vite 8
- PHPUnit 12

## التشغيل محليًا على Laragon

### 1. تثبيت المشروع

ضع المشروع داخل مجلد مواقع Laragon:

```powershell
cd C:\laragon\www
git clone <repository-url> kareem-shalaby
cd kareem-shalaby
```

### 2. تثبيت الاعتماديات

```powershell
composer install
npm install
```

### 3. إعداد البيئة

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

الإعداد الافتراضي يستخدم SQLite. أنشئ الملف إذا لم يكن موجودًا:

```powershell
New-Item database\database.sqlite -ItemType File -Force
```

### 4. قاعدة البيانات والملفات

```powershell
php artisan migrate --seed
php artisan storage:link
```

### 5. بناء الواجهة

للتطوير:

```powershell
npm run dev
```

أو لبناء نسخة الإنتاج:

```powershell
npm run build
```

بعد تشغيل Laragon سيكون الموقع متاحًا غالبًا على:

```text
http://kareem-shalaby.test
```

## إعداد حساب الإدارة

يُنشأ حساب المدير بواسطة Seeder باستخدام القيم الموجودة في `.env`:

```dotenv
ADMIN_EMAIL=admin@kareemshalaby.local
ADMIN_PASSWORD=ChangeMe123!
```

غيّر كلمة المرور الافتراضية قبل النشر، ثم شغّل:

```powershell
php artisan db:seed
```

رابط لوحة التحكم:

```text
http://kareem-shalaby.test/admin/login
```

## أهم المسارات

| الصفحة | العربية | الإنجليزية |
| --- | --- | --- |
| الرئيسية | `/ar` | `/en` |
| المقالات | `/ar/articles` | `/en/articles` |
| المشاريع | `/ar/projects` | `/en/projects` |
| الملف المهني | `/ar/professional` | `/en/professional` |
| Sitemap | `/sitemap.xml` | — |
| لوحة التحكم | `/admin` | — |

زر اللغة يحافظ على الصفحة الحالية، بما في ذلك المقال أو المشروع والفلاتر المستخدمة.

## لوحة التحكم

تتيح لوحة التحكم إدارة:

- المقالات وحالة النشر والمحتوى ثنائي اللغة.
- الأقسام والوسوم.
- المشاريع والصور الداخلية.
- الخبرات المهنية.
- وسائل التواصل وترتيبها وإخفاؤها.
- البيانات الشخصية ومعلومات التواصل.
- الإحصائيات والصفحات الأكثر زيارة.

## Google Analytics

أضف Measurement ID داخل `.env`:

```dotenv
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
```

ثم امسح كاش الإعدادات:

```powershell
php artisan optimize:clear
```

عند تفعيل Analytics يُستخدم `anonymize_ip` لتقليل البيانات الشخصية المرسلة.

## الاختبارات والجودة

```powershell
php artisan test
vendor\bin\pint
npm run build
```

الاختبارات تغطي صلاحيات الإدارة، إدارة المقالات والوسوم والمشاريع ووسائل التواصل، رفع الصور، اللغات، إخفاء المحتوى، وصفحات الموقع العامة.

## إعداد MySQL بدل SQLite

عدّل القيم التالية في `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kareem_shalaby
DB_USERNAME=root
DB_PASSWORD=
```

ثم شغّل:

```powershell
php artisan migrate --seed
```

## النشر

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
npm ci
npm run build
php artisan optimize
```

تأكد قبل فتح الموقع للجمهور من:

- ضبط `APP_ENV=production` و`APP_DEBUG=false`.
- استخدام كلمة مرور Admin قوية.
- ضبط `APP_URL` على الدومين الصحيح.
- تفعيل HTTPS.
- نسخ قاعدة البيانات ومجلد `storage/app/public` احتياطيًا.
- مراجعة سياسة الخصوصية لأن الموقع يسجل إحصائيات الزيارات وUser Agent.

## هيكل مختصر

```text
app/
├── Http/Controllers/        # واجهة الموقع ولوحة التحكم
├── Http/Requests/Admin/     # التحقق من مدخلات الإدارة
├── Http/Middleware/         # اللغة، الإدارة وتتبع الزيارات
└── Models/                  # المقالات، المشاريع، الوسوم وغيرها

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/
    ├── admin/
    ├── articles/
    ├── projects/
    └── layouts/
```

## الملكية

هذا المشروع هو الموقع الشخصي والمهني لـKareem Shalaby. راجع مالك المشروع قبل إعادة استخدام المحتوى الشخصي، السيرة الذاتية، الصور أو المقالات.
