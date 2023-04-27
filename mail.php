<?php
ini_set('SMTP', 'smtp.free.fr');
ini_set('smtp_port', 465);
ini_set('username', 'jonathan.auber@free.fr');
ini_set('password', 'Princesszelda76!');

// Destinataire de l'e-mail
$to = "auber.jonathan@gmail.com";

// Sujet de l'e-mail
$subject = "Test d'envoi d'e-mail via PHP avec la fonction mail()";

// Corps de l'e-mail
$message = "Ceci est un test d'envoi d'e-mail via PHP avec la fonction mail().";

// Headers de l'e-mail
$headers = "From: jonathan.auber@free.fr" . "\r\n" .
           "Reply-To: jonathan.auber@free.fr" . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Envoi de l'e-mail
if(mail($to, $subject, $message, $headers)) {
    echo "L'e-mail a été envoyé avec succès.";
} else {
    echo "L'envoi de l'e-mail a échoué.";
}

