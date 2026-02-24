<div align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="assets/icons/logoBranco.png">
    <source media="(prefers-color-scheme: light)" srcset="assets/icons/logoPreto.png">
    <img alt="VoluntárioCOVID19" src="assets/icons/logoPreto.png" width="320">
  </picture>

  <br />
  <br />

  <p><strong>Connecting volunteers with institutions for meaningful social impact</strong></p>

  <p>
    <a href="#-getting-started">Quick Start</a> ·
    <a href="#-features">Features</a> ·
    <a href="#-project-structure">Structure</a> ·
    <a href="#-test-accounts">Test Accounts</a>
  </p>

  <br />

  [![PHP](https://img.shields.io/badge/PHP-7.4-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
  [![MariaDB](https://img.shields.io/badge/MariaDB-10.6-003545?style=flat-square&logo=mariadb&logoColor=white)](https://mariadb.org/)
  [![Apache](https://img.shields.io/badge/Apache-2.4-D22128?style=flat-square&logo=apache&logoColor=white)](https://httpd.apache.org/)
  [![Docker](https://img.shields.io/badge/Docker-ready-2496ED?style=flat-square&logo=docker&logoColor=white)](https://www.docker.com/)
  [![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=flat-square&logo=javascript&logoColor=black)](https://developer.mozilla.org/docs/Web/JavaScript)
  [![License](https://img.shields.io/github/license/AfonsoBenedito/Volunteering-Website?style=flat-square&color=green)](LICENSE)

</div>

---

## Overview

**VoluntárioCOVID19** (VC19) is a web platform built to connect volunteers with the institutions that need them. In a landscape shaped by social emergencies, meaningful volunteering shouldn't require navigating fragmented networks. VC19 brings both sides to one place — volunteers find causes they care about, institutions find the people they need.

The platform supports two types of actors:

- 🙋 **Volunteers** — register, build a profile, browse initiatives, and connect with organisations
- 🏢 **Institutions** — manage their presence, recruit matching volunteers, and coordinate their community

> 🎓 Academic project for **ASW** (Aplicações e Serviços Web) at [Faculdade de Ciências, Universidade de Lisboa](https://ciencias.ulisboa.pt/).

---

## ✨ Features

<table>
<tr>
<td width="50%" valign="top">

### 🙋 For Volunteers

- **Profile** — Bio, interests, availability, location, and driving licence
- **Discovery** — Browse institutions and volunteer opportunities
- **Search & Filter** — Find by district, council, or parish
- **Photo Upload** — Personalise with a profile picture
- **Verification** — Trusted badge for verified volunteers

</td>
<td width="50%" valign="top">

### 🏢 For Institutions

- **Organisation Page** — Full profile with description, contacts, and representative
- **Volunteer Recruitment** — Search volunteers that match your requirements
- **Profile Management** — Keep institution details up to date

</td>
</tr>
</table>

### ⚙️ Platform-wide

| | |
|---|---|
| 🔐 **Secure auth** | bcrypt password hashing via `password_hash()` |
| 🛡️ **SQL injection** | Prepared statements throughout |
| 🧹 **XSS prevention** | `htmlspecialchars()` on all rendered output |
| 🔒 **Access control** | Session-based, per-role route protection |
| 🖥️ **Admin panel** | HTTP Basic Auth protected dashboard |
| 📱 **Responsive** | Works on desktop and mobile |

---

## 🛠️ Tech Stack

<table>
<tr>
  <td align="center" width="120">
    <img src="https://skillicons.dev/icons?i=php" width="48" /><br />
    <sub><b>PHP 7.4</b></sub>
  </td>
  <td align="center" width="120">
    <img src="https://skillicons.dev/icons?i=mysql" width="48" /><br />
    <sub><b>MariaDB 10.6</b></sub>
  </td>
  <td align="center" width="120">
    <img src="https://skillicons.dev/icons?i=html" width="48" /><br />
    <sub><b>HTML5</b></sub>
  </td>
  <td align="center" width="120">
    <img src="https://skillicons.dev/icons?i=css" width="48" /><br />
    <sub><b>CSS3</b></sub>
  </td>
  <td align="center" width="120">
    <img src="https://skillicons.dev/icons?i=js" width="48" /><br />
    <sub><b>JavaScript</b></sub>
  </td>
  <td align="center" width="120">
    <img src="https://skillicons.dev/icons?i=docker" width="48" /><br />
    <sub><b>Docker</b></sub>
  </td>
</tr>
</table>

---

## 🚀 Getting Started

### Option 1 — Docker *(recommended)*

The fastest way to get the full stack running — no local PHP or MySQL needed.

**Prerequisite:** [Docker Desktop](https://www.docker.com/products/docker-desktop/)

```bash
git clone https://github.com/AfonsoBenedito/Volunteering-Website.git
cd Volunteering-Website
make run-web
```

Open **[http://localhost:8080](http://localhost:8080)**

| Command | Description |
|---|---|
| `make run-web` | Build images and start all services |
| `make stop-web` | Stop containers — data is preserved |
| `make clean-web` | Stop containers and wipe all volumes |

> **Admin panel** → [http://localhost:8080/admin](http://localhost:8080/admin) · credentials: `admin` / `admin`

---

### Option 2 — XAMPP

1. Install [XAMPP](https://www.apachefriends.org/) with PHP 7.4 and MySQL
2. Copy the project into your `htdocs/` directory
3. Create the database and import the schema:

```bash
mysql -u root -p -e "CREATE DATABASE asw024;"
mysql -u root -p asw024 < db/schema.sql
mysql -u root -p asw024 < db/seed.sql   # optional — loads test accounts
```

4. Edit `includes/connection.php` with your credentials
5. Start **Apache** and **MySQL** from the XAMPP Control Panel
6. Open **[http://localhost/Volunteering-Website](http://localhost/Volunteering-Website)**

---

### Option 3 — University Server

The default credentials in `includes/connection.php` are pre-configured for `appserver-01.alunos.di.fc.ul.pt` (requires FCUL network or VPN). Upload all files via FTP or SCP — no configuration changes needed.

---

## 👤 Test Accounts

All accounts use the password **`password123`**

<details>
<summary><b>Volunteers</b></summary>
<br />

| Name | Email | Username | Location |
|------|-------|----------|----------|
| João Silva | joao.silva@example.com | `joao_silva` | Lisboa, Alvalade |
| Maria Santos | maria.santos@example.com | `maria_santos` | Porto, Cedofeita |
| Carlos Oliveira | carlos.oliveira@example.com | `carlos_oliveira` | Coimbra, Sé Nova |

</details>

<details>
<summary><b>Institutions</b></summary>
<br />

| Institution | Email | Representative |
|-------------|-------|----------------|
| Cruz Vermelha Portuguesa | contacto@cruzvermelha.pt | Ana Costa |
| Refood | info@refood.pt | Pedro Martins |
| Banco Alimentar Contra a Fome | geral@bancoalimentar.pt | Sofia Rodrigues |

</details>

---

## 📁 Project Structure

```
Volunteering-Website/
├── actions/                     # POST-only form handlers (never visited directly)
├── admin/                       # Admin dashboard — HTTP Basic Auth protected
│   ├── .htaccess
│   └── index.php
├── assets/
│   ├── FotosInstituicao/        # User-uploaded institution photos  [gitignored]
│   ├── FotosVoluntario/         # User-uploaded volunteer photos    [gitignored]
│   ├── icons/                   # App icons & logos
│   └── images/                  # Static images
├── db/
│   ├── schema.sql               # Database schema
│   └── seed.sql                 # Test data
├── includes/
│   ├── connection.php           # DB connection — reads DB_* env vars
│   ├── confirmarAlteracoes.php  # Change confirmation snippet
│   └── login.php                # Authentication handler
├── scripts/                     # JavaScript
├── styles/                      # CSS
├── index.php                    # Landing page
├── home.php                     # Authenticated home
├── voluntarios.php              # Volunteer directory
├── instituicoes.php             # Institution directory
├── perfilVoluntario.php         # Volunteer profile view
├── perfilInstituicao.php        # Institution profile view
├── editarPerfil.php             # Volunteer settings
├── editarPerfilInstituicao.php  # Institution settings
├── inscreverVoluntario.php      # Volunteer registration
├── inscreverInstituicao.php     # Institution registration
├── Dockerfile
├── docker-compose.yml
└── Makefile
```

---

## 🔒 Security

| Threat | Mitigation |
|--------|-----------|
| SQL Injection | Prepared statements and parameterised queries |
| Password exposure | `password_hash()` with bcrypt, verified via `password_verify()` |
| XSS | `htmlspecialchars()` on all user-controlled output |
| Unauthorised access | Session-based role checks on every protected page |
| Admin exposure | HTTP Basic Auth (`.htaccess` + `.htpasswd`) |

---

## 📄 License

This project is licensed under the terms specified in the [LICENSE](LICENSE) file.

---

<div align="center">
  <br />
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="assets/icons/logoBranco.png">
    <source media="(prefers-color-scheme: light)" srcset="assets/icons/logoPreto.png">
    <img src="assets/icons/logoPreto.png" width="72" alt="VC19">
  </picture>
  <br /><br />
  <sub>Built with ❤️ for <strong>ASW</strong> · <a href="https://ciencias.ulisboa.pt/">Faculdade de Ciências, Universidade de Lisboa</a></sub>
</div>
