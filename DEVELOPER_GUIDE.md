# Pet Adoption Portal - Developer Quick Reference Guide

## Quick Start for New Developers

### Running the Application

```bash
cd pet-adopt-php/public
php -S localhost:8000
# Access at http://localhost:8000
```

---

## Code Architecture at a Glance

### The 3 Main Layers

```
┌─────────────────────────────────────┐
│  PRESENTATION (Views)               │
│  - index.php, category.php, etc.   │
│  - HTML + minimal PHP loops         │
└────────────────┬────────────────────┘
                 ↓
┌─────────────────────────────────────┐
│  API LAYER (Controllers)            │
│  - /api/get_pets.php                │
│  - /api/submit_adoption.php         │
│  - /api/submit_contact.php          │
│  - JSON responses only              │
└────────────────┬────────────────────┘
                 ↓
┌─────────────────────────────────────┐
│  BUSINESS LOGIC (Models/Classes)   │
│  - PetManager (CRUD operations)     │
│  - AdoptionManager (adoption logic) │
│  - Filter & search functionality    │
└────────────────┬────────────────────┘
                 ↓
┌─────────────────────────────────────┐
│  DATA PERSISTENCE                   │
│  - pets.json                        │
│  - adoptions.json                   │
│  - contacts.json                    │
└─────────────────────────────────────┘
```

---

## Most Important Files to Know

### Backend

| File | Purpose | Key Responsibility |
|------|---------|-------------------|
| `src/config.php` | Configuration hub | All constants & settings |
| `src/PetManager.php` | Pet operations | Filter, search, update pets |
| `src/AdoptionManager.php` | Adoption logic | Record & validate adoptions |
| `public/api/get_pets.php` | Pet listing API | Return filtered pets as JSON |
| `public/api/submit_adoption.php` | Adoption API | Validate & record adoptions |
| `public/api/submit_contact.php` | Contact API | Log & email contact forms |

### Frontend

| File | Purpose | Key Responsibility |
|------|---------|-------------------|
| `public/category.php` | Browse page | Main pet browsing UI |
| `public/js/app.js` | Main app logic | Filtering, search, modals |
| `public/js/contact.js` | Contact form | Form validation & submission |

### Data

| File | Purpose | Structure |
|------|---------|-----------|
| `data/pets.json` | Pet records | `[{id, name, type, size, ...}]` |
| `data/adoptions.json` | Adoption requests | `[{id, petId, adopterName, ...}]` |
| `data/contacts.json` | Contact submissions | `[{id, name, email, message, ...}]` |

---

## Common Development Scenarios

### Scenario 1: Add New Filter Option

**Steps**:
1. Add filter logic to `PetManager::filterPets()`
2. Update category.php to show new filter UI
3. Update app.js to handle new filter in `applyFilters()`

**Example**: Add "color" filter
```php
// In PetManager::filterPets()
if (!empty($filters['color'])) {
    $result = array_filter($result, function($pet) use ($filters) {
        return in_array($pet['color'], $filters['color']);
    });
}
```

### Scenario 2: Modify Pet Display

**Steps**:
1. Edit pet card HTML in category.php (lines 100-150)
2. Adjust CSS in public/css/style.css
3. Test responsiveness on mobile

### Scenario 3: Add Pet Field

**Steps**:
1. Add field to pets.json records
2. Display field in pet cards (category.php)
3. Update PetManager filters if searchable
4. Update API response (already automatic)

### Scenario 4: Debug Adoption Request

**Check**:
1. Browser console (F12) for JavaScript errors
2. Network tab to see API response
3. adoptions.json file to verify record created
4. PHP error log for server errors

---

## Quick Code References

### Load All Pets
```php
$petManager = new PetManager(DATA_PATH);
$pets = $petManager->getAllPets();  // Returns array of pets
```

### Filter Pets
```php
$filtered = $petManager->filterPets([
    'type' => ['Dog'],
    'size' => ['Small', 'Medium'],
    'age_range' => 'young'
]);
```

### Record Adoption
```php
$adoptionManager = new AdoptionManager(DATA_PATH, $petManager);
$result = $adoptionManager->recordAdoption(
    $petId, $name, $email, $phone, $message
);
if ($result['success']) {
    echo "Adoption recorded: " . $result['adoptionId'];
}
```

### Submit to API via JavaScript
```javascript
fetch('api/get_pets.php?type[]=Dog&size[]=Small')
    .then(r => r.json())
    .then(data => console.log(data.pets))
    .catch(e => console.error(e));
```

---

## File Modification Checklist

When modifying code, check:

- [ ] Add descriptive comments for new functions
- [ ] Use existing naming conventions
- [ ] Test in browser after changes
- [ ] Check console for JavaScript errors
- [ ] Verify data files are still valid JSON
- [ ] Test on mobile (responsive)
- [ ] Document any new API parameters

---

## Debugging Checklist

**Page not loading?**
- [ ] Check PHP syntax: `php -l filename.php`
- [ ] Check file permissions: `ls -la`
- [ ] Check browser console (F12)
- [ ] Check server errors: `error_log`

**Filters not working?**
- [ ] Check filter form in category.php (correct names?)
- [ ] Check app.js applyFilters() logic
- [ ] Inspect element to verify data attributes
- [ ] Check console for JavaScript errors

**Adoption not submitting?**
- [ ] Check validation in contact form
- [ ] Check API response (Network tab)
- [ ] Check adoptions.json permissions
- [ ] Verify pet status is 'available'

**Contact form not working?**
- [ ] Check email configuration in submit_contact.php
- [ ] Check contacts.json permissions
- [ ] Verify form field names match (full_name, etc.)
- [ ] Check for JavaScript errors

---

## Data Validation Rules

### Pet Record
- `id`: Integer, unique
- `name`: String, required
- `type`: String from ['Dog', 'Cat', 'Rabbit', ...]
- `breed`: String
- `age`: Integer (years)
- `size`: String from ['Small', 'Medium', 'Large']
- `status`: String from ['available', 'pending', 'adopted']

### Adoption Request
- `petId`: Must reference existing pet
- `adopterName`: String, non-empty
- `adopterEmail`: Valid email format
- `adopterPhone`: String format
- `status`: 'pending', 'approved', or 'rejected'

### Contact Submission
- `full_name`: String, required
- `email`: Valid email format, required
- `message`: String, required
- `phone`: String, optional

---

## Key Concepts Explained

### Why JSON for Data Storage?

- **Simple**: Easy to read and debug
- **No dependencies**: No database setup needed
- **Fast prototyping**: Quick to get started
- **Limitations**: Doesn't scale beyond ~1000 records
- **Future**: Ready to migrate to MySQL when needed

### Why the DAO Pattern?

```
Benefits:
✓ Easy to test (mock PetManager)
✓ Easy to switch storage (JSON → MySQL)
✓ Business logic separate from storage
✓ Reusable across different pages
```

### Client-Side vs Server-Side Filtering

```
Client-Side (JavaScript):
- Faster (instant results)
- Works with ~100 pets max
- Used for real-time filtering

Server-Side (PHP):
- Needed for large datasets
- More secure validation
- Used for API calls
```

---

## Common Mistakes to Avoid

❌ **Don't**:
- Modify JSON files directly in code (use provided methods)
- Mix filtering logic in templates
- Make API calls on every keystroke
- Trust user input without validation
- Forget to save files after edit

✅ **Do**:
- Use PetManager for all pet operations
- Keep business logic in classes
- Use event debouncing for searches
- Validate on both client and server
- Test changes before committing

---

## Performance Tips

1. **Reduce API Calls**
   - Cache results in JavaScript
   - Use pagination (12 items at a time)

2. **Optimize Images**
   - Use WebP format when possible
   - Compress PNG/JPG files
   - Lazy load images below fold

3. **CSS/JS Optimization**
   - Minify in production
   - Combine multiple CSS files
   - Load scripts after page content

4. **JSON File Size**
   - Keep pets.json under 500KB
   - Archive old adoptions
   - Split large datasets

---

## Browser Compatibility

Tested on:
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

**Features used**:
- Fetch API (not IE compatible)
- ES6 classes
- Array destructuring
- Modern CSS Grid

---

## Resources & Documentation

- **PHP Docs**: https://www.php.net/manual/
- **JavaScript MDN**: https://developer.mozilla.org/en-US/docs/Web/JavaScript/
- **HTML/CSS**: https://developer.mozilla.org/en-US/docs/Learn
- **JSON**: https://www.json.org/

---

## Support Contacts

**Questions?**
1. Check DOCUMENTATION.md for detailed info
2. Review inline code comments
3. Check browser console (F12)
4. Review error logs

---

## Version Information

- **Current Version**: 1.0.0
- **PHP Required**: 7.4+
- **Last Updated**: January 2025
- **Status**: Production Ready

---

**Happy coding! 🐾**
