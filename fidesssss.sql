-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 26/11/2025 às 11:27
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `fides`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `espacos`
--

DROP TABLE IF EXISTS `espacos`;
CREATE TABLE IF NOT EXISTS `espacos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `valor` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `espacos`
--

INSERT INTO `espacos` (`id`, `nome`, `img`, `valor`) VALUES
(7, 'Sala de Reuniões', 'uploads/espacos/espaco_68dc1d7f68847.webp', 50),
(9, 'Laboratório de Informática', 'uploads/espacos/espaco_68dc1e0216095.webp', 30),
(10, 'Quadra Poliesportiva', 'uploads/espacos/espaco_68dc1e2dc97d2.webp', 45),
(11, 'Pátio Externo', 'uploads/espacos/espaco_68dc275105f8d.webp', 150),
(12, 'Sala de Reuniões Externa', 'uploads/espacos/espaco_68dc28d94de73.webp', 200),
(13, 'espaco teste sem data inicio e fim', 'uploads/espacos/espaco_6924929332793.png', 3000);

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

DROP TABLE IF EXISTS `eventos`;
CREATE TABLE IF NOT EXISTS `eventos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `data` date NOT NULL,
  `descricao` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `localizacao` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `imagem` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`id`, `nome`, `data`, `descricao`, `localizacao`, `imagem`) VALUES
(12, 'teste imagem', '2025-10-01', 'descricao', 'sale', 'uploads/1760033149_herosection.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomeUsuario` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `nomeUsuario`, `mensagem`) VALUES
(1, 'João Silva', 'Gostei muito do evento da Semana de Tecnologia!'),
(2, 'Maria Oliveira', 'O sistema de reservas é bem prático, mas poderia ter mais opções de pagamento.'),
(3, 'Carlos Souza', 'As instalações são ótimas, recomendo a todos!'),
(4, 'feedback', 'feedback'),
(5, 'feeeeeedback', 'tessettteeee'),
(6, 'maria', '............'),
(7, 'r6ui', 'mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm'),
(8, 'Luigi', 'teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste teste'),
(9, 'Gigi', 'S2');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reserva` varchar(255) NOT NULL,
  `espaco` varchar(100) NOT NULL,
  `data` date NOT NULL,
  `inicio` time NOT NULL,
  `fim` time NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `status` enum('Pago','Pendente') NOT NULL DEFAULT 'Pendente',
  `usuario_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reserva_usuario` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`id`, `reserva`, `espaco`, `data`, `inicio`, `fim`, `valor`, `status`, `usuario_id`) VALUES
(3, 'teste', '12', '2025-10-07', '14:00:00', '17:00:00', 45.00, 'Pago', 8),
(7, 'teste reserva ', '9', '2025-11-24', '15:00:00', '16:00:00', 30.00, 'Pendente', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `isAdmin`, `cpf`, `telefone`) VALUES
(6, 'teste', 'teste@gmail.com', '$2y$10$vtf0ZJMT/aMfRVMtcAU3YOkPrUf3TajTkLbC7mtbsP9iuZvyxsU3e', 0, '', ''),
(7, 'admin', 'admin@fides.com', '$2y$10$VLI6d9YvDhjZhhO94bS1D..cJLNjZhuw3q1EuDhRaiRwTdSX6kD3.', 1, '', ''),
(8, 'testecpftelefone', 'testecpftelefone@gmail.com', '$2y$10$1EB/KvZny.Fju3yThzLvGuUSWss66umxlfqocbXr.OUNMfcZKf82u', 0, '11111111111', '111111111111');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
