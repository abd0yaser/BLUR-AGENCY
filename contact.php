<?php

// Add blastex smtp class
require "blastex-smtp.php";

// With authentication (don't need use addPassword and addUser after this)
// $m = new BlastexSmtp('email@breakermind.com','Password');

// Create object
$m = new BlastexSmtp();

// Show logs
$m->Debug(0);
// Disable Self signed server certificate
$m->disableSelfSigned(1);

// Smtp server hostname
$m->addHostname("smtp-mail.outlook.com");
// Add smtp port 25, 587
$m->addPort(587);

// Smtp server email
$m->addUser("ahmednasser.fcis@gmail.com");
// Smtp server password
$m->addPassword("Tawasul@2020");

// Add from
$m->addFrom("ahmednasser.fcis@gmail.com", "Tawasul");
// Add to
$m->addTo("ahmednasser.fcis@gmail.com", "Tawasul");

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = [
    "name" => "Name",
    "subject" => "Subject",
    "company" => "Company",
    "email" => "Email",
    "message" => "Message",
];

// message that will be displayed when everything is OK :)
$okMessage =
    "Contact form successfully submitted. Thank you, I will get back to you soon!";

// If something goes wrong, we will display this message.
$errorMessage =
    "There was an error while submitting the form. Please try again later";

/*
 *  LET'S DO THE SENDING
 */

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try {
    if (count($_POST) == 0) {
        throw new \Exception("Form is empty");
    }

    $emailText = "You have a new message from your contact form<br>";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value<br>";
        }

        // Text message
        $m->addText($emailText);
        // Html message
        $m->addHtml($emailText);
        // Message subject
        $m->addSubject("New Contact Form");
    }

    if ($m->Send() == 1) {
        echo "Email has been sent!";
    } else {
        // Show last error
        echo $m->lastError;
    }

    $responseArray = ["type" => "success", "message" => $okMessage];
} catch (\Exception $e) {
    $responseArray = ["type" => "danger", "message" => $errorMessage];
}

// if requested by AJAX request return JSON response
if (
    !empty($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
    strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest"
) {
    $encoded = json_encode($responseArray);

    header("Content-Type: application/json");

    echo $encoded;
}
// else just display the message
else {
    echo $responseArray["message"];
}
