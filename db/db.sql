-- -- Create database
-- CREATE DATABASE quiz;

-- -- Use the quiz database
-- USE quiz;

-- -- Create the users table
-- CREATE TABLE users (
--     email VARCHAR(320) PRIMARY KEY,
--     username VARCHAR(255) UNIQUE NOT NULL,
--     password VARCHAR(255) NOT NULL,
--     isAdmin BOOLEAN DEFAULT FALSE,
--     reset_token VARCHAR(255) DEFAULT NULL
-- );

-- -- Create the categories table
-- CREATE TABLE categories (
--     category VARCHAR(255) PRIMARY KEY,
--     img_src TEXT NOT NULL,
--     img_alt VARCHAR(255) NOT NULL,
--     description TEXT NOT NULL
-- );

-- -- Create the subcategories table
-- CREATE TABLE subcategories (
--     subcategory VARCHAR(255) PRIMARY KEY,
--     img_src TEXT NOT NULL,
--     img_alt VARCHAR(255) NOT NULL,
--     description TEXT NOT NULL,
--     category VARCHAR(255),
--     FOREIGN KEY (category) REFERENCES categories(category)
-- );


-- Create database
CREATE DATABASE quizzicledb;

-- Use the quiz database
USE quizzicledb;

-- Create the users table
    CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(320) UNIQUE,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    isAdmin BOOLEAN DEFAULT FALSE,
    reset_token VARCHAR(255) DEFAULT NULL
);

-- Create the categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category VARCHAR(255) UNIQUE,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
);


-- Create the subcategories table
CREATE TABLE subcategories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subcategory VARCHAR(255) UNIQUE,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Create the quiz table
CREATE TABLE quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz VARCHAR(255) UNIQUE,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    subcategory_id INT,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id)
);


-- Create the question table
CREATE TABLE questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_text TEXT NOT NULL,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) NOT NULL,
    answer1 VARCHAR(255) NOT NULL,
    answer2 VARCHAR(255) NOT NULL,
    answer3 VARCHAR(255) NOT NULL,
    answer4 VARCHAR(255) NOT NULL,
    correct_answer_index INT NOT NULL CHECK (correct_answer_index BETWEEN 1 AND 4),
    quiz_id INT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);


-- Create the quiz_results table
CREATE TABLE quiz_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    quiz_id INT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id),
    score DECIMAL(5,2) CHECK (score BETWEEN 0 AND 100),
    time_to_complete INT, --in seconds
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Create question_result table
CREATE TABLE question_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_result_id INT,
    FOREIGN KEY (quiz_result_id) REFERENCES quiz_results(id),
    question_id INT,
    FOREIGN KEY (question_id) REFERENCES questions(id),
    user_answer_index INT NOT NULL CHECK (user_answer_index BETWEEN 0 AND 4),
    is_correct BOOLEAN
);


-- Insert data into the users table
INSERT INTO users (email, username, password, isAdmin)
VALUES
    ('slendermagican@gmail.com', 'ivak', '1234', TRUE),
    ('user1@example.com', 'user1', 'password123', FALSE),
    ('user2@example.com', 'user2', 'securepass', FALSE),
    ('user3@example.com', 'user3', 'secretword', FALSE);

-- Insert data into the categories table
INSERT INTO categories (category, img_src, img_alt, description)
VALUES
    ('History', 'https://i2.wp.com/www.narodnitebuditeli.com/wp-content/uploads/2013/05/paisii_hilendarski02.jpg?resize=210%2C239', 'history picture', 'History is fun except when it''s not'),
    ('Geography', 'https://petersfieldinfantschool.co.uk/wp-content/uploads/2022/04/geography-image.jpg', 'geography picture', 'Geography is fun except when it''s not'),
    -- ('Physics', 'https://media-cldnry.s-nbcnews.com/image/upload/t_fit-760w,f_auto,q_auto:best/newscms/2018_22/2451826/180601-atomi-mn-1540.jpg', 'physics picture', 'Physics is the study of matter, energy, and the fundamental forces of nature.'),
    -- ('Mathematics', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSmHJetArk3aZfjr7DVqlznCfPqevDZlAO3qw&usqp=CAU', 'mathematics picture', 'Mathematics is the language of the universe, and it''s all around us.'),
    -- ('Chemistry', 'https://iimtu.edu.in/blog/wp-content/uploads/2023/10/Chemistry-1.jpg', 'chemistry picture', 'Chemistry explores the properties, composition, and behavior of matter.'),
    -- ('Language', 'https://counseling.northwestern.edu/wp-content/uploads/sites/83/2021/02/Inclusive-Language_Hero.jpg', 'language picture', 'Language is the intricate tapestry of communication, a dynamic system of symbols and sounds that facilitates the expression of thoughts, ideas, and emotions, fostering connection and understanding among individuals.'),
    -- ('Literature', 'https://img.freepik.com/free-vector/watercolor-literature-illustration_52683-81536.jpg', 'literature', 'Literature is the artful exploration of human experience, emotions, and imagination through the written word, transcending time and culture to illuminate the complexities of the human condition.');

-- Insert data into the subcategories table with manual category IDs
INSERT INTO subcategories (subcategory, img_src, img_alt, description, category_id)
VALUES
    ('Bulgarian History', 'https://i0.wp.com/www.kashkaval-tourist.com/wp-content/uploads/2014/03/cover-big.jpg?fit=1600%2C577&ssl=1', 'bulgarian history picture', 'Explore the rich history of Bulgaria.', 1), -- Manually set the category_id for 'History' to 1
    ('World History', 'https://t4.ftcdn.net/jpg/06/07/14/09/360_F_607140968_2fgyCuYUaZVjgP40xNntLsDJurVj7ol2.jpg', 'world history picture', 'Discover the fascinating history of our world.', 1), -- Manually set the category_id for 'History' to 1
--     ('Physical Geography', 'https://example.com/physical_geography.jpg', 'physical geography picture', 'Study the natural features and processes of the Earth.', 2), -- Manually set the category_id for 'Geography' to 2
--     ('Human Geography', 'https://example.com/human_geography.jpg', 'human geography picture', 'Explore the relationship between humans and their environment.', 2), -- Manually set the category_id for 'Geography' to 2
--     ('Classical Mechanics', 'https://example.com/classical_mechanics.jpg', 'classical mechanics picture', 'Learn about the motion of objects under the influence of forces.', 3), -- Manually set the category_id for 'Physics' to 3
--     ('Quantum Physics', 'https://example.com/quantum_physics.jpg', 'quantum physics picture', 'Delve into the fascinating world of quantum phenomena.', 3), -- Manually set the category_id for 'Physics' to 3
--     ('Algebra', 'https://example.com/algebra.jpg', 'algebra picture', 'Explore the rules and relationships of mathematical symbols.', 4), -- Manually set the category_id for 'Mathematics' to 4
--     ('Geometry', 'https://example.com/geometry.jpg', 'geometry picture', 'Study the properties and relations of points, lines, surfaces, and solids.', 4), -- Manually set the category_id for 'Mathematics' to 4
--     ('Organic Chemistry', 'https://example.com/organic_chemistry.jpg', 'organic chemistry picture', 'Examine the structure, properties, and reactions of organic compounds.', 5), -- Manually set the category_id for 'Chemistry' to 5
--     ('Inorganic Chemistry', 'https://example.com/inorganic_chemistry.jpg', 'inorganic chemistry picture', 'Study the properties and behavior of inorganic compounds.', 5), -- Manually set the category_id for 'Chemistry' to 5
--     ('English Language', 'https://example.com/english_language.jpg', 'english language picture', 'Explore the intricacies of the English language.', 6), -- Manually set the category_id for 'Language' to 6
--     ('Foreign Languages', 'https://example.com/foreign_languages.jpg', 'foreign languages picture', 'Learn about languages from around the world.', 6), -- Manually set the category_id for 'Language' to 6
--     ('Poetry', 'https://example.com/poetry.jpg', 'poetry picture', 'Experience the art of language through poetic expression.', 7), -- Manually set the category_id for 'Literature' to 7
--     ('Prose Fiction', 'https://example.com/prose_fiction.jpg', 'prose fiction picture', 'Explore the world of fictional narratives in prose form.', 7); -- Manually set the category_id for 'Literature' to 7


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
--     ('Physical Geography Quiz 1', 'https://example.com/physical_geography_quiz_1.jpg', 'Physical Geography Quiz 1 Image', 'Test your understanding of Earth\s natural features.', 3),
--     ('Physical Geography Quiz 2', 'https://example.com/physical_geography_quiz_2.jpg', 'Physical Geography Quiz 2 Image', 'Another quiz to challenge your knowledge of physical geography.', 3),
--     -- Human Geography
--     ('Human Geography Quiz 1', 'https://example.com/human_geography_quiz_1.jpg', 'Human Geography Quiz 1 Image', 'Test your understanding of the relationship between humans and their environment.', 4),
--     ('Human Geography Quiz 2', 'https://example.com/human_geography_quiz_2.jpg', 'Human Geography Quiz 2 Image', 'Another quiz to explore the dynamic interaction between humans and their surroundings.', 4),
--     -- Classical Mechanics
--     ('Classical Mechanics Quiz 1', 'https://example.com/classical_mechanics_quiz_1.jpg', 'Classical Mechanics Quiz 1 Image', 'Test your knowledge of classical mechanics principles.', 5),
--     ('Classical Mechanics Quiz 2', 'https://example.com/classical_mechanics_quiz_2.jpg', 'Classical Mechanics Quiz 2 Image', 'Another quiz to challenge your understanding of classical mechanics.', 5),
--     -- Quantum Physics
--     ('Quantum Physics Quiz 1', 'https://example.com/quantum_physics_quiz_1.jpg', 'Quantum Physics Quiz 1 Image', 'Dive into the world of quantum phenomena with this quiz!', 6),
--     ('Quantum Physics Quiz 2', 'https://example.com/quantum_physics_quiz_2.jpg', 'Quantum Physics Quiz 2 Image', 'Another quiz to explore the fascinating aspects of quantum physics.', 6),
--     -- Algebra
--     ('Algebra Quiz 1', 'https://example.com/algebra_quiz_1.jpg', 'Algebra Quiz 1 Image', 'Test your understanding of algebraic concepts.', 7),
--     ('Algebra Quiz 2', 'https://example.com/algebra_quiz_2.jpg', 'Algebra Quiz 2 Image', 'Another quiz to challenge your knowledge of algebra.', 7),
--     -- Geometry
--     ('Geometry Quiz 1', 'https://example.com/geometry_quiz_1.jpg', 'Geometry Quiz 1 Image', 'Explore geometric principles with this quiz!', 8),
--     ('Geometry Quiz 2', 'https://example.com/geometry_quiz_2.jpg', 'Geometry Quiz 2 Image', 'Another quiz to test your knowledge of geometry.', 8),
--     -- Organic Chemistry
--     ('Organic Chemistry Quiz 1', 'https://example.com/organic_chemistry_quiz_1.jpg', 'Organic Chemistry Quiz 1 Image', 'Test your knowledge of organic chemistry.', 9),
--     ('Organic Chemistry Quiz 2', 'https://example.com/organic_chemistry_quiz_2.jpg', 'Organic Chemistry Quiz 2 Image', 'Another quiz to explore the world of organic compounds.', 9),
--     -- Inorganic Chemistry
--     ('Inorganic Chemistry Quiz 1', 'https://example.com/inorganic_chemistry_quiz_1.jpg', 'Inorganic Chemistry Quiz 1 Image', 'Test your understanding of inorganic compounds.', 10),
--     ('Inorganic Chemistry Quiz 2', 'https://example.com/inorganic_chemistry_quiz_2.jpg', 'Inorganic Chemistry Quiz 2 Image', 'Another quiz to delve into the properties and behavior of inorganic substances.', 10),
--     -- English Language
--     ('English Language Quiz 1', 'https://example.com/english_language_quiz_1.jpg', 'English Language Quiz 1 Image', 'Test your understanding of the English language.', 11),
--     ('English Language Quiz 2', 'https://example.com/english_language_quiz_2.jpg', 'English Language Quiz 2 Image', 'Another quiz to explore the intricacies of English communication.', 11),
--     -- Foreign Languages
--     ('Foreign Languages Quiz 1', 'https://example.com/foreign_languages_quiz_1.jpg', 'Foreign Languages Quiz 1 Image', 'Test your knowledge of languages from around the world.', 12),
--     ('Foreign Languages Quiz 2', 'https://example.com/foreign_languages_quiz_2.jpg', 'Foreign Languages Quiz 2 Image', 'Another quiz to challenge your understanding of global languages.', 12),
--     -- Poetry
--     ('Poetry Quiz 1', 'https://example.com/poetry_quiz_1.jpg', 'Poetry Quiz 1 Image', 'Test your knowledge of poetic expressions.', 13),
--     ('Poetry Quiz 2', 'https://example.com/poetry_quiz_2.jpg', 'Poetry Quiz 2 Image', 'Another quiz to explore the artful use of language in poetry.', 13),
--     -- Prose Fiction
--     ('Prose Fiction Quiz 1', 'https://example.com/prose_fiction_quiz_1.jpg', 'Prose Fiction Quiz 1 Image', 'Explore the world of fictional narratives in prose form with this quiz!', 14),
--     ('Prose Fiction Quiz 2', 'https://example.com/prose_fiction_quiz_2.jpg', 'Prose Fiction Quiz 2 Image', 'Another quiz to test your understanding of prose fiction.', 14);




-- Insert questions about the First Bulgarian Empire
INSERT INTO questions (question_text, img_src, img_alt, answer1, answer2, answer3, answer4, correct_answer_index, quiz_id)
VALUES
('Who was the founder of the First Bulgarian Empire?', 'https://bulstack.com/wp-content/gallery/famous-bulgarians/asparuh-for-web.jpg', 'Founding ruler', 'Krum', 'Asparuh', 'Tervel', 'Boris I', 2, 1),
('In which year was the First Bulgarian Empire officially established?', 'https://cdn4.focus.bg/fakti/photos/big/121/9-avgust-681-g-rajda-se-balgaria-1.jpg', 'Establishment year', '681', '632', '705', '743', 1, 1),
('Which city served as the capital of the First Bulgarian Empire?', 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/The_Great_Basilica_Klearchos_2.jpg/1200px-The_Great_Basilica_Klearchos_2.jpg', 'Capital city', 'Preslav', 'Sofia', 'Pliska', 'Veliko Tarnovo', 3, 1),
('Who was the ruler known for adopting Christianity as the official religion of the First Bulgarian Empire?', 'https://static.bnr.bg/gallery/cr/medium/ceccb8670c4bd91b047c42482384d5fa.jpg', 'Christianization ruler', 'Khan Krum', 'Krum', 'Boris I', 'Simeon I', 3, 1);


--make inserts like this
INSERT INTO quiz_results (user_id, quiz_id, score)
VALUES (1, 1, 90)
ON DUPLICATE KEY UPDATE score = GREATEST(score, VALUES(score));


