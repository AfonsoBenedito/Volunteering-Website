<div align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="static/assets/Icons/logoBranco.png">
    <source media="(prefers-color-scheme: light)" srcset="static/assets/Icons/logoPreto.png">
    <img alt="VC19 — VoluntárioCOVID19" src="static/assets/Icons/logoPreto.png" width="260">
  </picture>

  <h3>VoluntárioCOVID19</h3>
  <p>A volunteer management platform connecting people and institutions for meaningful social impact</p>

  <p>
    <a href="https://golang.org/"><img src="https://img.shields.io/badge/Go-1.24-00ADD8?style=for-the-badge&logo=go&logoColor=white" alt="Go 1.24"></a>
    <a href="https://www.sqlite.org/"><img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite"></a>
    <a href="https://www.docker.com/"><img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker"></a>
    <a href="https://cloud.google.com/run"><img src="https://img.shields.io/badge/Cloud_Run-4285F4?style=for-the-badge&logo=googlecloud&logoColor=white" alt="Google Cloud Run"></a>
    <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge" alt="MIT License"></a>
  </p>
</div>

---

## About

**VoluntárioCOVID19** (VC19) was born out of the need to simplify how volunteers and organisations find each other. In a world where social crises demand collective action, VC19 removes the friction between willing helpers and the institutions that need them.

Volunteers build rich profiles with their skills, availability, location and biography. Institutions register, manage their presence and search for the right people. An admin panel gives platform operators full oversight over both sides.

> 🎓 Academic project for **ASW** (Aplicações e Serviços Web) at [Faculdade de Ciências, Universidade de Lisboa](https://ciencias.ulisboa.pt/).

This branch (`for-cloud-run`) is a full rewrite in **Go**, deployable locally with Docker Compose and to **Google Cloud Run** for production. The database is an **ephemeral in-memory SQLite** — no external DB required, data resets on each restart. The original PHP version lives on the [`main`](https://github.com/AfonsoBenedito/Volunteering-Website/tree/main) branch.

---

## Goals

- **Simplicity first** — single Go binary, zero external database, one-command startup
- **Real-world deployment** — containerised with Docker and shipped to Google Cloud Run via a full CI/CD pipeline
- **Security by default** — bcrypt passwords, parameterised queries, auto-escaping templates, and signed encrypted sessions
- **Accessibility** — responsive layout for desktop and mobile, with light/dark logo variants

---

## Features

<table>
<tr>
<td width="50%">

### 🙋 For Volunteers
- **Profile management** — personal info, biography, interests, availability
- **Browse & search** — find institutions by location and type
- **Photo upload** — personalise your profile with an avatar
- **Account verification** — trusted volunteer badge

</td>
<td width="50%">

### 🏢 For Institutions
- **Organisation profiles** — contact info, description, representative details
- **Volunteer discovery** — search volunteers by skill and location
- **Profile control** — update institution data at any time

</td>
</tr>
</table>

### ⚙️ Platform-wide

- 🔐 **Secure authentication** — bcrypt password hashing (cost 12)
- 🛡️ **Injection protection** — parameterised queries & `html/template` auto-escaping
- 📱 **Responsive design** — desktop and mobile ready
- 🔧 **Admin panel** — volunteer and institution search behind HTTP Basic Auth
- ☁️ **Cloud-native storage** — local filesystem in dev · Google Cloud Storage in production
- 🌐 **Session-based access control** — encrypted, signed cookie store

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Go 1.24 — single binary, no runtime dependencies |
| **Database** | SQLite (in-memory, ephemeral) via `modernc.org/sqlite` |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Templates** | Go `html/template` (server-side rendering) |
| **Sessions** | `gorilla/sessions` (encrypted cookie store) |
| **Containerisation** | Docker & Docker Compose |
| **Cloud** | Google Cloud Run (`europe-southwest1`) |
| **Storage** | Local filesystem (dev) · Google Cloud Storage (prod) |
| **CI/CD** | GitHub Actions → Artifact Registry → Cloud Run |

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

**Locally**, the same binary runs as a single container; uploads are saved to `./uploads/`.
The in-memory database is seeded with demo data on startup and resets on every restart.

---

## Quick Start

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) v20.10+ with Compose v2
- [Go](https://golang.org/dl/) 1.24+ *(only needed for local binary builds)*

### Run with Docker (recommended)

```bash
git clone https://github.com/AfonsoBenedito/Volunteering-Website.git
cd Volunteering-Website

make up        # builds image, starts app, tails logs
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

See all available targets:

```bash
make help
```

---

## Configuration

Copy `.env.example` to `.env` and adjust as needed:

```bash
cp .env.example .env
```

| Variable | Default | Description |
|----------|---------|-------------|
| `PORT` | `8080` | HTTP listen port |
| `SESSION_KEY` | *(dev value)* | 32-byte secret for cookie signing — **change in production** |
| `ADMIN_USERNAME` | `admin` | HTTP Basic Auth username for `/admin` |
| `ADMIN_PASSWORD` | `admin` | HTTP Basic Auth password — **change in production** |
| `STORAGE_BUCKET` | *(unset)* | GCS bucket name — if unset, falls back to local `./uploads/` |

---

## Database

The application uses an **ephemeral in-memory SQLite** database. The schema is created and seed data is loaded automatically on startup — no external database or manual setup required.

| Table | Description |
|-------|-------------|
| `volunteers` | Volunteer profiles and credentials |
| `institutions` | Institution profiles and credentials |

**Reset data:** restart the app — `make restart` or `docker compose restart app`.

---

## Test Accounts

All seed accounts use the password **`password123`**

### Volunteers — 10 across Portugal

| Name | Email | Username | Location |
|------|-------|----------|----------|
| João Silva | joao.silva@example.com | `joao_silva` | Lisboa |
| Maria Santos | maria.santos@example.com | `maria_santos` | Porto |
| Carlos Oliveira | carlos.oliveira@example.com | `carlos_oliveira` | Coimbra |
| *(+ 7 more — Braga, Faro, Setúbal, Évora, Aveiro, Viseu, Guimarães)* | | | |

### Institutions — 7 total

| Institution | Email |
|-------------|-------|
| Cruz Vermelha Portuguesa | contacto@cruzvermelha.pt |
| Refood | info@refood.pt |
| Banco Alimentar Contra a Fome | geral@bancoalimentar.pt |
| *(+ AMI, Cáritas, SOS Aldeia Porto, APPACDM Braga)* | |

---

## Project Structure

```
Volunteering-Website/
├── templates/                  # Go html/template files
│   ├── common.html             # Base layout (nav, login modal, session)
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
├── static/                     # Embedded at build time (go:embed)
│   ├── assets/
│   │   ├── Icons/              # App logos
│   │   └── Imagens/            # Hero and card images
│   ├── scripts/                # Vanilla JS
│   └── styles/                 # CSS
├── uploads/                    # User photo uploads (local dev only)
├── main.go                     # Entry point, routing, template loading
├── handlers.go                 # HTTP request handlers
├── db.go                       # Database queries
├── models.go                   # Data structs and page data types
├── session.go                  # Session management
├── storage.go                  # File storage (local / GCS)
├── schema.sql                  # Database schema (SQLite reference)
├── seed_data.sql               # Seed data reference
├── Dockerfile                  # Multi-stage Go build
├── docker-compose.yml          # Local development stack
├── .env.example                # Environment variable reference
└── .github/workflows/
    └── deploy.yml              # Cloud Run CI/CD pipeline
```

---

## Deploying to Google Cloud Run

Deployment is automated via **GitHub Actions** on every push to `for-cloud-run`. See [`.github/workflows/deploy.yml`](.github/workflows/deploy.yml).

The workflow builds a Docker image, pushes it to **Artifact Registry**, and deploys to Cloud Run (`europe-southwest1`) with 0 → 3 instance autoscaling.

**Required GitHub secrets:**

| Secret | Description |
|--------|-------------|
| `GCP_PROJECT_ID` | Google Cloud project ID |
| `GCP_SA_KEY` | Service account key JSON |
| `SESSION_KEY` | 32-byte production session secret |
| `STORAGE_BUCKET` | GCS bucket name for photo uploads |
| `ADMIN_USERNAME` | Admin panel username |
| `ADMIN_PASSWORD` | Admin panel password |

---

## Security

| Feature | Implementation |
|---------|----------------|
| Password storage | bcrypt (cost 12) |
| SQL injection | Parameterised queries throughout |
| XSS prevention | `html/template` auto-escaping |
| Session security | Encrypted, signed cookie store |
| Admin access | HTTP Basic Auth over HTTPS |
| File uploads | Content-type validation, 5 MB size limit |

---

## Troubleshooting

<details>
<summary><strong>Too many redirects after login</strong></summary>

Clear your browser cookies for `localhost`, then try again. This can happen if an old session cookie conflicts with a new `SESSION_KEY`.
</details>

<details>
<summary><strong>Static files or templates not updating</strong></summary>

Assets and templates are embedded into the binary at build time. After changing `static/` or `templates/`, rebuild:

```bash
make restart    # local binary
make up         # Docker
```

Then hard-refresh in the browser (`Ctrl+Shift+R` / `Cmd+Shift+R`).
</details>

<details>
<summary><strong>Port already in use</strong></summary>

```bash
lsof -i :8080
```
</details>

---

## Branches

| Branch | Description |
|--------|-------------|
| [`main`](https://github.com/AfonsoBenedito/Volunteering-Website/tree/main) | Original version — PHP 7.4 + MariaDB + Apache |
| [`for-cloud-run`](https://github.com/AfonsoBenedito/Volunteering-Website/tree/for-cloud-run) | This branch — Go 1.24 + SQLite + Docker + Cloud Run |

---

## Team

<table>
  <tr>
    <td align="center"><b>Afonso Benedito</b></td>
    <td align="center"><b>Tomas Ndlate</b></td>
    <td align="center"><b>Afonso Telles</b></td>
  </tr>
</table>

---

<div align="center">
  <sub>Built for ASW · <a href="https://ciencias.ulisboa.pt/">Faculdade de Ciências, Universidade de Lisboa</a></sub>
  <br>
  <sub>Licensed under the <a href="LICENSE">MIT License</a> · © 2021 Afonso Benedito, Tomas Ndlate, Afonso Telles</sub>
</div>
