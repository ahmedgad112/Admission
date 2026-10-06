<?php

return [
    'leave' => [
        'submitted' => 'تم إرسال طلب الغياب.',
        'approved' => 'تم قبول طلب الغياب.',
        'rejected' => 'تم رفض طلب الغياب.',
        'cancelled' => 'تم إلغاء طلب الغياب.',
        'overlap' => 'عندك طلب قيد المراجعة أو مقبول يتداخل مع التواريخ دي.',
        'exceeds_balance' => 'الطلب ده أكبر من رصيد أيامك المتبقية (:remaining يوم).',
    ],
    'staff' => [
        'created' => 'تم إنشاء الموظف.',
        'updated' => 'تم تحديث الموظف.',
        'deleted' => 'تم حذف الموظف.',
        'imported' => 'اتضاف :count موظف. واتخطى :skipped صف.',
        'import_none' => 'مفيش موظفين اتعملوا من الشيت.',
        'import_invalid' => 'الصف :line ناقص اسم أو إيميل صحيح.',
        'import_duplicate' => 'الصف :line الإيميل :email موجود قبل كده.',
        'import_department' => 'الصف :line القسم :department مش موجود.',
        'import_branch' => 'الصف :line محتاج قسم عشان الفرع يتحدد.',
        'leave_days_adjusted' => 'تم تعديل أيام حضور :name (:days).',
        'leave_days_below_zero' => 'تقدر تخصم لحد :available يوم بس.',
        'leave_days_above_max' => 'أيام الحضور مش ممكن تزيد عن :max.',
        'leave_days_bulk' => 'تم تعديل أيام حضور :count موظف (:days).',
        'leave_days_bulk_bounds' => 'تعديل حضور :name هيخرج بره حد الشهر (من :min لـ :max).',
        'leave_days_bulk_forbidden' => 'في موظفين محددين مش مسموح تعدّل أيامهم.',
        'leave_days_imported' => 'تم تعديل أيام حضور :count موظف. واتخطى :skipped صف.',
        'leave_days_import_none' => 'مفيش أيام حضور اتعدلت من الشيت.',
        'leave_days_import_invalid' => 'الصف :line محتاج اسم وعدد أيام ونوع الحركة إضافة أو خصم.',
        'leave_days_import_missing' => 'الصف :line مش لاقي :name.',
        'leave_days_import_ambiguous' => 'الصف :line فيه أكتر من موظف باسم :name. ضيف الإيميل.',
        'leave_days_import_bounds' => 'الصف :line هيخلّي حضور :name بره حد الشهر.',
    ],
    'shift' => [
        'created' => 'تم إنشاء الوردية.',
        'updated' => 'تم تحديث الوردية.',
        'deleted' => 'تم حذف الوردية.',
    ],
    'branch' => [
        'created' => 'تم إنشاء الفرع.',
        'updated' => 'تم تحديث الفرع.',
        'deleted' => 'تم حذف الفرع.',
    ],
    'department' => [
        'created' => 'تم إنشاء القسم.',
        'updated' => 'تم تحديث القسم.',
        'deleted' => 'تم حذف القسم.',
    ],
    'roster' => [
        'created' => 'تم إنشاء جلسة الحضور.',
        'updated' => 'تم تحديث جلسة الحضور.',
        'deleted' => 'تم حذف جلسة الحضور.',
    ],
    'task' => [
        'created' => 'تم إنشاء المهمة.',
        'updated' => 'تم تحديث المهمة.',
        'deleted' => 'تم حذف المهمة.',
        'status' => 'تم تحديث حالة المهمة.',
        'commented' => 'تم إضافة التعليق.',
        'attached' => 'تم رفع المرفق.',
    ],
    'attendance' => [
        'saved' => 'تم حفظ مواعيد الحضور.',
        'checked_in' => 'تم تسجيل الحضور بنجاح.',
        'checked_out' => 'تم تسجيل الانصراف بنجاح.',
        'cleared' => 'تم مسح سجلات الحضور.',
    ],
    'profile' => [
        'updated' => 'تم تحديث الملف الشخصي.',
    ],
    'password' => [
        'updated' => 'تم تحديث كلمة المرور.',
    ],
    'timer' => [
        'updated' => 'كود الـ QR هيبقى صالح لمدة :seconds ثانية.',
    ],
    'permissions' => [
        'updated' => 'تم تحديث صلاحيات الأدوار.',
        'role_created' => 'تم إنشاء الدور.',
        'role_updated' => 'تم تحديث الدور.',
        'role_deleted' => 'تم حذف الدور.',
    ],
    'impersonation' => [
        'started' => 'تم تسجيل الدخول كـ :name.',
        'stopped' => 'رجعت لحسابك كـ :name.',
    ],
];
