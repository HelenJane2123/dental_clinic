<!DOCTYPE html>
<html lang ="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" contend="width=device-width, initial-scale=1.0">
    <title>
        Verification
    </title>
    <link rel="stylesheet" href="verification.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="form" style="text-align: center;">
        <h2>Verify Your Account</h2>
        <p>We emailed you the four digit otp code. Enter the code below to confirm your email address..</p>
        <form action="" autocomplete="off">
            <div class="fields-input">
                <input type="number" name="otp1" class="otp-field" placeholder="0" min="0" max="9" required onpaste="false">
                <input type="number" name="otp2" class="otp-field" placeholder="0" min="0" max="9" required onpaste="false">
                <input type="number" name="otp3" class="otp-field" placeholder="0" min="0" max="9" required onpaste="false">
                <input type="number" name="otp4" class="otp-field" placeholder="0" min="0" max="9" required onpaste="false">
            </div>
            <div class="submit">
                <input type="submit" value="Verify Now" class=""button>
            </div>
        </form>
    </div>
</body>
</html>
