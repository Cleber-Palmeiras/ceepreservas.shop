-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 07-Jun-2016 às 13:35
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tcc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservas`
--

CREATE TABLE IF NOT EXISTS `reservas` (
  `cod_sala` int(20) unsigned DEFAULT '0',
  `matricula` int(20) unsigned DEFAULT '0',
  `datatempo` datetime DEFAULT NULL,
  `ticket` varchar(255) DEFAULT '0',
  `disponivel` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `reservas`
--

INSERT INTO `reservas` (`cod_sala`, `matricula`, `datatempo`, `ticket`, `disponivel`) VALUES
(11, 104, '2016-06-04 14:00:00', '', 0),
(11, 104, '2016-05-31 18:00:00', '', 0),
(24, 104, '2016-06-02 14:00:00', '', 0),
(24, 104, '2016-05-31 08:00:00', '', 0),
(1, 104, '2016-06-09 18:00:00', '', 0),
(24, 104, '2016-06-01 08:00:00', '', 0),
(1, 104, '2016-05-30 18:00:00', '', 0),
(11, 104, '2016-06-04 18:00:00', '', 0),
(24, 104, '2016-06-03 14:00:00', '', 0),
(1, 104, '2016-06-02 18:00:00', '', 0),
(11, 104, '2016-05-27 18:00:00', '', 0),
(1, 104, '2016-06-02 08:00:00', '', 0),
(3, 104, '2016-05-20 14:00:00', '', 0),
(4, 104, '2016-05-25 08:00:00', '', 0),
(24, 104, '2016-05-19 08:00:00', '', 0),
(11, 104, '2016-05-20 18:00:00', '', 0),
(24, 104, '2016-05-21 14:00:00', '', 0),
(28, 104, '2016-05-20 18:00:00', '', 0),
(24, 104, '2016-05-25 18:00:00', '', 0),
(24, 104, '2016-05-21 08:00:00', '', 0),
(24, 104, '2016-05-20 14:00:00', '', 0),
(28, 104, '2016-05-19 08:00:00', '', 0),
(24, 104, '2016-05-19 18:00:00', '', 0),
(22, 697, '2016-05-21 18:00:00', '', 0),
(24, 104, '2016-05-20 18:00:00', '', 0),
(11, 104, '2016-05-19 08:00:00', '', 0),
(24, 697, '2016-05-21 18:00:00', '', 0),
(24, 104, '2016-05-21 18:00:00', '', 0),
(1, 104, '2016-05-19 18:00:00', '', 0),
(11, 104, '2016-05-27 08:00:00', '', 0),
(1, 104, '2016-05-23 18:00:00', '', 0),
(2, 104, '2016-05-19 08:00:00', '', 0),
(24, 104, '2016-05-19 14:00:00', '', 0),
(1, 104, '2016-05-26 14:00:00', '', 0),
(19, 104, '2016-05-14 14:00:00', '', 0),
(24, 104, '2016-05-14 18:00:00', '', 0),
(22, 104, '2016-05-19 18:00:00', '', 0),
(11, 104, '2016-05-14 14:00:00', '', 0),
(11, 104, '2016-05-14 18:00:00', '', 0),
(24, 104, '2016-05-14 08:00:00', '', 0),
(3, 104, '2016-05-13 18:00:00', '', 0),
(24, 104, '2016-05-13 14:00:00', '', 0),
(3, 104, '2016-05-18 14:00:00', '', 0),
(24, 104, '2016-05-20 08:00:00', '', 0),
(4, 104, '2016-05-14 18:00:00', '', 0),
(4, 104, '2016-05-13 18:00:00', '', 0),
(11, 104, '2016-05-20 14:00:00', '', 0),
(11, 104, '2016-05-13 08:00:00', '', 0),
(22, 104, '2016-05-13 14:00:00', '', 0),
(22, 104, '2016-05-14 14:00:00', '', 0),
(11, 104, '2016-05-21 08:00:00', '', 0),
(19, 104, '2016-05-13 18:00:00', '', 0),
(24, 104, '2016-06-01 18:00:00', '', 0),
(11, 104, '2016-06-01 18:00:00', '', 0),
(4, 104, '2016-06-09 08:00:00', '', 0),
(1, 104, '2016-06-09 08:00:00', '', 0),
(22, 104, '2016-06-04 18:00:00', '', 0),
(24, 104, '2016-06-04 18:00:00', '', 0),
(24, 104, '2016-06-06 08:00:00', '', 0),
(28, 104, '2016-06-06 08:00:00', '', 0),
(11, 104, '2016-06-06 14:00:00', '', 0),
(22, 104, '2016-06-06 18:00:00', '', 0),
(1, 104, '2016-06-14 18:00:00', '', 0),
(24, 104, '2016-06-10 18:00:00', '', 0),
(28, 104, '2016-06-06 18:00:00', '', 0),
(24, 104, '2016-06-09 18:00:00', '', 0),
(24, 104, '2016-06-07 08:00:00', '', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `salas`
--

CREATE TABLE IF NOT EXISTS `salas` (
  `cod_sala` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `nome_sala` varchar(255) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  KEY `cod_sala` (`cod_sala`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Extraindo dados da tabela `salas`
--

INSERT INTO `salas` (`cod_sala`, `nome_sala`, `status`) VALUES
(1, 'Datashow 1', 'Disponivel'),
(2, 'Datashow 2', 'Disponivel'),
(3, 'Datashow 3', 'Disponivel'),
(4, 'Datashow 4', 'Disponivel'),
(11, 'Laboratorio', 'Disponivel'),
(22, 'Laboratorio de  fisica', 'Disponivel'),
(19, 'Npd', 'Indisponivel'),
(24, 'Anfiteatro', 'Disponivel'),
(28, 'Auditório', 'Disponivel');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` int(5) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `sobrenome` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `cpf` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `matricula` int(8) NOT NULL,
  `email` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `tipo` varchar(15) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `senha` varchar(1000) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `nivel_usuario` enum('0','1','2') COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `data_cadastro` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data_ultimo_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ativado` enum('0','1') COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`usuario_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=92 ;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nome`, `sobrenome`, `cpf`, `matricula`, `email`, `tipo`, `senha`, `nivel_usuario`, `data_cadastro`, `data_ultimo_login`, `ativado`) VALUES
(17, 'Patricia ', 'Silva', '846965', 8754, 'dayanna93araujo@gmail.com', 'a', '1a418b5884c3e71e67b89dfc5d61b455', '0', '2016-04-12 22:06:42', '0000-00-00 00:00:00', '0'),
(90, 'Enzo', 'Gabreil', '9445', 8903, 'enzo@gmail.com', 'a', 'ade37b430e8a259e0b8eec105ea4ba16a380aebadcaa65b56e98ea09e60e7792a424e39cf4c2f50d9d5f21c7e5e28cbd8b77c8153135f9d0822dc496d45b87ca', '0', '2016-05-14 13:33:02', '0000-00-00 00:00:00', '0'),
(18, 'Joao', 'Marcos', '104', 1, 'cleberaraujowilliam97@gmail.com', 'a', '4dff4ea340f0a823f15d3f4f01ab62eae0e5da579ccb851f8db9dfe84c58b2b37b89903a740e1ee172da793a6e79d560e5f7f9bd058a12a280433ed6fa46510a', '0', '2016-04-25 16:40:37', '2016-06-07 08:08:10', '0'),
(2, 'Vicente', 'Arauj', '5691', 2, 'joaocleber@hotmail.com', 'p', '7741b5d9139b66227065f2a6fc40199ccc670714b60c101711f1aa1f44d689876f3a3016ff9da9fe67671037def4e97906920d74522d6c414d0e7d7c8e45ca1a', '0', '2015-09-12 17:58:39', '2016-01-17 15:16:59', '0'),
(12, 'Kerllon', 'Andrade', '48764', 7, 'KERLON@GMAIL.COM', 'p', 'e8957e3d9fb0ba076ccf889c8ec58fe147bb05cc1a7bd0f17ead862579c8fd854f1a722c5c20841e44b54f386eea987145a2f8a0ade97448847c4016e2bd232b', '0', '2015-10-07 20:26:27', '2016-01-17 16:34:03', '0'),
(9, 'Paula', 'Area', '66665', 8, 'paula@gmail.com', 'p', '123', '0', '2015-09-13 17:03:45', '2016-01-18 16:43:42', '0'),
(13, 'Cleber ', 'Palmeiras', '9834', 123456, 'cleberaraujoverdao@gmail.com', 'a', '10d7b37c8069046fd1c31f6fd4f494a363de9847763f8230480c50c1ceb1f729339c1f640600a735628a8e8e5c49cfdd0f89a2c499a5964c421bc7fc06379208', '0', '2016-04-12 13:03:05', '0000-00-00 00:00:00', '1'),
(14, 'Marquinhos ', 'Santos vigno', '697', 5823, 'marquinhoscamaleao@gmail.com', 'p', '8a2887d462907a942880d2b215adff76ef89525fec17cff0ff0fc1fa44960e20b0c0850301705963058e484583493c6f62b151651e92912d544af9047ce298f7', '0', '2016-04-12 20:58:44', '0000-00-00 00:00:00', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
