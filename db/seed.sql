-- All accounts use the password: password123
-- Hash: $2y$10$ZpcxBTXb3BEg9RQO7lB9Ge6mWMwaztBNUR3zDDO1WwGvZoNGmZn7C

INSERT INTO voluntariosVC19 (CC, Username, Email, Pass, Nome, Apelido, Nascimento, Conducao, Verificado, Genero, Distrito, Concelho, Freguesia) VALUES
('12345678',  'joao_silva',      'joao.silva@example.com',    '$2y$10$ZpcxBTXb3BEg9RQO7lB9Ge6mWMwaztBNUR3zDDO1WwGvZoNGmZn7C', 'João',   'Silva',    '1990-05-15', 1, 'TRUE', 'M', 'Lisboa', 'Lisboa',  'Alvalade'),
('23456789',  'maria_santos',    'maria.santos@example.com',  '$2y$10$ZpcxBTXb3BEg9RQO7lB9Ge6mWMwaztBNUR3zDDO1WwGvZoNGmZn7C', 'Maria',  'Santos',   '1995-08-22', 0, 'TRUE', 'F', 'Porto',   'Porto',   'Cedofeita'),
('34567890',  'carlos_oliveira', 'carlos.oliveira@example.com','$2y$10$ZpcxBTXb3BEg9RQO7lB9Ge6mWMwaztBNUR3zDDO1WwGvZoNGmZn7C', 'Carlos', 'Oliveira', '1988-11-03', 1, 'TRUE', 'M', 'Coimbra', 'Coimbra', 'Sé Nova');

INSERT INTO instituicoesVC19 (Nome, NomeRepresentante, EmailRepresentante, Email, Pass, Verificado) VALUES
('Cruz Vermelha Portuguesa',    'Ana Costa',       'ana.costa@cruzvermelha.pt',   'contacto@cruzvermelha.pt',   '$2y$10$ZpcxBTXb3BEg9RQO7lB9Ge6mWMwaztBNUR3zDDO1WwGvZoNGmZn7C', 'TRUE'),
('Refood',                      'Pedro Martins',   'pedro.martins@refood.pt',     'info@refood.pt',             '$2y$10$ZpcxBTXb3BEg9RQO7lB9Ge6mWMwaztBNUR3zDDO1WwGvZoNGmZn7C', 'TRUE'),
('Banco Alimentar Contra a Fome','Sofia Rodrigues', 'sofia@bancoalimentar.pt',    'geral@bancoalimentar.pt',    '$2y$10$ZpcxBTXb3BEg9RQO7lB9Ge6mWMwaztBNUR3zDDO1WwGvZoNGmZn7C', 'TRUE');
