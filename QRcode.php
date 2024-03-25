<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QrCode Generator</title>
    <link rel="stylesheet" href="Qrcode.css">
</head>

<body>

    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <p>Enter Your Text Or URL</p>
            <input type="text" name="url" placeholder="Text(or)URL" id="qrText" required>

            <div id="imgbox">
                <img src="" id="qrImage">
            </div>
            <button id="saveQRcode" name="submit">Save QR Code</button>
        </form>
        <button id="qrGenerate" onclick="generateQR()">Generate QR Code</button>
    </div>
    <?php
    if (isset($_POST['submit'])) {
        $connect = mysqli_connect('localhost', 'root', '', 'qr-code-generator');
        if (mysqli_connect_errno()) {
            echo "<script> alert('connection failed')</script>" . mysqli_connect_error();
            exit();
        }
        $url = mysqli_real_escape_string($connect, $_POST['url']);
        $QrCodeLink = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $url;
        $query = "INSERT INTO `qr-codes` (`user_link`, `qr_code_link`) VALUES ('$url', '$QrCodeLink')";
        if (mysqli_query($connect, $query)) {
            echo "<script> alert('QR code has been saved successfully')</script>";
        } else {
            echo "<script> alert('The QR code had not been saved due to an error')</script> " . mysqli_error($connect);
        }

        // Close connection
        mysqli_close($connect);
    }
    ?>
    <script>
        let imgbox = document.getElementById("imgbox");
        let qrImage = document.getElementById("qrImage");
        let qrText = document.getElementById("qrText");
        let qrSave = document.getElementById("saveQRcode");
        let qrGenerate = document.getElementById("qrGenerate");

        function generateQR() {
            if (qrText.value.length > 0) {
                qrImage.src = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + qrText.value;
                imgbox.classList.add("show-image");
                qrSave.style.display = "block";
                qrGenerate.style.display = "none";
            } else {
                qrText.classList.add("error");
                setTimeout(() => {
                    qrText.classList.remove("error");
                }, 1000);
            }
        }
    </script>
</body>

</html>