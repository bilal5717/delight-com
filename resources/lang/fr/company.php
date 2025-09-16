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
    'company_details' => 'Détails de l\'entreprise',
    'company_logo' => 'Logo de l\'entreprise',
    'company_name' => 'Nom de l\'entreprise',
    'about_business' => 'À propos de l\'entreprise',
    'Describe what makes your ad unique...' => 'Décrivez ce qui rend votre annonce unique...',
    'facebook' => 'Facebook',
    'twitter' => 'Twitter',
    'instagram' => 'Instagram',
    'linkedIn' => 'LinkedIn',
    'kvk' => 'KVK',
    'wechat' => 'WeChat',
    'phone' => 'Téléphone',
    'website' => 'Site web',
    'business_category' => 'Catégorie d\'entreprise',
    'company_size' => 'Taille de l\'entreprise (revenu)',
    'registration_number' => 'Numéro d\'enregistrement de l\'entreprise',
    'submit' => 'Soumettre',
    
    'company_payment_details' => 'Détails de paiement de l\'entreprise',
    // 'bank_account_number' => 'Numéro de compte bancaire',
    // 'confirm_bank_account_number' => 'Confirmer le numéro de compte bancaire',
    // 'bank_account_holder_name' => 'Nom du titulaire du compte bancaire',
    'payment_details' => 'Détails de paiement',

    'add_new_address' => 'Ajouter une nouvelle adresse',
    'company_address' => 'Adresse de la société',
    'Set as default address' => 'Définir comme adresse par défaut',
    'address' => 'Adresse',
    'company_city' => 'Ville',
    'company_state' => 'État',
    'company_pincode' => 'Code postal',
    'company_country' => 'Pays',
    'default_address' => 'Adresse par défaut',
    'action' => 'Action',
    'Oops...' => 'Oups...',
    'Are you sure you want to delete this address?' => 'Êtes-vous sûr de vouloir supprimer cette adresse ?',
    'You wont be able to revert this !' => 'Vous ne pourrez pas revenir en arrière !',
    'You can not delete default selected address!' => 'Vous ne pouvez pas supprimer l`adresse par défaut sélectionnée !',
    'Yes, delete it!' => 'Oui, supprimez-le !',

    'edit_company_details' => 'Modifier les détails de la société',
    'view_company_details' => 'Voir les détails de la société',
    'company_information' => 'Informations sur la société',
    'about' => 'À propos',
    'email' => 'E-mail',
    'company_email' => 'E-mail de la société',
    'user_information' => 'Informations sur l`utilisateur',

    //company payment
    'select_currency' => 'Sélectionnez la devise',
    'add_payment_detail' => 'Ajouter des détails de paiement',
    'currently_not_available' => 'Actuellement indisponible pour cette devise',
    'company_payment_list' => 'Liste de paiement de l`entreprise',
    'account_holder_name' => 'Nom du titulaire du compte',
    'account_number' => 'Numéro de compte',
    'country' => 'Pays',
    'default_payment' => 'Paiement par défaut',
    'Are you sure you want to delete this Payment?' => 'Êtes-vous sûr de vouloir supprimer ce paiement ?',
    'You can not delete default selected Payment!' => 'Vous ne pouvez pas supprimer le paiement sélectionné par défaut !',
    'Set as default Payment' => 'Définir comme paiement par défaut',
    'invalid_price' => 'Le prix que vous avez saisi doit être un prix valide ou pas inférieur à :price_currency',
    'can_not_use_bad_word_in_description' => 'Impossible d utiliser les mots <b>:badword</b> dans la description !',
    'description_length_not_greater_then' => 'Veuillez noter que nous pensons qu une longueur maximale de description de '.config('settings.single.max_word_description').' mots est optimale pour assurer une expérience utilisateur positive et pour l optimisation du référencement.',
    'description_length_not_less_then' => 'Veuillez noter que nous pensons qu une longueur minimale de description de '.config('settings.single.min_word_description').' mots est optimale pour assurer une expérience utilisateur positive et pour l optimisation du référencement.',
    'address_stored_successfully' => 'Adresse de l\'entreprise ajoutée avec succès...',
    'address_updated_successfully' => 'Adresse de l\'entreprise mise à jour avec succès...',
    'company_profile_updated' => 'Profil de l\'entreprise mis à jour avec succès...',
    'payment_details_submitted_successfully' => 'Les détails de paiement de l\'entreprise ont été soumis avec succès',
    'payment_details_updated_successfully' => 'Les détails de paiement de l\'entreprise ont été créés ou mis à jour avec succès',
    'min_max_word_description' => 'Minimum ' . config('settings.single.min_word_description') . ' et Maximum ' . config('settings.single.max_word_description') . ' mots autorisés dans la description',
    'title_character_limit' => 'Limite de caractères du titre',
    'title_length_not_greater_then' => 'Pour vous assurer que les titres de vos annonces sont clairs, informatifs, faciles à lire et optimisés pour le référencement, nous avons mis en place une exigence de longueur de titre maximale. Actuellement, la longueur maximale du titre est définie sur ne pas dépasser ' . config('settings.single.title_character_limit') . ' caractères',
    'title_character_minimum_limit' => 'Limite minimale de caractères du titre',
    'title_length_not_less_then' => 'Pour vous assurer que les titres de vos annonces sont clairs, informatifs, faciles à lire et optimisés pour le référencement, nous avons mis en place une exigence de longueur de titre minimale. Actuellement, la longueur minimale du titre est définie sur ne pas être inférieure à ' . config('settings.single.title_character_minimum_limit') . ' caractères',
    'max_word_description' => 'Maximum 500 mots autorisés dans la description',
    'about_company' => 'À propos de l\'entreprise',
    'company_description_length_not_greater_then' => 'La longueur de la description ne doit pas dépasser 500 mots!',

    'primary_email' => 'Email principal',
    'upgrade_post_package' => 'Mettre à niveau le forfait de publication',
    'default_business_type' => 'Type d\'entreprise par défaut',
    'information' => 'Information',
    'enter_information' => 'Entrer l\'information',

    'business_social_media' => 'Liens vers les sites de médias sociaux de cette entreprise:',
    'You will receive your payments on your default payment details' => 'Vous recevrez vos paiements sur vos détails de paiement par défaut',
];
