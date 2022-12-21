-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Nov-2022 às 22:32
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `applista`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `cat_id` bigint(20) UNSIGNED NOT NULL,
  `cat_nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cat_descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cat_padrao` tinyint(4) DEFAULT 0,
  `usu_id` bigint(20) UNSIGNED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`cat_id`, `cat_nome`, `cat_descricao`, `cat_padrao`, `usu_id`) VALUES
(27, 'TESTE', 'TESTE', 0, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido`
--

CREATE TABLE `pedido` (
  `ped_id` bigint(20) UNSIGNED NOT NULL,
  `ped_titulo` varchar(100) NOT NULL,
  `ped_descricao` varchar(300) DEFAULT NULL,
  `usu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ped_data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ped_local_compra` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido_item`
--

CREATE TABLE `pedido_item` (
  `pedi_id` bigint(20) UNSIGNED NOT NULL,
  `pro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ped_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pedi_qtd` float DEFAULT NULL,
  `pedi_valor_unitario` float DEFAULT NULL,
  `pedi_valor_total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `prod_nome` varchar(100) NOT NULL,
  `prod_descricao` varchar(200) DEFAULT NULL,
  `prod_preco` float DEFAULT NULL,
  `unid_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usu_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `sta_id` bigint(20) UNSIGNED NOT NULL,
  `sta_sigla` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sta_nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`sta_id`, `sta_sigla`, `sta_nome`) VALUES
(2, 'AT', 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidade_medida`
--

CREATE TABLE `unidade_medida` (
  `unid_id` bigint(20) UNSIGNED NOT NULL,
  `unid_slug` char(5) DEFAULT NULL,
  `unid_nome` varchar(50) DEFAULT NULL,
  `usu_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usu_id` bigint(20) UNSIGNED NOT NULL,
  `usu_nome` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usu_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usu_password` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usu_reset_token` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sta_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usut_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`usu_id`, `usu_nome`, `usu_email`, `usu_password`, `usu_reset_token`, `sta_id`, `usut_id`) VALUES
(3, 'Eduardo', 'wolfxweb@gmail.com', '123456', '0020', 2, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_tipo`
--

CREATE TABLE `usuario_tipo` (
  `usut_id` bigint(20) UNSIGNED NOT NULL,
  `usut_nome` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usut_sigla` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuario_tipo`
--

INSERT INTO `usuario_tipo` (`usut_id`, `usut_nome`, `usut_sigla`) VALUES
(2, 'Adminstrador', 'ADM'),
(3, 'Cliente', 'CL');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `fk_categorias_usuarios` (`usu_id`);

--
-- Índices para tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`ped_id`),
  ADD KEY `fk_pedido_usuarios` (`usu_id`);

--
-- Índices para tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD PRIMARY KEY (`pedi_id`),
  ADD KEY `fk_pedido_item_pedido` (`pro_id`),
  ADD KEY `fk_pedido_item_produto` (`ped_id`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`pro_id`),
  ADD KEY `fk_produto_unidade_medida` (`unid_id`),
  ADD KEY `fk_produto_categorias` (`cat_id`),
  ADD KEY `fk_produto_usuarios` (`usu_id`);

--
-- Índices para tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`sta_id`);

--
-- Índices para tabela `unidade_medida`
--
ALTER TABLE `unidade_medida`
  ADD PRIMARY KEY (`unid_id`),
  ADD KEY `fk_unidade_medida_usuarios` (`usu_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usu_id`),
  ADD KEY `fk_usuarios_usuario_tipo` (`usut_id`),
  ADD KEY `fk_usuarios_status` (`sta_id`);

--
-- Índices para tabela `usuario_tipo`
--
ALTER TABLE `usuario_tipo`
  ADD PRIMARY KEY (`usut_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `cat_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `ped_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  MODIFY `pedi_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `pro_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `sta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `unidade_medida`
--
ALTER TABLE `unidade_medida`
  MODIFY `unid_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usu_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuario_tipo`
--
ALTER TABLE `usuario_tipo`
  MODIFY `usut_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `fk_categorias_usuarios` FOREIGN KEY (`usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_usuarios` FOREIGN KEY (`usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD CONSTRAINT `fk_pedido_item_pedido` FOREIGN KEY (`pro_id`) REFERENCES `produto` (`pro_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pedido_item_produto` FOREIGN KEY (`ped_id`) REFERENCES `pedido` (`ped_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `fk_produto_categorias` FOREIGN KEY (`cat_id`) REFERENCES `categorias` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_unidade_medida` FOREIGN KEY (`unid_id`) REFERENCES `unidade_medida` (`unid_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produto_usuarios` FOREIGN KEY (`usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `unidade_medida`
--
ALTER TABLE `unidade_medida`
  ADD CONSTRAINT `fk_unidade_medida_usuarios` FOREIGN KEY (`usu_id`) REFERENCES `usuarios` (`usu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_status` FOREIGN KEY (`sta_id`) REFERENCES `status` (`sta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_usuario_tipo` FOREIGN KEY (`usut_id`) REFERENCES `usuario_tipo` (`usut_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;
