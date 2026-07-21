# ( 4163 ) V1 Login
## 1. Création de la base de données
- [x] Créer la base de données `mobile_money`.

---

## 2. Création des tables

### Administration
- [x] Créer la table `admin`
    - id
    - nom
    - pwd

### Clients
- [x] Créer la table `client`
    - id
    - nom
    - numero_telephone
    - solde          
    - status  

### Préfixes opérateurs
- [x] Créer la table `prefixe_operateur`
    - id
    - prefixe
    - libelle
    - type    (LOCAL ou EXTERNE)
    - actif

### configuration
- [x] Créer la table `configuration`
    - id
    - commission_autre_operateur

### operation
- [x] Créer la table `operation`
    - id
    - type_operation_id
    - client_id
    - client_destinataire_id
    - operateur_destination_id
    - commission
    - montant
    - frais_applique
    - montant_total
    - solde_avant
    - solde_apres
    - statut
    - date_operation

### Types d'opérations
- [x] Créer la table `type_operation`
    - id
    - code
    - libelle

### Barème des frais
- [x] Créer la table `bareme_frais` 
    - id
    - type_operation_id
    - montant_min
    - montant_max
    - frais
    - actif

---

## 3. Relations entre les tables
- [x] Ajouter la clé étrangère entre `bareme_frais` et `type_operation`.
- [x] Vérifier l'intégrité référentielle.
- [x] Définir les règles ON UPDATE et ON DELETE.

---

## 4. Contraintes
- [x] Définir les PRIMARY KEY.
- [x] Définir les FOREIGN KEY.
- [x] Ajouter les contraintes NOT NULL.
- [x] Ajouter les contraintes UNIQUE si nécessaire.
- [x] Définir les valeurs par défaut.
- [x] Vérifier les types de données.

---

## 5. Jeu de données
- [x] Insérer un administrateur de test.    
- [x] Insérer des clients de test.
- [x] Insérer les préfixes des opérateurs.
- [x] Insérer les différents types d'opérations.
- [x] Insérer les barèmes de frais.

---

## 6. Validation
- [x] Tester toutes les relations entre les tables.
- [x] Vérifier la cohérence des données.
- [x] Corriger les éventuelles anomalies.

---

# (4163) Version 2 - Partie Opérateur

## Base de données

- [x] Fusionner `prefixe_operateur` dans la table `operateur`.
- [x] Ajouter la colonne `type` (LOCAL / EXTERNE).
- [x] Créer la table `configuration`.
- [x] Ajouter la colonne `commission_autre_operateur`.
- [x] Ajouter `operateur_destination_id` dans `operation`.
- [x] Ajouter la colonne `commission` dans `operation`.
- [x] Créer la clé étrangère `operateur_destination_id`.
- [x] Vérifier l'unicité du préfixe (`UNIQUE`).
- [x] Vérifier les contraintes d'intégrité référentielle.
- [x] Insérer les données de test (opérateurs, configuration).
- [x] Tester le schéma après migration.

---

## Models

### OperateurModel

- [x] Adapter le modèle au nouveau schéma.
- [x] Ajouter la récupération des opérateurs locaux.
- [x] Ajouter la récupération des opérateurs externes.
- [x] Ajouter une méthode de recherche par préfixe.
- [x] Ajouter une méthode de regroupement par libellé.

### ConfigurationModel

- [x] Créer le modèle.
- [x] Ajouter la récupération du pourcentage de commission.
- [x] Ajouter la modification de la configuration.

### OperationModel

- [x] Adapter le modèle aux nouvelles colonnes.
- [x] Enregistrer l'opérateur de destination.
- [x] Enregistrer la commission appliquée.
- [x] Calculer les frais réellement appliqués.

---

## Controllers

### OperateurController

- [x] Adapter les méthodes existantes.
- [x] Gérer les opérateurs locaux.
- [x] Gérer les opérateurs externes.
- [x] Valider l'unicité des préfixes.
- [x] Ajouter les messages d'erreur.

### ConfigurationController

- [x] Créer le contrôleur.
- [x] Afficher la configuration.
- [x] Modifier le pourcentage de commission.
- [x] Vérifier la validité du pourcentage.

### DashboardController

- [x] Ajouter la récupération de la vue `vue_situation_gains`.
- [x] Ajouter la récupération de la vue `vue_situation_montants_operateurs`.
- [x] Préparer les données pour l'affichage.

---

## Views

### Gestion des opérateurs

- [x] Adapter le formulaire d'ajout/modification.
- [x] Ajouter le choix du type (LOCAL / EXTERNE).
- [x] Afficher le type dans la liste des opérateurs.

### Configuration

- [x] Créer la page de configuration.
- [x] Ajouter le formulaire de modification du pourcentage de commission.
- [x] Afficher le pourcentage actuel.

### Dashboard

- [x] Afficher la situation des gains par type d'opération.
- [x] Séparer les gains des opérateurs locaux et externes.
- [x] Afficher les montants à reverser à chaque opérateur externe.
- [x] Regrouper les opérateurs partageant le même libellé.

---

## Logique métier

- [x] Identifier automatiquement l'opérateur à partir du préfixe.
- [x] Déterminer si le transfert est local ou externe.
- [x] Calculer la commission selon la configuration.
- [x] Calculer le montant total débité.
- [x] Enregistrer tous les montants calculés.
- [x] Vérifier la cohérence des calculs avant validation.

---

## Vues SQL

- [x] Créer `vue_situation_gains`.
- [x] Vérifier le regroupement par type d'opération.
- [x] Vérifier la séparation LOCAL / EXTERNE.
- [x] Créer `vue_situation_montants_operateurs`.
- [x] Regrouper les commissions par libellé.

---

## Tests

- [x] Vérifier les calculs des commissions.
- [x] Vérifier les transferts locaux.
- [x] Vérifier les transferts vers d'autres opérateurs.
- [x] Vérifier les regroupements par libellé.
- [x] Vérifier les vues sans données.
- [x] Vérifier les vues avec plusieurs opérations.
- [x] Vérifier les montants affichés dans le dashboard.
- [x] Corriger les anomalies détectées.


# ( 4230 ) Login 
    - Login user/admin:
        - Model
            - ClientModel.php -> autoLogin: si num existe, on affiche les infos / else, on ajoute le num dans la base
        - Controller
            - ConnexionController.php:
                - index(): affichage
                - login(): pour client
                - loginAdmin(): pour admin
        - Vue
            - login.php
       - Routes:
            - Routes.php

# ( 4230 ) Opération
    - voir le solde:
        - Model
        - Controller:
            - fonction home(): verifie si l user est connecté
        - Vue
            - home_client.php
        - Routes:
            - routes->get('home', ...)

    - Faire un dépôt:
        - Model
        - Controller:
            - fonction depot(): manambatra montant vaovao nampidirina @ solde efa misy
        - Vue:
             - home_client.php
        - Routes:
            - routes->post('client/depot', ...)

    - faire un retrait:
        - Model
        - Controller:
            - fonction retrait(): le solde dispo est suffisant? si oui, on soustrait le montant
        - Vue:
             - home_client.php
        - Routes:
            routes->post('client/retrait', ...)

    - Faire un transfert:
        - Model
        - Controller:
            fonction transfert(): manala solde ao @ solde an'ny mpandefa 
        - Vue:
        - Routes:
            - routes->post('client/transfert', ...)

    - Voir les historiques
        - Model
        - Controller:
        - Vue:
        - Routes:

## ( 4230 ) Version 2

Dépôt: 
    Solde avant + (Montant + Frais) = Solde après

Retrait:
     Solde avant - (Montant + Frais) = Solde après

Transfert:
    - verification même opérateur pour l envoie multiple
        si les libelles sont les memes-> même opérateur

    - division montant si plusieurs destinataires ($montantParDest)
        $montantParDest = montant total/nb de destinataires

    - Frais pour transfert:

        - si même opérateur/retrait cochée:
            0 commission
            vola miala: montant + transfert + retrait

        - si même opérateur/retrait décochée:
            0 commission
            vola miala: montant + transfert

        - autre opérateur(retrait cochée)
            pas de frait de retrait
            vola miala: montant + transfert + commission

        - autre opérateur(retrait décochée)  
            pas de frait de retrait  
            vola miala: montant + transfert + commission





