--
-- Dumping data for table `language`
--

INSERT INTO `city` (`id`, `name`) VALUES
(1, 'Selecione a cidade'),
(2, 'Açores'),
(3, 'Aveiro'),
(4, 'Beja'),
(5, 'Braga'),
(6, 'Bragança'),
(7, 'Castelo Branco'),
(8, 'Coimbra'),
(9, 'Évora'),
(10, 'Faro'),
(11, 'Guarda'),
(12, 'Leiria'),
(13, 'Lisboa'),
(14, 'Madeira'),
(15, 'Portalegre'),
(16, 'Porto'),
(17, 'Santarém'),
(18, 'Setúbal'),
(19, 'Viana do Castelo'),
(20, 'Vila Real'),
(21, 'Viseu');

--
-- Dumping data for table `role`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Selecione a categoria'),
(2, 'Mulheres procurando Homens'),
(3, 'Mulheres procurando Mulheres'),
(4, 'Homens procurando Mulheres'),
(5, '	Homens procurando Homens');
--
-- Dumping data for table `roles_parents`
--
--SET FOREIGN_KEY_CHECKS = 0;
--TRUNCATE
--SET FOREIGN_KEY_CHECKS = 1; -- enable checking