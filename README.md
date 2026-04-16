# BMJeTransit - Marketplace avec livraison integree

Application desktop (NativePHP/Electron) + API REST pour mobile.

## Architecture

- **Admin BMJE** : App desktop NativePHP (PC) - gestion de la plateforme
- **Clients / Entreprises / Livreurs** : App mobile Android via API REST
- **Backend** : Laravel 11 + SQLite (dev) / MySQL (production)
- **Auth API** : Laravel Sanctum (Bearer token)

## Installation locale

### Pre-requis

- PHP 8.2+
- Composer
- Node.js 20+
- NPM

### Etapes

```bash
# 1. Cloner le projet
git clone https://github.com/Eudes8/bmjetransit-nativephp.git
cd bmjetransit-nativephp

# 2. Installer les dependances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Creer la base de donnees SQLite
touch database/database.sqlite
php artisan migrate --seed

# 5. Compiler les assets frontend
npm run build
```

### Lancer en mode web (tester tous les roles)

```bash
# Terminal 1 : serveur Laravel
php artisan serve

# Terminal 2 : Vite dev (hot reload CSS/JS)
npm run dev

# Ouvrir http://localhost:8000
```

### Lancer en mode desktop (NativePHP)

```bash
php artisan native:serve
```

## Comptes de test

| Role       | Email                    | Mot de passe |
|------------|--------------------------|--------------|
| Admin      | admin@bmjetransit.com    | password     |
| Client     | client@test.com          | password     |
| Entreprise | entreprise@test.com      | password     |
| Entreprise | boutique@test.com        | password     |
| Livreur    | livreur@test.com         | password     |

## API REST

Base URL : `http://localhost:8000/api`

### Endpoints publics

| Methode | URL                        | Description               |
|---------|----------------------------|---------------------------|
| POST    | /api/auth/inscription      | Inscription client        |
| POST    | /api/auth/connexion        | Connexion (retourne token)|
| GET     | /api/catalogue             | Liste produits            |
| GET     | /api/catalogue/{id}        | Detail produit            |
| GET     | /api/categories            | Liste categories          |
| GET     | /api/tracking/{numero}     | Suivi commande            |

### Endpoints authentifies (Bearer token)

Ajouter le header : `Authorization: Bearer {token}`

**Client** : `/api/client/panier`, `/api/client/commandes`, etc.
**Entreprise** : `/api/entreprise/dashboard`, `/api/entreprise/produits`, etc.
**Livreur** : `/api/livreur/dashboard`, `/api/livreur/livraisons`, etc.

## GitHub Actions

- **CI Tests** : Tests PHPUnit + code style a chaque push
- **Build Desktop** : Build .exe (Windows) + .AppImage (Linux) a chaque push sur main
- **Release** : Creer un tag `v1.0.0` pour generer une release GitHub avec les fichiers telechargeables

### Creer une release

```bash
git tag v1.0.0
git push origin v1.0.0
```

## Technologies

- Laravel 11, NativePHP/Electron, Laravel Sanctum
- Tailwind CSS, Vite, Font Awesome
- SQLite (dev), MySQL (prod)

## Licence

Proprietary - BMJE Transit
