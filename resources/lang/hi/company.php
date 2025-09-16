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
    'company_details' => 'कंपनी के विवरण',
    'company_logo' => 'कंपनी लोगो',
    'company_name' => 'कंपनी का नाम',
    'about_business' => 'व्यवसाय के बारे में बताएं',
    'Describe what makes your ad unique...' => 'विज्ञापन को अनूठा बनाने वाली विशेषताओं का वर्णन करें...',
    'facebook' => 'फेसबुक',
    'twitter' => 'ट्विटर',
    'instagram' => 'इंस्टाग्राम',
    'linkedIn' => 'लिंक्डइन',
    'kvk' => 'केवीके',
    'wechat' => 'वीचैट',
    'phone' => 'फोन',
    'website' => 'वेबसाइट',
    'business_category' => 'व्यवसाय श्रेणी',
    'company_size' => 'कंपनी का आकार (राजस्व)',
    'registration_number' => 'कंपनी का पंजीकरण संख्या',
    'submit' => 'प्रस्तुत',

    'company_payment_details' => 'कंपनी भुगतान विवरण',
    // 'bank_account_number' => 'बैंक खाता संख्या',
    // 'confirm_bank_account_number' => 'बैंक खाता संख्या की पुष्टि करें',
    // 'bank_account_holder_name' => 'बैंक खाता धारक का नाम',
    'payment_details' => 'भुगतान विवरण',

    'add_new_address' => 'नया पता जोड़ें',
    'company_address' => 'कंपनी का पता',
    'Set as default address' => 'मूल ठहराव रूप में सेट करें',
    'address' => 'पता',
    'company_city' => 'शहर',
    'company_state' => 'राज्य',
    'company_pincode' => 'पिनकोड',
    'company_country' => 'देश',
    'default_address' => 'मूल ठहराव',
    'action' => 'कार्रवाई',
    'Oops...' => 'अरे...',
    'Are you sure you want to delete this address?' => 'क्या आप वाकई इस पते को हटाना चाहते हैं?',
    'You wont be able to revert this !' => 'आप इसे वापस नहीं कर पाएंगे!',
    'You can not delete default selected address!' => 'आप मूल ठहराव का चयन किए गए पते को हटा नहीं सकते!',
    'Yes, delete it!' => 'हां, हटाएँ!',

    'edit_company_details' => 'कंपनी विवरण संपादित करें',
    'view_company_details' => 'कंपनी विवरण देखें',
    'company_information' => 'कंपनी जानकारी',
    'about' => 'विवरण',
    'email' => 'ईमेल',
    'company_email' => 'कंपनी ईमेल',
    'user_information' => 'उपयोगकर्ता जानकारी',

    //company payment
    'select_currency' => 'मुद्रा का चयन करें',
    'add_payment_detail' => 'भुगतान विवरण जोड़ें',
    'currently_not_available' => 'वर्तमान में इस मुद्रा के लिए उपलब्ध नहीं है',
    'company_payment_list' => 'कंपनी भुगतान सूची',
    'account_holder_name' => 'खाताधारक का नाम',
    'account_number' => 'खाता संख्या',
    'country' => 'देश',
    'default_payment' => 'डिफ़ॉल्ट भुगतान',
    'Are you sure you want to delete this Payment?' => 'क्या आप वाकई इस भुगतान को हटाना चाहते हैं?',
    'You can not delete default selected Payment!' => 'आप डिफ़ॉल्ट चयनित भुगतान को हटा नहीं सकते!',
    'Set as default Payment' => 'डिफ़ॉल्ट भुगतान के रूप में सेट करें',
    'invalid_price' => 'आपके द्वारा दर्ज की गई कीमत मान्य कीमत होनी चाहिए या निम्नलिखित से कम नहीं होनी चाहिए. :price_currency',
    'can_not_use_bad_word_in_description' => 'विवरण में <b>:badword</b> शब्द उपयोग नहीं कर सकते!',
    'description_length_not_greater_then' => 'कृपया ध्यान दें कि हम सकारात्मक उपभोग अनुभव सुनिश्चित करने और एसईओ के लिए अनुकूलित करने के लिए अधिकतम विवरण लंबाई ' . config('settings.single.max_word_description') . ' शब्द होना उपयुक्त मानते हैं।',
    'description_length_not_less_then' => 'कृपया ध्यान दें कि हम सकारात्मक उपयोगकर्ता अनुभव और एसईओ के लिए अधिकतम  ' . config('settings.single.min_word_description') . ' शब्दों की अवधि आदर्श मानते हैं।',
    'address_stored_successfully' => 'कंपनी का पता सफलतापूर्वक जोड़ा गया है ...',
    'address_updated_successfully' => 'कंपनी का पता सफलतापूर्वक अपडेट किया गया है ...',
    'company_profile_updated' => 'कंपनी प्रोफ़ाइल सफलतापूर्वक अपडेट की गई ...',
    'payment_details_submitted_successfully' => 'कंपनी भुगतान विवरण सफलतापूर्वक जमा किए गए हैं',
    'payment_details_updated_successfully' => 'कंपनी भुगतान विवरण सफलतापूर्वक बनाए गए या अपडेट किए गए हैं',
    'min_max_word_description' => 'विवरण में कम से कम ' . config('settings.single.min_word_description') . ' और अधिकतम ' . config('settings.single.max_word_description') . ' शब्द अनुमत हैं',
    'title_character_limit' => 'शीर्षक वर्ण सीमा',
    'title_length_not_greater_then' => 'विज्ञापन शीर्षक स्पष्ट, सूचनात्मक और पठनीय बनाने के लिए, और SEO के लिए अनुकूलित करने के लिए, हमने एक अधिकतम शीर्षक लंबाई आवश्यकता लागू की है। वर्तमान में, अधिकतम शीर्षक लंबाई ' . config('settings.single.title_character_limit') . ' अक्षरों से अधिक नहीं होना चाहिए',
    'title_character_minimum_limit' => 'शीर्षक अक्षर न्यूनतम सीमा',
    'title_length_not_less_then' => 'विज्ञापन शीर्षक स्पष्ट, सूचनात्मक और पठनीय बनाने के लिए, और SEO के लिए अनुकूलित करने के लिए, हमने एक न्यूनतम शीर्षक लंबाई आवश्यकता लागू की है। वर्तमान में, न्यूनतम शीर्षक लंबाई ' . config('settings.single.title_character_minimum_limit') . ' अक्षरों से कम नहीं होना चाहिए',
    'max_word_description' => 'विवरण में अधिकतम 500 शब्द अनुमत हैं',
    'about_company' => 'कंपनी के बारे में',
    'company_description_length_not_greater_then' => 'विवरण लंबाई 500 शब्दों से अधिक नहीं होनी चाहिए!',

    'primary_email' => 'प्राथमिक ईमेल',
    'upgrade_post_package' => 'पोस्ट पैकेज का अपग्रेड करें',
    'default_business_type' => 'डिफ़ॉल्ट व्यवसाय प्रकार',
    'information' => 'जानकारी',
    'enter_information' => 'जानकारी दर्ज करें',

    'business_social_media' => 'इस व्यवसाय के सोशल मीडिया वेबसाइट लिंक:',
    'You will receive your payments on your default payment details' => 'आप अपनी डिफ़ॉल्ट भुगतान विवरण पर अपने भुगतान प्राप्त करेंगे।'
];
