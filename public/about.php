<?php
/**
 * ============================================================================
 * ABOUT PAGE - Pet Adoption Portal
 * ============================================================================
 * 
 * Information page that communicates:
 * - Organization mission and values
 * - Impact statistics
 * - Team information
 * - How to get involved (adoption, fostering, volunteering)
 * - Organization story and background
 * 
 * PURPOSE:
 * Build trust and connection with visitors by sharing:
 * - Who we are and what we do
 * - Why pet adoption is important
 * - How visitors can help
 * - Our community of animal lovers
 * 
 * FEATURES:
 * - Mission statement and core values
 * - Impact metrics and achievements
 * - Team member profiles
 * - Call-to-action for getting involved
 * - Beautiful, informative layout
 * 
 * ============================================================================
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pet Adoption Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Simple About Page Styles */
        .about-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .about-section {
            margin-bottom: 50px;
        }

        .about-section h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }

        .about-section h3 {
            font-size: 1.3rem;
            color: var(--text-color);
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .about-section p {
            color: var(--text-color);
            line-height: 1.8;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .mission-list {
            list-style: none;
            padding: 0;
        }

        .mission-list li {
            background: var(--bg-color);
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary-color);
            border-radius: 4px;
        }

        .mission-list strong {
            color: var(--primary-color);
        }

        .values-list {
            columns: 2;
            column-gap: 30px;
            list-style: none;
            padding: 0;
        }

        .values-list li {
            background: var(--bg-color);
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            break-inside: avoid;
        }

        .values-list strong {
            color: var(--primary-color);
        }

        .team-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .team-member {
            background: var(--bg-color);
            padding: 20px;
            border-radius: 4px;
            border-left: 4px solid var(--secondary-color);
        }

        .team-member strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 5px;
        }

        .team-role {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .team-member p {
            font-size: 0.95rem;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }

        .stat-item {
            background: var(--bg-color);
            padding: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .stat-label {
            color: var(--text-light);
            margin-top: 5px;
        }

        .highlight-box {
            background: linear-gradient(120deg, rgba(91, 80, 163, 0.1), rgba(131, 121, 201, 0.1));
            padding: 20px;
            border-left: 4px solid var(--primary-color);
            margin: 20px 0;
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .values-list {
                columns: 1;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                text-align: center;
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
                <a href="index.php" class="nav-link">Home</a>
                <a href="category.php" class="nav-link">Browse</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="about.php" class="nav-link active">About</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="about-container">
        <!-- Who We Are -->
        <div class="about-section">
            <h2>Who We Are</h2>
            <p>PetAdopt is a pet adoption platform dedicated to connecting loving homes with wonderful animals in need. We believe every pet deserves a forever home, and every person deserves the joy of pet companionship.</p>
            <p>Our mission is simple: to make pet adoption accessible, easy, and rewarding for everyone.</p>
        </div>

        <!-- Our Mission -->
        <div class="about-section">
            <h2>Our Mission</h2>
            
            <h3>Prompt Pet & Animal Love</h3>
            <p>We believe in promoting and strengthening the deep bond between humans and animals. Every adoption is a life changed, a heart opened, and a community enriched.</p>

            <h3>Share Extra Pets</h3>
            <p>We encourage people to share the responsibility and joy of caring for pets. Through adoption and fostering, we help animals find forever families and create meaningful human-animal relationships.</p>

            <h3>Grow Community of Love</h3>
            <p>We're building a compassionate community of animal lovers who support each other, share experiences, and celebrate every adoption milestone together.</p>
        </div>

        <!-- Our Values -->
        <div class="about-section">
            <h2>Our Core Values</h2>
            <ul class="values-list">
                <li><strong>Compassion</strong> - We treat every animal with care and dignity</li>
                <li><strong>Responsibility</strong> - We ensure thorough vetting and health checks</li>
                <li><strong>Community</strong> - We foster connections among pet lovers</li>
                <li><strong>Transparency</strong> - We're honest and clear in all our dealings</li>
                <li><strong>Excellence</strong> - We strive for the highest standards in animal care</li>
                <li><strong>Accessibility</strong> - We make adoption easy for everyone</li>
            </ul>
        </div>

        <!-- Our Story -->
        <div class="about-section">
            <h2>Our Story</h2>
            <p>PetAdopt was founded on the belief that the main barrier between amazing pets and loving homes was simply lack of access and awareness. We created this platform to bridge that gap.</p>

            <div class="highlight-box">
                <p><strong>"Every animal deserves a loving home, and every person deserves the unconditional love of a pet."</strong></p>
            </div>

            <p>Over the years, we've helped thousands of pets find their forever families. We've witnessed shy rescue animals become confident companions, and lonely individuals discover the joy that comes with pet parenthood.</p>

            <p>Today, we're more than just an adoption platform—we're a community of passionate animal lovers working together to make the world a better place for animals.</p>
        </div>

        <!-- Impact -->
        <div class="about-section">
            <h2>Our Impact</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Pets Adopted</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100K+</div>
                    <div class="stat-label">Happy Families</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Partner Organizations</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1M+</div>
                    <div class="stat-label">Community Members</div>
                </div>
            </div>
        </div>

        <!-- How to Get Involved -->
        <div class="about-section">
            <h2>How to Get Involved</h2>
            <ul class="mission-list">
                <li><strong>Adopt</strong> - Browse our available pets and find your perfect companion</li>
                <li><strong>Foster</strong> - Provide temporary care for a rescue animal</li>
                <li><strong>Volunteer</strong> - Help with adoption events, pet care, and community outreach</li>
                <li><strong>Donate</strong> - Support our mission with funds or supplies</li>
                <li><strong>Share</strong> - Spread the word about available pets on social media</li>
                <li><strong>Connect</strong> - Join our community and support fellow pet lovers</li>
            </ul>
        </div>

        <!-- Team -->
        <div class="about-section">
            <h2>Meet Our Team</h2>
            <p>We're a dedicated group of animal lovers committed to making a difference:</p>
            <div class="team-list">
                <div class="team-member">
                    <strong>Sarah Johnson</strong>
                    <div class="team-role">Founder & CEO</div>
                    <p>Passionate animal advocate with 15+ years of experience in pet adoption and rescue.</p>
                </div>
                <div class="team-member">
                    <strong>Dr. Michael Chen</strong>
                    <div class="team-role">Veterinary Director</div>
                    <p>Licensed veterinarian ensuring all pets receive proper health screening and care.</p>
                </div>
                <div class="team-member">
                    <strong>Emma Rodriguez</strong>
                    <div class="team-role">Community Manager</div>
                    <p>Building and nurturing our community of pet lovers and adoption advocates.</p>
                </div>
                <div class="team-member">
                    <strong>James Wilson</strong>
                    <div class="team-role">Operations Lead</div>
                    <p>Managing partner organizations and ensuring smooth adoption processes.</p>
                </div>
                <div class="team-member">
                    <strong>Lisa Park</strong>
                    <div class="team-role">Education Specialist</div>
                    <p>Providing resources and education for responsible pet ownership.</p>
                </div>
                <div class="team-member">
                    <strong>David Martinez</strong>
                    <div class="team-role">Tech Lead</div>
                    <p>Building and maintaining the technology that powers our platform.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="about-section">
            <h2>Ready to Make a Difference?</h2>
            <p>Join our growing community of animal lovers. Whether you're looking to adopt a pet, foster a rescue animal, or simply support our mission—there's a place for you with us.</p>
            <div class="action-buttons">
                <a href="category.php" class="action-btn">Browse Pets</a>
                <a href="contact.php" class="action-btn">Get in Touch</a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Pet Adoption Portal. All rights reserved. | Building a community of love, one pet at a time.</p>
    </footer>
</body>
</html>
