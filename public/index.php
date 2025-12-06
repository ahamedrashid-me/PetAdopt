<?php
/**
 * ============================================================================
 * HOME PAGE - Pet Adoption Portal
 * ============================================================================
 * 
 * Landing page for the Pet Adoption Portal with:
 * - Hero section with call-to-action
 * - Statistics about adoption success
 * - Featured pets carousel
 * - "Why Adopt?" information section
 * - Contact call-to-action
 * 
 * PURPOSE:
 * The home page is the first impression for new visitors.
 * It encourages visitors to browse pets and learn about adoption.
 * Statistics and featured pets build trust and drive engagement.
 * 
 * FEATURES:
 * - Dynamic statistics from pet database
 * - Randomized featured pets rotation
 * - Responsive design for all devices
 * - SEO-friendly structure
 * 
 * ============================================================================
 */

// ============================================================================
// 1. LOAD DEPENDENCIES
// ============================================================================

// Load configuration constants (paths, settings, debug mode)
require_once __DIR__ . '/../src/config.php';

// Load PetManager class for pet data operations
require_once __DIR__ . '/../src/PetManager.php';

// ============================================================================
// 2. INITIALIZE PET DATA
// ============================================================================

// Create PetManager instance to access pet data
$petManager = new PetManager(DATA_PATH);

// Get all pets from the system
$allPets = $petManager->getAllPets();

// Filter available pets (status = 'available')
$availablePets = array_filter($allPets, fn($pet) => $pet['status'] === 'available');

// Filter adopted pets (status = 'adopted')
$adoptedPets = array_filter($allPets, fn($pet) => $pet['status'] === 'adopted');

// Get random featured pets (up to 3) for the featured section
$featuredPets = array_slice(
    array_filter($allPets, fn($pet) => $pet['status'] === 'available'),  // Only show available
    0,
    3
);

// Randomize the order of featured pets for variety
shuffle($featuredPets);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Adoption Portal - Find Your Perfect Companion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Home Page Specific Styles */
        .home-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .home-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }

        .home-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .home-hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .home-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 800;
            line-height: 1.2;
        }

        .home-hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 15px 35px;
            font-size: 1.05rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary {
            background: white;
            color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }

        /* Stats Section */
        .stats-section {
            background: var(--bg-color);
            padding: 60px 20px;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 1rem;
            font-weight: 500;
        }

        .stat-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        /* Featured Pets Section */
        .featured-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .featured-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .featured-header h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .featured-header p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .featured-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .featured-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .featured-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
            background: var(--bg-color);
        }

        .featured-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .featured-card:hover .featured-image img {
            transform: scale(1.1);
        }

        .featured-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--success-color);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .featured-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .featured-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .featured-breed {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .featured-details {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .featured-details span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .featured-button {
            margin-top: auto;
            padding: 12px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .featured-button:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .view-all-btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .view-all-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        /* Why Section */
        .why-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 60px 20px;
            margin-top: 40px;
        }

        .why-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .why-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .why-header h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .why-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .why-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .why-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
        }

        .why-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .why-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .why-text {
            font-size: 0.95rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            background: white;
            padding: 60px 20px;
            text-align: center;
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 2.2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 800;
        }

        .cta-text {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .cta-button {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 15px 50px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .cta-button:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(91, 80, 163, 0.3);
        }

        @media (max-width: 768px) {
            .home-hero h1 {
                font-size: 2.2rem;
            }

            .home-hero p {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn-hero {
                width: 100%;
                justify-content: center;
            }

            .featured-header h2 {
                font-size: 1.8rem;
            }

            .why-header h2 {
                font-size: 1.8rem;
            }

            .cta-title {
                font-size: 1.5rem;
            }
        }
    </style>
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
                <a href="index.php" class="nav-link active">Home</a>
                <a href="category.php" class="nav-link">Browse</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="about.php" class="nav-link">About</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="home-hero">
        <div class="home-hero-content">
            <h1>Find Your Perfect Companion</h1>
            <p>Give a loving pet a forever home. Browse through hundreds of adorable animals waiting to meet their new family today.</p>
            <div class="hero-buttons">
                <a href="category.php" class="btn-hero btn-hero-primary">
                    <i class="fas fa-paw"></i> Start Browsing
                </a>
                <a href="#featured" class="btn-hero btn-hero-secondary">
                    <i class="fas fa-star"></i> See Featured Pets
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-paw"></i></div>
                <div class="stat-number"><?php echo count($allPets); ?></div>
                <div class="stat-label">Pets Available</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-heart"></i></div>
                <div class="stat-number"><?php echo count($availablePets); ?></div>
                <div class="stat-label">Ready for Adoption</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-home"></i></div>
                <div class="stat-number"><?php echo count($adoptedPets); ?></div>
                <div class="stat-label">Happy Homes Found</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-number"><?php echo count(array_unique(array_map(fn($p) => $p['type'], $allPets))); ?></div>
                <div class="stat-label">Pet Categories</div>
            </div>
        </div>
    </section>

    <!-- Featured Pets Section -->
    <section class="featured-section" id="featured">
        <div class="featured-header">
            <h2>Featured Pets</h2>
            <p>Meet some of our wonderful animals looking for their forever homes</p>
        </div>

        <?php if (count($featuredPets) > 0): ?>
            <div class="featured-grid">
                <?php foreach ($featuredPets as $pet): ?>
                    <div class="featured-card">
                        <div class="featured-image">
                            <img src="<?php echo htmlspecialchars($pet['image']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>">
                            <div class="featured-badge">Available Now</div>
                        </div>
                        <div class="featured-info">
                            <div class="featured-name"><?php echo htmlspecialchars($pet['name']); ?></div>
                            <div class="featured-breed"><?php echo htmlspecialchars($pet['breed']); ?></div>
                            <div class="featured-details">
                                <span><i class="fas fa-ruler-vertical"></i> <?php echo htmlspecialchars($pet['size']); ?></span>
                                <span><i class="fas fa-birthday-cake"></i> <?php echo $pet['age']; ?> yrs</span>
                            </div>
                            <button class="featured-button" onclick="window.location.href='category.php'">
                                <i class="fas fa-heart"></i> View Details
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="category.php" class="view-all-btn">
                <i class="fas fa-search"></i> View All Pets
            </a>
        </div>
    </section>

    <!-- Why Adopt Section -->
    <section class="why-section">
        <div class="why-container">
            <div class="why-header">
                <h2>Why Adopt?</h2>
                <p>Discover the joy and benefits of pet adoption</p>
            </div>

            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-heart"></i></div>
                    <div class="why-title">Save a Life</div>
                    <div class="why-text">Every adoption makes room for another animal in need of care and love.</div>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-heartbeat"></i></div>
                    <div class="why-title">Health Benefits</div>
                    <div class="why-text">Pet owners experience lower stress levels and better overall health.</div>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-home"></i></div>
                    <div class="why-title">Meet Your Match</div>
                    <div class="why-text">Find a pet that perfectly suits your lifestyle and personality.</div>
                </div>
                <div class="why-card">
                    <div class="why-icon"><i class="fas fa-stethoscope"></i></div>
                    <div class="why-title">Fully Checked</div>
                    <div class="why-text">Our pets are health-checked, vaccinated, and ready for their new home.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="cta-container">
            <h2 class="cta-title">Ready to Find Your Best Friend?</h2>
            <p class="cta-text">Browse through our collection of lovable pets and start your adoption journey today. Your perfect companion is waiting!</p>
            <a href="category.php" class="cta-button">
                <i class="fas fa-paw"></i> Browse Pets Now
            </a>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2025 Pet Adoption Portal. All rights reserved. | Giving pets the loving homes they deserve.</p>
    </footer>
</body>
</html>
