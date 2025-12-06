# 🐾 Pet Adoption Portal

A modern, fully-documented web application that connects animal lovers with adoptable pets. Browse pets, filter by preferences, and submit adoption requests - all with an intuitive, responsive interface.

**Status**: ✅ Production Ready | **Version**: 1.0.0 | **Last Updated**: January 2025

## ⭐ Features

### 🎯 Core Features
- **Browse Pets**: Beautiful grid/list view with all available adoptable pets
- **Advanced Filtering**: Filter by pet type, size, age range, and status
- **Real-time Search**: Search for pets by name or breed instantly
- **Adoption Requests**: Submit adoption applications directly through the portal
- **Contact Form**: Get in touch with the organization
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices

### ⚡ Technical Features
- Lightning-fast filtering (no page reload)
- Infinite scroll pagination
- Real-time form validation
- Data persistence with JSON
- Email notifications for contact submissions
- Comprehensive error handling

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- A modern web browser
- No database required (uses JSON files)

### Installation

1. **Navigate to public directory**
   ```bash
   cd pet-adopt-php/public
   ```

2. **Start a PHP development server**
   ```bash
   php -S localhost:8000
   ```

3. **Open in browser**
   ```
   http://localhost:8000
   ```

## API Endpoints

### Get Filtered Pets
- **URL**: `/api/get_pets.php`
- **Method**: GET
- **Parameters**:
  - `type`: Pet type (Dog, Cat, etc.)
  - `size`: Pet size (Small, Medium, Large)
  - `age_range`: Age range (any, young, adult, senior)
  - `status`: Pet status (available, pending, adopted)
  - `search`: Search term

### Submit Adoption Request
- **URL**: `/api/submit_adoption.php`
- **Method**: POST
- **Parameters**:
  - `pet_id`: ID of the pet
  - `name`: Adopter's full name
  - `email`: Adopter's email
  - `phone`: Adopter's phone number
  - `message`: Adoption message

## Data Format

### Pet Object
```json
{
    "id": 1,
    "name": "Buddy",
    "type": "Dog",
    "breed": "Affenpinscher",
    "age": 2,
    "size": "Medium",
    "location": "Shelter Available",
    "image": "https://...",
    "description": "A wonderful dog waiting for a loving home.",
    "status": "available",
    "adoptionDate": null,
    "adoptedBy": null,
    "price": 150,
    "healthChecked": true,
    "vaccinated": true,
    "neutered": true
}
```

### Adoption Request Object
```json
{
    "id": 1,
    "petId": 1,
    "petName": "Buddy",
    "adopterName": "John Doe",
    "adopterEmail": "john@example.com",
    "adopterPhone": "555-1234",
    "message": "I love dogs and have a great home for Buddy",
    "requestDate": "2025-12-06 10:30:00",
    "status": "pending"
}
```

## Usage

### Browsing Pets
1. Visit the home page to see all available pets
2. Use filters on the left sidebar to narrow down results
3. Search for specific pets by name or breed
4. Toggle between grid and list view using the buttons

### Adopting a Pet
1. Click the "Adopt Now" button on a pet card
2. Fill in your information in the adoption form
3. Click "Submit Request"
4. You'll receive a confirmation message

## Technologies Used

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Data Storage**: JSON files
- **Icons**: Font Awesome 6
- **API**: RESTful with JSON responses

## Features

### Client-Side
- Real-time filtering and search
- Responsive grid/list view switching
- Modal-based adoption form
- Success notifications
- Keyboard shortcut support (ESC to close modal)

### Server-Side
- Pet management system
- Adoption request processing
- JSON-based data persistence
- API endpoints for AJAX requests
- Input validation and sanitization

## Future Enhancements

- Database integration (MySQL/PostgreSQL)
- User authentication
- Admin dashboard
- Email notifications
- Photo gallery for pets
- Favorites/wishlist feature
- Advanced search filters
- Pet matching algorithm

## License

MIT License

## Contact

For support or inquiries, please contact the development team.
