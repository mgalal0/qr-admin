# QR Code Generator API

A powerful API service for generating various types of QR codes including vCards, links, and PDF-linked QR codes.

## Features

- Generate vCard QR codes with contact information
- Create QR codes for URLs
- Generate QR codes linked to uploaded PDF files
- Secure verification code system with admin controls

## API Endpoints

### 1. Generate vCard QR Code

```http
POST /generate-qrcode.php?type=vcard
```

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "phone_number": "1234567890",
  "job_title": "Developer",
  "email": "john.doe@example.com",
  "social_media": "https://twitter.com/johndoe\nhttps://linkedin.com/in/johndoe"
}
```

**Response:**
```json
{
  "filename": "1626179864_60ee68b0.png"
}
```

### 2. Generate URL QR Code

```http
POST /generate-qrcode.php?type=link
```

**Request Body:**
```json
{
  "url": "http://example.com"
}
```

**Response:**
```json
{
  "filename": "1626179867_60ee68db.png"
}
```

### 3. Generate PDF-linked QR Code

```http
POST /generate-qrcode.php?type=pdf
```

**Request Body (Form Data):**
- `pdf`: File (required)
- `code`: String (required)

**Response:**
```json
{
  "filename": "1626179869_60ee68f7.png"
}
```

### 4. Update Verification Code

```http
POST /generate-qrcode.php?type=update-code
```

**Request Body:**
```json
{
  "code": "new_verification_code",
  "secret_key": "your_admin_secret_key"
}
```

**Response:**
```json
{
  "message": "Verification code updated successfully",
  "code": "new_verification_code"
}
```

## Implementation Examples

### Frontend Examples

#### JavaScript (Fetch API)
```javascript
fetch('/generate-qrcode.php?type=vcard', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        "first_name": "John",
        "last_name": "Doe",
        "phone_number": "1234567890",
        "job_title": "Developer",
        "email": "john.doe@example.com",
        "social_media": "https://twitter.com/johndoe\nhttps://linkedin.com/in/johndoe"
    })
})
.then(response => response.json())
.then(data => console.log('Response:', data))
.catch(error => console.error('Error:', error));
```

#### React (Axios)
```javascript
import axios from 'axios';

axios.post('/generate-qrcode.php?type=vcard', {
    "first_name": "John",
    "last_name": "Doe",
    "phone_number": "1234567890",
    "job_title": "Developer",
    "email": "john.doe@example.com",
    "social_media": ["https://twitter.com/johndoe", "https://linkedin.com/in/johndoe"]
})
.then(response => console.log('Response:', response.data))
.catch(error => console.error('Error:', error));
```

### Backend Examples

#### Python (Requests)
```python
import requests

url = 'http://localhost/generate-qrcode.php?type=vcard'
payload = {
    "first_name": "John",
    "last_name": "Doe",
    "phone_number": "1234567890",
    "job_title": "Developer",
    "email": "john.doe@example.com",
    "social_media": ["https://twitter.com/johndoe", "https://linkedin.com/in/johndoe"]
}
headers = {
    'Content-Type': 'application/json'
}

response = requests.post(url, json=payload, headers=headers)
print('Response:', response.json())
```

## Security

- Admin secret key required for verification code updates
- Contact Avnology for admin secret key access
- Keep the admin secret key confidential and accessible only to authorized personnel

## Support

For additional support or to obtain the admin secret key, please contact Avnology.

## License

© 2024 Mahmoud Galal. All rights reserved.
