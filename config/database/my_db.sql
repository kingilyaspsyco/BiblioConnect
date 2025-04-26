-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : sam. 26 avr. 2025 à 02:51
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `my_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE `auteur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `auteur`
--

INSERT INTO `auteur` (`id`, `nom`, `prenom`) VALUES
(29, 'victor hugo', 'Hortense'),
(30, 'ilyas', 'elaoufir'),
(31, 'Vaillant', 'Didi'),
(32, 'Morele3e3e', 'Louis'),
(34, 'douae', 'naciri'),
(35, 'hajar', 'hamidi'),
(36, 'Ghassane', 'hamidi'),
(37, 'samir', 'el aoufir'),
(38, 'elaoufir', 'ilyas'),
(39, 'Louis', 'enrique');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(49, 'Drame'),
(50, 'enim'),
(51, 'War'),
(54, 'action'),
(55, 'Adventure');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `livre_id` int(11) NOT NULL,
  `contenu` varchar(255) DEFAULT NULL,
  `note` int(11) DEFAULT NULL,
  `createdat` datetime NOT NULL,
  `is_approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id`, `user_id`, `livre_id`, `contenu`, `note`, `createdat`, `is_approved`) VALUES
(11, 10, 156, 'hada wa3r', 4, '2025-04-25 23:01:08', 1);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `langue`
--

CREATE TABLE `langue` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `langue`
--

INSERT INTO `langue` (`id`, `nom`) VALUES
(16, 'Français'),
(17, 'Anglais'),
(18, 'Espagnole'),
(19, 'Arabe'),
(20, 'Japonais'),
(21, 'Chineese');

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE `livre` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL,
  `dateajout` datetime NOT NULL,
  `auteur_id` int(11) DEFAULT NULL,
  `langue_id` int(11) DEFAULT NULL,
  `note_moyenne` double DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `nombre_pages` int(11) DEFAULT NULL,
  `annee_publication` int(11) DEFAULT NULL,
  `image_couverture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`id`, `categorie_id`, `titre`, `description`, `disponible`, `dateajout`, `auteur_id`, `langue_id`, `note_moyenne`, `isbn`, `nombre_pages`, `annee_publication`, `image_couverture`) VALUES
(154, 54, 'dernier jour d\'un condamné', 'Le Dernier Jour d\'un Condamné est un roman engagé de Victor Hugo, publié en 1829. Cette œuvre marquante constitue un plaidoyer contre la peine de mort, sujet qui tenait particulièrement à cœur à l\'auteur.', 0, '2025-04-26 21:50:00', 32, 16, NULL, '122233334445', 222, 2001, 'dr-680be989375f8.jpg'),
(155, 51, 'Un long chemin vers la liberté', 'Commencés en 1974 au pénitencier de Robben Island, ces souvenirs furent achevés par Nelson Mandela après sa libération, en 1990, à l\'issue de vingt-sept années de détention.\r\nRarement une destinée individuelle se sera aussi étroitement confondue avec le c', 1, '2025-04-04 22:26:00', 31, 16, NULL, '978-2253140634', 300, 1989, 'ee-680bf0347866b.jpg'),
(156, 54, 'Elon Musk', 'Pendant deux ans, l’auteur de la biographie autorisée de Steve Jobs a marché dans les pas d’Elon Musk, afin de partager son regard sur l’innovateur le plus fascinant et le plus controversé de la planète, celui qui a fait entrer le monde dans l’ère des voi', 1, '2025-04-01 22:28:00', 34, 17, 4, '978-2253143333', 250, 2023, '71RidqZa-sL-SY522-680bf0b5706a0.jpg'),
(158, 51, 'douae lbakkaya', 'e3e3', 1, '2025-04-18 02:39:00', 35, 17, NULL, '3333', 222, 2001, 'biblio-680c2b59e7c03.png');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `livre_id` int(11) NOT NULL,
  `datereservation` datetime NOT NULL,
  `statut` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `livre_id`, `datereservation`, `statut`) VALUES
(21, 9, 154, '2025-04-25 20:00:40', 'confirmee');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `createdat` datetime NOT NULL,
  `isverified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `createdat`, `isverified`) VALUES
(6, 'ilyaselaoufir07@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$HjGFBawUyEYh/VUGQ7yJJe5yr3e/6cffF/uo6yXml/rS1EZzLw/ba', 'Ilyas', 'Elaoufir', '2025-04-25 09:19:16', 0),
(7, 'elaoufirn@gmail.com', '[\"ROLE_LIBRARIAN\"]', '$2y$13$KYM0Dkr63/ic94aMKur99uvGHGpOBrk1nj/Fmm56G/n0Ex3JCYhnW', 'Nassim', 'El Aoufir', '2025-04-25 09:24:20', 0),
(9, 'samirelaoufir64@gmail.com', '[]', '$2y$13$IUAOt8vNAb0i0B13zZnLQumkaUxzdVuoHvutQCV4ujcw7BKaH55Ge', 'Ghassane', 'Hamidi', '2025-04-25 18:32:20', 0),
(10, 'douae@gmail.com', '[]', '$2y$13$5swy6FBr0ctKLRpbhkW4GOyF0.RucjX3RMQsOvOF3f4zeUQkYDnoO', 'Ghassane', 'Hamidi', '2025-04-25 22:59:07', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `auteur`
--
ALTER TABLE `auteur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_67F068BCA76ED395` (`user_id`),
  ADD KEY `IDX_67F068BC37D925CB` (`livre_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `langue`
--
ALTER TABLE `langue`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AC634F99BCF5E72D` (`categorie_id`),
  ADD KEY `IDX_AC634F9960BB6FE6` (`auteur_id`),
  ADD KEY `IDX_AC634F992AADBACD` (`langue_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_42C84955A76ED395` (`user_id`),
  ADD KEY `IDX_42C8495537D925CB` (`livre_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `auteur`
--
ALTER TABLE `auteur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `langue`
--
ALTER TABLE `langue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_67F068BC37D925CB` FOREIGN KEY (`livre_id`) REFERENCES `livre` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_67F068BCA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `FK_AC634F992AADBACD` FOREIGN KEY (`langue_id`) REFERENCES `langue` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_AC634F9960BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `auteur` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_AC634F99BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_42C8495537D925CB` FOREIGN KEY (`livre_id`) REFERENCES `livre` (`id`),
  ADD CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
