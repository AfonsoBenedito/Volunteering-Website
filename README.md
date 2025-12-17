<div align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="static/assets/Icons/logoBranco.png">
    <source media="(prefers-color-scheme: light)" srcset="static/assets/Icons/logoPreto.png">
    <img alt="VoluntárioCOVID19" src="static/assets/Icons/logoPreto.png" width="280">
  </picture>

  <h3>Volunteer Management Platform</h3>
  <p>Connecting volunteers with institutions for meaningful social impact</p>

  [![Go](https://img.shields.io/badge/Go-1.22-00ADD8?style=for-the-badge&logo=go&logoColor=white)](https://golang.org/)
  [![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org/)
  [![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com/)
  [![Google Cloud Run](https://img.shields.io/badge/Cloud_Run-4285F4?style=for-the-badge&logo=googlecloud&logoColor=white)](https://cloud.google.com/run)
  [![License](https://img.shields.io/github/license/AfonsoBenedito/asw-grupo24?style=for-the-badge)](LICENSE)
</div>

---

## About

**VoluntárioCOVID19** (VC19) is a volunteer management web platform that bridges volunteers and institutions. Institutions can recruit volunteers, while volunteers can discover causes, manage their profiles, and connect with organisations that need their help.

> 🎓 Academic project for **ASW** (Aplicações e Serviços Web) at [Faculdade de Ciências, Universidade de Lisboa](https://ciencias.ulisboa.pt/).

This branch (`dockerized`) is a full rewrite in **Go**, deployable locally with Docker Compose and to **Google Cloud Run** for production. The database is an **ephemeral in-memory SQLite** — no external DB required, data resets on each restart. The original PHP version lives in the [`main`](https://github.com/AfonsoBenedito/asw-grupo24/tree/main) branch.

---

## Features

<table>
<tr>
<td width="50%">

### 🙋 For Volunteers
- **Profile management** — personal info, biography, interests, availability
- **Browse & search** — find institutions by location and type
- **Photo upload** — personalise your profile
- **Account verification** — trusted volunteer status

</td>
<td width="50%">

### 🏢 For Institutions
- **Organisation profiles** — contact info, description, representative details
- **Volunteer search** — find matching volunteers by skills and location
- **Profile control** — update institution data at any time

</td>
</tr>
</table>

### ⚙️ Platform-wide
- 🔐 Secure authentication with bcrypt password hashing
- 🛡️ SQL injection prevention via parameterised queries
- 📱 Responsive design for desktop and mobile
- 🔧 Admin panel with volunteer and institution search
- 🌐 Session-based access control

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Go 1.22 — single binary, no runtime dependencies |
| **Database** | SQLite (in-memory, ephemeral) via `modernc.org/sqlite` |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Templates** | Go `html/template` (server-side rendering) |
| **Sessions** | `gorilla/sessions` (encrypted cookie store) |
| **Containerisation** | Docker & Docker Compose |
| **Cloud** | Google Cloud Run (`europe-southwest1`) |
| **Storage** | Local filesystem (dev) · Google Cloud Storage (prod) |

---

## Architecture

```
                         ┌────────────────────────────────────┐
                         │         Google Cloud Run            │
                         │                                    │
Internet ──────────────► │  Go binary  (port 8080)            │
                         │  · serves HTML templates            │
                         │  · embeds all static assets         │
                         │  · in-memory SQLite (ephemeral)     │
                         └──────────────────────┬─────────────┘
                                                │
                                   ┌────────────▼──────────────┐
                                   │  Google Cloud Storage      │
                                   │  (user photo uploads)      │
                                   └────────────────────────────┘
```

**Locally** the same binary runs as a single container; uploads are saved to `./uploads/`.
The in-memory database is seeded with demo data on startup and resets on every restart.

---

## Quick Start

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) v20.10+ with Compose v2
- [Go](https://golang.org/dl/) 1.22+ *(only needed for local binary builds)*

### Run with Docker (recommended)

```bash
git clone https://github.com/AfonsoBenedito/asw-grupo24.git
cd asw-grupo24
git checkout dockerized

make up        # builds image, starts app + DB, tails logs
```

| Service | URL |
|---------|-----|
| 🌐 Application | http://localhost:8080 |

### Run the Go binary locally

```bash
make run       # build and start the Go binary in the background
make stop      # stop it
make restart   # rebuild and restart
```

---

## Configuration

Copy `.env.example` to `.env` and adjust for your environment:

```bash
cp .env.example .env
```

| Variable | Default (Makefile) | Description |
|----------|--------------------|-------------|
| `PORT` | `8080` | HTTP listen port |
| `SESSION_KEY` | *(dev value)* | 32-byte secret for cookie signing — **change in production** |
| `ADMIN_USERNAME` | `admin` | HTTP Basic Auth username for `/admin` |
| `ADMIN_PASSWORD` | `admin` | HTTP Basic Auth password — **change in production** |
| `STORAGE_BUCKET` | *(unset)* | GCS bucket name; unset → local `./uploads/` |

---

## Database

The application uses an **ephemeral in-memory SQLite** database. The schema is created and seed data is loaded automatically every time the binary starts — no external database or setup required.

**Tables:**

| Table | Description |
|-------|-------------|
| `volunteers` | Volunteer profiles and credentials |
| `institutions` | Institution profiles and credentials |

**Reset data:** simply restart the app (`make restart` or `docker compose restart app`).

---

## Test Accounts

All seed accounts use the password: **`password123`**

### Volunteers (10 total across Portugal)

| Name | Email | Username | Location |
|------|-------|----------|----------|
| João Silva | joao.silva@example.com | `joao_silva` | Lisboa |
| Maria Santos | maria.santos@example.com | `maria_santos` | Porto |
| Carlos Oliveira | carlos.oliveira@example.com | `carlos_oliveira` | Coimbra |
| *(+ 7 more across Braga, Faro, Setúbal, Évora, Aveiro, Viseu, Guimarães)* | | | |

### Institutions (7 total)

| Institution | Email |
|-------------|-------|
| Cruz Vermelha Portuguesa | contacto@cruzvermelha.pt |
| Refood | info@refood.pt |
| Banco Alimentar Contra a Fome | geral@bancoalimentar.pt |
| *(+ AMI, Cáritas, SOS Aldeia Porto, APPACDM Braga)* | |

---

## Project Structure

```
asw-grupo24/
├── templates/              # Go html/template files
│   ├── common.html         # Base layout (nav, login modal, session)
│   ├── home.html
│   ├── volunteers.html
│   ├── institutions.html
│   ├── volunteer_profile.html
│   ├── institution_profile.html
│   ├── register_volunteer.html
│   ├── register_institution.html
│   ├── settings_volunteer.html
│   ├── settings_institution.html
│   └── admin.html
├── static/                 # Embedded at build time (go:embed)
│   ├── assets/
│   │   ├── Icons/          # App logos
│   │   └── Imagens/        # Hero and card images
│   ├── scripts/            # Vanilla JS
│   └── styles/             # CSS
├── uploads/                # User photo uploads (local dev only)
├── main.go                 # Entry point, routing, template loading
├── handlers.go             # HTTP request handlers
├── db.go                   # Database queries
├── models.go               # Data structs and page data types
├── session.go              # Session management
├── storage.go              # File storage (local / GCS)
├── schema.sql              # Database schema (SQLite reference; embedded in db.go)
├── seed_data.sql           # Seed data reference (embedded in db.go)
├── Dockerfile              # Multi-stage Go build
├── docker-compose.yml      # Local development stack
├── .env.example            # Environment variable reference
└── .github/workflows/
    └── deploy.yml          # Cloud Run CI/CD pipeline
```

---

## Deploying to Google Cloud Run

Deployment is automated via GitHub Actions on push to `dockerized`. See `.github/workflows/deploy.yml`.

**Prerequisites:**
- GCP project with billing enabled
- Artifact Registry repository
- Workload Identity Federation configured for the repository
- Cloud SQL PostgreSQL instance (or Neon/external PostgreSQL)

**Required GitHub secrets:**

| Secret | Description |
|--------|-------------|
| `GCP_PROJECT_ID` | Google Cloud project ID |
| `GCP_REGION` | Cloud Run region (e.g. `europe-southwest1`) |
| `GCP_WORKLOAD_IDENTITY_PROVIDER` | Workload Identity provider resource name |
| `GCP_SERVICE_ACCOUNT` | Service account email for deployment |
| `SESSION_KEY` | 32-byte production session secret |
| `ADMIN_USERNAME` | Admin panel username |
| `ADMIN_PASSWORD` | Admin panel password |

---

## Security

| Feature | Implementation |
|---------|---------------|
| Password storage | bcrypt (cost 12) |
| SQL injection | Parameterised queries throughout |
| XSS prevention | `html/template` auto-escaping |
| Session security | Encrypted, signed cookie store |
| Admin access | HTTP Basic Auth over HTTPS |
| File uploads | Content-type validation, size limit (5 MB) |

---

## Troubleshooting

<details>
<summary><strong>Too many redirects after login</strong></summary>

Clear your browser cookies for `localhost`, then try again. This can happen if an old session cookie conflicts with a new `SESSION_KEY`.
</details>

<details>
<summary><strong>Static files or templates not updating</strong></summary>

Static assets and templates are embedded into the binary at build time. After any change to `static/` or `templates/`, rebuild:

```bash
make restart    # local binary
make up         # Docker
```

Then do a hard refresh in the browser (`Ctrl+Shift+R`).
</details>

<details>
<summary><strong>Port already in use</strong></summary>

```bash
lsof -i :8080   # application
lsof -i :5432   # PostgreSQL
```
</details>

---

## Branches

| Branch | Description |
|--------|-------------|
| [`main`](https://github.com/AfonsoBenedito/asw-grupo24/tree/main) | Original version — PHP 7.4 + MariaDB + Apache |
| [`dockerized`](https://github.com/AfonsoBenedito/asw-grupo24/tree/dockerized) | This branch — Go 1.22 + PostgreSQL + Cloud Run |

---

<div align="center">
  <sub>Built with ❤️ for ASW · Faculdade de Ciências, Universidade de Lisboa</sub>
</div>
