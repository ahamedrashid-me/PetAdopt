/**
 * ============================================================================
 * Pet Adoption Portal - Main Application JavaScript
 * ============================================================================
 * 
 * Core frontend application managing:
 * - Pet filtering and search functionality
 * - Dynamic view mode switching (grid/list)
 * - Pagination and infinite scroll
 * - Modal dialogs for adoption requests
 * - Real-time filter updates
 * 
 * ARCHITECTURE: Class-based with global helper functions
 * Main class: PetAdoptionApp
 * Global functions: openAdoptionModal, closeAdoptionModal, submitAdoption, etc.
 * 
 * USAGE:
 * - Auto-initializes when DOM is ready
 * - Attaches to DOM elements defined in category.php
 * - Communicates with backend API for data operations
 * 
 * ============================================================================
 */

/**
 * Main Application Class - Manages all pet listing and filtering logic
 */
class PetAdoptionApp {
    // ========================================================================
    // CLASS PROPERTIES
    // ========================================================================
    
    /** @var Array All pets loaded from the page */
    allPets = [];
    
    /** @var Array Pets currently shown after applying filters */
    filteredPets = [];
    
    /** @var String Current view mode: 'grid' or 'list' */
    currentViewMode = 'grid';
    
    /** @var Number Pets to show per page (for pagination) */
    itemsPerPage = 12;
    
    /** @var Number Current page number (for pagination) */
    currentPage = 1;
    
    /** @var Boolean Flag to prevent loading multiple pages simultaneously */
    isLoadingMore = false;

    // ========================================================================
    // CONSTRUCTOR AND INITIALIZATION
    // ========================================================================

    /**
     * CONSTRUCTOR - Initialize the application
     */
    constructor() {
        this.allPets = [];
        this.filteredPets = [];
        this.currentViewMode = 'grid';
        this.itemsPerPage = 12;
        this.currentPage = 1;
        this.isLoadingMore = false;
        this.init();
    }

    /**
     * Initialize the application
     * Runs immediately after instantiation
     */
    init() {
        this.cacheElements();         // Cache all DOM element references
        this.attachEventListeners();  // Bind event handlers
        this.loadPets();              // Load pets from page
    }

    // ========================================================================
    // DOM ELEMENT CACHING
    // ========================================================================

    /**
     * Cache DOM element references for performance
     * Prevents repeated DOM queries on every event
     */
    cacheElements() {
        this.filterForm = document.getElementById('filter-form');
        this.searchInput = document.getElementById('search-input');
        this.petsContainer = document.getElementById('pets-container');
        this.noResults = document.getElementById('no-results');
        this.resultCount = document.getElementById('result-count');
        this.gridViewBtn = document.getElementById('grid-view');
        this.listViewBtn = document.getElementById('list-view');
        this.resetFiltersBtn = document.getElementById('reset-filters');
        this.adoptionModal = document.getElementById('adoption-modal');
        this.adoptionForm = document.getElementById('adoption-form');
        this.petIdInput = document.getElementById('pet-id-input');
        this.successMessage = document.getElementById('success-message');
    }

    // ========================================================================
    // EVENT LISTENERS
    // ========================================================================

    /**
     * Attach event listeners to interactive elements
     * Binds filter changes, search input, view mode buttons, etc.
     */
    attachEventListeners() {
        // Trigger filtering when any filter checkbox or radio button changes
        this.filterForm.addEventListener('change', () => this.applyFilters());
        
        // Trigger filtering on search input (real-time as user types)
        this.searchInput.addEventListener('input', () => this.applyFilters());
        
        // View mode switching buttons
        this.gridViewBtn.addEventListener('click', () => this.switchViewMode('grid'));
        this.listViewBtn.addEventListener('click', () => this.switchViewMode('list'));
        
        // Reset all filters to default state
        this.resetFiltersBtn.addEventListener('click', () => this.resetFilters());
        
        // Close modal when clicking outside the modal content
        this.adoptionModal.addEventListener('click', (e) => {
            if (e.target === this.adoptionModal) {
                closeAdoptionModal();
            }
        });
        
        // Infinite scroll: load more pets as user scrolls down
        window.addEventListener('scroll', () => this.handleInfiniteScroll());
    }

    // ========================================================================
    // PET LOADING
    // ========================================================================

    /**
     * Load pets from the page (pre-rendered by PHP)
     * 
     * Extracts pet data from pet card elements in the DOM.
     * This avoids an extra API call - pets are already in the page HTML.
     * 
     * Pet card data structure stored as HTML attributes:
     * - data-pet-id: Unique pet identifier
     * - data-type: Pet type (Dog, Cat, etc.)
     * - data-size: Pet size (Small, Medium, Large)
     * - data-age: Pet age in years
     * - data-status: Pet status (available, pending, adopted)
     */
    loadPets() {
        const petCards = document.querySelectorAll('.pet-card');
        this.allPets = Array.from(petCards).map(card => ({
            id: parseInt(card.dataset.petId),
            type: card.dataset.type,
            size: card.dataset.size,
            age: parseInt(card.dataset.age),
            status: card.dataset.status
        }));
        this.filteredPets = [...this.allPets];  // Initially, all pets are shown
    }

    // ========================================================================
    // FILTERING AND SEARCH
    // ========================================================================

    /**
     * Get current filter values from the filter form
     * 
     * @returns {Object} Object containing all current filter values
     */
    getFilterValues() {
        const formData = new FormData(this.filterForm);
        const filters = {
            type: formData.getAll('type'),           // Array of selected types
            size: formData.getAll('size'),           // Array of selected sizes
            age_range: formData.get('age_range'),    // Single age range value
            status: formData.getAll('status'),       // Array of selected statuses
            search: this.searchInput.value.trim()    // Search text
        };
        return filters;
    }

    /**
     * Apply all active filters to the pet list
     * 
     * Filter logic:
     * 1. Check if pet type is in selected types
     * 2. Check if pet size is in selected sizes
     * 3. Check if pet age matches age range
     * 4. Check if pet status is in selected statuses
     * 5. Check if search term matches pet name or breed
     * 
     * All conditions must be true for a pet to be shown (AND logic)
     */
    applyFilters() {
        const filters = this.getFilterValues();
        
        // Filter pets based on criteria
        this.filteredPets = this.allPets.filter(pet => {
            // TYPE FILTER
            if (filters.type.length > 0 && !filters.type.includes(pet.type)) {
                return false;
            }
            
            // SIZE FILTER
            if (filters.size.length > 0 && !filters.size.includes(pet.size)) {
                return false;
            }
            
            // AGE RANGE FILTER
            if (filters.age_range !== 'any') {
                if (filters.age_range === 'young' && pet.age > 1) return false;
                if (filters.age_range === 'adult' && (pet.age < 2 || pet.age > 7)) return false;
                if (filters.age_range === 'senior' && pet.age < 8) return false;
            }
            
            // STATUS FILTER
            if (filters.status.length > 0 && !filters.status.includes(pet.status)) {
                return false;
            }
            
            // SEARCH FILTER (searches both name and breed)
            if (filters.search) {
                const card = document.querySelector(`.pet-card[data-pet-id="${pet.id}"]`);
                const nameElement = card.querySelector('h3');
                const breedElement = card.querySelector('.breed');
                const name = nameElement ? nameElement.textContent.toLowerCase() : '';
                const breed = breedElement ? breedElement.textContent.toLowerCase() : '';
                const searchLower = filters.search.toLowerCase();
                
                if (!name.includes(searchLower) && !breed.includes(searchLower)) {
                    return false;
                }
            }
            
            return true;
        });
        
        // Reset to first page when filters change
        this.currentPage = 1;
        this.updateDisplay();
    }

    /**
     * Update the display of pet cards based on filters and pagination
     * 
     * Shows/hides pet cards based on:
     * 1. Whether pet matches current filters
     * 2. Whether pet is on current page (pagination)
     * 
     * Also updates "showing X of Y" counter and "no results" message
     */
    updateDisplay() {
        const petIds = this.filteredPets.map(pet => pet.id);
        const allCards = document.querySelectorAll('.pet-card');
        const itemsToShow = this.itemsPerPage * this.currentPage;
        
        let visibleCount = 0;
        allCards.forEach((card, index) => {
            const petId = parseInt(card.dataset.petId);
            const isFiltered = petIds.includes(petId);
            const indexInFiltered = petIds.indexOf(petId);
            
            // Show card if it passes filter AND is on current page
            if (isFiltered && indexInFiltered < itemsToShow) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show "no results" message if no pets match filters
        this.noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        
        // Update result counter
        this.resultCount.textContent = `Showing ${visibleCount} of ${this.filteredPets.length} pets`;
    }
    
    // ========================================================================
    // INFINITE SCROLL / PAGINATION
    // ========================================================================
    
    /**
     * Handle infinite scroll - detect when user scrolls near bottom
     * 
     * Automatically loads next page when user scrolls to 500px from bottom
     * Prevents multiple simultaneous page loads with isLoadingMore flag
     */
    handleInfiniteScroll() {
        if (this.isLoadingMore) return;
        
        // Calculate scroll position
        const scrollPosition = window.innerHeight + window.scrollY;
        const pageHeight = document.documentElement.scrollHeight;
        
        // Trigger load when 500px from bottom
        if (scrollPosition >= pageHeight - 500) {
            this.loadMorePets();
        }
    }
    
    /**
     * Load more pets (next page)
     * 
     * Increments current page and updates display.
     * Includes small delay for better UX (simulates loading).
     */
    loadMorePets() {
        const filteredPetIds = this.filteredPets.map(pet => pet.id);
        const itemsToShow = this.itemsPerPage * this.currentPage;
        const nextItemsToShow = this.itemsPerPage * (this.currentPage + 1);
        
        // Check if there are more items to show
        if (nextItemsToShow > filteredPetIds.length) {
            // Already showing all items
            return;
        }
        
        this.isLoadingMore = true;
        
        // Simulate a small delay for better UX
        setTimeout(() => {
            this.currentPage++;
            this.updateDisplay();
            this.isLoadingMore = false;
        }, 300);
    }

    // ========================================================================
    // VIEW MODE SWITCHING
    // ========================================================================

    /**
     * Switch between grid and list view
     * 
     * Updates:
     * - Active button styling
     * - CSS class on pet container (affects layout)
     * - Stored view mode preference
     * 
     * @param {String} mode 'grid' or 'list'
     */
    switchViewMode(mode) {
        this.currentViewMode = mode;
        
        // Update button states
        if (mode === 'grid') {
            this.gridViewBtn.classList.add('active');
            this.listViewBtn.classList.remove('active');
            this.petsContainer.classList.remove('list-view');
        } else {
            this.gridViewBtn.classList.remove('active');
            this.listViewBtn.classList.add('active');
            this.petsContainer.classList.add('list-view');
        }
    }

    /**
     * Reset all filters to default state
     * 
     * Resets:
     * - All type checkboxes to checked
     * - Age range to 'any'
     * - All status checkboxes to checked
     * - Search input to empty
     * - Pagination to page 1
     * 
     * Then re-applies filters to show full pet list
     */
    resetFilters() {
        // Clear search input
        this.searchInput.value = '';
        
        // Check all type checkboxes by default
        document.querySelectorAll('input[name="type"]').forEach(input => {
            input.checked = true;
        });
        
        // Reset age range to 'any'
        document.querySelector('input[name="age_range"][value="any"]').checked = true;
        
        // Check all status checkboxes by default
        document.querySelectorAll('input[name="status"]').forEach(input => {
            input.checked = true;
        });
        
        // Reset to first page
        this.currentPage = 1;
        
        // Reapply filters (which will show all pets now)
        this.applyFilters();
    }
}

// ============================================================================
// GLOBAL MODAL FUNCTIONS
// ============================================================================

/**
 * Open adoption modal dialog
 * 
 * Displays the adoption request form and pre-fills pet ID.
 * Called when user clicks "Adopt Now" button on a pet card.
 * 
 * @param {Number} petId The ID of the pet to adopt
 */
function openAdoptionModal(petId) {
    const modal = document.getElementById('adoption-modal');
    const petIdInput = document.getElementById('pet-id-input');
    
    // Get pet details to show in modal (optional)
    const petCard = document.querySelector(`.pet-card[data-pet-id="${petId}"]`);
    const petName = petCard.querySelector('h3').textContent;
    
    // Set hidden pet ID field
    petIdInput.value = petId;
    
    // Show modal
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';  // Prevent background scrolling
    
    // Focus on first input for better UX
    setTimeout(() => {
        document.getElementById('adopter-name').focus();
    }, 100);
}

/**
 * Close adoption modal dialog
 * 
 * Hides the modal and resets the form to empty state.
     */
function closeAdoptionModal() {
    const modal = document.getElementById('adoption-modal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';  // Re-enable background scrolling
    document.getElementById('adoption-form').reset();  // Clear form fields
}

/**
 * Submit adoption request form
 * 
 * Gathers form data, sends to API, and handles response.
 * On success: shows confirmation and reloads page.
 * On error: shows error message.
 * 
 * @param {Event} event Form submit event
 */
function submitAdoption(event) {
    event.preventDefault();
    
    // Gather form data
    const petId = document.getElementById('pet-id-input').value;
    const name = document.getElementById('adopter-name').value;
    const email = document.getElementById('adopter-email').value;
    const phone = document.getElementById('adopter-phone').value;
    const message = document.getElementById('adopter-message').value;
    
    // Send adoption request to server
    fetch('api/submit_adoption.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `pet_id=${encodeURIComponent(petId)}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        closeAdoptionModal();
        showSuccessMessage(data.message || 'Adoption request submitted successfully!');
        
        // Reload page after 2 seconds to show updated pet status
        setTimeout(() => location.reload(), 2000);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

/**
 * Show success message to user
 * 
 * Displays a temporary success/confirmation message at top of page.
 * Auto-hides after 4 seconds.
 * 
 * @param {String} message The message to display
 */
function showSuccessMessage(message) {
    const successMsg = document.getElementById('success-message');
    const successText = document.getElementById('success-text');
    
    successText.textContent = message;
    successMsg.style.display = 'flex';
    
    // Auto-hide after 4 seconds
    setTimeout(() => {
        successMsg.style.display = 'none';
    }, 4000);
}

// ============================================================================
// KEYBOARD SHORTCUTS
// ============================================================================

/**
 * Close modal when user presses Escape key
 */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAdoptionModal();
    }
});

// ============================================================================
// APPLICATION STARTUP
// ============================================================================

/**
 * Initialize the app when DOM is fully loaded
 */
document.addEventListener('DOMContentLoaded', () => {
    new PetAdoptionApp();
});

/**
 * Global functions for modal operations
 */

function openAdoptionModal(petId) {
    const modal = document.getElementById('adoption-modal');
    const petIdInput = document.getElementById('pet-id-input');
    
    // Get pet details to show in modal (optional)
    const petCard = document.querySelector(`.pet-card[data-pet-id="${petId}"]`);
    const petName = petCard.querySelector('h3').textContent;
    
    petIdInput.value = petId;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Focus on first input
    setTimeout(() => {
        document.getElementById('adopter-name').focus();
    }, 100);
}

function closeAdoptionModal() {
    const modal = document.getElementById('adoption-modal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('adoption-form').reset();
}

function submitAdoption(event) {
    event.preventDefault();
    
    const petId = document.getElementById('pet-id-input').value;
    const name = document.getElementById('adopter-name').value;
    const email = document.getElementById('adopter-email').value;
    const phone = document.getElementById('adopter-phone').value;
    const message = document.getElementById('adopter-message').value;
    
    // Send adoption request to server
    fetch('api/submit_adoption.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `pet_id=${encodeURIComponent(petId)}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}&message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        closeAdoptionModal();
        showSuccessMessage(data.message || 'Adoption request submitted successfully!');
        
        // Reload pets to update status
        setTimeout(() => location.reload(), 2000);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function showSuccessMessage(message) {
    const successMsg = document.getElementById('success-message');
    const successText = document.getElementById('success-text');
    
    successText.textContent = message;
    successMsg.style.display = 'flex';
    
    setTimeout(() => {
        successMsg.style.display = 'none';
    }, 4000);
}

/**
 * Close modal when clicking outside
 */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAdoptionModal();
    }
});

/**
 * Initialize the app when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', () => {
    new PetAdoptionApp();
});
