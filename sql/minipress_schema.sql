SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET NAMES utf8mb4 */;

-- 1. categorie
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- 2. utilisateur
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `motdepasse` varchar(256) NOT NULL,
  `role` tinyint(4) DEFAULT NULL, -- 100 = admin, 1 = utilisateur, 2 = auteur
  `chemin_acces_img` varchar(256) DEFAULT NULL,
  `pseudo` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_pk2` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- 3. article
CREATE TABLE `article` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
  `titre` varchar(255) NOT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `contenu` text NOT NULL,
  `date` date DEFAULT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `est_publie` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_article_categorie`
    FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_article_utilisateur`
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- 4. image
CREATE TABLE `image` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- 5. image_article
CREATE TABLE `image_article` (
  `id_image` int(11) UNSIGNED NOT NULL,
  `id_article` int(11) UNSIGNED NOT NULL,       
  PRIMARY KEY (`id_image`, `id_article`),
  CONSTRAINT `fk_ia_image`
    FOREIGN KEY (`id_image`) REFERENCES `image` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ia_article`
    FOREIGN KEY (`id_article`) REFERENCES `article` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

COMMIT;