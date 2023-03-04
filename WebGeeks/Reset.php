<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forgot Password?</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/css/reset-style.css">
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
                        // do nothing
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
                    $body .= '<p>Click on this <a href="127.0.0.1/WebTech_TeamProject/WebGeeks/reset_password.php?reset_key=' . $reset_key . 
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

                    // check if email was sent
                    if ($mail->Send()) {
                        header("Location: password_reset.html");
                        exit();
                    } else {
                        echo '<script>alert("Error sending reset password link to your email! ' .$mail->ErrorInfo; ' ")</script>';
                    }
                }
            }
        } 
    ?>


    <div class="container" id="cont1">
        <div class="row justify-content-center" id="row1">
            <div class="col-md-8" id="col1">
                <div class="card shadow-lg o-hidden border-0 my-5" id="card1">
                    <div class="card-body p-0">
                        <div class="row">
                            <!-- <div class="col-lg-6 d-none d-lg-flex" id="col2"><img src="assets/img/picture.png"></div> -->
                                <div class="col-lg-10">
                                <div class="p-5" style="margin-left: 15vw;">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-2">Reset your password.</h4>
                                    <form class="user" method="POST" action="" name="reset">
                                        <div class="mb-3">
                                            <input class="form-control" type="email" id="" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email" style="font-size: 16px;">
                                        </div>
                                        <button type="submit" name="reset" value="reset" class="btn btn-primary d-block btn-user w-100" id="btn2">Reset</button>

                                    </form>
                                    
                                    <p style="padding-right: 20px;margin-right: 236px;"></p>
                                    <div class="text-center">
                                        <p id="p2" style="font-size: 13px;"></p>
                                    </div>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>