package main

import (
	"errors"
	"fmt"
	"log"
	"net/http"
	"strconv"
	"strings"
	"time"
)

// ─── Helpers ────────────────────────────────────────────────────────────────

func (app *App) render(w http.ResponseWriter, tmplName string, data interface{}) {
	t, ok := app.tmpls[tmplName]
	if !ok {
		log.Printf("template not found: %s", tmplName)
		http.Error(w, "Internal Server Error", http.StatusInternalServerError)
		return
	}
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	if err := t.ExecuteTemplate(w, "base", data); err != nil {
		log.Printf("template %s: %v", tmplName, err)
	}
}

func (app *App) requireLogin(w http.ResponseWriter, r *http.Request) (SessionUser, bool) {
	u := app.sessions.GetUser(r)
	if !u.LoggedIn {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return SessionUser{}, false
	}
	return u, true
}

// flashAndRedirect stores a flash message and redirects.
func (app *App) flashAndRedirect(w http.ResponseWriter, r *http.Request, msg, dest string) {
	app.sessions.AddFlash(w, r, msg)
	http.Redirect(w, r, dest, http.StatusSeeOther)
}

// collectFlashes reads and categorises flash messages into success/error strings.
func (app *App) collectFlashes(w http.ResponseWriter, r *http.Request) (success, errMsg string) {
	for _, f := range app.sessions.GetFlashes(w, r) {
		if strings.HasPrefix(f, "success:") {
			success = strings.TrimPrefix(f, "success:")
		} else {
			errMsg = f
		}
	}
	return
}

// ─── Home ───────────────────────────────────────────────────────────────────

func (app *App) homeHandler(w http.ResponseWriter, r *http.Request) {
	if r.URL.Path != "/" {
		http.NotFound(w, r)
		return
	}
	sess := app.sessions.GetUser(r)
	app.render(w, "home", HomeData{Base: Base{Session: sess}})
}

// ─── Auth ────────────────────────────────────────────────────────────────────

func (app *App) loginHandler(w http.ResponseWriter, r *http.Request) {
	email := strings.TrimSpace(r.FormValue("LoginEmail"))
	password := r.FormValue("LoginPass")

	u, err := app.db.AuthenticateUser(email, password)
	if err != nil {
		loginErr := "*Email ou palavra-passe incorretos*"
		if errors.Is(err, ErrUserNotFound) {
			loginErr = "*Utilizador não encontrado*"
		}
		// Redirect back to referrer with the error in a flash
		app.sessions.AddFlash(w, r, "loginerr:"+loginErr)
		ref := r.Referer()
		if ref == "" {
			ref = "/"
		}
		http.Redirect(w, r, ref, http.StatusSeeOther)
		return
	}

	if err := app.sessions.SetUser(w, r, u); err != nil {
		http.Error(w, "session error", http.StatusInternalServerError)
		return
	}
	http.Redirect(w, r, "/", http.StatusSeeOther)
}

func (app *App) logoutHandler(w http.ResponseWriter, r *http.Request) {
	app.sessions.Clear(w, r)
	http.Redirect(w, r, "/", http.StatusSeeOther)
}

// ─── Listings ────────────────────────────────────────────────────────────────

func (app *App) volunteersHandler(w http.ResponseWriter, r *http.Request) {
	sess := app.sessions.GetUser(r)
	vols, err := app.db.GetAllVolunteers()
	if err != nil {
		log.Printf("GetAllVolunteers: %v", err)
	}
	app.render(w, "volunteers", VolunteersData{
		Base:       Base{Session: sess},
		Volunteers: vols,
	})
}

func (app *App) institutionsHandler(w http.ResponseWriter, r *http.Request) {
	sess := app.sessions.GetUser(r)
	insts, err := app.db.GetAllInstitutions()
	if err != nil {
		log.Printf("GetAllInstitutions: %v", err)
	}
	app.render(w, "institutions", InstitutionsData{
		Base:         Base{Session: sess},
		Institutions: insts,
	})
}

// ─── Profiles ────────────────────────────────────────────────────────────────

func (app *App) volunteerProfileHandler(w http.ResponseWriter, r *http.Request) {
	username := r.PathValue("username")
	sess := app.sessions.GetUser(r)

	// Consume login error flash if present
	loginErr := ""
	for _, f := range app.sessions.GetFlashes(w, r) {
		if strings.HasPrefix(f, "loginerr:") {
			loginErr = strings.TrimPrefix(f, "loginerr:")
		}
	}

	vol, err := app.db.GetVolunteerByUsername(username)
	if err != nil || vol == nil {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return
	}

	isOwn := sess.LoggedIn && sess.Email == vol.Email
	var incomplete []string
	if isOwn {
		if vol.Genero == "" {
			incomplete = append(incomplete, "Género")
		}
		if vol.Distrito == "" {
			incomplete = append(incomplete, "Distrito")
		}
		if vol.Biografia == "" {
			incomplete = append(incomplete, "Biografia")
		}
		if vol.Interesses == "" {
			incomplete = append(incomplete, "Interesses")
		}
	}

	app.render(w, "volunteer_profile", VolunteerProfileData{
		Base:         Base{Session: sess, LoginError: loginErr},
		Volunteer:    vol,
		IsOwnProfile: isOwn,
		Incomplete:   incomplete,
	})
}

func (app *App) institutionProfileHandler(w http.ResponseWriter, r *http.Request) {
	idStr := r.PathValue("id")
	id, err := strconv.Atoi(idStr)
	if err != nil {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return
	}
	sess := app.sessions.GetUser(r)

	loginErr := ""
	for _, f := range app.sessions.GetFlashes(w, r) {
		if strings.HasPrefix(f, "loginerr:") {
			loginErr = strings.TrimPrefix(f, "loginerr:")
		}
	}

	inst, err := app.db.GetInstitutionByID(id)
	if err != nil || inst == nil {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return
	}

	isOwn := sess.LoggedIn && sess.Email == inst.Email
	var incomplete []string
	if isOwn {
		if inst.Telefone == "" {
			incomplete = append(incomplete, "Telefone")
		}
		if inst.Distrito == "" {
			incomplete = append(incomplete, "Distrito")
		}
		if inst.Descricao == "" {
			incomplete = append(incomplete, "Descrição")
		}
	}

	app.render(w, "institution_profile", InstitutionProfileData{
		Base:         Base{Session: sess, LoginError: loginErr},
		Institution:  inst,
		IsOwnProfile: isOwn,
		Incomplete:   incomplete,
	})
}

func (app *App) myProfileHandler(w http.ResponseWriter, r *http.Request) {
	u, ok := app.requireLogin(w, r)
	if !ok {
		return
	}
	if u.UserType == "Vol" {
		http.Redirect(w, r, "/volunteer/"+u.Username, http.StatusSeeOther)
	} else {
		http.Redirect(w, r, fmt.Sprintf("/institution/%d", u.UserID), http.StatusSeeOther)
	}
}

// ─── Registration ─────────────────────────────────────────────────────────────

func (app *App) registerVolunteerFormHandler(w http.ResponseWriter, r *http.Request) {
	sess := app.sessions.GetUser(r)
	if sess.LoggedIn {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return
	}
	loginErr := ""
	for _, f := range app.sessions.GetFlashes(w, r) {
		if strings.HasPrefix(f, "loginerr:") {
			loginErr = strings.TrimPrefix(f, "loginerr:")
		}
	}
	app.render(w, "register_volunteer", RegisterVolunteerData{
		Base: Base{Session: sess, LoginError: loginErr},
	})
}

func (app *App) registerVolunteerHandler(w http.ResponseWriter, r *http.Request) {
	sess := app.sessions.GetUser(r)
	if sess.LoggedIn {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return
	}

	form := &RegVolForm{
		Username:   strings.TrimSpace(r.FormValue("RegistoUsername")),
		Nome:       strings.TrimSpace(r.FormValue("RegistoNome")),
		Apelido:    strings.TrimSpace(r.FormValue("RegistoApelido")),
		Nascimento: r.FormValue("RegistoNascimento"),
		CC:         strings.TrimSpace(r.FormValue("RegistoCC")),
		Email:      strings.TrimSpace(r.FormValue("RegistoEmail")),
	}
	pass := r.FormValue("RegistoPass")
	confPass := r.FormValue("RegistoConfPass")

	errs := &RegErrors{}
	hasErr := false

	if pass != confPass {
		errs.Password = "Palavras-passe não correspondem!"
		hasErr = true
	}
	if !hasErr {
		if ok, _ := app.db.VolunteerUsernameExists(form.Username); ok {
			errs.Username = "Username já existe!"
			hasErr = true
		}
		if ok, _ := app.db.VolunteerCCExists(form.CC); ok {
			errs.CC = "Cartão Cidadão já está registado!"
			hasErr = true
		}
		if ok, _ := app.db.EmailExistsAnywhere(form.Email); ok {
			errs.Email = "Email já tem uma conta associada!"
			hasErr = true
		}
	}

	if hasErr {
		app.render(w, "register_volunteer", RegisterVolunteerData{
			Base: Base{Session: sess}, Errors: errs, FormData: form,
		})
		return
	}

	if err := app.db.CreateVolunteer(form.CC, form.Username, form.Email, pass, form.Nome, form.Apelido, form.Nascimento); err != nil {
		log.Printf("CreateVolunteer: %v", err)
		app.render(w, "register_volunteer", RegisterVolunteerData{
			Base:     Base{Session: sess, Error: "Erro inesperado. Tente novamente."},
			FormData: form,
		})
		return
	}

	// Auto-login
	u, _ := app.db.AuthenticateUser(form.Email, pass)
	app.sessions.SetUser(w, r, u)
	http.Redirect(w, r, "/profile", http.StatusSeeOther)
}

func (app *App) registerInstitutionFormHandler(w http.ResponseWriter, r *http.Request) {
	sess := app.sessions.GetUser(r)
	if sess.LoggedIn {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return
	}
	loginErr := ""
	for _, f := range app.sessions.GetFlashes(w, r) {
		if strings.HasPrefix(f, "loginerr:") {
			loginErr = strings.TrimPrefix(f, "loginerr:")
		}
	}
	app.render(w, "register_institution", RegisterInstitutionData{
		Base: Base{Session: sess, LoginError: loginErr},
	})
}

func (app *App) registerInstitutionHandler(w http.ResponseWriter, r *http.Request) {
	sess := app.sessions.GetUser(r)
	if sess.LoggedIn {
		http.Redirect(w, r, "/", http.StatusSeeOther)
		return
	}

	form := &RegInstForm{
		Nome:               strings.TrimSpace(r.FormValue("RegistoNome")),
		NomeRepresentante:  strings.TrimSpace(r.FormValue("RegistoNomeRep")),
		EmailRepresentante: strings.TrimSpace(r.FormValue("RegistoEmailRep")),
		Email:              strings.TrimSpace(r.FormValue("RegistoEmail")),
	}
	telefone := strings.TrimSpace(r.FormValue("RegistoTelefone"))
	morada := strings.TrimSpace(r.FormValue("RegistoMorada"))
	tipo := strings.TrimSpace(r.FormValue("RegistoTipo"))
	pass := r.FormValue("RegistoPass")
	confPass := r.FormValue("RegistoConfPass")

	errs := &RegErrors{}
	hasErr := false

	if pass != confPass {
		errs.Password = "Palavras-passe não correspondem!"
		hasErr = true
	}
	if !hasErr {
		if ok, _ := app.db.EmailExistsAnywhere(form.Email); ok {
			errs.Email = "Email já tem uma conta associada!"
			hasErr = true
		}
	}

	if hasErr {
		app.render(w, "register_institution", RegisterInstitutionData{
			Base: Base{Session: sess}, Errors: errs, FormData: form,
		})
		return
	}

	if err := app.db.CreateInstitution(form.Nome, form.NomeRepresentante, form.EmailRepresentante, form.Email, pass, telefone, morada, tipo); err != nil {
		log.Printf("CreateInstitution: %v", err)
		app.render(w, "register_institution", RegisterInstitutionData{
			Base:     Base{Session: sess, Error: "Erro inesperado. Tente novamente."},
			FormData: form,
		})
		return
	}

	u, _ := app.db.AuthenticateUser(form.Email, pass)
	app.sessions.SetUser(w, r, u)
	http.Redirect(w, r, "/profile", http.StatusSeeOther)
}

// ─── Settings ─────────────────────────────────────────────────────────────────

func (app *App) settingsHandler(w http.ResponseWriter, r *http.Request) {
	u, ok := app.requireLogin(w, r)
	if !ok {
		return
	}
	success, errMsg := app.collectFlashes(w, r)

	if u.UserType == "Vol" {
		vol, err := app.db.GetVolunteerByEmail(u.Email)
		if err != nil || vol == nil {
			http.Redirect(w, r, "/", http.StatusSeeOther)
			return
		}
		app.render(w, "settings_volunteer", SettingsVolunteerData{
			Base:      Base{Session: u, Success: success, Error: errMsg},
			Volunteer: vol,
		})
	} else {
		inst, err := app.db.GetInstitutionByEmail(u.Email)
		if err != nil || inst == nil {
			http.Redirect(w, r, "/", http.StatusSeeOther)
			return
		}
		app.render(w, "settings_institution", SettingsInstitutionData{
			Base:        Base{Session: u, Success: success, Error: errMsg},
			Institution: inst,
		})
	}
}

func (app *App) updateProfileHandler(w http.ResponseWriter, r *http.Request) {
	u, ok := app.requireLogin(w, r)
	if !ok {
		return
	}

	if u.UserType == "Vol" {
		fields := map[string]string{
			"username":        strings.TrimSpace(r.FormValue("alterarUsername")),
			"nome":            strings.TrimSpace(r.FormValue("alterarNome")),
			"apelido":         strings.TrimSpace(r.FormValue("alterarApelido")),
			"biografia":       strings.TrimSpace(r.FormValue("alterarBiografia")),
			"nascimento":      r.FormValue("alterarNascimento"),
			"genero":          r.FormValue("alterarGenero"),
			"interesses":      strings.TrimSpace(r.FormValue("alterarIntresses")),
			"pop_alvo":        strings.TrimSpace(r.FormValue("alterarPopAlvo")),
			"disponibilidade": strings.TrimSpace(r.FormValue("alterarDisponibilidade")),
		}
		if err := app.db.UpdateVolunteerProfile(u.Email, fields); err != nil {
			app.flashAndRedirect(w, r, "Erro ao atualizar perfil: "+err.Error(), "/settings")
			return
		}
		// Update username in session if changed
		if newUsername := fields["username"]; newUsername != "" && newUsername != u.Username {
			u.Username = newUsername
			app.sessions.SetUser(w, r, u)
		}
	} else {
		fields := map[string]string{
			"nome":               strings.TrimSpace(r.FormValue("alterarNome")),
			"nome_representante": strings.TrimSpace(r.FormValue("alterarNomeRep")),
			"descricao":          strings.TrimSpace(r.FormValue("alterarDescricao")),
			"tipo":               strings.TrimSpace(r.FormValue("alterarTipo")),
		}
		if err := app.db.UpdateInstitutionProfile(u.Email, fields); err != nil {
			app.flashAndRedirect(w, r, "Erro ao atualizar perfil: "+err.Error(), "/settings")
			return
		}
	}
	app.flashAndRedirect(w, r, "success:Perfil atualizado com sucesso!", "/settings")
}

func (app *App) updateDataHandler(w http.ResponseWriter, r *http.Request) {
	u, ok := app.requireLogin(w, r)
	if !ok {
		return
	}

	if u.UserType == "Vol" {
		conducaoStr := r.FormValue("alterarConducao")
		fields := map[string]string{
			"distrito":  strings.TrimSpace(r.FormValue("alterarDistrito")),
			"concelho":  strings.TrimSpace(r.FormValue("alterarConcelho")),
			"freguesia": strings.TrimSpace(r.FormValue("alterarFreguesia")),
			"telemovel": strings.TrimSpace(r.FormValue("alterarTelemovel")),
			"cc":        strings.TrimSpace(r.FormValue("alterarCC")),
		}
		if conducaoStr == "1" || strings.EqualFold(conducaoStr, "true") || strings.EqualFold(conducaoStr, "sim") {
			fields["conducao"] = "true"
		} else if conducaoStr == "0" || strings.EqualFold(conducaoStr, "false") || strings.EqualFold(conducaoStr, "não") {
			fields["conducao"] = "false"
		}
		if err := app.db.UpdateVolunteerProfile(u.Email, fields); err != nil {
			app.flashAndRedirect(w, r, "Erro ao atualizar dados: "+err.Error(), "/settings")
			return
		}
	} else {
		fields := map[string]string{
			"telefone":           strings.TrimSpace(r.FormValue("alterarTelefone")),
			"morada":             strings.TrimSpace(r.FormValue("alterarMorada")),
			"distrito":           strings.TrimSpace(r.FormValue("alterarDistrito")),
			"concelho":           strings.TrimSpace(r.FormValue("alterarConcelho")),
			"freguesia":          strings.TrimSpace(r.FormValue("alterarFreguesia")),
			"email_representante": strings.TrimSpace(r.FormValue("alterarEmailRep")),
		}
		if err := app.db.UpdateInstitutionProfile(u.Email, fields); err != nil {
			app.flashAndRedirect(w, r, "Erro ao atualizar dados: "+err.Error(), "/settings")
			return
		}
	}
	app.flashAndRedirect(w, r, "success:Dados atualizados com sucesso!", "/settings")
}

func (app *App) updatePasswordHandler(w http.ResponseWriter, r *http.Request) {
	u, ok := app.requireLogin(w, r)
	if !ok {
		return
	}
	current := r.FormValue("alterarPassAtual")
	newPass := r.FormValue("alterarPassNova")
	confPass := r.FormValue("alterarPassNovaConf")

	if newPass != confPass {
		app.flashAndRedirect(w, r, "As palavras-passe não correspondem.", "/settings")
		return
	}
	if len(newPass) < 6 {
		app.flashAndRedirect(w, r, "A nova palavra-passe deve ter pelo menos 6 caracteres.", "/settings")
		return
	}

	var err error
	if u.UserType == "Vol" {
		err = app.db.UpdateVolunteerPassword(u.Email, current, newPass)
	} else {
		err = app.db.UpdateInstitutionPassword(u.Email, current, newPass)
	}

	if err != nil {
		if errors.Is(err, ErrInvalidCredentials) {
			app.flashAndRedirect(w, r, "Palavra-passe atual incorreta.", "/settings")
		} else {
			app.flashAndRedirect(w, r, "Erro ao atualizar palavra-passe.", "/settings")
		}
		return
	}
	app.flashAndRedirect(w, r, "success:Palavra-passe alterada com sucesso!", "/settings")
}

func (app *App) updateEmailHandler(w http.ResponseWriter, r *http.Request) {
	u, ok := app.requireLogin(w, r)
	if !ok {
		return
	}
	newEmail := strings.TrimSpace(r.FormValue("alterarEmail"))
	confEmail := strings.TrimSpace(r.FormValue("alterarEmailConf"))
	pass := r.FormValue("alterarEmailPass")

	if newEmail != confEmail {
		app.flashAndRedirect(w, r, "Os emails não correspondem.", "/settings")
		return
	}

	var err error
	if u.UserType == "Vol" {
		err = app.db.UpdateVolunteerEmail(u.Email, newEmail, pass)
	} else {
		err = app.db.UpdateInstitutionEmail(u.Email, newEmail, pass)
	}

	if err != nil {
		if errors.Is(err, ErrInvalidCredentials) {
			app.flashAndRedirect(w, r, "Palavra-passe incorreta.", "/settings")
		} else {
			app.flashAndRedirect(w, r, err.Error(), "/settings")
		}
		return
	}

	app.sessions.UpdateEmail(w, r, newEmail)
	app.flashAndRedirect(w, r, "success:Email atualizado com sucesso!", "/settings")
}

func (app *App) updatePhotoHandler(w http.ResponseWriter, r *http.Request) {
	u, ok := app.requireLogin(w, r)
	if !ok {
		return
	}

	if err := r.ParseMultipartForm(5 << 20); err != nil { // 5 MB
		app.flashAndRedirect(w, r, "Ficheiro demasiado grande (máx 5MB).", "/settings")
		return
	}

	file, header, err := r.FormFile("carregarFoto")
	if err != nil {
		app.flashAndRedirect(w, r, "Nenhum ficheiro foi enviado.", "/settings")
		return
	}
	defer file.Close()

	ct := header.Header.Get("Content-Type")
	if !strings.HasPrefix(ct, "image/") {
		app.flashAndRedirect(w, r, "Apenas ficheiros de imagem são permitidos.", "/settings")
		return
	}

	prefix := "volunteers"
	if u.UserType == "Inst" {
		prefix = "institutions"
	}

	path, err := app.storage.Save(file, header, prefix)
	if err != nil {
		log.Printf("upload photo: %v", err)
		app.flashAndRedirect(w, r, "Erro ao guardar imagem.", "/settings")
		return
	}

	if u.UserType == "Vol" {
		app.db.UpdateVolunteerPhoto(u.Email, path)
	} else {
		app.db.UpdateInstitutionPhoto(u.Email, path)
	}

	app.sessions.UpdateImagePath(w, r, path)
	app.flashAndRedirect(w, r, "success:Foto atualizada com sucesso!", "/settings")
}

// ─── Admin ───────────────────────────────────────────────────────────────────

// adminMiddleware protects the admin panel with HTTP Basic Auth.
func (app *App) adminMiddleware(next http.HandlerFunc) http.HandlerFunc {
	adminUser := envOrDefault("ADMIN_USERNAME", "admin")
	adminPass := envOrDefault("ADMIN_PASSWORD", "")

	return func(w http.ResponseWriter, r *http.Request) {
		if adminPass == "" {
			// No password set — require explicit configuration
			http.Error(w, "Admin not configured. Set ADMIN_PASSWORD env var.", http.StatusServiceUnavailable)
			return
		}
		user, pass, ok := r.BasicAuth()
		if !ok || user != adminUser || pass != adminPass {
			w.Header().Set("WWW-Authenticate", `Basic realm="VC19 Admin"`)
			http.Error(w, "Unauthorized", http.StatusUnauthorized)
			return
		}
		next(w, r)
	}
}

func (app *App) adminHandler(w http.ResponseWriter, r *http.Request) {
	searchType := r.FormValue("obj")
	if searchType == "" {
		searchType = r.URL.Query().Get("obj")
	}
	if searchType != "Vol" && searchType != "Inst" {
		searchType = "Vol"
	}

	data := AdminData{
		Base:       Base{Session: app.sessions.GetUser(r)},
		SearchType: searchType,
	}

	if r.Method == http.MethodPost {
		data.SearchQuery = strings.TrimSpace(r.FormValue("adminSearch"))
		if searchType == "Inst" {
			data.SearchQuery = strings.TrimSpace(r.FormValue("adminSearchInst"))
		}

		if searchType == "Vol" {
			var genders []string
			if r.FormValue("adminCheckMasc") == "M" {
				genders = append(genders, "M")
				data.GenderM = true
			}
			if r.FormValue("adminCheckFem") == "F" {
				genders = append(genders, "F")
				data.GenderF = true
			}
			if r.FormValue("adminCheckOutro") == "O" {
				genders = append(genders, "O")
				data.GenderO = true
			}

			data.LicenseVal = r.FormValue("adminCartaConducao")
			data.AgeRange = r.FormValue("adminIdade")

			hasLicense := ""
			switch data.LicenseVal {
			case "T":
				hasLicense = "yes"
			case "F":
				hasLicense = "no"
			}

			vols, err := app.db.SearchVolunteers(data.SearchQuery, genders, hasLicense, data.AgeRange)
			if err != nil {
				log.Printf("SearchVolunteers: %v", err)
			}
			data.Volunteers = vols
		} else {
			insts, err := app.db.SearchInstitutions(data.SearchQuery)
			if err != nil {
				log.Printf("SearchInstitutions: %v", err)
			}
			data.Institutions = insts
		}
	} else {
		// GET: show all
		if searchType == "Vol" {
			vols, _ := app.db.SearchVolunteers("", nil, "", "")
			data.Volunteers = vols
		} else {
			insts, _ := app.db.SearchInstitutions("")
			data.Institutions = insts
		}
	}

	app.render(w, "admin", data)
}

// ─── Template helpers (also registered as funcs) ─────────────────────────────

func calcAge(t time.Time) string {
	if t.IsZero() {
		return ""
	}
	years := int(time.Since(t).Hours() / (24 * 365.25))
	return fmt.Sprintf("%d Anos", years)
}
