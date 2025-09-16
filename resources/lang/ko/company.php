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
    'company_details' => '회사 정보',
    'company_logo' => '회사 로고',
    'company_name' => '회사 이름',
    'about_business' => '비즈니스 정보',
    'Describe what makes your ad unique...' => '광고를 유니크하게 만드는 특징을 설명하세요...',
    'facebook' => '페이스북',
    'twitter' => '트위터',
    'instagram' => '인스타그램',
    'linkedIn' => '링크드인',
    'kvk' => 'KVK',
    'wechat' => '위챗',
    'phone' => '전화번호',
    'website' => '웹사이트',
    'business_category' => '비즈니스 카테고리',
    'company_size' => '회사 규모(수익)',
    'registration_number' => '회사 등록번호',
    'submit' => '제출',
    
    'company_payment_details' => '회사 결제 정보',
    // 'bank_account_number' => '은행 계좌번호',
    // 'confirm_bank_account_number' => '은행 계좌번호 확인',
    // 'bank_account_holder_name' => '은행 계좌주 이름',
    'payment_details' => '결제 정보',

    'add_new_address' => '새 주소 추가',
    'company_address' => '회사 주소',
    'Set as default address' => '기본 주소로 설정',
    'address' => '주소',
    'company_city' => '도시',
    'company_state' => '주',
    'company_pincode' => '우편번호',
    'company_country' => '국가',
    'default_address' => '기본 주소',
    'action' => '작업',
    'Oops...' => '이런...',
    'Are you sure you want to delete this address?' => '이 주소를 삭제하시겠습니까?',
    'You wont be able to revert this !' => '삭제하면 되돌릴 수 없습니다!',
    'You can not delete default selected address!' => '기본 선택된 주소는 삭제할 수 없습니다!',
    'Yes, delete it!' => '네, 삭제하겠습니다!',

    'edit_company_details' => '회사 정보 수정',
    'view_company_details' => '회사 정보 보기',
    'company_information' => '회사 정보',
    'about' => '소개',
    'email' => '이메일',
    'company_email' => '회사 이메일',
    'user_information' => '사용자 정보',

    //company payment
    'select_currency' => '통화 선택',
    'add_payment_detail' => '결제 세부 정보 추가',
    'currently_not_available' => '현재 이 통화는 사용할 수 없습니다',
    'company_payment_list' => '회사 결제 목록',
    'account_holder_name' => '예금주 이름',
    'account_number' => '계좌 번호',
    'country' => '국가',
    'default_payment' => '기본 결제',
    'Are you sure you want to delete this Payment?' => '이 결제를 삭제하시겠습니까?',
    'You can not delete default selected Payment!' => '기본 선택된 결제는 삭제할 수 없습니다!',
    'Set as default Payment' => '기본 결제로 설정',
    'invalid_price' => '입력한 가격은 유효한 가격이거나 최소한 :price_currency 이상이어야합니다',
    'can_not_use_bad_word_in_description' => '설명에 <b>:badword</b> 단어를 사용할 수 없습니다!',
    'description_length_not_greater_then' => '최대 설명 길이는 ' . config('settings.single.max_word_description') . ' 단어로, 긍정적인 사용자 경험과 SEO 최적화를 위해 권장됩니다.',
    'description_length_not_less_then' => '최소 설명 길이는 ' . config('settings.single.min_word_description') . ' 단어로, 긍정적인 사용자 경험과 SEO 최적화를 위해 권장됩니다.',
    'address_stored_successfully' => '회사 주소가 성공적으로 추가되었습니다...',
    'address_updated_successfully' => '회사 주소가 성공적으로 업데이트되었습니다...',
    'company_profile_updated' => '회사 프로필이 성공적으로 업데이트되었습니다...',
    'payment_details_submitted_successfully' => '회사 결제 세부 정보가 성공적으로 제출되었습니다',
    'payment_details_updated_successfully' => '회사 결제 세부 정보가 성공적으로 생성되거나 업데이트되었습니다', 
    'min_max_word_description' => '설명에 최소 ' . config('settings.single.min_word_description') . ' 단어 이상, 최대 ' . config('settings.single.max_word_description') . ' 단어 이하 입력하세요',
    'title_character_limit' => '제목 글자 제한',
    'title_length_not_greater_then' => '광고 제목이 명확하고 정보를 제공하며 읽기 쉽고 SEO에 최적화되도록 하기 위해 최대 글자 수를 설정했습니다. 현재, 최대 글자 수는 ' . config('settings.single.title_character_limit') . '자 이하로 설정되어 있습니다',
    'title_character_minimum_limit' => '제목 최소 글자 수',
    'title_length_not_less_then' => '광고 제목이 명확하고 정보를 제공하며 읽기 쉽고 SEO에 최적화되도록 하기 위해 최소 글자 수를 설정했습니다. 현재, 최소 글자 수는 ' . config('settings.single.title_character_minimum_limit') . '자 이상으로 설정되어 있습니다',
    'max_word_description' => '설명에 최대 500 단어까지 입력할 수 있습니다',
    'about_company' => '회사 소개',
    'company_description_length_not_greater_then' => '설명 길이는 500 단어 이하로 입력하세요!',

    'primary_email' => '주 이메일',
    'upgrade_post_package' => '게시물 패키지 업그레이드',
    'default_business_type' => '기본 비즈니스 유형',
    'information' => '정보',
    'enter_information' => '정보 입력',

    'business_social_media' => '이 비즈니스의 소셜 미디어 웹사이트 링크:',
    'You will receive your payments on your default payment details' => '기본 결제 정보로 지급받게 됩니다'
];

