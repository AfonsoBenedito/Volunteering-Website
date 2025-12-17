package main

import (
	"net/http"

	"github.com/gorilla/sessions"
)

// SessionManager wraps gorilla/sessions with helper methods.
// Sessions are stored entirely in encrypted+signed cookies — no server state,
// so this works correctly with multiple Cloud Run instances.
type SessionManager struct {
	store *sessions.CookieStore
}

func newSessionManager(key string) *SessionManager {
	store := sessions.NewCookieStore([]byte(key))
	store.Options = &sessions.Options{
		Path:     "/",
		MaxAge:   86400 * 7, // 7 days
		HttpOnly: true,
		SameSite: http.SameSiteLaxMode,
	}
	return &SessionManager{store: store}
}

func (sm *SessionManager) get(r *http.Request) (*sessions.Session, error) {
	return sm.store.Get(r, "vc19_session")
}

// GetUser returns the SessionUser from the session cookie.
// Returns an empty (LoggedIn=false) SessionUser on error.
func (sm *SessionManager) GetUser(r *http.Request) SessionUser {
	s, err := sm.get(r)
	if err != nil || s.IsNew {
		return SessionUser{}
	}
	loggedIn, _ := s.Values["logged_in"].(bool)
	if !loggedIn {
		return SessionUser{}
	}
	return SessionUser{
		LoggedIn:  true,
		UserID:    asInt(s.Values["user_id"]),
		Email:     asString(s.Values["email"]),
		UserType:  asString(s.Values["user_type"]),
		ImagePath: asString(s.Values["image_path"]),
		Username:  asString(s.Values["username"]),
	}
}

// SetUser stores the user in the session cookie.
func (sm *SessionManager) SetUser(w http.ResponseWriter, r *http.Request, u SessionUser) error {
	s, err := sm.get(r)
	if err != nil {
		return err
	}
	s.Values["logged_in"] = u.LoggedIn
	s.Values["user_id"] = u.UserID
	s.Values["email"] = u.Email
	s.Values["user_type"] = u.UserType
	s.Values["image_path"] = u.ImagePath
	s.Values["username"] = u.Username
	return s.Save(r, w)
}

// Clear destroys the session cookie.
func (sm *SessionManager) Clear(w http.ResponseWriter, r *http.Request) error {
	s, err := sm.get(r)
	if err != nil {
		return err
	}
	s.Options.MaxAge = -1
	return s.Save(r, w)
}

// AddFlash stores a flash message (survives one redirect).
func (sm *SessionManager) AddFlash(w http.ResponseWriter, r *http.Request, msg string) {
	s, err := sm.get(r)
	if err != nil {
		return
	}
	s.AddFlash(msg)
	s.Save(r, w)
}

// GetFlashes retrieves and clears all flash messages.
func (sm *SessionManager) GetFlashes(w http.ResponseWriter, r *http.Request) []string {
	s, err := sm.get(r)
	if err != nil {
		return nil
	}
	raw := s.Flashes()
	s.Save(r, w)
	out := make([]string, 0, len(raw))
	for _, v := range raw {
		if str, ok := v.(string); ok {
			out = append(out, str)
		}
	}
	return out
}

// UpdateImagePath updates the image_path stored in the session.
func (sm *SessionManager) UpdateImagePath(w http.ResponseWriter, r *http.Request, path string) {
	u := sm.GetUser(r)
	if !u.LoggedIn {
		return
	}
	u.ImagePath = path
	sm.SetUser(w, r, u)
}

// UpdateEmail updates the email stored in the session.
func (sm *SessionManager) UpdateEmail(w http.ResponseWriter, r *http.Request, email string) {
	u := sm.GetUser(r)
	if !u.LoggedIn {
		return
	}
	u.Email = email
	sm.SetUser(w, r, u)
}

// helpers for safe type assertions from interface{}
func asString(v interface{}) string {
	s, _ := v.(string)
	return s
}
func asInt(v interface{}) int {
	switch n := v.(type) {
	case int:
		return n
	case int64:
		return int(n)
	}
	return 0
}
