package main

import "time"

// Volunteer represents a volunteer user.
type Volunteer struct {
	ID              int
	CC              string
	Username        string
	Email           string
	Nome            string
	Apelido         string
	Nascimento      time.Time
	Telemovel       string
	Conducao        bool
	Verificado      bool
	ImagePath       string
	Biografia       string
	Genero          string
	Interesses      string
	PopAlvo         string
	Disponibilidade string
	Concelho        string
	Distrito        string
	Freguesia       string
}

// Institution represents an institution user.
type Institution struct {
	ID                 int
	Nome               string
	NomeRepresentante  string
	EmailRepresentante string
	Email              string
	Telefone           string
	Morada             string
	Concelho           string
	Distrito           string
	Freguesia          string
	Descricao          string
	Tipo               string
	Verificado         bool
	ImagePath          string
}

// SessionUser holds the authenticated user info stored in the session cookie.
type SessionUser struct {
	LoggedIn  bool
	UserID    int
	Email     string
	UserType  string // "Vol" or "Inst"
	ImagePath string
	Username  string // volunteer username or institution name
}

// Base contains common fields for all page data structs.
type Base struct {
	Session    SessionUser
	Error      string
	Success    string
	LoginError string
}

// HomeData is the page data for the home/landing page.
type HomeData struct{ Base }

// VolunteersData is the page data for the volunteer listing page.
type VolunteersData struct {
	Base
	Volunteers []Volunteer
}

// InstitutionsData is the page data for the institution listing page.
type InstitutionsData struct {
	Base
	Institutions []Institution
}

// VolunteerProfileData is the page data for a volunteer's profile page.
type VolunteerProfileData struct {
	Base
	Volunteer    *Volunteer
	IsOwnProfile bool
	Incomplete   []string
}

// InstitutionProfileData is the page data for an institution's profile page.
type InstitutionProfileData struct {
	Base
	Institution  *Institution
	IsOwnProfile bool
	Incomplete   []string
}

// RegErrors holds field-level registration error messages.
type RegErrors struct {
	Username string
	Email    string
	CC       string
	Password string
	General  string
}

// RegVolForm holds submitted form values (repopulates form on error).
type RegVolForm struct {
	Username  string
	Nome      string
	Apelido   string
	Nascimento string
	CC        string
	Email     string
}

// RegisterVolunteerData is the page data for the volunteer registration page.
type RegisterVolunteerData struct {
	Base
	Errors   *RegErrors
	FormData *RegVolForm
}

// RegInstForm holds submitted institution form values.
type RegInstForm struct {
	Nome               string
	NomeRepresentante  string
	EmailRepresentante string
	Email              string
}

// RegisterInstitutionData is the page data for the institution registration page.
type RegisterInstitutionData struct {
	Base
	Errors   *RegErrors
	FormData *RegInstForm
}

// SettingsVolunteerData is the page data for the volunteer settings page.
type SettingsVolunteerData struct {
	Base
	Volunteer *Volunteer
}

// SettingsInstitutionData is the page data for the institution settings page.
type SettingsInstitutionData struct {
	Base
	Institution *Institution
}

// AdminData is the page data for the admin panel.
type AdminData struct {
	Base
	SearchType   string // "Vol" or "Inst"
	SearchQuery  string
	Volunteers   []Volunteer
	Institutions []Institution
	// Volunteer filter state
	GenderM    bool
	GenderF    bool
	GenderO    bool
	LicenseVal string
	AgeRange   string
}
