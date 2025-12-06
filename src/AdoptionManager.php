<?php
/**
 * ============================================================================
 * AdoptionManager CLASS - Pet Adoption Portal
 * ============================================================================
 * 
 * Core class responsible for managing adoption requests and records:
 * - Recording new adoption requests from users
 * - Storing adoption history in JSON file
 * - Validating pet availability before recording adoption
 * - Updating pet status when adoption is requested
 * 
 * WORKFLOW:
 * 1. User initiates adoption request for a pet
 * 2. AdoptionManager validates pet exists and is available
 * 3. Creates adoption record with timestamp and requester info
 * 4. Updates pet status from 'available' to 'pending'
 * 5. Stores adoption record in adoptions.json
 * 
 * DATA STRUCTURE:
 * Each adoption record contains:
 * - id: unique adoption request ID
 * - petId: reference to the pet being adopted
 * - petName: cached pet name for reference
 * - adopterName, Email, Phone: requester information
 * - message: reason for adoption (questionnaire response)
 * - requestDate: timestamp of adoption request
 * - status: adoption status (pending/approved/rejected)
 * 
 * ============================================================================
 */

class AdoptionManager {
    // ========================================================================
    // PRIVATE PROPERTIES
    // ========================================================================
    
    /** @var string Path to the adoptions.json data file */
    private $adoptionsFile;
    
    /** @var PetManager Reference to PetManager for pet operations */
    private $petManager;

    // ========================================================================
    // PUBLIC METHODS
    // ========================================================================

    /**
     * CONSTRUCTOR - Initialize AdoptionManager
     * 
     * Sets up paths and initializes the adoptions data file if needed.
     * 
     * @param string $dataPath Path to the data directory
     * @param PetManager $petManager Instance of PetManager for pet operations
     */
    public function __construct($dataPath, $petManager) {
        $this->adoptionsFile = $dataPath . '/adoptions.json';
        $this->petManager = $petManager;
        $this->initializeFile();
    }

    /**
     * PRIVATE METHOD - Initialize adoptions file if it doesn't exist
     * 
     * Creates an empty adoptions.json file on first run.
     * This ensures the file exists before trying to read from it.
     * 
     * @return void
     */
    private function initializeFile() {
        if (!file_exists($this->adoptionsFile)) {
            // Create file with empty JSON array
            file_put_contents($this->adoptionsFile, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    /**
     * PUBLIC METHOD - Get all adoption records
     * 
     * Retrieves the complete history of all adoption requests.
     * Useful for admin dashboards and reporting.
     * 
     * @return array Array of all adoption request records
     */
    public function getAllAdoptions() {
        $content = file_get_contents($this->adoptionsFile);
        return json_decode($content, true) ?: [];
    }

    /**
     * PUBLIC METHOD - Record a new adoption request
     * 
     * Main method for submitting a new adoption request.
     * Performs validation and creates adoption record if valid.
     * 
     * VALIDATION STEPS:
     * 1. Check pet exists in system
     * 2. Check pet status is 'available'
     * 3. Create adoption request record with unique ID
     * 4. Update pet status to 'pending'
     * 5. Save adoption record to file
     * 
     * @param int $petId The ID of pet to adopt
     * @param string $adopterName Full name of person adopting
     * @param string $adopterEmail Email address for notifications
     * @param string $adopterPhone Phone number for contact
     * @param string $message Adopter's reason/message for adoption
     * 
     * @return array Response array with format:
     *         [
     *             'success' => bool,
     *             'message' => string (explanation),
     *             'adoptionId' => int (if successful)
     *         ]
     */
    public function recordAdoption($petId, $adopterName, $adopterEmail, $adopterPhone, $message) {
        // Validate pet exists
        $pet = $this->petManager->getPetById($petId);
        if (!$pet) {
            return ['success' => false, 'message' => 'Pet not found'];
        }

        // Check if pet is available for adoption
        if ($pet['status'] !== 'available') {
            return ['success' => false, 'message' => 'Pet is not available for adoption'];
        }

        // Get all existing adoptions to determine next ID
        $adoptions = $this->getAllAdoptions();
        $newId = !empty($adoptions) ? max(array_column($adoptions, 'id')) + 1 : 1;

        // Create new adoption record
        $adoption = [
            'id' => $newId,
            'petId' => $petId,
            'petName' => $pet['name'],
            'adopterName' => $adopterName,
            'adopterEmail' => $adopterEmail,
            'adopterPhone' => $adopterPhone,
            'message' => $message,
            'requestDate' => date('Y-m-d H:i:s'),  // ISO 8601 format for compatibility
            'status' => 'pending'                   // Initial status awaiting admin review
        ];

        // Add new adoption to list
        $adoptions[] = $adoption;

        // Save updated adoptions list to file
        file_put_contents(
            $this->adoptionsFile, 
            json_encode($adoptions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        // Update pet status to 'pending' to indicate adoption request exists
        $this->petManager->updatePetStatus($petId, 'pending');

        return [
            'success' => true, 
            'message' => 'Adoption request submitted successfully', 
            'adoptionId' => $newId
        ];
    }

    /**
     * PUBLIC METHOD - Get adoptions for a specific pet
     * 
     * Retrieves all adoption requests (past and present) for a given pet.
     * Useful for seeing adoption history or current pending requests.
     * 
     * @param int $petId The pet to get adoptions for
     * @return array Array of adoption records matching the pet ID
     */
    public function getPetAdoptions($petId) {
        $adoptions = $this->getAllAdoptions();
        return array_filter($adoptions, function($adoption) use ($petId) {
            return $adoption['petId'] == $petId;
        });
    }
}
