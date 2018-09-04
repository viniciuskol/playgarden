-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 06/07/2018 às 01:38
-- Versão do servidor: 5.6.39
-- Versão do PHP: 5.6.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `egocriat_wish`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `categoria` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `category`
--

INSERT INTO `category` (`id`, `categoria`) VALUES
(1, 'Cultura nerd'),
(2, 'Atualidades'),
(3, 'São Carlos'),
(4, 'UFSCar'),
(5, 'Conhecimentos gerais'),
(6, 'Departamento de Computação');

-- --------------------------------------------------------

--
-- Estrutura para tabela `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `prefix` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posfix` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked` tinyint(1) NOT NULL DEFAULT '0',
  `color` int(2) DEFAULT '0',
  `consent` tinyint(1) NOT NULL DEFAULT '0',
  `previous_msgs` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estrutura para tabela `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `question` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `answer1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `answer2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `answer3` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `answer4` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `quiz`
--

INSERT INTO `quiz` (`id`, `cat_id`, `question`, `answer1`, `answer2`, `answer3`, `answer4`) VALUES
(9, 5, 'Qual desses quatro pesos é o mais leve?', '10 gramas', '10 onças', '10 quilos', '10 libras'),
(8, 5, 'Qual é o país que tem uma árvore estampada na bandeira?', 'Líbano', 'Canadá', 'Egito', 'Marrocos'),
(7, 5, 'Qual foi o primeiro presidente do Brasil eleito pelo povo?', 'Prudente de moraes', 'Getúlio vargas', 'Washington luís', 'Tancredo neves'),
(6, 5, 'A baleia está classificada em qual grupo de mamíferos?', 'Cetáceos', 'Felinos', 'Sirênios', 'Carnívoros'),
(10, 5, 'A que letra do nosso alfabeto corresponde a letra grega tau?', 'T', 'H', 'J', 'F'),
(11, 5, 'Quem escreveu \"Dom Quixote\"?', 'Miguel de Cervantes', 'Espinoza', 'Carlos Conte', 'Angel Morita'),
(12, 5, 'Sashimi é um prato originário de qual país?', 'Japão', 'China', 'Índia', 'Indonésia'),
(13, 5, 'De onde é a invenção do chuveiro elétrico?', 'Brasil', 'Inglaterra', 'França', 'Itália'),
(14, 5, 'Qual o livro mais vendido no mundo depois da Bíblia?', 'Dom Quixote', 'O Senhor dos Anéis', 'O Pequeno Príncipe', 'Um Conto de Duas Cidades'),
(15, 5, 'Qual o número mínimo de jogadores numa partida de futebol?', '7', '9', '10', '8'),
(16, 5, 'Os sobrinhos do personagem da Disney \"Pato Donald\" são:', 'Luizinho, Huguinho e Zézinho', 'Juninho, Zézinho e Huguinho', 'Patinho, Patola e Patinhozinho', 'Joãozinho, Zézinho, Huguinho e Paulinho'),
(17, 5, 'O animal já extinto chamado DODÔ, era:', 'Um pássaro', 'Um anfíbio', 'Um réptil', 'Um mamífero'),
(18, 5, 'Qual foi a primeira capital do Brasil?', 'Salvador', 'Palmas', 'Rio de Janeiro', 'Brasília'),
(19, 5, 'O Japão também é conhecido como:', 'Terra do sol nascente', 'A terra dos eletrônicos', 'Terra do sushi', 'País da longevidade'),
(20, 5, 'Quais são os naipes vermelhos do baralho?', 'Ouros e Copas', 'Copas e Paus', 'Paus e Ouros', 'Espadas e Paus'),
(21, 5, 'De quem é a famosa frase \"Penso, logo existo\"?', 'Descartes', 'Platão', 'Francis Bacon', 'Sócrates'),
(22, 5, 'Qual o menor país soberano do mundo?', 'Vaticano', 'Islândia', 'Havaí', 'Austrália'),
(23, 5, 'Qual o maior país do mundo?', 'Rússia', 'Brasil', 'Estados Unidos', 'China'),
(24, 5, 'Qual oceano tem o maior volume de água?', 'Pacífico', 'Atlântico', 'Ártico', 'Índico'),
(25, 5, 'Qual o livro mais vendido no mundo a seguir à Bíblia?', 'Dom Quixote', 'O Senhor dos Anéis', 'O Pequeno Príncipe', 'Harry Potter e o Prisioneiro de Azkaban'),
(26, 5, 'Atualmente, quantos elementos químicos a tabela periódica possui?', '118', '115', '103', '95'),
(27, 5, 'Que imperador pôs fogo em Roma?', 'Nero', 'Trajano', 'Brutus', 'Calígula'),
(28, 5, 'Qual é o ponto mais alto da Terra?', 'Everest', 'Monte Sinai', 'Monte Castelo', 'Mont Blanc'),
(29, 5, 'Quanto tempo demora a lua a dar a volta à terra (período orbital)?', '27 dias e 8 horas', '29 dias e 12 horas', '25 dias', '30 dias'),
(30, 5, 'Quem escreveu o livro Arte da guerra?', 'Sun Tzu', 'Confúcio', 'Gandhi', 'Mao Tsé Tung'),
(31, 5, 'O que a palavra legend significa em português?', 'Lenda', 'Legenda', 'Lendário', 'Legendado'),
(32, 5, 'Qual o número mínimo de jogadores numa partida de futebol?', '7', '11', '10', '6'),
(33, 5, 'Quem pintou o quadro \"La Gioconda\", conhecido como \"Mona Lisa\"?', 'Leonardo da Vinci', 'Salvador Dalí', 'Michelangelo', 'Vincent Van Gogh'),
(34, 5, 'Qual país ultrapassou o Japão e se tornou a segunda economia do mundo?', 'China', 'Alemanha', 'Reino Unido', 'Brasil'),
(35, 5, 'Qual desses países não fica na Ásia?', 'Egito', 'Paquistão', 'Japão', 'Tailândia'),
(36, 5, 'Que país europeu tem como atração a tourada?', 'Espanha', 'Itália', 'França', 'Alemanha'),
(37, 5, 'Qual o grupo em que todas as palavras foram escritas corretamente?', 'Asterisco, beneficente, meteorologia', 'Asterisco, beneficente, metereologia', 'Asterístico, beneficiente, metereologia', 'Asterisco, beneficiente, metereologia'),
(38, 5, 'Como é a conjugação do verbo caber na 1ª pessoa do singular do presente do indicativo?', 'Eu caibo', 'Ele cabe', 'Eu cabo', 'Nenhuma das alternativas'),
(39, 5, 'O \"Super Bowl\" faz parte de qual esporte?', 'Futebol Americano', 'Baseball', 'Basquete', 'Golf'),
(40, 5, 'As pessoas de qual tipo sanguíneo são consideradas doadores universais?', 'Tipo O', 'Tipo A', 'Tipo AB', 'Tipo C'),
(102, 1, 'Anakin Skywalker é também conhecido como:', 'Darth Vader', 'Conde Dookan', 'Darth Maul', 'Boba Fett'),
(103, 1, 'O dia da toalha é comemorado graças ao livro:', 'O guia do mochileiro das galáxias', 'Dune', 'Neuromancer', 'Eram os Deuses Astronautas'),
(101, 1, 'Qual é o nome verdadeiro do Batman?', 'BRUCE WAYNE', 'DEXTER', 'CLARK KENT', 'LEX LUTOR'),
(99, 1, 'Qual o nome do principal vilão dos vingadores no filme \"Guerra Infinita\"?', 'Thanos', 'Loki', 'Ultron', 'Dr. Estranho'),
(100, 1, 'Qual dos heróis a seguir não faz parte da equipe dos vingadores?', 'Lanterna Verde', 'Homem de Ferro', 'Dr. Estranho', 'Gavião Arqueiro'),
(98, 1, 'Qual o nome do filho do personagem Rick Grimes na série The Walking Dead?', 'Carl', 'Max', 'Julian', 'Shane'),
(97, 1, 'Qual o nome da criatura derrotada por Harry Potter no segundo filme? (A câmara secreta)', 'Basilisco', 'Fenix', 'Nagini', 'Elfo'),
(96, 1, 'Qual o nome do principal elfo-doméstico amigo de Harry Potter?', 'Dobby', 'Dob', 'Hobby', 'Moggy'),
(95, 1, 'Quem é a atriz que interpretou a \"Mulher Maravilha\" em 2017?', 'Gal Gadot', 'Robin Wright', 'Emma Watson', 'Emilia Clark'),
(94, 1, 'Qual o autor da série de livros As Crônicas de Gelo e Fogo?', 'George R. R. Martin', 'Stephen King', 'J.K Rowling', 'J. R. R. Tolkien'),
(93, 1, 'Qual personagem de Game of Thrones costuma a ter visões?', 'Brandon Stark', 'Sansa Stark', 'Cersei Lannister', 'Theon Greyjoy'),
(92, 1, 'Qual o nome do lobo do Jon Snow no seriado Game of Thrones?', 'Fantasma', 'Nymeria', 'Verão', 'Vento Cinzento'),
(91, 1, 'Qual das séries não pertence ao mesmo universo das outras?', 'Smallville', 'The Flash', 'Arrow', 'Legends of Tomorrow'),
(89, 1, 'Qual o super-poder do Sr. Fantástico do Quarteto Fantástico?', 'Elasticidade', 'Invisibilidade', 'Super-força', 'Super-velocidade'),
(88, 1, 'Qual desses não é vilão do Homem-Aranha?', 'Parasita', 'Duende verde', 'Consertador', 'Mystério'),
(87, 1, 'Qual desses personagens não é protagonista?', 'Pikachu', 'Jotaro Kujo', 'Naruto', 'Edward Elric'),
(86, 1, 'Quais equipes Deadpool já fez parte?', 'X-men e X-Force', 'Quarteto Fantástico e Inumanos', 'Irmandade e X-force', 'Liga da Justiça e Vingadores'),
(85, 1, 'Qual a comida favorita do Deadpool?', 'Chimichangas', 'Pizza', 'Hot Dog', 'Sangue'),
(84, 1, 'Como Deadpool é mencionado em alguns quadrinhos?', 'Mercenario Tagarela', 'Homem Aranha boca suja', 'Cão Sarnento', 'Sangria'),
(83, 1, 'Como é o nome real do Deadpool?', 'Wade Wilson', 'John Wilson', 'Slade Wilson', 'Ryan Reynolds'),
(82, 1, 'Qual o pokemon conhecido pela sua canção de ninar?', 'Jigglypuff', 'Pichu', 'Kricketune', 'Pikachu'),
(81, 1, 'Como é que o Pikachu evolui?', 'Dando a Pedra do Trovão para ele', 'Ele não evolui', 'No nível 45', 'Dando á ele o ataque \"Choque do Trovão\"'),
(80, 1, 'Quem é a desenvolvedora do jogo \"Pokemon Go\"?', 'Niantic', 'Sega', 'Rovio Mobile', 'Nintendo'),
(79, 1, 'Antes de ir para Hogwarts, onde Harry Potter vivia?', 'Na casa de seus tios, sr. e sra. Dursley', 'Na casa de seus avós paternos', 'Em um orfanato', 'No porão da casa de Lúcio Malfoy'),
(78, 1, 'Qual é o nome completo de Harry Potter na versão original do livro?', 'Harry James Potter', 'Harry Tiago Potter', 'Harry Severo Potter', 'Harry Potter'),
(77, 1, 'Qual das alternativas contêm apenas personagens da Marvel?', 'Feiticeira Escarlate, Hulk, Wolverine e Star-Lord', 'Motoqueiro Fantasma, Homem-aranha, Superman e Flash', 'Homem de Ferro, Pantera-Negra, Flash e Hulk', 'Hulk, Thor, Lobo e Batman'),
(76, 1, 'Qual o nome do Dragão de O Hobbit?', 'Smaug', 'Godzilla', 'Gollum', 'Smeagol'),
(104, 1, 'Qual desses super-heróis não é da Marvel?', 'Mulher-Gavião', 'Wolverine', 'Surfista Prateado', 'Homem-Aranha'),
(105, 1, 'Qual dessas expressões foi criada por Sheldon Cooper?', 'Bazinga!', 'Abracadabra', 'Zazzle', 'Jibber-Jabber'),
(106, 1, 'Qual o nome da máquina do tempo e nave espacial de Doctor Who?', 'Tardis', 'Heaven', 'Time', 'Space'),
(107, 1, 'Qual destes personagens é um Guardião da Galáxia?', 'Gamora', 'Viúva Negra', 'Hulk', 'Homem-Aranha'),
(108, 1, 'Qual nome da nave do filme Star Trek?', 'Enterprise', 'C3PO', 'Millenium Falcon', 'Concorde'),
(109, 1, 'Qual a identidade secreta de Superman?', 'Clark Kent', 'Louis Lane', 'Bruce Wayne', 'Wade Wilson'),
(110, 1, 'Spock, de Star Trek, é metade humano e metade...', 'Vulcano', 'Klingon', 'Borg', 'Marciano'),
(113, 6, 'Qual professor(a) do DC foi destaque num programa de entrevistas recentemente?', 'Helena de Medeiros Caseli', 'Maurício Fernandes Figueiredo', 'Marcela Xavier Ribeiro', 'Orides Morandin Junior'),
(114, 6, 'Qual o nome da praça ao lado do Departamento de Computação?', 'Praça da Ciência', 'Praça dos Quero-Queros', 'Praça da Computação', 'Praça do Observatório'),
(115, 6, 'Quantos laboratórios de ensino (LEs) existem no DC?', '6', '5', '7', '3'),
(116, 6, 'Qual desses departamentos está mais próximo do DC?', 'Departamento de Matemática', 'Departamento de Letras', 'Departamento de Astronomia', 'Departamento da Saúde'),
(117, 6, 'Qual desses não é um laboratório do DC da UFSCar?', 'NILC', 'GSDR', 'LaPES', 'Asgard'),
(118, 6, 'Quais desses não é um cursos de graduação do DC da UFSCar?', 'Análise e Desenvolvimento de Sistemas', 'Bacharelado em Ciência da Computação', 'Engenharia de Computação', 'Sistemas de Informação (UAB-UFSCar)'),
(119, 6, 'Quantas vagas são ofertadas anualmente no curso de Ciência da Computação na UFSCar?', '60', '30', '45', '50'),
(120, 6, 'Quantas vagas são ofertadas anualmente no curso de Engenharia de Computação na UFSCar?', '30', '45', '50', '60'),
(121, 6, 'Desde quando existe o Departamento de Computação na UFSCar?', '1972', '1981', '1933', '2002'),
(122, 6, 'Qual desses não é um laboratório de pesquisa do Departamento de Computação da UFSCar?', 'LABIC', 'MaLL', 'LIA', 'TEAR'),
(123, 6, 'Quando o DC completou 40 anos?', '2012', '2016', '2015', '2018'),
(124, 6, 'Qual das seguintes disciplinas não é ministrada por professores do DC?', 'Teoria das Organizações', 'Banco de Dados', 'Engenharia de Software', 'Revisão Sistemática da Literatura'),
(125, 6, 'Qual o nome da Empresa Junior sediada no DC?', 'CATI Jr', 'CAQui', 'EngeALI', 'EnCaCC'),
(126, 6, 'Qual o nome da semana de computação dos cursos de graduação do DC?', 'SECOMP', 'SEMAC', 'SECCOMP', 'SEMACOMP'),
(127, 6, 'Quantos bebedouros de água existem no DC?', '3', '2', '5', '1'),
(128, 6, 'Qual desses cursos de graduação o DC da UFSCar oferece na modalidade a distância?', 'Sistemas de Informação', 'Engenharia de Software', 'Banco de dados', 'Análise e Desenv. de Sistemas'),
(129, 6, 'Quantas áreas de cozinha existem no DC?', '2', '1', '3', '5'),
(130, 6, 'Onde estão localizadas a maioria das salas dos professores do DC?', 'Andar Superior', 'Andar Inferior', 'Área externa', 'Próximo à churrasqueira'),
(131, 6, 'Qual o nome do auditório do DC?', 'Mauro Biajiz', 'Fernão Stella', 'Florestan Fernandes', 'Bento Prado'),
(133, 6, 'Quais desses é um cursos de especialização oferecido pelo DC?', 'Desenv. de Software para Web', 'Matemática Computacional', 'Segurança da Informação', 'Física Computacional'),
(134, 6, 'Em qual área da UFSCar se localiza o DC?', 'Área Norte', 'Área Sul', 'Região do Lago', 'IFSP'),
(135, 6, 'Quais desses objetos fica exposto numa parede do DC?', 'Régua de cálculo', 'Computador ENIAC', 'Máquina de calcular eletromecânica', 'Leitor de cartão perfurado'),
(136, 6, 'Qual desses eventos é realizado anualmente no Departamento de Computação da UFSCar?', 'É Dia de Java', 'Linux Weekend', 'Science Jam', 'Local Hack Day'),
(137, 3, 'Qual o ano de fundação da cidade de São Carlos?', '1857', '1863', '1914', '1911'),
(138, 3, 'Quando é o aniversário da cidade de São Carlos?', '04/11', '05/06', '13/08', '25/01'),
(139, 3, 'Qual o número mais próximo de cidadãos em São Carlos?', '246.000', '300.000', '130.000', '190.000'),
(140, 3, 'Quem é o atual prefeito da cidade?', 'Airton Garcia (PSB)', 'Paulo Altomani (PSDB)', 'Netto Donato (PMDB)', 'Dante Peixoto (PSOL)'),
(141, 3, 'Quem é o atual vice-prefeito da cidade?', 'Giuliano Cardinalli (PSD)', 'Dante Peixoto (PSOL)', 'Lineu Navarro (PT)', 'Airton Garcia (PSB)'),
(142, 3, 'Qual o número de vereadores em São Carlos?', '21', '25', '18', '14'),
(143, 3, 'Qual a taxa de doutores por habitante em São Carlos?', '1 doutor para cada 135 habitantes', '1 doutor para cada 229 habitantes', '1 doutor para cada 93 habitantes', '1 doutor para cada 160 habitantes'),
(144, 3, 'Qual desses não é um nome pelo qual a cidade de Sâo Carlos é conhecida?', 'Cidade dos pombos', 'Capital da Tecnologia', 'Sanca', 'Cidade do Clima'),
(145, 3, 'Qual destes não é um evento tradicional de São Carlos?', 'Rodeio São Carlos Bull', 'TUSCA', 'Festa do Clima', 'Matsuri'),
(146, 3, 'Qual foi o primeiro nome oficial da cidade de São Carlos?', 'São Carlos do Pinhal', 'San Carlos', 'São Bento de Araraquara', 'Ibaté'),
(147, 3, 'Qual o principal rio presente na cidade de São Carlos?', 'Monjolinho', 'Negro', 'Amazonas', 'São Francisco'),
(148, 3, 'Qual o verdadeiro nome do \"Conde do Pinhal\"?', 'Antônio Carlos de Arruda Botelho', 'Antônio Manuel da Costa', 'José Antônio Carvalhaes', 'Armando José dos Santos Arruda'),
(149, 3, 'Por que São Carlos é conhecida como Athenas Paulista?', 'Por ser um polo educacional', 'Por suas pesquisas em história', 'Pela  arquitetura da cidade', 'Devido ao culto a deuses gregos'),
(150, 3, 'Qual é a empresa atualmente responsável pelo transporte público coletivo (ônibus) em São Carlos?', 'Suzantur', 'Athenas Paulista', 'BusBrasil', 'Itamaraty'),
(151, 3, 'Qual dessas empresas não possui filial em São Carlos?', 'Microsoft', 'Volkwagen', 'Eletrolux', 'Faber Castell'),
(152, 3, 'Qual dessas personalidades nasceu em São Carlos?', 'Ronald Golias', 'Chacrinha', 'Sílvio Santos', 'Pelé'),
(153, 3, 'Qual dessas cidades não é vizinha de São Carlos/SP?', 'Campinas/SP', 'Ibaté/SP', 'Ribeirão Bonito/SP', 'Itirapina/SP'),
(154, 3, 'Qual desses não é um bairro de São Carlos/SP?', 'Jardim Ricoletti', 'Cidade Jardim', 'Parque Fehr', 'Parque Sabará'),
(155, 4, 'Quantos campus a UFSCar possui atualmente?', '4', '5', '1', '2'),
(156, 4, 'Em que ano foi fundada a UFSCar', '1968', '1950', '1920', '1998'),
(157, 4, 'Qual o nome do lago do campus de São Carlos da UFSCar?', 'Monjolinho', 'Tietê', 'Amazonas', 'Lagoa do Sino'),
(158, 4, 'Qual o nome do último campus inaugurado da UFSCar?', 'Lagoa do Sino', 'Araras', 'Sorocaba', 'Araraquara'),
(159, 4, 'Qual desses cursos de graduação não é oferecido pela UFSCar', 'Publicidade e Propaganda', 'Educação Especial', 'Música', 'Medicina'),
(160, 4, 'Qual o nome do(a) atual reitor(a) da UFSCar?', 'Wanda Hoffmann', 'Targino de Araújo Filho', 'Munir Rachid', 'Heitor Gurgulino de Souza'),
(161, 4, 'Qual desses animais podem ser vistos próximo ao lago no campus de São Carlos da UFSCar?', 'Ganso', 'Dinossauro', 'Urso', 'Onça pintada'),
(162, 4, 'Qual desses departamentos não é localizado na área norte da UFSCar - São Carlos?', 'Departamento de Educação', 'Departamento de Computação', 'Departamento de Matemática', 'Departamento de Medicina'),
(163, 4, 'Onde fica o Restaurante Universitário da UFSCar - São Carlos?', 'Próximo ao lago', 'Área Sul', 'Área Norte', 'Próximo à USE'),
(164, 4, 'Onde está localizada a área de cerrado do campus de São Carlos da UFSCar?', 'Extremo Norte', 'Área Sul', 'Próximo ao lago', 'Próximo da Biblioteca Comunitária'),
(165, 4, 'Quantos cursos de graduação a distância são oferecidos pela UAB-UFSCar?', '5', '10', '2', '8'),
(168, 2, 'Qual a maior fonte geradora de energia elétrica no Brasil?', 'Usinas hidrelétricas', 'Usinas termonucleares', 'Usinas de carvão mineral', 'Usinas de energia eólica'),
(169, 2, 'O que não podemos afirmar sobre o Biodiesel?', 'É um combustível não renovável', 'Polui menos que os derivados de petróleo', 'Combustível renovável, de origem vegetal', 'A Mamona é uma fonte desse combustível'),
(170, 2, 'Quando teve fim a Guerra do Iraque?', 'Dezembro de 2011', 'Setembro de 2013', 'Março de 2016', 'Janeiro de 2010'),
(171, 2, 'Qual o país com o menor IDH da América do Sul?', 'Guiana', 'Chile', 'Bolívia', 'Brasil'),
(173, 2, 'Qual capital teve o título de \"Cidade Modelo\" durante vários anos consecutivos?', 'Curitiba', 'Cuiabá', 'Andradina', 'São José dos Campos'),
(174, 2, 'Que tipo de festa é muito comum no mês de Junho?', 'Festa Junina', 'Carnaval', 'Folia de Reis', 'Semana Santa'),
(175, 2, 'Qual desses países sofreu com a erupção de um vulcão recentemente?', 'Guatemala', 'Brasil', 'Venezuela', 'Paraguai'),
(176, 2, 'Que classe de trabalhadores fez uma grande greve recentemente no Brasil?', 'Caminhoneiros', 'Médicos', 'Bombeiros', 'Juízes'),
(177, 2, 'Qual é o país sede da Copa do Mundo de Futebol de 2018?', 'Rússia', 'Brasil', 'Estados Unidos, Canadá e México', 'Catar'),
(178, 2, 'Como ficou conhecida a saída do Reino Unido da União Européia?', 'Brexit', 'British out', 'New UK', 'UK Break'),
(179, 2, 'Em 2018, teremos eleições para eleger:', 'Presidente, governadores, deputados e senadores', 'Prefeitos e vereadores', 'Presidente e governadores', 'Prefeitos e governadores'),
(180, 2, 'Como é chamada a distribuição de boatos e  notícias falsas?', 'Fake news', 'Spam', 'Memes', 'Web 4.0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ranking`
--

CREATE TABLE `ranking` (
  `id` int(11) NOT NULL,
  `userid` bigint(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `startedat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `duration` int(11) NOT NULL,
  `questions` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `categories` varchar(2000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `corrects` int(11) NOT NULL,
  `consent` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `sensor_counting`
--

CREATE TABLE `sensor_counting` (
  `id` int(11) NOT NULL,
  `sensor1` tinyint(1) NOT NULL,
  `sensor2` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `source` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sensor_error`
--

CREATE TABLE `sensor_error` (
  `id` int(11) NOT NULL DEFAULT '1',
  `error` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Fazendo dump de dados para tabela `sensor_error`
--

INSERT INTO `sensor_error` (`id`, `error`, `date`) VALUES
(1, 0, '2018-06-29 20:19:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sensor_status`
--

CREATE TABLE `sensor_status` (
  `id` int(11) NOT NULL DEFAULT '1',
  `sensor1` tinyint(1) NOT NULL,
  `sensor2` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `sensor_status`
--

INSERT INTO `sensor_status` (`id`, `sensor1`, `sensor2`, `date`, `blocked`) VALUES
(1, 0, 0, '2018-06-29 19:22:46', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `watering`
--

CREATE TABLE `watering` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estrutura para tabela `watering_status`
--

CREATE TABLE `watering_status` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `watering_status`
--

INSERT INTO `watering_status` (`id`, `status`, `date`) VALUES
(1, 0, '2018-06-30 15:22:09');

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sensor_counting`
--
ALTER TABLE `sensor_counting`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sensor_error`
--
ALTER TABLE `sensor_error`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sensor_status`
--
ALTER TABLE `sensor_status`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `watering`
--
ALTER TABLE `watering`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `watering_status`
--
ALTER TABLE `watering_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT de tabela `ranking`
--
ALTER TABLE `ranking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sensor_counting`
--
ALTER TABLE `sensor_counting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `watering`
--
ALTER TABLE `watering`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `watering_status`
--
ALTER TABLE `watering_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
