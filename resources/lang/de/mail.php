<?php

return [
	
	/*
	|--------------------------------------------------------------------------
	| Emails Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are used by the Mail notifications.
	|
	*/
	
	// built-in template
	'Whoops!' => 'Whoops!',
	'Hello!' => 'Hallo!',
	'Regards' => 'Grüße',
	"having_trouble_on_link" => "Wenn Sie Probleme haben, klicken Sie auf \":actionText \" Klicken Sie auf die Schaltfläche, kopieren Sie die folgende URL und fügen Sie sie in Ihren Webbrowser ein:",
	'All rights reserved.' => 'Alle Rechte vorbehalten.',
	
	
	// mail salutation
	'footer_salutation' => 'Mit Freundliche Grüßen,<br>:appName',
	
	
	// custom mail_footer (unused)
	'mail_footer_content' => 'Dies ist Ihre neue, kostenlose globale E-Commerce-Plattform, mit der Sie Produkte lokal oder international kaufen und verkaufen können. Mit dieser Plattform können Sie sich mit Käufern und Verkäufern aus der ganzen Welt verbinden',
	
	
	// email_verification
	'email_verification_title'       => 'Bitte bestätige deine Email Adresse.',
	'email_verification_action'      => 'Email Adresse bestätigen',
	'email_verification_content_1'   => 'Hallo :userName !',
	'email_verification_content_2'   => 'Klicken Sie auf die Schaltfläche unten, um Ihre E-Mail-Adresse zu bestätigen.',
	'email_verification_content_3'   => 'Sie erhalten diese E-Mail, weil Sie kürzlich eine neue erstellt haben :appName Konto oder eine neue E-Mail-Adresse hinzugefügt. Wenn Sie es nicht waren, ignorieren Sie bitte diese E-Mail.',
	
	
	// post_activated (new)
	'post_activated_title'             => 'Ihre Anzeige wurde aktiviert',
	'post_activated_content_1'         => 'Hallo,',
	'post_activated_content_2'         => 'Ihre Anzeige <a href=":postUrl">:title</a> wurde aktiviert.',
	'post_activated_content_3'         => 'Es wird in Kürze von einem unserer Administratoren auf seine Online-Veröffentlichung geprüft.',
	'post_activated_content_4' => 'Sie erhalten diese E-Mail, da Sie kürzlich eine neue Anzeige auf :appName erstellt haben. Wenn Sie dies nicht waren, ignorieren Sie bitte diese E-Mail.
    <br>
    Lassen Sie Ihre Anzeige nicht löschen-
    Lesen Sie unsere FAQs, um zu verstehen, wie Sie von NEWTOUSE profitieren können',
	
	
	// post_reviewed (new)
	'post_reviewed_title'              => 'Ihre Anzeige ist jetzt online',
	'post_reviewed_content_1'          => 'Hallo,',
	'post_reviewed_content_2'          => 'Ihre Anzeige <a href=":postUrl">:title</a> ist jetzt online.',
	'post_reviewed_content_3'          => 'Sie erhalten diese E-Mail, weil Sie kürzlich eine neue Anzeige erstellt haben :appName. Wenn Sie es nicht waren, ignorieren Sie bitte diese E-Mail.',
	
	
	// post_republished (new)
	'post_republished_title'              => 'Ihre Anzeige wurde erneut veröffentlicht',
	'post_republished_content_1'          => 'Hallo,',
	'post_republished_content_2' => 'Herzlichen Glückwunsch, Ihre Anzeige mit dem Titel <a href=":postUrl">:title</a> ist jetzt online.
    <br>
    Um sicherzustellen, dass potenzielle Käufer Sie direkt erreichen können, achten Sie bitte darauf, Ihre Telefonnummer im internationalen Format in Ihrer Anzeige zu aktualisieren.
    <br>
    Zum Beispiel sollte Ihre Nummer, wenn sie sich in Großbritannien befindet, wie folgt formatiert sein: +44 21 221552242 ( +Ländercode -Vorwahl-Mobilnummer).
    <br>
    Zusätzlich sollten Sie daran denken, Ihre Chat-App-ID in Ihrer Anzeige zu aktualisieren, indem Sie in Ihr Dashboard gehen, Ihre Anzeige bearbeiten und auf die Option "Chat mit Verkäufer" klicken. Auf diese Weise können Menschen Sie direkt über die von Ihnen ausgewählten Chat-Apps kontaktieren.
    <br>',
    'post_republished_content_3' => 'Sie erhalten diese E-Mail, da Sie kürzlich eine neue Anzeige auf :appName erstellt haben. Wenn Sie dies nicht waren, ignorieren Sie bitte diese E-Mail.
    <br>
    <br>
    Wenn Sie planen, Ihre Produkte und Dienstleistungen zu bewerben, sehen Sie sich bitte unser Leitfaden-Video an.
    <br>
    Listen von Produkten oder Dienstleistungen, die nicht unseren Richtlinien entsprechen, können von unserer Website entfernt werden.
    <br>
    Youtube ; https://youtu.be/loI6hK-cvtk
    <br>',
	
	
	// post_deleted
	'post_deleted_title'               => 'Ihre Anzeige wurde gelöscht',
	'post_deleted_content_1'           => 'Hallo,',
	'post_deleted_content_2'           => 'Ihre Anzeige ":title" wurde gelöscht von <a href=":appUrl">:appName</a>',
	'post_deleted_content_3'           => 'Vielen Dank für Ihr Vertrauen und bis bald.',
	'post_deleted_content_4'           => 'Dies ist eine automatisierte E-Mail, bitte antworten Sie nicht.',
	
	
	// post_seller_contacted
	'post_seller_contacted_title'      => 'Ihre Anzeige ":title" auf :appName',
	'post_seller_contacted_content_1'  => '<strong>Kontakt Informationen:</strong>
    <br>Name: :name
    <br>Email address: :email
    <br>Phone number: :phone',
	'post_seller_contacted_content_2' => 'Diese E-Mail wurde Ihnen über die Anzeige ":title" gesendet, die Sie auf :appName eingereicht haben: <a href=":postUrl">:postUrl</a>',
    'post_seller_contached_content_3' => 'HINWEIS: Bitte beachten Sie, dass die Person, die Sie kontaktiert, standardmäßig keinen Zugriff auf Ihre E-Mail-Adresse hat, bis Sie den Kontakt mit ihr aufnehmen. Sie haben jedoch die Möglichkeit, Ihre Einstellungen in Ihrem Benutzerprofil zu ändern, um Ihre E-Mail beim Antworten auf Nachrichten anzuzeigen oder zu verbergen.',
    'post_seller_contacted_content_4' => 'Es ist wichtig, die Details der Person, mit der Sie in Kontakt stehen, immer zu überprüfen, wie z.B. deren Name, Adresse und andere relevante Informationen. Auf diese Weise haben Sie eine Möglichkeit, sie im Falle von Streitigkeiten zu kontaktieren. Zusätzlich wird empfohlen, eine Versandmethode zu wählen, die entweder von Ihnen selbst oder einer renommierten Versandfirma gehandhabt wird. Dadurch wird sichergestellt, dass Ihr Artikel sicher und rechtzeitig beim Empfänger ankommt.',
    'post_seller_contacted_content_5' => 'Es ist wichtig, vorsichtig mit Angeboten umzugehen, die zu gut klingen um wahr zu sein. Seien Sie besonders vorsichtig bei Anfragen von Personen im Ausland, die nur eine Kontakt-E-Mail haben, da diese Anfragen möglicherweise betrügerisch sind. Die Banküberweisungsmethoden, die sie vorschlagen, wie Western Union oder MoneyGram, können falsch sein. Um die Sicherheit Ihrer Transaktion zu gewährleisten, ist es am besten, Geschäfte mit verifizierten und renommierten Unternehmen und nicht mit Einzelpersonen zu machen. Stellen Sie immer sicher, die Echtheit von Angeboten zu überprüfen, bevor Sie mit Transaktionen fortfahren.',
	'post_seller_contacted_content_6'  => 'Vielen Dank für Ihr Vertrauen und bis bald.',
	'post_seller_contacted_content_7'  => 'Dies ist eine automatisierte E-Mail, bitte antworten Sie nicht.',
	
	
	// user_deleted
	'user_deleted_title'             => 'Ihr Konto wurde am gelöscht :appName',
	'user_deleted_content_1'         => 'Hallo,',
	'user_deleted_content_2'         => 'Ihr Konto wurde gelöscht von <a href=":appUrl">:appName</a> am :jetzt.',
	'user_deleted_content_3'         => 'Vielen Dank für Ihr Vertrauen und bis bald.',
	'user_deleted_content_4'         => 'Dies ist eine automatisierte E-Mail, bitte antworten Sie nicht.',
	
	
	// user_activated (new)
	'user_activated_title'           => 'Willkommen zu :appName !',
	'user_activated_content_1'       => 'Willkommen zu :appName :userName !',
	'user_activated_content_2'       => 'Ihr Konto wurde aktiviert.',
    'user_activated_content_3' => 'Herzlichen Glückwunsch, Ihr Konto wurde aktiviert! <br> <br> Bevor Sie mit dem Verkauf von Produkten beginnen, ist es wichtig, sich mit unseren FAQs und Richtlinien vertraut zu machen. Sie können diese Ressourcen auf unserer Website finden. Zusätzlich haben wir auch Youtube-Videos, die hilfreich sein können, um zu verstehen, wie man seine Produkte effektiv bewirbt. Sie können unseren Youtube-Kanal über den Google- oder Youtube-Icon am unteren Rand unserer Website aufrufen.
    <br>',
    'user_activated_content_4' => '<strong>Hinweis: Das :appName-Team empfiehlt dringend, dass Sie:</strong> <br><br>
    1 - Immer vorsichtig sein, wenn Verkäufer sich weigern, Ihnen den Artikel zu zeigen, den sie zum Verkauf oder zur Vermietung anbieten. <br>
    2 - Niemals Geld über Western Union oder andere internationale Mandate senden.
    <br><br>
    Wenn Sie Zweifel an der Glaubwürdigkeit eines Verkäufers haben, kontaktieren Sie uns bitte umgehend oder melden Sie den Verkäufer. Wir werden dann so schnell wie möglich handeln, um zu verhindern, dass jemand, der weniger informiert ist, zum Opfer wird.
    <br>
    Wir wollen, dass jeder sich bei der Verwendung von :appName sicher fühlt, deshalb sind wir auf Ihre Unterstützung angewiesen, um Verkäufer oder Käufer mit schlechten Absichten zu identifizieren und zu beseitigen.
    <br>',	
	
	// reset_password
	'reset_password_title'           => 'Setze dein Passwort zurück',
	'reset_password_action'          => 'Passwort zurücksetzen',
	'reset_password_content_1'       => 'Passwort vergessen?',
	'reset_password_content_2'       => 'Lass uns dir einen neuen besorgen.',
	'reset_password_content_3'       => 'Wenn Sie kein Zurücksetzen des Kennworts angefordert haben, sind keine weiteren Maßnahmen erforderlich.',
	
	
	// contact_form
	'contact_form_title'             => 'Neue Nachricht von :appName',
	
	
	// post_report_sent
	'post_report_sent_title'           => 'Neuer Missbrauchsbericht',
	'Post URL'                         => 'Post URL',
	
	
	// post_archived
	'post_archived_title'              => 'Ihre Anzeige wurde archiviert',
	'post_archived_content_1'          => 'Hallo,',
	'post_archived_content_2'          => 'Ihre Anzeige ":title" wurde archiviert von :appName',
	'post_archived_content_3'          => 'Sie können es erneut veröffentlichen, indem Sie hier klicken : <a href=":repostUrl">:repostUrl</a>',
	'post_archived_content_4'          => 'Wenn Sie nichts tun, wird Ihre Anzeige dauerhaft gelöscht :dateDel.',
	'post_archived_content_5'          => 'Vielen Dank für Ihr Vertrauen und bis bald.',
	'post_archived_content_6'          => 'Dies ist eine automatisierte E-Mail, bitte antworten Sie nicht.',
	
	
	// post_will_be_deleted
	'post_will_be_deleted_title'       => 'Ihre Anzeige wird in gelöscht :days Tagen',
	'post_will_be_deleted_content_1'   => 'Hallo,',
	'post_will_be_deleted_content_2'   => 'Ihre Anzeige ":title" wird in gelöscht :days Tagen von :appName.',
	'post_will_be_deleted_content_3'   => 'Sie können es erneut veröffentlichen, indem Sie hier klicken : <a href=":repostUrl">:repostUrl</a>',
	'post_will_be_deleted_content_4'   => 'Wenn Sie nichts tun, wird Ihre Anzeige dauerhaft gelöscht :dateDel.',
	'post_will_be_deleted_content_5'   => 'Vielen Dank für Ihr Vertrauen und bis bald.',
	'post_will_be_deleted_content_6'   => 'Dies ist eine automatisierte E-Mail, bitte antworten Sie nicht.',
	
	
	// post_notification
	'post_notification_title'          => 'Neue Anzeige wurde veröffentlicht',
	'post_notification_content_1'      => 'Hallo Admin,',
	'post_notification_content_2'      => 'Der Nutzer :advertiserName hat gerade eine neue Anzeige geschaltet.',
	'post_notification_content_3'      => 'Die Anzeige title: <a href=":postUrl">:title</a><br>Veröffentlicht am: :now um :time',
	
	
	// user_notification
	'user_notification_title'        => 'Neue Benutzerregistrierung',
	'user_notification_content_1'    => 'Hallo Admin,',
	'user_notification_content_2'    => ':name hat sich gerade registriert.',
	'user_notification_content_3'    => 'Registriert am: :now um :time<br>Email: <a href="mailto::email">:email</a>',
	
	
	// payment_sent
	'payment_sent_title'             => 'Vielen Dank für Ihre Zahlung!',
	'payment_sent_content_1'         => 'Hallo,',
	'payment_sent_content_2'         => 'Wir haben Ihre Zahlung für die Anzeige erhalten "<a href=":postUrl">:title</a>".',
	'payment_sent_content_3'         => 'Danke dir!',
	
	
	// payment_notification
	'payment_notification_title'     => 'Neue Zahlung wurde gesendet',
	'payment_notification_content_1' => 'Hallo Admin,',
	'payment_notification_content_2' => 'Der Nutzer :advertiserName hat gerade ein Paket für ihre Anzeige bezahlt "<a href=":postUrl">:title</a>".',
	'payment_notification_content_3' => 'DIE ZAHLUNGSDETAILS
<br><strong>Grund der Zahlung:</strong> Ad #:adId - :packageName
<br><strong>Amount:</strong> :amount :currency
<br><strong>Zahlungsmethode:</strong> :paymentMethodName',
	
	// payment_approved (new)
	'payment_approved_title'     => 'Ihre Zahlung wurde genehmigt!',
	'payment_approved_content_1' => 'Hallo,',
	'payment_approved_content_2' => 'Ihre Zahlung für die Anzeige "<a href=":postUrl">:title</a>" wurde genehmigt.',
	'payment_approved_content_3' => 'Vielen Dank!',
	'payment_approved_content_4' => 'DIE ZAHLUNGSDETAILS
    <br><strong>Grund der Zahlung:</strong> Anzeige #:adId - :packageName
    <br><strong>Amount:</strong> :amount :currency
    <br><strong>Zahlungsmethode:</strong> :paymentMethodName',
	
	
	// reply_form
	'reply_form_title'               => ':subject',
	'reply_form_content_1'           => 'Hallo,',
	'reply_form_content_2'           => '<strong>Sie haben eine Antwort von erhalten: :senderName. Siehe die Nachricht unten:</strong>',
	
	
	// generated_password
	'generated_password_title'            => 'Ihr Passwort',
	'generated_password_content_1'        => 'Hallo :userName!',
	'generated_password_content_2'        => 'Ihr Konto wurde erstellt.',
	'generated_password_verify_content_3' => 'Klicken Sie auf die Schaltfläche unten, um Ihre E-Mail-Adresse zu bestätigen.',
	'generated_password_verify_action'    => 'Email Adresse bestätigen',
	'generated_password_content_4'        => 'Ihr Passwort lautet: <strong>:randomPassword</strong>',
	'generated_password_login_action'     => 'Jetzt einloggen!',
    'generated_password_content_6'        => 'Sie erhalten diese E-Mail, weil Sie kürzlich ein neues :appName -Konto erstellt haben oder eine neue E-Mail-Adresse zu Ihrem bestehenden Konto hinzugefügt haben. Wenn Sie diese Aktion nicht initiiert haben, ignorieren Sie bitte diese E-Mail.
    <br>
    <br>
    Wenn Sie planen, Ihre Produkte oder Dienstleistungen auf unserer Plattform zu bewerben, ist es wichtig, dass Sie sich zuerst mit unseren Richtlinien vertraut machen. Um Ihnen dabei zu helfen, haben wir ein Leitfaden-Video erstellt, das Sie auf unserem Youtube-Kanal unter dem folgenden Link finden können:
    <br>
    https://www.youtube.com/channel/',


];
