# 🚚 BMJeTransit — Marketplace + Livraison

**Plateforme marketplace avec service de livraison intégré pour la Côte d'Ivoire.**

Les entreprises publient leurs produits et services. Les clients commandent et paient. BMJE Transit gère toute la logistique de livraison avec ses propres livreurs.

## 🏗️ Architecture

- **Backend** : Laravel 11 + PHP 8.3
- **Desktop Admin** : NativePhP (Electron)
- **Frontend** : Blade + Livewire
- **Base de données** : SQLite (dev) / MySQL (prod)
- **Paiements** : Orange Money, MTN MoMo, Wave, Espèces

## 💰 Business Model

```
┌──────────────┐      ┌──────────────┐      ┌──────────────┐
│  ENTREPRISE  │      │  BMJE TRANSIT │      │    CLIENT    │
│  (vendeur)   │◄────►│ (plateforme)  │◄────►│  (acheteur)  │
│              │      │               │      │              │
│ • Publie ses │      │ • Gère tout   │      │ • Achète     │
│   produits   │      │ • Prend une   │      │ • Paie       │
│ • Paie un    │      │   commission  │      │   produit +  │
│   abonnement │      │ • Gère les    │      │   livraison  │
│              │      │   livreurs    │      │              │
└──────────────┘      └───────┬───────┘      └──────────────┘
                              │
                      ┌───────▼───────┐
                      │   LIVREUR     │
                      │  (employé     │
                      │   BMJE)       │
                      │ • Enlève chez │
                      │   l'entreprise│
                      │ • Livre au    │
                      │   client      │
                      └───────────────┘
```

## 📦 Installation

### Prérequis
- PHP 8.3+
- Composer
- Node.js 22+
- npm

### 1. Cloner le projet
```bash
git clone https://github.com/Eudes8/bmjetransit-nativephp.git
cd bmjetransit-nativephp
```

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Installer NativePhP
```bash
composer require nativephp/desktop
php artisan native:install
```

### 4. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Créer la base de données
```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

### 6. Lancer l'application
```bash
# En mode web (navigateur)
php artisan serve

# En mode desktop natif (Electron)
php artisan native:run
```

## 🗃️ Base de données (15 tables)

| Groupe | Tables |
|--------|--------|
| Auth | `users` |
| Entreprises | `entreprises`, `forfaits`, `abonnements` |
| Catalogue | `categories`, `produits` |
| Commandes | `commandes`, `commande_produits` |
| Livraison | `livreurs`, `livraisons`, `suivi_livraisons` |
| Finances | `transactions`, `portefeuilles_entreprises`, `versements` |
| Social | `avis`, `notifications` |

## 👥 Rôles utilisateurs

| Rôle | Description |
|------|-------------|
| 👑 Admin BMJE | Gère la plateforme, les tarifs, les livreurs |
| 🏢 Entreprise | Publie produits, reçoit commandes, reçoit paiements |
| 👤 Client | Achète, paie, suit sa livraison |
| 🚴 Livreur | Employé BMJE, enlève et livre les commandes |

## 📄 Licence

Propriétaire — BMJE TRANSIT © 2026
