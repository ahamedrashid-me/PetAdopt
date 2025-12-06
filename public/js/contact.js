/**
 * ============================================================================
 * Contact Page - Form Handling and Submission
 * ============================================================================
 * 
 * Handles the contact form on the Contact Us page:
 * - Client-side validation (before sending to server)
 * - Form submission via AJAX (no page reload)
 * - Response handling (success/error messages)
 * - Loading state feedback to user
 * 
 * FEATURES:
 * - Real-time input validation
 * - Email format verification
 * - Asynchronous form submission
 * - User-friendly error and success messages
 * - Form reset on successful submission
 * 
 * ============================================================================
 */

/**
 * Initialize contact form when DOM is ready
 * 
 * This self-executing function runs as soon as the page loads.
 * It finds the contact form and attaches event listeners.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Get references to form elements
    const form = document.getElementById('contact-form');
    const feedback = document.getElementById('form-feedback');

    // Only proceed if contact form exists on page
    if (form) {
        /**
         * Handle form submission event
         * 
         * Called when user clicks the submit button.
         * Performs client-side validation and sends data to server.
         * 
         * @param {Event} e The form submission event
         */
        form.addEventListener('submit', async function(e) {
            e.preventDefault();  // Prevent default form submission (page reload)
            
            // ================================================================
            // 1. CLIENT-SIDE VALIDATION
            // ================================================================
            
            // Get and trim form input values
            const fullName = document.getElementById('full-name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            // Check that all required fields are filled
            if (!fullName || !email || !message) {
                showFeedback('Please fill in all required fields.', 'error');
                return;
            }

            // Validate email format using regex pattern
            // Ensures email has format: something@something.something
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showFeedback('Please enter a valid email address.', 'error');
                return;
            }

            // ================================================================
            // 2. PREPARE FORM DATA FOR SUBMISSION
            // ================================================================
            
            // Create FormData object from the HTML form
            // Automatically includes all form fields (name, value pairs)
            const formData = new FormData(this);
            
            // Show loading message to user
            showFeedback('Sending message...', 'loading');

            try {
                // ============================================================
                // 3. SUBMIT FORM DATA TO SERVER
                // ============================================================
                
                // Send form data to PHP endpoint via POST request
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData
                    // Note: Don't set Content-Type header when using FormData
                    // Browser will set it automatically with proper boundary
                });
                
                // Parse JSON response from server
                const result = await response.json();

                // ============================================================
                // 4. HANDLE SERVER RESPONSE
                // ============================================================
                
                if (response.ok && result.success) {
                    // Success: show success message and reset form
                    showFeedback(
                        result.message || 'Thank you for your message! We will be in touch soon.', 
                        'success'
                    );
                    form.reset();  // Clear all form fields
                } else {
                    // Error: show server error message to user
                    showFeedback(
                        result.message || 'There was an issue sending your message. Please try again.', 
                        'error'
                    );
                }

            } catch (error) {
                // ============================================================
                // 5. ERROR HANDLING - Network or JavaScript errors
                // ============================================================
                
                console.error('Submission Error:', error);
                showFeedback(
                    'Network error or server connection failed. Please check your connection.', 
                    'error'
                );
            }
        });
    }

    /**
     * Display feedback message to user
     * 
     * Shows temporary messages for:
     * - 'error': Validation errors or server errors (stays visible)
     * - 'success': Successful form submission (auto-hides after 5s)
     * - 'loading': Form is being submitted (stays visible until replaced)
     * 
     * @param {String} message The message text to display
     * @param {String} type The type of message (error/success/loading)
     */
    function showFeedback(message, type) {
        // Update message text
        feedback.textContent = message;
        
        // Update CSS class to style message appropriately
        feedback.className = `feedback-message ${type}`;
        
        // Make message visible
        feedback.style.display = 'block';
        
        // Auto-hide success messages after 5 seconds
        // Keep error and loading messages visible indefinitely
        if (type !== 'error' && type !== 'loading') {
            setTimeout(() => {
                feedback.style.display = 'none';
            }, 5000);
        }
    }
});
