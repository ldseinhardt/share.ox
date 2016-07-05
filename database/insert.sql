INSERT INTO `levels` (`id`, `title`) VALUES
  (1, 'Estudante'),
  (2, 'Administrador');

INSERT INTO `countries` (`id`, `name`, `uf`) VALUES
  (1, 'Brasil', 'BR');

INSERT INTO `states` (`id`, `country_id`, `name`, `uf`) VALUES
  ( 1, 1, 'Acre', 'AC'),
  ( 2, 1, 'Alagoas', 'AL'),
  ( 3, 1, 'Amapá', 'AP'),
  ( 4, 1, 'Amazonas', 'AM'),
  ( 5, 1, 'Bahia', 'BA'),
  ( 6, 1, 'Ceará', 'CE'),
  ( 7, 1, 'Distrito Federal', 'DF'),
  ( 8, 1, 'Espírito Santo', 'ES'),
  ( 9, 1, 'Goiás', 'GO'),
  (10, 1, 'Maranhão', 'MA'),
  (11, 1, 'Mato Grosso', 'MT'),
  (12, 1, 'Mato Grosso do Sul', 'MS'),
  (13, 1, 'Minas Gerais', 'MG'),
  (14, 1, 'Pará', 'PA'),
  (15, 1, 'Paraíba', 'PB'),
  (16, 1, 'Paraná', 'PR'),
  (17, 1, 'Pernambuco', 'PE'),
  (18, 1, 'Piauí', 'PI'),
  (19, 1, 'Rio de Janeiro', 'RJ'),
  (20, 1, 'Rio Grande do Norte', 'RN'),
  (21, 1, 'Rio Grande do Sul', 'RS'),
  (22, 1, 'Rondônia', 'RO'),
  (23, 1, 'Roraima', 'RR'),
  (24, 1, 'Santa Catarina', 'SC'),
  (25, 1, 'São Paulo', 'SP'),
  (26, 1, 'Sergipe', 'SE'),
  (27, 1, 'Tocantins', 'TO');

INSERT INTO `cities` (`id`, `state_id`, `name`) VALUES
  (1, 21, 'Pelotas'),
  (2, 21, 'Porto Alegre'),
  (3, 21, 'Rio Grande');

INSERT INTO `institutions` (`id`, `name`, `shortname`) VALUES
  (1, 'Universidade Federal de Pelotas', 'UFPel'),
  (2, 'Universidade Católica de Pelotas', 'UCPel'),
  (3, 'Campus Pelotas do Instituto Federal Sul-rio-grandense', 'IFSul');

INSERT INTO `courses` (`id`, `name`) VALUES
  (1, 'Ciência da Computação'),
  (2, 'Design Gráfico'),
  (3, 'Engenharia de Computação');

INSERT INTO `types` (`id`, `type`) VALUES
  (1, 'cópia'),
  (2, 'cópias de livros'),
  (3, 'resumo/anotação');

INSERT INTO `categories` (`id`, `category`) VALUES
  (1, 'grifado'),
  (2, 'com anotações'),
  (3, 'dobras/amassado/orelhas');

INSERT INTO `status` (`id`, `status`) VALUES
  (1, 'novo'),
  (2, 'usado');
