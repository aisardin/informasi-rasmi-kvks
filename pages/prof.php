<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../auth/upload.php" 
      method="POST" 
      enctype="multipart/form-data">


    <input type="file" 
           name="gambar"
           required>


    <button type="submit" name="upload">
        Upload Gambar
    </button>


</form>
</body>
</html>