CREATE TABLE board(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    owner_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE board_members(
    id INT PRIMARY KEY AUTO_INCREMENT,
    board_id INT,
    user_id INT,
    role enum('owner', 'member') DEFAULT 'member',
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  username VARCHAR(30) NOT NULL,
  password VARCHAR(255) NOT NULL,
  profile_picture varchar(255),
  PRIMARY KEY (id),
  UNIQUE (email),
  UNIQUE (username)
);

CREATE TABLE task (
  id int PRIMARY KEY auto_increment,
  name varchar(50),		
  card_id	int,
  state BOOLEAN DEFAULT FALSE,
  description VARCHAR(150),	
  photo VARCHAR(255) NULL
);

CREATE TABLE card (
  id int PRIMARY KEY auto_increment,
  name	varchar(50),		
  board_id int		
);

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,          
    action VARCHAR(50) NOT NULL,  
    target_type VARCHAR(50),   
    target_id INT,            
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    target_name VARCHAR(50)
);


CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipient_user_id INT NOT NULL,
  source_activity_id INT NULL,
  board_id INT NULL, 
  is_read BOOLEAN DEFAULT FALSE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  notification_msg VARCHAR(255) NOT NULL
);

INSERT INTO users (
  first_name,
  last_name,
  email,
  phone,
  username,
  password
) VALUES (
  'Admin',
  'User',
  'Admin@gmail.com',
  '2507625445',
  'AdminUser',
  '$2y$10$VTpIfAxBgSuUmiT1dtn1geTEfbc/25oiiqAZAiOQ73WKTXNbNV7Au'
);

INSERT INTO users (
  first_name,
  last_name,
  email,
  phone,
  username,
  password
) VALUES (
  'Admin',
  'Collaborator',
  'Collaborator@gmail.com',
  '2507625445',
  'Collaborator',
  '$2y$10$VTpIfAxBgSuUmiT1dtn1geTEfbc/25oiiqAZAiOQ73WKTXNbNV7Au'
);
