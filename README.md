# MiniPress

MiniPress est un mini CMS « headless » développé dans le cadre de la SAÉ *Atelier de développement d'application web* (BUT Informatique, S4, IUT Nancy-Charlemagne). Le projet permet de créer et de publier des articles, classés par catégories, et de les rendre disponibles via une API JSON consommée à la fois par une application web et par une application mobile.

## Équipe

- Auriane GUYOT
- Thibaut LOUYOT
- Maryam TAHRI
- Jordan VIGNEULLE
- Nathan YVON

## Architecture du projet

MiniPress se compose de trois sous-projets distincts, chacun correspondant à un sujet de la SAÉ :

| Sous-projet | Rôle | Techno principale |
|---|---|---|
| **MiniPress.core** | Backend : API JSON + interface d'administration (création d'articles, catégories, utilisateurs) | PHP / Slim / Twig / Eloquent |
| **MiniPress.web** | Application web cliente consommant l'API pour parcourir et afficher les articles | TypeScript / Handlebars |
| **MiniPress.app** | Application mobile (master/détail) consommant l'API | Flutter / Dart |

Les trois composants sont déployés sous forme de services Docker distincts sur la machine `docketu.iutnc.univ-lorraine.fr`.

---

## Liens

- **Back Office (MiniPress.core)** : http://docketu.iutnc.univ-lorraine.fr:29029/
- **Application web (MiniPress.web)** : http://docketu.iutnc.univ-lorraine.fr:29032/
- **Application mobile (MiniPress.app)** : code source sur [GitHub](https://github.com/Jordan-Vigneulle/Minipress)

### Identifiants de démonstration (back office)

- **admin@minipress.fr** : Adm!n#2024$Secure

- **auteur@minipress.fr** : Auth0r@MiniPr3ss!

---

## Modèle de données

Un **article** est composé de :
- un titre,
- un résumé (optionnel, rédigé en Markdown),
- un contenu (rédigé en Markdown),
- une date de création/publication,
- une catégorie,
- une ou plusieurs images (optionnel), référencées par des URLs,
- un auteur (l'utilisateur l'ayant créé),
- un statut publié / dépublié.

--- 

## Fonctionnalités

### MiniPress.core (backend)

- Création d'un article via formulaire HTML (titre, résumé, contenu en Markdown, date auto), avec choix de la catégorie.
- Création de catégories via formulaire.
- Affichage de la liste des articles (triée par date de création décroissante) et filtrage par catégorie.
- Authentification des utilisateurs (email + mot de passe) pour accéder à la création d'articles.
- Gestion des auteurs : l'utilisateur connecté devient automatiquement l'auteur de l'article créé.
- Publication / dépublication des articles depuis la liste d'administration.
- Création de nouveaux utilisateurs par un administrateur.
- API publique en lecture

### MiniPress.web (client web)

- Navigation dans les catégories et les listes d'articles.
- Affichage de la liste des articles (titre, date, auteur), triée par date décroissante.
- Affichage complet d'un article, avec conversion du Markdown en HTML côté client.
- Affichage des articles d'un auteur en cliquant sur son nom.
- Fonctions étendues : tri (date croissante/décroissante) et filtrage par mot-clé (titre, ou titre + résumé).

### MiniPress.app (mobile)

- Interface de type « master / détail » : liste des articles d'un côté, détail au clic.
- Navigation dans les catégories et listes d'articles, affichage des articles d'un auteur.
- Appels asynchrones à l'API (`Future` / `FutureBuilder`) avec gestion des erreurs.
- Fonctions étendues : tri et filtrage identiques à la version web.

## Routes de l'application (extrait de `routes.php`)

### Interface web (admin)

| Méthode | Route | Action |
|---|---|---|
| GET | `/` | Page d'accueil |
| GET | `/categories` | Liste des catégories |
| GET | `/categories/{id}/articles` | Articles d'une catégorie |
| GET / POST | `/categories/create` | Formulaire / création de catégorie |
| GET | `/articles` | Liste des articles |
| GET | `/articles/{id}` | Détail d'un article |
| GET / POST | `/articles/create` | Formulaire / création d'article |
| POST | `/articles/{id}/toggle-publish` | Publication / dépublication |
| GET | `/loginPage`, POST `/login` | Connexion |
| GET | `/signinPage`, POST `/signin` | Inscription |
| GET | `/logout` | Déconnexion |
| GET | `/profil` | Profil utilisateur |
| POST | `/profil/avatar`, `/profil/password`, `/profil/username` | Modification du profil |
| GET | `/usersList` | Liste des utilisateurs (admin) |

### API JSON

| Méthode | Route | Action |
|---|---|---|
| GET | `/api/categories` | Liste des catégories |
| GET | `/api/categories/{id}/articles` | Articles d'une catégorie |
| GET | `/api/articles` | Liste des articles (supporte `?sort=`) |
| GET | `/api/articles/{id}` | Article complet |
| GET | `/api/auteurs/{id}/articles` | Articles d'un auteur |
| GET | `/api/auteurs` | Liste des auteurs |

---

## Installation locale

> Section à compléter selon votre fichier `docker-compose.yml` final.

```bash
git clone https://github.com/Jordan-Vigneulle/Minipress.git
cd Minipress
docker-compose up -d --build
```

L'API est alors disponible sur `http://localhost:29029/api/...` et l'interface d'administration sur `http://localhost:29029`.


