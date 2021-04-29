<?php

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
        $mail->Username = "xxxx";
        $mail->Password = "xxxx";
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
        $mail->setFrom("form@geoline.org", "Kontaktní formulář Geoline");
        $mail->addReplyTo($userEmail, $userName);
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
            $msg = "<div>Chyba Maileru: Zprávu se nepodařilo odeslat. Zkuste to prosím znovu nebo nás kontaktujte na geoline@geoline.org. Error: " . $mail->ErrorInfo . "</div>";
            header('Location: kontakt.php#contact-form');

        } else {

            $msg = "<div>Vaše zpráva byla úspěšně odeslána!</div>";
            header('Location: kontakt.php#contact-form');
        }
    }

//catch and output exceptions
} catch (Exception $e) {
    $e->errorMessage();
    $msg = "<div> Error: Zprávu se nepodařilo odeslat. Kontaktujte nás prosím na geoline@geoline.org. Podrobnosti: " . $e . "</div>";
    header('Location: kontakt.php#contact-form');

} catch (\Exception $e) {
    $e->getMessage();
    $msg = "<div> Error: Zprávu se nepodařilo odeslat. Kontaktujte nás prosím na geoline@geoline.org. Podrobnosti: " . $e . "</div>";
    header('Location: kontakt.php#contact-form');
}
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="color-scheme" content="dark light">
        <title>Kontakt | Geoline CZ v.o.s.</title>
        <meta name="description" content="Geodetické práce v katastru nemovitostí.">
        <!-- favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
    
        <!-- fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/style-main.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
          
        <script>window.MSInputMethodContext && document.documentMode && document.write('<script src="https://cdn.jsdelivr.net/gh/nuxodin/ie11CustomProperties@4.1.0/ie11CustomProperties.min.js"><\/script>');</script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZGQWK87L6E"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-ZGQWK87L6E');
        </script>
    </head>
    <body>
    <header>
        <div class="topnav-wrap">
            <nav class="topnav" id="topnav">
                <ul class="menu" id="menu">
                    <li class="menu-item menu-item-icon"><a href="javascript:void(0)" onclick="mobileMenuFunction()"><i id="menu-icon" class="fa fa-bars"></i></a></li>
                    <li class="menu-item"><a class="active" href="index.html">Domů</a></li>
                    <li class="menu-item">
                        <a href="nase-sluzby.html">Naše služby</a>
                        <ul class="dropdown-menu">
                            <li class="menu-item menu-item-dropdown"><a href="nase-sluzby.html#katastr-nemovitosti">Katastr nemovitostí</a></li>
                            <li class="menu-item menu-item-dropdown"><a href="nase-sluzby.html#inzenyrska-geodezie">Inženýrská geodézie</a></li>
                        </ul>
                    </li>
                    <li class="menu-item"><a href="index.html#o-nas">O nás</a></li>
                    <li class="menu-item"><a href="reference.html">Reference</a></li>
                    <li class="menu-item"><a href="cenik.html">Ceník</a></li>
                    <li class="menu-item"><a href="kontakt.html">Kontakt</a></li>
                    <li class="menu-item"><a href="https://form.geoline.org/index.php"><i class="fas fa-user"></i> Zákaznický portál</a></li>
                </ul>
                <div class="panel">
                    <div class="logo"><a href="index.html"><img src="img/logo/geoline-A_new_nobg.png" alt="Geoline CZ v.o.s."></a></div>
                </div>
            </nav>
        </div>
    </header>
    <main class="main">
        <section>
            <h2>Kontakt</h2>
            <div class="company-container">
                <div class="company-info">
                    <h3>Geoline CZ v.o.s.</h3>
                    <p>IČO: 25034316, DIČ: CZ25034316</p>

                    <p><span class="material-icons">place</span> Kašparova 186/23</p>
                    <p><span class="material-icons">place</span> Liberec VI-Rochlice, 46006 Liberec</p>
                    <p><span class="material-icons">mail</span> <a href="mailto:geoline@geoline.org">geoline@geoline.org</a></p>
                    <p><span class="material-icons">call</span> <a href="tel:+420485104234">+420 485 104 234</a></p>
                </div>  
                <div class="company-map"><iframe class="iframe" frameborder="0" style="border:0; height: 400px;" src="https://www.google.com/maps/embed/v1/streetview?location=50.7329%2C15.0713&pano=Lt7zvpRaQKCvAvUg0qIf9g&key=AIzaSyC5Wpi96VSzFnSkPEealHiw0BbiSdVELEc&heading=102&pitch=1" allowfullscreen></iframe></div>
            </div>            
                <h2>Náš tým</h2>
                <div class="contact-container">
                    <div class="contact-card" id="svobope">
                        <div class="contact-img">
                            <img src="img/other/person.png" alt="Ing. Petr Svoboda">
                        </div>
                        <div class="contact-card-description">
                            <p class="caption">Ing. Petr Svoboda</p>
                            <p>Společník</p>
                            <p><span class="icon-envelop"></span><a href="mailto:petr@geoline.org"> petr@geoline.org</a></p>
                            <p><span class="icon-mobile"></span><a href="tel:+420608020215"> 608 02 02 15</a></p>
                        </div>
                    </div>
                    <div class="contact-card" id="vl">
                        <div class="contact-img">
                            <img src="img/other/person.png" alt="Vlastimil Coufal">
                        </div>
                        <div class="contact-card-description">
                            <p class="caption">Vlastimil Coufal</p>
                            <p>Společník</p>
                            <p><span class="icon-envelop"></span><a href="mailto:vlasta@geoline.org"> vlasta@geoline.org</a></p>
                            <p><span class="icon-mobile"></span><a href="tel:+420608987874"> 608 978 874</a></p>
                        </div>
                    </div>
                    <div class="contact-card" id="placeholder">
                        <div class="contact-img">
                            <img src="img/other/person.png" alt="placeholder1">
                        </div>
                        <div class="contact-card-description">
                            <p class="caption">Ondřej</p>
                            <p>Webdesigner</p>
                            <p><span class="icon-envelop"></span><a href="mailto:ondra@svobodao.cz"> ondra@svobodao.cz</a></p>
                            <p><span class="icon-mobile"></span><a href="tel:+420777888999"> 775 510 215</a></p>
                        </div>
                    </div>
                    <div class="contact-card" id="placeholder2">
                        <div class="contact-img">
                            <img src="img/other/person.png" alt="placeholder2">
                        </div>
                        <div class="contact-card-description">
                            <p class="caption">Ondřej</p>
                            <p>Webdesigner</p>
                            <p><span class="icon-envelop"></span><a href="mailto:ondra@svobodao.cz"> ondra@svobodao.cz</a>
                            </p>
                            <p><span class="icon-mobile"></span><a href="tel:+420777777777"> 775 510 215</a></p>
                        </div>
                    </div>   
                </div>  
        </section>
        <section>
            <h2>Kontaktní formulář</h2>
            <div class="form">
                <form id="contact-form" name="contact-form" method="POST" enctype="multipart/form-data">
                    <?php if (!empty($msg)) {
                        echo "<div>$msg</div>";
                    } ?>
                    <div class="form-field">
                        <label for="name">Vaše jméno</label><br>
                        <input class="form-control" type="text" id="name" name="name" required>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label><br>
                        <input class="form-control" type="email" id="email" name="email" required>
                    </div>
                    <div class="form-field">
                        <label for="address">Adresa</label><br>
                        <input class="form-control" type="text" id="address" name="address" value="" required>
                    </div>
                    <div class="form-field">
                        <label for="tel">Telefon</label><br>
                        <input class="form-control" type="tel" id="tel" name="tel" value="" required>
                    </div>
                    <div class="form-field">
                        <label for="message">Vaše zpráva</label><br>
                        <textarea class="form-control" name="message" id="message" rows="4" required></textarea>
                    </div>
                    <div class="form-submit">
                        <button id="submit-all" type="submit" class="button button-highlighted" form="contact-form" name="send" value="Odeslat">Odeslat</button>
                    </div>
                </form> 
            </div>
        </section>
    </main>
    <footer>
        <div class="contacts">
            <div class="footer-box footer-box-contacts">
                <div class="footer-box">
                    <span class="icon-location"></span>
                    <p>Kašparova 186/23,</p>
                    <p>Rochlice, 460 06 Liberec</p>
                </div>
                <div class="footer-box">
                    <span class="icon-envelop"></span>
                    <p><a href="mailto:geoline@geoline.org">geoline@geoline.org</a></p>
                </div>
                <div class="footer-box">
                    <span class="icon-phone"></span>
                    <p><a href="tel:+420485104234">+420 485 104 234</a></p>
                    <p>Po-Pá 8 - 17</p>
                </div>
            </div>
            <div class="footer-box footer-box-nav">
                <a class="link" href="index.html#nase-sluzby">Naše služby</a>
                <a class="link" href="">Katastr nemovitostí</a>
                <a class="link" href="">Inženýrská geodézie</a>
                <a class="link" href="">O nás</a>
                <a class="link" href="">Reference</a>
                <a class="link" href="">Ceník</a>
                <a class="link" href="">Kontakt</a>
            </div>
            <div class="footer-box footer-box-icons">
                <a class="button button-footer" href="https://goo.gl/maps/tL4XGtbSeiwCiP3p9"><span class="icon-location"></span></a>
                <a class="button button-footer" href="mailto:geoline@geoline.org"><span class="icon-envelop"></span></a>
                <a class="button button-footer" href="tel:+420485104234"><span class="icon-phone"></span></a>
            </div>
        </div>
        <div class="copyright">
            <p>v0.6 © 2021 GEOLINE CZ v.o.s.</p>
            <p>Made by <a href="https://svobodao.cz">Ondřej Svoboda</a></p>

        </div>
        <a onclick="topFunction()" class="floating-button floating-button-top" id="topbtn" title="Go to top"><i class="fas fa-arrow-up"></i></a>
    </footer>
    <!-- scroll to top button -->
    <script>
        var mybutton = document.getElementById("topbtn");
        
        window.onscroll = function() {scrollFunction()};
        
        function scrollFunction() {
          if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
          } else {
            mybutton.style.display = "none";
          }
        }
        
        function topFunction() {
          document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;
        }
    </script>
    <!-- top nav mobile hamburger menu -->
    <script>
        function mobileMenuFunction() {
          var menu = document.getElementById("topnav");
          var menuicon = document.getElementById("menu-icon");

          if (menu.className === "topnav") {
            menu.className += " responsive";
            menuicon.className == "fas fa-times";

          } else {
            menu.className = "topnav";
            menuicon.className == "fas fa-bars";
          }
        }
    </script>
</body>
</html>