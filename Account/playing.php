<!-- PLAYING WITH SELECTION INPUTS -->


















<!-- PLAYING WITH IMAGE UPLOAD -->
<!-- <?php
    require("db.php");

    if (isset($_POST["upload"])) {
        $name = $_POST["name"];
        $image = $_FILES["image"]["name"];

        // insert into db
        $sql = $connection->prepare("INSERT INTO test_image (name, image) VALUES (?, ?)");
        $sql->bind_param("ss", $name, $image);
        $sql->execute();

        // check if the query was successful
        if ($sql->affected_rows > 0) {
            move_uploaded_file($_FILES["image"]["tmp_name"], "profile_images/$image");
            echo '<script>alert("Image has been uploaded successfully!")</script>';
        } else {
            echo '<script>alert("Image upload failed!")</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playing</title>
</head>
<body>

    <form method="POST" action="playing.php" enctype="multipart/form-data">
        <input type="text" name="name" id="image"><br><br>
        <input type="file" name="image" id="upload_image"><br><br>
        <button type="submit" name="upload">Upload</button>
    </form>


</body>
</html> -->