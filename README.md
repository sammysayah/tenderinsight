<p align="center"><a href="https://tenderinsight.ssappdev.com" target="_blank"><img src="https://tenderinsight.ssappdev.com/img/TenderInsight.png" width="400" alt="Tender Insight System Logo"></a></p>

<p align="center">
<a href="https://github.com/sammysayah/tender-insight/actions"><img src="https://github.com/sammysayah/tender-insight/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/sammysayah/tender-insight"><img src="https://img.shields.io/packagist/dt/sammysayah/tender-insight" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/sammysayah/tender-insight"><img src="https://img.shields.io/packagist/v/sammysayah/tender-insight" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/sammysayah/tender-insight"><img src="https://img.shields.io/packagist/l/sammysayah/tender-insight" alt="License"></a>
</p>

## About Tender Insight System

Tender Insight System is a web-based system developed using Laravel for keeping company important documents and analyzing company business performance through a dashboard that shows:

- Total sales made per month and year through Tenders, Prequalifications and Quotations
- Sales breakdown by category (Tenders, Prequalifications, Quotations)
- Document management for company and business documents
- Tracking system for Tenders, Prequalifications and Quotations with won/lost status
- Role-based access control (Admin can view all documents and apply for opportunities)
- Restricted access for normal users based on assigned roles

## Key Features

### Business Functionality
- Company document management
- Business performance analytics dashboard
- Tender/Prequalification/Quotation tracking
- Sales reporting by period and category
- Role-based access control

### Security Features
- **Authentication**:
  - Secure user registration/login
  - Password reset functionality
  - Email verification
  - Two-factor authentication (2FA)
  
- **Authorization**:
  - Role/Permission system
  - Gates & Policies
  - Route protection
  
- **Data Protection**:
  - Password hashing (bcrypt)
  - CSRF protection via `@csrf` in forms
  - Input validation/sanitization
  - Automatic XSS protection via Blade (`{{ }}` escapes output)
  - SQL injection prevention via Eloquent/Query Builder
  
- **Session Security**:
  - Encrypted session storage
  - Configurable session lifetime
  - Protection against session fixation
  - Security Headers (Middleware)
    - XSRF-TOKEN cookie for CSRF protection
    - X-Frame-Options: SAMEORIGIN (prevents clickjacking)

## Getting Started

 **installation/quick start guide here**
-After downloading the project
-edit the env files with  database and SMTP configurations
-run php artisan migrate if you are setting on local machine
-run php artisan serve  to run server if you are setting on local machine
-to access the system  use http:/127.0.0.1:8000
-register two users one should be admin and the other user(normal) 
-after registering user both need email verification 
-for initial configuration for admin user go to phpmyadmin or mysql console  and change admin user in usertype filed change from user to admin and in is_approved change to 1
-then after login as admin you can now approve the other users.

## Documentation

[Add link to your documentation or brief setup instructions]

## License

The Tender Insight System is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
