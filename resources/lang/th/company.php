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
    'company_details' => 'รายละเอียดบริษัท',
    'company_logo' => 'โลโก้บริษัท',
    'company_name' => 'ชื่อบริษัท',
    'about_business' => 'เกี่ยวกับธุรกิจ',
    'Describe what makes your ad unique...' => 'อธิบายว่าทำไมโฆษณาของคุณเป็นเอกลักษณ์...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'Wechat',
    'phone' => 'โทรศัพท์',
    'website' => 'เว็บไซต์',
    'business_category' => 'ประเภทธุรกิจ',
    'company_size' => 'ขนาดบริษัท (รายได้)',
    'registration_number' => 'หมายเลขการลงทะเบียนบริษัท',
    'submit' => 'ยื่น',

    'company_payment_details' => 'รายละเอียดการชำระเงินของบริษัท',
    // 'bank_account_number' => 'หมายเลขบัญชีธนาคาร',
    // 'confirm_bank_account_number' => 'ยืนยันหมายเลขบัญชีธนาคาร',
    // 'bank_account_holder_name' => 'ชื่อเจ้าของบัญชีธนาคาร',
    'payment_details' => 'รายละเอียดการชำระเงิน',

    'add_new_address' => 'เพิ่มที่อยู่ใหม่',
    'company_address' => 'ที่อยู่บริษัท',
    'Set as default address' => 'ตั้งเป็นที่อยู่เริ่มต้น',
    'address' => 'ที่อยู่',
    'company_city' => 'เมือง',
    'company_state' => 'รัฐ',
    'company_pincode' => 'รหัสไปรษณีย์หรือหมายเลข',
    'company_country' => 'ประเทศ',
    'default_address' => 'ที่อยู่เริ่มต้น',
    'action' => 'การดำเนินการ',
    'Oops...' => 'อุ๊ปส์...',
    'Are you sure you want to delete this address?' => 'คุณแน่ใจหรือไม่ว่าต้องการลบที่อยู่นี้?',
    'You wont be able to revert this !' => 'คุณจะไม่สามารถกลับไปได้!',
    'You can not delete default selected address!' => 'คุณไม่สามารถลบที่อยู่ที่ถูกเลือกเป็นค่าเริ่มต้นได้!',
    'Yes, delete it!' => 'ใช่ ลบออก!',

    'edit_company_details' => 'แก้ไขรายละเอียดบริษัท',
    'view_company_details' => 'ดูรายละเอียดบริษัท',
    'company_information' => 'ข้อมูลบริษัท',
    'about' => 'เกี่ยวกับ',
    'email' => 'อีเมล',
    'company_email' => 'อีเมลของบริษัท',
    'user_information' => 'ข้อมูลผู้ใช้',


    //company payment
    'select_currency' => 'เลือกสกุลเงิน',
    'add_payment_detail' => 'เพิ่มรายละเอียดการชำระเงิน',
    'currently_not_available' => 'ปัจจุบันไม่มีให้บริการสำหรับสกุลเงินนี้',
    'company_payment_list' => 'รายการชำระเงินของบริษัท',
    'account_holder_name' => 'ชื่อผู้ถือบัญชี',
    'account_number' => 'หมายเลขบัญชี',
    'country' => 'ประเทศ',
    'default_payment' => 'การชำระเงินเริ่มต้น',
    'Are you sure you want to delete this Payment?' => 'คุณแน่ใจหรือไม่ว่าต้องการลบการชำระเงินนี้?',
    'You can not delete default selected Payment!' => 'คุณไม่สามารถลบการชำระเงินที่เลือกเริ่มต้นได้!',
    'Set as default Payment' => 'ตั้งเป็นการชำระเงินเริ่มต้น',
    'invalid_price' => 'ราคาที่คุณป้อนต้องเป็นราคาที่ถูกต้องหรือไม่ต่ำกว่า :price_currency',
    'can_not_use_bad_word_in_description' => 'ไม่สามารถใช้คำ <b>:badword</b> ในคำอธิบายได้!',
    'description_length_not_greater_then' => 'โปรดทราบว่าเราเชื่อว่าความยาวสูงสุดของคำอธิบายคือ ' . config('settings.single.max_word_description') . ' คำเป็นวิธีที่ดีที่สุดเพื่อให้แน่ใจว่าผู้ใช้ได้รับประสบการณ์ที่ดีและเหมาะสมที่สุด และเพื่อเพิ่มประสิทธิภาพ SEO',
    'description_length_not_less_then' => 'โปรดทราบว่าเราเชื่อว่าความยาวขั้นต่ำของคำอธิบายคือ ' . config('settings.single.min_word_description') . ' คำเป็นวิธีที่ดีที่สุดเพื่อให้แน่ใจว่าผู้ใช้ได้รับประสบการณ์ที่ดีและเหมาะสมที่สุด และเพื่อเพิ่มประสิทธิภาพ SEO',
    'address_stored_successfully' => 'เพิ่มที่อยู่บริษัทเรียบร้อยแล้ว...',
    'address_updated_successfully' => 'อัปเดตที่อยู่บริษัทเรียบร้อยแล้ว...',
    'company_profile_updated' => 'อัปเดตโปรไฟล์บริษัทเรียบร้อยแล้ว...',
    'payment_details_submitted_successfully' => 'รายละเอียดการชำระเงินของบริษัทถูกส่งเรียบร้อยแล้ว',
    'payment_details_updated_successfully' => 'รายละเอียดการชำระเงินของบริษัทได้รับการสร้างหรืออัปเดตเรียบร้อยแล้ว',
    'min_max_word_description' => 'คำอธิบายต้องมีความยาวอย่างน้อย ' . config('settings.single.min_word_description') . ' และไม่เกิน ' . config('settings.single.max_word_description') . ' คำ',
    'title_character_limit' => 'จำกัดจำนวนอักขระในหัวเรื่อง',
    'title_length_not_greater_then' => 'เพื่อให้แน่ใจว่าชื่อโฆษณาของคุณเป็นชัดเจน มีข้อมูลอธิบายอย่างเพียงพอ และง่ายต่อการอ่าน รวมถึงเพื่อเพิ่มประสิทธิภาพ SEO เราได้กำหนดความยาวสูงสุดในชื่อโฆษณา ปัจจุบันความยาวสูงสุดถูกตั้งไว้ไม่เกิน ' . config('settings.single.title_character_limit') . ' ตัวอักษร',
    'title_character_minimum_limit' => 'ขีด จำกัด ตัวอักษร ขั้น ต่ำ ของ ชื่อ โฆษณา',
    'title_length_not_less_then' => 'เพื่อให้แน่ใจว่าชื่อโฆษณาของคุณเป็นชัดเจน มีข้อมูลอธิบายอย่างเพียงพอ และง่ายต่อการอ่าน รวมถึงเพื่อเพิ่มประสิทธิภาพ SEO เราได้กำหนดความยาวขั้นต่ำในชื่อโฆษณา ปัจจุบันความยาวขั้นต่ำถูกตั้งไว้ไม่น้อยกว่า ' . config('settings.single.title_character_minimum_limit') . ' ตัวอักษร',
    'max_word_description' => 'คำอธิบายสูงสุด 500 คำ',
    'about_company' => 'เกี่ยวกับบริษัท',
    'company_description_length_not_greater_then' => 'ความยาวของคำอธิบายไม่เกิน 500 คำ!',

    'primary_email' => 'อีเมลหลัก',
    'upgrade_post_package' => 'อัพเกรดแพ็คเกจโพสต์',
    'default_business_type' => 'ประเภทธุรกิจเริ่มต้น',
    'information' => 'ข้อมูล',
    'enter_information' => 'กรอกข้อมูล',

    'business_social_media' => 'ลิงก์เว็บไซต์สื่อสังคมของธุรกิจนี้:',
    'You will receive your payments on your default payment details' => 'คุณจะได้รับการชำระเงินของคุณผ่านรายละเอียดการชำระเงินเริ่มต้นของคุณ'
];
