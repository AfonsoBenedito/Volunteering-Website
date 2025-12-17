package main

import (
	"embed"
	"fmt"
	"html/template"
	"io/fs"
	"log"
	"net/http"
	"os"
	"strings"
	"time"
)

//go:embed templates
var templateFS embed.FS

//go:embed static
var staticFS embed.FS

// App holds application-wide dependencies.
type App struct {
	tmpls    map[string]*template.Template
	db       *Database
	sessions *SessionManager
	storage  Storage
}

func main() {
	// ── Database ─────────────────────────────────────────────────────────
	db := newDatabase()

	// ── Sessions ──────────────────────────────────────────────────────────
	sessionKey := os.Getenv("SESSION_KEY")
	if sessionKey == "" {
		sessionKey = "dev-secret-key-please-change-in-production-32+"
		log.Println("WARNING: Using default SESSION_KEY. Set SESSION_KEY env var in production.")
	}
	sessions := newSessionManager(sessionKey)

	// ── Storage ───────────────────────────────────────────────────────────
	var storage Storage
	if bucket := os.Getenv("STORAGE_BUCKET"); bucket != "" {
		storage = &GCSStorage{Bucket: bucket}
		log.Printf("Using GCS storage: gs://%s", bucket)
	} else {
		if err := os.MkdirAll("uploads", 0755); err != nil {
			log.Printf("Warning: could not create uploads dir: %v", err)
		}
		storage = &LocalStorage{BasePath: "uploads", BaseURL: "/uploads"}
		log.Println("Using local storage: ./uploads/")
	}

	// ── Templates ─────────────────────────────────────────────────────────
	tmpls := buildTemplates()

	app := &App{tmpls: tmpls, db: db, sessions: sessions, storage: storage}

	// ── Static files (embedded) ───────────────────────────────────────────
	staticSub, err := fs.Sub(staticFS, "static")
	if err != nil {
		log.Fatalf("static sub: %v", err)
	}
	fileServer := http.FileServer(http.FS(staticSub))

	// ── Router ────────────────────────────────────────────────────────────
	mux := http.NewServeMux()

	// Static assets (embedded)
	mux.Handle("/styles/", fileServer)
	mux.Handle("/assets/", fileServer)
	mux.Handle("/scripts/", fileServer)

	// User-uploaded files (local dev only; GCS serves directly via URL in prod)
	if _, err := os.Stat("uploads"); err == nil {
		mux.Handle("/uploads/", http.StripPrefix("/uploads/", http.FileServer(http.Dir("uploads"))))
	}

	// Pages
	mux.HandleFunc("GET /{$}", app.homeHandler)
	mux.HandleFunc("POST /login", app.loginHandler)
	mux.HandleFunc("GET /logout", app.logoutHandler)

	mux.HandleFunc("GET /volunteers", app.volunteersHandler)
	mux.HandleFunc("GET /institutions", app.institutionsHandler)

	mux.HandleFunc("GET /volunteer/{username}", app.volunteerProfileHandler)
	mux.HandleFunc("GET /institution/{id}", app.institutionProfileHandler)

	mux.HandleFunc("GET /register/volunteer", app.registerVolunteerFormHandler)
	mux.HandleFunc("POST /register/volunteer", app.registerVolunteerHandler)
	mux.HandleFunc("GET /register/institution", app.registerInstitutionFormHandler)
	mux.HandleFunc("POST /register/institution", app.registerInstitutionHandler)

	mux.HandleFunc("GET /profile", app.myProfileHandler)
	mux.HandleFunc("GET /settings", app.settingsHandler)
	mux.HandleFunc("POST /settings/profile", app.updateProfileHandler)
	mux.HandleFunc("POST /settings/data", app.updateDataHandler)
	mux.HandleFunc("POST /settings/password", app.updatePasswordHandler)
	mux.HandleFunc("POST /settings/email", app.updateEmailHandler)
	mux.HandleFunc("POST /settings/photo", app.updatePhotoHandler)

	mux.HandleFunc("/admin", app.adminMiddleware(app.adminHandler))
	mux.HandleFunc("/admin/", app.adminMiddleware(app.adminHandler))

	// ── Server ────────────────────────────────────────────────────────────
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	srv := &http.Server{
		Addr:         ":" + port,
		Handler:      mux,
		ReadTimeout:  15 * time.Second,
		WriteTimeout: 30 * time.Second,
		IdleTimeout:  60 * time.Second,
	}

	log.Printf("VoluntárioCOVID19 listening on :%s", port)
	log.Fatal(srv.ListenAndServe())
}

// buildTemplates parses each page template together with common.html and
// returns a map of page-name → *template.Template.
func buildTemplates() map[string]*template.Template {
	funcMap := template.FuncMap{
		"age":     calcAge,
		"join":    strings.Join,
		"formatDate": func(t time.Time) string {
			if t.IsZero() {
				return ""
			}
			return t.Format("2006-01-02")
		},
		"conducaoStr": func(b bool) string {
			if b {
				return "Sim"
			}
			return "Não"
		},
		"genderStr": func(g string) string {
			switch g {
			case "M":
				return "Masculino"
			case "F":
				return "Feminino"
			default:
				return "Outro"
			}
		},
		"add": func(a, b int) int { return a + b },
		"locationStr": func(distrito, concelho, freguesia string) string {
			parts := []string{}
			if freguesia != "" {
				parts = append(parts, freguesia)
			}
			if concelho != "" {
				parts = append(parts, concelho)
			}
			if distrito != "" {
				parts = append(parts, distrito)
			}
			return strings.Join(parts, ", ")
		},
		"safeHTML": func(s string) template.HTML {
			return template.HTML(s)
		},
		"printf": fmt.Sprintf,
	}

	pages := []string{
		"home",
		"volunteers",
		"institutions",
		"volunteer_profile",
		"institution_profile",
		"register_volunteer",
		"register_institution",
		"settings_volunteer",
		"settings_institution",
		"admin",
	}

	tmpls := make(map[string]*template.Template, len(pages))
	for _, p := range pages {
		t, err := template.New("").Funcs(funcMap).ParseFS(
			templateFS,
			"templates/common.html",
			"templates/"+p+".html",
		)
		if err != nil {
			log.Fatalf("parse template %s: %v", p, err)
		}
		tmpls[p] = t
	}
	return tmpls
}
