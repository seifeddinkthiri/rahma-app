<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول الحقل.',
    'accepted_if' => 'يجب قبول الحقل عندما يكون الحقل الآخر هو :value.',
    'active_url' => 'الرابط غير صالح.',
    'after' => 'يجب أن يكون التاريخ بعد :date.',
    'after_or_equal' => 'يجب أن يكون التاريخ بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي الحقل على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي الحقل على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي الحقل على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون الحقل مصفوفة.',
    'before' => 'يجب أن يكون التاريخ قبل :date.',
    'before_or_equal' => 'يجب أن يكون التاريخ قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'يجب أن يكون الحقل بين :min و :max.',
        'file' => 'يجب أن يكون حجم الملف بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون طول النص بين :min و :max أحرف.',
        'array' => 'يجب أن يحتوي الحقل على بين :min و :max عنصر.',
    ],
    'boolean' => 'يجب أن يكون الحقل صحيح أو خطأ.',
    'confirmed' => 'تأكيد الحقل غير مطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'التاريخ غير صالح.',
    'date_equals' => 'يجب أن يكون التاريخ يساوي :date.',
    'date_format' => 'لا يتوافق مع الصيغة :format.',
    'declined' => 'يجب رفض الحقل.',
    'declined_if' => 'يجب رفض الحقل عندما يكون الحقل الآخر هو :value.',
    'different' => 'يجب أن يكون الحقل والحقل الآخر مختلفين.',
    'digits' => 'يجب أن يحتوي الحقل على :digits أرقام.',
    'digits_between' => 'يجب أن يكون الحقل بين :min و :max أرقام.',
    'dimensions' => 'الصورة ذات أبعاد غير صحيحة.',
    'distinct' => 'الحقل له قيمة مكررة.',
    'email' => 'يجب أن يكون عنوان البريد الإلكتروني صحيحًا.',
    'ends_with' => 'يجب أن ينتهي بواحد من القيم التالية: :values.',
    'exists' => 'الحقل المحدد غير صحيح.',
    'file' => 'يجب أن يكون ملفًا.',
    'filled' => 'الحقل يجب أن يحتوي على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن يكون الحقل أكبر من :value.',
        'file' => 'يجب أن يكون حجم الملف أكبر من :value كيلوبايت.',
        'string' => 'يجب أن يكون طول النص أكبر من :value أحرف.',
        'array' => 'يجب أن يحتوي الحقل على أكثر من :value عنصر.',
    ],
    'gte' => [
        'numeric' => 'يجب أن يكون الحقل أكبر من أو يساوي :value.',
        'file' => 'يجب أن يكون حجم الملف أكبر من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يكون طول النص أكبر من أو يساوي :value أحرف.',
        'array' => 'يجب أن يحتوي الحقل على :value عنصر أو أكثر.',
    ],
    'image' => 'يجب أن يكون ملفًا صورة.',
    'in' => 'الحقل المحدد غير صحيح.',
    'in_array' => 'الحقل غير موجود في :other.',
    'integer' => 'يجب أن يكون الحقل عددًا صحيحًا.',
    'ip' => 'يجب أن يكون الحقل عنوان IP صالحًا.',
    'ipv4' => 'يجب أن يكون الحقل عنوان IPv4 صالحًا.',
    'ipv6' => 'يجب أن يكون الحقل عنوان IPv6 صالحًا.',
    'json' => 'يجب أن يكون الحقل نص JSON صالحًا.',
    'lt' => [
        'numeric' => 'يجب أن يكون الحقل أقل من :value.',
        'file' => 'يجب أن يكون حجم الملف أقل من :value كيلوبايت.',
        'string' => 'يجب أن يكون طول النص أقل من :value أحرف.',
        'array' => 'يجب أن يحتوي الحقل على أقل من :value عنصر.',
    ],
    'lte' => [
        'numeric' => 'يجب أن يكون الحقل أقل من أو يساوي :value.',
        'file' => 'يجب أن يكون حجم الملف أقل من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يكون طول النص أقل من أو يساوي :value أحرف.',
        'array' => 'يجب أن يحتوي الحقل على أقل من :value عنصر.',
    ],
    'max' => [
        'numeric' => 'لا يجب أن يكون الحقل أكبر من :max.',
        'file' => 'لا يجب أن يكون حجم الملف أكبر من :max كيلوبايت.',
        'string' => 'لا يجب أن يكون طول النص أكبر من :max أحرف.',
        'array' => 'لا يجب أن يحتوي الحقل على أكثر من :max عنصر.',
    ],
    'mimes' => 'يجب أن يكون الحقل ملفًا من النوع: :values.',
    'mimetypes' => 'يجب أن يكون الحقل ملفًا من النوع: :values.',
    'min' => [
        'numeric' => 'يجب أن يكون الحقل على الأقل :min.',
        'file' => 'يجب أن يكون حجم الملف على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يكون طول النص على الأقل :min أحرف.',
        'array' => 'يجب أن يحتوي الحقل على على الأقل :min عنصر.',
    ],
    'multiple_of' => 'يجب أن يكون الحقل مضاعف لـ :value.',
    'not_in' => 'الحقل المحدد غير صحيح.',
    'not_regex' => 'تنسيق الحقل غير صالح.',
    'numeric' => 'يجب أن يكون الحقل عددًا.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'الحقل يجب أن يكون موجودًا.',
    'prohibited' => 'الحقل ممنوع.',
    'prohibited_if' => 'الحقل ممنوع عندما يكون الحقل الآخر هو :value.',
    'prohibited_unless' => 'الحقل ممنوع ما لم يكون الحقل الآخر هو أحد القيم :values.',
    'prohibits' => 'الحقل يمنع وجود الحقل الآخر.',
    'regex' => 'تنسيق الحقل غير صالح.',
    'required' => 'الحقل مطلوب.',
    'required_if' => 'الحقل مطلوب عندما يكون الحقل الآخر هو :value.',
    'required_unless' => 'الحقل مطلوب ما لم يكون الحقل الآخر هو أحد القيم :values.',
    'required_with' => 'الحقل مطلوب عندما يكون الحقول التالية موجودة: :values.',
    'required_with_all' => 'الحقل مطلوب عندما تكون الحقول التالية موجودة: :values.',
    'required_without' => 'الحقل مطلوب عندما تكون الحقول التالية غير موجودة: :values.',
    'required_without_all' => 'الحقل مطلوب عندما لا تكون الحقول التالية موجودة: :values.',
    'same' => 'يجب أن يتطابق الحقل والحقل الآخر.',
    'size' => [
        'numeric' => 'يجب أن يكون الحقل :size.',
        'file' => 'يجب أن يكون حجم الملف :size كيلوبايت.',
        'string' => 'يجب أن يكون طول النص :size أحرف.',
        'array' => 'يجب أن يحتوي الحقل على :size عنصر.',
    ],
    'starts_with' => 'يجب أن يبدأ الحقل بواحد من القيم التالية: :values.',
    'string' => 'يجب أن يكون الحقل نصًا.',
    'timezone' => 'يجب أن يكون الحقل منطقة زمنية صالحة.',
    'unique' => 'الحقل مأخوذ بالفعل.',
    'uploaded' => 'فشل في تحميل الحقل.',
    'url' => 'الحقل غير صالح.',
    'uuid' => 'يجب أن يكون الحقل معرف UUID صالح.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */
    'attributes' => [
        'name' => 'الاسم',
        'address' => 'العنوان',
        'cin' => 'رقم البطاقة الوطنية',
        'phone' => 'رقم الهاتف',
        'birth_date' => 'تاريخ الميلاد',
        'birth_city' => 'مدينة الميلاد',
        'social_status' => 'الوضع الاجتماعي',
        'health_insurance' => 'تأمين صحي',
        'kinship' => 'صلة القرابة',
        'caregiver' => 'مقدم الرعاية',
        'education_level' => 'مستوى التعليم',
        'job' => 'الوظيفة',
        'job_place' => 'مكان العمل',
        'good' => 'جيد',
        'disease' => 'الأمراض',
        'disability' => 'الإعاقة',
        'disability_card_number' => 'رقم بطاقة الإعاقة',
        'beneficial' => 'المستفيد',
        'type' => 'النوع',
        'value' => 'القيمة',
        'date' => 'التاريخ',
        'intervenor' => 'المتدخل',
        'intervenor_phone' => 'رقم هاتف المتدخل',
        'file' => 'الملف',
        'title' => 'العنوان',
        'notes' => 'الملاحظات',
        'family' => 'العائلة',
        'status'=>'الحالة',
        'photo'=>'الصورة',
        'first_name' => 'الاسم الأول',
        'last_name' => 'الاسم الأخير',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'owner' => 'أدمن',
        'Sanitation' => 'الصرف الصحي',
        'electricity' => 'الكهرباء',
        'water' => 'الماء',
        'ventilation' => 'التهوية',
        'status' => 'الحالة',
        'allocation_price' => 'سعر الكراء',
        'desciption' => 'الوصف',
        "grant_source"=>'مصدر المنحة',
        "grant_value"=>'قيمة المنحة',


    ],


];
