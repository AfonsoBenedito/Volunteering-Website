package main

import (
	"database/sql"
	"errors"
	"fmt"
	"log"
	"os"
	"strings"
	"time"

	_ "modernc.org/sqlite"
	"golang.org/x/crypto/bcrypt"
)

// Database wraps *sql.DB and provides typed query methods.
type Database struct{ db *sql.DB }

const sqliteSchema = `
CREATE TABLE IF NOT EXISTS volunteers (
	id              INTEGER PRIMARY KEY AUTOINCREMENT,
	cc              TEXT NOT NULL UNIQUE,
	username        TEXT NOT NULL UNIQUE,
	email           TEXT NOT NULL UNIQUE,
	pass            TEXT NOT NULL,
	nome            TEXT NOT NULL,
	apelido         TEXT NOT NULL,
	nascimento      TEXT NOT NULL,
	conducao        INTEGER DEFAULT 0,
	verificado      INTEGER DEFAULT 0,
	image_path      TEXT DEFAULT '/assets/Imagens/perfilDefault.png',
	biografia       TEXT,
	genero          TEXT,
	interesses      TEXT,
	pop_alvo        TEXT,
	disponibilidade TEXT,
	concelho        TEXT,
	distrito        TEXT,
	freguesia       TEXT,
	telemovel       TEXT,
	created_at      TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS institutions (
	id                   INTEGER PRIMARY KEY AUTOINCREMENT,
	nome                 TEXT NOT NULL,
	nome_representante   TEXT,
	email_representante  TEXT,
	email                TEXT NOT NULL UNIQUE,
	pass                 TEXT NOT NULL,
	telefone             TEXT,
	morada               TEXT,
	concelho             TEXT,
	distrito             TEXT,
	freguesia            TEXT,
	descricao            TEXT,
	tipo                 TEXT,
	verificado           INTEGER DEFAULT 0,
	image_path           TEXT DEFAULT '/assets/Imagens/perfilDefault.png',
	created_at           TEXT DEFAULT CURRENT_TIMESTAMP
);`

// seedPasswordHash is the bcrypt hash of "password123" at cost 12.
const seedPasswordHash = `$2a$12$v4WCuNUKf3KHi7amldcaTeT3NR/mnxQmREtezJ2OkHJq/6xG77K8y`

func envOrDefault(key, fallback string) string {
	if v := os.Getenv(key); v != "" {
		return v
	}
	return fallback
}

func newDatabase() *Database {
	// file::memory:?cache=shared lets all connections share the same in-memory DB.
	// SetMaxOpenConns(1) prevents the DB from being destroyed when the last connection closes.
	db, err := sql.Open("sqlite", "file::memory:?cache=shared")
	if err != nil {
		log.Fatalf("sql.Open: %v", err)
	}
	db.SetMaxOpenConns(1)

	if _, err = db.Exec(sqliteSchema); err != nil {
		log.Fatalf("init schema: %v", err)
	}
	if err = seedDatabase(db); err != nil {
		log.Fatalf("seed data: %v", err)
	}

	log.Println("Database initialised (ephemeral in-memory SQLite)")
	return &Database{db: db}
}

func seedDatabase(db *sql.DB) error {
	// Each row: cc, username, email, nome, apelido, nascimento, conducao, verificado,
	//           biografia, genero, interesses, pop_alvo, disponibilidade,
	//           concelho, distrito, freguesia, telemovel
	vols := [][]interface{}{
		{"12345678", "joao_silva", "joao.silva@example.com", "João", "Silva", "1995-05-15", 1, 1,
			"Apaixonado por ajudar a comunidade e fazer a diferença na vida das pessoas.",
			"M", "Ajuda a idosos, Distribuição de alimentos, Apoio escolar", "Idosos, Crianças",
			"Fins de semana e feriados", "Lisboa", "Lisboa", "Alvalade", "912000001"},
		{"87654321", "maria_santos", "maria.santos@example.com", "Maria", "Santos", "1992-08-22", 1, 1,
			"Voluntária há 5 anos, especializada em ações sociais e ambientais.",
			"F", "Meio ambiente, Apoio social, Eventos culturais", "Famílias, Jovens",
			"Tardes durante a semana", "Porto", "Porto", "Cedofeita", "912000002"},
		{"11223344", "carlos_oliveira", "carlos.oliveira@example.com", "Carlos", "Oliveira", "1988-12-03", 0, 1,
			"Engenheiro que dedica tempo livre a projetos de voluntariado técnico.",
			"M", "Tecnologia, Ensino, Manutenção", "Jovens, Adultos",
			"Noites e fins de semana", "Coimbra", "Coimbra", "Sé Nova", "912000003"},
		{"22334455", "ana_ferreira", "ana.ferreira@example.com", "Ana", "Ferreira", "1997-03-10", 0, 1,
			"Estudante de medicina com paixão pelo apoio a populações vulneráveis.",
			"F", "Saúde, Apoio a idosos, Primeiros socorros", "Idosos, Sem-abrigo",
			"Manhãs e fins de semana", "Braga", "Braga", "São Vítor", "912000004"},
		{"33445566", "pedro_costa", "pedro.costa@example.com", "Pedro", "Costa", "1990-07-19", 1, 1,
			"Empresário que acredita que a responsabilidade social começa em cada um de nós.",
			"M", "Empreendedorismo social, Formação profissional", "Jovens desempregados",
			"Fins de semana", "Faro", "Faro", "Faro (Sé)", "912000005"},
		{"44556677", "sofia_mendes", "sofia.mendes@example.com", "Sofia", "Mendes", "2000-01-25", 0, 1,
			"Recém-licenciada em psicologia, motivada a apoiar a saúde mental da comunidade.",
			"F", "Saúde mental, Apoio emocional, Grupos de jovens", "Jovens, Adultos",
			"Tardes e noites", "Setúbal", "Setúbal", "Setúbal (São Julião)", "912000006"},
		{"55667788", "rui_almeida", "rui.almeida@example.com", "Rui", "Almeida", "1985-09-14", 1, 1,
			"Professor aposentado que quer continuar a contribuir para a educação da sociedade.",
			"M", "Educação, Tutoria, Literacia digital", "Crianças, Idosos",
			"Manhãs de segunda a sexta", "Évora", "Évora", "Évora (Sé)", "912000007"},
		{"66778899", "inês_rodrigues", "ines.rodrigues@example.com", "Inês", "Rodrigues", "1993-11-08", 0, 0,
			"Nutricionista que quer ajudar famílias carenciadas a ter uma alimentação saudável.",
			"F", "Nutrição, Distribuição alimentar, Workshops de culinária", "Famílias, Crianças",
			"Fins de semana", "Aveiro", "Aveiro", "Aveiro", "912000008"},
		{"77889900", "miguel_lopes", "miguel_lopes@example.com", "Miguel", "Lopes", "1999-06-30", 1, 1,
			"Estudante de arquitetura com interesse em projetos de habitação social.",
			"M", "Habitação social, Reabilitação urbana, Apoio comunitário", "Famílias carenciadas",
			"Fins de semana e feriados", "Viseu", "Viseu", "Viseu", "912000009"},
		{"88990011", "catarina_nunes", "catarina_nunes@example.com", "Catarina", "Nunes", "1996-04-17", 0, 1,
			"Assistente social que quer fazer mais além do horário de trabalho.",
			"F", "Apoio social, Acompanhamento familiar, Inclusão social", "Idosos, Famílias",
			"Tardes de segunda a sexta", "Guimarães", "Braga", "Guimarães (Oliveira, São Paio)", "912000010"},
	}
	for _, v := range vols {
		_, err := db.Exec(`
			INSERT OR IGNORE INTO volunteers
				(cc, username, email, pass, nome, apelido, nascimento, conducao, verificado,
				 image_path, biografia, genero, interesses, pop_alvo, disponibilidade,
				 concelho, distrito, freguesia, telemovel)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)`,
			v[0], v[1], v[2], seedPasswordHash, v[3], v[4], v[5], v[6], v[7],
			"/assets/Imagens/perfilDefault.png",
			v[8], v[9], v[10], v[11], v[12], v[13], v[14], v[15], v[16],
		)
		if err != nil {
			return fmt.Errorf("seed volunteer %v: %w", v[1], err)
		}
	}

	// Each row: nome, nome_representante, email_representante, email,
	//           telefone, morada, concelho, distrito, freguesia, descricao, tipo, verificado
	insts := [][]interface{}{
		{"Cruz Vermelha Portuguesa", "Ana Costa", "ana.costa@cruzvermelha.pt", "contacto@cruzvermelha.pt",
			"213913600", "Jardim 9 de Abril, 1-5, 1249-083 Lisboa", "Lisboa", "Lisboa", "Misericórdia",
			"Organização humanitária que presta auxílio a pessoas em situação de vulnerabilidade social.",
			"Organização Humanitária", 1},
		{"Refood", "Pedro Martins", "pedro.martins@refood.pt", "info@refood.pt",
			"912345678", "Rua da Prata, 80, 1100-420 Lisboa", "Lisboa", "Lisboa", "Santa Maria Maior",
			"Movimento cívico que combate o desperdício alimentar através da redistribuição de excedentes.",
			"ONG Ambiental/Social", 1},
		{"Banco Alimentar Contra a Fome", "Sofia Rodrigues", "sofia.rodrigues@bancoalimentar.pt", "geral@bancoalimentar.pt",
			"217941200", "Rua de São Bento, 640, 1250-222 Lisboa", "Lisboa", "Lisboa", "São Bento",
			"Instituição de solidariedade social que luta contra o desperdício alimentar e a fome.",
			"Instituição de Solidariedade", 1},
		{"AMI - Assistência Médica Internacional", "Luís Fonseca", "luis.fonseca@ami.org.pt", "ami@ami.org.pt",
			"213969510", "Rua José do Patrocínio, 49, 1959-003 Lisboa", "Lisboa", "Lisboa", "Marvila",
			"ONG humanitária que atua em zonas de conflito e populações vulneráveis em Portugal e no mundo.",
			"ONG Humanitária", 1},
		{"Cáritas Portuguesa", "Marta Vieira", "marta.vieira@caritas.pt", "caritas@caritas.pt",
			"213460585", "Rua Afonso de Albuquerque, 6, 1350-003 Lisboa", "Lisboa", "Lisboa", "Campo de Ourique",
			"Organização da Igreja Católica que promove o desenvolvimento humano e a solidariedade social.",
			"Organização Religiosa/Social", 1},
		{"SOS Aldeia Solidária do Porto", "Ricardo Pinto", "ricardo.pinto@aldeia-porto.pt", "info@aldeia-porto.pt",
			"225508900", "Av. da Boavista, 1234, 4100-130 Porto", "Porto", "Porto", "Lordelo do Ouro",
			"Associação de apoio a famílias carenciadas e crianças em risco no Grande Porto.",
			"Associação de Solidariedade", 1},
		{"APPACDM Braga", "Teresa Azevedo", "teresa.azevedo@appacdm-braga.pt", "appacdm@appacdm-braga.pt",
			"253218190", "Av. Central, 100, 4710-228 Braga", "Braga", "Braga", "Braga (São José de São Lázaro)",
			"Associação que promove a inclusão social de pessoas com deficiência intelectual.",
			"Associação de Apoio Social", 1},
	}
	for _, inst := range insts {
		_, err := db.Exec(`
			INSERT OR IGNORE INTO institutions
				(nome, nome_representante, email_representante, email, pass, telefone, morada,
				 concelho, distrito, freguesia, descricao, tipo, verificado, image_path)
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)`,
			inst[0], inst[1], inst[2], inst[3], seedPasswordHash,
			inst[4], inst[5], inst[6], inst[7], inst[8], inst[9], inst[10], inst[11],
			"/assets/Imagens/perfilDefault.png",
		)
		if err != nil {
			return fmt.Errorf("seed institution %v: %w", inst[0], err)
		}
	}
	return nil
}

// ─── Auth ──────────────────────────────────────────────────────────────────

var ErrInvalidCredentials = errors.New("credenciais inválidas")
var ErrUserNotFound = errors.New("utilizador não encontrado")

// AuthenticateUser checks email + password against both tables and returns a
// populated SessionUser on success.
func (d *Database) AuthenticateUser(email, password string) (SessionUser, error) {
	// Try volunteers first
	var id int
	var hash, imagePath, username string
	err := d.db.QueryRow(
		`SELECT id, pass, image_path, username FROM volunteers WHERE email = ? LIMIT 1`, email,
	).Scan(&id, &hash, &imagePath, &username)
	if err == nil {
		if bcrypt.CompareHashAndPassword([]byte(hash), []byte(password)) != nil {
			return SessionUser{}, ErrInvalidCredentials
		}
		return SessionUser{
			LoggedIn: true, UserID: id, Email: email,
			UserType: "Vol", ImagePath: imagePath, Username: username,
		}, nil
	}
	if !errors.Is(err, sql.ErrNoRows) {
		return SessionUser{}, err
	}

	// Try institutions
	var instName string
	err = d.db.QueryRow(
		`SELECT id, pass, image_path, nome FROM institutions WHERE email = ? LIMIT 1`, email,
	).Scan(&id, &hash, &imagePath, &instName)
	if errors.Is(err, sql.ErrNoRows) {
		return SessionUser{}, ErrUserNotFound
	}
	if err != nil {
		return SessionUser{}, err
	}
	if bcrypt.CompareHashAndPassword([]byte(hash), []byte(password)) != nil {
		return SessionUser{}, ErrInvalidCredentials
	}
	return SessionUser{
		LoggedIn: true, UserID: id, Email: email,
		UserType: "Inst", ImagePath: imagePath, Username: instName,
	}, nil
}

// ─── Volunteers ────────────────────────────────────────────────────────────

const volunteerCols = `
	id, cc, username, email, nome, apelido, nascimento, COALESCE(telemovel,''),
	conducao, verificado, image_path, COALESCE(biografia,''), COALESCE(genero,''),
	COALESCE(interesses,''), COALESCE(pop_alvo,''), COALESCE(disponibilidade,''),
	COALESCE(concelho,''), COALESCE(distrito,''), COALESCE(freguesia,'')`

func scanVolunteer(row interface{ Scan(...interface{}) error }) (*Volunteer, error) {
	v := &Volunteer{}
	var nascimentoStr string
	var conducao, verificado int
	err := row.Scan(
		&v.ID, &v.CC, &v.Username, &v.Email, &v.Nome, &v.Apelido,
		&nascimentoStr, &v.Telemovel, &conducao, &verificado,
		&v.ImagePath, &v.Biografia, &v.Genero, &v.Interesses,
		&v.PopAlvo, &v.Disponibilidade, &v.Concelho, &v.Distrito, &v.Freguesia,
	)
	if err != nil {
		return nil, err
	}
	v.Conducao = conducao != 0
	v.Verificado = verificado != 0
	if nascimentoStr != "" {
		v.Nascimento, _ = time.Parse("2006-01-02", nascimentoStr)
	}
	return v, nil
}

func (d *Database) GetAllVolunteers() ([]Volunteer, error) {
	rows, err := d.db.Query(
		`SELECT` + volunteerCols + ` FROM volunteers ORDER BY id ASC`)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	var out []Volunteer
	for rows.Next() {
		v, err := scanVolunteer(rows)
		if err != nil {
			return nil, err
		}
		out = append(out, *v)
	}
	return out, rows.Err()
}

func (d *Database) GetVolunteerByUsername(username string) (*Volunteer, error) {
	row := d.db.QueryRow(
		`SELECT`+volunteerCols+` FROM volunteers WHERE username = ? LIMIT 1`, username)
	v, err := scanVolunteer(row)
	if errors.Is(err, sql.ErrNoRows) {
		return nil, nil
	}
	return v, err
}

func (d *Database) GetVolunteerByEmail(email string) (*Volunteer, error) {
	row := d.db.QueryRow(
		`SELECT`+volunteerCols+` FROM volunteers WHERE email = ? LIMIT 1`, email)
	v, err := scanVolunteer(row)
	if errors.Is(err, sql.ErrNoRows) {
		return nil, nil
	}
	return v, err
}

func (d *Database) VolunteerUsernameExists(username string) (bool, error) {
	var n int
	err := d.db.QueryRow(`SELECT COUNT(*) FROM volunteers WHERE username=?`, username).Scan(&n)
	return n > 0, err
}
func (d *Database) VolunteerCCExists(cc string) (bool, error) {
	var n int
	err := d.db.QueryRow(`SELECT COUNT(*) FROM volunteers WHERE cc=?`, cc).Scan(&n)
	return n > 0, err
}
func (d *Database) EmailExistsAnywhere(email string) (bool, error) {
	var n int
	err := d.db.QueryRow(
		`SELECT COUNT(*) FROM (SELECT 1 FROM volunteers WHERE email=? UNION ALL SELECT 1 FROM institutions WHERE email=?) t`,
		email, email).Scan(&n)
	return n > 0, err
}

func (d *Database) CreateVolunteer(cc, username, email, password, nome, apelido, nascimento string) error {
	hash, err := bcrypt.GenerateFromPassword([]byte(password), bcrypt.DefaultCost)
	if err != nil {
		return err
	}
	_, err = d.db.Exec(
		`INSERT INTO volunteers (cc, username, email, pass, nome, apelido, nascimento)
		 VALUES (?,?,?,?,?,?,?)`,
		cc, username, email, string(hash), nome, apelido, nascimento,
	)
	return err
}

// UpdateVolunteerProfile updates editable profile fields (any empty string is skipped).
func (d *Database) UpdateVolunteerProfile(email string, fields map[string]string) error {
	sets := []string{}
	args := []interface{}{}
	allowedProfileCols := map[string]bool{
		"username": true, "nome": true, "apelido": true, "biografia": true,
		"nascimento": true, "genero": true, "interesses": true, "pop_alvo": true,
		"disponibilidade": true, "distrito": true, "concelho": true, "freguesia": true,
		"telemovel": true, "cc": true, "conducao": true,
	}
	for col, val := range fields {
		if val == "" || !allowedProfileCols[col] {
			continue
		}
		// SQLite stores conducao as INTEGER; convert "true"/"false" strings.
		if col == "conducao" {
			if strings.EqualFold(val, "true") || val == "1" {
				val = "1"
			} else {
				val = "0"
			}
		}
		sets = append(sets, fmt.Sprintf("%s=?", col))
		args = append(args, val)
	}
	if len(sets) == 0 {
		return nil
	}
	args = append(args, email)
	_, err := d.db.Exec(
		fmt.Sprintf(`UPDATE volunteers SET %s WHERE email=?`, strings.Join(sets, ",")),
		args...,
	)
	return err
}

func (d *Database) UpdateVolunteerPassword(email, currentPassword, newPassword string) error {
	var hash string
	if err := d.db.QueryRow(`SELECT pass FROM volunteers WHERE email=?`, email).Scan(&hash); err != nil {
		return ErrUserNotFound
	}
	if bcrypt.CompareHashAndPassword([]byte(hash), []byte(currentPassword)) != nil {
		return ErrInvalidCredentials
	}
	newHash, err := bcrypt.GenerateFromPassword([]byte(newPassword), bcrypt.DefaultCost)
	if err != nil {
		return err
	}
	_, err = d.db.Exec(`UPDATE volunteers SET pass=? WHERE email=?`, string(newHash), email)
	return err
}

func (d *Database) UpdateVolunteerEmail(oldEmail, newEmail, password string) error {
	var hash string
	if err := d.db.QueryRow(`SELECT pass FROM volunteers WHERE email=?`, oldEmail).Scan(&hash); err != nil {
		return ErrUserNotFound
	}
	if bcrypt.CompareHashAndPassword([]byte(hash), []byte(password)) != nil {
		return ErrInvalidCredentials
	}
	exists, _ := d.EmailExistsAnywhere(newEmail)
	if exists {
		return errors.New("email já está em uso")
	}
	_, err := d.db.Exec(`UPDATE volunteers SET email=? WHERE email=?`, newEmail, oldEmail)
	return err
}

func (d *Database) UpdateVolunteerPhoto(email, imagePath string) error {
	_, err := d.db.Exec(`UPDATE volunteers SET image_path=? WHERE email=?`, imagePath, email)
	return err
}

// SearchVolunteers performs a filtered search for the admin panel.
func (d *Database) SearchVolunteers(query string, genders []string, hasLicense string, ageRange string) ([]Volunteer, error) {
	conditions := []string{"1=1"}
	args := []interface{}{}

	if query != "" {
		pattern := "%" + query + "%"
		// SQLite LIKE is case-insensitive for ASCII; pass the pattern once per field.
		conditions = append(conditions,
			`(username LIKE ? OR nome LIKE ? OR apelido LIKE ? OR cc LIKE ? OR email LIKE ?
			  OR COALESCE(telemovel,'') LIKE ? OR COALESCE(distrito,'') LIKE ?
			  OR COALESCE(concelho,'') LIKE ? OR COALESCE(freguesia,'') LIKE ?)`)
		args = append(args, pattern, pattern, pattern, pattern, pattern, pattern, pattern, pattern, pattern)
	}

	if len(genders) > 0 {
		placeholders := make([]string, 0, len(genders))
		for _, g := range genders {
			if g == "M" || g == "F" || g == "O" {
				placeholders = append(placeholders, "?")
				args = append(args, g)
			}
		}
		if len(placeholders) > 0 {
			conditions = append(conditions, fmt.Sprintf("(genero IN (%s) OR genero IS NULL)", strings.Join(placeholders, ",")))
		}
	}

	switch hasLicense {
	case "yes":
		conditions = append(conditions, "conducao = 1")
	case "no":
		conditions = append(conditions, "conducao = 0")
	}

	// Compute age in SQLite using date strings (YYYY-MM-DD).
	const ageExpr = `(CAST(strftime('%Y','now') AS INTEGER) - CAST(strftime('%Y', nascimento) AS INTEGER) -
		(CASE WHEN strftime('%m-%d','now') < strftime('%m-%d', nascimento) THEN 1 ELSE 0 END))`
	switch ageRange {
	case "1-18":
		conditions = append(conditions, ageExpr+" BETWEEN 1 AND 18")
	case "19-29":
		conditions = append(conditions, ageExpr+" BETWEEN 19 AND 29")
	case "30-45":
		conditions = append(conditions, ageExpr+" BETWEEN 30 AND 45")
	case "46-60":
		conditions = append(conditions, ageExpr+" BETWEEN 46 AND 60")
	case "61+":
		conditions = append(conditions, ageExpr+" >= 61")
	}

	q := `SELECT` + volunteerCols + ` FROM volunteers WHERE ` + strings.Join(conditions, " AND ") + ` ORDER BY id ASC`
	rows, err := d.db.Query(q, args...)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	var out []Volunteer
	for rows.Next() {
		v, err := scanVolunteer(rows)
		if err != nil {
			return nil, err
		}
		out = append(out, *v)
	}
	return out, rows.Err()
}

// ─── Institutions ──────────────────────────────────────────────────────────

const institutionCols = `
	id, nome, COALESCE(nome_representante,''), COALESCE(email_representante,''),
	email, COALESCE(telefone,''), COALESCE(morada,''),
	COALESCE(concelho,''), COALESCE(distrito,''), COALESCE(freguesia,''),
	COALESCE(descricao,''), COALESCE(tipo,''), verificado, image_path`

func scanInstitution(row interface{ Scan(...interface{}) error }) (*Institution, error) {
	inst := &Institution{}
	var verificado int
	err := row.Scan(
		&inst.ID, &inst.Nome, &inst.NomeRepresentante, &inst.EmailRepresentante,
		&inst.Email, &inst.Telefone, &inst.Morada, &inst.Concelho, &inst.Distrito,
		&inst.Freguesia, &inst.Descricao, &inst.Tipo, &verificado, &inst.ImagePath,
	)
	if err != nil {
		return nil, err
	}
	inst.Verificado = verificado != 0
	return inst, nil
}

func (d *Database) GetAllInstitutions() ([]Institution, error) {
	rows, err := d.db.Query(
		`SELECT` + institutionCols + ` FROM institutions ORDER BY id ASC`)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	var out []Institution
	for rows.Next() {
		inst, err := scanInstitution(rows)
		if err != nil {
			return nil, err
		}
		out = append(out, *inst)
	}
	return out, rows.Err()
}

func (d *Database) GetInstitutionByID(id int) (*Institution, error) {
	row := d.db.QueryRow(
		`SELECT`+institutionCols+` FROM institutions WHERE id=? LIMIT 1`, id)
	inst, err := scanInstitution(row)
	if errors.Is(err, sql.ErrNoRows) {
		return nil, nil
	}
	return inst, err
}

func (d *Database) GetInstitutionByEmail(email string) (*Institution, error) {
	row := d.db.QueryRow(
		`SELECT`+institutionCols+` FROM institutions WHERE email=? LIMIT 1`, email)
	inst, err := scanInstitution(row)
	if errors.Is(err, sql.ErrNoRows) {
		return nil, nil
	}
	return inst, err
}

func (d *Database) CreateInstitution(nome, nomeRep, emailRep, email, password, telefone, morada, tipo string) error {
	hash, err := bcrypt.GenerateFromPassword([]byte(password), bcrypt.DefaultCost)
	if err != nil {
		return err
	}
	_, err = d.db.Exec(
		`INSERT INTO institutions (nome, nome_representante, email_representante, email, pass, telefone, morada, tipo)
		 VALUES (?,?,?,?,?,?,?,?)`,
		nome, nomeRep, emailRep, email, string(hash), telefone, morada, tipo,
	)
	return err
}

func (d *Database) UpdateInstitutionProfile(email string, fields map[string]string) error {
	sets := []string{}
	args := []interface{}{}
	allowedCols := map[string]bool{
		"nome": true, "nome_representante": true, "email_representante": true,
		"telefone": true, "morada": true, "descricao": true, "tipo": true,
		"distrito": true, "concelho": true, "freguesia": true,
	}
	for col, val := range fields {
		if val == "" || !allowedCols[col] {
			continue
		}
		sets = append(sets, fmt.Sprintf("%s=?", col))
		args = append(args, val)
	}
	if len(sets) == 0 {
		return nil
	}
	args = append(args, email)
	_, err := d.db.Exec(
		fmt.Sprintf(`UPDATE institutions SET %s WHERE email=?`, strings.Join(sets, ",")),
		args...,
	)
	return err
}

func (d *Database) UpdateInstitutionPassword(email, currentPassword, newPassword string) error {
	var hash string
	if err := d.db.QueryRow(`SELECT pass FROM institutions WHERE email=?`, email).Scan(&hash); err != nil {
		return ErrUserNotFound
	}
	if bcrypt.CompareHashAndPassword([]byte(hash), []byte(currentPassword)) != nil {
		return ErrInvalidCredentials
	}
	newHash, err := bcrypt.GenerateFromPassword([]byte(newPassword), bcrypt.DefaultCost)
	if err != nil {
		return err
	}
	_, err = d.db.Exec(`UPDATE institutions SET pass=? WHERE email=?`, string(newHash), email)
	return err
}

func (d *Database) UpdateInstitutionEmail(oldEmail, newEmail, password string) error {
	var hash string
	if err := d.db.QueryRow(`SELECT pass FROM institutions WHERE email=?`, oldEmail).Scan(&hash); err != nil {
		return ErrUserNotFound
	}
	if bcrypt.CompareHashAndPassword([]byte(hash), []byte(password)) != nil {
		return ErrInvalidCredentials
	}
	exists, _ := d.EmailExistsAnywhere(newEmail)
	if exists {
		return errors.New("email já está em uso")
	}
	_, err := d.db.Exec(`UPDATE institutions SET email=? WHERE email=?`, newEmail, oldEmail)
	return err
}

func (d *Database) UpdateInstitutionPhoto(email, imagePath string) error {
	_, err := d.db.Exec(`UPDATE institutions SET image_path=? WHERE email=?`, imagePath, email)
	return err
}

func (d *Database) SearchInstitutions(query string) ([]Institution, error) {
	conditions := []string{"1=1"}
	args := []interface{}{}
	if query != "" {
		pattern := "%" + query + "%"
		conditions = append(conditions,
			`(nome LIKE ? OR email LIKE ? OR COALESCE(telefone,'') LIKE ? OR COALESCE(morada,'') LIKE ?
			  OR COALESCE(email_representante,'') LIKE ? OR COALESCE(nome_representante,'') LIKE ?
			  OR COALESCE(distrito,'') LIKE ? OR COALESCE(concelho,'') LIKE ? OR COALESCE(freguesia,'') LIKE ?)`)
		args = append(args, pattern, pattern, pattern, pattern, pattern, pattern, pattern, pattern, pattern)
	}
	q := `SELECT` + institutionCols + ` FROM institutions WHERE ` + strings.Join(conditions, " AND ") + ` ORDER BY id ASC`
	rows, err := d.db.Query(q, args...)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	var out []Institution
	for rows.Next() {
		inst, err := scanInstitution(rows)
		if err != nil {
			return nil, err
		}
		out = append(out, *inst)
	}
	return out, rows.Err()
}
