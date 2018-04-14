<?php



      // Only process POST reqeusts.
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Get the form fields and remove whitespace.
          $name = strip_tags(trim($_POST["name"]));
  				$name = str_replace(array("\r","\n"),array(" "," "),$name);
          $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
          $message = trim($_POST["message"]);

          // Check that data was sent to the mailer.
          if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
              // Set a 400 (bad request) response code and exit.
              http_response_code(400);
              echo "Dane wpisane do formularza są niepoprawne. Spróbuj jeszcze raz!";
              exit;
          }

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'secret' => "6Le9HzIUAAAAAOrOygNRxUxNgSDWZkDI4JGCTkHy",
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR']
          ]);

          $resp = json_decode(curl_exec($ch));
          curl_close($ch);

          // captcha success
          if ($resp->success) {


            // Set the recipient email address.
            // FIXME: Update this to your desired email address.
            // $recipient = "bogna.kolodziej@wp.pl";
            $recipient = "jakub.godawa@gmail.com";

            // Set the email subject.
            $subject = "Nowy kontakt ze strony: $name";

            // Build the email content.
            $email_content = "Imię: $name\n";
            $email_content .= "Email: $email\n\n";
            $email_content .= "Wiadomość:\n$message\n";

            // Build the email headers.
            $email_headers = "From: $name <$email>";

            // Send the email.
            $mail=mail($recipient, $subject, $email_content, $email_headers);
            if ($mail) {
                // Set a 200 (okay) response code.
                http_response_code(200);
                echo "Dziękujemy! Twoja wiadomość już do nas przyszła!";
            } else {
                // Set a 500 (internal server error) response code.
                http_response_code(500);
                echo "Ups! Coś poszło nie tak. Spróbuj do nas zadzwonić!";
            }
          } else {
             // captcha failure
             http_response_code(403);
             echo "Captcha nie została potwierdzona.";
          }

      } else {
          // Not a POST request, set a 403 (forbidden) response code.
          http_response_code(403);
          echo "Był pewien problem z wysłaniem wiadomości. Spróbuj jeszcze raz!";
      }

?>
