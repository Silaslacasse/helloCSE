# Projet API HelloCSE

## Prérequis
Assurez-vous d'avoir les éléments suivants installés sur votre machine :
- [Composer](https://getcomposer.org/)
- [PHP](https://www.php.net/)
- [Docker](https://www.docker.com/)

## Installation et Lancement du Projet en Local

1. **Clonez le dépôt :**
   ```bash
   git clone <URL_DU_DEPOT>
   cd helloCSE
   ```

2. **Créer le fichier .env : Copiez le fichier .env.example en .env :**

    ```
    cp .env.example .env
    ```

3. **Installez les dépendances PHP :**

    ```
    composer install
    ```

4. **Changez dans le .env les accès à la BDD :**

    ```
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=laravel_user
    DB_PASSWORD=laravel_password
    ```

5. **Générez la clé d'application**

    ```
    php artisan key:generate
    ```

6. **Construisez et démarrez les conteneurs Docker :**

    ```
    docker-compose build
    docker-compose up -d
    ```

## Exécution des Migrations de la Base de Données

Pour exécuter les migrations, suivez les étapes ci-dessous :

Connectez-vous au conteneur Laravel :

    docker exec -it laravel-app bash

Lancez les migrations :

    php artisan migrate

## Exécution des Tests

Toujours dans le conteneur, lancez les tests avec la commande suivante :

    php artisan test

## Accès api : 

https://hellocse.silaslacasse.com/


Endpoint public : 

- Récuperer les profils actifs : Get -> https://hellocse.silaslacasse.com/api/profiles

- Créer un admin : Post -> https://hellocse.silaslacasse.com/api/register

    ```
    Schéma : 
        {
            "name": "name",
            "email": "email",
            "password": "password",
            "password_confirmation": "password"
        }
    ```

- S'authentifier : Post -> https://hellocse.silaslacasse.com/api/login

    ```
    Schéma : 
        {
            "email": "email",
            "password": "password"
        }
    ```

Authentification nécessaire : 

- Créer un profil : Post -> https://hellocse.silaslacasse.com/api/profile

    ```
    Schéma : 
        {
            "name": "Name",
            "firstName" : "FisrtName",
            "imagePath" : "https://test.com/test.png",
            "status" : "active"
        }
    ```


- Modifier un profil : Put -> https://hellocse.silaslacasse.com/api/profile/{id}

    ```
    Schéma : 
        {
            "name": "updatedName",
            "firstName" : "updatedFirstName",
            "imagePath" : "https://test.com/testUpadated.png",
            "status" : "active"
        }
    ```

- Supprimer un profil : Delete -> https://hellocse.silaslacasse.com/api/profile/{id}

- Récupérer un prifil via l'id : Get -> https://hellocse.silaslacasse.com/api/profile/{id}


Si vous avez la moindre question, je suis disponible sur ce mail : jocelynduperret@gmail.com
