<?php
    require('credential.php');
	error_reporting(E_ERROR | E_PARSE);
    require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
    
    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $smtp->host;
        $mail->Port       = $smtp->port;
        $mail->SMTPSecure = $smtp->smtpSecure;
        $mail->SMTPAuth   = $smtp->smtpAuth;
        $mail->Username = $smtp->email;
        $mail->Password = $smtp->password;
        $mail->SetFrom('admin@esteemhealthsolutions.com.au', 'FromEmail');
        $mail->addAddress('admin@esteemhealthsolutions.com.au', 'ToEmail');
        //$mail->SMTPDebug  = 3;
        //$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";}; //$mail->Debugoutput = 'echo';
        $mail->IsHTML(true);

        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
                $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $contact = trim($_POST["contact"]);
        $availability = trim($_POST["availability"]);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($contact) OR empty($availability) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $target_dir = "uploads/";

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        // Full local path to file attachment
        $file = $_FILES["fileToUpload"]["tmp_name"];
        $filename = $_FILES["fileToUpload"]["name"];


        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.\n";
            
            $mail->Subject = "Email from - ".$name;
            $mail->addAttachment($target_file, $filename);
            $nmessage = "Name: $name\n";
            $nmessage .= "Email: $email\n\n";
            $nmessage .= "Contact: $contact\n";
            $nmessage .= "Availability: $availability\n";
            $nmessage .= "Message:\n$message\n";

            $mail->Body = $nmessage;

            // Send the email.
            if ($mail->send()) {
                // Set a 200 (okay) response code.
                http_response_code(200);
                echo "Thank You! Your message has been sent.";
            } else {
                // Set a 500 (internal server error) response code.
                http_response_code(500);
                echo "Oops! Something went wrong and we couldn't send your message.";
            }
        } else {
            http_response_code(403);
            echo "Sorry, there was an error uploading your file.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>