-- Catégories
INSERT INTO `categorie` (`titre`) VALUES
('Technologie'),
('Culture'),
('Science');

-- Utilisateurs (mot de passe = "password" hashé bcrypt)
INSERT INTO `utilisateur` (`email`, `motdepasse`, `role`, `chemin_acces_img`, `pseudo`) VALUES
('jordan@minipress.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'uploads/jordan.jpg', 'Jordan'),
('auriane@minipress.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, 'uploads/auriane.jpg',   'Auriaaa');

-- Articles
INSERT INTO `article` (`titre`, `resume`, `contenu`, `date`, `id_categorie`, `id_utilisateur`) VALUES
('Lorem ipsum',
 '**Lorem ipsum** — _dolor sit amet_, une introduction classique.',
 '## Lorem Ipsum\n\n**Lorem ipsum dolor sit amet**, consectetur adipiscing elit.\n\nSed do eiusmod tempor incididunt ut labore et dolore magna aliqua :\n\n- Ut enim ad minim veniam\n- Quis nostrud exercitation\n- Ullamco laboris',
 '2024-01-15', 1, 1),

('La culture du vide',
 '**La culture du vide** — _Résumé sur la culture de Gauthier._',
 '## La culture du vide\n\n**Gauthier** explore ici le concept de vide culturel avec profondeur.\n\nSed do eiusmod tempor incididunt ut labore et dolore magna aliqua :\n\n- Ut enim ad minim veniam\n- Quis nostrud exercitation\n- Ullamco laboris',
 '2024-02-20', 2, 2),

('Dolor sit',
 '**Dolor sit** — _Vive le latin !_ Une ode à la langue de Cicéron.',
 "## Dolor Sit\n\n_Vive le latin !_ Cette langue ancienne reste une source d'inspiration inépuisable.\n\n**Dolor sit amet**, consectetur adipiscing elit :\n\n- Ut enim ad minim veniam\n- Quis nostrud exercitation\n- Ullamco laboris",
 '2024-03-10', 3, 1);

-- Images
INSERT INTO `image` (`url`) VALUES
('/images/articles/cat-taking-a-selfie.webp'),
('/images/articles/femme-travaillant-de-la-maison-avec-ses-chats.webp'),
('/images/articles/mignon-chaton-jouant.webp'),
('/images/articles/thats-funny.webp');

-- Liaisons image_article
INSERT INTO `image_article` (`id_image`, `id_article`) VALUES
(1, 1),
(4, 1),
(2, 2),
(3, 3);