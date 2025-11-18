## Copier le repo 
git clone https://github.com/Mickaeldev67/Hamster-examen.git
cd Hamster-examen

## Installer les dépendances 
composer install 

## Fichier .env 
1. Copiez le fichier env et renommez le nouveau fichier en .env.local
2. Ajoutez DATABASE_URL="mysql://user:password@127.0.0.1:3306/hamsterjoly?charset=utf8mb4"
3. Changez user et password par vos identifiant pour la base de données. 

## Préparation de la base de données 
1. Créez la base de données : 
php bin/console doctrine:database:create

2. Executez les migrations : 
php bin/console doctrine:migrations:migrate

## Configuration des Clé JWT 
1. Créer un dossier jwt dans config 
mkdir -p config/jwt
2. Dans le git bash, executer cette commande en mettant une passphrase 
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 
3. Executer ensuite cette deuxième commande avec la même passphrase 
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
4. Mettez votre passphrase dans le .env.local 
JWT_PASSPHRASE=votrepassphrase

## Lancer symfony 
symfony server:start

## Tester les routes ! 
1. Pour commencer créez un login avec 
POST /api/register

2. List de toutes les routes disponible :
POST /api/login
DELETE /api/delete/{id}
GET /api/user
GET /api/hamsters
GET /api/hamsters/{id}