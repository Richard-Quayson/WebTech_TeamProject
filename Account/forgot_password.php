<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>

    <?php
        // establish db connection
        require("db.php");

        // import helper methods
        require("helper.php");

        // establish connection to php email API
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require $_SERVER['DOCUMENT_ROOT'] . '/WebTech_TeamProject/PHPMailer/src/Exception.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/WebTech_TeamProject/PHPMailer/src/PHPMailer.php';
        require $_SERVER['DOCUMENT_ROOT'] . '/WebTech_TeamProject/PHPMailer/src/SMTP.php';

        // ensure that the request is valid
        // ensure that email field is not empty 
        // and request if coming from submit button
        if (isset($_POST["reset"])) {
            // collect form data
            $email = $_POST["email"];
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            // if the value is not an email, redirect to login
            if (!$email) {
                header("Location: login.php");
                exit();
            } else {
                // ensure user is in db
                $user = get_user_details($email, "email");

                // if user doesn't exist, echo error alert
                if ($user == -1) {
                    echo '<script>alert("User with email does not exist!!")</script>';
                } else {
                    // compute expiry date of reset token
                    // get current date and increment minute by 30
                    $date = mktime(
                        date("H"), date("i") + 30, date("s"), date("m"), date("d"), date("Y")
                    );

                    $expiry_date = date("Y-m-d H:i:s", $date);

                    // generating a reset key
                    $hashed_email = md5($user["email"]);
                    $hashed_firstname = password_hash($user["firstname"], PASSWORD_DEFAULT);
                    $reset_key = $hashed_email . $hashed_firstname;

                    // insert details into password_reset table
                    $sql = $connection->prepare("INSERT INTO password_reset (email, reset_key, expDate)
                        VALUES (?, ?, ?)");
                    $sql->bind_param("sss", $email, $reset_key, $expiry_date);
                    $sql->execute();

                    // check if the query was successful
                    if ($sql->affected_rows > 0) {
                        echo '<script>alert("Records inserted successfully!")</script>';
                    } else {
                        header("Location: reset_password.php");
                        exit();
                    }

                    // creating email message

                    $recipient_email = $email;
                    $sender_email = "projectile.webgeeks@gmail.com";

                    $subject = 'Password Reset for ' . $user["firstname"] . ' ' . $user["lastname"] . ' - WebGeeks.com';
                    
                    $body = '<p>Dear ' . $user["firstname"] . ',</p>';
                    $body .= '<p>Please click on the following link to reset your password.</p>';
                    $body .= '<p>Click on this <a href="127.0.0.1/WebTech_TeamProject/Account/reset_password.php?reset_key=' . $reset_key . 
                        '&email=' . $email . '&action=reset" target="_blank">link</a> to reset password!</p>';
                    $body .= '<p>The link expires in 30 minutes, do well to reset your password within that time.</p>';
                    $body .= '<p>However, if you did not request for a password reset, ignore this email. Yet, do well to log
                        into your account and change your security details because it may be compromised.</p>';
                    $body .= '<p>Regards,</p>';
                    $body .= '<p>Web Geeks Team.</p>';

                    // creating a new PHPMailer object
                    $mail = new PHPMailer(true);
                    $mail->IsSMTP();
                    $mail->CharSet = "utf-8";
                    $mail->SMTPAuth = true;                          // ensure SMTP authentication
                    $mail->SMTPSecure = 'ssl';
                    $mail->Host = "smtp.gmail.com";                  // set gmail as the SMTP server
                    $mail->Port = 465;                               // set the SMTP port for the server 
                    $mail->Username = $sender_email;                 // sender email
                    $mail->Password = "mgsyuknntllkmdel";            // sender's gmail app password
                    $mail->From = $sender_email;                     // sender email
                    $mail->FromName = "Web Geeks";                   // sender's name
                    $mail->AddAddress($recipient_email);             // add recipient email
                    $mail->Subject = $subject;                       // subject of the email
                    $mail->IsHTML(true);               
                    $mail->Body = $body;                             // body of the email

                    // send the mail
                    // $mail->Send();

                    // check if email was sent
                    if ($mail->Send()) {
                        echo '<script>alert("Reset Password instructions has been sent to your email!")</script>';
                    } else {
                        echo '<script>alert("Error sending reset password link to your email! ' .$mail->ErrorInfo; ' ")</script>';
                    }
                }
            }
        } 
    ?>
    
    <form method="POST" action="" name="reset">
        <label><b>Email:</b></label>
        <input type="email" name="email">
        <br><br>

        <button type="submit" name="reset" value="reset">Reset</button>
    </form>
</body>
</html>