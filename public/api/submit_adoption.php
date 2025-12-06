<?php
/**
 * ============================================================================
 * API ENDPOINT: Submit Adoption Request
 * ============================================================================
 * 
 * URL: /api/submit_adoption.php
 * METHOD: POST (only POST allowed)
 * CONTENT-TYPE: application/x-www-form-urlencoded or multipart/form-data
 * 
 * PURPOSE:
 * Processes adoption requests from potential adopters.
 * Validates pet availability and adopter information, then
 * records the adoption request in the system.
 * 
 * REQUIRED POST PARAMETERS:
 * - pet_id       : Integer - ID of pet to adopt (must be available)
 * - name         : String - Full name of adopter
 * - email        : String - Valid email address
 * - phone        : String - Phone number for contact
 * 
 * OPTIONAL POST PARAMETERS:
 * - message      : String - Reason for adoption (questionnaire)
 * 
 * RESPONSE FORMAT (JSON):
 * SUCCESS (200):
 * {
 *     "success": true,
 *     "message": "Adoption request submitted successfully",
 *     "adoptionId": 42
 * }
 * 
 * ERROR RESPONSES:
 * - 405: Method not allowed (not POST)
 * - 400: Missing/invalid required fields
 * - 400: Pet not available for adoption
 * - 500: Server error
 * 
 * ============================================================================
 */

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/PetManager.php';
require_once __DIR__ . '/../src/AdoptionManager.php';

// Set response header to JSON
header('Content-Type: application/json');

// ============================================================================
// 1. VALIDATE HTTP REQUEST METHOD
// ============================================================================
// This endpoint only accepts POST requests (form submissions)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// ============================================================================
// 2. EXTRACT AND SANITIZE POST DATA
// ============================================================================
// Retrieve all required and optional form fields
$petId = isset($_POST['pet_id']) ? intval($_POST['pet_id']) : null;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// ============================================================================
// 3. VALIDATE REQUIRED FIELDS
// ============================================================================
// Ensure all mandatory fields are provided
if (!$petId || !$name || !$email || !$phone) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// ============================================================================
// 4. VALIDATE EMAIL FORMAT
// ============================================================================
// Prevent invalid email addresses from being submitted
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// ============================================================================
// 5. PROCESS ADOPTION REQUEST
// ============================================================================
try {
    // Initialize managers
    $petManager = new PetManager(DATA_PATH);
    $adoptionManager = new AdoptionManager(DATA_PATH, $petManager);
    
    // Attempt to record the adoption request
    // This method validates pet existence and availability
    $result = $adoptionManager->recordAdoption($petId, $name, $email, $phone, $message);
    
    if ($result['success']) {
        // Adoption request was successful
        http_response_code(200);
        echo json_encode($result);
    } else {
        // Adoption failed (pet not found or not available)
        http_response_code(400);
        echo json_encode($result);
    }
    
} catch (Exception $e) {
    // Handle unexpected errors
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
