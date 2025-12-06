<?php
/**
 * ============================================================================
 * CATEGORY/BROWSE PAGE - Pet Adoption Portal
 * ============================================================================
 * 
 * Main pet listing page where users can:
 * - View all available pets in grid or list layout
 * - Filter pets by type, size, age range, and status
 * - Search for specific pets by name or breed
 * - Sort and paginate through results
 * - Submit adoption requests for available pets
 * 
 * PURPOSE:
 * This is the core browsing page for finding adoptable pets.
 * Provides multiple filtering and search options for easy pet discovery.
 * 
 * ARCHITECTURE:
 * - PHP Backend: Loads and prepares pet data
 * - HTML: Renders pet cards and filter sidebar
 * - JavaScript (app.js): Handles filtering, search, and interactions
 * - CSS: Responsive grid/list layouts
 * 
 * KEY FEATURES:
 * 1. Advanced Filtering (type, size, age range, status)
 * 2. Real-time Search functionality
 * 3. Grid/List view switching
 * 4. Pagination with infinite scroll
 * 5. Adoption request modal
 * 6. Success feedback messages
 * 
 * ============================================================================
 */

// ============================================================================
// 1. LOAD DEPENDENCIES
// ============================================================================

// Load configuration constants
require_once __DIR__ . '/../src/config.php';

// Load PetManager for pet data operations
require_once __DIR__ . '/../src/PetManager.php';

// ============================================================================
// 2. INITIALIZE DATA
// ============================================================================

// Create PetManager instance
$petManager = new PetManager(DATA_PATH);

// Get all pets from the system
$allPets = $petManager->getAllPets();

// Extract unique filter options for sidebar
// These values are used to populate filter checkboxes

/** Get all unique pet types (e.g., Dog, Cat, Rabbit) */
$petTypes = $petManager->getUniquePetTypes();

/** Get all unique pet sizes (e.g., Small, Medium, Large) */
$sizes = $petManager->getUniqueSizes();

/** 
 * Define age range options
 * These are fixed values (not extracted from data)
 * Maps to user-friendly labels for display
 */
$ageRanges = [
    'any' => 'Any',                    // No age restriction
    'young' => 'Young (0-1 yr)',       // Puppies/young animals
    'adult' => 'Adult (2-7 yrs)',      // Prime age animals
    'senior' => 'Senior (8+ yrs)'      // Senior animals
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Your Perfect Pet - Pet Adoption Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header/Navigation -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-heart"></i>
                <span>PetAdopt</span>
            </div>
            <nav class="nav">
                <a href="index.php" class="nav-link">Home</a>
                <a href="category.php" class="nav-link active">Browse</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="about.php" class="nav-link">About</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Find Your Perfect Pet</h1>
            <p>Your new best friend is just a few clicks away. Browse our lovely pets waiting for a forever home.</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Sidebar Filters -->
        <aside class="sidebar">
            <div class="filters">
                <h2>Filters</h2>
                
                <!-- Search Box -->
                <div class="search-box">
                    <input type="text" id="search-input" placeholder="Search by name, breed...">
                    <i class="fas fa-search"></i>
                </div>

                <!-- Filter Form -->
                <form id="filter-form">
                    <!-- Animal Type Filter -->
                    <div class="filter-group">
                        <h3>Animal Type</h3>
                        <div class="checkbox-group">
                            <?php foreach ($petTypes as $type): ?>
                                <label>
                                    <input type="checkbox" name="type" value="<?php echo htmlspecialchars($type); ?>" checked>
                                    <?php echo htmlspecialchars($type); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div class="filter-group">
                        <h3>Size</h3>
                        <div class="checkbox-group">
                            <?php foreach ($sizes as $size): ?>
                                <label>
                                    <input type="checkbox" name="size" value="<?php echo htmlspecialchars($size); ?>">
                                    <?php echo htmlspecialchars($size); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Age Range Filter -->
                    <div class="filter-group">
                        <h3>Age Range</h3>
                        <div class="radio-group">
                            <?php foreach ($ageRanges as $key => $label): ?>
                                <label>
                                    <input type="radio" name="age_range" value="<?php echo htmlspecialchars($key); ?>" <?php echo ($key === 'any') ? 'checked' : ''; ?>>
                                    <?php echo htmlspecialchars($label); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="filter-group">
                        <h3>Status</h3>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="status" value="available" checked> Available</label>
                            <label><input type="checkbox" name="status" value="pending"> Pending</label>
                            <label><input type="checkbox" name="status" value="adopted"> Adopted</label>
                        </div>
                    </div>

                    <button type="button" id="reset-filters" class="btn btn-secondary btn-block">Reset Filters</button>
                </form>
            </div>
        </aside>

        <!-- Pet Listings -->
        <section class="content">
            <!-- View Options -->
            <div class="view-options">
                <div class="results-info">
                    <span id="result-count">Showing <?php echo count($allPets); ?> pets</span>
                </div>
                <div class="view-controls">
                    <button id="grid-view" class="view-btn active" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                    <button id="list-view" class="view-btn" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <!-- Pet Cards Container -->
            <div id="pets-container" class="pets-grid">
                <?php foreach ($allPets as $pet): ?>
                    <div class="pet-card" data-pet-id="<?php echo $pet['id']; ?>" data-type="<?php echo htmlspecialchars($pet['type']); ?>" data-size="<?php echo htmlspecialchars($pet['size']); ?>" data-age="<?php echo $pet['age']; ?>" data-status="<?php echo htmlspecialchars($pet['status']); ?>">
                        <div class="pet-image">
                            <img src="<?php echo htmlspecialchars($pet['image']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>">
                            <div class="pet-badges">
                                <span class="badge badge-status status-<?php echo htmlspecialchars($pet['status']); ?>">
                                    <?php echo ucfirst(htmlspecialchars($pet['status'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="pet-details">
                            <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                            <p class="breed"><?php echo htmlspecialchars($pet['breed']); ?></p>
                            <div class="info">
                                <span><i class="fas fa-ruler-vertical"></i> <?php echo htmlspecialchars($pet['size']); ?></span>
                                <span><i class="fas fa-birthday-cake"></i> <?php echo $pet['age']; ?> yrs</span>
                            </div>
                            
                            <?php if ($pet['status'] === 'available'): ?>
                                <button class="btn btn-primary adopt-btn" onclick="openAdoptionModal(<?php echo $pet['id']; ?>)">
                                    <i class="fas fa-heart"></i> Adopt Now
                                </button>
                            <?php else: ?>
                                <button class="btn btn-disabled" disabled>
                                    Not Available
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- No Results Message -->
            <div id="no-results" class="no-results" style="display: none;">
                <i class="fas fa-search"></i>
                <p>No pets found matching your filters.</p>
            </div>
        </section>
    </main>

    <!-- Adoption Modal -->
    <div id="adoption-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeAdoptionModal()">&times;</span>
            <h2>Adoption Request</h2>
            <form id="adoption-form" onsubmit="submitAdoption(event)">
                <input type="hidden" id="pet-id-input" name="pet_id">
                
                <div class="form-group">
                    <label for="adopter-name">Full Name</label>
                    <input type="text" id="adopter-name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="adopter-email">Email</label>
                    <input type="email" id="adopter-email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="adopter-phone">Phone Number</label>
                    <input type="tel" id="adopter-phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="adopter-message">Why do you want to adopt?</label>
                    <textarea id="adopter-message" name="message" rows="4" required></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAdoptionModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="alert alert-success" style="display: none;">
        <i class="fas fa-check-circle"></i>
        <span id="success-text">Adoption request submitted successfully!</span>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Pet Adoption Portal. All rights reserved.</p>
    </footer>

    <script src="js/app.js"></script>
</body>
</html>
