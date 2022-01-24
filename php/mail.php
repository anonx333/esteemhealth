<?php
	// error_reporting(E_ERROR | E_PARSE);
    ini_set('SMTP', "smtp.hostinger.com");
    ini_set('smtp_port', "465");
    ini_set('sendmail_from', "info@kukhurikan.com");

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {


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
        $recipient = "info@kukhurikan.com";
        $target_dir = "uploads/";

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        // Full local path to file attachment
        $file = $_FILES["fileToUpload"]["tmp_name"];
        $filename = $_FILES["fileToUpload"]["name"];

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.\n";

            // File contents
            $content = file_get_contents($target_file);
            $content = chunk_split(base64_encode($content));
            $uid = md5(uniqid(time()));

            // Headers for email
            $header = "From: ".$name." <".$email.">\r\n";
            $header .= "Reply-To: ".$recipient."\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

            // Build the email content.
            $email_content = "Name: $name\n";
            $email_content .= "Email: $email\n\n";
            $email_content .= "Contact: $contact\n";
            $email_content .= "Availability: $availability\n";
            $email_content .= "Message:\n$message\n";
                    

            // Messages and attachment
            $nmessage = "--".$uid."\r\n";
            $nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
            $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $nmessage .= "Name: $name\n";
            $nmessage .= "Email: $email\n\n";
            $nmessage .= "Contact: $contact\n";
            $nmessage .= "Availability: $availability\n";
            $nmessage .= "Message:\n$message\n";
            $nmessage .= "\r\n\r\n";
            $nmessage .= "--".$uid."\r\n";
            $nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
            $nmessage .= "Content-Transfer-Encoding: base64\r\n";
            $nmessage .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
            $nmessage .= $content."\r\n\r\n";
            $nmessage .= "--".$uid."--";

            $subject = "Email from - ".$name;

            // Send the email.
            if (mail($recipient,$subject,$nmessage,$header)) {
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