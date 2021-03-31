<?php

//WORK IN PROGRESS :-)

//. 　　　。　　　　•　 　ﾟ　　。 　　.

//　　　.　　　 　　.　　　　　。　　 。　. 　

//.　　 。　　　　　 ඞ 。 . 　　 • 　　　　•

//　　ﾟ　　 Black was not An Impostor.　 。　.

//　　'　　　 1 Impostor remains 　 　　。

//　　ﾟ　　　.　　　. ,　　　　.　 .

//PHPMailer dependecies
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

try {
    //create new phpmailer
    $mail = new PHPMailer(true);

    if(!empty($_POST["send"])) {
        //post html form inputs
        $userName = $_POST['name'];
        $userEmail = $_POST['email'];
        $userAddress = $_POST['address'];
        $userTel = $_POST['tel'];
        $userMessage = $_POST['message'];

        //set correct encoding and language
        $mail->CharSet = "UTF-8";
        $mail->Encoding = 'base64';
        $mail->setLanguage('cs', 'PHPMailer-master/language/');
        
        //smtp server settings
        $mail->SMTPDebug = 0;
        $mail->IsSMTP();
        $mail->Host = 'smtp.forpsi.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = "****";
        $mail->Password = "****";
        $mail->SMTPSecure = 'ssl';
        
        //mail subject
        $subject = 'Kontaktní formulář Geoline';
    
        //encode subject
        $preferences = ['input-charset' => 'UTF-8', 'output-charset' => 'UTF-8'];
        $encoded_subject = iconv_mime_encode('Subject', $subject, $preferences);
        $encoded_subject = substr($encoded_subject, strlen('Subject: '));
    
        //mail html message
        $message = '<html><body style="font-size: 16px;">';
        $message .= '<img src="https://form.geoline.org/content/logo/geoline-A_new_male.png" alt="Geoline logo" />';
        $message .= '<h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 24px;">Kontaktní formulář Geoline CZ</h1>';
        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
        $message .= "<tr style='background: #f2f2f2;'><td><strong>Vaše jméno:</strong> </td><td>" . $userName . "</td></tr>";
        $message .= "<tr><td><strong>Email:</strong> </td><td>" . $userEmail . "</td></tr>";
        $message .= "<tr style='background: #f2f2f2;'><td><strong>Adresa:</strong> </td><td>" . $userAddress . "</td></tr>";
        $message .= "<tr><td><strong>Telefon:</strong> </td><td>" . $userTel . "</td></tr>";
        $message .= "<tr style='background: #f2f2f2;'><td><strong>Vaše zpráva:</strong> </td><td>" . $userMessage . "</td></tr>";
        $message .= "</table>";
        $message .= "</body></html>";
    
        //addresses
        $mail->setFrom($userEmail, $userName);
        // $mail->addAddress("geoline@geoline.org");

        //just for testing
        $mail->addAddress("ondra2305@gmail.com");
    
        //mail content
        $mail->isHTML(true);
        $mail->Subject = $encoded_subject;
        $mail->MsgHTML($message);
        
        //attachments
        //taken from https://github.com/PHPMailer/PHPMailer/blob/master/examples/send_multiple_file_upload.phps

        for ($ct = 0, $ctMax = count($_FILES['attachment']['tmp_name']); $ct < $ctMax; $ct++) {
            //Extract an extension from the provided filename
            $ext = PHPMailer::mb_pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
            //Define a safe location to move the uploaded file to, preserving the extension
            $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['attachment']['name'][$ct])) . '.' . $ext;
            $filename = $_FILES['attachment']['name'][$ct];
            if (move_uploaded_file($_FILES['attachment']['tmp_name'][$ct], $uploadfile)) {
                if (!$mail->addAttachment($uploadfile, $filename)) {
                    $msg .= 'Nepovedlo se připojit soubor ' . $filename;
                }
            } else {
                $msg .= 'Nepovedlo se přesunout soubor do: ' . $uploadfile;
            }
        }

        //send mail
        if (! $mail->Send()) {
            //mailer error
            echo "<div>Chyba Maileru: Zprávu se nepodařilo odeslat. Zkuste to prosím znovu nebo nás kontaktujte na geoline@geoline.org. Error: " . $mail->ErrorInfo . "</div>";

        } else {
            echo "<div>Vaše zpráva byla úspěšně odeslána!</div>";
        }
    }

//catch and output exceptions
} catch (Exception $e) {
    $e->errorMessage();
    echo "<div> Error: Zprávu se nepodařilo odeslat. Kontaktujte nás prosím na geoline@geoline.org. Podrobnosti: " . $e . "</div>";

} catch (\Exception $e) {
    $e->getMessage();
    echo "<div> Error: Zprávu se nepodařilo odeslat. Kontaktujte nás prosím na geoline@geoline.org. Podrobnosti: " . $e . "</div>";
}