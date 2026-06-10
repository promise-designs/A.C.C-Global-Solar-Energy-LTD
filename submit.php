<?php
// A.C C Global Solar Energy Ltd - Contact Form Handler

// Configuration
$recipient_email = "your-email@example.com"; // Replace with your actual email
$whatsapp_number = "2349065604615"; // Your WhatsApp number
$company_name = "A.C C Global Solar Energy Ltd";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If validation fails, redirect back with error
    if (!empty($errors)) {
        $error_msg = urlencode(implode(", ", $errors));
        header("Location: contact.html?status=error&message=$error_msg");
        exit();
    }
    
    // Prepare email
    $email_subject = "New Contact Form Submission - $subject";
    
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #0d0d0d; color: #e0e0e0; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #00BFFF; color: #0d0d0d; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #1a1a1a; padding: 20px; border-radius: 0 0 10px 10px; }
            .field { margin-bottom: 15px; padding: 10px; background: #0d0d0d; border-left: 3px solid #FFD700; }
            .label { color: #00BFFF; font-weight: bold; }
            .value { color: #e0e0e0; }
            .footer { text-align: center; margin-top: 20px; color: #FFD700; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>$company_name</h2>
                <p>New Contact Form Message</p>
            </div>
            <div class='content'>
                <div class='field'>
                    <span class='label'>Name:</span><br>
                    <span class='value'>$name</span>
                </div>
                <div class='field'>
                    <span class='label'>Email:</span><br>
                    <span class='value'>$email</span>
                </div>
                <div class='field'>
                    <span class='label'>Subject:</span><br>
                    <span class='value'>$subject</span>
                </div>
                <div class='field'>
                    <span class='label'>Message:</span><br>
                    <span class='value'>" . nl2br($message) . "</span>
                </div>
            </div>
            <div class='footer'>
                <p>Sent from $company_name Website</p>
                <p>Reply via WhatsApp: https://wa.me/$whatsapp_number</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $company_name <noreply@accglobalsolar.com>" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
    
    // Send email
    $mail_sent = mail($recipient_email, $email_subject, $email_body, $headers);
    
    if ($mail_sent) {
        // Success - redirect back with success message
        header("Location: contact.html?status=success&message=" . urlencode("Thank you $name! Your message has been received. We will contact you shortly."));
        exit();
    } else {
        // Failed - redirect back with error
        header("Location: contact.html?status=error&message=" . urlencode("Sorry, there was an error sending your message. Please try again or contact us directly on WhatsApp."));
        exit();
    }
    
} else {
    // If accessed directly without POST, redirect to contact page
    header("Location: contact.html");
    exit();
}
?>
