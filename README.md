
# Business Trip Management System (BTMS)

A simple Business Trip Management System built using PHP (native) and Bootstrap 5 to handle business trips, approvals, and settlements with security hardening and role-based access control (RBAC).

## Installation on Laragon

To install the BTMS on your local environment using   Laragon  , follow these steps:



1.   Clone or Download the Project 
```  
   https://github.com/hfzrfa/btms_yeb.git
```

2.   Set up Databases  
   - Open   phpMyAdmin   from Laragon or use your MySQL command-line tool.
   - Create a new database (e.g., `btms_db`), and import the initial schema if available.
   - Alternatively, if the database is provided, you can import it via `phpMyAdmin` or a MySQL client.

3.   Configure Database Credentials  
   - Open `config/config.php`.
   - Set the database credentials for your environment:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'btms_db');  // Or 'yeb_business' if applicable
     ```

4.   Access the Application  
   - Open your browser and go to `http://localhost/btms/public/` to access the BTMS application.

5.   Set up Permissions and Roles  
   - Default roles are `admin`, `manager`, and `employee`. Ensure you set up users in the database with appropriate roles.

6.   Log In  
   - The default admin user can log in with a pre-configured username and password, or you can create new users via the database.


## Features

### 1.   Multi-database Support  
   - The system attempts to use the `yeb_business` database, falling back to the local `btms_db`.

### 2.   Roles  
   - The system has role-based access control (RBAC) with three roles:
     - `admin`
     - `manager`
     - `employee`
   - Role-based page whitelisting in the router ensures each role only has access to appropriate pages.

### 3.   Employee and Master Data Management  
   - Manage employee data, including department, group, and designation.

### 4.   Trip Registration and Approval  
   - Register trips, submit approval forms, and manage trip cancellations.
   - Printable trip approval forms are available for each trip.

### 5.   Dynamic Approver Names  
   - Approver names are dynamically pulled from the `circular` table (DeptCode → Approval1..7), streamlining the approval process.

### 6.   Temporary Payment Management  
   - Supports multi-currency payments (IDR, SGD, YEN).
   - The Temporary Payment feature replaces the old "Advance" system, offering better flexibility with currency management.

### 7.   Simplified Settlement Workflow  
   - Users can enter the amount used, and the system auto-calculates the remaining or shortfall of temporary payments.
   - Payments to employees or returns to accounts are automatically calculated based on variance logic.

### 8.   Auto Currency Conversion  
   - The system auto-converts settlement totals between IDR, SGD, and YEN based on the original temporary payment ratios.

### 9.   Printable Documents  
   - Trip approval and settlement forms can be printed or saved as PDF using the browser’s Print dialog (Ctrl+P).
   - The settlement printout is auto-converted to SGD/YEN if applicable.

### 10.   File Uploads  
   - Attach multiple receipt files (images or PDFs) for settlement verification.

### 11.   Security Features  
   -   Session Security  : Regenerates sessions on login and uses strict cookie flags (HttpOnly, SameSite=Strict).
   -   Login Throttling  : Limits login attempts to prevent brute force attacks.
   -   CSRF Protection  : Uses CSRF tokens for modifying forms.
   -   Security Headers  : Includes Content Security Policy (CSP), X-Frame-Options, and other headers.
   -   Sanitized Routing  : Ensures page parameters are sanitized to prevent security vulnerabilities.

### 12.   Auto Schema Extension  
   - The system automatically extends the database schema during runtime, adding missing columns or tables for backward compatibility.

## Printing PDFs

We output pure HTML to keep the system lightweight and use the browser’s Print dialog (Ctrl+P) → Save as PDF for document generation. If needed, you can later integrate a dedicated PDF library like   Dompdf   or   mPDF   for real PDF generation.

## Auto Currency Conversion Logic

- The system automatically converts between IDR, SGD, and YEN. The conversion rates are inferred based on the temporary payment amount:

rate\_sgd = temp\_payment\_idr / temp\_payment\_sgn
rate\_yen = temp\_payment\_idr / temp\_payment\_yen


## Security Hardening Summary

| Area        | Measures                                                        |
|-------------|-----------------------------------------------------------------|
|   Sessions  | ID regeneration on login, strict cookie flags                   |
|   Login     | Throttling (delay / attempt limiting)                            |
|   Routing   | Whitelisted pages by role, sanitized `page` param                |
|   CSRF      | Token helper on modifying forms                                 |
|   Output    | `esc()` helper for HTML contexts                                 |
|   Headers   | CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy   |

## Roadmap / Potential Enhancements

- Settlement approval (manager/finance) and digital signatures.
- Real PDF generation (Dompdf/mPDF) with QR code or hash footer.
- Editable settlements with audit trail.
- Pagination/server-side filtering for large datasets.
- Enhanced input validation and stronger filtering.
- Comprehensive logging and auditing (DB or file).
- Unit/feature tests with Continuous Integration (CI).
- Docker/Compose for reproducible development environments.
- Multi-currency line items (currently only IDR is converted).

## Developer Notes

- Avoid relying on runtime `ALTER TABLE` in strict environments; instead, port those statements to migration scripts.
- Add new pages by registering them in the router whitelist to enforce RBAC.
- If switching back to detailed settlement line items, restore the previous modal/table logic and populate `settlement_items`.

## Attachments

  ![Web View](pubic/assets/1.png)
    ![Web View](pubic/assets/2.png)
      ![Web View](pubic/assets/3.png)




