<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form fields
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Recipient email
    $to = "manalohelenjane@gmail.com";

    // Email subject
    $email_subject = "Contact Form Submission: $subject";

    // Email body
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Message:\n$message\n";

    // Additional headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $email_subject, $email_body, $headers)) {
        $_SESSION['contact_message'] = "Message sent successfully!";
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
