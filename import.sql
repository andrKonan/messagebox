CREATE TABLE user (
	id int NOT NULL AUTO_INCREMENT,
    name varchar(32),
    token varchar(256),
    PRIMARY KEY (id),
    UNIQUE (name)
);

CREATE TABLE chat (
	id int NOT NULL AUTO_INCREMENT,
	name varchar(32),
	description varchar(256),
	PRIMARY KEY (id)
);

CREATE TABLE member (
	id int NOT NULL AUTO_INCREMENT,
	user_id int NOT NULL,
	chat_id int NOT NULL,
	is_admin int DEFAULT 0,
	FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
	FOREIGN KEY (chat_id) REFERENCES chat(id) ON DELETE CASCADE,
	PRIMARY KEY (id)
);

CREATE TABLE message (
	id int NOT NULL AUTO_INCREMENT,
    text varchar(1024),
    post_date datetime,
    user_id int,
    chat_id int,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (chat_id) REFERENCES chat(id) ON DELETE CASCADE,
    PRIMARY KEY (id)
);