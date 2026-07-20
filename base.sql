PRAGMA foreign_keys = ON;


CREATE TABLE admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100),
    pwd VARCHAR(255) NOT NULL
);

CREATE TABLE client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100),
    numero_telephone VARCHAR(15) UNIQUE,
    solde DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    status VARCHAR(20) NOT NULL DEFAULT 'actif'
);

CREATE TABLE prefixe_operateur(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe VARCHAR(5) NOT NULL UNIQUE,
    actif INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,   
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE bareme_frais(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min DECIMAL(15,2) NOT NULL,
    montant_max DECIMAL(15,2) NOT NULL,
    frais DECIMAL(15,2) NOT NULL,
    actif INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (type_operation_id) REFERENCES type_operation(id) ON DELETE CASCADE
);

CREATE TABLE operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    client_id INTEGER NOT NULL,              
    client_destinataire_id INTEGER,            -- refa manao transfert iany
    montant DECIMAL(15,2) NOT NULL,
    frais_applique DECIMAL(15,2) NOT NULL DEFAULT 0,
    montant_total DECIMAL(15,2) NOT NULL,      -- montant + frais
    solde_avant DECIMAL(15,2) NOT NULL,
    solde_apres DECIMAL(15,2) NOT NULL,
    statut VARCHAR(20) NOT NULL DEFAULT 'reussie',
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES type_operation(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE,
    FOREIGN KEY (client_destinataire_id) REFERENCES client(id) ON DELETE SET NULL
);









INSERT INTO type_operation (code, libelle) VALUES
('DEPOT', 'Dépôt'),
('RETRAIT', 'Retrait'),
('TRANSFERT', 'Transfert');

INSERT INTO prefixe_operateur (prefixe, actif) VALUES
('033', 1),
('037', 1);

INSERT INTO bareme_frais (type_operation_id, montant_min, montant_max, frais, actif)
SELECT id, 100,      1000,     50,   1 FROM type_operation
UNION ALL
SELECT id, 1001,     5000,     50,   1 FROM type_operation
UNION ALL
SELECT id, 5001,     10000,    100,  1 FROM type_operation
UNION ALL
SELECT id, 10001,    25000,    200,  1 FROM type_operation
UNION ALL
SELECT id, 25001,    50000,    400,  1 FROM type_operation
UNION ALL
SELECT id, 50001,    100000,   800,  1 FROM type_operation
UNION ALL
SELECT id, 100001,   250000,   1500, 1 FROM type_operation
UNION ALL
SELECT id, 250001,   500000,   1500, 1 FROM type_operation
UNION ALL
SELECT id, 500001,   1000000,  2500, 1 FROM type_operation
UNION ALL
SELECT id, 1000001,  2000000,  3000, 1 FROM type_operation;