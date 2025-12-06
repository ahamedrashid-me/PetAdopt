<?php
/**
 * ============================================================================
 * API ENDPOINT: Get Filtered Pets
 * ============================================================================
 * 
 * URL: /api/get_pets.php
 * METHOD: GET
 * CONTENT-TYPE: application/json
 * 
 * PURPOSE:
 * Fetches a filtered list of pets based on query parameters.
 * This endpoint is used by the frontend JavaScript to dynamically
 * filter and search pets without page refresh.
 * 
 * QUERY PARAMETERS:
 * - type[]      : Array of pet types to filter by (e.g., type[]=Dog&type[]=Cat)
 * - size[]      : Array of sizes to filter by (Small, Medium, Large)
 * - age_range   : Age range filter (young/adult/senior/any)
 * - status[]    : Array of statuses (available/pending/adopted)
 * - search      : Text search for pet name or breed
 * 
 * RESPONSE FORMAT (JSON):
 * {
 *     "success": true,
 *     "count": 15,
 *     "pets": [
 *         { "id": 1, "name": "Max", "type": "Dog", ... },
 *         { "id": 2, "name": "Bella", "type": "Cat", ... }
 *     ]
 * }
 * 
 * USAGE EXAMPLES:
 * GET /api/get_pets.php
 * GET /api/get_pets.php?type[]=Dog
 * GET /api/get_pets.php?size[]=Small&age_range=young
 * GET /api/get_pets.php?search=fluffy
 * 
 * ============================================================================
 */

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/PetManager.php';

// Set response header to JSON
header('Content-Type: application/json');

try {
    // Initialize PetManager
    $petManager = new PetManager(DATA_PATH);
    
    // ====================================================================
    // 1. COLLECT FILTER PARAMETERS FROM QUERY STRING
    // ====================================================================
    // Supports both single values and arrays for flexibility
    
    $filters = [];
    
    // TYPE FILTER: Support multiple pet types
    if (isset($_GET['type'])) {
        $filters['type'] = is_array($_GET['type']) ? $_GET['type'] : [$_GET['type']];
    }
    
    // SIZE FILTER: Support multiple sizes
    if (isset($_GET['size'])) {
        $filters['size'] = is_array($_GET['size']) ? $_GET['size'] : [$_GET['size']];
    }
    
    // AGE RANGE FILTER: Single value (young/adult/senior/any)
    if (isset($_GET['age_range'])) {
        $filters['age_range'] = $_GET['age_range'];
    }
    
    // STATUS FILTER: Support multiple statuses
    if (isset($_GET['status'])) {
        $filters['status'] = is_array($_GET['status']) ? $_GET['status'] : [$_GET['status']];
    }
    
    // SEARCH FILTER: Text search in pet names and breeds
    if (isset($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    
    // ====================================================================
    // 2. APPLY FILTERS USING PetManager
    // ====================================================================
    // PetManager handles all filtering logic and returns filtered results
    $pets = $petManager->filterPets($filters);
    
    // ====================================================================
    // 3. RETURN SUCCESS RESPONSE
    // ====================================================================
    echo json_encode([
        'success' => true,
        'count' => count($pets),           // Number of pets returned
        'pets' => $pets                    // Array of pet records
    ]);
    
} catch (Exception $e) {
    // ====================================================================
    // 4. ERROR HANDLING
    // ====================================================================
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
