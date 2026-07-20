CREATE DATABASE bibliotheque;
USE bibliotheque;

CREATE TABLE livres (
    id_livre INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    ISBN VARCHAR(20) NOT NULL UNIQUE,
    annee_publication INT NOT NULL,
    categorie VARCHAR(100) NOT NULL,
    resume TEXT,
    fichier_couverture VARCHAR(255),
    statut ENUM('disponible', 'emprunte') NOT NULL DEFAULT 'disponible',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE emprunts (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_livre INT NOT NULL,
    nom_emprunteur VARCHAR(255) NOT NULL,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_livre) REFERENCES livres(id_livre)
);

INSERT INTO livres (titre, auteur, ISBN, annee_publication, categorie, resume, fichier_couverture, statut)
VALUES
(
    'L Alchimiste',
    'Paulo Coelho',
    '9780061122415',
    1988,
    'Roman',
    'Un jeune berger part a la recherche de son destin et d un tresor cache.',
    'alchimiste.jpg',
    'disponible'
),
(
    '1984',
    'George Orwell',
    '9780451524935',
    1949,
    'Science-fiction',
    'Un monde totalitaire ou la surveillance est omnipresente.',
    '1984.jpg',
    'emprunte'
),
(
    'Le Petit Prince',
    'Antoine de Saint-Exupery',
    '9780156013987',
    1943,
    'Conte',
    'Un pilote rencontre un petit prince venu d une autre planete.',
    'petit_prince.jpg',
    'disponible'
),
(
    'Harry Potter a l ecole des sorciers',
    'J K Rowling',
    '9782070643028',
    1997,
    'Fantastique',
    'Un jeune garcon decouvre qu il est sorcier et entre a Poudlard.',
    'harry_potter1.jpg',
    'disponible'
),
(
    'Le Seigneur des Anneaux',
    'J R R Tolkien',
    '9780261102385',
    1954,
    'Fantastique',
    'Une quete epique pour detruire un anneau malefique.',
    'seigneur_anneaux.jpg',
    'emprunte'
);