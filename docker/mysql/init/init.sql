-- Use camagru database:
USE camagru;

-- Set the event scheduler on:
SET GLOBAL event_scheduler = ON;

-- Create table for the users:
CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id VARCHAR(80) NOT NULL UNIQUE,
	email VARCHAR(320) NULL DEFAULT NULL UNIQUE,
	username VARCHAR(50) NULL DEFAULT NULL UNIQUE,
	password VARCHAR(255) NULL DEFAULT NULL,
	activation_token VARCHAR(32) NULL DEFAULT NULL,
	account_activated BOOLEAN NOT NULL DEFAULT FALSE,
	reset_password_token VARCHAR(32) NULL DEFAULT NULL,
	reset_password_expiry DATETIME NULL DEFAULT NULL,
	notifications_activated BOOLEAN NOT NULL DEFAULT TRUE,
	date_of_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create table for the user images:
CREATE TABLE images (
	image_id INT AUTO_INCREMENT PRIMARY KEY,
	user_id VARCHAR(80) NOT NULL,
	upload_by VARCHAR(50) NULL DEFAULT NULL,
	date_of_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	image MEDIUMBLOB,
	thumbnail MEDIUMBLOB NULL DEFAULT NULL,
	FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- Create table for comments:
CREATE TABLE comments (
	comment_id INT AUTO_INCREMENT PRIMARY KEY,
	user_id VARCHAR(80) NOT NULL,
	image_id INT,
	posted_by VARCHAR(50) NULL DEFAULT NULL,
	comment TEXT,
	date_of_creation TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (image_id) REFERENCES images(image_id)
) ENGINE=InnoDB;

-- Create table for likes:
CREATE TABLE likes (
	like_id INT AUTO_INCREMENT PRIMARY KEY,
	user_id VARCHAR(80) NOT NULL,
	image_id INT,
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (image_id) REFERENCES images(image_id)
) ENGINE=InnoDB;

/*
Create the event to delete users that have
not activated their account in a week: 
*/
delimiter |

CREATE EVENT IF NOT EXISTS delete_unactivated_users
	ON SCHEDULE EVERY 1 DAY
	ON COMPLETION PRESERVE
	DO
		BEGIN
			DELETE FROM users
			WHERE account_activated = FALSE AND date_of_creation < DATE_SUB(NOW(), INTERVAL 1 WEEK);
		END |

delimiter ;