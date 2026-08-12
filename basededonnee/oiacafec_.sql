-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 12 août 2026 à 10:30
-- Version du serveur : 10.11.18-MariaDB-0+deb12u1
-- Version de PHP : 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `oiacafec_`
--

-- --------------------------------------------------------

--
-- Structure de la table `actes_oia`
--

CREATE TABLE `actes_oia` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `pdf_file` varchar(255) NOT NULL,
  `date_pub` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `actes_oia`
--

INSERT INTO `actes_oia` (`id`, `title`, `slug`, `description`, `pdf_file`, `date_pub`, `is_published`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Ordonnance n° 2011-481 du 28 dscembre 2011 fixant las regles relatives ala Commercialisation du Cafe at du Cacao et a la Regulation de la Filiere Cafe-Cacao', 'ordonnance-n-2011-481-du-28-dscembre-2011-fixant-las-regles-relatives-ala-commercialisation-du-cafe-at-du-cacao-et-a-la-regulation-de-la-filiere-cafe-cacao', NULL, 'uploads/actes/acte_6a57b1ca1d14b_1784132042.pdf', '2026-07-15', 1, 1, '2026-07-15 14:14:02', '2026-07-15 14:14:02'),
(3, 'Décret n02012-1 009 du 17 octobre 2012 fixant les conditions d\'exercice de la profession d\'acheteur de produits café et cacao', 'decret-n02012-1-009-du-17-octobre-2012-fixant-les-conditions-d-exercice-de-la-profession-d-acheteur-de-produits-cafe-et-cacao', NULL, 'uploads/actes/acte_6a57b202377b6_1784132098.pdf', '2026-07-15', 1, 1, '2026-07-15 14:14:58', '2026-07-15 14:14:58'),
(5, 'Vu le décret n° 86-491 du 9 juillet 1986, portant nomination des membres du Gouvernement de la République de Côte d\'Ivoire;', 'vu-le-decret-n-86-491-du-9-juillet-1986-portant-nomination-des-membres-du-gouvernement-de-la-republique-de-cote-d-ivoire-1', NULL, 'uploads/actes/acte_6a57b22c0e2fd_1784132140.pdf', '2026-07-15', 1, 1, '2026-07-15 14:15:40', '2026-07-15 14:15:40');

-- --------------------------------------------------------

--
-- Structure de la table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `model` varchar(100) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `model`, `model_id`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-01 14:37:00'),
(2, 1, 'Connexion administrateur', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-01 14:37:00'),
(3, 1, 'login', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-02 09:41:19'),
(4, 1, 'Connexion administrateur', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-02 09:41:19'),
(5, 1, 'logout', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-03 12:57:01'),
(6, 1, 'login', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-03 12:57:08'),
(7, 1, 'Connexion administrateur', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-03 12:57:08'),
(8, 1, 'login', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-06 10:56:41'),
(9, 1, 'Connexion administrateur', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-06 10:56:41'),
(10, 1, 'login', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-07 07:50:05'),
(11, 1, 'Connexion administrateur', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-07 07:50:05'),
(12, 1, 'login', 'User', 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-07 14:06:56'),
(13, 1, 'Connexion administrateur', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-07 14:06:56'),
(14, 1, 'login', 'User', 1, NULL, '102.209.220.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-15 11:21:58'),
(15, 1, 'Connexion administrateur', NULL, NULL, NULL, '102.209.220.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-15 11:21:58'),
(16, 1, 'login', 'User', 1, NULL, '74.118.126.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-15 14:41:59'),
(17, 1, 'Connexion administrateur', NULL, NULL, NULL, '74.118.126.34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-15 14:41:59'),
(18, 1, 'login', 'User', 1, NULL, '102.209.217.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-29 12:04:11'),
(19, 1, 'Connexion administrateur', NULL, NULL, NULL, '102.209.217.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-29 12:04:11'),
(20, 1, 'login', 'User', 1, NULL, '102.209.217.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-29 13:23:54'),
(21, 1, 'Connexion administrateur', NULL, NULL, NULL, '102.209.217.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-29 13:23:54'),
(22, 1, 'login', 'User', 1, NULL, '196.192.125.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-29 15:32:51'),
(23, 1, 'Connexion administrateur', NULL, NULL, NULL, '196.192.125.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-29 15:32:51'),
(24, 1, 'login', 'User', 1, NULL, '160.155.241.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-06 14:09:11'),
(25, 1, 'Connexion administrateur', NULL, NULL, NULL, '160.155.241.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-06 14:09:11'),
(26, 1, 'login', 'User', 1, NULL, '102.209.221.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-10 11:23:33'),
(27, 1, 'Connexion administrateur', NULL, NULL, NULL, '102.209.221.131', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-10 11:23:33');

-- --------------------------------------------------------

--
-- Structure de la table `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agenda`
--

INSERT INTO `agenda` (`id`, `title`, `slug`, `description`, `start_date`, `end_date`, `start_time`, `end_time`, `location`, `address`, `image`, `is_published`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Ouverture de la campgne', 'ouverture-de-la-campgne', '1er septembre au 28 février : Ouverture de la grande campagne principale de commercialisation du cacao.', '2026-07-15', '2026-07-02', '16:09:00', '16:09:00', 'Côte d\'Ivoire', '', NULL, 1, 1, '2026-07-15 14:10:48', '2026-07-15 14:10:55'),
(3, 'Journée nationale de l\'OIA CAFE-CACAO', 'journee-nationale-de-l-oia-cafe-cacao', 'La première Journée nationale de l\'Organisation interprofessionnelle agricole (OIA) Café-Cacao s\'est tenue du 24 au 26 juillet 2026, avec une grande cérémonie officielle le samedi 25 juillet 2026 à Duékoué. Cet événement a rassemblé plus de 10 000', '2026-07-24', '2026-08-26', '06:00:00', '23:59:00', 'Duekoué', 'Duekoué', NULL, 1, 1, '2026-08-06 13:00:31', '2026-08-06 13:00:44');

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` text NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `status`, `is_featured`, `views`, `published_at`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'Un modèle historique universel', 'un-modele-historique-universel', 'En ce moment même, après les pluies torrentielles de 2023, les cours du cacao s’envolent. Les industriels craignent une crise de la production en Côte d’Ivoire et au Ghana, les deux premiers pays exportateurs. Est-ce purement conjoncturel ? A partir de l’histoire de la production de cacao, François Ruf propose un cadre d’analyse qui va plus loin. Dossier en trois parties.', 'En ce moment même, après les pluies torrentielles de 2023, les cours du cacao s’envolent. Les industriels craignent une crise de la production en Côte d’Ivoire et au Ghana, les deux premiers pays exportateurs. Est-ce purement conjoncturel ? A partir de l’histoire de la production de cacao, François Ruf propose un cadre d’analyse qui va plus loin. Dossier en trois parties.\r\n\r\nPar François Ruf, CIRAD, SADRCI\r\n\r\nEn 2023, les cours s’envolent\r\nEn 2023, le cours mondial du cacao bondit de 2500 à 4000 $/tonne. Cette hausse brutale prouve que l’industrie du chocolat s’inquiète vraiment et anticipe une sérieuse baisse de ses approvisionnements en cacao pour la campagne 2023/24. La Côte d’Ivoire et le Ghana, respectivement premier et second producteur mondial, qui représentent ensemble près des 2/3 de la production de cacao de la planète, sont les plus concernés. En Côte d’Ivoire, en juillet 2022, le directeur du Conseil du Café et du Cacao (CCC), a annoncé une baisse de la production de 25% et l’a attribuée à une pluviométrie excessive. Les annonces alarmistes sur les « pluies torrentielles » et des pertes encore plus sévères se sont multipliées (Le Monde, 14 juillet 2023 ; Africanews, 29 novembre 2023). 1 La Côte d’Ivoire et le Ghana seraient les victimes d’un choc climatique externe dans lequel ils n’auraient aucune responsabilité.\r\n\r\nOr quand le cours mondial s’effondrait, le discours officiel présentait également la Côte d’Ivoire et le Ghana comme les victimes d’un choc économique externe, cette fois plus ou moins du fait des multinationales ou d’une supposée baisse de la consommation pendant la pandémie du covid. Nous avions alors rappelé que les politiques publiques de certains pays producteurs, et notamment de la Côte d’Ivoire depuis des décennies, tous régimes politiques confondus, ont une grande part de responsabilité. En laissant des centaines de milliers de migrants s’infiltrer dans leurs forêts, y compris dans les forêts supposées protégées, elles contribuent à inonder le marché mondial de cacao et génèrent en partie ce choc économique (Ecofin, 17 juillet 2023)2.\r\n\r\nQuatre siècles d’histoire du cacao\r\nLe choc climatique, indéniable et en partie externe, pourrait bien rendre visible un changement beaucoup plus endogène et structurel en Afrique de l’Ouest et dans le monde du cacao. Il s’agit d’un changement qui se reproduit selon un modèle éprouvé, quasi « programmé » depuis le XVIe siècle par le déplacement des cycles économiques en Amérique Centrale (Ruf, 19953 ; Touzard, 19934). Un boom du cacao finit toujours par générer un blocage endogène des facteurs de production ; la production se déplace alors vers d’autres zones. Mais revenons à l’actualité.\r\n\r\nL’actualité cacaoyère en Côte d’Ivoire et au Ghana\r\nDès juillet 2023, des planteurs témoignent des pluies torrentielles menaçant la production : les fleurs avortent, la pourriture brune attaque les cabosses (Le Monde, 14 juillet 2013). D’après nos observations, le vent aurait également joué un rôle dans la chute des fleurs. Il est aussi possible que le cacaoyer se repose après une période de forte production entre avril et juin 2023. En septembre 2023, l’International Cocoa Organization (ICCO5) insiste sur le caractère conjoncturel de la baisse de production et appelle à la prudence dans l’interprétation, notamment en Côte d’Ivoire, parce qu’un rattrapage est possible au cours de l’année6. En effet, une nouvelle floraison en novembre pourrait donner du cacao en mars-avril, avec un simple décalage du calendrier de production au cours de l’année.\r\n\r\nMais fin novembre, les fleurs restent discrètes dans bien des régions, les interrogations demeurent, et le cours mondial continue de monter, dépassant 4200 $ par tonne au 1er décembre. Nombre d’articles et de communiqués émanant des multinationales préparent les consommateurs à une flambée du prix des chocolats de Noël.\r\n\r\nMême si le facteur climatique « pluies torrentielles et vent » joue un rôle important, même s’il devait y avoir un rattrapage partiel en 2024, cette baisse de production attendue et ce pic du cours mondial relèvent-ils seulement d’un phénomène climatique ?\r\n\r\n« Quelques difficultés de commercialisation internes »\r\nAu Ghana, où la production a déjà chuté de 30% en 2021-2022, l’ICCO avançait pour l’expliquer quelques « difficultés de commercialisation internes » et déjà un problème climatique, cette fois une sécheresse (ICCO, 2022).7 Mais une chute de la production de 30% nécessiterait une analyse plus approfondie. Le très faible prix du cacao avait aussi de quoi décourager les planteurs du pays et apparaissait déjà quasiment comme une incitation à négliger l’entretien des plantations et à se diversifier pour aller vers l’orpaillage, la culture de l’hévéa, voire du cocotier dans la région de Kumasi (Ruf, 2022).8\r\n\r\nDans les deux pays, le prix payé au producteur est fixé à chaque début de campagne par des structures nationales, le ‘CCC’ en Côte d’Ivoire et le ‘Cocobod’ au Ghana. En septembre 2023, le Ghana décide d’une hausse de 50% du prix payé au producteur, ce qui paraissait élevé en monnaie courante, le GHC. Mais comme ce prix reste fixe durant toute la campagne, il devient bien faible en décembre 2023 au regard du cours mondial en US$, en pleine ascension. Les observateurs confirment en outre que les planteurs ghanéens, très loin de profiter du pic du cours mondial, subissent des retards de paiement. (Uncommon cacao, 2023)9.\r\n\r\nLa maladie du « swollen shoot« \r\nQuelques témoignages rapportent aussi les inquiétudes des multinationales du cacao et de chercheurs sur l’essor de la maladie virale du « swollen shoot » 10 dans les deux pays11 .\r\n\r\nFin 2016, à l’occasion d’une visite en Côte d’Ivoire du ministre français de l’agriculture Stéphane Le Foll, qui fut reçu par le président Ouattara, le directeur général du CIRAD à l’époque, Michel Eddy, attira l’attention du président sur les graves dangers de la maladie du swollen shoot.\r\n\r\nSelon Jeune Afrique, le directeur du CCC aurait aussi expliqué la baisse de la production par la contrebande aux frontières. Cette contrebande jouant sur le différentiel des prix payés au producteur entre la Côte d’Ivoire et les pays voisins, il la qualifia de « criminelle ». Selon Jeune Afrique, la politique qui interdit de distribuer des plants hybrides depuis 2019 aurait aussi conduit à une baisse des rendements et de la production (Jeune Afrique, 30 novembre 2023).12.\r\n\r\nDes facteurs liés ?\r\nAinsi, plusieurs facteurs, réels ou supposés, auraient impacté la production de cacao dans les pays qui fournissent les deux tiers des besoins mondiaux, progressivement, et au fil des mois : pluies torrentielles et pertes des fleurs, maladies, faible prix payé au producteur, difficultés de commercialisation interne, contrebande, politique de restriction sur le matériel végétal (en Côte d’Ivoire mais pas du tout au Ghana). Certains semblent évidemment liés, comme le prix bas incitant à la contrebande, mais dans l’ensemble cette liste ne forme pas un ensemble cohérent.\r\n\r\nPourtant ces facteurs pourraient être beaucoup plus liés qu’il n’y paraît. Un changement structurel et quasi inéluctable pourrait s’amorcer, suivant un modèle des « cycles du cacao » bien connu (Ruf 1995 et Ruf, 201615 Ce pourrait être là le début d’un nouveau cycle du cacao, selon le modèle qui se répète depuis 4 siècles (Ruf, 1992).16\r\n\r\nLe modèle des cycles du cacao\r\nDepuis quatre siècles, la déforestation est constitutive de tous les booms du cacao. En combinaison avec le travail de millions de migrants, la forêt tropicale est le facteur de production universel du cacao (Ruf, 1995). A un moment donné, une population pauvre apprend qu’elle a la possibilité de trouver du travail en forêt. Ceux qui en ont l’opportunité prennent le risque de la migration (Fig.1)\r\n\r\nLa forêt leur apporte à la fois une terre et une « rente forêt » : la terre est fertile, pleine d’humus, la pression des mauvaises herbes est faible, la pluviométrie abondante et utile ; il y a peu de maladies et peu d’insectes. Le cacaoyer prospère dans ce milieu naturel, avec du travail abondant, donc un faible coût de production (Ruf, 1987).', 'uploads/articles/article_6a57ad4fe425e_1784130895.webp', 'published', 1, 6, '2026-07-15 15:54:55', '2026-07-15 15:54:56', '2026-07-17 23:18:38'),
(3, 1, 1, 'Marché du cacao: changement de régime', 'marche-du-cacao-changement-de-regime', 'Le marché ne spécule plus seulement sur la prochaine récolte. Il redécouvre que le cacao est redevenu un marché structurellement tendu.', 'Les dernières données d’Afrique de l’Ouest, en ravivant les craintes de déficit, confirment un changement de régime durable, qui pourrait plonger le cacao dans une troisième année consécutive de tensions, avec des répercussions majeures sur les prix mondiaux.\r\n\r\nIl y a encore trois semaines, le débat semblait tranché. Les arrivages ivoiriens progressaient de 18% par rapport à l’an dernier, les stocks certifiés ICE retrouvaient leur plus haut niveau depuis près de deux ans et les broyages européens tombaient à leur plus bas niveau pour un premier trimestre depuis dix-sept ans. L’histoire était simple: l’offre redevenait abondante tandis que la demande ralentissait. Le marché vendait, et il avait de bonnes raisons de le faire. En quelques séances, ce scénario s’est pourtant fissuré. Le cacao à New York a progressé de près de 20% entre le 22 et le 26 juin, signant sa meilleure performance hebdomadaire depuis novembre 2024, avant de franchir les 5100 dollars la tonne, un sommet de cinq mois. Cette hausse ne traduit pas un simple accès de nervosité. Elle révèle un changement plus profond: le marché ne regarde plus le cacao de la même manière.\r\n\r\nLes données de terrain prennent le dessus\r\n\r\nTrois éléments ont fait basculer les anticipations. Le premier est la décision de la Côte d’Ivoire de suspendre les ventes de contrats export pour la campagne 2026/27 afin de réévaluer les perspectives de production. Une décision rare, qui traduit moins une volonté d’intervenir sur les prix qu’une incertitude inhabituelle des autorités elles-mêmes. Le deuxième provient des observations réalisées sur le terrain. Selon une enquête menée par Bloomberg auprès de plusieurs négociants internationaux, les comptages de cabosses en Côte d’Ivoire et au Ghana seraient les plus faibles observés à cette période depuis plusieurs décennies. La récolte ivoirienne pourrait ainsi revenir autour de 1,8 million de tonnes, contre près de 2,2 millions cette saison, retrouvant quasiment le niveau de 2023/24, l’année où les prix mondiaux avaient triplé. Enfin, les producteurs ghanéens alertent sur des précipitations exceptionnellement abondantes, en lien avec un potentiel «super El Niño» (un phénomène climatique qui modifie les régimes de pluie et de température à l’échelle mondiale), qui favorisent la chute des fleurs avant leur transformation en cabosses et dégradent progressivement le potentiel de production. Pris isolément, chacun de ces éléments pourrait être relativisé. Ensemble, ils changent la lecture du marché.\r\n\r\nPourquoi les prix réagissent aussi vite\r\n\r\nLa réaction des prix peut paraître disproportionnée. Elle ne l’est pas. Après deux campagnes successives marquées par des déficits, le marché dispose de beaucoup moins de marges de sécurité qu’auparavant. Les opérateurs savent désormais qu’une révision relativement limitée des perspectives de récolte peut suffire à modifier l’équilibre mondial. Autrement dit, le cacao est entré dans un régime où les informations de terrain comptent davantage que les estimations théoriques. Quelques milliers de cabosses observées en moins suffisent aujourd’hui à déplacer les anticipations et, avec elles, les prix.\r\n\r\nUn changement de régime plus qu’un changement de chiffres\r\n\r\nFaut-il pour autant considérer que le scénario d’excédent est définitivement abandonné? Pas encore. Les disponibilités physiques restent importantes et Rabobank continue d’anticiper un léger surplus pour la campagne 2026/27.Mais l’évolution la plus intéressante ne concerne pas les prévisions elles-mêmes. Elle concerne la manière dont elles se répartissent. Il y a quelques semaines encore, la plupart des grandes maisons de recherche envisageaient un excédent relativement confortable. Désormais, une partie d’entre elles évoquent la possibilité d’un déficit. Le débat ne porte donc plus sur l’ampleur d’un surplus. Il oppose désormais un léger excédent à un retour du déficit. La nuance peut sembler limitée. En réalité, elle est considérable. Sur un marché où les stocks ont été profondément entamés par les campagnes précédentes, une variation relativement faible de la production suffit désormais à modifier durablement l’équilibre des prix.\r\n\r\nLe test décisif de juillet\r\n\r\nLes prochains comptages de cabosses, attendus au cours du mois de juillet, permettront d’affiner les perspectives de la récolte principale d’octobre.Mais le véritable changement est peut-être déjà intervenu. Le marché ne spécule plus seulement sur la prochaine récolte. Il redécouvre que le cacao est redevenu un marché structurellement tendu, dans lequel la moindre déception sur l’offre peut faire disparaître la perspective d’un excédent. Les prochaines semaines ne diront donc pas seulement si la récolte sera bonne ou mauvaise. Elles permettront de savoir si le cacao entre dans une troisième campagne consécutive de tensions structurelles, avec toutes les conséquences que cela implique pour les prix mondiaux.', 'uploads/articles/article_6a57afb004493_1784131504.jpg', 'published', 1, 5, '2026-07-15 16:05:04', '2026-07-15 16:05:04', '2026-07-29 12:11:41'),
(4, 1, 1, 'Cacao : l\'idée d\'un cartel regroupant les principaux producteurs africains prend de l\'ampleur', 'cacao-l-idee-d-un-cartel-regroupant-les-principaux-producteurs-africains-prend-de-l-ampleur', '(Agence Ecofin) - L\'Afrique assure environ 70 % de la production mondiale de cacao. Face à la volatilité des cours et aux nouveaux défis de la filière, les principaux pays producteurs du continent cherchent à renforcer leur coordination afin de mieux défendre leurs intérêts sur le marché mondial.', 'Abuja accueillera, ce 14 juillet 2026, le Sommet sur la valeur ajoutée dans le cacao, réunissant le Nigeria, pays hôte, la Côte d’Ivoire, le Ghana et le Cameroun. Selon un communiqué publié par le ministère nigérian de l’Industrie, cet événement sera l’occasion d’établir une « Alliance pour la valeur ajoutée dans le cacao ».\r\n\r\nConcrètement, il s’agit d’une plateforme destinée à permettre aux quatre pays, qui représentent près de 66 % de la production mondiale de cacao, de négocier, de définir des normes et de dialoguer avec les marchés internationaux en tant que bloc uni. Ce n’est pas la première fois que l’idée d’un regroupement de ces géants de la production mondiale de cacao est évoquée.\r\n\r\nQuelques semaines plus tôt, la Côte d’Ivoire et le Ghana avaient déjà affiché leur volonté d’élargir leur coopération au Nigeria ainsi qu’au Cameroun, à travers l’Initiative Cacao Côte d’Ivoire-Ghana. Cette organisation intergouvernementale, créée en 2018 par les deux premiers producteurs mondiaux de cacao, vise à réguler le marché, à défendre les revenus des planteurs et à accroître leur pouvoir de négociation face aux multinationales.\r\n\r\nLors d’un sommet de haut niveau tenu le 16 juin 2026 à Abidjan, dans le cadre de cette initiative, les présidents ivoirien Alassane Ouattara et ghanéen John Dramani Mahama avaient réaffirmé leur ambition de renforcer la coordination entre producteurs africains. Les deux pays sont notamment convenus d’harmoniser leurs politiques de prix bord champ et d’aligner leurs calendriers de la campagne de commercialisation à compter de la campagne 2026/2027.\r\n\r\nEn cherchant à coordonner leurs politiques et à parler d\'une seule voix, les principaux producteurs africains s\'inspirent d\'une logique déjà observée sur d\'autres marchés de matières premières, où la coopération entre pays producteurs vise à renforcer leur pouvoir de négociation. À ce stade, toutefois, aucun mécanisme de contrôle de la production ou des exportations, caractéristique d\'un véritable cartel, n\'a encore été adopté.\r\n\r\nQuels enjeux ?\r\n\r\nAu-delà de la coordination politique, cette initiative répond à plusieurs enjeux économiques. Malgré leur domination sur la production, les pays africains restent largement positionnés sur les segments amont de la chaîne de valeur, tandis que la transformation finale et la commercialisation des produits chocolatés demeurent concentrées dans les grands marchés consommateurs. Dans ce contexte, ils captent moins de valeur ajoutée et sont davantage exposés à la volatilité des cours du cacao.\r\n\r\nAprès avoir atteint un record historique de 12 906 dollars la tonne en décembre 2024, les cours du cacao sont retombés autour de 3 000 à 4 000 dollars au premier semestre 2026 avant de rebondir aux environs de 6 000 dollars au début du mois de juillet. Cette forte volatilité a ravivé les incertitudes pour les producteurs, qui ne bénéficient pas toujours pleinement des variations des cours internationaux.\r\n\r\nL’Alliance pour la valeur ajoutée dans le cacao, annoncée à Abuja, vise justement à « renforcer la transformation locale, à attirer davantage d’investissements industriels et à coordonner les positions africaines face aux nouvelles exigences du commerce mondial, notamment en matière de traçabilité et de durabilité ».\r\n\r\nRappelons que le Règlement européen sur la déforestation (EUDR), qui entrera en application pour les grands et moyens opérateurs à partir du 30 décembre 2026, constitue également un sujet de préoccupation pour les producteurs africains. Cette réglementation impose une traçabilité au niveau des parcelles pour tout le cacao entrant sur le marché européen, qui absorbe environ 60 % des exportations mondiales de cacao.\r\n\r\n« L’Alliance s’engage à adopter une position commune sur la mise en œuvre de cette réglementation, notamment pour obtenir la reconnaissance des systèmes nationaux de traçabilité et défendre le principe selon lequel les coûts de mise en conformité ne doivent pas être supportés par les petits producteurs », souligne le ministère nigérian de l’Industrie. Le défi sera désormais de transformer cette volonté politique en mécanismes de coopération concrets et durables.\r\n\r\nStéphanas Assocle\r\n\r\nLire aussi:', 'uploads/articles/article_6a57b012a1af5_1784131602.jpg', 'published', 1, 10, '2026-07-15 16:06:42', '2026-07-15 16:06:42', '2026-07-28 16:36:02'),
(5, 1, 1, 'Côte d\'Ivoire : Duékoué, Bruno Nabagné Koné lance la nouvelle dynamique de l\'OIA Café-Cacao devant plus de 12000 producteurs', 'cote-d-ivoire-duekoue-bruno-nabagne-kone-lance-la-nouvelle-dynamique-de-l-oia-cafe-cacao-devant-plus-de-12000-producteurs', 'Duékoué a vécu, samedi 25 juillet 2026, une journée historique pour la filière café-cacao. Plus de 12 000 producteurs, venus des treize délégations régionales de Côte d\'Ivoire, ont convergé vers la Place des Fêtes à l\'occasion de la première Journée nationale de l\'Organisation interprofessionnelle agricole de la filière Café-Cacao (OIA Café-Cacao), officiellement reconnue le 3 décembre 2025.', 'Duékoué a vécu, samedi 25 juillet 2026, une journée historique pour la filière café-cacao. Plus de 12 000 producteurs, venus des treize délégations régionales de Côte d\'Ivoire, ont convergé vers la Place des Fêtes à l\'occasion de la première Journée nationale de l\'Organisation interprofessionnelle agricole de la filière Café-Cacao (OIA Café-Cacao), officiellement reconnue le 3 décembre 2025.\r\n\r\n\r\nDès les premières heures de la matinée, la cité de l\'ouest ivoirien s\'est animée au rythme des arrivées de cars, de minicars et de motos transportant des milliers de producteurs, responsables de coopératives et acteurs de la filière. Dans une ambiance festive, marquée par des chants, des danses et des messages de mobilisation, cette forte affluence a illustré les attentes suscitées par cette nouvelle organisation appelée à fédérer l\'ensemble des maillons de la chaîne café-cacao.\r\n\r\n\r\nPlacée sous le parrainage du ministre de l\'Agriculture, du Développement rural et des Productions vivrières, Bruno Nabagné Koné, la cérémonie a également été marquée par le lancement officiel d\'une campagne nationale de distribution gratuite de produits phytosanitaires homologués.\r\n\r\n\r\nFace à une assistance nombreuse, le ministre a exhorté les producteurs à renforcer leur cohésion afin de relever les défis auxquels la filière est confrontée.\r\n\r\n\r\nSelon lui, les nouvelles exigences des marchés internationaux doivent être perçues comme une opportunité d\'améliorer la qualité de la production nationale et de consolider le leadership de la Côte d\'Ivoire sur le marché mondial du cacao.\r\n\r\n\r\nIl a réaffirmé la volonté du gouvernement de poursuivre les réformes engagées pour améliorer durablement les revenus des producteurs, développer la transformation locale et renforcer la compétitivité de la filière.\r\n\r\n\r\nLe ministre a également insisté sur l\'importance d\'accroître les rendements sur les superficies déjà exploitées afin de concilier performance agricole et préservation du couvert forestier.\r\n\r\n\r\nRéitérant l\'engagement de l\'État aux côtés des producteurs, il a rappelé que le Président Alassane Ouattara fait du développement de la filière café-cacao un axe majeur de sa politique agricole.\r\n\r\n\r\nLe Directeur général du Conseil du Café-Cacao, Koné Brahima Yves, a invité les producteurs à anticiper l\'entrée en vigueur, au 1er janvier 2027, des nouvelles normes du marché européen.\r\nIl a présenté le Système national de traçabilité, qui permettra de suivre chaque cargaison de cacao depuis la plantation jusqu\'au port d\'exportation, renforçant ainsi la transparence de la filière et la valorisation du cacao ivoirien sur les marchés internationaux.\r\n\r\n\r\nLe responsable a également insisté sur l\'importance de l\'enregistrement des producteurs afin qu\'ils obtiennent leur carte professionnelle, désormais indispensable pour commercialiser leur production dans le respect des nouvelles règles.\r\n\r\n\r\nIl a, en outre, encouragé les planteurs à privilégier exclusivement l\'utilisation de produits phytosanitaires homologués afin de garantir la qualité des récoltes et la conformité des exportations.\r\n\r\n\r\nPour le président de l\'OIA Café-Cacao, Issiaka Diakité, cette première Journée nationale marque le début d\'une nouvelle étape pour la gouvernance de la filière.\r\n\r\n\r\nIl a présenté l\'organisation comme un cadre de concertation réunissant producteurs, commerçants et transformateurs autour d\'un objectif commun : bâtir une filière plus moderne, plus performante et mieux préparée aux mutations du marché mondial.\r\n\r\n\r\nLa forte mobilisation enregistrée à Duékoué, a-t-il souligné, témoigne de la confiance que les acteurs accordent désormais à cette nouvelle structure interprofessionnelle.\r\n\r\n\r\nAu-delà des interventions officielles, la cérémonie a été ponctuée par la remise symbolique de bons de produits phytosanitaires à dix producteurs, donnant le coup d\'envoi d\'une vaste campagne nationale.\r\n\r\n\r\nCette opération prévoit la distribution de 2 000 cartons de produits phytosanitaires homologués au profit de près de 20 000 producteurs répartis dans les treize délégations régionales de l\'OIA Café-Cacao.\r\n\r\n\r\nLes autorités estiment que cette initiative permettra de protéger environ 40 000 hectares de plantations contre les maladies et les ravageurs, tout en améliorant durablement les performances des exploitations.\r\n\r\n\r\nUn dialogue direct avec les producteurs\r\nLa rencontre s\'est achevée par un échange entre les responsables de la filière et les producteurs autour de plusieurs préoccupations majeures, notamment la traçabilité, la rémunération des planteurs, la qualité des récoltes et la modernisation des exploitations.\r\n\r\n\r\nCette première Journée nationale aura ainsi permis de poser les bases d\'une gouvernance plus participative de la filière café-cacao.\r\n\r\n\r\nÀ travers cette mobilisation exceptionnelle, l\'OIA Café-Cacao affiche clairement son ambition : fédérer les différents acteurs autour d\'une vision commune afin de bâtir une filière plus compétitive, durable et créatrice de richesse pour les producteurs comme pour l\'économie ivoirienne.\r\n\r\n\r\nWassimagnon', 'uploads/articles/article_6a6a033962bf5_1785332537.jpg', 'published', 1, 9, '2026-07-29 13:42:17', '2026-07-29 13:42:17', '2026-08-09 22:53:54'),
(6, 1, 1, '1ére Journée Nationale de l\'OIA Café-Cacao : Plusieurs producteurs mobilisés à Duékoué pour une filière plus forte', '1ere-journee-nationale-de-l-oia-cafe-cacao-plusieurs-producteurs-mobilises-a-duekoue-pour-une-filiere-plus-forte', 'Les responsables de l\'OIA Café Cacao ont gagné le pari de mobiliser, le samedi 25 juillet 2026, tous les producteurs de café cacao dans la ville de Duékoué à l\'occasion de la première journée nationale de cette institution pour parler des problèmes liés à ces deux principales cultures agricoles du pays, surtout le cacao dont la Côte d\'Ivoire est le premier producteur mondial.', 'La ville de Duékoué a vécu, le samedi 25 juillet 2026, un événement majeur pour la filière café-cacao ivoirienne. À l’occasion de la première Journée nationale de l’Organisation interprofessionnelle agricole (OIA) Café-Cacao, plus de 12 000 producteurs venus des treize délégations régionales du pays se sont rassemblés afin d’échanger sur les défis et les perspectives de cette filière stratégique, dont le cacao fait de la Côte d’Ivoire le premier producteur mondial.\r\n\r\nUne forte affluence\r\nDe la veille au jour de la cérémonie, la cité de l’ouest ivoirien a enregistré une forte affluence. Des dizaines de cars, minicars et motos ont convergé vers la Place des Fêtes, transportant des milliers de producteurs, hommes, femmes et jeunes planteurs, venus représenter leurs coopératives respectives. Arborant fièrement leurs couleurs et munis de leurs cartes de producteurs, les participants ont donné à cette première édition une dimension nationale marquée par un esprit de solidarité, d’espoir et d’engagement pour l’avenir de la filière.\r\n\r\nCette mobilisation exceptionnelle marque l’entrée officielle sur la scène nationale de l’Organisation interprofessionnelle agricole de la filière Café-Cacao, reconnue officiellement le 3 décembre 2025. Au-delà de son caractère festif, cette journée s’est voulue un cadre de réflexion et d’échanges entre les producteurs, les autorités publiques et les acteurs du secteur sur les enjeux liés à la compétitivité, à la durabilité et à la modernisation de la principale richesse agricole du pays.\r\n\r\nPlusieurs hautes personnalités ont honoré l’événement de leur présence\r\nSi le Haut patron de la cérémonie, le Vice-Premier ministre Téné Birahima Ouattara, annoncé pour l’occasion, a été empêché à la dernière minute, plusieurs hautes personnalités ont honoré l’événement de leur présence. Le ministre de l’Agriculture, du Développement rural et des Productions vivrières, Bruno Nabagné Koné, parrain de la cérémonie, a conduit la délégation gouvernementale. À ses côtés figuraient notamment le directeur général du Conseil du Café-Cacao, Koné Brahima Yves, ainsi que le président du Conseil d’administration de l’OIA Café-Cacao, Issiaka Diakité.\r\n\r\nL’un des temps forts de cette première Journée nationale a été le lancement officiel d’une vaste campagne nationale de distribution gratuite de produits phytosanitaires homologués. Devant des milliers de producteurs, les autorités ont procédé à la remise symbolique de bons à dix bénéficiaires, donnant ainsi le coup d’envoi de cette opération destinée à renforcer la protection des plantations contre les maladies et les ravageurs.\r\n\r\n2 000 cartons de produits phytosanitaires au profit d’environ 20 000 producteurs\r\nSelon les responsables de la filière, cette campagne prévoit la distribution de 2 000 cartons de produits phytosanitaires au profit d’environ 20 000 producteurs répartis dans les treize délégations régionales. À terme, près de 40 000 hectares de plantations de cacao et de café devraient bénéficier de ces traitements, contribuant ainsi à améliorer les rendements, la qualité des récoltes et la durabilité des exploitations agricoles.\r\n\r\nPrenant la parole, le ministre Bruno Nabagné Koné a exhorté les producteurs à renforcer leur cohésion afin de relever ensemble les nombreux défis auxquels la filière est confrontée. « Votre union sera votre force. Restons ensemble, travaillons ensemble et avançons ensemble », a-t-il déclaré, insistant sur l’importance de l’organisation collective pour préserver le leadership mondial de la Côte d’Ivoire.\r\n\r\nBruno Koné a réaffirmé l’engagement de l’État à accompagner durablement les producteurs, rappelant que le Président de la République, Alassane Ouattara, fait de la filière café-cacao une priorité nationale\r\nLe ministre a également souligné que les nouvelles exigences des marchés internationaux, notamment en matière de qualité, de traçabilité et de durabilité, ne doivent pas être perçues comme des contraintes, mais plutôt comme des opportunités pour mieux valoriser le cacao et le café ivoiriens. Il a réaffirmé l’engagement de l’État à accompagner durablement les producteurs, rappelant que le Président de la République, Alassane Ouattara, fait de la filière café-cacao une priorité nationale.\r\n\r\nRépondant aux préoccupations exprimées par les responsables de l’OIA Café-Cacao, Bruno Nabagné Koné s’est montré rassurant. Il a indiqué avoir pris bonne note des doléances formulées par l’organisation, précisant que plusieurs d’entre elles étaient déjà en cours de mise en œuvre et que les autres bénéficieraient d’une attention particulière de la part du gouvernement.\r\n\r\nLes avancées liées au Système national de traçabilité\r\nLe directeur général du Conseil du Café-Cacao, Koné Brahima Yves, a, pour sa part, présenté les avancées liées au Système national de traçabilité. Selon lui, chaque kilogramme de cacao pourra désormais être suivi depuis la plantation jusqu’au port d’exportation. Une réforme qui permettra non seulement de répondre aux exigences des partenaires internationaux, mais aussi de renforcer la crédibilité et la valeur du cacao ivoirien sur les marchés mondiaux. Il a rappelé que la carte de producteur constitue désormais un outil indispensable pour commercialiser la production dans les meilleures conditions.\r\n\r\nLe président de l’OIA Café-Cacao, Issiaka Diakité, a qualifié cette première Journée nationale de « tournant historique » pour la filière. Il a rappelé que l’organisation se veut la maison commune des producteurs, des commerçants et des transformateurs, avec pour ambition de fédérer toutes les compétences afin de bâtir une filière moderne, performante et durable.\r\n\r\nSaluant la forte mobilisation observée à Duékoué, il a estimé que cette participation massive traduit la confiance retrouvée des producteurs envers leur organisation et ouvre une nouvelle étape dans la gouvernance de la filière café-cacao en Côte d’Ivoire.\r\n\r\n', 'uploads/articles/article_6a6a05976f5ec_1785333143.webp', 'published', 1, 4, '2026-07-29 13:52:23', '2026-07-29 13:52:23', '2026-08-10 11:24:09'),
(7, 1, 1, 'Journée Nationale de l\'OIA Café-Cacao 2026 — Duékoué, 24-26  juillet 2026', 'journee-nationale-de-l-oia-cafe-cacao-2026-duekoue-24-26-juillet-2026', '« SI NOUS VOULONS UNE FILIÈRE FORTE, NOUS DEVONS NOUS UNIR »', 'Obed Blon, la voix qui réclame et qui rassemble\r\n\r\nIl y a des discours protocolaires, et il y a des prises de parole qui, sans jamais élever le ton, parviennent à porter à la fois une fidélité, une exigence et un appel. C\'est ce registre-là qu\'a choisi Obed Blon, porte parole de l\'Organisation Interprofessionnelle Agricole (OIA) Café Cacao, lorsqu\'il s\'est adressé, au nom des familles professionnelles de la filière, à l\'assistance réunie à Duékoué pour la première Journée Nationale de l\'OIA Café-Cacao. En trois mouvements, l\'un tourné vers l\'État, l\'autre vers le Gouvernement, le dernier vers les producteurs eux-mêmes, il aura donné à cette journée sa respiration institutionnelle.\r\n\r\nUne fidélité affirmée à l\'égard de l\'État \r\nLe propos s\'ouvre par une séquence de reconnaissance qui ne doit rien au hasard : hommage à la vision portée par le Président Alassane Ouattara pour la filière café-cacao, remerciements adressés au Ministre de l\'Agriculture, salutations au Directeur Général du Conseil du Café-Cacao et au Président de l\'OIA, Monsieur Siaka Diakité. Cette abondance de témoignages n\'est pas un exercice de courtoisie gratuite : elle ancre une jeune institution, encore en construction, dans la continuité de l\'action de l\'État plutôt que dans une posture de rupture. Obed Blon en donne la formule exacte, reprise à dessein pour qu\'elle s\'impose comme une évidence partagée : \r\n\r\n« Le Conseil régule, l\'OIA rassemble, représente, dialogue et propose. » \r\nUne répartition des rôles que l\'atelier de Yamoussoukro aura permis de préciser, et que le porte-parole choisit de rappeler à Duékoué, \r\ndevant la filière tout entière, comme le socle d\'une relation désormais apaisée entre les deux institutions. Une doléance portée avec mesure \r\nMais Obed Blon n\'était pas seulement venu rendre hommage. Il portait, au nom de l\'interprofession, une demande précise : le renforcement de l\'accompagnement des producteurs, des coopératives, des exportateurs et des transformateurs, et surtout la validation, par l\'État, d\'un mécanisme de financement pérenne pour l\'OIA. Cette attente prendra la forme d\'un Cahier officiel de doléances, prochainement transmis au Gouvernement et au Conseil du Café-Cacao — non comme une sommation, mais comme l\'aboutissement naturel d\'un dialogue déjà engagé. C\'est là le cœur \r\npolitique du discours : au-delà des égards protocolaires, l\'OIA cherche les moyens durables de remplir sa mission de représentation, \r\nde formation et d\'accompagnement d\'une filière en pleine mutation. Un appel vibrant aux producteurs Le troisième temps du discours change de registre et de destinataire. Le porte-parole s\'adresse alors directement à la base de la filière, dans une tonalité fraternelle qui tranche avec la solennité protocolaire des premiers instants : \r\n\r\n« Chers producteurs, chers parents, chers frères et sœurs… »\r\n\r\nC\'est dans cette proximité retrouvée qu\'Obed Blon présente, un à un, les grands chantiers de modernisation de la filière — la carte de producteur, la norme ARS 1000, le Système National de Traçabilité — non comme des contraintes, mais comme les conditions d\'un avenir sécurisé pour les producteurs eux-mêmes et pour leurs enfants. L\'appel se referme sur cinq injonctions, aussi simples que déterminantes : \r\n▪ Faites-vous recenser ! \r\n▪ Procurez-vous votre carte de producteur ! \r\n▪ Adoptez le paiement par carte ! \r\n▪ Engagez-vous dans la norme ARS 1000 ! \r\n▪ Adhérez pleinement au Système National de Traçabilité ! \r\n\r\nCinq gestes concrets, présentés comme le prix d\'une ambition partagée : faire de la Côte d\'Ivoire, selon les mots mêmes du porte parole, « une référence mondiale d\'une filière café-cacao moderne, durable et compétitive ».\r\n\r\nEn définitive  En trois temps — la fidélité à l\'État, la doléance mesurée, l\'appel fraternel aux producteurs — Obed Blon aura livré, à Duékoué, un discours qui dépasse le cadre protocolaire de la première Journée Nationale de l\'OIA Café-Cacao. Porte parole d\'une institution encore jeune, il en aura été, ce jour-là, la voix la plus complète : celle qui rend hommage sans s\'effacer, qui réclame sans rompre, et qui rassemble sans contraindre \r\n\r\n\r\nDASSO DENIS \r\nDIRECTEURS DES PUBLICATIONS ', 'uploads/articles/article_6a749dffaf68e_1786027519.png', 'published', 1, 4, '2026-08-06 14:45:19', '2026-08-06 14:28:55', '2026-08-11 21:44:18'),
(8, 1, 1, 'JNOIACC 2026 : À DUÉKOUÉ, LE  DIRECTEUR EXÉCUTIF DE L\'OIA CAFÉ CACAO POSE LES JALONS D\'UNE  FILIÈRE EN MARCHE', 'jnoiacc-2026-a-duekoue-le-directeur-executif-de-l-oia-cafe-cacao-pose-les-jalons-d-une-filiere-en-marche', 'Devant producteurs, autorités et partenaires réunis pour la première Journée Nationale de l\'OIA, ME KOUAKOU a dressé un état des lieux précis de l\'interprofession et fixé un cap clair pour la campagne à venir', 'Duékoué, le 25 juillet 2026  |  La Tribune Élite 360° / AGRICLIMAT\r\n\r\nDuékoué a vibré, ce 25 juillet 2026, au rythme de la toute première Journée Nationale de l\'Organisation Interprofessionnelle Agricole de la filière Café-Cacao de Côte d\'Ivoire (JNOIACC 2026). Devant un parterre de producteurs, de représentants des pouvoirs publics et de partenaires techniques de la filière, le Directeur Exécutif de l\'OIA Café-Cacao CI, ME KOUAKOU, a livré une allocution dense et méthodique, dans laquelle il a tour à tour rappelé les fondements de l\'institution, détaillé son fonctionnement et annoncé plusieurs échéances majeures pour les producteurs, commerçants et transformateurs du pays. \r\n\r\nDès l\'entame de son propos, le Directeur Exécutif a tenu à replacer la naissance de l\'OIA Café-Cacao CI dans son contexte institutionnel. Créée le 21 août 2025 et reconnue par décret le 3 décembre 2025, l\'interprofession, a-t-il rappelé, s\'inscrit dans le prolongement direct de la vision portée par le Président de la République, Son Excellence Monsieur Alassane Ouattara, et dans la continuité du travail conduit par le Ministère de l\'Agriculture et le Conseil du Café-Cacao. « La cérémonie de ce jour marque le passage de l\'OIA d\'une institution en construction à une institution en action », a-t-il déclaré, sous les applaudissements de la salle.\r\n\r\n\r\nUNE GOUVERNANCE PRÉSENTÉE DANS LE DÉTAIL\r\n\r\nLe Directeur Exécutif a ensuite consacré une large partie de son intervention à la présentation de l\'architecture institutionnelle de l\'OIA. Il a détaillé devant l\'assistance la répartition en trois collèges avant de présenter un à un les quatre organes de l\'interprofession : une Assemblée Générale de 100 délégués, un Conseil d\'Administration de 35 membres présidé par Monsieur Siaka Diakité, une Commission de Conciliation et d\'Arbitrage de 10 membres présidée par Monsieur Koffi Kan Patrice, et la Direction Exécutive. \r\n\r\n\r\n« L\'OIA se veut un cadre de confiance pour aborder les sujets les plus sensibles - prix, contrats, répartition de la valeur », a insisté ME KOUAKOU, présentant l\'interprofession comme l\'espace où producteurs, commerçants et transformateurs pourront désormais négocier sous un même toit les grands équilibres économiques de la filière. \r\n\r\n\r\n\r\nDES RÉSULTATS DÉJÀ TANGIBLES POUR LES PRODUCTEURS \r\n\r\nLoin de s\'en tenir à la présentation institutionnelle, le Directeur Exécutif a tenu à illustrer son propos par des actions concrètes déjà menées par l\'OIA. Il a cité l\'atelier organisé à Yamoussoukro avec le Conseil du Café Cacao, avant d\'annoncer, sous les acclamations, la remise symbolique de bons de retrait de pesticides à des producteurs du Guémon, rendue possible par une dotation du Conseil du Café-Cacao équivalente à 40 000 hectares d\'insecticides pour 20 000 producteurs. Dix producteurs, montés sur scène pour l\'occasion, ont reçu leurs bons des mains même du Directeur Exécutif, dans une ambiance particulièrement chaleureuse. \r\n\r\n\r\nTRAÇABILITÉ, CONFORMITÉ EUROPÉENNE : LE CAP DE LA CAMPAGNE 2026-2027 \r\n\r\nC\'est sans doute l\'un des passages les plus attendus de son intervention : ME KOUAKOU a annoncé que le Système National de Traçabilité deviendra obligatoire dès l\'ouverture de la campagne 2026-2027, fixée au 1er septembre 2026, en cohérence avec le Programme ARS 1000. Il a expliqué que cette obligation répond directement aux exigences du Règlement européen sur la déforestation (RDUE/EUDR), rappelant que l\'Europe demeure le premier débouché du cacao ivoirien et que l\'accès à ce marché est désormais conditionné à la preuve de non-déforestation et à la traçabilité des productions. \r\n\r\n\r\nUN APPEL À LA MOBILISATION DE TOUS LES ACTEURS \r\n\r\nLe Directeur Exécutif a conclu son intervention par une série d\'appels directs aux producteurs, coopératives et exportateurs. Il a exhorté les producteurs qui ne se sont pas encore enrôlés à le faire sans délai pour obtenir leur carte de producteur, rappelée comme « utile pour la campagne à venir ». Il a également invité l\'ensemble des acteurs à s\'approprier le recensement engagé lors de l\'atelier de Yamoussoukro, ainsi que la Commission de Conciliation et d\'Arbitrage, présentée comme la voie de règlement à l\'amiable des différends commerciaux entre producteurs, commerçants et transformateurs. \r\n\r\n\r\nME KOUAKOU a par ailleurs affirmé sa volonté de renforcer la présence de l\'OIA sur l\'ensemble du territoire, au-delà des seules localités ayant accueilli la cérémonie de Duékoué, afin que chaque bassin de production puisse bénéficier des avancées portées par l\'interprofession. \r\n\r\n\r\nUNE ALLOCUTION SALUÉE PAR L\'ASSISTANCE \r\n\r\nLe discours de Duékoué, prononcé avec assurance et précision par le Directeur Exécutif, a été chaleureusement accueilli par l\'ensemble des participants à la JNOIACC 2026. Producteurs, autorités locales et partenaires techniques présents ont salué la clarté du propos et la cohérence de la feuille de route tracée pour l\'interprofession. Une première Journée Nationale qui restera, pour l\'OIA Café-Cacao CI, celle de l\'entrée pleine et entière dans l\'action. \r\n\r\n\r\nME KOUAKOU : L\'HOMME-PONT ENTRE LE CONSEIL DU CAFÉ-CACAO ET L\'OIA \r\n\r\nS\'il fallait résumer en une seule image le rôle que joue ME KOUAKOU à la tête de l\'OIA Café-Cacao CI, ce serait celle du trait d\'union vivant entre deux institutions appelées à travailler main dans la main : le Conseil du Café-Cacao, régulateur historique de la filière, et l\'Organisation Interprofessionnelle Agricole, jeune structure faîtière encore en pleine construction. \r\n\r\nCette continuité n\'est pas une posture, elle est inscrite dans son parcours. Pendant plus de 10 ans, ME KOUAKOU a servi la filière café cacao, notamment comme Coordonnateur des Organisations Professionnelles Agricoles (OPA) au sein du Conseil du Café-Cacao. C\'est à ce poste qu\'il a piloté, de l\'intérieur, l\'identification et la structuration des familles professionnelles, la vérification des données de représentativité et la préparation du dossier ayant conduit à la reconnaissance officielle de l\'OIA. Nommé ensuite Directeur Exécutif de l\'OIA à l\'issue d\'un processus de recrutement validé à l\'unanimité, il n\'a donc pas changé de camp : il a simplement changé de rive, tout en gardant les deux mains sur le pont qui les relie. \r\n\r\n\r\nCe positionnement se lit très concrètement dans son action quotidienne. C\'est lui qui a représenté l\'OIA lors de la séance de travail avec M. Abdou Seydou, Directeur Financier et Comptable du CCC, pour négocier les conditions d\'un accord de financement entre les deux structures. C\'est encore lui qui a porté, aux côtés du CCC, l\'atelier de Yamoussoukro, posant les jalons d\'un dialogue institutionnel apaisé entre le régulateur et l\'interprofession. Et c\'est sous son impulsion que la remise symbolique de bons de retrait de pesticides aux producteurs du Guémon — une dotation rendue possible grâce à une coordination étroite avec le CCC — a offert à la JNOIACC 2026, tenue à Duékoué, sa preuve d\'utilité la plus tangible. \r\n\r\n\r\nEn définitive, ME KOUAKOU incarne une double loyauté qui n\'en fait qu\'une : celle envers l\'histoire du Conseil du Café-Cacao, dont il connaît les rouages pour les avoir façonnés, et celle envers l\'avenir de l\'OIA, dont il doit assurer l\'autonomie sans jamais rompre le fil du dialogue institutionnel. C\'est cette capacité à tenir les deux bouts — mémoire du régulateur, ambition de l\'interprofession — qui fait de lui, aujourd\'hui, bien plus qu\'un dirigeant : un véritable passeur entre deux mondes appelés à ne faire qu\'un pour la filière cafécacao ivoirienne. \r\n\r\n\r\nDASSO DENIS \r\nDIRECTEUR DES PUBLICATIONS', 'uploads/articles/article_6a749ddf2b48c_1786027487.png', 'published', 1, 4, '2026-08-06 14:44:47', '2026-08-06 14:37:53', '2026-08-11 12:39:53');
INSERT INTO `articles` (`id`, `category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `status`, `is_featured`, `views`, `published_at`, `created_at`, `updated_at`) VALUES
(9, 1, 1, 'Première edition de la Journée Nationnal de l\'OIA Café - Cacao à Duékoué', 'premiere-edition-de-la-journee-nationnal-de-l-oia-cafe-cacao-a-duekoue', 'Le grand cœur de Siaka Diakité, Président de l\'OIA Café-Cacao, dévoilé à Duékoué', '«si ton ami te dit qu\'il a rêvé la nuit, c\'est qu\'il a eu un endroit pour dormir» \r\n\r\nDuékoué, 25 juillet 2026 — Retour sur un moment d\'émotion et de vérité lors de la première Journée Nationale de l\'OIA Café-Cacao (JNOIACC)\r\n\r\nIl y a des instants, dans la vie d\'une organisation naissante, où un homme cesse d\'être un simple dirigeant pour devenir la voix d\'une filière tout entière. Ce moment, la Côte d\'Ivoire agricole l\'a vécu à Duékoué, lorsque Siaka Diakité, Président de l\'Organisation Interprofessionnelle Agricole de la filière Café-Cacao (OIA) et Président du Comité d\'Organisation de la JNOIACC, a pris la parole devant une foule immense, portée par l\'émotion d\'un défi relevé. \r\n\r\nUn chef comblé, un leader qui a fait mentir les sceptiques \r\nIls étaient plus de 10 000 producteurs et acteurs de la filière rassemblée à Duékoué, en un temps record. Une mobilisation à la hauteur de l\'ambition de l\'OIA, et que peu de responsables auraient pu réaliser avec une telle aisance. Face à cette marée humaine venue des quatre coins du pays, Siaka Diakité n\'a pas caché sa fierté. On a vu, ce jour-là, un chef comblé — un leader dont le visage rayonnait de la joie du devoir accompli, celle qui ne trompe pas, celle des grands bâtisseurs qui voient enfin leur œuvre prendre corps. \r\n\r\nMais avant la fierté, il y a eu la gratitude. Car Siaka Diakité est resté, envers et contre tout, un homme reconnaissant. Reconnaissant envers le Président de la République, dont la vision a permis la création de l\'OIA. Et c\'est dans cet élan de gratitude qu\'il a livré une phrase que seuls les sages savent prononcer, une sentence à la sagesse populaire ivoirienne, aussi simple que profonde :\r\n\r\nUne sagesse, un message : le rêve devenu réalité \r\nDerrière cette formule empruntée à la tradition orale se cache une vérité limpide : on ne rêve que lorsqu\'on a d\'abord trouvé où poser sa tête. Le rêve de l\'OIA — cette filière café-cacao enfin structurée, organisée, entendue — n\'a pu se réaliser que parce que ses bâtisseurs ont eu, eux aussi, un toit sous lequel s\'abriter : le soutien indéfectible du Président de la République, l\'accompagnement du Ministre de l\'Agriculture, et la présence constante de celui que Siaka Diakité appelle, avec un respect touchant, son grand frère : Yves Koné Brahima, Directeur Général du Conseil du Café-Cacao (CCC). \r\n\r\nYves Koné Brahima, le grand frère bâtisseur \r\nSi le Gouvernement a porté la vision de la création de l\'OIA, sa concrétisation, elle, porte la signature d\'un homme : Yves Koné Brahima. Le Directeur Général du CCC n\'a ménagé aucun effort pour donner corps à cette organisation, accompagnant chaque étape avec la rigueur et la disponibilité d\'un véritable compagnon de route. \r\n\r\nCe jour-là, à Duékoué, l\'acceptation du parrainage par le Ministre et la présence effective du DG du CCC — tous deux mobilisés sur le terrain — ont été salués comme des gestes forts, dignes d\'une reconnaissance appuyée. Et c\'est avec des mots à la fois solennels et fraternels que Siaka Diakité a tenu à rassurer le DG du CCC, dans un moment de vérité rare dans la vie institutionnelle ivoirienne : \r\n\r\n« Monsieur le Directeur, il ne faudrait pas regarder le comportement de chacun de nous. Il faut regarder les intérêts. Donc on tient à vous aider, et vous pouvez compter sur nous. » \r\n\r\nUn moment d\'émotion sincère, qui aura eu le mérite de clarifier durablement les rapports entre l\'OIA et le CCC, et de faire taire, définitivement, les rumeurs de dissension qui circulaient autour de cette relation institutionnelle.\r\n\r\nL\'homme du consensus \r\nAu-delà des mots, il y a eu les actes — et les actes, à Duékoué, ont parlé plus fort que tout discours. La présence remarquée des principales organisations syndicales et des autres Organisations Professionnelles Agricoles (OPA) a témoigné, avec éclat, de ce que Siaka Diakité incarne avant tout : l\'homme du consensus. \r\n\r\nIl n\'a d\'ailleurs pas caché sa joie de voir tous les collèges de l\'OIA impliqués, unis, mobilisés autour de l\'organisation de cette Journée Nationale. Une unité qui ne doit rien au hasard, mais tout à un leadership rassembleur.\r\n\r\nL\'OIA a démontré sa force \r\n\r\nÀ Duékoué, l\'OIA n\'a pas seulement organisé un événement : elle a démontré, aux yeux de tous, sa représentativité et sa capacité à faire consensus au sein de son Conseil d\'Administration. Une prouesse collective, mais qui porte incontestablement l\'empreinte du leadership de Siaka Diakité, l\'homme qui aura su, en si peu de temps, transformer une vision présidentielle en un mouvement populaire de plus de 10 000 âmes, uni autour d\'un même rêve : celui d\'une filière café-cacao enfin debout, organisée, et fière.', 'uploads/articles/article_6a74a4368b850_1786029110.png', 'published', 1, 6, '2026-08-06 15:11:50', '2026-08-06 15:11:50', '2026-08-11 11:16:04');

-- --------------------------------------------------------

--
-- Structure de la table `article_comments`
--

CREATE TABLE `article_comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article_comments`
--

INSERT INTO `article_comments` (`id`, `article_id`, `name`, `email`, `content`, `is_approved`, `created_at`) VALUES
(2, 9, 'Djiehi', 'djiehilevyjonathan@gmail.com', 'Bien', 1, '2026-08-10 11:20:44');

-- --------------------------------------------------------

--
-- Structure de la table `banners`
--

CREATE TABLE `banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Actualités', 'actualites', 'Actualités et événements de l\'OIA', NULL, NULL, 1, '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(2, 'Publications', 'publications', 'Publications et rapports', NULL, NULL, 2, '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(3, 'La Filière', 'la-filiere', 'Informations sur la filière café-cacao', NULL, NULL, 3, '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(4, 'Nos Actions', 'nos-actions', 'Projets et actions réalisées', NULL, NULL, 4, '2026-07-01 14:09:36', '2026-07-01 14:09:36');

-- --------------------------------------------------------

--
-- Structure de la table `colleges`
--

CREATE TABLE `colleges` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon_class` varchar(100) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(100) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `contact_website` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_adresses`
--

CREATE TABLE `contact_adresses` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `adresse` text NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `google_maps_url` varchar(255) DEFAULT NULL,
  `zoom_level` tinyint(4) NOT NULL DEFAULT 15,
  `ordre_affichage` int(11) NOT NULL DEFAULT 0,
  `statut` tinyint(1) NOT NULL DEFAULT 1,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `date_up` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact_adresses`
--

INSERT INTO `contact_adresses` (`id`, `titre`, `adresse`, `latitude`, `longitude`, `google_maps_url`, `zoom_level`, `ordre_affichage`, `statut`, `date_add`, `date_up`) VALUES
(1, 'Siège social', 'Yamoussoukro, Côte d\'Ivoire', 6.81670000, -5.26670000, NULL, 14, 1, 1, '2026-07-01 16:35:27', '2026-07-01 16:35:27');

-- --------------------------------------------------------

--
-- Structure de la table `contact_coordonnees`
--

CREATE TABLE `contact_coordonnees` (
  `id` int(11) NOT NULL,
  `type` enum('telephone','whatsapp','email','reseau_social','autre') NOT NULL DEFAULT 'telephone',
  `valeur` varchar(255) NOT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `icone` varchar(100) DEFAULT NULL,
  `lien` varchar(255) DEFAULT NULL,
  `ordre_affichage` int(11) NOT NULL DEFAULT 0,
  `statut` tinyint(1) NOT NULL DEFAULT 1,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `date_up` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `objet` varchar(255) DEFAULT NULL,
  `service` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `ip_visiteur` varchar(45) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `navigateur` varchar(255) DEFAULT NULL,
  `systeme_exploitation` varchar(100) DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `date_up` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('nouveau','en_cours','traite','archive') NOT NULL DEFAULT 'nouveau'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_replies`
--

CREATE TABLE `contact_replies` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `title`, `slug`, `description`, `file_name`, `file_path`, `file_type`, `file_size`, `is_published`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Statuts de l\'OIA', 'statuts-de-l-oia', 'Textes constitutifs définissant les missions et le fonctionnement de l\'organisation.', 'STATUTS OIA CAFE-CACAO CI.pdf', 'uploads/documents/document_6a46b834e82b4.pdf', 'application/pdf', 13904004, 1, 1, '2026-07-02 17:12:53', '2026-07-02 17:12:53'),
(2, 'Règlement Intérieur', 'reglement-interieur', 'Règles de fonctionnement et procédures internes.', 'REGLEMENT INTERIEUR OIA CAFE-CACAO CI.pdf', 'uploads/documents/document_6a46b86b3d8a2.pdf', 'application/pdf', 4390140, 1, 1, '2026-07-02 17:13:47', '2026-07-02 17:13:47');

-- --------------------------------------------------------

--
-- Structure de la table `filieres`
--

CREATE TABLE `filieres` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `actions` text DEFAULT NULL,
  `production_share` varchar(50) DEFAULT NULL,
  `producers_count` varchar(50) DEFAULT NULL,
  `tonnes_per_year` varchar(50) DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `filieres`
--

INSERT INTO `filieres` (`id`, `name`, `slug`, `description`, `content`, `actions`, `production_share`, `producers_count`, `tonnes_per_year`, `cover_photo`, `is_published`, `sort_order`, `created_at`, `updated_at`) VALUES
(4, 'Cacao', 'cacao', 'Cacao en Côte d\'Ivoire: deux leaders de la filière incarcérés pour diffamation et calomnie', 'En Côte d’Ivoire, les leaders de deux organisations de planteurs de cacao ont été placés en garde à vue dans la soirée du lundi 2 février. Ils sont accusés d\'avoir dénoncé des risques de mévente et d\'être à l\'origine du ralentissement observé de la principale campagne de commercialisation du cacao. Mercredi 4 février, le procureur a décidé de les renvoyer devant un juge pour déterminer s’ils sont coupables ou non des faits de « diffamation » et de « dénonciation calomnieuse contre le Conseil Café Cacao » dont ils font l\'objet. Ils recevront prochainement une citation à comparaître pour un procès correctionnel. \r\n\r\n\r\nLa filière cacao est un secteur clé en Côte d\'Ivoire, représentant près de 40 % de la production mondiale et 10 % du PIB. Elle fait vivre environ 6 millions de personnes, principalement à travers de petites exploitations familiales. La production nécessite une traçabilité rigoureuse pour répondre aux normes internationales.', 'Amélioration de la productivité\r\nFormation des producteurs\r\nCertification durable\r\nValorisation locale', '40%', '2M+', '2.2M+', 'uploads/filieres/93ed6nq-1784132414.webp', 1, 1, '2026-07-15 14:20:14', '2026-07-15 14:20:14'),
(5, 'Cafe', 'cafe', 'Le café est un pilier historique de l\'agriculture ivoirienne, introduit vers 1880. Le pays produit exclusivement du café Robusta, caractérisé par un goût corsé et une amertume affirmée. Autrefois premier exportateur mondial, le secteur se modernise aujourd\'hui pour répondre aux exigences internationales.', 'Les détails clés du café en Côte d\'Ivoire :Variété : Le Robusta, reconnu pour sa robustesse, son corps et sa teneur en caféine élevée.Régions de production : Les zones humides, telles que la région d\'Azaguier (au nord d\'Abidjan), et les zones forestières.Enjeux actuels : Le secteur a été surclassé par le cacao, mais les autorités misent sur la formation de dégustateurs professionnels et sur un Système National de Traçabilité pour valoriser le \"Robusta de spécialité\" et relancer la consommation locale.', 'Amélioration de la productivité\r\nFormation des producteurs\r\nCertification durable\r\nValorisation locale', '20%', '1,04M', '1M', 'uploads/filieres/images-1-1784132673.jpg', 1, 2, '2026-07-15 14:24:33', '2026-07-15 14:24:33');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` bigint(20) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `folder` varchar(100) DEFAULT 'default',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_campaigns`
--

CREATE TABLE `newsletter_campaigns` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('draft','sending','sent','cancelled') NOT NULL DEFAULT 'draft',
  `sent_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_logs`
--

CREATE TABLE `newsletter_logs` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) DEFAULT NULL,
  `subscriber_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('sent','failed','opened','clicked') NOT NULL,
  `error_message` text DEFAULT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `subscribed_at` timestamp NULL DEFAULT current_timestamp(),
  `confirmed_at` datetime DEFAULT NULL,
  `unsubscribed_at` datetime DEFAULT NULL,
  `date_up` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_templates`
--

CREATE TABLE `newsletter_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operators`
--

CREATE TABLE `operators` (
  `id` int(11) NOT NULL,
  `filiere_id` int(11) DEFAULT NULL,
  `type` enum('acheteur','operateur') NOT NULL DEFAULT 'operateur',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `operator_filieres`
--

CREATE TABLE `operator_filieres` (
  `id` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `filiere_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `organisations`
--

CREATE TABLE `organisations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `responsable` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `personnes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `partners`
--

CREATE TABLE `partners` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('institutionnel','prive') NOT NULL DEFAULT 'prive',
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `album_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `photos`
--

INSERT INTO `photos` (`id`, `album_id`, `title`, `slug`, `description`, `file_path`, `file_type`, `file_size`, `is_published`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(13, NULL, 'Snapchat-1634654762', 'snapchat-1634654762', NULL, 'uploads/photos/photo_6a4685001ac8b_1783006464_0.jpg', 'image/jpeg', 132767, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(14, NULL, 'IMG_20260623_132914_860', 'img-20260623-132914-860', NULL, 'uploads/photos/photo_6a4685001d1ba_1783006464_1.jpg', 'image/jpeg', 710041, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(15, NULL, 'recto', 'recto', NULL, 'uploads/photos/photo_6a4685001ece4_1783006464_2.jpg', 'image/jpeg', 746028, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(16, NULL, 'IMG_20260623_132908_321', 'img-20260623-132908-321', NULL, 'uploads/photos/photo_6a4685002090c_1783006464_3.jpg', 'image/jpeg', 3000062, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(17, NULL, '1', '1', NULL, 'uploads/photos/photo_6a46850022597_1783006464_4.jpg', 'image/jpeg', 51117, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(18, NULL, 'Snapchat-1634654762', 'snapchat-1634654762', NULL, 'uploads/photos/photo_6a468500234fe_1783006464_5.jpg', 'image/jpeg', 132767, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(19, NULL, 'IMG_20260623_132914_860', 'img-20260623-132914-860', NULL, 'uploads/photos/photo_6a4685002470d_1783006464_6.jpg', 'image/jpeg', 710041, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(20, NULL, 'recto', 'recto', NULL, 'uploads/photos/photo_6a46850025fcc_1783006464_7.jpg', 'image/jpeg', 746028, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(21, NULL, 'IMG_20260623_132908_321', 'img-20260623-132908-321', NULL, 'uploads/photos/photo_6a46850026ca8_1783006464_8.jpg', 'image/jpeg', 3000062, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(22, NULL, '1', '1', NULL, 'uploads/photos/photo_6a46850027d16_1783006464_9.jpg', 'image/jpeg', 51117, 1, 1, '2026-07-02 13:34:24', '2026-07-02 13:48:44'),
(23, NULL, 'Snapchat-1634654762', 'snapchat-1634654762', NULL, 'uploads/photos/photo_6a468cd3060bb_1783008467_0.jpg', 'image/jpeg', 132767, 1, 1, '2026-07-02 14:07:47', '2026-07-15 11:26:12'),
(24, NULL, 'IMG_20260623_132914_860', 'img-20260623-132914-860', NULL, 'uploads/photos/photo_6a468cd3082ab_1783008467_1.jpg', 'image/jpeg', 710041, 1, 1, '2026-07-02 14:07:47', '2026-07-15 11:26:12'),
(25, NULL, 'recto', 'recto', NULL, 'uploads/photos/photo_6a468cd309772_1783008467_2.jpg', 'image/jpeg', 746028, 1, 1, '2026-07-02 14:07:47', '2026-07-15 11:26:12'),
(26, NULL, 'IMG_20260623_132908_321', 'img-20260623-132908-321', NULL, 'uploads/photos/photo_6a468cd30a859_1783008467_3.jpg', 'image/jpeg', 3000062, 1, 1, '2026-07-02 14:07:47', '2026-07-15 11:26:12'),
(27, NULL, '1', '1', NULL, 'uploads/photos/photo_6a468cd30b9ad_1783008467_4.jpg', 'image/jpeg', 51117, 1, 1, '2026-07-02 14:07:47', '2026-07-15 11:26:12'),
(35, 3, 'Le ministre de l’Agriculture, du Développement rural et des Productions vivrières, Bruno Nabagné Koné (au centre), entouré de part et d\'autre, par le directeur général du Conseil du café-cacao Koné  Brahima Yves et des responsables de l’OIA Café-Cacaoa, j', 'le-ministre-de-l-agriculture-du-developpement-rural-et-des-productions-vivrieres-bruno-nabagne-kone-au-centre-entoure-de-part-et-d-autre-par-le-directeur-general-du-conseil-du-cafe-cacao-kone-brahima-yves-et-des-responsables-de-l-oia-cafe-cacaoa-jeudi-9-a', NULL, 'uploads/photos/photo_6a57a2a6112ff_1784128166.jpg', 'image/jpeg', 105017, 1, 1, '2026-07-15 15:09:26', '2026-07-15 15:09:26'),
(36, 4, 'Le ministre de la Transition numérique et de l’Innovation technologique, Djibril Ouattara, visiblement heureux lors de la présentation du robot intelligent développé par Kayode Technologies.', 'le-ministre-de-la-transition-numerique-et-de-l-innovation-technologique-djibril-ouattara-visiblement-heureux-lors-de-la-presentation-du-robot-intelligent-developpe-par-kayode-technologies', NULL, 'uploads/photos/photo_6a57a34851c88_1784128328.jpg', 'image/jpeg', 94845, 1, 1, '2026-07-15 15:12:08', '2026-07-15 15:12:08'),
(37, 5, 'Les participants à la conférence de presse de l\'ONG WiLDAF sur le recueil sur les VBG, à Abidjan, le 14/07/2026', 'les-participants-a-la-conference-de-presse-de-l-ong-wildaf-sur-le-recueil-sur-les-vbg-a-abidjan-le-14-07-2026', NULL, 'uploads/photos/photo_6a57a400d0a8d_1784128512.jpeg', 'image/jpeg', 69762, 1, 1, '2026-07-15 15:15:12', '2026-07-15 15:15:12'),
(38, 6, 'Des particiapnts à la rencontre de l\'OMPA sur le management en Afrique, à Abidjan, le 14/07/2026', 'des-particiapnts-a-la-rencontre-de-l-ompa-sur-le-management-en-afrique-a-abidjan-le-14-07-2026', NULL, 'uploads/photos/photo_6a57a442a2ea0_1784128578.jpeg', 'image/jpeg', 50172, 1, 1, '2026-07-15 15:16:18', '2026-07-15 15:16:18'),
(39, 7, '755063275_914040537723451_7620069267259791460_n', '755063275-914040537723451-7620069267259791460-n', NULL, 'uploads/photos/photo_6a69f03fb21f8_1785327679_0.jpg', 'image/jpeg', 50307, 1, 1, '2026-07-29 12:21:19', '2026-07-29 12:21:19'),
(40, 7, '752484635_1581533226819527_7095010572678339308_n', '752484635-1581533226819527-7095010572678339308-n', NULL, 'uploads/photos/photo_6a69f03fba2fc_1785327679_1.jpg', 'image/jpeg', 51799, 1, 1, '2026-07-29 12:21:19', '2026-07-29 12:21:19'),
(41, 7, '752881050_1411714330831757_3124017611146522384_n', '752881050-1411714330831757-3124017611146522384-n', NULL, 'uploads/photos/photo_6a69f03fba69d_1785327679_2.jpg', 'image/jpeg', 414666, 1, 1, '2026-07-29 12:21:19', '2026-07-29 12:21:19'),
(45, 8, '754007074_1059247956646000_4060862764211864188_n (1)', '754007074-1059247956646000-4060862764211864188-n-1', NULL, 'uploads/photos/photo_6a69f1fda1ae5_1785328125.jpg', 'image/jpeg', 133000, 1, 1, '2026-07-29 12:28:45', '2026-07-29 12:28:45'),
(46, 8, '755063275_1763268324666282_3568085627374862970_n', '755063275-1763268324666282-3568085627374862970-n', NULL, 'uploads/photos/photo_6a69f20beca9a_1785328139.jpg', 'image/jpeg', 209428, 1, 1, '2026-07-29 12:28:59', '2026-07-29 12:28:59'),
(47, 8, '753694904_3374869406007078_7954557977689827591_n', '753694904-3374869406007078-7954557977689827591-n', NULL, 'uploads/photos/photo_6a69f21758acb_1785328151.jpg', 'image/jpeg', 245682, 1, 1, '2026-07-29 12:29:11', '2026-07-29 12:29:11'),
(48, 8, '757755011_2089647571667784_2397318785219470794_n', '757755011-2089647571667784-2397318785219470794-n', NULL, 'uploads/photos/photo_6a69f2260e6b9_1785328166.jpg', 'image/jpeg', 216382, 1, 1, '2026-07-29 12:29:26', '2026-07-29 12:29:26'),
(49, 8, '754536216_1710068190270637_9179491380095714133_n', '754536216-1710068190270637-9179491380095714133-n', NULL, 'uploads/photos/photo_6a69f244b1106_1785328196.jpg', 'image/jpeg', 235031, 1, 1, '2026-07-29 12:29:56', '2026-07-29 12:29:56'),
(60, 8, '753707291_2034265723845791_2727159823911287429_n', '753707291-2034265723845791-2727159823911287429-n', NULL, 'uploads/photos/photo_6a69f24fe6ae9_1785328207.jpg', 'image/jpeg', 207204, 1, 1, '2026-07-29 12:30:07', '2026-07-29 12:30:07'),
(61, 8, '755734352_1016335307781626_7278313858424735190_n', '755734352-1016335307781626-7278313858424735190-n', NULL, 'uploads/photos/photo_6a69f2dfcf3dd_1785328351.jpg', 'image/jpeg', 211259, 1, 1, '2026-07-29 12:32:31', '2026-07-29 12:32:31'),
(72, 8, '756342514_1592728658860327_7337918508304735596_n', '756342514-1592728658860327-7337918508304735596-n', NULL, 'uploads/photos/photo_6a69f2efc3a41_1785328367.jpg', 'image/jpeg', 178093, 1, 1, '2026-07-29 12:32:47', '2026-07-29 12:32:47'),
(73, 8, '754825062_997581173283807_841513877893051987_n', '754825062-997581173283807-841513877893051987-n', NULL, 'uploads/photos/photo_6a69f35b3724f_1785328475.jpg', 'image/jpeg', 343697, 1, 1, '2026-07-29 12:34:35', '2026-07-29 12:34:35'),
(74, 8, '756040432_27607476408873015_5344139392349048313_n', '756040432-27607476408873015-5344139392349048313-n', NULL, 'uploads/photos/photo_6a69f376706bb_1785328502.jpg', 'image/jpeg', 255057, 1, 1, '2026-07-29 12:35:02', '2026-07-29 12:35:02'),
(75, 8, '753702312_2109241096636053_7300746896414070395_n', '753702312-2109241096636053-7300746896414070395-n', NULL, 'uploads/photos/photo_6a69f38a872da_1785328522.jpg', 'image/jpeg', 216841, 1, 1, '2026-07-29 12:35:22', '2026-07-29 12:35:22'),
(76, 8, '755105295_2301939630341391_4276426994202549794_n', '755105295-2301939630341391-4276426994202549794-n', NULL, 'uploads/photos/photo_6a69f3a832923_1785328552.jpg', 'image/jpeg', 221819, 1, 1, '2026-07-29 12:35:52', '2026-07-29 12:35:52'),
(77, 8, '756341994_1718656559304975_8733131542948838381_n', '756341994-1718656559304975-8733131542948838381-n', NULL, 'uploads/photos/photo_6a69f3c23afa1_1785328578.jpg', 'image/jpeg', 255494, 1, 1, '2026-07-29 12:36:18', '2026-07-29 12:36:18'),
(78, 8, '753550963_1705598197347115_6119869771047119848_n', '753550963-1705598197347115-6119869771047119848-n', NULL, 'uploads/photos/photo_6a69f3ca9afd2_1785328586.jpg', 'image/jpeg', 161783, 1, 1, '2026-07-29 12:36:26', '2026-07-29 12:36:26'),
(79, 8, '755735626_1546636126303502_2312274623550486810_n', '755735626-1546636126303502-2312274623550486810-n', NULL, 'uploads/photos/photo_6a69f3d2c9ac6_1785328594.jpg', 'image/jpeg', 51831, 1, 1, '2026-07-29 12:36:34', '2026-07-29 12:36:34'),
(80, 8, '753887191_912460131226976_5733469234789799831_n', '753887191-912460131226976-5733469234789799831-n', NULL, 'uploads/photos/photo_6a69f3db803ca_1785328603.jpg', 'image/jpeg', 116508, 1, 1, '2026-07-29 12:36:43', '2026-07-29 12:36:43'),
(81, 8, '754462871_1521541262424628_7123440227051092733_n', '754462871-1521541262424628-7123440227051092733-n', NULL, 'uploads/photos/photo_6a69f3ede81a2_1785328621.jpg', 'image/jpeg', 120601, 1, 1, '2026-07-29 12:37:01', '2026-07-29 12:37:01'),
(83, 9, 'PHOTO-2026-08-06-13-13-04', 'photo-2026-08-06-13-13-04', NULL, 'uploads/photos/photo_6a74a0523a4d7_1786028114.jpg', 'image/jpeg', 88331, 1, 1, '2026-08-06 14:55:14', '2026-08-06 14:55:14'),
(84, 9, 'PHOTO-2026-08-06-13-13-04_1', 'photo-2026-08-06-13-13-04-1', NULL, 'uploads/photos/photo_6a74a059b0942_1786028121.jpg', 'image/jpeg', 86163, 1, 1, '2026-08-06 14:55:21', '2026-08-06 14:55:21'),
(85, 9, 'PHOTO-2026-08-06-13-13-05', 'photo-2026-08-06-13-13-05', NULL, 'uploads/photos/photo_6a74a060d8268_1786028128.jpg', 'image/jpeg', 70349, 1, 1, '2026-08-06 14:55:28', '2026-08-06 14:55:28'),
(86, 9, 'PHOTO-2026-08-06-13-13-05_1', 'photo-2026-08-06-13-13-05-1', NULL, 'uploads/photos/photo_6a74a06709371_1786028135.jpg', 'image/jpeg', 82050, 1, 1, '2026-08-06 14:55:35', '2026-08-06 14:55:35');

-- --------------------------------------------------------

--
-- Structure de la table `photo_albums`
--

CREATE TABLE `photo_albums` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `photo_albums`
--

INSERT INTO `photo_albums` (`id`, `title`, `slug`, `description`, `thumbnail`, `is_published`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 'Côte d’Ivoire-AIP/ L’OIA Café-Cacao annonce la célérité de l’enlèvement du quota complémentaire de 23 830 T de fèves', 'cote-d-ivoire-aip-l-oia-cafe-cacao-annonce-la-celerite-de-l-enlevement-du-quota-complementaire-de-23-830-t-de-feves', 'Abidjan, 10 avr 2026(AIP)- Le vice-président de l’Organisation interprofessionnelle agricole café-cacao (OIA Café-Cacao), Obed Blondé Doua, a annoncé lors d’une conférence de presse tenue jeudi 9 avril 2026 à l’auditorium de l’immeuble de la Caistab, à Abidjan Plateau, la célérité de l’enlèvement du quota complémentaire de 23 830 tonnes, tout en exhortant  les acteurs à se mobiliser  pour  la campagne intermédiaire. \r\n\r\n »Les opérations d’enlèvement du quota complémentaire de 23 830 tonnes  réattribué à l’OIA Café-Cacao par le Conseil du Café-Cacao seront engagées dans les tout prochains jours, avec une accélération significative », a déclaré M. Doua.  \r\n\r\nLe 3e vice-président de l’OIA Café-Cacao relevé que l’enlèvement de ce volume  qui constitue « la dernière phase opérationnelle » du processus de déstockage permettra de réduire fortement les stocks encore immobilisés; de soulager les producteur et d’amorcer une sortie concrète et visible de la crise.\r\n\r\nIl s’est dit confiant quant à l’évolution des cours internationaux, à la résilience des producteurs et à la capacité collective de relance de la filière.\r\n\r\nM. Doua a salué ‘l’effort exceptionnel consenti par le président de la République, Alassane Ouattara, qui a mobilisé des ressources importantes issues du fonds de réserve (231 milliards FCFA) afin de porter le prix bord champ à 1 200 FCFA/kg au lieu de 900 FCFA correspondant à 60% du prix CAF, en soutien aux producteurs.\r\n\r\nIl a  adressé les remerciements appuyés  de l’OIA aux autorités de l’État pour leur engagement  et la reconnaissance  des siens à l’ensemble des acteurs de la filière pour leur patience.\r\n\r\nM. Doua a par ailleurs lancé un appel à la cohésion, à la responsabilité et à la mobilisation collective en présence du  ministre de l’Agriculture, du Développement rural et des Productions vivrières, Bruno Nabagné Koné.\r\n\r\nLe porte-voix de l’OIA a souligné que la décision d’annulation des quotas de SITAPA et TRANSCAO, suivie de la réallocation des volumes au profit de l’OIA, constitue une étape décisive dans la résolution de la crise.\r\n\r\nLe   directeur général du Conseil du Café-Cacao Koné  Brahima Yves, a salué l’engagement des unions de coopératives et des différents acteurs avant de réjouir de ce que cette crise qu’il a qualifiée de crise mondiale, arrive à son terme.  »La situation est extrêmement difficile au plan mondial », a-t-il souligné.\r\n\r\n(AIP)', NULL, 1, 1, '2026-07-15 15:08:09', '2026-07-15 15:08:09'),
(4, 'AIP/Un Ivoirien basé aux États-Unis présente au gouvernement des robots autonomes pour les services de proximité', 'aip-un-ivoirien-base-aux-etats-unis-presente-au-gouvernement-des-robots-autonomes-pour-les-services-de-proximite', 'Abidjan, 15 juil 2026 (AIP)- Le jeune entrepreneur ivoirien installé aux États-Unis, Karim Fagbohim, fondateur de Kayod Technologies, a présenté mardi 14 juillet 2026 à Abidjan au ministre de la Transition numérique et de l’Innovation technologique, Djibril Ouattara, un robot autonome destiné à la livraison et à l’affichage digital, dans le cadre du renforcement de l’innovation technologique en Côte d’Ivoire.\r\n\r\nLe promoteur était accompagné du consul général de la République de Côte d’Ivoire à New York (États-Unis), Inza Camara.\r\n\r\nDéveloppée aux États-Unis, cette technologie combine plusieurs fonctionnalités innovantes. Le robot est capable d’effectuer des livraisons de manière autonome ou semi-autonome, tout en diffusant des contenus numériques grâce à des écrans intégrés. Il fonctionne également grâce à une alimentation électrique soutenue par des panneaux solaires.\r\n\r\nSelon Karim Fagbohim, cette innovation pourrait être adaptée aux réalités ivoiriennes à travers la mise en œuvre de programmes pilotes. Ces robots pourraient notamment assurer la livraison de courriers dans les administrations, effectuer de l’affichage numérique dans les centres commerciaux ou proposer des services de proximité dans des zones urbaines telles que le Plateau, Zone 4 ou l’aéroport international Félix-Houphouët-Boigny.\r\n\r\n« L’idée est de travailler avec le gouvernement afin d’adapter cette technologie conçue aux États-Unis aux besoins ivoiriens », a expliqué le fondateur de Kayode Technologies, soulignant que ces robots peuvent être supervisés à distance par des opérateurs grâce à un système de caméras offrant une vision à 360 degrés.\r\n\r\nPour le jeune entrepreneur, cette initiative représente également une opportunité de création d’emplois et de développement de nouvelles compétences pour la jeunesse ivoirienne. Il envisage de former des jeunes aux outils numériques, aux logiciels et aux procédés liés à cette technologie afin qu’ils puissent assurer la téléopération des robots depuis la Côte d’Ivoire.\r\n\r\nLe ministre de la Transition numérique et de l’Innovation technologique, Djibril Ouattara, a salué cette initiative, estimant qu’elle traduit l’engagement de la diaspora ivoirienne dans le développement national.\r\n\r\nIl a encouragé l’installation d’un centre d’opérations de Kayod Technologies en Côte d’Ivoire, notamment au Village des technologies de l’information et de la biotechnologie (VITIB) à Grand-Bassam, afin de rapprocher cette technologie des étudiants et jeunes diplômés en mécanique, automatisme et robotique.\r\n\r\nCette implantation pourrait favoriser la création d’emplois qualifiés et renforcer les compétences locales dans des domaines liés aux technologies d’avenir.\r\n\r\nÀ travers cette initiative, Karim Fagbohim et Kayode Technologies ambitionnent de contribuer à l’émergence d’un écosystème ivoirien de la robotique, en faisant de l’innovation un levier de formation, d’emploi et de développement économique.', NULL, 1, 1, '2026-07-15 15:11:29', '2026-07-15 15:11:29'),
(5, 'Côte d’Ivoire-AIP/ Une ONG présente son recueil de 27 témoignages de survivantes des VBG', 'cote-d-ivoire-aip-une-ong-presente-son-recueil-de-27-temoignages-de-survivantes-des-vbg', 'Abidjan, 15 juil 2026 (AIP) – L’ONG Women in Law and Development in Africa (WiLDAF-Côte d’Ivoire) a présenté, un recueil intitulé « Voix des survivantes : l’écriture comme outil de plaidoyer contre les violences basées sur le genre », mardi 14 juillet 2026 à la Maison de la presse. réunissant 27 témoignages de survivantes de Violences basées sur le genre (VBG), dans le but de sensibiliser l’opinion publique et de renforcer le plaidoyer en faveur d’une meilleure prise en charge des victimes.\r\n\r\nPrésenté au cours d’une conférence de presse, l’ouvrage est l’aboutissement d’un projet pilote ayant permis à des survivantes de transformer leur vécu en récits grâce à un accompagnement psychologique et à des ateliers d’écriture.\r\n\r\nLe chef de projet, Justin Oulai, a expliqué que cette initiative a débuté par des sessions de formation destinées aux participantes, encadrées par des consultants en écriture et un psychologue, afin de les aider à mettre des mots sur leur expérience.\r\n\r\n« Nous avons pu réaliser un recueil qui rassemble 27 témoignages de survivantes des violences basées sur le genre, couvrant les différentes formes de violences, notamment psychologiques, sexuelles, émotionnelles et conjugales », a-t-il indiqué.\r\n\r\nLa directrice exécutive de WiLDAF-Côte d’Ivoire, Simone Guidi, a souligné que ce projet vise à rompre le silence qui entoure les VBG et à porter la voix des survivantes auprès des décideurs.\r\n\r\n« Nous avons voulu les écouter, entendre leurs voix, pour pouvoir porter haut les plaidoyers que nous devons faire », a-t-elle déclaré.\r\n\r\nElle a précisé que plusieurs actions de plaidoyer ont déjà été engagées auprès de l’Assemblée nationale et du ministère de la Femme, de la Famille et de l’Enfant. Ces démarches portent notamment sur l’adoption d’une loi spécifique contre le féminicide, la création de centres d’hébergement d’urgence pour les survivantes ainsi que le renforcement de leur prise en charge psychologique, sociale et économique.\r\n\r\nReprésentant la présidente de l’association “Akwaba Mousso” un partenaire, Lydie Pascaline Koffi a estimé que les survivantes doivent être placées au cœur de la lutte contre les violences basées sur le genre.\r\n\r\n« Nous ne lançons pas seulement un recueil de témoignages. Nous faisons entendre des voix que la peur, la honte et le silence ont trop longtemps empêché de s’exprimer », a-t-elle affirmé.\r\n\r\nElle a rappelé que le centre intégré “Akwaba Mousso”, créé en 2023, a enregistré plus de 1 500 consultations en 2025 et accompagné individuellement plus de 200 femmes et enfants survivants de violences.\r\n\r\nL’une des participantes au projet a témoigné des effets de cette initiative sur son parcours de reconstruction après plusieurs années de violences conjugales.\r\n\r\n« Lorsque la parole est libérée, le cœur se détend », a-t-elle confié, expliquant que cette expérience lui a permis de se reconstruire et de s’engager à son tour dans la sensibilisation d’autres survivantes.\r\n\r\nÀ travers ce projet, WiLDAF-Côte d’Ivoire et ses partenaires entendent faire de l’écriture un levier de guérison individuelle, mais également un outil de sensibilisation et de plaidoyer pour renforcer les politiques de prévention et de lutte contre les violences basées sur le genre.\r\n\r\n(AIP)', NULL, 1, 1, '2026-07-15 15:14:50', '2026-07-15 15:14:50'),
(6, 'Côte d’Ivoire-AIP/ Un observatoire met en lumière les défis des managers africains', 'cote-d-ivoire-aip-un-observatoire-met-en-lumiere-les-defis-des-managers-africains', 'Abidjan, 15 juil 2026 (AIP) – Les premiers résultats de l’Observatoire des pratiques managériales en Afrique (OPMA), présentés mardi 14 juillet 2026 au Novotel Abidjan Plateau, mettent en lumière plusieurs défis structurels auxquels sont confrontés les managers africains, notamment la difficulté à instaurer une culture du feedback et à garantir la pérennité des organisations.\r\n\r\nSelon le directeur général du cabinet ITHOS et initiateur de l’OPMA, Stéphane Coridon, les travaux menés dans sept pays africains montrent que le management sur le continent « est en construction ».\r\n\r\n« Les résultats montrent que le management en Afrique a de belles qualités. Mais il y a encore des choses à travailler, notamment la capacité des managers à être lucides sur eux-mêmes », a-t-il déclaré.\r\n\r\nL’étude, réalisée auprès de 188 managers issus de sept pays africains, met en évidence plusieurs risques pour le développement et la pérennité des organisations, notamment la dépendance excessive aux individus, le faible apprentissage collectif, la difficulté à assurer la relève, une culture de conformité au détriment de la responsabilité, ainsi que l’usure progressive des managers.\r\n\r\nFace à ces constats, l’Observatoire formule cinq recommandations à l’endroit des décideurs. Il s’agit notamment de faire du feedback une compétence stratégique, de développer la maturité réflexive des managers, de structurer les pratiques collectives du management, de repenser les investissements dans la formation managériale et de faire de la durabilité managériale un enjeu stratégique.\r\n\r\nPour Stéphane Coridon, l’un des principaux défis consiste désormais à construire un modèle de management adapté aux réalités africaines.\r\n\r\n« Les pratiques managériales en Afrique ne sont pas encore modélisées. L’Observatoire permettra justement de commencer à construire un modèle africain du management », a-t-il expliqué.\r\n\r\nL’initiateur de l’OPMA a également plaidé en faveur d’une recherche scientifique davantage centrée sur les réalités culturelles, sociologiques et organisationnelles africaines, estimant que les modèles de gestion importés ne répondent pas toujours aux spécificités du continent.\r\n\r\nÉvoquant l’intelligence artificielle, il a souligné que, si celle-ci améliore la productivité des entreprises, elle peut également réduire l’engagement des collaborateurs lorsque les organisations négligent la dimension humaine du management.\r\n\r\n« Le management et le leadership sont d’abord des réalités humaines. Une entreprise est avant tout composée d’hommes et de femmes », a-t-il insisté.\r\n\r\nÀ travers cette initiative, l’Observatoire des pratiques managériales en Afrique entend accompagner les décideurs publics et privés en mettant à leur disposition des données scientifiques et des outils d’analyse, afin de contribuer à l’amélioration durable des pratiques managériales sur le continent.\r\n\r\n(AIP)', NULL, 1, 1, '2026-07-15 15:15:51', '2026-07-15 15:15:51'),
(7, 'Journée nationale de l\'OIA Café-Cacao', 'journee-nationale-de-l-oia-cafe-cacao', 'Parmi les belles satisfactions de cette Journée nationale de l\'OIA Café-Cacao, il y a eu cette rencontre.\r\nJ\'ai été ravie de retrouver Monsieur Me Kouakou, qui a activement contribué à la mise en place de l\'Organisation Interprofessionnelle Agricole du Café-Cacao et qui en est aujourd\'hui le Directeur exécutif.\r\nCela fait toujours plaisir de voir les personnes qui ont participé à la construction de notre filière continuer à en écrire l\'histoire.\r\nFélicitations à lui pour ce parcours et plein succès dans la poursuite de cette belle mission au service du café-cacao ivoirien.', NULL, 1, 1, '2026-07-29 12:21:19', '2026-07-29 12:21:19'),
(8, 'JOURNÉE DE L\'OIA CAFÉ-CACAO À DUÉKOUÉ : DEVANT 10 000 PRODUCTEURS, LE MINISTRE BRUNO KONÉ EXHORTE À L\'UNION', 'journee-de-l-oia-cafe-cacao-a-duekoue-devant-10-000-producteurs-le-ministre-bruno-kone-exhorte-a-l-union', 'JOURNÉE DE L\'OIA CAFÉ-CACAO À DUÉKOUÉ : DEVANT 10 000 PRODUCTEURS, LE MINISTRE BRUNO KONÉ EXHORTE À L\'UNION\r\nParrain de la première Journée nationale de l\'Organisation Interprofessionnelle Agricole du Café-Cacao (OIA Café-Cacao), le Ministre de l\'Agriculture, du Développement Rural et des Productions Vivrières, M. Bruno Nabagné Kone, a rendu hommage, ce samedi 25 juillet à Duékoué, aux plus de 10 000 producteurs mobilisés pour cette première édition.\r\nEn présence du Directeur Général du Conseil du Café-Cacao, M. KONÉ Brahima Yves, et du Président de l\'OIA Café-Cacao, M. Siaka Diakité, le Ministre a salué une mobilisation exceptionnelle, reflet de la vitalité de l\'agriculture ivoirienne et de la confiance des planteurs dans l\'avenir de la filière.\r\nRappelant que toute la chaîne mondiale du chocolat repose sur le travail des producteurs africains, et particulièrement ivoiriens, le Ministre de l\'Agriculture a souligné que le défi de la production, désormais relevé, doit céder la place à celui de la qualité, pour porter durablement le label Côte d\'Ivoire. \r\nIl a ainsi invité producteurs et coopératives à s\'approprier les nouvelles exigences internationales, notamment en matière de traçabilité à travers la norme ARS 1000, qu\'il a qualifiée de « passeport commun pour l\'avenir de la filière ». \r\nLe Ministre Bruno KONÉ a également insisté sur l\'urgence d\'accélérer la transformation locale du cacao, afin de réduire la vulnérabilité de la filière face aux fluctuations des marchés internationaux, avec un objectif de 60 % de transformation à l\'horizon 2030.\r\nPour conclure, il a lancé un appel à l\'unité : « Votre union sera votre force. Restez ensemble, travaillez ensemble, avancez ensemble », dans l\'intérêt des producteurs et de la Côte d\'Ivoire.', NULL, 1, 1, '2026-07-29 12:23:38', '2026-07-29 12:23:38'),
(9, 'JOURNÉE DE L\'OIA CAFÉ-CACAO À DUÉKOUÉ', 'journee-de-l-oia-cafe-cacao-a-duekoue', '', NULL, 1, 1, '2026-08-06 14:54:42', '2026-08-06 14:54:42');

-- --------------------------------------------------------

--
-- Structure de la table `press_book`
--

CREATE TABLE `press_book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `date_event` date DEFAULT NULL,
  `time_event` varchar(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `organizer` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `press_book`
--

INSERT INTO `press_book` (`id`, `title`, `slug`, `description`, `date_event`, `time_event`, `location`, `organizer`, `category`, `cover_image`, `status`, `is_featured`, `created_at`, `updated_at`) VALUES
(5, 'Cacao, une rente menacée', 'cacao-une-rente-menacee', 'Alassane Ouattara a déclaré l\'augmentation du prix du cacao, fixé par l\'État, le 1ᵉʳ octobre 2025. Alors qu\'elle représente 14% du PIB ivoirien, la filière reste pourtant gravement menacée par les conséquences du changement climatique et par la déforestation.\r\n\r\nLe 1ᵉʳ octobre 2025, Alassane Ouattara décrétait l’augmentation du prix du cacao, fixé chaque année en Côte d’Ivoire par l’État par le biais du puissant Conseil Café-Cacao (CCC). Une annonce politiquement importante, car cette culture représente 14% du PIB ivoirien et nourrit indirectement 5 millions de personnes, mais qui n’estompe pas les menaces majeures qui pèsent sur la filière : le dérèglement climatique (notamment les pluies torrentielles de 2023) qui engendre une baisse des récoltes, ainsi que l’entrée en vigueur prochainement de la loi européenne interdisant l’importation de produits issus de la déforestation, alors que le pays aurait perdu 90 % de ses forêts primaires principalement du fait de l’activité agricole.\r\n\r\nCette hausse des prix d’achat n’empêche pas non plus les trafics de prospérer. En effet, ces prix restent bien inférieurs au cours du marché, ce qui permet le développement de réseaux illégaux entre la Côte d’Ivoire et les pays frontaliers, au premier rang duquel le Liberia, nouveau front pionnier de la culture extensive du cacao. Cette culture attise par ailleurs les tensions autour du foncier, sujet qui divise les populations depuis la fin de la colonisation et l’arrivée massive de migrants burkinabés et baoulés (originaires du centre du pays) vers les régions du sud et l’ouest.\r\n\r\nComment expliquer que le cacao occupe une telle place dans l’économie ivoirienne ? Comment l’État et les cultivateurs tentent-ils de s’adapter aux nouvelles contraintes climatiques et réglementaires qui pèsent sur eux ? Et dans quelle mesure la culture du cacao est-elle au cœur de conflits fonciers, révélateurs des tensions sociales, identitaires et politiques qui structurent le pays ?\r\n\r\nFocus -  Comment qualifier le travail des enfants dans les cacaoyères ?\r\n\r\nAvec Ladji Bamba, criminologue, maître de conférences à l’université Houphouët-Boigny d’Abidjan\r\n\r\nEn Côte d’Ivoire, de nombreux mineurs travaillent dans les plantations de cacao. Certains aident leurs parents sur l’exploitation familiale, dans une société encore largement agricole où les besoins de main d’œuvre sont importants et où l’apprentissage du métier de cacaoculteur est perçue comme essentielle dans l’éducation des enfants. D’autres sont en revanche victimes de trafic et de traite, des phénomènes pourtant parfois confondus du fait d’une mauvaise compréhension de ces réalités par les pays du Nord et les normes internationales.\r\n\r\nQuelle est la réalité du travail des enfants en Côte d’Ivoire ? Quelles mesures mettre en place pour garantir le respect des droits de l’enfant ?', '2026-07-15', '15:30', NULL, NULL, NULL, NULL, 'published', 0, '2026-07-15 13:35:14', '2026-07-15 13:35:13'),
(6, 'Conférence scientifique internationale Cacao/Café Adaptation de la culture du cacaoyer et du caféier aux changements climatiques', 'conference-scientifique-internationale-cacao-cafe-adaptation-de-la-culture-du-cacaoyer-et-du-cafeier-aux-changements-climatiques', 'Les lampions se sont éteints et les carillons se sont ouverts sur la conférence scientifique internationale de la culture du cacaoyer et du caféier aux changements climatiques organisée du 6-8 juin 2023 à Yaoundé par le Conseil interprofessionnel du cacao et du café (CICC) sous le patronage du Ministère du commerce.\r\n\r\n\r\n       Le représentant permanent de la Côte-d’Ivoire, précise que les défis qui nous interpellent ont trait à la durabilité. La production cacaoyère précise-t-il , a chuté de 80- 1500 dollars et l’une des voies de sortie c’est le passage à l’irrigation. Le nouveau Règlement sur la déforestation est sensé entré en vigueur le 1er janvier 2025.Pour le Ministre du Commerce, la question de durabilité devrait être mise en débat. Il s’inspire du Programme d’Assistance technique et financier, et des Mesures d’Accompagnement pour la Banane (MAB). Les femmes ont brillé par leur participation au Forum « Cacao/Café : Climat, Femme et Investissements » organisé le 8 juin au Hilton Hôtel de Yaoundé présidé par le Ministre de la Promotion de la Femme et de la Famille (MINPROFF) Marie Thérèse Abena Ondoa qu’assistait le Ministre Luc Magloire Mbarga Atangana. A en croire la MINPROFF, « nous devons passer à l’action. Nous devons tous sauver la planète. Nous devons inviter les femmes ».\r\n\r\n\r\n     Placée sur le thème : « Adaptation pratique d’adaptation de la culture du cacaoyer et du caféier aux changements climatiques »,à la cérémonie de clôture, le patron du commerce le Ministre Luc Magloire Mbarga Atangana a souligné avec emphase au sujet de l’adaptation aux changements, que  nous n’allons pas nous arrêter à cette conférence . Apollinaire Ngwe président du Conseil Exécutif du Conseil international du cacao et du café (CICC) abondant dans ce sens s’est souvenu de la position gouvernementale selon laquelle l’adaptation aux changements climatiques dépend de l’avenir des filières café/cacao. Car,le changement climatique affecte l’émergence des pathologies et n’est pas la seule cause. Pour une meilleure prise en charge, le comité scientifique commis cet effet suggère l’implication de l’assurance indiciaire agricole pour assurer un meilleur rendement.\r\n\r\n\r\n        L’Ambassadeur Chef de délégation de l’Union européenne pour le Cameroun et la Guinée Equatoriale Jean Claude Van Damne a profité de cette présence des Organisations faitières, pour réitérer sa volonté à œuvrer aux côtés du Cameroun dans la transition agroécologique : « l’UE est engagée à accompagner le Cameroun pour améliorer les revenus des producteurs ». L’un des centres d’attraction de la conférence était la tenue des Cocoa Talks  en marge . Aussi a-t-on appris avec beaucoup de surprise que sur 100 milliards de chiffres d’affaires dans le secteur cacao, seuls 2 % vont aux pays producteurs.\r\n\r\n\r\n\r\n      SE Aly Touré Ambassadeur représentant permanent de la Cote d’Ivoire au sein des Organisations des produits de base à Londres, suggère qu’il faudrait rechercher des solutions à la problématique. Car, les défis qui interpellent les deux secteurs ont trait à la durabilité. Aussi va-t-il   conseiller de repenser les systèmes de productivité afin de développer les solutions durables. La production cacaoyère depuis 40 ans a chuté de 10 mille – 80 mille dollars. La maitrise de l’environnement culturel, l’introduction de l’irrigation, les préventions des agences pathogènes figurent parmi les pistes de solutions identifiées pour améliorer les conditions de travail des petits producteurs.', '2026-07-15', '15:38', NULL, NULL, NULL, NULL, 'published', 0, '2026-07-15 13:40:25', '2026-07-15 13:40:25'),
(7, 'Cameroun: une conférence scientifique pour aider les planteurs face aux perturbations climatiques', 'cameroun-une-conference-scientifique-pour-aider-les-planteurs-face-aux-perturbations-climatiques', 'Face au désarroi des petits planteurs, le CICC, le Comité interprofessionel du cacao et du café, a organisé cette semaine une conférence scientifique internationale à Yaoundé. Le Cameroun est le troisième pays africain producteur de cacao. Mais depuis une décennie, l\'intensification des périodes chaudes ou l\'irrégularité des pluies, entraîne une surmortalité des plants de cacaoyers dans les exploitations. Pour aider les producteurs, le CICC propose de revoir la logique jusque-là mise en œuvre et de ne plus baser le calendrier agricole sur des périodes fixes, mais sur le comportement de la plante.\r\n\r\nOmer Maledy parle d\'une situation dramatique pour de nombreux petits planteurs au Cameroun. Depuis douze ans, le Conseil interprofessionnel du cacao et du café dont il est le secrétaire exécutif, documente les pertes annuelles sur les exploitations.\r\n\r\n« On ne va pas dire aux planteurs \"attendez, attendez le jour où on va mettre au point une variété résistante\" ! Certains ont perdu plus de 70 % de leur production habituelle. Depuis douze ans, c\'est les mêmes cris de détresse, les mêmes sollicitations des producteurs qui viennent au Conseil Interprofessionnel en disant \"mais qu\'est-ce qu\'on doit faire ?\" »\r\n\r\nTrop de soleil ou trop de pluies selon les régions. Des cacaoyers qui dessèchent ou pourrissent. Et des planteurs qui n\'ont pas les moyens d\'attendre.\r\n\r\nPour trouver des palliatifs, au niveau du Cameroun, le CICC a commis des experts sur le terrain. Le professeur Nicolas Niemenak, enseignant à l\'Université Yaoundé 1, chercheur en biotechnologie végétale teste lui-même sur sa plantation une cacaoculture basée sur l\'observation du comportement de la plante.\r\n\r\n« Dans le passé, le ministère de l\'Agriculture avec les ingénieurs avaient établi un programme calendaire de gestion basée sur les saisons rigides qui étaient bien connues avant. On savait qu\'entre janvier/février/mars, il faut faire la taille. Entre février et avril, il faut pulvériser. Il faut traiter, il faut faire ceci. Maintenant quand un cacaoyer renouvelle ses feuilles, ce n\'est plus forcément en février, mars, comme c\'était prévu dans l\'ancien calendrier agricole. Parfois dans la zone de Ntui c\'est maintenant qu\'on observe un renouvellement des feuilles, donc les traitements phytosanitaires, qu\'on appliquait en avril, on est obligés de les appliquer à fin mai, début juin. »\r\n\r\nObserver et agir en conséquence. Le CICC, avec ses partenaires, veut partager avec les petits producteurs sur le terrain une nouvelle fiche technique sur cette nouvelle approche de la cacao-culture qui fait vivre environ 400 000 personnes.', '2026-07-15', '15:47', NULL, NULL, NULL, NULL, 'published', 0, '2026-07-15 13:47:42', '2026-07-15 13:47:42'),
(8, 'Côte d\'Ivoire: le blocage du secteur du cacao', 'cote-d-ivoire-le-blocage-du-secteur-du-cacao', 'La Côte d\'Ivoire, premier producteur mondial de cacao, traverse actuellement une crise sans précédent dans sa filière cacao. Des centaines de camions chargés de fèves de cacao sont bloqués aux portes du port d\'Abidjan, incapables de décharger leur précieuse cargaison. Cette situation met en lumière les tensions croissantes entre les producteurs, les exportateurs et le Conseil Café Cacao (CCC), l\'organisme régulateur de la filière.\r\n\r\nMais qui maîtrise réellement le prix du cacao ivoirien dans un marché mondialisé dominé par quelques grands acheteurs ?\r\n\r\nSelon les syndicats de producteurs, le blocage observé autour du port d’Abidjan s’explique par le refus de certains exportateurs d’acheter les fèves au prix bord champ fixé par l’État à 2 800 FCFA le kilo (4,27 €) pour la campagne en cours.\r\n\r\nFace à la polémique, le directeur général du Conseil du Café-Cacao (CCC), Yves Brahima Koné, a tenu à rassurer les planteurs lors d’une conférence de presse, le 14 janvier. “Je leur demande de se ressaisir. Ce n\'est pas la bonne direction que de vouloir pénaliser les plus grands producteurs. Ce sont eux les acteurs premiers de la filière. Sans planteurs, il n’y a pas de cacao, il n’y a pas de chocolat”, a-t-il déclaré, assurant que l’ensemble de la production ivoirienne serait écoulé.', '2026-07-15', '15:49', NULL, NULL, NULL, NULL, 'published', 0, '2026-07-15 13:50:51', '2026-07-15 13:50:51'),
(9, 'Côte d\'Ivoire : Duékoué, Bruno Nabagné Koné lance la nouvelle dynamique de l\'OIA Café-Cacao devant plus de 12000 producteurs', 'cote-d-ivoire-duekoue-bruno-nabagne-kone-lance-la-nouvelle-dynamique-de-l-oia-cafe-cacao-devant-plus-de-12000-producteurs', 'Duékoué a vécu, samedi 25 juillet 2026, une journée historique pour la filière café-cacao. Plus de 12 000 producteurs, venus des treize délégations régionales de Côte d\'Ivoire, ont convergé vers la Place des Fêtes à l\'occasion de la première Journée nationale de l\'Organisation interprofessionnelle agricole de la filière Café-Cacao (OIA Café-Cacao), officiellement reconnue le 3 décembre 2025.\r\n\r\n\r\nDès les premières heures de la matinée, la cité de l\'ouest ivoirien s\'est animée au rythme des arrivées de cars, de minicars et de motos transportant des milliers de producteurs, responsables de coopératives et acteurs de la filière. Dans une ambiance festive, marquée par des chants, des danses et des messages de mobilisation, cette forte affluence a illustré les attentes suscitées par cette nouvelle organisation appelée à fédérer l\'ensemble des maillons de la chaîne café-cacao.\r\n\r\n\r\nPlacée sous le parrainage du ministre de l\'Agriculture, du Développement rural et des Productions vivrières, Bruno Nabagné Koné, la cérémonie a également été marquée par le lancement officiel d\'une campagne nationale de distribution gratuite de produits phytosanitaires homologués.\r\n\r\n\r\nFace à une assistance nombreuse, le ministre a exhorté les producteurs à renforcer leur cohésion afin de relever les défis auxquels la filière est confrontée.\r\n\r\n\r\nSelon lui, les nouvelles exigences des marchés internationaux doivent être perçues comme une opportunité d\'améliorer la qualité de la production nationale et de consolider le leadership de la Côte d\'Ivoire sur le marché mondial du cacao.\r\n\r\n\r\nIl a réaffirmé la volonté du gouvernement de poursuivre les réformes engagées pour améliorer durablement les revenus des producteurs, développer la transformation locale et renforcer la compétitivité de la filière.\r\n\r\n\r\nLe ministre a également insisté sur l\'importance d\'accroître les rendements sur les superficies déjà exploitées afin de concilier performance agricole et préservation du couvert forestier.\r\n\r\n\r\nRéitérant l\'engagement de l\'État aux côtés des producteurs, il a rappelé que le Président Alassane Ouattara fait du développement de la filière café-cacao un axe majeur de sa politique agricole.\r\n\r\n\r\nLe Directeur général du Conseil du Café-Cacao, Koné Brahima Yves, a invité les producteurs à anticiper l\'entrée en vigueur, au 1er janvier 2027, des nouvelles normes du marché européen.\r\nIl a présenté le Système national de traçabilité, qui permettra de suivre chaque cargaison de cacao depuis la plantation jusqu\'au port d\'exportation, renforçant ainsi la transparence de la filière et la valorisation du cacao ivoirien sur les marchés internationaux.\r\n\r\n\r\nLe responsable a également insisté sur l\'importance de l\'enregistrement des producteurs afin qu\'ils obtiennent leur carte professionnelle, désormais indispensable pour commercialiser leur production dans le respect des nouvelles règles.\r\n\r\n\r\nIl a, en outre, encouragé les planteurs à privilégier exclusivement l\'utilisation de produits phytosanitaires homologués afin de garantir la qualité des récoltes et la conformité des exportations.\r\n\r\n\r\nPour le président de l\'OIA Café-Cacao, Issiaka Diakité, cette première Journée nationale marque le début d\'une nouvelle étape pour la gouvernance de la filière.\r\n\r\n\r\nIl a présenté l\'organisation comme un cadre de concertation réunissant producteurs, commerçants et transformateurs autour d\'un objectif commun : bâtir une filière plus moderne, plus performante et mieux préparée aux mutations du marché mondial.\r\n\r\n\r\nLa forte mobilisation enregistrée à Duékoué, a-t-il souligné, témoigne de la confiance que les acteurs accordent désormais à cette nouvelle structure interprofessionnelle.\r\n\r\n\r\nAu-delà des interventions officielles, la cérémonie a été ponctuée par la remise symbolique de bons de produits phytosanitaires à dix producteurs, donnant le coup d\'envoi d\'une vaste campagne nationale.\r\n\r\n\r\nCette opération prévoit la distribution de 2 000 cartons de produits phytosanitaires homologués au profit de près de 20 000 producteurs répartis dans les treize délégations régionales de l\'OIA Café-Cacao.\r\n\r\n\r\nLes autorités estiment que cette initiative permettra de protéger environ 40 000 hectares de plantations contre les maladies et les ravageurs, tout en améliorant durablement les performances des exploitations.\r\n\r\n\r\nUn dialogue direct avec les producteurs\r\nLa rencontre s\'est achevée par un échange entre les responsables de la filière et les producteurs autour de plusieurs préoccupations majeures, notamment la traçabilité, la rémunération des planteurs, la qualité des récoltes et la modernisation des exploitations.\r\n\r\n\r\nCette première Journée nationale aura ainsi permis de poser les bases d\'une gouvernance plus participative de la filière café-cacao.\r\n\r\n\r\nÀ travers cette mobilisation exceptionnelle, l\'OIA Café-Cacao affiche clairement son ambition : fédérer les différents acteurs autour d\'une vision commune afin de bâtir une filière plus compétitive, durable et créatrice de richesse pour les producteurs comme pour l\'économie ivoirienne.\r\n\r\n\r\n\r\nWassimagnon', '2026-07-26', '09:23', NULL, NULL, NULL, NULL, 'published', 0, '2026-07-29 11:25:11', '2026-07-29 11:43:22'),
(10, 'Journée nationale de l\'OIA CAFE-CACAO', 'journee-nationale-de-l-oia-cafe-cacao', 'Revue de presse', '2026-07-29', '13:45', NULL, NULL, NULL, NULL, 'published', 0, '2026-07-29 11:44:45', '2026-07-29 11:49:06'),
(11, 'Journée nationale de l\'OIA CAFE-CACAO', 'journee-nationale-de-l-oia-cafe-cacao-1', '', '2026-07-26', '10:00', NULL, NULL, NULL, NULL, 'published', 0, '2026-08-06 12:57:15', '2026-08-06 12:57:15');

-- --------------------------------------------------------

--
-- Structure de la table `press_book_photos`
--

CREATE TABLE `press_book_photos` (
  `id` int(11) NOT NULL,
  `press_book_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `is_cover` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `press_book_photos`
--

INSERT INTO `press_book_photos` (`id`, `press_book_id`, `photo_path`, `caption`, `is_cover`, `sort_order`, `created_at`) VALUES
(10, 5, 'uploads/press-book/photos/images-5-1784129714-0.jpg', '', 0, 0, '2026-07-15 13:35:14'),
(11, 5, 'uploads/press-book/photos/images-4-1784129714-1.jpg', '', 0, 0, '2026-07-15 13:35:14'),
(12, 5, 'uploads/press-book/photos/images-3-1784129714-2.jpg', '', 0, 0, '2026-07-15 13:35:14'),
(13, 5, 'uploads/press-book/photos/images-2-1784129714-3.jpg', '', 0, 0, '2026-07-15 13:35:14'),
(14, 5, 'uploads/press-book/photos/images-1-1784129714-4.jpg', '', 0, 0, '2026-07-15 13:35:14'),
(15, 6, 'uploads/press-book/photos/cfcc2-1-1784130217-0.jpg', '', 0, 0, '2026-07-15 13:43:37'),
(16, 6, 'uploads/press-book/photos/cf-cacao-1-1784130217-1.png', '', 0, 0, '2026-07-15 13:43:37'),
(17, 6, 'uploads/press-book/photos/cf-cacao-2-1784130217-2.png', '', 0, 0, '2026-07-15 13:43:37'),
(18, 6, 'uploads/press-book/photos/cf-2-1784130217-3.png', '', 0, 0, '2026-07-15 13:43:37'),
(19, 6, 'uploads/press-book/photos/cf-cc1-1784130217-4.jpg', '', 0, 0, '2026-07-15 13:43:37'),
(20, 7, 'uploads/press-book/photos/gettyimages-1372733143-1784130463-0.webp', '', 0, 0, '2026-07-15 13:47:43'),
(21, 9, 'uploads/press-book/photos/photo-1785057811-1785331511-0.jpg', '', 0, 0, '2026-07-29 11:25:11'),
(22, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-36-1785332685-0.jpeg', '', 0, 0, '2026-07-29 11:44:45'),
(23, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-46-1785332946-0.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(24, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-45-1785332946-1.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(25, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-43-1-1785332946-2.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(26, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-43-1785332946-3.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(27, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-42-1785332946-4.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(28, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-40-1785332946-5.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(29, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-39-1785332946-6.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(30, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-38-1785332946-7.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(31, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-37-1785332946-8.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(32, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-36-2-1785332946-9.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(33, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-36-1-1785332946-10.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(34, 10, 'uploads/press-book/photos/whatsapp-image-2026-07-29-at-11-55-36-1785332946-11.jpeg', '', 0, 0, '2026-07-29 11:49:06'),
(35, 11, 'uploads/press-book/photos/photo-2026-08-06-13-13-45-1786028235-0.jpg', '', 0, 0, '2026-08-06 12:57:15'),
(36, 11, 'uploads/press-book/photos/photo-2026-08-06-13-13-45-1-1786028236-1.jpg', '', 0, 0, '2026-08-06 12:57:16');

-- --------------------------------------------------------

--
-- Structure de la table `press_book_videos`
--

CREATE TABLE `press_book_videos` (
  `id` int(11) NOT NULL,
  `press_book_id` int(11) NOT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `youtube_id` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `press_book_videos`
--

INSERT INTO `press_book_videos` (`id`, `press_book_id`, `youtube_url`, `youtube_id`, `title`, `created_at`) VALUES
(7, 5, 'https://www.youtube.com/watch?v=H9kz1WNdhDA', 'H9kz1WNdhDA', NULL, '2026-07-15 13:35:14'),
(8, 5, 'https://www.youtube.com/watch?v=7gApptO_W64', '7gApptO_W64', NULL, '2026-07-15 13:35:14'),
(9, 5, 'https://www.youtube.com/watch?v=d6krYjiSD18', 'd6krYjiSD18', NULL, '2026-07-15 13:35:14'),
(10, 8, 'https://www.youtube.com/watch?v=AbQDBHN77No', 'AbQDBHN77No', NULL, '2026-07-15 13:50:53'),
(11, 8, 'https://www.youtube.com/watch?v=3xujmOPJS8I', '3xujmOPJS8I', NULL, '2026-07-15 13:50:54');

-- --------------------------------------------------------

--
-- Structure de la table `price_trends`
--

CREATE TABLE `price_trends` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `price_trends`
--

INSERT INTO `price_trends` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Cacao', 'cacao', 'Les prix du cacao en 2026 sont marqués par une forte volatilité. Sur le marché international, après les pics historiques de fin 2024, les cours ont fortement chuté, gravitant autour de $5 933 la tonne (soit environ 3 400 dollars la tonne à certaines périodes de l\'année).\r\n\r\nEn Côte d\'Ivoire, après un prix record de 2 800 FCFA le kg pour la campagne principale, le prix bord champ a été réajusté à la baisse à 1 200 FCFA le kg pour la campagne intermédiaire, qui s\'étend jusqu\'en septembre 2026. Pour la prochaine campagne 2026-2027, le Conseil Café Cacao prévoit des prix d\'achat indicatifs se situant autour de 3 312 FCFA le kg', 'active', '2026-07-15 12:14:07', '2026-07-15 12:14:07'),
(4, 'Cafe', 'cafe', 'Les prix du café en 2026 sont marqués par la volatilité. Après des sommets historiques, les cours mondiaux de l\'Arabica ont fortement chuté—jusqu\'à -40% sur six mois—grâce à des récoltes abondantes. Pourtant, en supermarché ou au comptoir, cette baisse ne se répercute pas immédiatement sur le ticket de caisse.\r\n\r\n🌱 Tendances de consommation 2026Le café de spécialité : \r\n\r\nTrès prisé, il tire le marché vers le haut malgré la baisse des prix sur les cafés de qualité inférieure.Les boissons fraîches : Le cold brew (infusion à froid) et le café glacé s\'imposent comme des incontournables dans les tendances de consommation.', 'active', '2026-07-15 14:43:29', '2026-07-15 14:43:29');

-- --------------------------------------------------------

--
-- Structure de la table `price_trend_histories`
--

CREATE TABLE `price_trend_histories` (
  `id` int(11) NOT NULL,
  `price_trend_id` int(11) NOT NULL,
  `national_price` decimal(15,2) NOT NULL,
  `international_price` decimal(15,2) NOT NULL,
  `application_date` date NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `price_trend_histories`
--

INSERT INTO `price_trend_histories` (`id`, `price_trend_id`, `national_price`, `international_price`, `application_date`, `comment`, `created_at`, `updated_at`) VALUES
(5, 3, 1200.00, 4300.00, '2026-07-15', '', '2026-07-15 12:15:35', '2026-07-15 12:15:35'),
(6, 4, 1700.00, 2223.00, '2026-07-15', '', '2026-07-15 14:56:43', '2026-07-15 14:56:43'),
(7, 3, 1200.00, 3240.00, '2026-07-29', '', '2026-07-29 15:35:33', '2026-07-29 15:35:33'),
(8, 4, 1700.00, 3014.00, '2026-07-29', '', '2026-07-29 15:38:16', '2026-07-29 15:38:16');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` varchar(512) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrateur avec tous les droits', '2026-07-01 14:09:35', '2026-07-01 14:09:35'),
(2, 'editor', 'Rédacteur capable de gérer le contenu', '2026-07-01 14:09:35', '2026-07-01 14:09:35'),
(3, 'user', 'Utilisateur standard', '2026-07-01 14:09:35', '2026-07-01 14:09:35');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `group_name` varchar(100) DEFAULT 'general',
  `type` varchar(50) DEFAULT 'text',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`, `group_name`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Organisation Interprofessionnelle Agricole Café-Cacao', 'general', 'text', 'Nom du site', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(2, 'site_description', 'Un acteur clé pour une filière équitable, durable et prospère au service des producteurs.', 'general', 'textarea', 'Description du site', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(3, 'site_email', 'contact@oia-cafecacao.ci', 'general', 'email', 'Email de contact', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(4, 'site_phone', '(+225) 27 20 00 00 00', 'general', 'text', 'Numéro de téléphone', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(5, 'site_address', 'Yamoussoukro, Côte d\'Ivoire', 'general', 'textarea', 'Adresse', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(6, 'facebook_url', '', 'social', 'text', 'URL Facebook', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(7, 'twitter_url', '', 'social', 'text', 'URL Twitter', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(8, 'instagram_url', '', 'social', 'text', 'URL Instagram', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(9, 'youtube_url', '', 'social', 'text', 'URL YouTube', '2026-07-01 14:09:36', '2026-07-01 14:09:36'),
(10, 'presentation_title', 'Qui sommes-nous ?', 'presentation', 'text', 'Titre de la presentation generale', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(11, 'presentation_subtitle', 'Presentation generale de l\'Organisation Interprofessionnelle Agricole Cafe-Cacao.', 'presentation', 'textarea', 'Sous-titre de la presentation generale', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(12, 'presentation_hero_text', 'L\'Organisation Interprofessionnelle Agricole Cafe-Cacao (OIA) regroupe les acteurs de la filiere pour promouvoir une gouvernance inclusive, durable et performante.', 'presentation', 'textarea', 'Texte principal de la presentation generale', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(13, 'presentation_section_title', 'Un espace de gouvernance pour la filiere cafe-cacao', 'presentation', 'text', 'Titre de section', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(14, 'presentation_section_text', 'L\'OIA Cafe-Cacao travaille a la cohesion des acteurs, a l\'amelioration des conditions de production, et a la valorisation des produits sur les marches locaux et internationaux.', 'presentation', 'textarea', 'Texte de section', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(15, 'presentation_history_title', 'Historique', 'presentation', 'text', 'Titre bloc historique', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(16, 'presentation_history_text', 'L\'OIA Café-Cacao s\'est construite autour d\'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.L\'OIA Café-Cacao s\'est construite autour d\'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.L\'OIA Café-Cacao s\'est construite autour d\'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.L\'OIA Café-Cacao s\'est construite autour d\'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.L\'OIA Café-Cacao s\'est construite autour d\'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.L\'OIA Café-Cacao s\'est construite autour d\'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.L\'OIA Café-Cacao s\'est construite autour d\'une ambition commune : structurer la filière, renforcer la cohésion entre acteurs et accompagner son évolution.', 'presentation', 'textarea', 'Texte bloc historique', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(17, 'presentation_mission_title', 'Mission', 'presentation', 'text', 'Titre bloc mission', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(18, 'presentation_mission_text', 'Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.Promouvoir une filière café-cacao compétitive, durable et inclusive au service des producteurs, des opérateurs et des consommateurs.', 'presentation', 'textarea', 'Texte bloc mission', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(19, 'presentation_vision_title', 'Vision', 'presentation', 'text', 'Titre bloc vision', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(20, 'presentation_vision_text', 'Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.Devenir un acteur de référence dans la gouvernance, la transformation et la valorisation de la filière café-cacao.', 'presentation', 'textarea', 'Texte bloc vision', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(21, 'presentation_objective_title', 'Objectif', 'presentation', 'text', 'Titre bloc objectif', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(22, 'presentation_objective_text', 'Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.Consolider la cohésion des acteurs, améliorer la performance économique et renforcer la visibilité de la filière sur les marchés.', 'presentation', 'textarea', 'Texte bloc objectif', '2026-07-10 12:36:07', '2026-07-10 12:36:07'),
(23, 'presentation_structure_title', 'Présentation de la structure', 'presentation', 'text', 'Titre de la structure', '2026-07-10 12:51:38', '2026-07-10 12:51:38'),
(24, 'presentation_structure_text', 'Reconnue par l’État ivoirien, l’OIA réunit l’ensemble des collèges de la chaîne de valeur et assure la coordination entre les acteurs du café et du cacao.', 'presentation', 'textarea', 'Texte de présentation de la structure', '2026-07-10 12:51:38', '2026-07-10 12:51:38'),
(25, 'presentation_structure_secondary_text', 'Elle constitue une interface de dialogue entre les producteurs, les transformateurs, les commerçants et les pouvoirs publics pour renforcer la gouvernance de la filière.', 'presentation', 'textarea', 'Texte secondaire de la structure', '2026-07-10 12:51:38', '2026-07-10 12:51:38'),
(26, 'presentation_structure_mission_title', 'Missions', 'presentation', 'text', 'Titre des missions de la structure', '2026-07-10 12:51:38', '2026-07-10 12:51:38'),
(27, 'presentation_structure_mission_items', 'Assurer la concertation permanente entre les différents collèges professionnels.\r\nReprésenter et défendre les intérêts économiques et institutionnels de la filière.\r\nContribuer à la régulation, à la modernisation et à la qualité de la filière.', 'presentation', 'textarea', 'Liste des missions de la structure (séparées par des lignes)', '2026-07-10 12:51:38', '2026-07-10 12:51:58'),
(28, 'presentation_vision_items_title', 'Points clés', 'presentation', 'text', 'Titre de la liste vision', '2026-07-10 12:55:58', '2026-07-10 12:55:58'),
(29, 'presentation_vision_items', 'Vision stratégique\r\nFilière de référence\r\nInnovation et durabilité', 'presentation', 'textarea', 'Liste des points vision (une ligne par point)', '2026-07-10 12:55:58', '2026-07-10 12:56:11'),
(30, 'presentation_objective_items_title', 'Axes prioritaires', 'presentation', 'text', 'Titre de la liste objectif', '2026-07-10 12:55:58', '2026-07-10 12:55:58'),
(31, 'presentation_objective_items', 'Renforcer la cohésion des acteurs\r\nAméliorer la performance économique\r\nValoriser la filière sur les marchés', 'presentation', 'textarea', 'Liste des points objectif (une ligne par point)', '2026-07-10 12:55:58', '2026-07-10 12:55:58'),
(32, 'president_hero_title', 'Présentation du président dd dfdf df df', 'presentation', 'text', 'Titre du bloc présentation du président', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(33, 'president_hero_subtitle', 'Découvrez la vision et l’engagement de M. Siaka DIAKITÉ pour une filière café-cacao forte, durable et inclusive.', 'presentation', 'textarea', 'Sous-titre du bloc président', '2026-07-13 12:32:41', '2026-07-13 12:46:42'),
(34, 'president_hero_text', 'Avec une vision claire, il porte l’ambition d’une filière plus forte, plus juste et plus durable.', 'presentation', 'textarea', 'Texte du bloc président', '2026-07-13 12:32:41', '2026-07-13 12:46:42'),
(35, 'president_name', 'M. Siaka DIAKITÉ dfdf df', 'presentation', 'text', 'Nom du président', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(36, 'president_role', 'Président du Conseil d\'Administration', 'presentation', 'text', 'Rôle du président', '2026-07-13 12:32:41', '2026-07-13 12:46:42'),
(37, 'president_bio', 'Président du Conseil d\'Administration de l\'OIA Café-Cacao, il porte une vision stratégique pour la filière.', 'presentation', 'textarea', 'Biographie courte du président', '2026-07-13 12:32:41', '2026-07-13 12:46:42'),
(38, 'president_vision_text', 'Instaurer une gouvernance partagée, renforcer la compétitivité et assurer la durabilité de la filière café-cacao ivoirienne.', 'presentation', 'textarea', 'Texte de la vision du président', '2026-07-13 12:32:41', '2026-07-13 12:46:42'),
(39, 'president_vision_items', 'Gouvernance transparente\r\nGouvernance transparente', 'presentation', 'textarea', 'Points de la vision du président', '2026-07-13 12:32:41', '2026-07-13 12:48:30'),
(40, 'president_engagements_text', 'Au cœur de son action, il place l’organisation, la cohésion et l’innovation pour la filière café-cacao. df df df df', 'presentation', 'textarea', 'Texte des engagements du président', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(41, 'president_engagements_items', 'Renforcer la transparence de la gouvernance\r\nSoutenir le développement économique des acteurs\r\nEncourager l’adoption de bonnes pratiques durables\r\nPromouvoir la qualité et l’exportation des produits', 'presentation', 'textarea', 'Points des engagements du président', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(42, 'president_quote_text', 'Nous œuvrons pour renforcer la cohésion, la transparence et la compétitivité de notre filière café-cacao, pilier de l’économie ivoirienne. dfdf df d', 'presentation', 'textarea', 'Citation du président', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(43, 'president_quote_author', 'M. Siaka DIAKITÉ', 'presentation', 'text', 'Auteur de la citation du président', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(44, 'president_quote_role', 'Président du Conseil d’Administration', 'presentation', 'text', 'Fonction de la citation du président', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(45, 'president_cta_organization_text', 'Voir l’organisation', 'presentation', 'text', 'Texte du bouton organisation', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(46, 'president_cta_partners_text', 'Découvrir les partenaires', 'presentation', 'text', 'Texte du bouton partenaires', '2026-07-13 12:32:41', '2026-07-13 12:32:41'),
(47, 'president_photo', 'uploads/president/president_photo_1783954041.jpeg', 'presentation', 'text', 'Photo du président', '2026-07-13 12:46:42', '2026-07-13 12:47:21');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','blocked','pending') DEFAULT 'pending',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `role_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `avatar`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', 'OIA', 'admin@oia-cafecacao.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'active', '2026-08-10 11:23:33', '2026-07-01 14:09:36', '2026-08-10 11:23:33');

-- --------------------------------------------------------

--
-- Structure de la table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_type` enum('upload','youtube') NOT NULL DEFAULT 'upload',
  `file_path` varchar(255) DEFAULT NULL,
  `youtube_id` varchar(50) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `videos`
--

INSERT INTO `videos` (`id`, `title`, `slug`, `description`, `video_type`, `file_path`, `youtube_id`, `thumbnail`, `is_published`, `created_by`, `created_at`, `updated_at`) VALUES
(7, 'Côte d\'ivoire : La filière du cacao en crise', 'co-te-d-ivoire-la-filiere-du-cacao-en-crise', 'La Côte d\'Ivoire, premier producteur mondial de cacao, a divisé de plus de moitié le prix d\'achat de \"l\'or brun\" aux planteurs.  Le nouveau prix, 1 200 francs CFA (1,82 euros) est inférieur de près de 60% au montant record de 2 800 francs (4,26 euros) en vigueur depuis octobre, une coupe qui intervient dans un contexte de chute des cours mondiaux et d\'une crise de surstockage.', 'youtube', NULL, '2MYgQR9KjtA', 'https://i.ytimg.com/vi/2MYgQR9KjtA/maxresdefault.jpg', 1, 1, '2026-07-15 15:19:48', '2026-07-15 15:19:48'),
(8, 'Face à la crise du cacao, la Côte d\'Ivoire réduit drastiquement le prix d\'achat aux planteurs', 'face-a-la-crise-du-cacao-la-cote-d-ivoire-reduit-drastiquement-le-prix-d-achat-aux-planteurs', 'En Côte d’Ivoire, le prix du kilogramme de cacao pour la campagne intermédiaire de commercialisation est fixé à 1 200 francs CFA. Une baisse de plus de 57 % par rapport à la campagne principale, qui avait atteint le prix record de 2 800 francs CFA le kilo. L’annonce a été faite le 4 mars 2026 par le ministre de l’Agriculture, Bruno Nabagné Koné, lors d’une conférence de presse à Abidjan.', 'youtube', NULL, '4A-e1ho1p7o', 'https://i.ytimg.com/vi/4A-e1ho1p7o/maxresdefault.jpg', 1, 1, '2026-07-15 15:20:57', '2026-07-15 15:20:57'),
(9, 'NCI Reportages | Les cités maudites du cacao', 'nci-reportages-les-cites-maudites-du-cacao', 'NCI est une chaîne TV généraliste qui propose des programmes majoritairement produits en Côte d’Ivoire et traite tous les sujets de façon moderne .', 'youtube', NULL, 'krct4TbrO4I', 'https://i.ytimg.com/vi/krct4TbrO4I/maxresdefault.jpg', 1, 1, '2026-07-15 15:22:16', '2026-07-15 15:22:16'),
(10, 'Côte d\'Ivoire : la filière cacao dans une situation critique avec la chute des ventes', 'cote-d-ivoire-la-filiere-cacao-dans-une-situation-critique-avec-la-chute-des-ventes', 'La filière cacao est en crise en Côte d\'Ivoire. Son cours a chuté mais le prix fixé par l\'Etat ne baisse pas, et des milliers de tonnes de cacao ne trouvent plus d\'acheteurs et s’entassent dans les entrepôts. L\'Etat s\'est engagé à racheter le stock. Mais l\'Etat et les syndicats ne sont pas d\'accord sur le volume de cacao entassé dans les entrepôts. Reportage de Julia Guggenheim.', 'youtube', NULL, 'gpTsfXQkbFs', 'https://i.ytimg.com/vi/gpTsfXQkbFs/maxresdefault.jpg', 1, 1, '2026-07-15 15:23:29', '2026-07-15 15:23:29'),
(11, 'Crise du cacao : la Côte d\'Ivoire réduit drastiquement le prix d\'achat aux planteurs • FRANCE 24', 'crise-du-cacao-la-cote-d-ivoire-reduit-drastiquement-le-prix-d-achat-aux-planteurs-france-24', 'La Côte d\'Ivoire, premier producteur mondial de cacao, a divisé de plus de moitié le prix d\'achat de \"l\'or brun\" aux planteurs.  Le nouveau prix, 1.200 francs CFA (1,82 euro) est inférieur de près de 60% au montant record de 2.800 francs (4,26 euros) en vigueur depuis octobre, une coupe qui intervient dans un contexte de chute des cours mondiaux et d\'une crise de surstockage.', 'youtube', NULL, 'CzUzvYLVtSA', 'https://i.ytimg.com/vi/CzUzvYLVtSA/maxresdefault.jpg', 1, 1, '2026-07-15 15:24:18', '2026-07-15 15:24:18'),
(12, 'Côte-d\'Ivoire - Jus de Cacao: Un Espoir Pour Les Planteurs', 'cote-d-ivoire-jus-de-cacao-un-espoir-pour-les-planteurs', 'Depuis le début de l’année 2026, le marché du cacao traverse une phase d’ajustement en Côte d’Ivoire. Dans ce contexte, les acteurs de la filière explorent de nouvelles approches. Une dynamique qui ouvre la voie à de nouvelles opportunités de transformation locale, comme la valorisation du jus de cacao, offrant des sources de revenus additionnels sans impacter la commercialisation des fèves pour les planteurs.\"', 'youtube', NULL, 'yihR8vBIjTI', 'https://i.ytimg.com/vi/yihR8vBIjTI/maxresdefault.jpg', 1, 1, '2026-07-15 15:24:55', '2026-07-15 15:24:55'),
(19, 'Toute l\'actualité de la Côte d\'Ivoire chaque jour sur RTI Info avec les journaux, reportages et magazines d\'information RTI1, RTI2, Radio Côte d\'Ivoire.', 'toute-l-actualite-de-la-cote-d-ivoire-chaque-jour-sur-rti-info-avec-les-journaux-reportages-et-magazines-d-information-rti1-rti2-radio-cote-d-ivoire', '', 'youtube', NULL, 'no10FWWscMM', 'https://i.ytimg.com/vi/no10FWWscMM/maxresdefault.jpg', 1, 1, '2026-07-29 12:40:23', '2026-07-29 12:40:23'),
(24, 'DUEKOUE : L’OIA Café cacao tient sa première édition de sa journée nationale', 'duekoue-l-oia-cafe-cacao-tient-sa-premie-re-e-dition-de-sa-journe-e-nationale', 'DUEKOUE : L’OIA Café cacao tient sa première édition de sa journée nationale', 'youtube', NULL, '_Lpl4LlDhO4', 'https://i.ytimg.com/vi/_Lpl4LlDhO4/maxresdefault.jpg', 1, 1, '2026-07-29 13:55:02', '2026-07-29 13:55:02');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actes_oia`
--
ALTER TABLE `actes_oia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`),
  ADD KEY `idx_date_pub` (`date_pub`);

--
-- Index pour la table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_user` (`user_id`),
  ADD KEY `idx_log_action` (`action`),
  ADD KEY `idx_log_date` (`created_at`);

--
-- Index pour la table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`),
  ADD KEY `idx_start_date` (`start_date`);

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `idx_article_slug` (`slug`),
  ADD KEY `idx_article_status` (`status`),
  ADD KEY `idx_article_published` (`published_at`),
  ADD KEY `idx_article_featured` (`is_featured`);

--
-- Index pour la table `article_comments`
--
ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_article_id` (`article_id`),
  ADD KEY `idx_is_approved` (`is_approved`);

--
-- Index pour la table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_banners_published` (`is_published`),
  ADD KEY `idx_banners_sort_order` (`sort_order`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_category_slug` (`slug`),
  ADD KEY `idx_category_parent` (`parent_id`);

--
-- Index pour la table `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Index pour la table `contact_adresses`
--
ALTER TABLE `contact_adresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_statut_adresse` (`statut`);

--
-- Index pour la table `contact_coordonnees`
--
ALTER TABLE `contact_coordonnees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_statut` (`statut`);

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contact_email` (`email`),
  ADD KEY `idx_contact_date` (`date_add`);

--
-- Index pour la table `contact_replies`
--
ALTER TABLE `contact_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_message_id` (`message_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`);

--
-- Index pour la table `filieres`
--
ALTER TABLE `filieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_published` (`is_published`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_media_user` (`user_id`),
  ADD KEY `idx_media_folder` (`folder`);

--
-- Index pour la table `newsletter_campaigns`
--
ALTER TABLE `newsletter_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Index pour la table `newsletter_logs`
--
ALTER TABLE `newsletter_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_campaign` (`campaign_id`),
  ADD KEY `idx_subscriber` (`subscriber_id`),
  ADD KEY `idx_status` (`status`);

--
-- Index pour la table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_newsletter_email` (`email`),
  ADD KEY `idx_newsletter_active` (`is_active`);

--
-- Index pour la table `newsletter_templates`
--
ALTER TABLE `newsletter_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Index pour la table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_filiere` (`filiere_id`),
  ADD KEY `idx_published` (`is_published`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Index pour la table `operator_filieres`
--
ALTER TABLE `operator_filieres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_operator_filiere` (`operator_id`,`filiere_id`),
  ADD KEY `idx_operator_id` (`operator_id`),
  ADD KEY `idx_filiere_id` (`filiere_id`);

--
-- Index pour la table `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_organisations_slug` (`slug`),
  ADD KEY `idx_organisations_published` (`is_published`);

--
-- Index pour la table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_partners_slug` (`slug`),
  ADD KEY `idx_partners_published` (`is_published`);

--
-- Index pour la table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`),
  ADD KEY `idx_album_id` (`album_id`);

--
-- Index pour la table `photo_albums`
--
ALTER TABLE `photo_albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`);

--
-- Index pour la table `press_book`
--
ALTER TABLE `press_book`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_date_event` (`date_event`);

--
-- Index pour la table `press_book_photos`
--
ALTER TABLE `press_book_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_press_book_id` (`press_book_id`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Index pour la table `press_book_videos`
--
ALTER TABLE `press_book_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_press_book_id` (`press_book_id`);

--
-- Index pour la table `price_trends`
--
ALTER TABLE `price_trends`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `uniq_price_trends_slug` (`slug`),
  ADD KEY `idx_price_trends_slug` (`slug`),
  ADD KEY `idx_price_trends_status` (`status`),
  ADD KEY `idx_price_trends_name` (`name`);

--
-- Index pour la table `price_trend_histories`
--
ALTER TABLE `price_trend_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_price_history_product_date` (`price_trend_id`,`application_date`),
  ADD KEY `idx_price_history_date` (`application_date`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_projects_slug` (`slug`),
  ADD KEY `idx_projects_status` (`status`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_role_name` (`name`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session_user` (`user_id`),
  ADD KEY `idx_session_activity` (`last_activity`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`),
  ADD KEY `idx_setting_key` (`key_name`),
  ADD KEY `idx_setting_group` (`group_name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`),
  ADD KEY `idx_user_status` (`status`),
  ADD KEY `idx_user_role` (`role_id`);

--
-- Index pour la table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_published` (`is_published`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actes_oia`
--
ALTER TABLE `actes_oia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `colleges`
--
ALTER TABLE `colleges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `contact_adresses`
--
ALTER TABLE `contact_adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `contact_coordonnees`
--
ALTER TABLE `contact_coordonnees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `contact_replies`
--
ALTER TABLE `contact_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `filieres`
--
ALTER TABLE `filieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newsletter_campaigns`
--
ALTER TABLE `newsletter_campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `newsletter_logs`
--
ALTER TABLE `newsletter_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `newsletter_templates`
--
ALTER TABLE `newsletter_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `operators`
--
ALTER TABLE `operators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `operator_filieres`
--
ALTER TABLE `operator_filieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `organisations`
--
ALTER TABLE `organisations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `photo_albums`
--
ALTER TABLE `photo_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `press_book`
--
ALTER TABLE `press_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `press_book_photos`
--
ALTER TABLE `press_book_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `press_book_videos`
--
ALTER TABLE `press_book_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `price_trends`
--
ALTER TABLE `price_trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `price_trend_histories`
--
ALTER TABLE `price_trend_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `article_comments`
--
ALTER TABLE `article_comments`
  ADD CONSTRAINT `article_comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `operators`
--
ALTER TABLE `operators`
  ADD CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `operator_filieres`
--
ALTER TABLE `operator_filieres`
  ADD CONSTRAINT `operator_filieres_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `operator_filieres_ibfk_2` FOREIGN KEY (`filiere_id`) REFERENCES `filieres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `press_book_photos`
--
ALTER TABLE `press_book_photos`
  ADD CONSTRAINT `press_book_photos_ibfk_1` FOREIGN KEY (`press_book_id`) REFERENCES `press_book` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `press_book_videos`
--
ALTER TABLE `press_book_videos`
  ADD CONSTRAINT `press_book_videos_ibfk_1` FOREIGN KEY (`press_book_id`) REFERENCES `press_book` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `price_trend_histories`
--
ALTER TABLE `price_trend_histories`
  ADD CONSTRAINT `price_trend_histories_ibfk_1` FOREIGN KEY (`price_trend_id`) REFERENCES `price_trends` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
