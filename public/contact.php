<?php
/**
 * ============================================================================
 * CONTACT PAGE - Pet Adoption Portal
 * ============================================================================
 * 
 * Contact and communication page that provides:
 * - Direct contact information (address, phone, email)
 * - Contact form for inquiries and messages
 * - Volunteer opportunity information
 * - Multiple ways to connect with the organization
 * 
 * PURPOSE:
 * Enable visitors to:
 * - Send inquiries and questions via form
 * - Get in touch for adoption support
 * - Volunteer with the organization
 * - Access direct contact information
 * 
 * FEATURES:
 * - Contact information display
 * - AJAX form submission (no page reload)
 * - Client and server-side validation
 * - Response logging to contacts.json
 * - Email notifications to admin
 * - Volunteer call-to-action
 * 
 * FORM HANDLING:
 * - Form submission handled by: /api/submit_contact.php
 * - Frontend validation: /js/contact.js
 * - Responses stored in: /data/contacts.json
 * 
 * ============================================================================
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pet Adoption</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
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
                <a href="contact.php" class="nav-link active">Contact</a>
                <a href="about.php" class="nav-link">About</a>
            </nav>
        </div>
    </header>

    <header class="contact-header">
        <h1>Contact Our Pet Adoption Center</h1>
        <p>Get in touch with us for pet adoption inquiries, volunteering, or general questions.</p>
    </header>

    <main class="contact-container">
        <div class="contact-grid">
            
            <section class="info-card">
                <h2>Get in Touch</h2>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Uttara 10, Dhaka, Bangladesh</p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <p>+880 456-7890</p>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <p>softwave-pet@community.com</p>
                </div>
            </section>
            
            <section class="form-card">
                <h2>Send Us a Message</h2>
                <form id="contact-form" action="api/submit_contact.php" method="POST">
                    
                    <div class="form-group">
                        <label for="full-name">Full Name</label>
                        <input type="text" id="full-name" name="full_name" placeholder="MR. MOksa" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone (Optional)</label>
                        <input type="tel" id="phone" name="phone" placeholder="+880 456-7890">
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="How can we help you today?" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Send Message</button>

                    <div id="form-feedback" class="feedback-message"></div>
                </form>
            </section>

        </div>

        <section class="volunteer-card">
            <h2>Join Our Team of Volunteers</h2>
            <p>Make a difference in the lives of pets. We're always looking for passionate people to join our cause.</p>
            <button class="btn-secondary" onclick="alert('Thank you for your interest! Please email us at softwave.org@gmail.com')">Become a Volunteer</button>
        </section>
        
    </main>

    <footer class="footer">
        <p>&copy; 2025 Pet Adoption Portal. All rights reserved.</p>
    </footer>

    <script src="js/contact.js"></script>
</body>
</html>
