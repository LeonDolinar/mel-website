<?php
$errors = [];
$data   = [];
$emailTo = 'mel@mel-doo.com';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name)) {
        $errors['name'] = 'Molimo unesite svoje ime';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Molimo unesite svoju email adresu';
    }
    if (empty($message)) {
        $errors['message'] = 'Molimo unesite poruku';
    }
    
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
        http_response_code(400);
    } else {
        $body = "
            <strong>Ime: </strong>$name<br />
            <strong>Email: </strong>$email<br />
            <strong>Telefon: </strong>$phone<br />
            <strong>Poruka: </strong>" . nl2br($message) . "<br />
        ";
        $headers = "MIME-Version: 1.1" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "From: MEL d.o.o. <$emailTo>" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;

        if (mail($emailTo, 'Upit putem web stranice', $body, $headers)) {
            $data['success'] = true;
            $data['message'] = 'Vaša poruka je uspješno poslana';
        } else {
            $data['success'] = false;
            $data['message'] = 'Došlo je do pogreške prilikom slanja vaše poruke, pokušajte ponovno';
            http_response_code(500);
        }
    }
    echo json_encode($data);
}
