<?php
/**
 * ============================================================================
 * API ENDPOINT: Submit Contact Form
 * ============================================================================
 * 
 * URL: /api/submit_contact.php
 * METHOD: POST
 * PURPOSE: Processes contact form submissions from the Contact Us page
 * 
 * FEATURES:
 * 1. Validates and sanitizes user input
 * 2. Sends email notification to admin
 * 3. Logs contact submissions to contacts.json for record keeping
 * 4. Includes IP address tracking for security
 * 
 * REQUIRED POST PARAMETERS:
 * - full_name    : String - Name of the person contacting us
 * - email        : String - Valid email address for reply
 * 
 * OPTIONAL POST PARAMETERS:
 * - phone        : String - Phone number for direct contact
 * - message      : String - The actual message/inquiry
 * 
 * RESPONSE:
 * {
 *     "success": true/false,
 *     "message": "Human-readable response message"
 * }
 * 
 * ============================================================================
 */

// ============================================================================
// 1. SET RESPONSE HEADER
// ============================================================================
header('Content-Type: application/json');

// ============================================================================
// 2. VERIFY REQUEST METHOD
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// ============================================================================
// 3. SANITIZE INPUT DATA
// ============================================================================
// Use built-in PHP filters to prevent XSS and injection attacks
$fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone    = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$message  = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// ============================================================================
// 4. VALIDATE REQUIRED FIELDS
// ============================================================================
// Ensure mandatory fields are present and email is valid
if (empty($fullName) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid name, email, and message.']);
    exit;
}

// ============================================================================
// 5. CONFIGURE EMAIL NOTIFICATION
// ============================================================================
$to      = 'contact@petadoption.com';  // TODO: Change to actual email address
$subject = "New Contact Inquiry from $fullName";

// Build detailed email body with all contact information
$body = "=== New Contact Inquiry ===\n\n";
$body .= "Name: " . $fullName . "\n";
$body .= "Email: " . $email . "\n";
$body .= "Phone: " . (!empty($phone) ? $phone : "Not provided") . "\n";
$body .= "Date: " . date('Y-m-d H:i:s') . "\n";
$body .= "\n--- Message ---\n";
$body .= $message . "\n";
$body .= "\n===================\n";

// Configure email headers
$headers = "From: noreply@petadoption.com\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// ============================================================================
// 6. SEND EMAIL NOTIFICATION
// ============================================================================
// Uses PHP's built-in mail() function
// @ suppresses warnings (mail() may fail on localhost/development)
$mail_sent = @mail($to, $subject, $body, $headers);

// ============================================================================
// 7. LOG CONTACT SUBMISSION TO JSON FILE
// ============================================================================
// Maintains local record of all contact submissions for backup/archival
// This ensures we never lose contact data even if email fails

$contactsFile = __DIR__ . '/../../data/contacts.json';

// Create contact record with metadata
$contactData = [
    'id' => time(),                         // Use timestamp as unique ID
    'full_name' => $fullName,
    'email' => $email,
    'phone' => $phone,
    'message' => $message,
    'submitted_at' => date('Y-m-d H:i:s'),
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'  // Track submitter IP
];

// Read existing contacts from file
$contacts = [];
if (file_exists($contactsFile)) {
    $contactsJson = file_get_contents($contactsFile);
    $contacts = json_decode($contactsJson, true) ?? [];
}

// Add new contact to list
$contacts[] = $contactData;

// Ensure contacts is a valid array (fallback safety check)
if (!is_array($contacts)) {
    $contacts = [$contactData];
}

// Save updated contacts list to file
file_put_contents(
    $contactsFile, 
    json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

// ============================================================================
// 8. SEND RESPONSE TO CLIENT
// ============================================================================
// Return appropriate response regardless of email success
// (because contact is always logged to file)

if ($mail_sent) {
    // Email was sent successfully
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Your message has been successfully sent! We will respond shortly.'
    ]);
} else {
    // Email failed but contact was logged to file
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Your message was logged, but could not be emailed. Please contact us by phone at +880 21413241234.'
    ]);
}
