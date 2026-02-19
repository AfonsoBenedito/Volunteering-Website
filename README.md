<div align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="assets/Icons/logoBranco.png">
    <source media="(prefers-color-scheme: light)" srcset="assets/Icons/logoPreto.png">
    <img alt="VoluntárioCOVID19" src="assets/Icons/logoPreto.png" width="280">
  </picture>

  <h3>Volunteer Management Platform</h3>
  <p>Connecting volunteers with institutions for meaningful social impact</p>

  [![PHP](https://img.shields.io/badge/PHP-7.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
  [![MariaDB](https://img.shields.io/badge/MariaDB-10.4-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
  [![Apache](https://img.shields.io/badge/Apache-2.4-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://httpd.apache.org/)
  [![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/docs/Web/HTML)
  [![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/docs/Web/CSS)
  [![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/docs/Web/JavaScript)
  [![License](https://img.shields.io/github/license/AfonsoBenedito/asw-grupo24?style=for-the-badge)](LICENSE)
</div>

---

## About

**VoluntárioCOVID19** (VC19) is a volunteer management web platform designed to bridge the gap between volunteers and institutions that need their help. Institutions can publish volunteer opportunities and events, while volunteers can discover, connect, and register with the causes they care about.

> 🎓 Built as an academic project for **ASW** (Aplicações e Serviços Web) at [Faculdade de Ciências, Universidade de Lisboa](https://ciencias.ulisboa.pt/).

This branch (`main`) contains the **original version** of the project, designed to run on a standard PHP/MySQL server — including XAMPP for local development or the university server at `appserver-01.alunos.di.fc.ul.pt`.

> 🐳 Looking for the containerized version with Docker Compose and Google Cloud Run support? Check out the [`dockerized`](https://github.com/AfonsoBenedito/asw-grupo24/tree/dockerized) branch.

---

## Features

<table>
<tr>
<td width="50%">

### 🙋 For Volunteers
- **Profile Management** — Create and manage volunteer profiles with personal info, interests, and availability
- **Browse Opportunities** — Discover events and initiatives posted by institutions
- **Search & Filter** — Find institutions and volunteers by location and interests
- **Photo Upload** — Personalise your profile with a photo
- **Verification** — Account verification for trusted volunteers

</td>
<td width="50%">

### 🏢 For Institutions
- **Organisation Profiles** — Detailed pages with contact info and descriptions
- **Volunteer Recruitment** — Search and connect with matching volunteers
- **Event Management** — Create and manage volunteer events
- **Profile Control** — Update institution info and representative details

</td>
</tr>
</table>

### ⚙️ Platform-wide
- 🔐 Secure authentication with bcrypt password hashing
- 🛡️ SQL injection prevention via prepared statements
- 📱 Responsive design for desktop and mobile
- 🔧 Admin panel for platform management
- 🌐 Session-based access control

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 7.4 |
| **Database** | MariaDB / MySQL |
| **Web Server** | Apache 2.4 |
| **Frontend** | HTML5, CSS3, JavaScript (Vanilla) |

---

## Requirements

To run this project you need **one** of the following setups:

### Option A — XAMPP *(recommended for local development)*

[XAMPP](https://www.apachefriends.org/) is an easy-to-install Apache distribution containing PHP and MySQL. Available for Windows, macOS, and Linux.

**Required:** XAMPP with PHP 7.4 and MySQL enabled.

### Option B — University Server

Access to `appserver-01.alunos.di.fc.ul.pt` (requires FCUL network or VPN). The connection credentials are already configured in `connection.php`.

### Option C — Any PHP/MySQL Server

- PHP 7.4+
- MySQL or MariaDB 10.4+
- Apache with `mod_rewrite` enabled

---

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/AfonsoBenedito/asw-grupo24.git
cd asw-grupo24
```

### 2. Set Up the Database

Create the database and import the schema:

```bash
# Create the database
mysql -u root -p -e "CREATE DATABASE asw024;"

# Import schema
mysql -u root -p asw024 < schema.sql

# Load test data (optional)
mysql -u root -p asw024 < seed_data.sql
```

Or directly inside the MySQL shell:

```sql
CREATE DATABASE asw024;
USE asw024;
SOURCE path/to/schema.sql;
SOURCE path/to/seed_data.sql;
```

### 3. Configure the Database Connection

Edit `connection.php` with your database credentials:

```php
$dbhost = "127.0.0.1";  // Database host
$dbuser = "root";        // Your MySQL username
$dbpass = "";            // Your MySQL password
$dbname = "asw024";      // Database name
```

> For the university server, the preconfigured credentials in `connection.php` are already correct — no changes needed.

### 4. Deploy to Your Web Server

**With XAMPP:**
1. Copy the project folder into `htdocs/` inside your XAMPP installation directory
2. Start **Apache** and **MySQL** from the XAMPP control panel
3. Open http://localhost/asw-grupo24 in your browser

**With the University Server:**
1. Upload all project files to your web directory via FTP or SCP
2. Ensure `connection.php` points to the university server (default config)
3. Access through the university's assigned web server URL

---

## Test Accounts

All test accounts use the password: **`password123`**

### Volunteers

| Name | Email | Username | Location |
|------|-------|----------|----------|
| João Silva | joao.silva@example.com | `joao_silva` | Lisboa, Alvalade |
| Maria Santos | maria.santos@example.com | `maria_santos` | Porto, Cedofeita |
| Carlos Oliveira | carlos.oliveira@example.com | `carlos_oliveira` | Coimbra, Sé Nova |

### Institutions

| Institution | Email | Representative |
|-------------|-------|---------------|
| Cruz Vermelha Portuguesa | contacto@cruzvermelha.pt | Ana Costa |
| Refood | info@refood.pt | Pedro Martins |
| Banco Alimentar Contra a Fome | geral@bancoalimentar.pt | Sofia Rodrigues |

---

## Project Structure

```
asw-grupo24/
├── admin/                    # Admin panel
│   ├── .htaccess            # Admin access control
│   └── index.php            # Admin interface
├── assets/
│   ├── FotosInstituicao/    # Institution profile photos
│   ├── FotosVoluntario/     # Volunteer profile photos
│   ├── Icons/               # Application icons & logos
│   └── Imagens/             # General images
├── includes/
│   ├── functions.php        # Shared utility functions
│   └── init.php             # Session initialization
├── scripts/                 # JavaScript files
├── styles/                  # CSS stylesheets
├── connection.php           # Database connection configuration
├── index.php                # Main entry point
├── home.php                 # Home page (logged-in users)
├── Login.php                # Authentication handler
├── logout.php               # Session termination
├── voluntarios.php          # Volunteer listing & search
├── instituicoes.php         # Institution listing & search
├── perfilVoluntario.php     # Volunteer profile page
├── perfilInstituicao.php    # Institution profile page
├── editarPerfil.php         # Profile editing (volunteers)
├── editarPerfilInstituicao.php # Profile editing (institutions)
├── schema.sql               # Database schema
└── seed_data.sql            # Test/seed data
```

---

## Security

| Feature | Implementation |
|---------|---------------|
| SQL Injection | Prepared statements throughout |
| Password Storage | `password_hash()` with bcrypt |
| Input Sanitization | `htmlspecialchars()` on all output |
| Session Security | Secure session management |
| XSS Prevention | Output escaping before rendering |

---

## Branches

| Branch | Description |
|--------|-------------|
| [`main`](https://github.com/AfonsoBenedito/asw-grupo24/tree/main) | This branch — original PHP/XAMPP setup |
| [`dockerized`](https://github.com/AfonsoBenedito/asw-grupo24/tree/dockerized) | Containerized version with Docker Compose & Cloud Run |

---

## License

This project is licensed under the terms specified in the [LICENSE](LICENSE) file.

---

<div align="center">
  <sub>Built with ❤️ for ASW · Faculdade de Ciências, Universidade de Lisboa</sub>
</div>
