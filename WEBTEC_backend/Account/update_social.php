<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account Details</title>
</head>
<body>

    <?php 
        // start a session
        session_start();

        // establish database connection
        require("db.php");

        // check if the user is logged in
        // if not, redirect to login page
        if (!isset($_SESSION["user_id"])) {
            header("Location: login.php");
            exit();
        }

        // if the request is a GET request, load the information of the social
        if (isset($_GET["social_id"])) {
            // get the social's id from url
            $social_id = $_GET["social_id"];

            // retrieve social details
            $get_social = $connection->prepare("SELECT * FROM social WHERE social_id=?");
            $get_social->bind_param("i", $social_id);
            $get_social->execute();
            $social_result = $get_social->get_result();

            // check if the query was successful
            if ($social_result->num_rows > 0) {
                $social_details = $social_result->fetch_assoc();
            } else {
                header("Location: user_dashboard.php");
                exit();
            }
        } 

        // if the request is a POST request, update data in db
        if (isset($_POST["update-social"])) {

            // collect form data
            $social_name = $_POST["social-name"];
            $social_url = $_POST["social-url"];
            $url_type = $_POST["url-type"];

            // write update query
            $get_sql = $connection->prepare("UPDATE social SET name=?, url=?, url_type=? WHERE social_id=?");
            $get_sql->bind_param("sssi", $social_name, $social_url, $url_type, $_GET["social_id"]);
            $get_sql->execute();

            // check if the query was successful
            if ($get_sql->affected_rows > 0) {
                // alert user on update
                echo '<script>alert("Update successful!")</script>';

                // redirect user to update page
                header("Location: update_social.php?social_id=" . $_GET["social_id"]);
                exit();
            } else {
                echo '<script>alert("No change detected or social link already exist!")</script>';
            }
        }
    ?>

    <h1>Update</h1>
    <p>Please fill this form to update social details</p>
    <hr>

    <form method="POST" enctype="multipart/form-data">
        <label for="social-name"><b>Name:</b></label>
        <select name="social-name" id="social-name" required>
            <?php
                $social_names = array(
                    "LinkedIn", "Github", "Gmail", "Outlook", "Portfolio", "YouTube", "Twitter",
                    "Instagram", "Facebook", "WhatsApp", "SnapChat", "TitTok", "Discord", "Other"
                );

                foreach ($social_names as $social_name) {
                    // if social name matches social under review, append selected
                    if ($social_details["name"] == $social_name) {
                        echo '<option value="' . $social_name . '" selected>' . $social_name . '</option>';
                    } else {
                        echo '<option value="' . $social_name . '">' . $social_name . '</option>';
                    }
                }
            ?>
        </select>
        <br><br>

        <label for="social-url"><b>Url:</b></label>
        <input keyup="determineUrlType()" type="social-url" placeholder="https://www.linkedin.com/in/richard-quayson/" name="social-url" 
            id="social-url" value='<?php echo $social_details["url"] ?>' required>
        <br><br>

        <label for="url-type"><b>Url Type:</b></label>
        <select name="url-type" id="url-type" required>
            <?php
                $url_types = array("Email", "Website");
                
                foreach ($url_types as $url_type) {
                    // if url type matches social under review, append selected
                    if ($social_details["url_type"] == $url_type) {
                        echo '<option value="' . $url_type . '" selected>' . $url_type . '</option>';
                    } else {
                        echo '<option value="' . $url_type . '">' . $url_type . '</option>';
                    }
                }
            ?>
        </select>
        <br><br>

        <button type="submit" name="update-social" id="update-social">Update Social</button>
    </form>

    <script>
        // create DOM variables
        var social_url = document.getElementById("social-url"),
        url_category = document.getElementById("url-type"),
        update_button = document.getElementById("update-social");

        const regexPatterns = {
        email: /^[^0-9!@#$%^&*(+=)\\[\].></{}`]\w+([\.-_]?\w+)*@([a-z\d-]+)\.([a-z]{2,10})(\.[a-z]{2,10})?$/,
        website: /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/,
        }

        function validateInput(inputTag, regexPattern) {
            return regexPattern.test(inputTag.value);
        }

        function determineUrlType() {
            $isEmail = validateInput(social_url, regexPatterns["email"]);
            $isWebsite = validateInput(social_url, regexPatterns["website"]);

            if ($isEmail) {
                url_category.value = "Email";
            } else if ($isWebsite) {
                url_category.value = "Website";
            }
        }
        
        // create an event listener for the social-url
        document.addEventListener("click", determineUrlType());
        update_button.addEventListener("submit", determineUrlType());

        
    </script>

    <br>
    <a href="add_social.php?user_id=<?php echo $_SESSION["user_id"]; ?>">Add social</a>

    <br><br>
    <a href="user_dashboard.php">Back</a>
</body>
</html>