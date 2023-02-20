<?php

require 'vendor/autoload.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style/style.css">
    <title>roitest</title>
</head>
<body>
    <div class="main mt-5 col-lg-4 mx-auto">
        <form id = 'form' class = 'form col-lg-6 mx-auto' method="post">
            <h1 class = "text-center">Send request</h1>
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name = "data[name]" value="<?=$_SESSION['last']['name']?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name = "data[email]" value="<?=$_SESSION['last']['email']?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="tel" class="form-control" name = "data[phone]" value="<?=$_SESSION['last']['phone']?>" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" class="form-control" name = "data[price]" value="<?=$_SESSION['last']['price']?>" required>
            </div>
            <button class = "btn btn-primary" type="submit">send</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="assets/script/main.js"></script>

</html>