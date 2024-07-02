<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add social</title>
</head>
<body>

    <?php
        // establish db connection
        require("db.php");

        // ensure that the user is authenticated
        session_start();
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // check if request is valid
        if (isset($_POST["add-social"]) && isset($_POST["social-url"]) && isset($_POST["social-name"])) {
            // collect form data
            $social_name = $_POST["social-name"];
            $social_url = $_POST["social-url"];
            $url_type = $_POST["url-type"];

            // write insert query
            $insert_sql = $connection->prepare("INSERT INTO social (name, url, url_type) VALUES (?, ?, ?)");
            $insert_sql->bind_param("sss", $social_name, $social_url, $url_type);
            $insert_sql->execute();

            // check if insert query was successful
            if ($insert_sql->affected_rows > 0) {
                // retrieve the id of social and insert it in user_social
                $get_sql = $connection->prepare("SELECT * FROM social WHERE url=?");
                $get_sql->bind_param("s", $social_url);
                $get_sql->execute();
                $result = $get_sql->get_result();

                // check if select query was successful
                if ($result->num_rows > 0) {
                    // fetch associated data
                    $social_details = $result->fetch_assoc();

                    // insert social_id and user_id in user_socials table
                    $sql = $connection->prepare("INSERT INTO user_socials (social_id, user_id) VALUES (?, ?)");
                    $sql->bind_param("ii", $social_details["social_id"], $_SESSION["user_id"]);
                    $sql->execute();

                    // check if insert query was successful
                    if ($sql->affected_rows > 0) {
                        echo '<script>alert("Social added successfully!")</script>';
                    } else {
                        header("Location: add_social.php?user_id=" . $_SESSION["user_id"]);
                        exit();
                    }
                } else {
                    header("Location: add_social.php?user_id=" . $_SESSION["user_id"]);
                    exit();
                }
            } else {
                echo '<script>alert("Social link already exists!")</script>';
            }
        }
    ?>

    <form method="POST" action="">
        <label for="social-name"><b>Name:</b></label>
        <select name="social-name" id="social-name" required>
            <option value="LinkedIn">LinkedIn</option>
            <option value="Github">Github</option>
            <option value="Gmail">Gmail</option>
            <option value="Outlook">Outlook</option>
            <option value="Portfolio">Personal Website</option>
            <option value="YouTube">YouTube</option>
            <option value="Twitter">Twitter</option>
            <option value="Instagram">Instagram</option>
            <option value="Facebook">Facebook</option>
            <option value="WhatsApp">WhatsApp</option>
            <option value="SnapChat">SnapChat</option>
            <option value="TitTok">TitTok</option>
            <option value="Discord">Discord</option>
        </select>
        <br><br>

        <label for="social-url"><b>Url:</b></label>
        <input type="social-url" placeholder="https://www.linkedin.com/in/richard-quayson/" name="social-url" id="social-url" required>
        <br><br>

        <label for="url-type"><b>Url Type:</b></label>
        <select name="url-type" id="url-type" required>
            <option value="Email">Email</option>
            <option value="Website">Website link</option>
        </select>
        <br><br>

        <button type="submit" id="add-social" name="add-social">Add Social</button>
    </form>

    <a href="user_dashboard.php">Back</a>
</body>
</html>