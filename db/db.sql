
-- Create database
CREATE DATABASE quizzicledb;

-- Use the quiz database
USE quizzicledb;

-- Create the users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(320) UNIQUE,
    username VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    isAdmin BOOLEAN DEFAULT FALSE,
    reset_token VARCHAR(255) DEFAULT NULL
);

-- Create the categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
);


-- Create the subcategories table
CREATE TABLE subcategories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subcategory VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Create the quiz table
CREATE TABLE quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci UNIQUE,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    subcategory_id INT,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE CASCADE
);


-- Create the questions table
CREATE TABLE questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_text TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    answer1 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    answer2 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    answer3 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    answer4 VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    correct_answer_index INT NOT NULL CHECK (correct_answer_index BETWEEN 1 AND 4),
    quiz_id INT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);


-- Create the quiz_results table
CREATE TABLE quiz_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    quiz_id INT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    score DECIMAL(5,2) CHECK (score BETWEEN 0 AND 100),
    time_to_complete INT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Create question_results table
CREATE TABLE question_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_result_id INT,
    FOREIGN KEY (quiz_result_id) REFERENCES quiz_results(id) ON DELETE CASCADE,
    question_id INT,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    user_answer_index INT NOT NULL CHECK (user_answer_index BETWEEN 0 AND 4),
    is_correct BOOLEAN
);

-- Create feedback table
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    feedback_type ENUM('problem', 'request') NOT NULL DEFAULT 'problem',
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




-- Insert data into the users table
INSERT INTO users (email, username, password, isAdmin)
VALUES
    ('slendermagican@gmail.com', 'ivak', '$2y$10$CdA1h9jaMysmu8oqvfNr.e44mvw4xr2/cwIeqPfMkkKEj03EA5MpK', TRUE);
    

-- Insert data into the categories table
INSERT INTO categories (category, img_src, img_alt, description)
VALUES
    ('History', 'https://i2.wp.com/www.narodnitebuditeli.com/wp-content/uploads/2013/05/paisii_hilendarski02.jpg?resize=210%2C239', 'history picture', 'History is fun except when it''s not'),
    ('Geography', 'https://petersfieldinfantschool.co.uk/wp-content/uploads/2022/04/geography-image.jpg', 'geography picture', 'Geography is fun except when it''s not'),
    ('Physics', 'https://media-cldnry.s-nbcnews.com/image/upload/t_fit-760w,f_auto,q_auto:best/newscms/2018_22/2451826/180601-atomi-mn-1540.jpg', 'physics picture', 'Physics is the study of matter, energy, and the fundamental forces of nature.'),
    ('Mathematics', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSmHJetArk3aZfjr7DVqlznCfPqevDZlAO3qw&usqp=CAU', 'mathematics picture', 'Mathematics is the language of the universe, and it''s all around us.'),
    ('Chemistry', 'https://iimtu.edu.in/blog/wp-content/uploads/2023/10/Chemistry-1.jpg', 'chemistry picture', 'Chemistry explores the properties, composition, and behavior of matter.'),
    ('Language', 'https://counseling.northwestern.edu/wp-content/uploads/sites/83/2021/02/Inclusive-Language_Hero.jpg', 'language picture', 'Language is the intricate tapestry of communication, a dynamic system of symbols and sounds that facilitates the expression of thoughts, ideas, and emotions, fostering connection and understanding among individuals.'),
    ('Literature', 'https://img.freepik.com/free-vector/watercolor-literature-illustration_52683-81536.jpg', 'literature', 'Literature is the artful exploration of human experience, emotions, and imagination through the written word, transcending time and culture to illuminate the complexities of the human condition.');

-- Insert data into the subcategories table with manual category IDs
INSERT INTO subcategories (subcategory, img_src, img_alt, description, category_id)
VALUES
    ('Bulgarian History', 'https://i0.wp.com/www.kashkaval-tourist.com/wp-content/uploads/2014/03/cover-big.jpg?fit=1600%2C577&ssl=1', 'bulgarian history picture', 'Explore the rich history of Bulgaria.', 1),
    ('World History', 'https://t4.ftcdn.net/jpg/06/07/14/09/360_F_607140968_2fgyCuYUaZVjgP40xNntLsDJurVj7ol2.jpg', 'world history picture', 'Discover the fascinating history of our world.', 1),
    ('Physical Geography', 'https://www.teachersdiscovery.com/images/uploads/3P2711_01_Th.jpg', 'physical geography picture', 'Study the natural features and processes of the Earth.', 2),
    ('Human Geography', 'https://study.com/cimages/course-image/human-geography-textbook_137698_large.jpg', 'human geography picture', 'Explore the relationship between humans and their environment.', 2),
    ('Classical Mechanics', 'https://ocw.mit.edu/courses/8-223-classical-mechanics-ii-january-iap-2017/10f76c13caec5bf5cc2610c53e94d6fc_8-223iap17.jpg', 'classical mechanics picture', 'Learn about the motion of objects under the influence of forces.', 3),
    ('Quantum Physics', 'https://assets-global.website-files.com/5f7244960797844e78d7a13a/615cd58d5000d4747070b9dc_Full%20Size%20Image%20(20).png', 'quantum physics picture', 'Delve into the fascinating world of quantum phenomena.', 3),
    ('Algebra', 'https://media4.manhattan-institute.org/wp-content/uploads/sites/5/math.jpg', 'algebra picture', 'Explore the rules and relationships of mathematical symbols.', 4),
    ('Geometry', 'https://www.mathaware.org/wp-content/uploads/2023/05/geometry-3146.jpg', 'geometry picture', 'Study the properties and relations of points, lines, surfaces, and solids.', 4),
    ('Organic Chemistry', 'https://ucfsi.files.wordpress.com/2023/01/e0a81672-5734-4e7d-9fb4-d7243a195ff5.jpeg?w=1568', 'organic chemistry picture', 'Examine the structure, properties, and reactions of organic compounds.', 5),
    ('Inorganic Chemistry', 'https://uscibooks.aip.org/wp-content/uploads/Biological-Inorganic-Chemistry.jpg', 'inorganic chemistry picture', 'Study the properties and behavior of inorganic compounds.', 5),
    ('Bulgarian Language', 'https://qph.cf2.quoracdn.net/main-qimg-e72d553fe459b03696c3fb578ad96a9f', 'bulgarian language picture', 'Explore the intricacies of the Bulgarian language.', 6),
    ('Foreign Languages', 'https://www.lcps.org/cms/lib/VA01000195/Centricity/Domain/739/World%20Languages%20Love.jpg', 'foreign languages picture', 'Learn about languages from around the world.', 6),
    ('Poetry', 'https://capitalizemytitle.com/wp-content/uploads/2022/08/William-Shakespeare.jpg', 'poetry picture', 'Experience the art of language through poetic expression.', 7),
    ('Prose Fiction', 'https://hips.hearstapps.com/hmg-prod/images/leo-tolstoy.jpg', 'prose fiction picture', 'Explore the world of fictional narratives in prose form.', 7);


-- Insert data into the quizzes table
INSERT INTO quizzes (quiz, img_src, img_alt, description, subcategory_id)
VALUES
    -- Bulgarian History
    ('First Bulgarian Empire (681 - 1018)', 'https://about-history.com/wp-content/uploads/2017/12/The-Golden-Age-of-the-First-Bulgarian-Empire-Tsar-Simeon-I-the-Great.jpg', 'First Bulgarian Empire Image', 'Embark on a journey through time with our quiz on the First Bulgarian Empire, testing your knowledge of its inception under Khan Asparuh, pivotal moments under Tsar Simeon I, and the cultural shifts that shaped this ancient Balkan powerhouse.', 1),
    ('Third Bulgarian State (1878 - 1946)', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/BASA-3K-7-342-28-Boris_III_of_Bulgaria.jpeg/1200px-BASA-3K-7-342-28-Boris_III_of_Bulgaria.jpeg', 'Third Bulgarian State 2 Image', 'Dive into the historical twists and turns of the Third Bulgarian State in our quiz, exploring its path to independence in 1878, navigating the challenges of two World Wars, and unraveling the complexities of its communist era before embracing democracy in the late 20th century..', 1),
    -- World History
    ('20 historical events', 'https://static.independent.co.uk/s3fs-public/thumbnails/image/2019/06/03/16/moon-landing.jpg?width=1200', '20 historical events Image', 'Do you know which are these 20 historical events!', 2),
    ('Historical figures', 'https://cdn.britannica.com/93/173193-131-3EE3B458/Nelson-Mandela.jpg', 'Historical figure Image', 'Can you unravel the mysteries behind these iconic figures from history?', 2),
    -- Physical Geography
    ('Do you know this country flags?', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219314248086192219/OIG4.png?ex=660ad9e2&is=65f864e2&hm=cff5e1a56b32d59cebc22d07898c56cff663797ce3d5de4a1adf50b31826ba59&', 'flag quiz image', 'Test your knowledge of World flags.', 3),
    ('What is the capital of this country?', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219315225162027109/OIG4.png?ex=660adacb&is=65f865cb&hm=a50b93533d7369ee0ae7fd46be624f0aeb7cdec426fd05cf33c8fca89c55fa5d&', 'capitals quiz image', 'Another quiz to challenge your knowledge of general knowledge geography.', 3),
    -- Human Geography
    ('Do you know the GDP of this country?', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Nominal_GDP_of_Countries_Crimea_edited.svg/1200px-Nominal_GDP_of_Countries_Crimea_edited.svg.png', 'Human Geography Quiz 1 Image', 'Test your understanding of the world economics.', 4),
    ('In which country is this located?', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219316401677008996/OIG3.png?ex=660adbe3&is=65f866e3&hm=4a77b9a912882457010eff6619afd762c65bd7659b8734985a4e26477191978a&', 'Human Geography Quiz 2 Image', 'Another quiz to explore the dynamic interaction between humans and their surroundings.', 4),
    -- Classical Mechanics
    ('Newtonian Mechanics', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219316139902107748/1520080788802.png?ex=660adba5&is=65f866a5&hm=b788972fa89a001164b275a76c90f7994f6505e74c83cd61f3cce4dc4e79eae9&', 'Classical Mechanics Quiz 1 Image', 'Test your knowledge of classical mechanics principles.', 5),
    ('Hamil- tonian Mechanics', 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/WilliamRowanHamilton.jpeg/220px-WilliamRowanHamilton.jpeg', 'Classical Mechanics Quiz 2 Image', 'Another quiz to challenge your understanding of classical mechanics.', 5),
    -- Quantum Physics
    ('History of Quantum Mechanics', 'https://res-4.cloudinary.com/the-university-of-melbourne/image/upload/s--qX0UpMlQ--/c_limit,f_auto,q_75,w_892/v1/pursuit-uploads/69e/645/223/69e645223c0029518b8f856037cba6b241c40ee5ad2ce8b438fb3a8cb5fc.jpg', 'Quantum Physics Quiz 1 Image', 'Dive into the world of quantum phenomena with this quiz!', 6),
    ('Laws of Quantum Mechanics', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219319029366259742/OIG2.png?ex=660ade56&is=65f86956&hm=538a9ec88ad03211152a8928186b4de5b642866091b136edb2a2a17a6790e7c5&', 'Quantum Physics Quiz 2 Image', 'Another quiz to explore the fascinating aspects of quantum physics.', 6),
    -- Algebra
    ('Liniar Algebra', 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Linear_subspaces_with_shading.svg/1200px-Linear_subspaces_with_shading.svg.png', 'Algebra Quiz 1 Image', 'Test your understanding of algebraic concepts.', 7),
    ('Calculus', 'https://apasseducation.com/wp-content/uploads/2017/06/calc268f.jpg', 'Algebra Quiz 2 Image', 'Another quiz to challenge your knowledge of algebra.', 7),
    -- Geometry
    ('Planimetrics', 'https://lh3.googleusercontent.com/proxy/tA8T1hVpk2CoRPbt5Ru-Zf6ztn-56MaUkDP4TXabfDi2MY396hj_X-TJDQEh6BvHmuqKzTQ9AZH1dpYuRyxkGGebavS2b92z', 'Geometry Quiz 1 Image', 'Explore geometric principles with this quiz!', 8),
    ('Polymetry', 'https://i5.walmartimages.com/seo/Learning-Resources-Hands-On-Soft-Geosolids-Soft-Foam-3D-Shapes-Math-Manipulatives-Set-of-12-Ages-5-6-7_003c41c0-c3ab-4251-8aa9-f3640a8b2e5c.90f53576418ef66d052d55ef4c306c9c.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF', 'Geometry Quiz 2 Image', 'Another quiz to test your knowledge of geometry.', 8),
    -- Organic Chemistry
    ('Stereochemistry', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219319504824303677/OIG3.png?ex=660adec7&is=65f869c7&hm=152f5a7792393e6811b28ebdb3c04abceea1e62aca455bda2d254e174473263f&', 'Organic Chemistry Quiz 1 Image', 'Test your knowledge of organic chemistry.', 9),
    ('Organic synthesis', 'https://www.sigmaaldrich.com/deepweb/assets/sigmaaldrich/marketing/global/images/applications/chemistry-and-synthesis/organic-reaction-toolbox/organic-reaction-toolbox.jpg', 'Organic Chemistry Quiz 2 Image', 'Another quiz to explore the world of organic compounds.', 9),
    -- Inorganic Chemistry
    ('Nuclear chemistry', 'https://tse1.mm.bing.net/th/id/OIG4.sKJ7dZZwV6p9LJbbLxSa?pid=ImgGn', 'Inorganic Chemistry Quiz 1 Image', 'Test your understanding of inorganic compounds.', 10),
    ('Organometallic chemistry', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219320391953154068/OIG3.png?ex=660adf9b&is=65f86a9b&hm=640553b414e355baf35a3c52d2d4ac3c472c24d2ee774b4831778589d7fb9072&', 'Inorganic Chemistry Quiz 2 Image', 'Another quiz to delve into the properties and behavior of inorganic substances.', 10),
    -- Bulgaraina Language
    ('Punctoation', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219320856912593047/OIG3.png?ex=660ae00a&is=65f86b0a&hm=b1fc922f478f60c3e54489074122cc2699532d15a0917ef8267ce94a9c7b2376&', 'English Language Quiz 1 Image', 'Test your understanding of the English language.', 11),
    ('Gramatics', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219321213730685010/OIG2.png?ex=660ae05f&is=65f86b5f&hm=114bf63aeae15410e93d703cd1d3eca73e994d587e87a6c372a8a53239203036&', 'English Language Quiz 2 Image', 'Another quiz to explore the intricacies of English communication.', 11),
    -- Foreign Languages
    ('Which language is this?', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219321729793392650/OIG2.png?ex=660ae0da&is=65f86bda&hm=b563ba865de76ac2bf2245bfaefac9ae7f8184742932380cfc5616a124ea8636&', 'Foreign Languages Quiz 1 Image', 'Test your knowledge of languages from around the world.', 12),
    ('What is the official language of this country?', 'https://media.discordapp.net/attachments/1194326610233536552/1219322305725861928/OIG2.png?ex=660ae163&is=65f86c63&hm=26d5321d96573ed3f10f709f8d447d7b9f182b1bfe28c41a5e4e4a8403b21ee7&=&format=webp&quality=lossless&width=676&height=676', 'Foreign Languages Quiz 2 Image', 'Another quiz to challenge your understanding of global languages.', 12),
    -- Poetry
    ('Bulgarian Poetry', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219325149090812086/blob.png?ex=660ae409&is=65f86f09&hm=6dcb18545e64d9b525fad5c8e4e714a8e42e65d0c39928edf46408f223b85712&', 'Poetry Quiz 1 Image', 'Test your knowledge of poetic expressions.', 13),
    ('World Poetry', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219324590497464380/OIG4.png?ex=660ae384&is=65f86e84&hm=93c41debd4c50153863cf97777cb836fc4e29bb122e7be6f739bf1b51e29d317&', 'Poetry Quiz 2 Image', 'Another quiz to explore the artful use of language in poetry.', 13),
    -- Prose
    ('Bulgarian Prose', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219326016971997224/OIG1.png?ex=660ae4d8&is=65f86fd8&hm=92c4c2e1ca5458cafd04729142712f6dd39c8781c4961c31c9266fad70cc7dce&', 'Prose Fiction Quiz 1 Image', 'Explore the world of fictional narratives in prose form with this quiz!', 14),
    ('World Prose', 'https://previews.123rf.com/images/savo/savo1811/savo181100269/113859480-color-icon-on-the-theme-of-poetry-and-prose.jpg', 'Prose Fiction Quiz 2 Image', 'Another quiz to test your understanding of prose fiction.', 14);




-- Insert questions about the First Bulgarian Empire
INSERT INTO questions (question_text, img_src, img_alt, answer1, answer2, answer3, answer4, correct_answer_index, quiz_id)
VALUES
('Who was the founder of the First Bulgarian Empire?', 'https://bulstack.com/wp-content/gallery/famous-bulgarians/asparuh-for-web.jpg', 'Founding ruler', 'Krum', 'Asparuh', 'Tervel', 'Boris I', 2, 1),
('In which year was the First Bulgarian Empire officially established?', 'https://cdn4.focus.bg/fakti/photos/big/121/9-avgust-681-g-rajda-se-balgaria-1.jpg', 'Establishment year', '681', '632', '705', '743', 1, 1),
('Which city served as the capital of the First Bulgarian Empire?', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/The_Great_Basilica_Klearchos_2.jpg/1200px-The_Great_Basilica_Klearchos_2.jpg', 'Capital city', 'Preslav', 'Sofia', 'Pliska', 'Veliko Tarnovo', 3, 1),
('Who was the ruler known for adopting Christianity as the official religion of the First Bulgarian Empire?', 'https://static.bnr.bg/gallery/cr/medium/ceccb8670c4bd91b047c42482384d5fa.jpg', 'Christianization ruler', 'Khan Krum', 'Krum', 'Boris I', 'Simeon I', 3, 1);


-- Insert quiz questions for the "Third Bulgarian State (1878 - 1946)" 
INSERT INTO questions (question_text, img_src, img_alt, answer1, answer2, answer3, answer4, correct_answer_index, quiz_id)
VALUES
    ('When did the Third Bulgarian State officially begin?', 'https://static.bnr.bg/gallery/cr/medium/c3f2175e6c9f7da38c97747cb5938e77.jpg', 'creation date image', '1870', '1878', '1885', '1908', 2, 2),
    ('Which treaty marked the formal establishment of the Third Bulgarian State?', 'https://about-history.com/wp-content/uploads/2019/01/Congress-of-Berlin-and-the-Berlin-Treaty-1878.jpg', 'Treaty of berlin', 'Treaty of Berlin', 'Treaty of San Stefano', 'Treaty of Bucharest', 'Treaty of Constantinople', 1, 2),
    ('Who was the first ruler (Knyaz) of the Third Bulgarian State?', 'https://upload.wikimedia.org/wikipedia/commons/d/d3/Alexander_I_of_Bulgaria_by_Dimitar_Karastoyanov.jpg', 'image of the first third bulgarian country ruler', 'Tsar Ferdinand I', 'Tsar Boris III', 'Alexander of Battenberg', 'Alexander Malinov', 3, 2),
    ('What was the capital city of Bulgaria during the Third Bulgarian State?', 'https://www.sofia.bg/documents/58151/68724/%D0%9B%D1%8A%D0%B2%D0%BE%D0%B2-%D0%9C%D0%BE%D1%81%D1%82-%D0%BD%D1%8F%D0%BA%D0%BE%D0%B3%D0%B0-pav.jpg/5139b01b-42dd-4f10-acd1-a8beac8a871d?t=1506954051515', 'image of the capital of the third bulgarian state', 'Sofia', 'Plovdiv', 'Varna', 'Burgas', 1, 2),
    ('Which war led to significant territorial gains for Bulgaria during the Third Bulgarian State?', 'https://www.historycrunch.com/uploads/4/1/1/6/41169839/balkan-league_orig.jpg', 'image of the war that led to teritorial gains for Bulgaria', 'First Balkan War', 'Second Balkan War', 'World War I', 'World War II', 1, 2),
    ('Who served as Bulgaria''s Prime Minister during the critical period of World War I?', 'https://upload.wikimedia.org/wikipedia/commons/a/a4/BASA-313K-1-2536-7-Vasil_Radoslavov.jpg', 'image of the primer minister of bulgarian during the first world war who is bort in Lovech on 27 of July 1854', 'Vasil Radoslavov', 'Alexander Stamboliyski', 'Aleksandar Tsankov', 'Nikola Mushanov', 1, 2),
    ('What event marked the first national crisis in the Third Bulgarian State?', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Participants_in_the_Bucharest_Peace_Treaty_negotiations%2C_1913.jpg/250px-Participants_in_the_Bucharest_Peace_Treaty_negotiations%2C_1913.jpg', 'End of Third Bulgarian State', 'Signing of the Treaty of Bucharest', 'Abdication of Tsar Boris III', 'Communist uprising', 'Invasion by the Soviet Union', 1, 2),
    ('Which country occupied Bulgaria following the end of World War II, leading to the establishment of a communist government?', 'https://sofiaglobe.com/wp-content/uploads/2016/09/September-9-Bulgaria-communist-600x338.jpg', 'image of the occupation', 'United States', 'Soviet Union', 'United Kingdom', 'France', 2, 2),
    ('What was the name of the communist leader who rose to power in Bulgaria after World War II?', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQykQVuwGcaWiQYyr99dgHezwUkT3-98UuUK0h5S7alMA&s', 'first bulgarian communist leader', 'Todor Zhivkov', 'Georgi Dimitrov', 'Petar Gabrovski', 'Valko Chervenkov', 2, 2),
    ('When was the monarchy abolished in Bulgaria, officially ending the Third Bulgarian State?', 'https://static.bnr.bg/gallery/f2/f27a1d1ede4879ee51d5f91b50c475de.jpg', 'fall of the monarchy in bulgaria referendum', '1943', '1946', '1952', '1965', 2, 2);

-- Insert questions about famous historical events (Quiz ID: 3)
INSERT INTO questions (question_text, img_src, img_alt, answer1, answer2, answer3, answer4, correct_answer_index, quiz_id)
VALUES
    ('Which event is depicted in this image?', 'https://www.worldhistory.org/img/r/p/500x600/18141.jpg?v=1699624943', 'Boston Tea Party', 'Boston Tea Party', 'The signing of the Declaration of Independence', 'The Battle of Bunker Hill', 'The Boston Massacre', 1, 3),
    ('What historical event is commonly associated with this date: July 20, 1969?', 'data:image/jpeg;base64,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', 'Apollo 11 Moon Landing', 'Assassination of John F. Kennedy', 'Apollo 11 Moon Landing', 'D-Day Invasion', 'Fall of the Berlin Wall', 2, 3),
    ('What event marked the beginning of World War II?', 'https://www.thoughtco.com/thmb/QM_bjlk7KAM47q6DAO3rKwOJHK0=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/hitler_reviewing_troops-2696364-58e795523df78c51623a7b47.jpg', 'Invasion of Poland', 'Battle of Stalingrad', 'Invasion of Poland', 'Bombing of Pearl Harbor', 'Signing of the Treaty of Versailles', 1, 3),
    ('Which event is often considered the beginning of the Renaissance period?', 'https://knightstemplar.co/wp-content/uploads/2023/09/galileus_the_event_marking_the_beginning_of_the_Renaissance_per_e7cb9a97-d29c-4869-9e54-1df2704b46d7.jpg', 'Italian Renaissance', 'Invention of the printing press', 'Italian Renaissance', 'Fall of Constantinople', 'Publication of "The Prince" by Machiavelli', 1, 3),
    ('Как отговаря г-жа Ушанова на въпроса"Ще дойдете ли с нас на бала?" от този въпрос зависи света както го познаваме?', 'https://cdn.discordapp.com/attachments/1194326610233536552/1219590518841806928/6475f6ae87264a3d926d87d7437e9cac.png?ex=660bdb2e&is=65f9662e&hm=655c28f22ae3fbe13dc89bb77114f53293d70cfe8e797e08d7335f164098aefe&','cat with flowers', 'Да', 'Yes', 'Да, ако Атанас Казиев го няма', 'Ja Ja Wundaba', 3, 3);





