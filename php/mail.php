<?php
    function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

	error_reporting(E_ERROR | E_PARSE);
    ini_set('SMTP', "smtp.hostinger.com");
    ini_set('smtp_port', "465");
    ini_set('sendmail_from', "admin@esteemhealthsolutions.com");

    
    // Set the recipient email address.
    // FIXME: Update this to your desired email address.
    $recipient = "dingosloth@gmail.com";

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

        $uid = md5(uniqid(time()));

        $subject = "Email from - ".$name;

        $nmessage = "--".$uid."\r\n";
        $nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $nmessage .= "Name: $name\n";
        $nmessage .= "Email: $email\n\n";
        $nmessage .= "Contact: $contact\n";
        $nmessage .= "Availability: $availability\n";
        $nmessage .= "Message:\n$message\n";
        $nmessage .= "\r\n\r\n";

        $target_dir = "uploads/";

        foreach ($file_ary as $file) {
            $target_file = $target_dir.time()."_".basename($file["name"]);
            $file_main = $file["tmp_name"];
            $filename = $file["name"];

            try{
                move_uploaded_file($file["tmp_name"], $target_file);
                echo "File ". htmlspecialchars( basename( $file["name"])). " has been uploaded.\n";
                $content = file_get_contents($target_file);
                $content = chunk_split(base64_encode($content));

                $nmessage .= implode("\r\n", [
                    "",
                    "--$uid",
                    "Content-Type: application/octet-stream; name=\"". basename($filename) . "\"",
                    "Content-Transfer-Encoding: base64",
                    "Content-Disposition: attachment",
                    "",
                    $content,
                    "--$uid"
                  ]);
            }
            catch(Exception $ex){
                http_response_code(403);
                echo "Sorry, there was an error uploading file: ".htmlspecialchars( basename( $file["name"]));

                $file_upload_status = false;
                return;
            }
        }

        $nmessage.= "--";
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

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>