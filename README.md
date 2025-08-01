# CareCompass Hospital Management System

A comprehensive hospital management system built with PHP and MySQL, designed to streamline healthcare operations including patient care, staff management, appointments, and administrative functions.

![CareCompass Hospital](assets/logo.jpeg)

## 🏥 Project Overview

CareCompass Hospital is a full-featured hospital management system that provides a complete solution for healthcare facilities. The system supports multiple user roles, appointment scheduling, doctor management, inventory tracking, and comprehensive reporting.

## ✨ Key Features

### 🔐 Multi-Role User Management
- **Patients**: Registration, appointment booking, profile management
- **Staff**: Task management, supply requests, patient check-ins
- **Administrators**: Complete system management and analytics

### 📅 Appointment Management
- Real-time appointment booking with conflict detection
- Doctor availability tracking
- Automatic PDF receipt generation
- Payment processing integration
- Appointment status tracking

### 👨‍⚕️ Doctor Management
- Complete doctor profiles with images
- Specialization and branch management
- Availability scheduling
- Search and filter functionality

### 🚨 Emergency Services
- 24/7 Emergency Department management
- Triage system implementation
- Trauma and cardiac care tracking
- Emergency case management

### 📦 Inventory & Supply Management
- Medical supplies tracking
- Staff supply request system
- Admin approval workflow
- Real-time inventory updates

### 💳 Payment Processing
- Multiple payment methods support
- Automatic receipt generation
- Payment history tracking
- Transaction status management

### 📊 Analytics & Reporting
- Patient volume analytics
- Staff efficiency metrics
- Bed availability tracking
- Admission rate monitoring

## 🛠️ Technology Stack

- **Backend**: PHP 8.3.14
- **Database**: MySQL 9.1.0
- **Frontend**: HTML5, CSS3, JavaScript
- **PDF Generation**: FPDF Library
- **Server**: XAMPP Environment
- **Security**: bcrypt password hashing, session management

## 📋 Prerequisites

Before running this project, ensure you have:

- **XAMPP** (or similar local server stack)
- **PHP** 8.0 or higher
- **MySQL** 5.7 or higher
- **Web Browser** (Chrome, Firefox, Safari, Edge)

## 🚀 Installation Guide

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/carecompass-hospital.git
cd carecompass-hospital
```

### Step 2: Set Up XAMPP
1. Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services
3. Place the project folder in `htdocs` directory

### Step 3: Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `care_compass_db`
3. Import the database schema:
   ```sql
   -- Import the care_compass_db.sql file
   ```

### Step 4: Configure Database Connection
Edit `php/config.php` with your database credentials:
```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "care_compass_db";
```

### Step 5: Set File Permissions
Ensure the following directories are writable:
```bash
chmod 755 php/uploads/
chmod 755 assets/
```

### Step 6: Access the Application
Open your web browser and navigate to:
```
http://localhost/carecompass-hospital/
```

## 👥 User Roles & Access

### 🔑 Default Login Credentials

#### Administrator
- **Email**: admin@gmail.com
- **Password**: admin123
- **Access**: Full system management

#### Staff Member
- **Email**: staff@gmail.com
- **Password**: staff123
- **Access**: Task management, supply requests

#### Patient (Register New)
- **Registration**: Available on homepage
- **Access**: Appointment booking, profile management

## 📁 Project Structure

```
CareCompassHospital/
├── assets/                    # Images and media files
│   ├── logo.jpeg             # Hospital logo
│   ├── hero1.jpeg            # Hero slider images
│   ├── hero2.jpeg
│   ├── hero3.jpeg
│   ├── fb.png                # Social media icons
│   ├── twitter.png
│   ├── instagram.png
│   └── staff.jpg             # Staff images
├── php/                      # PHP application files
│   ├── config.php            # Database configuration
│   ├── login.php             # User authentication
│   ├── register.php          # Patient registration
│   ├── admin-dashboard.php   # Admin control panel
│   ├── staff-dashboard.php   # Staff dashboard
│   ├── patient-dashboard.php # Patient dashboard
│   ├── book-appointment.php  # Appointment booking
│   ├── doctors-dashboard.php # Doctor management
│   ├── manage-inventory.php  # Inventory management
│   ├── assign-tasks.php      # Task assignment
│   ├── request-supplies.php  # Supply requests
│   ├── fpdf.php              # PDF generation library
│   ├── uploads/              # User uploaded files
│   └── tutorial/             # FPDF documentation
├── index.php                 # Main homepage
├── care_compass_db.sql       # Database schema
└── README.md                 # Project documentation
```

## 🗄️ Database Schema

### Core Tables

| Table | Description | Key Fields |
|-------|-------------|------------|
| `users` | Multi-role user management | id, email, password, role, first_name, last_name |
| `appointments` | Appointment scheduling | id, patient_id, doctor_id, date, time, status |
| `doctors` | Doctor profiles | id, name, specialization, branch, availability |
| `tasks` | Staff task management | id, staff_id, task_description, status |
| `supply_requests` | Inventory requests | id, staff_id, item_name, quantity, status |
| `inventory` | Medical supplies | id, item_name, category, quantity, status |
| `feedback` | Patient feedback | id, patient_name, feedback, submitted_at |
| `contact_inquiries` | Contact form data | id, name, email, message |
| `messages` | Internal messaging | id, name, email, message |
| `testimonials` | Patient testimonials | id, name, message |
| `faqs` | Frequently asked questions | id, question, answer |

## 🔧 Configuration

### Database Configuration
The database connection is configured in `php/config.php`:
```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "care_compass_db";
```

### File Upload Settings
- Maximum file size: 5MB
- Allowed formats: JPG, JPEG, PNG
- Upload directory: `php/uploads/`

### Session Configuration
- Session timeout: 30 minutes
- Secure session handling
- Role-based access control

## 🚀 Usage Guide

### For Patients
1. **Registration**: Click "Register" on homepage
2. **Login**: Use registered email and password
3. **Book Appointment**: 
   - Navigate to Doctors section
   - Select a doctor
   - Choose date and time
   - Complete payment
4. **View Appointments**: Access from patient dashboard
5. **Submit Feedback**: Available when logged in

### For Staff
1. **Login**: Use staff credentials
2. **View Tasks**: Check assigned tasks
3. **Update Task Status**: Mark tasks as in-progress/completed
4. **Request Supplies**: Submit supply requests
5. **Patient Check-ins**: Manage patient arrivals

### For Administrators
1. **Login**: Use admin credentials
2. **Analytics**: View system statistics
3. **User Management**: Manage staff and patient accounts
4. **Appointment Management**: Oversee all appointments
5. **Inventory Management**: Approve supply requests
6. **Doctor Management**: Add/edit doctor profiles

## 🔒 Security Features

- **Password Hashing**: bcrypt encryption
- **SQL Injection Prevention**: Prepared statements
- **Session Security**: Secure session handling
- **Input Validation**: Server-side validation
- **File Upload Security**: Type and size validation
- **Role-Based Access**: Strict permission controls

## 📱 Responsive Design

The system is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones
- All modern web browsers

## 🎨 UI/UX Features

- **Modern Design**: Professional hospital branding
- **Interactive Elements**: 
  - Hero slider with automatic transitions
  - FAQ accordion system
  - Testimonial carousel
  - Search functionality
- **Color Scheme**: Medical blue (#00a9b4) with professional styling
- **Navigation**: Dropdown menus with subcategories

## 📊 Specialized Services

### Centres of Excellence
- **Accident and Emergency**: 24/7 emergency services
- **Heart Centres**: Cardiac care and treatment
- **Brain and Spine Centre**: Neurological services

### Health & Wellness
- **Amazing Care**: General healthcare services
- **Diabetes Care**: Specialized diabetes management

## 🔄 Workflow Examples

### Appointment Booking Workflow
1. Patient registers/logs in
2. Searches for available doctors
3. Selects doctor and time slot
4. System checks for conflicts
5. Patient completes payment
6. System generates PDF receipt
7. Appointment is confirmed

### Supply Request Workflow
1. Staff logs in
2. Submits supply request
3. Admin reviews request
4. Admin approves/rejects
5. Inventory is updated
6. Staff is notified

## 🐛 Troubleshooting

### Common Issues

#### Database Connection Error
```php
// Check config.php settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "care_compass_db";
```

#### File Upload Issues
- Ensure `php/uploads/` directory is writable
- Check file size limits in PHP configuration
- Verify file type restrictions

#### Session Issues
- Clear browser cookies
- Check PHP session configuration
- Ensure proper session_start() calls

### Error Logs
Check XAMPP error logs at:
- Apache: `xampp/apache/logs/error.log`
- PHP: `xampp/php/logs/php_error_log`

## 🔮 Future Enhancements

### Planned Features
- [ ] Real payment gateway integration
- [ ] Mobile application (iOS/Android)
- [ ] Advanced analytics dashboard
- [ ] Email/SMS notifications
- [ ] API for external integrations
- [ ] Two-factor authentication
- [ ] Audit logging system
- [ ] Multi-language support

### Technical Improvements
- [ ] RESTful API architecture
- [ ] Microservices implementation
- [ ] Docker containerization
- [ ] CI/CD pipeline
- [ ] Automated testing
- [ ] Performance optimization

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Development Team

- **Lead Developer**: [Your Name]
- **UI/UX Designer**: [Designer Name]
- **Database Administrator**: [DBA Name]

## 📞 Support

For support and questions:
- **Email**: support@carecompass.com
- **Phone**: +94 11 789 1234
- **Website**: https://carecompass.com

## 🙏 Acknowledgments

- FPDF Library for PDF generation
- XAMPP for local development environment
- Bootstrap for responsive design inspiration
- All contributors and testers

---

**CareCompass Hospital Management System** - Making healthcare management efficient and accessible.

*Last updated: February 2025* 