# 🏥 توثيق قاعدة بيانات مركز مطمئنة الطبي
> **اسم قاعدة البيانات:** mutmainah_new2  
> **آخر باك اب:** 9-3-2026  
> **آخر مراجعة مسجلة:** 15-3-2026  
> **آخر دفعة مسجلة:** 8-3-2026  
> **صيغة التواريخ:** `D-M-YYYY` (مثال: 8-3-2026) مخزنة كـ VARCHAR

---

## 📋 فهرس الجداول حسب الأهمية

| الجدول | الوصف | عدد السجلات |
|--------|-------|-------------|
| `vouchers` | سندات القيد المحاسبية | 383,622 |
| `patient_services` | خدمات المرضى | 118,267 |
| `kpayments` | فواتير ومدفوعات المرضى | 111,137 |
| `rec` | سجلات المراجعات الطبية | 93,890 |
| `kstu` | بيانات المرضى الكاملة | ~17,000+ |
| `acck` | الحسابات المحاسبية | 16,809 |
| `discountk` | الخصومات | 14,264 |
| `databackup` | النسخ الاحتياطية | 2,882 |
| `appoint_cancel` | المواعيد الملغية | 699 |
| `employees` | الموظفون والأطباء | 197 |
| `clinic` | العيادات | 136 |
| `ana_results` | نتائج التحاليل | 309 |
| `ana` | أنواع التحاليل وأسعارها | 134 |
| `branchk` | الفروع | 70 |
| `attend` | الحضور والغياب | 31 |

---

## 🔑 الجداول الأساسية بالتفصيل

### 1. جدول `kstu` — بيانات المرضى
> الجدول الرئيسي لتخزين معلومات المرضى

| العمود | النوع | الوصف |
|--------|-------|-------|
| `id` | int PRI | المعرف الفريد للمريض |
| `full_name` | varchar(255) | الاسم الكامل |
| `phone` | varchar(50) | رقم الهاتف |
| `email` | varchar(50) | البريد الإلكتروني |
| `date_of_birth` | varchar(255) | تاريخ الميلاد |
| `gender` | int | الجنس (1=ذكر, 2=أنثى) |
| `nationality` | int | الجنسية (مرتبط بجدول nations) |
| `reg_date` | varchar(250) | تاريخ التسجيل |
| `assur_no` | varchar(100) | رقم التأمين |
| `assur_date` | varchar(100) | تاريخ انتهاء التأمين |
| `class_id` | int | درجة المريض/التصنيف |
| `file_id` | int | رقم الملف |
| `com_id` | int | رقم الشركة (مرتبط بجدول التأمين) |
| `blood_group` | varchar | فصيلة الدم |
| `weight` | float | الوزن |
| `height` | int | الطول |
| `chronic` | text | الأمراض المزمنة |
| `notes` | text | ملاحظات |

---

### 2. جدول `rec` — سجلات المراجعات
> كل زيارة مريض للعيادة تُسجل هنا

| العمود | النوع | الوصف |
|--------|-------|-------|
| `id` | int PRI | معرف المراجعة |
| `st_id` | int MUL | معرف المريض → `kstu.id` |
| `clinic_id` | int | معرف العيادة → `clinic.id` |
| `doc_id` | int | معرف الطبيب → `employees.id` |
| `service_id` | int | معرف الخدمة |
| `rec_date` | varchar | تاريخ المراجعة (D-M-YYYY) |
| `rec_time` | varchar | وقت المراجعة |
| `pdate` | varchar | تاريخ إضافي |
| `sym` | text | الأعراض |
| `dia` | text | التشخيص |
| `pres` | text | الوصفة الطبية |
| `pressure` | varchar | ضغط الدم |
| `heat` | varchar | الحرارة |
| `pulse` | varchar | النبض |
| `diab` | varchar | السكر |
| `state_id` | int | حالة المراجعة |
| `type_id` | int | نوع المراجعة |
| `order_id` | int MUL | رقم الطلب |
| `serv_no` | int MUL | رقم الخدمة |
| `date_serial` | float MUL | الرقم التسلسلي للتاريخ |

---

### 3. جدول `kpayments` — المدفوعات والفواتير
> كل عملية دفع أو فاتورة تُسجل هنا

| العمود | النوع | الوصف |
|--------|-------|-------|
| `id` | int PRI | معرف الفاتورة |
| `serial_no` | int MUL | الرقم التسلسلي |
| `user_id` | int MUL | معرف المريض → `kstu.id` |
| `clinic_id` | int | معرف العيادة |
| `doc_id` | int | معرف الطبيب |
| `credit` | float | المبلغ المدفوع |
| `net` | float | الصافي |
| `pdate` | varchar | تاريخ الدفع (D-M-YYYY) |
| `vno` | varchar | رقم القسيمة |
| `rec_id` | int MUL | معرف المراجعة → `rec.id` |
| `payment_method` | int | طريقة الدفع |
| `status` | int | الحالة |
| `discount` | float | الخصم |
| `cash_amount` | float | المبلغ النقدي |
| `net_amount` | float | الصافي النهائي |
| `insur_amount` | float | مبلغ التأمين |
| `type_id` | int | نوع الدفع |
| `com_id` | int | معرف الشركة |

---

### 4. جدول `appoint_cancel` — المواعيد الملغية
> سجل المواعيد التي تم إلغاؤها

| العمود | النوع | الوصف |
|--------|-------|-------|
| `id` | int PRI | معرف السجل |
| `clinic_id` | int | معرف العيادة |
| `doc_id` | int | معرف الطبيب |
| `user_id` | int | معرف المريض |
| `rec_date` | varchar | تاريخ الموعد (D-M-YYYY) |
| `rec_time` | varchar | وقت الموعد |
| `cancel_time` | varchar | وقت الإلغاء |
| `type_id` | int | نوع الإلغاء |
| `st_id` | int | معرف الحالة |

---

### 5. جدول `clinic` — العيادات
> بيانات العيادات المتاحة في المركز (136 عيادة)

| العمود | النوع | الوصف |
|--------|-------|-------|
| `id` | int PRI | معرف العيادة |
| `name` | varchar | اسم العيادة |
| `clinic_time` | int | وقت العيادة |

---

### 6. جدول `employees` — الموظفون والأطباء
> بيانات الموظفين والأطباء (197 موظف)

| العمود | النوع | الوصف |
|--------|-------|-------|
| `id` | int PRI | معرف الموظف |
| `name` | varchar | اسم الموظف |
| `date` | varchar | تاريخ التوظيف |
| `date_of_birth` | varchar | تاريخ الميلاد |
| `work_date` | varchar | تاريخ بدء العمل |

---

### 7. جدول `vouchers` — السندات المحاسبية
> أكبر جدول في قاعدة البيانات (383,622 سجل)

| العمود | النوع | الوصف |
|--------|-------|-------|
| `id` | int PRI | معرف السند |
| `account_id` | varchar MUL | معرف الحساب |
| `credit` | float | دائن |
| `debit` | float | مدين |
| `pdate` | varchar | تاريخ السند (D-M-YYYY) |
| `vno` | varchar | رقم السند |
| `serial_no` | int MUL | الرقم التسلسلي |
| `rec_id` | int MUL | معرف المراجعة |
| `date_serial` | int MUL | الرقم التسلسلي للتاريخ |
| `stu_id` | int | معرف المريض |
| `com_id` | int | معرف الشركة |

---

## 🔗 العلاقات بين الجداول

```
kstu (المرضى)
  ├── rec.st_id          → زيارات المريض
  ├── kpayments.user_id  → فواتير المريض
  ├── appoint_cancel.user_id → مواعيد ملغية
  └── patient_services.st_id → خدمات المريض

clinic (العيادات)
  ├── rec.clinic_id      → مراجعات العيادة
  ├── kpayments.clinic_id → فواتير العيادة
  └── appoint_cancel.clinic_id → مواعيد العيادة

employees (الأطباء)
  ├── rec.doc_id         → مراجعات الطبيب
  ├── kpayments.doc_id   → فواتير الطبيب
  └── appoint_cancel.doc_id → مواعيد الطبيب

rec (المراجعات)
  ├── kpayments.rec_id   → فاتورة المراجعة
  └── vouchers.rec_id    → سند المراجعة
```

---

## 📅 ملاحظات مهمة على التواريخ

```sql
-- ❌ طريقة خاطئة (تقرأ التاريخ كنص)
WHERE SUBSTRING(pdate, 7, 4) = '2026'

-- ✅ طريقة صحيحة (للسنة فقط)
WHERE SUBSTRING_INDEX(pdate, '-', -1) = '2026'

-- ✅ أفضل طريقة (تحويل حقيقي للتاريخ)
WHERE YEAR(STR_TO_DATE(pdate, '%d-%m-%Y')) = 2026

-- ✅ للحصول على أحدث تاريخ
SELECT MAX(STR_TO_DATE(pdate, '%d-%m-%Y')) FROM kpayments;
```

---

## 📊 إحصائيات البيانات حسب السنة

### المراجعات (rec):
| السنة | العدد |
|-------|-------|
| 2017 | 806 |
| 2018 | 1,897 |
| 2019 | 2,656 |
| 2020 | 2,931 |
| 2021 | 2,677 |
| 2022 | 2,535 |
| 2023 | 2,246 |
| 2024 | 1,590 |
| 2025 | 1,632 |
| 2026 | 1,480 |

### المدفوعات (kpayments):
| السنة | العدد |
|-------|-------|
| 2017 | 820 |
| 2018 | 2,106 |
| 2019 | 2,963 |
| 2020 | 3,375 |
| 2021 | 2,966 |
| 2022 | 15,110 |
| 2023 | 13,922 |
| 2024 | 13,041 |
| 2025 | 12,397 |
| 2026 | 2,033 |

### المواعيد الملغية (appoint_cancel):
| السنة | العدد |
|-------|-------|
| 2017 | أقدم البيانات |
| 2022 | 29 |
| 2023 | 16 |
| 2024 | 4 |
| 2025 | 16 |

---

## 💡 استعلامات مفيدة جاهزة

### عدد مراجعات سنة معينة:
```sql
SELECT COUNT(*) FROM rec 
WHERE SUBSTRING_INDEX(rec_date, '-', -1) = '2026';
```

### مراجعات مريض معين مع اسمه:
```sql
SELECT r.rec_date, r.rec_time, k.full_name, r.clinic_id, r.doc_id
FROM rec r
JOIN kstu k ON r.st_id = k.id
WHERE k.full_name LIKE '%اسم المريض%'
ORDER BY STR_TO_DATE(r.rec_date, '%d-%m-%Y') DESC;
```

### فواتير مريض معين:
```sql
SELECT k.full_name, p.pdate, p.credit, p.net_amount, p.vno
FROM kpayments p
JOIN kstu k ON p.user_id = k.id
WHERE k.full_name LIKE '%اسم المريض%'
ORDER BY STR_TO_DATE(p.pdate, '%d-%m-%Y') DESC;
```

### إجمالي الدخل حسب السنة:
```sql
SELECT 
    SUBSTRING_INDEX(pdate, '-', -1) AS السنة,
    COUNT(*) AS عدد_الفواتير,
    SUM(credit) AS إجمالي_الدخل
FROM kpayments
GROUP BY SUBSTRING_INDEX(pdate, '-', -1)
ORDER BY السنة;
```

### مراجعات عيادة معينة:
```sql
SELECT r.rec_date, k.full_name, r.doc_id, r.sym, r.dia
FROM rec r
JOIN kstu k ON r.st_id = k.id
WHERE r.clinic_id = [رقم_العيادة]
ORDER BY STR_TO_DATE(r.rec_date, '%d-%m-%Y') DESC;
```

### أكثر الأطباء مراجعات:
```sql
SELECT 
    e.name AS اسم_الطبيب,
    COUNT(r.id) AS عدد_المراجعات
FROM rec r
JOIN employees e ON r.doc_id = e.id
GROUP BY r.doc_id
ORDER BY عدد_المراجعات DESC
LIMIT 10;
```

---

## ⚠️ تنبيهات مهمة

1. **التواريخ مخزنة كـ VARCHAR** وليس DATE — استخدم دائماً `STR_TO_DATE(date, '%d-%m-%Y')` للمقارنة الصحيحة
2. **جدول kstu هو مرجع المرضى** وليس جدول `patients` (الذي يبدو فارغاً أو غير مستخدم)
3. **الربط الأساسي للمريض:** `rec.st_id = kstu.id` و `kpayments.user_id = kstu.id`
4. **العيادات 136** والأطباء ضمن جدول `employees`
5. **أكبر جدول** هو `vouchers` (383,622 سجل) — جدول محاسبي