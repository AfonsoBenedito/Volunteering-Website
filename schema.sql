-- SQLite schema for VoluntárioCOVID19
-- NOTE: This file is kept as a reference only.
-- The schema is embedded directly in db.go and applied at startup.

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
);
