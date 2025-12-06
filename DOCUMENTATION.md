# Pet Adoption Portal - Complete Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Architecture](#architecture)
3. [Directory Structure](#directory-structure)
4. [Configuration](#configuration)
5. [Core Classes](#core-classes)
6. [API Endpoints](#api-endpoints)
7. [Pages & Features](#pages--features)
8. [JavaScript Application](#javascript-application)
9. [Data Flow](#data-flow)
10. [Development Guide](#development-guide)

---

## Project Overview

**Pet Adoption Portal** is a full-stack web application that connects animal lovers with adoptable pets. It provides an intuitive platform for browsing available pets, filtering by preferences, and submitting adoption requests.

### Key Features
- Browse and search adoptable pets
- Advanced filtering (type, size, age, status)
- Adoption request submission
- Contact form for inquiries
- Responsive design for all devices
- JSON-based data persistence
- Real-time filtering without page refresh

### Technology Stack
- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Data Storage**: JSON files
- **Architecture**: MVC pattern with DAO (Data Access Object) design

---

## Architecture

### Design Patterns Used

1. **DAO (Data Access Object) Pattern**
   - `PetManager` class abstracts all pet data operations
   - `AdoptionManager` class handles adoption records
   - Easy to switch from JSON to database in future

2. **MVC-like Structure**
   - **Models**: PetManager, AdoptionManager (data layer)
   - **Views**: PHP templates (index.php, category.php, etc.)
   - **Controllers**: API endpoints (get_pets.php, submit_adoption.php)

3. **Separation of Concerns**
   - PHP handles server-side logic and data persistence
   - JavaScript handles client-side interactivity
   - CSS manages styling and layout

### Application Flow

```
User Browser
    ↓
[HTML Pages] (category.php, index.php, contact.php)
    ↓
[JavaScript] (app.js, contact.js) - Client-side filtering, forms
    ↓
[API Endpoints] (get_pets.php, submit_adoption.php, submit_contact.php)
    ↓
[Core Classes] (PetManager, AdoptionManager) - Business logic
    ↓
[JSON Files] (pets.json, adoptions.json, contacts.json) - Data persistence
```

---

## Directory Structure

```
pet-adopt-php/
│
├── public/                          # Web-accessible files
│   ├── index.php                   # Home page
│   ├── category.php                # Pet listing & browsing
│   ├── about.php                   # About organization
│   ├── contact.php                 # Contact page
│   ├── api/                        # REST API endpoints
│   │   ├── get_pets.php           # Fetch filtered pets
│   │   ├── submit_adoption.php    # Process adoption requests
│   │   └── submit_contact.php     # Process contact forms
│   ├── css/                        # Stylesheets
│   │   ├── style.css              # Main styles
│   │   └── contact.css            # Contact page styles
│   ├── js/                         # JavaScript files
│   │   ├── app.js                 # Main application logic
│   │   └── contact.js             # Contact form handling
│   └── images/                     # Pet images & assets
│
├── src/                            # Backend PHP classes
│   ├── config.php                 # Configuration constants
│   ├── PetManager.php             # Pet data operations
│   └── AdoptionManager.php        # Adoption request handling
│
├── data/                           # JSON data files
│   ├── pets.json                  # All pet records
│   ├── adoptions.json             # Adoption requests
│   └── contacts.json              # Contact form submissions
│
└── README.md                       # Project documentation
```

---

## Configuration

### config.php
Central configuration file with all constants:

```php
// Directory Paths
BASE_PATH       // Root project directory
SRC_PATH        // Source code directory
PUBLIC_PATH     // Web-accessible directory
DATA_PATH       // Data files directory

// Data Files
PETS_DATA_FILE      // Path to pets.json
ADOPTIONS_DATA_FILE // Path to adoptions.json

// Environment
ENV             // 'development' or 'production'
DEBUG           // Boolean flag for debug mode

// Site Settings
SITE_NAME       // 'Pet Adoption Portal'
SITE_URL        // Base URL of the site
```

### Environment Variables
```bash
# Optional environment variable to override default
APP_ENV=production  # or 'development'
```

---

## Core Classes

### 1. PetManager Class

**File**: `src/PetManager.php`

**Purpose**: Manages all pet-related data operations

#### Public Methods:

| Method | Parameters | Returns | Description |
|--------|-----------|---------|-------------|
| `getAllPets()` | none | Array | Get all pets in system |
| `getPetById($id)` | int $id | Array/null | Get specific pet by ID |
| `getUniquePetTypes()` | none | Array | Get all unique pet types |
| `getUniqueSizes()` | none | Array | Get all unique pet sizes |
| `filterPets($filters)` | array $filters | Array | Get filtered pet list |
| `getPetsByStatus($status)` | string $status | Array | Get pets by status |
| `updatePetStatus($petId, $status)` | int, string | Boolean | Update pet status |
| `addPet($petData)` | array $petData | Boolean | Add new pet record |

#### Filter Parameters (for `filterPets()`):

```php
$filters = [
    'type' => ['Dog', 'Cat'],           // Array of types
    'size' => ['Small', 'Medium'],      // Array of sizes
    'age_range' => 'young',             // 'young', 'adult', 'senior', 'any'
    'status' => ['available'],          // Array of statuses
    'search' => 'Buddy'                 // Search string
];
```

### 2. AdoptionManager Class

**File**: `src/AdoptionManager.php`

**Purpose**: Handles adoption requests and records

#### Public Methods:

| Method | Parameters | Returns | Description |
|--------|-----------|---------|-------------|
| `getAllAdoptions()` | none | Array | Get all adoption records |
| `recordAdoption($petId, $name, $email, $phone, $msg)` | mixed | Array | Create new adoption request |
| `getPetAdoptions($petId)` | int $petId | Array | Get adoptions for pet |

#### Adoption Record Structure:

```php
[
    'id' => 1,
    'petId' => 5,
    'petName' => 'Max',
    'adopterName' => 'John Doe',
    'adopterEmail' => 'john@example.com',
    'adopterPhone' => '+1234567890',
    'message' => 'Why they want to adopt...',
    'requestDate' => '2025-01-15 10:30:00',
    'status' => 'pending'
]
```

---

## API Endpoints

### 1. GET /api/get_pets.php

**Purpose**: Fetch filtered list of pets

**Query Parameters**:
```
?type[]=Dog&type[]=Cat          // Filter by pet types
&size[]=Small&size[]=Medium     // Filter by sizes
&age_range=adult                // Filter by age range
&status[]=available             // Filter by status
&search=fluffy                  // Search by name/breed
```

**Response**:
```json
{
    "success": true,
    "count": 15,
    "pets": [
        {
            "id": 1,
            "name": "Max",
            "type": "Dog",
            "breed": "Golden Retriever",
            "age": 3,
            "size": "Large",
            "image": "path/to/image.jpg",
            "status": "available"
        }
    ]
}
```

### 2. POST /api/submit_adoption.php

**Purpose**: Submit adoption request for a pet

**POST Parameters**:
```
pet_id    : Integer (required) - Pet ID to adopt
name      : String (required) - Adopter name
email     : String (required) - Adopter email
phone     : String (required) - Adopter phone
message   : String (optional) - Reason for adoption
```

**Response Success**:
```json
{
    "success": true,
    "message": "Adoption request submitted successfully",
    "adoptionId": 42
}
```

**Response Error**:
```json
{
    "success": false,
    "message": "Pet not found" or "Pet is not available for adoption"
}
```

### 3. POST /api/submit_contact.php

**Purpose**: Submit contact form inquiry

**POST Parameters**:
```
full_name  : String (required) - Name of inquirer
email      : String (required) - Email address
phone      : String (optional) - Phone number
message    : String (required) - Message content
```

**Response Success**:
```json
{
    "success": true,
    "message": "Your message has been successfully sent!"
}
```

**Response Error**:
```json
{
    "success": false,
    "message": "Please provide a valid name, email, and message."
}
```

---

## Pages & Features

### 1. Home Page (index.php)

**Purpose**: Landing page and first impression

**Key Sections**:
- Hero section with main call-to-action
- Statistics dashboard (total pets, available, adopted)
- Featured pets carousel (randomized)
- "Why Adopt?" informational section
- Footer with links

**Features**:
- Dynamic statistics from pet database
- Responsive design
- Beautiful hero with gradient background
- SEO-friendly structure

### 2. Browse/Category Page (category.php)

**Purpose**: Main pet discovery and browsing interface

**Key Sections**:
- Filter sidebar (type, size, age, status)
- Search input
- Pet grid/list view
- View mode switcher (grid ↔ list)
- Pagination/infinite scroll
- No results message

**Features**:
- Real-time filtering (no page reload)
- Dynamic search functionality
- Grid and list layout options
- Adoption request modal
- Infinite scroll pagination
- Success notifications

### 3. About Page (about.php)

**Purpose**: Organization information and trust-building

**Key Sections**:
- Mission statement
- Core values
- Organization story
- Impact statistics
- Team member profiles
- Ways to get involved

**Features**:
- Professional design
- Responsive layout
- Team profiles with roles
- Call-to-action buttons

### 4. Contact Page (contact.php)

**Purpose**: Contact and communication hub

**Key Sections**:
- Contact information (address, phone, email)
- Contact form
- Volunteer opportunity section

**Features**:
- AJAX form submission
- Client-side validation
- Server-side validation
- Email notifications
- Response logging

---

## JavaScript Application

### File: public/js/app.js

**Class**: `PetAdoptionApp`

#### Key Methods:

| Method | Purpose |
|--------|---------|
| `init()` | Initialize the application |
| `cacheElements()` | Cache DOM element references |
| `attachEventListeners()` | Bind event handlers |
| `loadPets()` | Load pet data from page |
| `getFilterValues()` | Extract current filter values |
| `applyFilters()` | Apply filters to pet list |
| `updateDisplay()` | Render filtered results |
| `switchViewMode(mode)` | Switch between grid/list |
| `resetFilters()` | Reset all filters to defaults |
| `handleInfiniteScroll()` | Detect scroll to bottom |
| `loadMorePets()` | Load next page |

#### Global Functions:

| Function | Purpose |
|----------|---------|
| `openAdoptionModal(petId)` | Open adoption form modal |
| `closeAdoptionModal()` | Close adoption modal |
| `submitAdoption(event)` | Submit adoption request |
| `showSuccessMessage(msg)` | Display success notification |

### File: public/js/contact.js

**Purpose**: Handle contact form submission

#### Features:
- Real-time validation
- Email format checking
- AJAX submission
- Loading state feedback
- Success/error messaging
- Form reset on success

---

## Data Flow

### Pet Browsing Flow

```
1. User visits category.php
   ↓
2. PHP loads all pets and renders pet cards
   ↓
3. JavaScript app initializes (app.js)
   ↓
4. User interacts with filters/search
   ↓
5. JavaScript applies filters to pet list in memory
   ↓
6. DOM updates to show/hide pet cards
   ↓
7. User clicks "Adopt Now"
   ↓
8. Adoption modal opens
   ↓
9. User fills and submits adoption form
   ↓
10. JavaScript sends POST to /api/submit_adoption.php
   ↓
11. PHP validates and records adoption
   ↓
12. Pet status updated to 'pending'
   ↓
13. Success message shown, page reloads
```

### Contact Form Flow

```
1. User visits contact.php
   ↓
2. User fills contact form
   ↓
3. User clicks submit
   ↓
4. JavaScript validates (contact.js)
   ↓
5. JavaScript sends POST to /api/submit_contact.php
   ↓
6. PHP validates input
   ↓
7. Contact logged to contacts.json
   ↓
8. Email sent to admin (if configured)
   ↓
9. Response sent to browser
   ↓
10. Success/error message shown
```

---

## Data Structures

### Pet Record (pets.json)

```json
{
    "id": 1,
    "name": "Max",
    "type": "Dog",
    "breed": "Golden Retriever",
    "age": 3,
    "size": "Large",
    "image": "/images/max.jpg",
    "description": "Friendly and energetic dog",
    "status": "available",
    "adoptedBy": null,
    "adoptionDate": null
}
```

### Adoption Record (adoptions.json)

```json
{
    "id": 1,
    "petId": 5,
    "petName": "Bella",
    "adopterName": "Jane Smith",
    "adopterEmail": "jane@example.com",
    "adopterPhone": "+1234567890",
    "message": "We have a backyard and love dogs",
    "requestDate": "2025-01-15 14:30:00",
    "status": "pending"
}
```

### Contact Record (contacts.json)

```json
{
    "id": 1736933400,
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "message": "I would like more information about volunteering",
    "submitted_at": "2025-01-15 10:30:00",
    "ip_address": "192.168.1.100"
}
```

---

## Development Guide

### Setting Up Development Environment

1. **Clone/Download Project**
   ```bash
   cd pet-adopt-php
   ```

2. **Start PHP Development Server**
   ```bash
   cd public
   php -S localhost:8000
   ```

3. **Access in Browser**
   ```
   http://localhost:8000
   ```

### File Permissions

Ensure data directory is writable:
```bash
chmod 755 data/
chmod 644 data/*.json
```

### Common Development Tasks

#### Add a New Pet
```php
$petManager = new PetManager(DATA_PATH);
$petManager->addPet([
    'name' => 'Buddy',
    'type' => 'Dog',
    'breed' => 'Labrador',
    'age' => 2,
    'size' => 'Large',
    'image' => '/images/buddy.jpg',
    'status' => 'available'
]);
```

#### Update Pet Status
```php
$petManager->updatePetStatus($petId, 'adopted', 'Adopter Name');
```

#### Get Filtered Pets
```php
$filters = ['type' => ['Dog'], 'size' => ['Small', 'Medium']];
$pets = $petManager->filterPets($filters);
```

#### Record Adoption
```php
$adoptionManager = new AdoptionManager(DATA_PATH, $petManager);
$result = $adoptionManager->recordAdoption(
    $petId,
    $name,
    $email,
    $phone,
    $message
);
```

### Testing the Application

1. **Test Pet Filtering**
   - Visit category.php
   - Try different filter combinations
   - Verify results update correctly

2. **Test Adoption Flow**
   - Click "Adopt Now" on a pet
   - Fill adoption form
   - Submit and verify success message

3. **Test Contact Form**
   - Visit contact.php
   - Submit contact form
   - Verify message logged in contacts.json

4. **Test Search**
   - Use search box on category page
   - Search by pet name or breed
   - Verify results update correctly

### Performance Considerations

1. **JSON File Optimization**
   - Keep pets.json files under 10MB for optimal performance
   - Consider database migration for large datasets

2. **Pagination**
   - Currently loads 12 pets per page
   - Adjust `itemsPerPage` in app.js if needed

3. **Caching**
   - Consider implementing Redis for frequently accessed data
   - Add browser caching headers for static assets

### Security Best Practices

1. **Input Validation**
   - All user inputs are sanitized with PHP filters
   - Email validation for contact forms
   - Integer casting for numeric IDs

2. **File Uploads**
   - Currently no file upload functionality
   - If adding, implement proper validation and sanitization

3. **SQL Injection Prevention**
   - Not applicable (JSON-based, no SQL)
   - Use parameterized queries if migrating to database

4. **CSRF Protection**
   - Consider adding CSRF tokens for form submissions
   - Implement origin validation

### Debugging

**Enable Debug Mode in config.php**:
```php
define('DEBUG', true);  // Show all errors
```

**Check Error Logs**:
```bash
tail -f error.log
```

**Browser Console**:
- Press F12 to open Developer Tools
- Check Console tab for JavaScript errors
- Use Network tab to inspect API calls

---

## Deployment Guide

### Production Configuration

1. **Update config.php**
   ```php
   define('ENV', 'production');
   define('DEBUG', false);
   define('SITE_URL', 'https://petadoption.com');
   ```

2. **Configure Email**
   ```php
   $to = 'admin@petadoption.com';  // Your email
   ```

3. **Set Secure Permissions**
   ```bash
   chmod 755 public/
   chmod 750 data/
   chmod 640 data/*.json
   ```

4. **Enable HTTPS**
   - Use SSL certificate
   - Redirect HTTP to HTTPS

5. **Database Migration** (Optional)
   - Consider moving from JSON to MySQL
   - Implement database connection layer

### Server Requirements

- PHP 7.4 or higher
- File write permissions for data/ directory
- No external dependencies (uses PHP standard library only)

---

## Future Enhancements

1. **Database Integration**
   - Replace JSON with MySQL/PostgreSQL
   - Add database migration utilities

2. **User Accounts**
   - User registration and login
   - Adoption history tracking
   - Wishlist functionality

3. **Admin Dashboard**
   - Add/edit/delete pets
   - View adoption requests
   - Manage contact submissions

4. **Image Management**
   - File upload for pet photos
   - Image optimization
   - Gallery for multiple images per pet

5. **Advanced Features**
   - Email notifications for adoption status
   - User reviews and ratings
   - Adoption statistics and reports
   - Social media integration

6. **Mobile App**
   - Native iOS/Android applications
   - Push notifications

---

## Support & Contributing

For issues, questions, or contributions:
1. Check existing documentation
2. Review code comments
3. Test changes thoroughly
4. Follow existing code style

---

**Last Updated**: January 2025
**Version**: 1.0.0
**License**: Open Source
