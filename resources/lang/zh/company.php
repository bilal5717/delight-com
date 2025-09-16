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
    'company_details' => '公司详情',
    'company_logo' => '公司标志',
    'company_name' => '公司名称',
    'about_business' => '关于业务',
    'Describe what makes your ad unique...' => '描述广告的独特之处...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => '微信',
    'phone' => '电话',
    'website' => '网站',
    'business_category' => '业务类别',
    'company_size' => '公司规模（收入）',
    'registration_number' => '公司注册号',
    'submit' => '提交',

    'company_payment_details' => '公司付款详情',
    // 'bank_account_number' => '银行账号',
    // 'confirm_bank_account_number' => '确认银行账号',
    // 'bank_account_holder_name' => '银行账户持有人姓名',
    'payment_details' => '付款详情',

    'add_new_address' => '添加新地址',
    'company_address' => '公司地址',
    'Set as default address' => '设置为默认地址',
    'address' => '地址',
    'company_city' => '城市',
    'company_state' => '州/省',
    'company_pincode' => '邮政编码或编号',
    'company_country' => '国家',
    'default_address' => '默认地址',
    'action' => '操作',
    'Oops...' => '哎呀...',
    'Are you sure you want to delete this address?' => '您确定要删除此地址吗？',
    'You wont be able to revert this !' => '此操作无法撤销！',
    'You can not delete default selected address!' => '您无法删除默认选定的地址！',
    'Yes, delete it!' => '是的，删除它！',

    'edit_company_details' => '编辑公司详情',
    'view_company_details' => '查看公司详情',
    'company_information' => '公司信息',
    'about' => '关于',
    'email' => '电子邮件',
    'company_email' => '公司电子邮件',
    'user_information' => '用户信息',
    
    //company payment
    'select_currency' => '选择货币',
    'add_payment_detail' => '添加付款详情',
    'currently_not_available' => '目前此货币不可用',
    'company_payment_list' => '公司付款列表',
    'account_holder_name' => '账户持有人姓名',
    'account_number' => '账户号码',
    'country' => '国家',
    'default_payment' => '默认付款',
    'Are you sure you want to delete this Payment?' => '您确定要删除此付款吗？',
    'You can not delete default selected Payment!' => '您无法删除默认选择的付款！',
    'Set as default Payment' => '设为默认付款',
    'invalid_price' => '您输入的价格必须是有效的价格，且不低于 :price_currency。',
    'can_not_use_bad_word_in_description' => '描述中不能使用 <b>:badword</b> 这些词！',
    'description_length_not_greater_then' => '请注意，我们认为最多可以使用 ' . config('settings.single.max_word_description') . ' 个单词的描述长度，对于确保积极的用户体验和优化您的广告SEO来说是最佳的。',
    'description_length_not_less_then' => '请注意，我们认为最少可以使用 ' . config('settings.single.min_word_description') . ' 个单词的描述长度，对于确保积极的用户体验和优化您的广告SEO来说是最佳的。',
    'address_stored_successfully' => '公司地址已成功添加...',
    'address_updated_successfully' => '公司地址已成功更新...',
    'company_profile_updated' => '公司资料已成功更新...',
    'payment_details_submitted_successfully' => '公司付款明细已成功提交',
    'payment_details_updated_successfully' => '公司付款明细已成功创建或更新',
    'min_max_word_description' => '描述中允许的最小字数为' . config('settings.single.min_word_description') . '，最大字数为' . config('settings.single.max_word_description') . '。',
    'title_character_limit' => '标题字符限制',
    'title_length_not_greater_then' => '为确保您的广告标题清晰、信息丰富、易于阅读且优化了SEO，我们实施了最大标题长度的要求。当前，最大标题长度设置为不超过' . config('settings.single.title_character_limit') . '个字符。',
    'title_character_minimum_limit' => '标题字符最小限制',
    'title_length_not_less_then' => '为确保您的广告标题清晰、信息丰富、易于阅读且优化了SEO，我们实施了最小标题长度的要求。当前，最小标题长度设置为不少于' . config('settings.single.title_character_minimum_limit') . '个字符。',
    'max_word_description' => '描述中允许的最多500个单词。',
    'about_company' => '关于公司',
    'company_description_length_not_greater_then' => '描述长度不得超过500个单词！',

    'primary_email' => '主要电子邮件',
    'upgrade_post_package' => '升级广告套餐',
    'default_business_type' => '默认业务类型',
    'information' => '信息',
    'enter_information' => '输入信息',

    'business_social_media' => '此业务的社交媒体网站链接：',
    'You will receive your payments on your default payment details' => '您将在默认付款详细信息上收到付款'
];
