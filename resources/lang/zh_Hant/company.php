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
    'company_details' => '公司詳細信息',
    'company_logo' => '公司標誌',
    'company_name' => '公司名稱',
    'about_business' => '關於業務',
    'Describe what makes your ad unique...' => '描述使您的廣告獨特的特點...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => '微信',
    'phone' => '電話',
    'website' => '網站',
    'business_category' => '業務類別',
    'company_size' => '公司規模（收入）',
    'registration_number' => '公司登記號碼',
    'submit' => '提交',

    'company_payment_details' => '公司付款詳情',
    // 'bank_account_number' => '銀行帳戶號碼',
    // 'confirm_bank_account_number' => '確認銀行帳戶號碼',
    // 'bank_account_holder_name' => '銀行帳戶持有人姓名',
    'payment_details' => '付款詳情',

    'add_new_address' => '添加新地址',
    'company_address' => '公司地址',
    'Set as default address' => '設置為默認地址',
    'address' => '地址',
    'company_city' => '城市',
    'company_state' => '省/州',
    'company_pincode' => '郵遞區號或號碼',
    'company_country' => '國家',
    'default_address' => '默認地址',
    'action' => '操作',
    'Oops...' => '糟糕...',
    'Are you sure you want to delete this address?' => '您確定要刪除此地址嗎？',
    'You wont be able to revert this !' => '您將無法撤消此操作！',
    'You can not delete default selected address!' => '您無法刪除已選擇的默認地址！',
    'Yes, delete it!' => '是的，刪除它！',

    'edit_company_details' => '編輯公司詳細資料',
    'view_company_details' => '查看公司詳細資料',
    'company_information' => '公司資訊',
    'about' => '關於',
    'email' => '電子郵件',
    'company_email' => '公司電子郵件',
    'user_information' => '用戶信息',
    
    //company payment
    'select_currency' => '選擇貨幣',
    'add_payment_detail' => '添加付款詳情',
    'currently_not_available' => '當前貨幣暫不支援',
    'company_payment_list' => '公司付款列表',
    'account_holder_name' => '帳戶持有人姓名',
    'account_number' => '帳戶號碼',
    'country' => '國家',
    'default_payment' => '預設付款',
    'Are you sure you want to delete this Payment?' => '您確定要刪除此付款嗎？',
    'You can not delete default selected Payment!' => '您無法刪除預設選定的付款！',
    'Set as default Payment' => '設為預設付款',
    'invalid_price' => '您輸入的價格必須是有效的價格或不低於 :price_currency。',
    'can_not_use_bad_word_in_description' => '描述中不能使用 <b>:badword</b> 字詞！',
    'description_length_not_greater_then' => '請注意，我們認為描述最多可以包含 ' . config('settings.single.max_word_description') . ' 個單詞，這有助於確保正面的用戶體驗和優化您的廣告的 SEO。',
    'description_length_not_less_then' => '請注意，我們認為描述至少應包含 ' . config('settings.single.min_word_description') . ' 個單詞，這有助於確保正面的用戶體驗和優化您的廣告的 SEO。',
    'address_stored_successfully' => '公司地址已成功添加...',
    'address_updated_successfully' => '公司地址已成功更新...',
    'company_profile_updated' => '公司檔案已成功更新...',
    'payment_details_submitted_successfully' => '公司付款詳情已成功提交',
    'payment_details_updated_successfully' => '公司付款詳情已成功創建或更新',
    'min_max_word_description' => '描述最少 ' . config('settings.single.min_word_description') . ' 個字，最多 ' . config('settings.single.max_word_description') . ' 個字。',
    'title_character_limit' => '標題字數限制',
    'title_length_not_greater_then' => '為了確保您的廣告標題清晰、資訊豐富、易於閱讀，並且經過優化以提高 SEO 效果，我們實施了最大標題字數的要求。目前，標題最大長度為不超過 ' . config('settings.single.title_character_limit') . ' 個字元。',
    'title_character_minimum_limit' => '標題字數最少限制',
    'title_length_not_less_then' => '為了確保您的廣告標題清晰、資訊豐富、易於閱讀，並且經過優化以提高 SEO 效果，我們實施了最小標題字數的要求。目前，標題最小長度為不少於 ' . config('settings.single.title_character_minimum_limit') . ' 個字元。',
    'max_word_description' => '描述最多 500 個字。',
    'about_company' => '關於公司',
    'company_description_length_not_greater_then' => '描述最多 500 個字！',

    'primary_email' => '主要電子郵件',
    'upgrade_post_package' => '升級廣告套餐',
    'default_business_type' => '默認業務類型',
    'information' => '資訊',
    'enter_information' => '輸入資訊',
    
    'business_social_media' => '該企業的社交媒體網站鏈接：',
    'You will receive your payments on your default payment details' => '您將在默認付款詳細信息上收到付款'

];
