<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form fields
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = nl2br(htmlspecialchars(trim($_POST['message'])));

    // Recipient email
    $to = "rosellesantander@rs-dentalclinic.com";

    // Email subject
    $email_subject = "Contact Form Submission: $subject";

    // Email body
    $email_body = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                line-height: 1.6;
            }
            .email-container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .header {
                background-color: #007bff;
                color: #ffffff;
                padding: 10px;
                text-align: center;
                border-radius: 8px 8px 0 0;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
            }
            .content {
                padding: 20px;
            }
            .content p {
                margin: 10px 0;
            }
            .content strong {
                color: #007bff;
            }
            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 12px;
                color: #555;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='header'>
                <h1>Contact Form Submission</h1>
            </div>
            <div class='content'>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> <a href='mailto:$email'>$email</a></p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong><br>$message</p>
            </div>
            <div class='footer'>
                <p>&copy; 2024 Your Company. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Additional headers
   // Headers for HTML email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: rosellesantander@rs-dentalclinic.com\r\n"; // Use a no-reply email address
    $headers .= "Reply-To: $email\r\n";


    // Send the email
    if (mail($to, $email_subject, $email_body, $headers)) {
        $_SESSION['contact_message'] = "Thank you, $name! Your message has been sent successfully.";
    } else {
        $_SESSION['contact_message'] = "Failed to send message. Please try again.";
    }

    // Redirect back to contact.php
    header("Location: ../contact.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
