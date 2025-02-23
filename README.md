# Gestion_Projets

Gestion_Projets est une application de gestion de projets collaboratifs développée avec **Laravel**. Ce projet permet aux utilisateurs de créer, gérer et suivre des projets de manière intuitive.

##  Installation et exécution

### 1️⃣ Cloner le dépôt
```sh
git clone https://github.com/DuracellG/Gestion_Projets.git
cd Gestion_Projets
```

### 2️⃣ Installer les dépendances
Assurez-vous d'avoir **PHP**, **Composer** et **Node.js** installés.

```sh
composer install
npm install
```

### 3️⃣ Configurer l'environnement
Copiez le fichier `.env.example` en `.env` et mettez à jour les informations de la base de données :
```sh
cp .env.example .env
```

Générez la clé d'application Laravel :
```sh
php artisan key:generate
```

### 4️⃣ Configurer la base de données
Créez une base de données et mettez à jour `.env` avec vos paramètres.
Puis exécutez :
```sh
php artisan migrate --seed
```

### 5️⃣ Lancer le serveur
```sh
php artisan serve
```
L'application sera disponible sur : **http://127.0.0.1:8000**

### 6️⃣ Compiler les assets (si nécessaire)
```sh
npm run dev
```


**Auteur :** [Dorel GNANGNON](https://github.com/DuracellG)


