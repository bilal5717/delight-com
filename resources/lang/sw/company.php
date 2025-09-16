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
    'company_details' => 'Maelezo ya Kampuni',
    'company_logo' => 'Logo la Kampuni',
    'company_name' => 'Jina la Kampuni',
    'about_business' => 'Kuhusu Biashara',
    'Describe what makes your ad unique...' => 'Eleza kinachofanya tangazo lako kuwa tofauti...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'Wechat',
    'phone' => 'Simu',
    'website' => 'Tovuti',
    'business_category' => 'Jamii ya Biashara',
    'company_size' => 'Ukubwa wa Kampuni(Mapato)',
    'registration_number' => 'Namba ya Usajili wa Kampuni',
    'submit' => 'Tuma',

    'company_payment_details' => 'Maelezo ya Malipo ya Kampuni',
    // 'bank_account_number' => 'Nambari ya Akaunti ya Benki',
    // 'confirm_bank_account_number' => 'Thibitisha nambari ya akaunti ya benki',
    // 'bank_account_holder_name' => 'Jina la mmiliki wa akaunti ya benki',
    'payment_details' => 'Maelezo ya Malipo',

    'add_new_address' => 'Ongeza anuani mpya',
    'company_address' => 'Anuani ya Kampuni',
    'Set as default address' => 'Weka kama anuani ya chaguo-msingi',
    'address' => 'Anwani',
    'company_city' => 'Mji wa Kampuni',
    'company_state' => 'Jimbo la Kampuni',
    'company_pincode' => 'Nambari ya Posta ya Kampuni',
    'company_country' => 'Nchi ya Kampuni',
    'default_address' => 'Anwani ya chaguo-msingi',
    'action' => 'Hatua',
    'Oops...' => 'Adui...',
    'Are you sure you want to delete this address?' => 'Una uhakika unataka kufuta anuani hii?',
    'You wont be able to revert this !' => 'Hutaweza kurejesha hii!',
    'You can not delete default selected address!' => 'Huwezi kufuta anuani iliyochaguliwa kama chaguo-msingi!',
    'Yes, delete it!' => 'Ndio, ifute!',

    'edit_company_details' => 'Hariri Maelezo ya Kampuni',
    'view_company_details' => 'Angalia Maelezo ya Kampuni',
    'company_information' => 'Maelezo ya Kampuni',
    'about' => 'Kuhusu',
    'email' => 'Barua pepe',
    'company_email' => 'Barua pepe ya Kampuni',
    'user_information' => 'Maelezo ya Mtumiaji',
    
    //company payment
    'select_currency' => 'Chagua Sarafu',
    'add_payment_detail' => 'Ongeza Maelezo ya Malipo',
    'currently_not_available' => 'Kwa sasa haipatikani kwa sarafu hii',
    'company_payment_list' => 'Orodha ya Malipo ya Kampuni',
    'account_holder_name' => 'Jina la Mmiliki wa Akaunti',
    'account_number' => 'Nambari ya Akaunti',
    'country' => 'Nchi',
    'default_payment' => 'Malipo ya Msingi',
    'Are you sure you want to delete this Payment?' => 'Je, una uhakika unataka kufuta Malipo haya?',
    'You can not delete default selected Payment!' => 'Hauwezi kufuta Malipo ya kuchaguliwa kama msingi!',
    'Set as default Payment' => 'Weka kama Malipo ya Msingi',
    'invalid_price' => 'Bei uliyoiingiza lazima iwe bei halali au isiwe chini ya :price_currency',
    'can_not_use_bad_word_in_description' => 'Hauwezi kutumia maneno mabaya kama vile <b>:badword</b> kwenye Maelezo!',
    'description_length_not_greater_then' => 'Tafadhali kumbuka kuwa tunadhani urefu wa maelezo wa juu ya ' . config('settings.single.max_word_description') . ' maneno ni bora kuhakikisha uzoefu mzuri wa mtumiaji na kuboresha SEO.',
    'description_length_not_less_then' => 'Tafadhali kumbuka kuwa tunadhani urefu wa maelezo wa chini ya ' . config('settings.single.min_word_description') . ' maneno ni bora kuhakikisha uzoefu mzuri wa mtumiaji na kuboresha SEO.',
    'address_stored_successfully' => 'Anuani ya Kampuni Imeongezwa kwa Mafanikio...',
    'address_updated_successfully' => 'Anuani ya Kampuni Imesasishwa kwa Mafanikio...',
    'company_profile_updated' => 'Profaili ya Kampuni Imesasishwa kwa Mafanikio...',
    'payment_details_submitted_successfully' => 'Maelezo ya malipo ya kampuni yameshawasilishwa kwa mafanikio',
    'payment_details_updated_successfully' => 'Maelezo ya malipo ya kampuni yameshawasilishwa au kusasishwa kwa mafanikio',
    'min_max_word_description' => 'Maneno ya chini ya ' . config('settings.single.min_word_description') . ' na maneno ya juu ya ' . config('settings.single.max_word_description') . ' yanaruhusiwa katika maelezo',
    'title_character_limit' => 'Kikomo cha Tabia ya Kichwa',
    'title_length_not_greater_then' => 'Ili kuhakikisha kuwa vichwa vya matangazo yako ni wazi, yenye habari, rahisi kusoma, na kuongeza SEO, tumetekeleza mahitaji ya urefu wa kichwa. Kwa sasa, urefu wa kichwa wa juu hauwezi kuwa zaidi ya wahusika ' . config('settings.single.title_character_limit') . ' (maandishi)',
    'title_character_minimum_limit' => 'Kikomo cha Chini cha Tabia ya Kichwa',
    'title_length_not_less_then' => 'Ili kuhakikisha kuwa vichwa vya matangazo yako ni wazi, yenye habari, rahisi kusoma, na kuongeza SEO, tumetekeleza mahitaji ya urefu wa chini wa kichwa. Kwa sasa, urefu wa kichwa wa chini hauwezi kuwa chini ya wahusika ' . config('settings.single.title_character_minimum_limit') . ' (maandishi)',
    'max_word_description' => 'Maneno ya juu ya 500 yanaruhusiwa katika maelezo',
    'about_company' => 'Kuhusu Kampuni',
    'company_description_length_not_greater_then' => 'Urefu wa maelezo usizidi maneno 500!',
    'primary_email' => 'Barua Pepe Kuu',
    'upgrade_post_package' => 'Boresha Pakiti ya Machapisho',
    'default_business_type' => 'Aina ya biashara chaguo-msingi',
    'information' => 'Taarifa',
    'enter_information' => 'Ingiza Taarifa',

    'business_social_media' => 'Viungo vya wavuti wa media ya kijamii ya biashara hii:',
    'You will receive your payments on your default payment details' => 'Utapokea malipo yako kwenye maelezo yako ya malipo ya chaguo-msingi'
];
