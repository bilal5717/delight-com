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
    'company_details' => 'Данные компании',
    'company_logo' => 'Логотип компании',
    'company_name' => 'Название компании',
    'about_business' => 'О бизнесе',
    'Describe what makes your ad unique...' => 'Опишите, что делает вашу рекламу уникальной...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'Wechat',
    'phone' => 'Телефон',
    'website' => 'Веб-сайт',
    'business_category' => 'Категория бизнеса',
    'company_size' => 'Размер компании (доход)',
    'registration_number' => 'Регистрационный номер компании',
    'submit' => 'Отправить',

    'company_payment_details' => 'Данные платежей компании',
    // 'bank_account_number' => 'Номер банковского счета',
    // 'confirm_bank_account_number' => 'Подтвердите номер банковского счета',
    // 'bank_account_holder_name' => 'Имя владельца банковского счета',
    'payment_details' => 'Данные платежей',

    'add_new_address' => 'Добавить новый адрес',
    'company_address' => 'Адрес компании',
    'Set as default address' => 'Установить как основной адрес',
    'address' => 'Адрес',
    'company_city' => 'Город',
    'company_state' => 'Штат',
    'company_pincode' => 'Индекс',
    'company_country' => 'Страна',
    'default_address' => 'Основной адрес',
    'action' => 'Действие',
    'Oops...' => 'Упс...',
    'Are you sure you want to delete this address?' => 'Вы уверены, что хотите удалить этот адрес?',
    'You wont be able to revert this !' => 'Вы не сможете вернуть это обратно!',
    'You can not delete default selected address!' => 'Вы не можете удалить выбранный основной адрес!',
    'Yes, delete it!' => 'Да, удалить!',

    'edit_company_details' => 'Изменить данные о компании',
    'view_company_details' => 'Просмотреть данные о компании',
    'company_information' => 'Информация о компании',
    'about' => 'О компании',
    'email' => 'Электронная почта',
    'company_email' => 'Электронная почта компании',
    'user_information' => 'Информация о пользователе',

    //company payment
    'select_currency' => 'Выберите валюту',
    'add_payment_detail' => 'Добавить данные платежа',
    'currently_not_available' => 'В настоящее время не доступно для этой валюты',
    'company_payment_list' => 'Список платежей компании',
    'account_holder_name' => 'Имя владельца счета',
    'account_number' => 'Номер счета',
    'country' => 'Страна',
    'default_payment' => 'Платеж по умолчанию',
    'Are you sure you want to delete this Payment?' => 'Вы уверены, что хотите удалить этот платеж?',
    'You can not delete default selected Payment!' => 'Вы не можете удалить выбранный платеж по умолчанию!',
    'Set as default Payment' => 'Установить как платеж по умолчанию',
    'invalid_price' => 'Цена, которую вы ввели, должна быть действительной или не менее :price_currency',
    'can_not_use_bad_word_in_description' => 'Не можете использовать слова <b>:badword</b> в описании!',
    'description_length_not_greater_then' => 'Обратите внимание, что мы считаем оптимальным максимальную длину описания в ' . config('settings.single.max_word_description') . ' слов для обеспечения положительного пользовательского опыта и оптимизации для SEO.',
    'description_length_not_less_then' => 'Обратите внимание, что мы считаем оптимальным минимальную длину описания в ' . config('settings.single.min_word_description') . ' слов для обеспечения положительного пользовательского опыта и оптимизации для SEO.',
    'address_stored_successfully' => 'Адрес компании успешно добавлен...',
    'address_updated_successfully' => 'Адрес компании успешно обновлен...',
    'company_profile_updated' => 'Профиль компании успешно обновлен...',
    'payment_details_submitted_successfully' => 'Детали оплаты компании успешно отправлены',
    'payment_details_updated_successfully' => 'Детали оплаты компании успешно созданы или обновлены',
    'min_max_word_description' => 'Минимальное количество слов ' . config('settings.single.min_word_description') . ', а максимальное - ' . config('settings.single.max_word_description') . ' в описании',
    'title_character_limit' => 'Лимит символов в заголовке',
    'title_length_not_greater_then' => 'Чтобы обеспечить ясность, информативность и легкость чтения заголовков рекламных объявлений и оптимизации для SEO, мы установили максимальный лимит длины заголовка. В настоящее время максимальная длина заголовка не должна превышать ' . config('settings.single.title_character_limit') . ' символов',
    'title_character_minimum_limit' => 'Минимальное количество символов в заголовке',
    'title_length_not_less_then' => 'Чтобы обеспечить ясность, информативность и легкость чтения заголовков рекламных объявлений и оптимизации для SEO, мы установили минимальный лимит длины заголовка. В настоящее время минимальная длина заголовка должна быть не менее ' . config('settings.single.title_character_minimum_limit') . ' символов',
    'max_word_description' => 'Максимально допустимое количество слов в описании - 500',
    'about_company' => 'О компании',
    'company_description_length_not_greater_then' => 'Длина описания не должна превышать 500 слов!',

    'primary_email' => 'Основной адрес электронной почты',
    'upgrade_post_package' => 'Обновить пакет постов',
    'default_business_type' => 'Тип бизнеса по умолчанию',
    'information' => 'Информация',
    'enter_information' => 'Введите информацию',

    'business_social_media' => 'Ссылки на социальные сети этого бизнеса:',
    'You will receive your payments on your default payment details' => 'Вы будете получать платежи на свои данные по умолчанию'
];
