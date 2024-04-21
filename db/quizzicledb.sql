-- Create database
CREATE DATABASE quizzicledb;

-- Use the quiz database
USE quizzicledb;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2024 at 09:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizzicledb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_src` text NOT NULL,
  `img_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `img_src`, `img_alt`, `description`) VALUES
(1, 'History', 'https://i.imgur.com/ze6EtxI.jpeg', 'history picture', 'History is fun except when it\'s not'),
(2, 'Geography', 'https://i.imgur.com/w8kGOWh.jpeg', 'geography picture', 'Geography is fun except when it\'s not'),
(3, 'Physics', 'https://i.imgur.com/kYdqfR4.jpeg', 'physics picture', 'Physics is the study of matter, energy, and the fundamental forces of nature.'),
(4, 'Mathematics', 'https://i.imgur.com/fBQ8u1F.jpeg', 'mathematics picture', 'Mathematics is the language of the universe, and it\'s all around us.'),
(5, 'Chemistry', 'https://i.imgur.com/RLFRVTx.jpeg', 'chemistry picture', 'Chemistry explores the properties, composition, and behavior of matter.'),
(6, 'Language', 'https://i.imgur.com/FV8V7CE.jpeg', 'language picture', 'Language is the intricate tapestry of communication, a dynamic system of symbols and sounds that facilitates the expression of thoughts, ideas, and emotions, fostering connection and understanding among individuals.'),
(7, 'Literature', 'https://i.imgur.com/nDUrY5e.jpeg', 'literature', 'Literature is the artful exploration of human experience, emotions, and imagination through the written word, transcending time and culture to illuminate the complexities of the human condition.');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `feedback_type` enum('problem','request') NOT NULL DEFAULT 'problem',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `description`, `feedback_type`, `timestamp`) VALUES
(1, 5, 'I don\'t like the design of the page it is simply too good to be true. Please make it uglier. I am the owner of BuzzFeed and my website is losing popularity because of your web-based application. Please stop it I need people to play quizzes on my website so I can make money of advertisments.', 'problem', '2024-03-21 15:29:31'),
(2, 5, 'Please stop it😭😭😭😭', 'request', '2024-03-21 15:50:37'),
(3, 1, 'These quizzes are too good to be true WOW POGGERS', 'problem', '2024-03-21 16:09:44'),
(5, 7, 'Mnogo hubav sajt', 'request', '2024-03-22 06:35:30'),
(6, 8, 'Mnogo hubavo web bazirano prilozhenie', 'request', '2024-03-22 07:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `img_src` text NOT NULL,
  `img_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_answer_index` int(11) NOT NULL CHECK (`correct_answer_index` between 1 and 4),
  `quiz_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_text`, `img_src`, `img_alt`, `answer1`, `answer2`, `answer3`, `answer4`, `correct_answer_index`, `quiz_id`) VALUES
(1, 'Кой е основателят на Първото българско царство?', 'https://bulstack.com/wp-content/gallery/famous-bulgarians/asparuh-for-web.jpg', 'Founding ruler', 'Крум', 'Аспарух', 'Тервел', 'Кубрат', 2, 1),
(2, 'През коя година е официално създадено Първото българско царство?', 'https://cdn4.focus.bg/fakti/photos/big/121/9-avgust-681-g-rajda-se-balgaria-1.jpg', 'Establishment year', '681', '632', '705', '743', 1, 1),
(3, 'Кой град е бил столица на Първото българско царство?', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/The_Great_Basilica_Klearchos_2.jpg/1200px-The_Great_Basilica_Klearchos_2.jpg', 'Capital city', 'Велики Преслав', 'София', 'Плиска', 'Велико Търново', 3, 1),
(4, 'Кой е владетелят, известен с приемането на християнството като официална религия на Първото българско царство?', 'https://static.bnr.bg/gallery/cr/medium/ceccb8670c4bd91b047c42482384d5fa.jpg', 'Christianization ruler', 'Крум', 'Аспарух', 'Борис 1', 'Симеон 1', 3, 1),
(5, 'When did the Third Bulgarian State officially begin?', 'https://static.bnr.bg/gallery/cr/medium/c3f2175e6c9f7da38c97747cb5938e77.jpg', 'creation date image', '1870', '1878', '1885', '1908', 2, 2),
(6, 'Which treaty marked the formal establishment of the Third Bulgarian State?', 'https://about-history.com/wp-content/uploads/2019/01/Congress-of-Berlin-and-the-Berlin-Treaty-1878.jpg', 'Treaty of berlin', 'Treaty of Berlin', 'Treaty of San Stefano', 'Treaty of Bucharest', 'Treaty of Constantinople', 1, 2),
(7, 'Who was the first ruler (Knyaz) of the Third Bulgarian State?', 'https://upload.wikimedia.org/wikipedia/commons/d/d3/Alexander_I_of_Bulgaria_by_Dimitar_Karastoyanov.jpg', 'image of the first third bulgarian country ruler', 'Tsar Ferdinand I', 'Tsar Boris III', 'Alexander of Battenberg', 'Alexander Malinov', 3, 2),
(8, 'What was the capital city of Bulgaria during the Third Bulgarian State?', 'https://www.sofia.bg/documents/58151/68724/%D0%9B%D1%8A%D0%B2%D0%BE%D0%B2-%D0%9C%D0%BE%D1%81%D1%82-%D0%BD%D1%8F%D0%BA%D0%BE%D0%B3%D0%B0-pav.jpg/5139b01b-42dd-4f10-acd1-a8beac8a871d?t=1506954051515', 'image of the capital of the third bulgarian state', 'Sofia', 'Plovdiv', 'Varna', 'Burgas', 1, 2),
(9, 'Which war led to significant territorial gains for Bulgaria during the Third Bulgarian State?', 'https://www.historycrunch.com/uploads/4/1/1/6/41169839/balkan-league_orig.jpg', 'image of the war that led to teritorial gains for Bulgaria', 'First Balkan War', 'Second Balkan War', 'World War I', 'World War II', 1, 2),
(10, 'Who served as Bulgaria\'s Prime Minister during the critical period of World War I?', 'https://upload.wikimedia.org/wikipedia/commons/a/a4/BASA-313K-1-2536-7-Vasil_Radoslavov.jpg', 'image of the primer minister of bulgarian during the first world war who is bort in Lovech on 27 of July 1854', 'Vasil Radoslavov', 'Alexander Stamboliyski', 'Aleksandar Tsankov', 'Nikola Mushanov', 1, 2),
(11, 'What event marked the first national crisis in the Third Bulgarian State?', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Participants_in_the_Bucharest_Peace_Treaty_negotiations%2C_1913.jpg/250px-Participants_in_the_Bucharest_Peace_Treaty_negotiations%2C_1913.jpg', 'End of Third Bulgarian State', 'Signing of the Treaty of Bucharest', 'Abdication of Tsar Boris III', 'Communist uprising', 'Invasion by the Soviet Union', 1, 2),
(12, 'Which country occupied Bulgaria following the end of World War II, leading to the establishment of a communist government?', 'https://sofiaglobe.com/wp-content/uploads/2016/09/September-9-Bulgaria-communist-600x338.jpg', 'image of the occupation', 'United States', 'Soviet Union', 'United Kingdom', 'France', 2, 2),
(13, 'What was the name of the communist leader who rose to power in Bulgaria after World War II?', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQykQVuwGcaWiQYyr99dgHezwUkT3-98UuUK0h5S7alMA&s', 'first bulgarian communist leader', 'Todor Zhivkov', 'Georgi Dimitrov', 'Petar Gabrovski', 'Valko Chervenkov', 2, 2),
(14, 'When was the monarchy abolished in Bulgaria, officially ending the Third Bulgarian State?', 'https://static.bnr.bg/gallery/f2/f27a1d1ede4879ee51d5f91b50c475de.jpg', 'fall of the monarchy in bulgaria referendum', '1943', '1946', '1952', '1965', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `question_results`
--

CREATE TABLE `question_results` (
  `id` int(11) NOT NULL,
  `quiz_result_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `user_answer_index` int(11) NOT NULL CHECK (`user_answer_index` between 0 and 4),
  `is_correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_results`
--

INSERT INTO `question_results` (`id`, `quiz_result_id`, `question_id`, `user_answer_index`, `is_correct`) VALUES
(1, 1, 1, 2, 1),
(2, 1, 2, 1, 1),
(3, 1, 3, 3, 1),
(4, 1, 4, 3, 1),
(5, 2, 5, 2, 1),
(6, 2, 6, 1, 1),
(7, 2, 7, 3, 1),
(8, 2, 8, 1, 1),
(9, 2, 9, 1, 1),
(10, 2, 10, 1, 1),
(11, 2, 11, 1, 1),
(12, 2, 12, 2, 1),
(13, 2, 13, 2, 1),
(14, 2, 14, 2, 1),
(15, 3, 1, 2, 1),
(16, 3, 2, 1, 1),
(17, 3, 3, 3, 1),
(18, 3, 4, 3, 1),
(19, 4, 5, 2, 1),
(20, 4, 6, 1, 1),
(21, 4, 7, 2, 0),
(22, 4, 8, 1, 1),
(23, 4, 9, 4, 0),
(24, 4, 10, 3, 0),
(25, 4, 11, 3, 0),
(26, 4, 12, 4, 0),
(27, 4, 13, 3, 0),
(28, 4, 14, 2, 1),
(29, 5, 5, 2, 1),
(30, 5, 6, 1, 1),
(31, 5, 7, 3, 1),
(32, 5, 8, 1, 1),
(33, 5, 9, 1, 1),
(34, 5, 10, 3, 0),
(35, 5, 11, 2, 0),
(36, 5, 12, 2, 1),
(37, 5, 13, 2, 1),
(38, 5, 14, 2, 1),
(39, 6, 1, 2, 1),
(40, 6, 2, 1, 1),
(41, 6, 3, 3, 1),
(42, 6, 4, 3, 1),
(43, 7, 1, 2, 1),
(44, 7, 2, 1, 1),
(45, 7, 3, 3, 1),
(46, 7, 4, 3, 1),
(47, 8, 1, 2, 1),
(48, 8, 2, 1, 1),
(49, 8, 3, 1, 0),
(50, 8, 4, 3, 1),
(51, 9, 5, 2, 1),
(52, 9, 6, 1, 1),
(53, 9, 7, 3, 1),
(54, 9, 8, 1, 1),
(55, 9, 9, 1, 1),
(56, 9, 10, 3, 0),
(57, 9, 11, 1, 1),
(58, 9, 12, 2, 1),
(59, 9, 13, 2, 1),
(60, 9, 14, 2, 1),
(61, 10, 1, 2, 1),
(62, 10, 2, 1, 1),
(63, 10, 3, 3, 1),
(64, 10, 4, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `quiz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_src` text NOT NULL,
  `img_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `quiz`, `img_src`, `img_alt`, `description`, `subcategory_id`) VALUES
(1, 'Първо Българско Царство(681 - 1018)', 'https://i.imgur.com/opbH35h.jpeg', 'First Bulgarian Empire Image', 'Впуснете се в пътешествие във времето с нашата викторина за Първото българско царство, проверявайки знанията си за неговото създаване при хан Аспарух, ключовите моменти при цар Симеон I и културните промени, оформили тази древна балканска сила.', 1),
(2, 'Трета Българска Държава (1878 - 1946)', 'https://i.imgur.com/x422mWG.jpeg', 'Third Bulgarian State 2 Image', 'Потопете се в историческите обрати на Третата българска държава в нашата викторина, изследвайки нейния път към независимост през 1878 г., навигирайки през предизвикателствата на две световни войни и разгадавайки сложността на нейната комунистическа епоха, преди да прегърне демокрацията в края на 20-ти век.', 1),
(3, '20 historical events', 'https://i.imgur.com/YzNQzL6.jpeg', '20 historical events Image', 'Do you know which are these 20 historical events!', 2),
(4, 'Historical figures', 'https://i.imgur.com/AWZcPSD.jpeg', 'Historical figure Image', 'Can you unravel the mysteries behind these iconic figures from history?', 2),
(5, 'Do you know this country flags?', 'https://i.imgur.com/ebMQl7G.jpeg', 'flag quiz image', 'Test your knowledge of World flags.', 3),
(6, 'What is the capital of this country?', 'https://i.imgur.com/KYwKo4s.jpeg', 'capitals quiz image', 'Another quiz to challenge your knowledge of general knowledge geography.', 3),
(7, 'Do you know the GDP of this country?', 'https://i.imgur.com/1OdqnW3.jpeg', 'Human Geography Quiz 1 Image', 'Test your understanding of the world economics.', 4),
(8, 'In which country is this located?', 'https://i.imgur.com/Tlj5tyr.jpeg', 'Human Geography Quiz 2 Image', 'Another quiz to explore the dynamic interaction between humans and their surroundings.', 4),
(9, 'Newtonian Mechanics', 'https://i.imgur.com/WtwOR4w.jpeg', 'Classical Mechanics Quiz 1 Image', 'Test your knowledge of classical mechanics principles.', 5),
(10, 'Hamil- tonian Mechanics', 'https://i.imgur.com/mkdtc0t.jpeg', 'Classical Mechanics Quiz 2 Image', 'Another quiz to challenge your understanding of classical mechanics.', 5),
(11, 'History of Quantum Mechanics', 'https://i.imgur.com/c1MLsmW.jpeg', 'Quantum Physics Quiz 1 Image', 'Dive into the world of quantum phenomena with this quiz!', 6),
(12, 'Laws of Quantum Mechanics', 'https://i.imgur.com/V5vJ1hO.jpeg', 'Quantum Physics Quiz 2 Image', 'Another quiz to explore the fascinating aspects of quantum physics.', 6),
(13, 'Liniar Algebra', 'https://i.imgur.com/WDhw2Z3.jpeg', 'Algebra Quiz 1 Image', 'Test your understanding of algebraic concepts.', 7),
(14, 'Calculus', 'https://i.imgur.com/Clad2KW.jpeg', 'Algebra Quiz 2 Image', 'Another quiz to challenge your knowledge of algebra.', 7),
(15, 'Planimetrics', 'https://i.imgur.com/Lb1hiFG.jpeg', 'Geometry Quiz 1 Image', 'Explore geometric principles with this quiz!', 8),
(16, 'Polymetry', 'https://i.imgur.com/YCPvHbE.jpeg', 'Geometry Quiz 2 Image', 'Another quiz to test your knowledge of geometry.', 8),
(17, 'Stereochemistry', 'https://i.imgur.com/tZbPKPR.jpeg', 'Organic Chemistry Quiz 1 Image', 'Test your knowledge of organic chemistry.', 9),
(18, 'Organic synthesis', 'https://i.imgur.com/k0sYYNg.jpeg', 'Organic Chemistry Quiz 2 Image', 'Another quiz to explore the world of organic compounds.', 9),
(19, 'Nuclear chemistry', 'https://i.imgur.com/kTQbAcC.jpeg', 'Inorganic Chemistry Quiz 1 Image', 'Test your understanding of inorganic compounds.', 10),
(20, 'Organometallic chemistry', 'https://i.imgur.com/UxbhTdm.jpeg', 'Inorganic Chemistry Quiz 2 Image', 'Another quiz to delve into the properties and behavior of inorganic substances.', 10),
(21, 'Punctoation', 'https://i.imgur.com/P4cCGLF.jpeg', 'English Language Quiz 1 Image', 'Test your understanding of the English language.', 11),
(22, 'Gramatics', 'https://i.imgur.com/fqph2OX.jpeg', 'English Language Quiz 2 Image', 'Another quiz to explore the intricacies of English communication.', 11),
(23, 'Which language is this?', 'https://i.imgur.com/2DzmloD.jpeg', 'Foreign Languages Quiz 1 Image', 'Test your knowledge of languages from around the world.', 12),
(24, 'What is the official language of this country?', 'https://i.imgur.com/mwFcVp4.jpeg', 'Foreign Languages Quiz 2 Image', 'Another quiz to challenge your understanding of global languages.', 12),
(25, 'Bulgarian Poetry', 'https://i.imgur.com/K2Yr6y7.jpeg', 'Poetry Quiz 1 Image', 'Test your knowledge of poetic expressions.', 13),
(26, 'World Poetry', 'https://i.imgur.com/HWvU2IA.jpeg', 'Poetry Quiz 2 Image', 'Another quiz to explore the artful use of language in poetry.', 13),
(27, 'Bulgarian Prose', 'https://i.imgur.com/p5dWESn.jpeg', 'Prose Fiction Quiz 1 Image', 'Explore the world of fictional narratives in prose form with this quiz!', 14),
(28, 'World Prose', 'https://i.imgur.com/SPMB9Ra.jpeg', 'Prose Fiction Quiz 2 Image', 'Another quiz to test your understanding of prose fiction.', 14);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL CHECK (`score` between 0 and 100),
  `time_to_complete` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `quiz_id`, `score`, `time_to_complete`, `timestamp`) VALUES
(1, 1, 1, 100.00, 10, '2024-03-22 06:21:13'),
(2, 1, 2, 100.00, 314, '2024-03-22 06:28:00'),
(3, 8, 1, 100.00, 49, '2024-03-22 07:38:30'),
(4, 8, 2, 40.00, 21, '2024-03-22 07:41:35'),
(5, 8, 2, 80.00, 188, '2024-03-22 07:57:30'),
(6, 8, 1, 100.00, 60, '2024-03-22 07:59:36'),
(7, 1, 1, 100.00, 10, '2024-03-28 15:38:24'),
(8, 1, 1, 75.00, 6, '2024-03-28 15:39:23'),
(9, 10, 2, 90.00, 193, '2024-04-17 16:47:55'),
(10, 10, 1, 100.00, 63, '2024-04-17 16:49:54');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `subcategory` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_src` text NOT NULL,
  `img_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `subcategory`, `img_src`, `img_alt`, `description`, `category_id`) VALUES
(1, 'Българаска история', 'https://i.imgur.com/NwiX1Tw.jpeg', 'bulgarian history picture', 'Разгледайте богатата история на България.', 1),
(2, 'World History', 'https://i.imgur.com/ytDvTFJ.jpeg', 'world history picture', 'Discover the fascinating history of our world.', 1),
(3, 'Physical Geography', 'https://i.imgur.com/vH9fOBv.jpeg', 'physical geography picture', 'Study the natural features and processes of the Earth.', 2),
(4, 'Human Geography', 'https://i.imgur.com/PIoK7xE.jpeg', 'human geography picture', 'Explore the relationship between humans and their environment.', 2),
(5, 'Classical Mechanics', 'https://i.imgur.com/9qu7j9n.jpeg', 'classical mechanics picture', 'Learn about the motion of objects under the influence of forces.', 3),
(6, 'Quantum Physics', 'https://i.imgur.com/23NkbAF.jpeg', 'quantum physics picture', 'Delve into the fascinating world of quantum phenomena.', 3),
(7, 'Algebra', 'https://i.imgur.com/HzCam2o.jpeg', 'algebra picture', 'Explore the rules and relationships of mathematical symbols.', 4),
(8, 'Geometry', 'https://i.imgur.com/MpusIe9.jpeg', 'geometry picture', 'Study the properties and relations of points, lines, surfaces, and solids.', 4),
(9, 'Organic Chemistry', 'https://i.imgur.com/zJyvdRn.jpeg', 'organic chemistry picture', 'Examine the structure, properties, and reactions of organic compounds.', 5),
(10, 'Inorganic Chemistry', 'https://i.imgur.com/OXYPx4t.jpeg', 'inorganic chemistry picture', 'Study the properties and behavior of inorganic compounds.', 5),
(11, 'Bulgarian Language', 'https://i.imgur.com/umD4YUS.jpeg', 'bulgarian language picture', 'Explore the intricacies of the Bulgarian language.', 6),
(12, 'Foreign Languages', 'https://i.imgur.com/iAgcvdu.jpeg', 'foreign languages picture', 'Learn about languages from around the world.', 6),
(13, 'Poetry', 'https://i.imgur.com/bhIUrFE.jpeg', 'poetry picture', 'Experience the art of language through poetic expression.', 7),
(14, 'Prose Fiction', 'https://i.imgur.com/tKBDUKE.jpeg', 'prose fiction picture', 'Explore the world of fictional narratives in prose form.', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(320) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `isAdmin`, `reset_token`) VALUES
(1, 'slendermagican@gmail.com', 'Ivak', '$2y$10$CdA1h9jaMysmu8oqvfNr.e44mvw4xr2/cwIeqPfMkkKEj03EA5MpK', 1, NULL),
(4, 'timon@mail.com', 'timon', '$2y$10$daFjGcW6t1knA4TsEs3wROukRpWcgh8PInMA1ChG5ARnOBoieuChe', 0, NULL),
(5, 'buzzfeed@buzz.com', 'BuzzFeed', '$2y$10$EP2zHNYRjMTx/v8Z07FXxewZ4wYGVUxDyAAgf7VJhS.w.TDg2H5UG', 0, NULL),
(7, 'stamat@bitex.com', 'stamat', '$2y$10$IQq3W.ZLr/piM/nQR1VwL.mrKL4x442shFN1y0lW.wUMpF52OOB3C', 0, NULL),
(8, 'jewel@gmail.com', 'joe', '$2y$10$/xNumQj2WzO362ip8UmD9enegGTjpkPsz2cNhYKdxis0B/oG/.x1y', 0, NULL),
(9, '11.ivaylo.ivanov@gmail.com', 'asdf', '$2y$10$LGOQRVVf.UEbfppxY.7MqOl/E6bLhtFogacQfVF5xejJv7n.czs0i', 0, NULL),
(10, 'sasho@abv.bg', 'sasho', '$2y$10$yvaE/SXZCxa5nOpwMqHkh.mSms3wDyOY3QcZOEGYcc77zgxh0wxkm', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `question_results`
--
ALTER TABLE `question_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_result_id` (`quiz_result_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quiz` (`quiz`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcategory` (`subcategory`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `question_results`
--
ALTER TABLE `question_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_results`
--
ALTER TABLE `question_results`
  ADD CONSTRAINT `question_results_ibfk_1` FOREIGN KEY (`quiz_result_id`) REFERENCES `quiz_results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `question_results_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
