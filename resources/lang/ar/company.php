<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the global website.
    |
    */
    'company_details' => 'تفاصيل الشركة',
    'company_logo' => 'شعار الشركة',
    'company_name' => 'اسم الشركة',
    'about_business' => 'نبذة عن العمل',
    'Describe what makes your ad unique...' => 'صف ما يجعل إعلانك فريدًا ...',
    'facebook' => 'فيسبوك',
    'twitter' => 'تويتر',
    'instagram' => 'إنستجرام',
    'linkedIn' => 'لينكد إن',
    'kvk' => 'KVK',
    'wechat' => 'WeChat',
    'phone' => 'رقم الهاتف',
    'website' => 'موقع الويب',
    'business_category' => 'فئة الأعمال',
    'company_size' => 'حجم الشركة (الإيرادات)',
    'registration_number' => 'رقم تسجيل الشركة',
    'submit' => 'إرسال',
    
    'company_payment_details' => 'تفاصيل دفع الشركة',
    // 'bank_account_number' => 'رقم الحساب المصرفي',
    // 'confirm_bank_account_number' => 'تأكيد رقم الحساب المصرفي',
    // 'bank_account_holder_name' => 'اسم صاحب الحساب المصرفي',
    'payment_details' => 'تفاصيل الدفع',
    
    'add_new_address' => 'إضافة عنوان جديد',
    'company_address' => 'عنوان الشركة',
    'Set as default address' => 'تعيين كعنوان افتراضي',
    'address' => 'العنوان',
    'company_city' => 'المدينة',
    'company_state' => 'الولاية',
    'company_pincode' => 'الرمز البريدي',
    'company_country' => 'البلد',
    'default_address' => 'العنوان الافتراضي',
    'action' => 'الإجراء',
    'Oops...' => 'عذراً...',
    'Are you sure you want to delete this address?' => 'هل أنت متأكد أنك تريد حذف هذا العنوان؟',
    'You wont be able to revert this !' => 'لن تكون قادرًا على التراجع عن هذا!',
    'You can not delete default selected address!' => 'لا يمكنك حذف العنوان المحدد افتراضيًا!',
    'Yes, delete it!' => 'نعم، احذفه!',
    
    'edit_company_details' => 'تعديل تفاصيل الشركة',
    'view_company_details' => 'عرض تفاصيل الشركة',
    'company_information' => 'معلومات الشركة',
    'about' => 'حول',
    'email' => 'البريد الإلكتروني',
    'company_email' => 'بريد الشركة',
    'user_information' => 'معلومات المستخدم',
    
        //company payment
    'select_currency' => 'اختر العملة',
    'add_payment_detail' => 'إضافة تفاصيل الدفع',
    'currently_not_available' => 'غير متاح حاليًا لهذه العملة',
    'company_payment_list' => 'قائمة دفع الشركة',
    'account_holder_name' => 'اسم حامل الحساب',
    'account_number' => 'رقم الحساب',
    'country' => 'الدولة',
    'default_payment' => 'الدفع الافتراضي',
    'Are you sure you want to delete this Payment?' => 'هل أنت متأكد أنك تريد حذف هذا المدفوع؟',
    'You can not delete default selected Payment!' => 'لا يمكنك حذف الدفع المحدد بشكل افتراضي!',
    'Set as default Payment' => 'تعيين كدفع افتراضي',
    'invalid_price' => 'يجب أن يكون السعر الذي تدخله سعرًا صالحًا أو لا يقل عن :price_currency',
    'can_not_use_bad_word_in_description' => 'لا يمكن استخدام كلمات <b>:badword</b> في الوصف!',
    'description_length_not_greater_then' => 'يرجى ملاحظة أننا نعتقد أن الحد الأقصى لطول الوصف من ' . config('settings.single.max_word_description') . ' الكلمات هو الأمثل لضمان تجربة مستخدم إيجابية وتحسين محركات البحث.',
    'description_length_not_less_then' => 'يرجى ملاحظة أننا نعتقد أن الحد الأدنى لطول الوصف من ' . config('settings.single.min_word_description') . ' الكلمات هو الأمثل لضمان تجربة مستخدم إيجابية وتحسين محركات البحث.',
    'address_stored_successfully' => 'تم إضافة عنوان الشركة بنجاح...',
    'address_updated_successfully' => 'تم تحديث عنوان الشركة بنجاح...',
    'company_profile_updated' => 'تم تحديث ملف الشركة بنجاح...',
    'payment_details_submitted_successfully' => 'تم إرسال تفاصيل دفع الشركة بنجاح',
    'payment_details_updated_successfully' => 'تم إنشاء أو تحديث تفاصيل دفع الشركة بنجاح',
    'min_max_word_description' => 'الحد الأدنى ' . config('settings.single.min_word_description') . ' والحد الأقصى ' . config('settings.single.max_word_description') . ' الكلمات المسموح بها في الوصف',
    'title_character_limit' => 'حد الأحرف في العنوان',
    'title_length_not_greater_then' => 'لضمان وضوح وإيضاح عناوين الإعلانات الخاصة بك، وتسهيل قراءتها وتحسينها لمحركات البحث، نطبق الآن حداً أقصى لعدد الأحرف في العنوان، حيث يجب أن لا يتجاوز ' . config('settings.single.title_character_limit') . ' حرفًا',
    'title_character_minimum_limit' => 'الحد الأدنى من عدد أحرف العنوان',
    'title_length_not_less_then' => 'لضمان وضوح وإيضاح عناوين الإعلانات الخاصة بك، وتسهيل قراءتها وتحسينها لمحركات البحث، نطبق الآن حداً أدنى لعدد الأحرف في العنوان، حيث يجب أن لا يقل عن  ' . config('settings.single.title_character_minimum_limit') . ' حرفًا',
    'max_word_description' => 'الحد الأقصى 500 كلمة في الوصف',
    'about_company' => 'نبذة عن الشركة',
    'company_description_length_not_greater_then' => 'لا يجوز أن يزيد طول الوصف عن 500 كلمة!',

    'primary_email' => 'البريد الإلكتروني الأساسي',
    'upgrade_post_package' => 'ترقية حزمة الإعلانات',
    'default_business_type' => 'نوع العمل الافتراضي',
    'information' => 'المعلومات',
    'enter_information' => 'أدخل المعلومات',

    'business_social_media' => 'وسائل التواصل الاجتماعي لهذا العمل:',
    'You will receive your payments on your default payment details' => 'سوف تتلقى مدفوعاتك على تفاصيل الدفع الافتراضية الخاصة بك'
];
