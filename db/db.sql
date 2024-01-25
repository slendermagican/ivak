-- Create database
CREATE DATABASE quiz;

-- Use the quiz database
USE quiz;

-- Create the users table
CREATE TABLE users (
    email VARCHAR(320) PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    isAdmin BOOLEAN DEFAULT FALSE,
    reset_token VARCHAR(255) DEFAULT NULL
);

-- Create the categories table
CREATE TABLE categories (
    category VARCHAR(255) PRIMARY KEY,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
);

-- Create the subcategories table
CREATE TABLE subcategories (
    subcategory VARCHAR(255) PRIMARY KEY,
    img_src TEXT NOT NULL,
    img_alt VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(255),
    FOREIGN KEY (category) REFERENCES categories(category)
);

-- Insert data into the users table
INSERT INTO users (email, username, password, isAdmin)
VALUES
    ('slendermagican@gmail.com', 'ivak', '1234', TRUE),
    ('user1@example.com', 'user1', 'password123', FALSE),
    ('user2@example.com', 'user2', 'securepass', FALSE),
    ('user3@example.com', 'user3', 'secretword', FALSE),
    


-- Insert data into the categories table
INSERT INTO categories (category, img_src, img_alt, description)
VALUES
    ("History", "https://i2.wp.com/www.narodnitebuditeli.com/wp-content/uploads/2013/05/paisii_hilendarski02.jpg?resize=210%2C239", "history picture", "History is fun except when it's not"),
    ("Geography", "https://petersfieldinfantschool.co.uk/wp-content/uploads/2022/04/geography-image.jpg", "geography picture", "Geography is fun except when it's not"),
    ("Physics", "https://media-cldnry.s-nbcnews.com/image/upload/t_fit-760w,f_auto,q_auto:best/newscms/2018_22/2451826/180601-atomi-mn-1540.jpg", "physics picture", "Physics is the study of matter, energy, and the fundamental forces of nature."),
    ("Mathematics", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSmHJetArk3aZfjr7DVqlznCfPqevDZlAO3qw&usqp=CAU", "mathematics picture", "Mathematics is the language of the universe, and it's all around us."),
    ("Chemistry", "https://iimtu.edu.in/blog/wp-content/uploads/2023/10/Chemistry-1.jpg", "chemistry picture", "Chemistry explores the properties, composition, and behavior of matter."),
    ("Language", "https://counseling.northwestern.edu/wp-content/uploads/sites/83/2021/02/Inclusive-Language_Hero.jpg", "language picture", "Language is the intricate tapestry of communication, a dynamic system of symbols and sounds that facilitates the expression of thoughts, ideas, and emotions, fostering connection and understanding among individuals."),
    ("Literature", "https://img.freepik.com/free-vector/watercolor-literature-illustration_52683-81536.jpg", "literature", "Literature is the artful exploration of human experience, emotions, and imagination through the written word, transcending time and culture to illuminate the complexities of the human condition.");


-- Insert data into the subcategories table
INSERT INTO subcategories (subcategory, img_src, img_alt, description, category)
VALUES
    ('Bulgarian History', 'https://example.com/bulgarian_history.jpg', 'bulgarian history picture', 'Explore the rich history of Bulgaria.', 'History'),
    ('World History', 'https://example.com/world_history.jpg', 'world history picture', 'Discover the fascinating history of our world.', 'History'),
    ('Physical Geography', 'https://example.com/physical_geography.jpg', 'physical geography picture', 'Study the natural features and processes of the Earth.', 'Geography'),
    ('Human Geography', 'https://example.com/human_geography.jpg', 'human geography picture', 'Explore the relationship between humans and their environment.', 'Geography'),
    ('Classical Mechanics', 'https://example.com/classical_mechanics.jpg', 'classical mechanics picture', 'Learn about the motion of objects under the influence of forces.', 'Physics'),
    ('Quantum Physics', 'https://example.com/quantum_physics.jpg', 'quantum physics picture', 'Delve into the fascinating world of quantum phenomena.', 'Physics'),
    ('Algebra', 'https://example.com/algebra.jpg', 'algebra picture', 'Explore the rules and relationships of mathematical symbols.', 'Mathematics'),
    ('Geometry', 'https://example.com/geometry.jpg', 'geometry picture', 'Study the properties and relations of points, lines, surfaces, and solids.', 'Mathematics'),
    ('Organic Chemistry', 'https://example.com/organic_chemistry.jpg', 'organic chemistry picture', 'Examine the structure, properties, and reactions of organic compounds.', 'Chemistry'),
    ('Inorganic Chemistry', 'https://example.com/inorganic_chemistry.jpg', 'inorganic chemistry picture', 'Study the properties and behavior of inorganic compounds.', 'Chemistry'),
    ('English Language', 'https://example.com/english_language.jpg', 'english language picture', 'Explore the intricacies of the English language.', 'Language'),
    ('Foreign Languages', 'https://example.com/foreign_languages.jpg', 'foreign languages picture', 'Learn about languages from around the world.', 'Language'),
    ('Poetry', 'https://example.com/poetry.jpg', 'poetry picture', 'Experience the art of language through poetic expression.', 'Literature'),
    ('Prose Fiction', 'https://example.com/prose_fiction.jpg', 'prose fiction picture', 'Explore the world of fictional narratives in prose form.', 'Literature');

