# Arche
Application de streaming musical
## Installation
cloner le projet sur son serveur
```
git clone git@lab.frogg.it:arche/arche.git
cd arche/app
git clone git@lab.frogg.it:arche/arche-vue-js.git .
cd ../api
git clone git@lab.frogg.it:arche/arche-api.git .
mkdir -p public/files/songs
mkdir -p public/files/thumbnails
cd ..
```

créer le fichier .env à la racine
```
APP_USER=martin # utilisateur du serveur
APP_UID=1000 # identifiant de l'utilisateur du serveur
APP_ENV=production # type d'environnement (production ou development)
APP_PORT=8080 # port sur lequel l'app sera accessible

DB_DATABASE=arche # nom de la base de données MySQL
DB_PASSWORD=passw0rd # mot de passe pour accéder à la base de données MySQL
DB_USER=user # nom d'utilisateur de la db
DB_HOST=arche_db # host de la db (laisser arche_db)
```

créer un nouveau fichier .env dans api/
```
DB_DATABASE=arche
DB_PASSWORD=passw0rd
DB_USER=user
DB_HOST=arche_db

JWT_TOKEN=cledetoken
CLIENT_BASEPATH=app.arche.com
```

créer le fichier env.js dans le dossier app/src/sonfig
```javascript
const config = {
        api: 'http://api.arche.com:<APP_PORT>/' // 'http://api.arche.tech:<APP_PORT>/' pour environnement de développement avec <APP_PORT> définit dans le fichier .env
};

export default config;
```

lancer les services
```bash
docker-compose up -d
docker exec arche_api composer install
docker exec arche_api php -f database/setup.php
docker exec arche_app npm install
docker exec arche_app npm run build
```

## Accéder à l'application
rendez-vous sur l'adresse IP de votre serveur depuis votre navigateur web
par exemple
```
http://127.0.0.1:<APP_PORT>/ # avec <APP_PORT> définit dans votre fichier .env
```
