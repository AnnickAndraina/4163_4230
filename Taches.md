# ( 4163 ) Login 
## 1. Analyse des besoins
- [ ] Identifier les données nécessaires au fonctionnement de l'application.
- [ ] Déterminer les entités principales.
- [ ] Définir les relations entre les entités.
- [ ] Vérifier les règles de gestion avec l'équipe.

---

## 2. Création de la base de données
- [ ] Créer la base de données `mobile_money`.

---

## 3. Création des tables

### Administration
- [ ] Créer la table `admin`
    - id
    - nom
    - pwd

### Clients
- [ ] Créer la table `client`
    - id
    - nom
    - numero_telephone

### Préfixes opérateurs
- [ ] Créer la table `prefixe_operateur`
    - id
    - prefixe
    - libelle
    - actif

### Types d'opérations
- [ ] Créer la table `type_operation`
    - id
    - code
    - libelle

### Barème des frais
- [ ] Créer la table `bareme_frais`
    - id
    - type_operation_id
    - montant_min
    - montant_max
    - frais
    - actif

---

## 4. Relations entre les tables
- [ ] Ajouter la clé étrangère entre `bareme_frais` et `type_operation`.
- [ ] Vérifier l'intégrité référentielle.
- [ ] Définir les règles ON UPDATE et ON DELETE.

---

## 5. Contraintes
- [ ] Définir les PRIMARY KEY.
- [ ] Définir les FOREIGN KEY.
- [ ] Ajouter les contraintes NOT NULL.
- [ ] Ajouter les contraintes UNIQUE si nécessaire.
- [ ] Définir les valeurs par défaut.
- [ ] Vérifier les types de données.

---

## 6. Jeu de données
- [ ] Insérer un administrateur de test.
- [ ] Insérer des clients de test.
- [ ] Insérer les préfixes des opérateurs.
- [ ] Insérer les différents types d'opérations.
- [ ] Insérer les barèmes de frais.

---

## 7. Validation
- [ ] Tester toutes les relations entre les tables.
- [ ] Vérifier la cohérence des données.
- [ ] Corriger les éventuelles anomalies.

---




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





