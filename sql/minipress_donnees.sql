-- Catégories
INSERT INTO `categorie` (`id`, `titre`) VALUES
('tech', 'Technologie'),
('culture', 'Culture'),
('science', 'Science');

-- Utilisateurs (mot de passe = "password" hashé bcrypt)
INSERT INTO `utilisateur` (`email`, `motdepasse`, `role`, `chemin_acces_img`, `pseudo`) VALUES
('jordan@minipress.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'uploads/jordan.jpg', 'Jordan'),
('auriane@minipress.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, 'uploads/auriane.jpg',   'Auriaaa');

-- Articles
INSERT INTO `article` (`id`, `titre`, `resume`, `contenu`, `date`, `id_categorie`, `id_utilisateur`) VALUES
('article-1', 'Lorem ipsum', 'Lorem ipsum dolor',
 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
 '2024-01-15', 'tech', 1),

('article-2', 'La culture du vide', 'Résumé sur la culture de Gauthier.',
 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
 '2024-02-20', 'culture', 2),

('article-3', 'Dolor sit', 'Vive le latin',
 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
 '2024-03-10', 'science', 1);

-- Images
INSERT INTO `image` (`url`) VALUES
('images/img-1.jpg'),
('images/img-2.jpg'),
('images/img-3.jpg'),
('images/img-4.jpg');

-- Liaisons image_article
INSERT INTO `image_article` (`id_image`, `id_article`) VALUES
(1, 'article-1'),
(4, 'article-1'),
(2, 'article-2'),
(3, 'article-3');