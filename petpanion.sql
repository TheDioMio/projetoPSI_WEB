-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 04-Jan-2026 às 20:32
-- Versão do servidor: 10.6.22-MariaDB-0ubuntu0.22.04.1
-- versão do PHP: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `petpanion`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `age_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `vaccination_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `animal_type_id` int(11) NOT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `neutered` tinyint(1) DEFAULT 0,
  `location` varchar(150) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `statusDate` date NOT NULL DEFAULT '2026-01-03',
  `observations` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `animal`
--

INSERT INTO `animal` (`id`, `age_id`, `size_id`, `vaccination_id`, `description`, `age`, `animal_type_id`, `breed_id`, `neutered`, `location`, `user_id`, `created_at`, `name`, `status`, `statusDate`, `observations`) VALUES
(2, 2, 2, 1, 'Cão muito amigável', NULL, 1, 3, 1, 'Leiria', 2, '2026-01-04 00:15:06', 'Rex', 1, '2026-01-04', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal_age`
--

CREATE TABLE `animal_age` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `animal_age`
--

INSERT INTO `animal_age` (`id`, `description`) VALUES
(1, 'Jovem (0 – 2 anos)'),
(2, 'Adulto (3 – 8 anos)'),
(3, 'Sénior (> 9 anos)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal_size`
--

CREATE TABLE `animal_size` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `animal_size`
--

INSERT INTO `animal_size` (`id`, `description`) VALUES
(1, 'Pequeno (< 10 Kg)'),
(2, 'Médio (11 – 26 Kg)'),
(3, 'Grande (27-45 Kg)'),
(4, 'Gigante (> 45 Kgs)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal_type`
--

CREATE TABLE `animal_type` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `animal_type`
--

INSERT INTO `animal_type` (`id`, `description`) VALUES
(1, 'Indefinido'),
(2, 'Cão'),
(3, 'Gato');

-- --------------------------------------------------------

--
-- Estrutura da tabela `application`
--

CREATE TABLE `application` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `target_user_id` int(11) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `statusDate` date NOT NULL DEFAULT '2026-01-03',
  `isRead` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `application`
--

INSERT INTO `application` (`id`, `status`, `description`, `user_id`, `animal_id`, `type`, `created_at`, `target_user_id`, `data`, `statusDate`, `isRead`) VALUES
(2, 0, 'Tenho experiência com cães', 2, 2, 1, '2026-01-04 19:48:47', 2, '\"{\\\"age\\\":28,\\\"name\\\":\\\"Jo\\\\u00e3o Silva\\\",\\\"contact\\\":\\\"912345678\\\",\\\"motive\\\":\\\"Tenho experi\\\\u00eancia com c\\\\u00e3es\\\",\\\"home\\\":1,\\\"bills\\\":1,\\\"timeAlone\\\":2,\\\"children\\\":0,\\\"followUp\\\":1}\"', '2026-01-04', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Extraindo dados da tabela `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1767478681),
('user', '2', 1767479713),
('user', '3', 1767546195);

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Extraindo dados da tabela `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, NULL, NULL, NULL, 1767478681, 1767478681),
('animalObservations', 2, 'Ver e criar observações nos seus animais', NULL, NULL, 1767478681, 1767478681),
('animalsManager', 2, 'Fazer upload de avatar', NULL, NULL, 1767478681, 1767478681),
('applicationManeger', 2, 'applicationManeger', NULL, NULL, 1767478681, 1767478681),
('applyUserPro', 2, 'Candidatar-se a User Pro', NULL, NULL, 1767478681, 1767478681),
('createComment', 2, 'Criar comentários', NULL, NULL, 1767478681, 1767478681),
('deleteAnimalPhoto', 2, 'Apagar fotos de animais', NULL, NULL, 1767478681, 1767478681),
('deleteComment', 2, 'Apagar um comentários', NULL, NULL, 1767478681, 1767478681),
('fileDelete', 2, 'fileDelete', NULL, NULL, 1767478681, 1767478681),
('listComment', 2, 'Listar comentários de um anúncio', NULL, NULL, 1767478681, 1767478681),
('listingsManeger', 2, 'listingsManeger', NULL, NULL, 1767478681, 1767478681),
('loginBackend', 2, 'Acesso ao Backend (Login)', NULL, NULL, 1767478681, 1767478681),
('loginFrontend', 2, 'Acesso ao Frontend (Login)', NULL, NULL, 1767478681, 1767478681),
('messageManeger', 2, 'messageManeger', NULL, NULL, 1767478681, 1767478681),
('statisticsPage', 2, 'Aceder à página de estatísticas sobre a conta', NULL, NULL, 1767478681, 1767478681),
('updateAnimalStatus', 2, 'updateAnimalStatus', NULL, NULL, 1767478681, 1767478681),
('updateComment', 2, 'Atualizar um comentários', NULL, NULL, 1767478681, 1767478681),
('updatePost', 2, 'Update post', NULL, NULL, 1767478681, 1767478681),
('uploadAnimalPhoto', 2, 'Upload fotos de animais', NULL, NULL, 1767478681, 1767478681),
('uploadAvatar', 2, 'Fazer upload de avatar', NULL, NULL, 1767478681, 1767478681),
('user', 1, NULL, NULL, NULL, 1767478681, 1767478681),
('userPro', 1, NULL, NULL, NULL, 1767478681, 1767478681),
('viewAnimalPhotos', 2, 'Ver fotos de animais', NULL, NULL, 1767478681, 1767478681),
('viewAvatar', 2, 'Ver avatar de utilizadores', NULL, NULL, 1767478681, 1767478681),
('viewComment', 2, 'Ver um comentários', NULL, NULL, 1767478681, 1767478681);

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Extraindo dados da tabela `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'loginBackend'),
('user', 'animalsManager'),
('user', 'applicationManeger'),
('user', 'applyUserPro'),
('user', 'createComment'),
('user', 'deleteAnimalPhoto'),
('user', 'deleteComment'),
('user', 'fileDelete'),
('user', 'listComment'),
('user', 'listingsManeger'),
('user', 'loginFrontend'),
('user', 'messageManeger'),
('user', 'updateAnimalStatus'),
('user', 'updateComment'),
('user', 'updatePost'),
('user', 'uploadAnimalPhoto'),
('user', 'uploadAvatar'),
('user', 'viewAnimalPhotos'),
('user', 'viewAvatar'),
('user', 'viewComment'),
('userPro', 'animalObservations'),
('userPro', 'animalsManager'),
('userPro', 'applicationManeger'),
('userPro', 'createComment'),
('userPro', 'deleteAnimalPhoto'),
('userPro', 'deleteComment'),
('userPro', 'fileDelete'),
('userPro', 'listComment'),
('userPro', 'listingsManeger'),
('userPro', 'loginFrontend'),
('userPro', 'messageManeger'),
('userPro', 'statisticsPage'),
('userPro', 'updateAnimalStatus'),
('userPro', 'updateComment'),
('userPro', 'updatePost'),
('userPro', 'uploadAnimalPhoto'),
('userPro', 'uploadAvatar'),
('userPro', 'viewAnimalPhotos'),
('userPro', 'viewAvatar'),
('userPro', 'viewComment');

-- --------------------------------------------------------

--
-- Estrutura da tabela `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `breed`
--

CREATE TABLE `breed` (
  `id` int(11) NOT NULL,
  `description` varchar(120) NOT NULL,
  `animal_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `breed`
--

INSERT INTO `breed` (`id`, `description`, `animal_type_id`) VALUES
(1, 'Indefinido', 1),
(2, 'Indefinido', 2),
(3, 'Indefinido', 3),
(4, 'Golden Retriever ', 2),
(5, 'Labrador Retriever', 2),
(6, 'Pastor Alemão', 2),
(7, 'Bulldog Francês', 2),
(8, 'Poodle', 2),
(9, 'Beagle', 2),
(10, 'Rottweiler', 2),
(11, 'Yorkshire Terrier', 2),
(12, 'Dachshund (Cão Salsicha)', 2),
(13, 'Boxer', 2),
(14, 'Siamês', 3),
(15, 'Persa', 3),
(16, 'Maine Coon', 3),
(17, 'Bengal', 3),
(18, 'Ragdoll', 3),
(19, 'Sphynx', 3),
(20, 'British Shorthair', 3),
(21, 'Scottish Fold', 3),
(22, 'Abissínio', 3),
(23, 'Angorá Turco', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `file`
--

INSERT INTO `file` (`id`, `type_id`, `animal_id`, `user_id`, `path`, `created_at`) VALUES
(2, 2, NULL, 2, '/uploads/users/6959ce992ee71.jpg', NULL),
(5, 1, 2, 2, '/uploads/animals/2/animal_6959d76fd4451.jpg', '2026-01-04 02:58:55'),
(6, 1, 2, 2, '/uploads/animals/2/animal_6959d76fd4f7b.jpg', '2026-01-04 02:58:55');

-- --------------------------------------------------------

--
-- Estrutura da tabela `file_type`
--

CREATE TABLE `file_type` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `file_type`
--

INSERT INTO `file_type` (`id`, `description`) VALUES
(1, 'animal_photo'),
(2, 'avatar'),
(3, 'document');

-- --------------------------------------------------------

--
-- Estrutura da tabela `listing`
--

CREATE TABLE `listing` (
  `id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `animal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `views` int(11) DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `listing`
--

INSERT INTO `listing` (`id`, `description`, `animal_id`, `user_id`, `views`, `status`, `created_at`) VALUES
(2, 'Rex procura uma nova família', 2, 2, 2, 1, '2026-01-04 00:15:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `receiver_user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `isRead` tinyint(1) DEFAULT 0,
  `subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `message`
--

INSERT INTO `message` (`id`, `text`, `sender_user_id`, `receiver_user_id`, `created_at`, `isRead`, `subject`) VALUES
(2, 'Mensagem enviada de teste', 2, 2, '2026-01-04 17:12:44', 0, 'Mensagem de teste'),
(9, 'Mensagem enviada de teste', 2, 2, '2026-01-04 18:27:21', 0, 'Mensagem de teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1767477429),
('m130524_201442_init', 1767477431),
('m140506_102106_rbac_init', 1767477432),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1767477432),
('m180523_151638_rbac_updates_indexes_without_prefix', 1767477432),
('m190124_110200_add_verification_token_column_to_user_table', 1767477432),
('m200409_110543_rbac_update_mssql_trigger', 1767477432),
('m251022_183526_create_petpanion_tables', 1767477432),
('m251030_193654_file_add_type', 1767477432),
('m251030_194520_animal_add_name', 1767477432),
('m251105_183221_drop_created_time_column_from_comment_table', 1767477524),
('m251106_220432_create_table_file_type', 1767477537),
('m251106_222849_populate_table_FileType', 1767477537),
('m251106_224025_create_Aux_Tables', 1767477537),
('m251106_225639_populate_Aux_Tables', 1767477537),
('m251107_104830_update_animal_table', 1767477537),
('m251107_190628_update_file_table', 1767477537),
('m251107_191525_update_animal_table', 1767477537),
('m251108_000352_update_animal_table', 1767477537),
('m251108_154523_update_user_table_created_at_updated_at', 1767477537),
('m251110_104921_update_application_table', 1767477537),
('m251112_193803_add_role_id_column_to_user_table', 1767477537),
('m251112_203004_rename_roles_table_to_role', 1767477537),
('m251202_190809_message_add_isRead', 1767477537),
('m251207_020355_create_user_admin', 1767478631),
('m251208_225626_add_animal_status', 1767478631),
('m251213_230227_add_animal_statusDate', 1767478631),
('m251214_205028_add_application_isRead_statusDate', 1767478631),
('m251218_131652_turn_animal_id_on_application_nullable', 1767478631),
('m251218_213033_create_field_observations_in_animal_table', 1767478631);

-- --------------------------------------------------------

--
-- Estrutura da tabela `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `role`
--

INSERT INTO `role` (`id`, `description`) VALUES
(1, 'Administrador'),
(2, 'User Pro'),
(3, 'User');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `auth_key`, `password_hash`, `address`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`, `role_id`) VALUES
(1, 'Administrador', 'admin', 'oczrjc0YoJwsE9qnh-VfSnW3ShJzk4G7', '$2y$13$4mIGElGbpHk6eQgxQOeF9uEyhybbvO9g9oYGbQlJ33yuJcE14/8UK', 'Admin Address', NULL, 'admin@example.com', 10, '2026-01-03 22:17:10', '2026-01-03 22:17:10', NULL, 1),
(2, 'Utilizador de Frontend', 'userFront', 'Tar9teA3fowGlconKKoBQ0eVxwBDboNg', '$2y$13$cIHqKVHEIEI4BS5CsTJ9jeswsip6KcWzs0v6YlYu7fNgj6UU2f6N.', 'IPLeiria, Leiria', NULL, 'userFront@user.com', 10, '2026-01-03 22:35:13', '2026-01-03 22:35:13', 'JptBHWLj4TT0_KXJWqVCzkKdCzDzLOtd_1767479713', 3),
(3, 'userFront2', 'userFront2', 's8M-2buyivEyg86iLrJLfHhTYi3JG1vm', '$2y$13$WUudM9cHty4cxa3pgr1t0uZm/mfCQtANQSXI4ZXJJ6bSsiQAY3H9O', 'Sem endereço', NULL, 'userFront2@user.pt', 10, '2026-01-04 17:03:14', '2026-01-04 17:03:14', 'hoerlTT0X0YVg0j4DxSGsYOgf6cjDHwF_1767546194', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `vaccination`
--

CREATE TABLE `vaccination` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `vaccination`
--

INSERT INTO `vaccination` (`id`, `description`) VALUES
(1, 'Completa'),
(2, 'Parcial'),
(3, 'Não Vacinado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `visit`
--

CREATE TABLE `visit` (
  `id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `shelter_id` int(11) DEFAULT NULL,
  `visit_name` varchar(150) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_animal_animal_type` (`animal_type_id`),
  ADD KEY `fk_animal_breed` (`breed_id`),
  ADD KEY `fk_animal_user` (`user_id`),
  ADD KEY `idx_animal_age_id` (`age_id`),
  ADD KEY `idx_animal_size_id` (`size_id`),
  ADD KEY `idx_animal_vaccination_id` (`vaccination_id`),
  ADD KEY `idx-animal-status` (`status`);

--
-- Índices para tabela `animal_age`
--
ALTER TABLE `animal_age`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `animal_size`
--
ALTER TABLE `animal_size`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `animal_type`
--
ALTER TABLE `animal_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_application_animal` (`animal_id`),
  ADD KEY `fk_application_user` (`user_id`),
  ADD KEY `fk_application_target_user` (`target_user_id`);

--
-- Índices para tabela `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Índices para tabela `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Índices para tabela `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Índices para tabela `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Índices para tabela `breed`
--
ALTER TABLE `breed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_breed_animal_type` (`animal_type_id`);

--
-- Índices para tabela `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comment_listing` (`listing_id`),
  ADD KEY `fk_comment_user` (`user_id`);

--
-- Índices para tabela `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_file_animal` (`animal_id`),
  ADD KEY `fk_file_user` (`user_id`),
  ADD KEY `idx_file_type_id` (`type_id`);

--
-- Índices para tabela `file_type`
--
ALTER TABLE `file_type`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_listing_animal` (`animal_id`),
  ADD KEY `fk_listing_user` (`user_id`);

--
-- Índices para tabela `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_sender` (`sender_user_id`),
  ADD KEY `fk_message_receiver` (`receiver_user_id`);

--
-- Índices para tabela `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Índices para tabela `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`),
  ADD KEY `idx-user-role_id` (`role_id`);

--
-- Índices para tabela `vaccination`
--
ALTER TABLE `vaccination`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_visit_animal` (`animal_id`),
  ADD KEY `fk_visit_listing` (`listing_id`),
  ADD KEY `fk_visit_user` (`user_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `animal_age`
--
ALTER TABLE `animal_age`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `animal_size`
--
ALTER TABLE `animal_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `animal_type`
--
ALTER TABLE `animal_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `application`
--
ALTER TABLE `application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `breed`
--
ALTER TABLE `breed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `file_type`
--
ALTER TABLE `file_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `listing`
--
ALTER TABLE `listing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `vaccination`
--
ALTER TABLE `vaccination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `fk_animal_age` FOREIGN KEY (`age_id`) REFERENCES `animal_age` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animal_animal_type` FOREIGN KEY (`animal_type_id`) REFERENCES `animal_type` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animal_breed` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animal_size` FOREIGN KEY (`size_id`) REFERENCES `animal_size` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animal_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_animal_vaccination` FOREIGN KEY (`vaccination_id`) REFERENCES `vaccination` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `fk_application_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_application_target_user` FOREIGN KEY (`target_user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_application_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `breed`
--
ALTER TABLE `breed`
  ADD CONSTRAINT `fk_breed_animal_type` FOREIGN KEY (`animal_type_id`) REFERENCES `animal_type` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_listing` FOREIGN KEY (`listing_id`) REFERENCES `listing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `fk_file_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_type` FOREIGN KEY (`type_id`) REFERENCES `file_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_file_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `listing`
--
ALTER TABLE `listing`
  ADD CONSTRAINT `fk_listing_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_listing_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_message_receiver` FOREIGN KEY (`receiver_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_message_sender` FOREIGN KEY (`sender_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk-user-role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `fk_visit_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_visit_listing` FOREIGN KEY (`listing_id`) REFERENCES `listing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_visit_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
