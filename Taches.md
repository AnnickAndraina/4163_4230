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
