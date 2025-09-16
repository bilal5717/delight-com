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
    'company_details' => '企業詳細',
    'company_logo' => '企業ロゴ',
    'company_name' => '企業名',
    'about_business' => 'ビジネスについての説明...',
    'Describe what makes your ad unique...' => '広告を他と差別化する特長を説明してください...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'Wechat',
    'phone' => '電話番号',
    'website' => 'ウェブサイト',
    'business_category' => 'ビジネスカテゴリ',
    'company_size' => '企業規模（収益）',
    'registration_number' => '会社登記番号',
    'submit' => '提出',
    
    'company_payment_details' => '企業の支払い詳細',
    // 'bank_account_number' => '銀行口座番号',
    // 'confirm_bank_account_number' => '銀行口座番号の確認',
    // 'bank_account_holder_name' => '銀行口座名義人の名前',
    'payment_details' => '支払い詳細',

    'add_new_address' => '新しい住所を追加',
    'company_address' => '会社の住所',
    'Set as default address' => 'デフォルト住所に設定する',
    'address' => '住所',
    'company_city' => '市区町村',
    'company_state' => '都道府県',
    'company_pincode' => '郵便番号',
    'company_country' => '国',
    'default_address' => 'デフォルト住所',
    'action' => 'アクション',
    'Oops...' => 'おっと...',
    'Are you sure you want to delete this address?' => 'この住所を削除してもよろしいですか？',
    'You wont be able to revert this !' => 'この操作は取り消せません！',
    'You can not delete default selected address!' => 'デフォルトに設定されている住所は削除できません！',
    'Yes, delete it!' => 'はい、削除します！',

    'edit_company_details' => '会社情報を編集',
    'view_company_details' => '会社情報を表示',
    'company_information' => '会社情報',
    'about' => '概要',
    'email' => 'メール',
    'company_email' => '会社のメール',
    'user_information' => 'ユーザー情報',

    //company payment
    'select_currency' => '通貨を選択',
    'add_payment_detail' => '支払い詳細を追加',
    'currently_not_available' => '現在、この通貨では利用できません',
    'company_payment_list' => '企業支払いリスト',
    'account_holder_name' => '口座名義人',
    'account_number' => '口座番号',
    'country' => '国',
    'default_payment' => 'デフォルトの支払い',
    'Are you sure you want to delete this Payment?' => 'この支払いを削除してもよろしいですか？',
    'You can not delete default selected Payment!' => 'デフォルトで選択された支払いは削除できません！',
    'Set as default Payment' => 'デフォルト支払いに設定',
    'invalid_price' => '入力した価格は有効な価格であるか、または以下であってはなりません。:price_currency',
    'can_not_use_bad_word_in_description' => '説明文に<b>:badword</b>ワードを使用することはできません！',
    'description_length_not_greater_then' => '良いユーザーエクスペリエンスを確保し、SEOを最適化するために、最大説明文の長さは ' . config('settings.single.max_word_description') . '語以内に収めることをお勧めします。',
    'description_length_not_less_then' => '良いユーザーエクスペリエンスを確保し、SEOを最適化するために、最小説明文の長さは ' . config('settings.single.min_word_description') . '語以上にすることをお勧めします。',
    'address_stored_successfully' => '住所が正常に追加されました...',
    'address_updated_successfully' => '住所が正常に更新されました...',
    'company_profile_updated' => '会社プロフィールが正常に更新されました...',
    'payment_details_submitted_successfully' => '支払い詳細が正常に送信されました',
    'payment_details_updated_successfully' => '支払い詳細が正常に作成または更新されました',
    'min_max_word_description' => '説明文には最低限' . config('settings.single.min_word_description') . 'から' . config('settings.single.max_word_description') . 'の単語が必要です',
    'title_character_limit' => 'タイトルの文字数制限',
    'title_length_not_greater_then' => '広告タイトルが明確で、情報量があり、読みやすく、SEOに最適化されるようにするために、最大タイトル文字数の要件を実装しています。現在、最大タイトル長は、' . config('settings.single.title_character_limit') . '文字以下に設定されています',
    'title_character_minimum_limit' => 'タイトルの最小文字数制限',
    'title_length_not_less_then' => '広告タイトルが明確で、情報量があり、読みやすく、SEOに最適化されるようにするために、最小タイトル文字数の要件を実装しています。現在、最小タイトル長は、' . config('settings.single.title_character_minimum_limit') . '文字以上に設定されています',
    'max_word_description' => '説明文には最大500単語までしか使用できません',
    'about_company' => '会社について',
    'company_description_length_not_greater_then' => '説明文は500単語以下にしてください！',

    'primary_email' => 'プライマリメール',
    'upgrade_post_package' => '投稿パッケージのアップグレード',
    'default_business_type' => 'デフォルトのビジネスタイプ',
    'information' => '情報',
    'enter_information' => '情報を入力してください',
    'business_social_media' => 'このビジネスのソーシャルメディアウェブサイトリンク：',
    'You will receive your payments on your default payment details' => 'デフォルトの支払い詳細に支払われます。',
];
