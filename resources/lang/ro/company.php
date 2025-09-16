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
    'company_details' => 'Detalii companie',
    'company_logo' => 'Logo companie',
    'company_name' => 'Nume companie',
    'about_business' => 'Despre afacerea ta...',
    'Describe what makes your ad unique...' => 'Descrie ce face anunțul tău unic...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'WeChat',
    'phone' => 'Telefon',
    'website' => 'Site web',
    'business_category' => 'Categorie de afaceri',
    'company_size' => 'Mărime companie (Venituri)',
    'registration_number' => 'Număr înregistrare companie',
    'submit' => 'Trimite',

    'company_payment_details' => 'Detalii de plată ale companiei',
    // 'bank_account_number' => 'Număr cont bancar',
    // 'confirm_bank_account_number' => 'Confirmă numărul contului bancar',
    // 'bank_account_holder_name' => 'Numele deținătorului contului bancar',
    'payment_details' => 'Detalii de plată',

    'add_new_address' => 'Adaugă o adresă nouă',
    'company_address' => 'Adresă companie',
    'Set as default address' => 'Setează ca adresă implicită',
    'address' => 'Adresă',
    'company_city' => 'Oraș',
    'company_state' => 'Stat',
    'company_pincode' => 'Cod poștal',
    'company_country' => 'Țară',
    'default_address' => 'Adresă implicită',
    'action' => 'Acțiune',
    'Oops...' => 'Ups...',
    'Are you sure you want to delete this address?' => 'Sigur doriți să ștergeți această adresă?',
    'You wont be able to revert this !' => 'Nu veți putea reveni la această acțiune!',
    'You can not delete default selected address!' => 'Nu puteți șterge adresa selectată implicit!',
    'Yes, delete it!' => 'Da, ștergeți-o!',

    'edit_company_details' => 'Editare Detalii Companie',
    'view_company_details' => 'Vizualizare Detalii Companie',
    'company_information' => 'Informații Companie',
    'about' => 'Despre',
    'email' => 'Email',
    'company_email' => 'Email Companie',
    'user_information' => 'Informații Utilizator',

    //company payment
    'select_currency' => 'Selectare Monedă',
    'add_payment_detail' => 'Adaugare Detalii de Plată',
    'currently_not_available' => 'Momentan nu este disponibil pentru această monedă',
    'company_payment_list' => 'Listă de Plăți Companie',
    'account_holder_name' => 'Nume deținător cont',
    'account_number' => 'Număr de cont',
    'country' => 'Țară',
    'default_payment' => 'Plată implicită',
    'Are you sure you want to delete this Payment?' => 'Sigur doriți să ștergeți această plată?',
    'You can not delete default selected Payment!' => 'Nu puteți șterge plata selectată implicit!',
    'Set as default Payment' => 'Setați ca plată implicită',
    'invalid_price' => 'Prețul pe care îl introduceți trebuie să fie un preț valid sau să nu fie mai mic de :price_currency',
    'can_not_use_bad_word_in_description' => 'Nu puteți utiliza cuvinte <b>:badword</b> în descriere!',
    'description_length_not_greater_then' => 'Vă rugăm să rețineți că credem că o lungime maximă a descrierii de ' . config('settings.single.max_word_description') . ' cuvinte este optimă pentru asigurarea unei experiențe pozitive a utilizatorului și optimizarea pentru SEO.',
    'description_length_not_less_then' => 'Vă rugăm să rețineți că credem că o lungime minimă a descrierii de ' . config('settings.single.min_word_description') . ' cuvinte este optimă pentru asigurarea unei experiențe pozitive a utilizatorului și optimizarea pentru SEO.',
    'address_stored_successfully' => 'Adresa Companiei a fost adăugată cu succes...',
    'address_updated_successfully' => 'Adresa Companiei a fost actualizată cu succes...',
    'company_profile_updated' => 'Profilul Companiei a fost actualizat cu succes...',
    'payment_details_submitted_successfully' => 'Detaliile de plată ale companiei au fost trimise cu succes',
    'payment_details_updated_successfully' => 'Detaliile de plată ale companiei au fost create sau actualizate cu succes',
    'min_max_word_description' => 'Sunt permise minimum ' . config('settings.single.min_word_description') . ' și maximum ' . config('settings.single.max_word_description') . ' cuvinte în descriere',
    'title_character_limit' => 'Limită de caractere pentru titlu',
    'title_length_not_greater_then' => 'Pentru a vă asigura că titlurile dvs. de reclame sunt clare, informative și ușor de citit și optimizează SEO, am implementat o cerință de lungime maximă pentru titlu. În prezent, lungimea maximă a titlului este setată la nu mai mult de ' . config('settings.single.title_character_limit') . ' caractere',
    'title_character_minimum_limit' => 'Limită minimă de caractere pentru titlu',
    'title_length_not_less_then' => 'Pentru a vă asigura că titlurile dvs. de reclame sunt clare, informative și ușor de citit și optimizează SEO, am implementat o cerință de lungime minimă pentru titlu. În prezent, lungimea minimă a titlului este setată la nu mai puțin de ' . config('settings.single.title_character_minimum_limit') . ' caractere',
    'max_word_description' => 'Sunt permise maximum 500 de cuvinte în descriere',
    'about_company' => 'Despre Companie',
    'company_description_length_not_greater_then' => 'Lungimea descrierii nu trebuie să depășească 500 de cuvinte!',

    'primary_email' => 'Email Primar',
    'upgrade_post_package' => 'Upgrade Pachet de Postări',
    'default_business_type' => 'Tip de afacere implicit',
    'information' => 'Informații',
    'enter_information' => 'Introduceți informații',

    'business_social_media' => 'Link-uri către site-uri de socializare ale acestei afaceri:',
    'You will receive your payments on your default payment details' => 'Veți primi plățile pe detaliile de plată implicite ale companiei'
];
