<?php
// Configuration
$admin_email = "airmaxconstruction@gmail.com";
$site_name = "Airmax Construction";

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $name = sanitize_input($_POST["name"]);
    $email = sanitize_input($_POST["email"]);
    $phone = sanitize_input($_POST["phone"]);
    $service = sanitize_input($_POST["service"]);
    $message = sanitize_input($_POST["message"]);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    // Prepare email content
    $subject = "New Contact Form Submission from $site_name";
    
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n";
    $email_content .= "Service Interested In: $service\n\n";
    $email_content .= "Message:\n$message\n";

    // Email headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email
    if (mail($admin_email, $subject, $email_content, $headers)) {
        // Send confirmation email to the user
        $user_subject = "Thank you for contacting $site_name";
        $user_message = "Dear $name,\n\n";
        $user_message .= "Thank you for contacting $site_name. We have received your message and will get back to you shortly.\n\n";
        $user_message .= "Best regards,\n$site_name Team";
        
        mail($email, $user_subject, $user_message, "From: $admin_email\r\n");

        echo json_encode(["status" => "success", "message" => "Thank you for your message. We will get back to you soon!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Sorry, there was an error sending your message. Please try again later."]);
    }
} else {
    // If not a POST request, redirect to the contact page
    header("Location: contact.html");
    exit;
}
?> 