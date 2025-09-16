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
    'company_details' => 'Detalji tvrtke',
    'company_logo' => 'Logo tvrtke',
    'company_name' => 'Naziv tvrtke',
    'about_business' => 'O poslovanju',
    'Describe what makes your ad unique...' => 'Objasnite što vaš oglas čini jedinstvenim...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'Wechat',
    'phone' => 'Telefon',
    'website' => 'Web stranica',
    'business_category' => 'Kategorija poslovanja',
    'company_size' => 'Veličina tvrtke (prihod)',
    'registration_number' => 'Broj registracije tvrtke',
    'submit' => 'Potvrdi',

    'company_payment_details' => 'Detalji plaćanja tvrtke',
    // 'bank_account_number' => 'Broj bankovnog računa',
    // 'confirm_bank_account_number' => 'Potvrdite broj bankovnog računa',
    // 'bank_account_holder_name' => 'Ime vlasnika bankovnog računa',
    'payment_details' => 'Detalji plaćanja',

    'add_new_address' => 'Dodaj novu adresu',
    'company_address' => 'Adresa tvrtke',
    'Set as default address' => 'Postavi kao zadana adresa',
    'address' => 'Adresa',
    'company_city' => 'Grad',
    'company_state' => 'Županija',
    'company_pincode' => 'Poštanski broj',
    'company_country' => 'Zemlja',
    'default_address' => 'Zadana adresa',
    'action' => 'Radnja',
    'Oops...' => 'Ups...',
    'Are you sure you want to delete this address?' => 'Jeste li sigurni da želite izbrisati ovu adresu?',
    'You wont be able to revert this !' => 'Ovo nećete moći vratiti!',
    'You can not delete default selected address!' => 'Ne možete izbrisati odabranu zadanu adresu!',
    'Yes, delete it!' => 'Da, izbriši!',

    'edit_company_details' => 'Uredi detalje tvrtke',
    'view_company_details' => 'Pregledaj detalje tvrtke',
    'company_information' => 'Informacije o tvrtki',
    'about' => 'O tvrtki',
    'email' => 'E-mail',
    'company_email' => 'E-mail tvrtke',
    'user_information' => 'Informacije o korisniku',

    //company payment
    'select_currency' => 'Odaberite valutu',
    'add_payment_detail' => 'Dodajte detalje plaćanja',
    'currently_not_available' => 'Trenutno nije dostupno za ovu valutu',
    'company_payment_list' => 'Popis plaćanja tvrtke',
    'account_holder_name' => 'Ime vlasnika računa',
    'account_number' => 'Broj računa',
    'country' => 'Zemlja',
    'default_payment' => 'Zadano plaćanje',
    'Are you sure you want to delete this Payment?' => 'Jeste li sigurni da želite izbrisati ovaj način plaćanja?',
    'You can not delete default selected Payment!' => 'Ne možete izbrisati zadano odabrani način plaćanja!',
    'Set as default Payment' => 'Postavite kao zadano plaćanje',
    'invalid_price' => 'Cijena koju ste unijeli mora biti valjana cijena ili ne smije biti niža od :price_currency',
    'can_not_use_bad_word_in_description' => 'Ne možete koristiti riječi <b>:badword</b> u opisu!',
    'description_length_not_greater_then' => 'Imajte na umu da vjerujemo da je maksimalna duljina opisa od ' . config('settings.single.max_word_description') . ' riječi optimalna za osiguravanje pozitivnog korisničkog iskustva i optimizaciju za SEO.',
    'description_length_not_less_then' => 'Imajte na umu da vjerujemo da je minimalna duljina opisa od ' . config('settings.single.min_word_description') . ' riječi optimalna za osiguravanje pozitivnog korisničkog iskustva i optimizaciju za SEO.',
    'address_stored_successfully' => 'Adresa tvrtke uspješno dodana...',
    'address_updated_successfully' => 'Adresa tvrtke uspješno ažurirana...',
    'company_profile_updated' => 'Profil tvrtke uspješno ažuriran...',
    'payment_details_submitted_successfully' => 'Podaci o plaćanju tvrtke uspješno poslani',
    'payment_details_updated_successfully' => 'Podaci o plaćanju tvrtke su uspješno stvoreni ili ažurirani',
    'min_max_word_description' => 'Minimalno ' . config('settings.single.min_word_description') . ' i maksimalno ' . config('settings.single.max_word_description') . ' riječi dopušteno u opisu',
    'title_character_limit' => 'Ograničenje znakova naslova',
    'title_length_not_greater_then' => 'Da bi vaši oglasi bili jasni, informativni, lako čitljivi i optimizirani za SEO, implementirali smo zahtjev za maksimalnom duljinom naslova. Trenutno je maksimalna duljina naslova postavljena na ne više od ' . config('settings.single.title_character_limit') . ' znakova',
    'title_character_minimum_limit' => 'Minimalno ograničenje znakova naslova',
    'title_length_not_less_then' => 'Da bi vaši oglasi bili jasni, informativni, lako čitljivi i optimizirani za SEO, implementirali smo zahtjev za minimalnom duljinom naslova. Trenutno je minimalna duljina naslova postavljena na ne manje od ' . config('settings.single.title_character_minimum_limit') . ' znakova',
    'max_word_description' => 'U opisu je dopušteno najviše 500 riječi',
    'about_company' => 'O tvrtki',
    'company_description_length_not_greater_then' => 'Duljina opisa ne smije biti veća od 500 riječi!',

    'primary_email' => 'Primarna e-pošta',
    'upgrade_post_package' => 'Ažuriraj paket postova',
    'default_business_type' => 'Zadani tip poslovanja',
    'information' => 'Informacije',
    'enter_information' => 'Unesite informacije',

    'business_social_media' => 'Poveznice društvenih medija ovog poslovanja:',
    'You will receive your payments on your default payment details' => 'Primat ćete svoje uplate na zadanim detaljima plaćanja'
];
