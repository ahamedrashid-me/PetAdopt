<?php
/**
 * ============================================================================
 * PetManager CLASS - Pet Adoption Portal
 * ============================================================================
 * 
 * Core class responsible for all pet-related data operations including:
 * - Loading and saving pet data from/to JSON files
 * - Filtering pets by various criteria (type, size, age, status)
 * - Searching for pets by name or breed
 * - Managing pet status updates
 * - Retrieving pet statistics and metadata
 * 
 * DESIGN PATTERN: Data Access Object (DAO)
 * This class abstracts all database/file operations for pets, making it
 * easy to switch storage backends (JSON -> Database) in the future.
 * 
 * DATA STRUCTURE:
 * Each pet has: id, name, type, breed, age, size, image, status, adoptedBy, adoptionDate
 * 
 * USAGE EXAMPLE:
 * $petManager = new PetManager(DATA_PATH);
 * $allPets = $petManager->getAllPets();
 * $filtered = $petManager->filterPets(['type' => ['Dog'], 'age_range' => 'young']);
 * ============================================================================
 */

class PetManager {
    // ========================================================================
    // PRIVATE PROPERTIES
    // ========================================================================
    
    /** @var string Path to the pets.json data file */
    private $petsFile;
    
    /** @var array Cached array of all pets loaded from file */
    private $pets = [];

    // ========================================================================
    // PUBLIC METHODS
    // ========================================================================

    /**
     * CONSTRUCTOR - Initialize PetManager with data path
     * 
     * @param string $dataPath Path to the data directory containing pets.json
     */
    public function __construct($dataPath) {
        $this->petsFile = $dataPath . '/pets.json';
        $this->loadPets();  // Load all pets from file on instantiation
    }

    /**
     * PRIVATE METHOD - Load pets from JSON file
     * 
     * Called automatically on class instantiation.
     * Reads pets.json and decodes it into a PHP array for in-memory operations.
     * If file doesn't exist, initializes empty pets array.
     * 
     * @return void
     */
    private function loadPets() {
        if (file_exists($this->petsFile)) {
            $content = file_get_contents($this->petsFile);
            // json_decode with true = associative array; ?: [] = fallback to empty array on failure
            $this->pets = json_decode($content, true) ?: [];
        }
    }

    /**
     * PUBLIC METHOD - Get all pets
     * 
     * Returns the complete list of all pets in the system.
     * Used for displaying full pet listings or initial data loading.
     * 
     * @return array Array of all pet records
     */
    public function getAllPets() {
        return $this->pets;
    }

    /**
     * PUBLIC METHOD - Get a single pet by ID
     * 
     * Searches through all pets and returns the matching pet record.
     * Returns null if pet is not found (important for error handling).
     * 
     * @param int $id The pet's unique identifier
     * @return array|null Pet record array, or null if not found
     */
    public function getPetById($id) {
        foreach ($this->pets as $pet) {
            if ($pet['id'] == $id) {
                return $pet;
            }
        }
        return null;
    }

    /**
     * PUBLIC METHOD - Get unique pet types
     * 
     * Extracts all unique pet types (e.g., 'Dog', 'Cat', 'Rabbit') from the pets list.
     * Used to populate type filter checkboxes on the category page.
     * Results are sorted alphabetically for consistent UI presentation.
     * 
     * @return array Sorted array of unique pet types
     */
    public function getUniquePetTypes() {
        $types = array_unique(array_column($this->pets, 'type'));
        sort($types);
        return $types;
    }

    /**
     * PUBLIC METHOD - Get unique pet sizes
     * 
     * Extracts all unique pet sizes and returns them in a logical order:
     * Small -> Medium -> Large (not alphabetical order for better UX).
     * Used to populate size filter checkboxes on the category page.
     * 
     * @return array Array of pet sizes in custom order
     */
    public function getUniqueSizes() {
        $sizes = array_unique(array_column($this->pets, 'size'));
        $order = ['Small', 'Medium', 'Large'];
        usort($sizes, function($a, $b) use ($order) {
            return array_search($a, $order) - array_search($b, $order);
        });
        return $sizes;
    }

    /**
     * PUBLIC METHOD - Filter pets based on multiple criteria
     * 
     * Advanced filtering system that allows filtering by:
     * - Type: Filter to specific pet types (e.g., ['Dog', 'Cat'])
     * - Size: Filter to specific sizes (e.g., ['Small', 'Medium'])
     * - Age Range: Filter by age brackets (young/adult/senior)
     * - Status: Filter by adoption status (available/pending/adopted)
     * - Search: Text search matching name or breed
     * 
     * FILTER STRUCTURE:
     * [
     *     'type' => ['Dog', 'Cat'],                    // Array of types
     *     'size' => ['Small'],                         // Array of sizes
     *     'age_range' => 'young',                      // young/adult/senior/any
     *     'status' => ['available'],                   // Array of statuses
     *     'search' => 'Buddy'                          // Search string
     * ]
     * 
     * @param array $filters Associative array of filter criteria
     * @return array Filtered array of pet records
     */
    public function filterPets($filters = []) {
        $result = $this->pets;

        // ====================================================================
        // FILTER BY TYPE (e.g., Dog, Cat, Rabbit)
        // ====================================================================
        if (!empty($filters['type']) && is_array($filters['type'])) {
            $result = array_filter($result, function($pet) use ($filters) {
                return in_array($pet['type'], $filters['type']);
            });
        }

        // ====================================================================
        // FILTER BY SIZE (Small, Medium, Large)
        // ====================================================================
        if (!empty($filters['size']) && is_array($filters['size'])) {
            $result = array_filter($result, function($pet) use ($filters) {
                return in_array($pet['size'], $filters['size']);
            });
        }

        // ====================================================================
        // FILTER BY AGE RANGE (young, adult, senior)
        // ====================================================================
        if (!empty($filters['age_range']) && $filters['age_range'] !== 'any') {
            $result = array_filter($result, function($pet) use ($filters) {
                $age = $pet['age'];
                switch ($filters['age_range']) {
                    case 'young':
                        return $age <= 1;           // 0-1 years old
                    case 'adult':
                        return $age >= 2 && $age <= 7;  // 2-7 years old
                    case 'senior':
                        return $age >= 8;           // 8+ years old
                    default:
                        return true;
                }
            });
        }

        // ====================================================================
        // FILTER BY STATUS (available, pending, adopted)
        // ====================================================================
        if (!empty($filters['status']) && is_array($filters['status'])) {
            $result = array_filter($result, function($pet) use ($filters) {
                return in_array($pet['status'], $filters['status']);
            });
        }

        // ====================================================================
        // SEARCH BY NAME OR BREED (case-insensitive text search)
        // ====================================================================
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $result = array_filter($result, function($pet) use ($search) {
                // Check if search term appears in pet name OR breed (case-insensitive)
                return strpos(strtolower($pet['name']), $search) !== false ||
                       strpos(strtolower($pet['breed']), $search) !== false;
            });
        }

        // Re-index array so key indices are sequential (0, 1, 2, etc.)
        return array_values($result);
    }

    /**
     * PUBLIC METHOD - Get pets by status
     * 
     * Retrieves all pets matching a specific status.
     * Useful for getting lists of available, pending, or adopted pets.
     * 
     * @param string $status The status to filter by (available/pending/adopted)
     * @return array Array of pets with matching status
     */
    public function getPetsByStatus($status) {
        return array_filter($this->pets, function($pet) use ($status) {
            return $pet['status'] === $status;
        });
    }

    /**
     * PUBLIC METHOD - Update pet status
     * 
     * Changes a pet's status and updates related metadata.
     * When a pet is adopted, also records who adopted it and the date.
     * Automatically persists changes to the JSON file.
     * 
     * @param int $petId The pet's unique identifier
     * @param string $status The new status (available/pending/adopted)
     * @param string $adoptedBy Name of adopter (optional, used when status='adopted')
     * @return bool True if save was successful, false otherwise
     */
    public function updatePetStatus($petId, $status, $adoptedBy = null) {
        foreach ($this->pets as &$pet) {
            if ($pet['id'] == $petId) {
                $pet['status'] = $status;
                if ($status === 'adopted') {
                    // Record adoption details
                    $pet['adoptedBy'] = $adoptedBy;
                    $pet['adoptionDate'] = date('Y-m-d');
                }
                break;
            }
        }
        return $this->savePets();
    }

    /**
     * PRIVATE METHOD - Save pets to JSON file
     * 
     * Persists the in-memory pets array to the pets.json file.
     * Uses JSON_PRETTY_PRINT for readable formatting and 
     * JSON_UNESCAPED_SLASHES to keep URLs intact.
     * 
     * @return bool True if file write was successful, false on failure
     */
    private function savePets() {
        return file_put_contents(
            $this->petsFile, 
            json_encode($this->pets, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        ) !== false;
    }

    /**
     * PUBLIC METHOD - Add a new pet
     * 
     * Creates a new pet record with an auto-generated ID
     * (ID = max existing ID + 1) and saves it to file.
     * 
     * @param array $petData The pet information to add
     * @return bool True if pet was successfully added and saved
     */
    public function addPet($petData) {
        $newId = max(array_column($this->pets, 'id')) + 1;
        $petData['id'] = $newId;
        $this->pets[] = $petData;
        return $this->savePets();
    }
}
