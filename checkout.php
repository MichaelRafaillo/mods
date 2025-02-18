<?php
session_start();
include './database/user.php';
include './database/dbh.php';



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marly ODS - Abandoned checkout Msg</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="./js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</head>

<body>
    <?php include './includes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">WhatsApp Checkout Reminder</h2>
                <form id="whatsappForm">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your name">
                    </div>
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number:</label>
                        <input type="text" class="form-control" id="phoneNumber" placeholder="Enter phone number">
                    </div>
                    <div class="form-group">
                        <label for="checkoutUrl">Checkout URL:</label>
                        <input type="text" class="form-control" id="checkoutUrl" placeholder="Enter checkout URL">
                    </div>
                    <button type="button" class="btn btn-primary btn-block" onclick="sendWhatsAppMessage()">Send WhatsApp Message</button>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <script>
        function sendWhatsAppMessage() {
            var name = document.getElementById("name").value;
            var phoneNumber = document.getElementById("phoneNumber").value;
            var checkoutUrl = document.getElementById("checkoutUrl").value;

            // Construct the WhatsApp message
            var message = "Hello *" + name + "*! \n \nWe understand that life can get busy, but we wouldn't you to miss out on your favorites. We 've saved your items in your cart - click the button below to complete your purchase now:\n \n " + checkoutUrl + "\n \nMarly Team.";


            // Construct the WhatsApp URL
            var whatsappUrl = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);

            // Redirect to WhatsApp
            window.location.href = whatsappUrl;
        }
    </script>
</body>

</html>