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

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(100) NOT NULL,
    prefixe VARCHAR(5) NOT NULL UNIQUE,
    type VARCHAR(20) NOT NULL CHECK(type IN ('LOCAL','EXTERNE')),
    actif INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,   
    libelle VARCHAR(50) NOT NULL
);

CREATE TABLE configuration (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    commission_autre_operateur DECIMAL(5,2) NOT NULL
);

CREATE TABLE bareme_frais (
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
    client_destinataire_id INTEGER,
    operateur_destination_id INTEGER,
    commission DECIMAL(15,2) NOT NULL DEFAULT 0,            
    montant DECIMAL(15,2) NOT NULL,
    frais_applique DECIMAL(15,2) NOT NULL DEFAULT 0,
    montant_total DECIMAL(15,2) NOT NULL,      
    solde_avant DECIMAL(15,2) NOT NULL,
    solde_apres DECIMAL(15,2) NOT NULL,
    statut VARCHAR(20) NOT NULL DEFAULT 'reussie',
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES type_operation(id) ON DELETE CASCADE,
    FOREIGN KEY (operateur_destination_id) REFERENCES operateur(id),
    FOREIGN KEY (client_id) REFERENCES client(id) ON DELETE CASCADE,
    FOREIGN KEY (client_destinataire_id) REFERENCES client(id) ON DELETE SET NULL
);

CREATE VIEW vue_situation_gains AS
SELECT
    t.code AS code_operation,
    t.libelle AS type_operation,
    op.type AS type_operateur,
    COALESCE(SUM(o.frais_applique), 0) AS total_gains,
    COUNT(o.id) AS nombre_operations
FROM type_operation t
CROSS JOIN operateur op
LEFT JOIN operation o ON t.id = o.type_operation_id
    AND o.operateur_destination_id = op.id
    AND o.statut = 'reussie'
WHERE t.code IN ('RETRAIT', 'TRANSFERT')
GROUP BY t.id, op.id;

CREATE VIEW vue_situation_comptes AS
SELECT
    c.id,
    c.nom,
    c.numero_telephone,
    c.solde,
    c.status,
    COUNT(o.id) AS total_operations_effectuees
FROM client c
LEFT JOIN operation o ON c.id = o.client_id AND o.statut = 'reussie'
GROUP BY c.id;

CREATE VIEW vue_situation_montants_operateurs AS
SELECT
    o.id AS operateur_id,
    o.libelle AS operateur_libelle,
    o.type AS type_operateur,
    o.prefixe,
    COALESCE(SUM(op.montant), 0) AS total_montant_a_envoyer,
    COUNT(op.id) AS nombre_transferts
FROM operateur o
LEFT JOIN operation op ON o.id = op.operateur_destination_id
    AND op.type_operation_id = (SELECT id FROM type_operation WHERE code = 'TRANSFERT')
    AND op.statut = 'reussie'
GROUP BY o.id;

INSERT INTO admin (nom, pwd) VALUES ('admin', 'admin123');

INSERT INTO type_operation (code, libelle) VALUES
('DEPOT', 'Dépôt'),
('RETRAIT', 'Retrait'),
('TRANSFERT', 'Transfert');

INSERT INTO configuration (commission_autre_operateur) VALUES (1.00);

INSERT INTO operateur (libelle, prefixe, type, actif) VALUES
('MVola', '033', 'LOCAL', 1),
('MVola', '037', 'LOCAL', 1),
('Orange Money', '032', 'EXTERNE', 1),
('Orange Money', '031', 'EXTERNE', 1),
('Airtel Money', '034', 'EXTERNE', 1);

INSERT INTO bareme_frais (type_operation_id, montant_min, montant_max, frais)
SELECT id, 0, 2000000, 0 FROM type_operation WHERE code = 'DEPOT';

INSERT INTO bareme_frais (type_operation_id, montant_min, montant_max, frais)
SELECT id, 100,      1000,     50   FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 1001,     5000,     50   FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 5001,     10000,    100  FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 10001,    25000,    200  FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 25001,    50000,    400  FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 50001,    100000,   800  FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 100001,   250000,   1500 FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 250001,   500000,   1500 FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 500001,   1000000,  2500 FROM type_operation WHERE code = 'RETRAIT' UNION ALL
SELECT id, 1000001,  2000000,  3000 FROM type_operation WHERE code = 'RETRAIT';

INSERT INTO bareme_frais (type_operation_id, montant_min, montant_max, frais)
SELECT id, 100,      1000,     50   FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 1001,     5000,     50   FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 5001,     10000,    100  FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 10001,    25000,    200  FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 25001,    50000,    400  FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 50001,    100000,   800  FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 100001,   250000,   1500 FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 250001,   500000,   1500 FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 500001,   1000000,  2500 FROM type_operation WHERE code = 'TRANSFERT' UNION ALL
SELECT id, 1000001,  2000000,  3000 FROM type_operation WHERE code = 'TRANSFERT';

INSERT INTO client (nom, numero_telephone, solde, status) VALUES 
('Jean Rabe', '0331234567', 50000.00, 'actif'),
('Marie Ranaivo', '0377654321', 10000.00, 'actif');