-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/10/2024 às 02:27
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `esp32`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `corridas`
--

CREATE TABLE `corridas` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `corridas`
--

INSERT INTO `corridas` (`id`, `x`, `y`, `status`) VALUES
(21, 9, 2, 'pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `leitura`
--

CREATE TABLE `leitura` (
  `id` int(11) NOT NULL,
  `esp_mac` varchar(50) NOT NULL,
  `dados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`dados`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `leitura`
--

INSERT INTO `leitura` (`id`, `esp_mac`, `dados`) VALUES
(500, 'DD:EE:45:50:79:CC', '{\"AA:BB:CC:DD:EE:FF\":-61,\"AA:BB:CC:DD:EE:FF\":-62,\"AA:BB:CC:DD:EE:FF\":-66,\"AA:BB:CC:DD:EE:FF\":-80,\"AA:BB:CC:DD:EE:FF\":-82,\"AA:BB:CC:DD:EE:FF\":-85,\"AA:BB:CC:DD:EE:FF\":-87,\"AA:BB:CC:DD:EE:FF\":-90,\"AA:BB:CC:DD:EE:FF\":-91,\"AA:BB:CC:DD:EE:FF\":-92,\"AA:BB:CC:DD:EE:FF\":-92,\"AA:BB:CC:DD:EE:FF\":-92,\"AA:BB:CC:DD:EE:FF\":-93,\"AA:BB:CC:DD:EE:FF\":-93,\"AA:BB:CC:DD:EE:FF\":-94,\"AA:BB:CC:DD:EE:FF\":-95,\"AA:BB:CC:DD:EE:FF\":-96,\"AA:BB:CC:DD:EE:FF\":-96,\"AA:BB:CC:DD:EE:FF\":-96,\"AA:BB:CC:DD:EE:FF\":-96,\"AA:BB:CC:DD:EE:FF\":-96,\"AA:BB:CC:DD:EE:FF\":-97,\"AA:BB:CC:DD:EE:FF\":-98,\"AA:BB:CC:DD:EE:FF\":-101}');

-- --------------------------------------------------------

--
-- Estrutura para tabela `operadores`
--

CREATE TABLE `operadores` (
  `id` varchar(50) NOT NULL,
  `dados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`dados`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `operadores`
--

INSERT INTO `operadores` (`id`, `dados`) VALUES
('op01', '{\"situacao\":\"livre\",\"x\":1,\"y\":1}'),
('op02', '{\"situacao\":\"ocupado\", \"x\": 25, \"y\": 15}'),
('op03', '{\"situacao\":\"livre\", \"x\": 2, \"y\": 4}'),
('op04', '{\"situacao\":\"livre\", \"x\": 22, \"y\": 5}'),
('op05', '{\"situacao\": \"ocupado\", \"x\": 10, \"y\": 10}'),
('op06', '{\"situacao\":\"livre\",\"x\":1,\"y\":1}'),
('op07', '{\"situacao\":\"livre\",\"x\":1,\"y\":1}');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pecas`
--

CREATE TABLE `pecas` (
  `id` int(11) NOT NULL,
  `corrida_id` int(11) NOT NULL,
  `nome_peca` varchar(50) NOT NULL,
  `quantidade` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pecas`
--

INSERT INTO `pecas` (`id`, `corrida_id`, `nome_peca`, `quantidade`) VALUES
(2, 19, 'Parafuso', 3),
(3, 19, 'Engrenagem', 3),
(4, 19, 'Correia', 2),
(5, 20, 'Parafuso', 5),
(6, 20, 'Engrenagem', 3),
(7, 20, 'Correia', 2),
(8, 21, 'Parafuso', 2),
(9, 21, 'Engrenagem', 2),
(10, 21, 'Correia', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `percurso`
--

CREATE TABLE `percurso` (
  `id` int(11) NOT NULL,
  `caminho` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`caminho`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `percurso`
--

INSERT INTO `percurso` (`id`, `caminho`) VALUES
(15, '[[1, 1], [2, 1], [3, 1], [4, 1], [5, 1], [6, 1], [7, 1], [8, 1], [8, 2], [9, 2]]'),
(19, '[[1, 2], [1, 1], [2, 1], [3, 1], [4, 1], [5, 1], [6, 1], [7, 1], [8, 1], [8, 2], [9, 2]]'),
(20, '[[2, 1], [3, 1], [4, 1], [5, 1], [6, 1], [7, 1], [8, 1], [8, 2], [9, 2]]');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posicao`
--

CREATE TABLE `posicao` (
  `macAddress` varchar(50) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posicao`
--

INSERT INTO `posicao` (`macAddress`, `x`, `y`) VALUES
('DD:EE:45:50:79:CC', 2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `redes`
--

CREATE TABLE `redes` (
  `id` int(11) NOT NULL,
  `celula` varchar(50) NOT NULL,
  `bssid` varchar(50) NOT NULL,
  `rssi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Armazena o RSSI previamente coletado.';

--
-- Despejando dados para a tabela `redes`
--

INSERT INTO `redes` (`id`, `celula`, `bssid`, `rssi`) VALUES
(521, '(1, 1)', '5C:E3:0E:15:51:F1', -50),
(522, '(1, 1)', '10:72:23:F8:05:61', -51),
(523, '(1, 1)', '94:EA:EA:06:3B:DE', -78),
(524, '(1, 1)', '70:4F:57:09:A9:27', -79),
(525, '(1, 1)', 'A4:56:CC:E4:FE:3A', -85),
(526, '(1, 1)', '18:34:AF:62:61:9C', -86),
(527, '(1, 1)', '48:4B:D4:9F:1F:C8', -87),
(528, '(1, 1)', '84:0B:BB:BC:2E:90', -88),
(529, '(1, 1)', '68:D4:0C:DE:FF:AF', -89),
(682, '(1, 2)', '5C:E3:0E:15:51:F1', -44),
(683, '(1, 2)', '5E:E3:0E:25:51:F1', -45),
(684, '(1, 2)', '10:72:23:F8:05:61', -64),
(685, '(1, 2)', '94:EA:EA:06:3B:DE', -77),
(686, '(1, 2)', '70:4F:57:09:A9:27', -80),
(687, '(1, 2)', 'A4:56:CC:E4:FE:3A', -81),
(688, '(1, 2)', '18:34:AF:62:61:9C', -86),
(689, '(1, 2)', '54:A6:5C:8F:A1:7F', -86),
(690, '(1, 2)', '48:4B:D4:9F:1F:C8', -87),
(805, '(2, 1)', '5C:E3:0E:15:51:F1', -60),
(806, '(2, 1)', '5E:E3:0E:25:51:F1', -61),
(807, '(2, 1)', '70:4F:57:09:A9:27', -76),
(808, '(2, 1)', '10:72:23:F8:05:61', -76),
(809, '(2, 1)', '94:EA:EA:06:3B:DE', -79),
(810, '(2, 1)', 'A4:56:CC:E4:FE:3A', -87),
(811, '(2, 1)', '6C:B5:6B:8E:16:C0', -90),
(812, '(2, 1)', '6E:B5:6B:8E:16:C1', -92),
(813, '(2, 1)', '60:E3:27:52:7D:6A', -93),
(814, '(2, 1)', '84:0B:BB:F6:F3:B0', -94),
(815, '(2, 1)', '18:34:AF:62:61:9C', -94),
(873, '(1, 3)', '5C:E3:0E:15:51:F1', -66),
(874, '(1, 3)', '5E:E3:0E:25:51:F1', -66),
(875, '(1, 3)', '10:72:23:F8:05:61', -67),
(876, '(1, 3)', '94:EA:EA:06:3B:DE', -85),
(877, '(1, 3)', '18:34:AF:62:61:9C', -87),
(878, '(1, 3)', '48:4B:D4:9F:1F:C8', -87),
(879, '(1, 3)', '70:4F:57:09:A9:27', -90),
(880, '(1, 3)', '44:D4:54:AB:97:31', -90),
(881, '(1, 3)', 'D4:6E:0E:BC:8F:C6', -91),
(882, '(1, 3)', 'A4:56:CC:E4:FE:3A', -91),
(883, '(1, 3)', 'C8:5D:38:A3:D3:DC', -91),
(884, '(1, 3)', '48:F8:B3:E3:BF:7C', -92);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `corridas`
--
ALTER TABLE `corridas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `leitura`
--
ALTER TABLE `leitura`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `esp_mac` (`esp_mac`);

--
-- Índices de tabela `operadores`
--
ALTER TABLE `operadores`
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices de tabela `pecas`
--
ALTER TABLE `pecas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `percurso`
--
ALTER TABLE `percurso`
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices de tabela `posicao`
--
ALTER TABLE `posicao`
  ADD UNIQUE KEY `macAddress` (`macAddress`);

--
-- Índices de tabela `redes`
--
ALTER TABLE `redes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `corridas`
--
ALTER TABLE `corridas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `leitura`
--
ALTER TABLE `leitura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1446;

--
-- AUTO_INCREMENT de tabela `pecas`
--
ALTER TABLE `pecas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `redes`
--
ALTER TABLE `redes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=959;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
