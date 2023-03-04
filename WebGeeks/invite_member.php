<?php 
    // start a session
    session_start();

    // check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

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

    // collect the project_id from the url
    if (isset($_GET["project_id"])) {
        $project_id = $_GET["project_id"];
    }

    if (strstr($_SERVER['HTTP_REFERER'], "view_project")) {

        // collect the form data
        $email = $_POST["email"];
        $role_id = $_POST["role"];

        // check if is user is already registered with us
        $member_exist = get_user_details($email, "email");

        // if user doesn't exist, send them an email to sign up and join the project
        if ($member_exist == -1){
            // retrieve project details
            $project_details = get_project_details($project_id, "project_id");

            // retrieve user details
            $user_details = get_user_details($_SESSION["user_id"], "user_id");

            // retrieve role details
            $role_details = get_role_details($role_id);

            // creating email message

            $recipient_email = $email;
            $sender_email = "projectile.webgeeks@gmail.com";

            $subject = 'Invitation from ' . $user_details["firstname"] . ' ' . $user_details["lastname"] . ", to join the " . 
            $project_details["project_name"] . ' project';

            $body = '<p>Dear Sir/Madam,</p>';
            $body .= "<p>You skills has caught a project lead's attention and you're being invited to join an ongoing project.</p>";
            $body .= $user_details["firstname"] . ' ' . $user_details["lastname"] . ' would like you to join the ' . $project_details["project_name"];
            $body .= 'as a/an ' . $role_details["role_name"] . ' Click <a href="127.0.0.1/WebTech_TeamProject/WebGeeks/register.php' . '">here</a> to sign up and accept invitation.';
            $body .= '<p><b>Project Description:</b><br>' . $project_details["project_description"] . '</p>';
            $body .= '<p>Regards,</p>';
            $body .= '<p>Web Geeks Team.</p>';

            // creating a new PHPMailer object
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;                          // ensure SMTP authentication
            $mail->SMTPSecure = "ssl";
            $mail->Host = "smtp.gmail.com";                  // set gmail as the SMTP server
            $mail->Port = 465;                               // set the SMTP port for the server 
            $mail->Username = $sender_email;                 // sender email
            $mail->Password = "mxomcwyelebrdqah";            // sender's gmail app password
            $mail->From = $sender_email;                     // sender email
            $mail->FromName = "Web Geeks";                   // sender's name
            $mail->AddAddress($recipient_email);             // add recipient email
            $mail->Subject = $subject;                       // subject of the email
            $mail->IsHTML(true);               
            $mail->Body = $body;                             // body of the email

            // check if the email was sent
            if ($mail->Send()) {
                // insert the details into the invited projects page
                // invitation is valid for only seven days
                $date = mktime(
                    date("H"), date("i"), date("s"), date("m"), date("d") + 7, date("Y")
                );
                $expiry_date = date("Y-m-d H:i:s", $date);

                $insert_sql = $connection->prepare("INSERT INTO invited_members VALUES (?, ?, ?, ?)");
                $insert_sql->bind_param("isis", $project_id, $email, $role_id, $expiry_date);
                $insert_sql->execute();

                // check if the query was successful
                if ($insert_sql->affected_rows > 0) {

                    // NOTE: Do check your spam if the details are sent successfully, yet you don't see it in your inbox
                    echo '<script>alert("Invitation details stored successfully!")</script>';
                } else {
                    echo '<script>alert("User is already a project member!")</script>';
                }
            } else {
                echo '<script>alert("Failed to send invitation email!")</script>';
            }
            
        } else {
            // ensure user is not already a member of the project
            $project_member = get_project_member($member_exist["user_id"], $project_id);

            if ($project_member != -1) {
                echo '<script>alert("User is a member of the project!")</script>';
            } else {
                // ensure that the user hasn't already been invited
            
                // check if user is in the project_members entity
                $invited_member = get_invited_member($email, $project_id);

                if ($invited_member == -1) {
                    // insert member into invited projects
                    // invitation is valid for only seven days
                    $date = mktime(
                        date("H"), date("i"), date("s"), date("m"), date("d") + 7, date("Y")
                    );
                    $expiry_date = date("Y-m-d H:i:s", $date);

                    $insert_sql = $connection->prepare("INSERT INTO invited_members VALUES (?, ?, ?, ?)");
                    $insert_sql->bind_param("isis", $project_id, $email, $role_id, $expiry_date);
                    $insert_sql->execute();

                    // check if the query was successful
                    if ($insert_sql->affected_rows > 0) {
                        echo '<script>alert("Invitation details stored successfully!")</script>';
                    } else {
                        echo '<script>alert("Failed to insert invitation details!")</script>';
                    }

                } else {
                    echo '<script>alert("User with email: ' . $email . ' has already been invited!")</script>';
                }
            }
        }
    }
?>    