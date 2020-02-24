CREATE TABLE users(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(255),
	about TEXT,
	joined_date DATE,
	active_profile_image_id SMALLINT,
	cover_image_id SMALLINT,
	email varchar(255),
	password varchar(255)
);

CREATE TABLE gyms(
 	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 	name VARCHAR(255),
 	cover_image VARCHAR(255),
 	gym_image VARCHAR(255),
 	about TEXT
);

CREATE TABLE groups(
 	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 	name VARCHAR(255),
 	cover_image VARCHAR(255),
 	group_image VARCHAR(255),
 	about TEXT,
 	admin SMALLINT
 	
 );


CREATE TABLE friend_ids(
	user_id SMALLINT,
	friend_id SMALLINT
);
	
CREATE TABLE cover_images(
	image_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id SMALLINT,
	image_url VARCHAR(255)
);
CREATE TABLE user_images(
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	url VARCHAR(255),
	album_id SMALLINT
);
CREATE TABLE gym_images(
	id SMALLINT,
	url VARCHAR(255),
	album_id SMALLINT
);
 

 CREATE TABLE gym_member_ids(
 	gym_id SMALLINT,
 	member_id SMALLINT
 );