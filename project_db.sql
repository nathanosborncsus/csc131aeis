CREATE TABLE users (
	user_id integer not null AUTO_INCREMENT PRIMARY KEY,
	firstName varchar(50) not null,
	lastName varchar(50) not null,
	email varchar(100) not null,
	passwordHash varchar(256) not null
);

CREATE TABLE assessments (
	report_ID integer not null AUTO_INCREMENT PRIMARY KEY,
	report_name varchar(50) not null,
	question_1 bit not null,
	question_2 bit not null,
	question_3 bit not null,
	question_4 bit not null,
	question_5 bit not null,
	question_6 bit not null,
	question_7 bit not null,
	question_8 bit not null,
	question_9 bit not null,
	question_10 bit not null,
	dt_created datetime DEFAULT CURRENT_TIMESTAMP,
	dt_modified datetime DEFAULT null ON UPDATE CURRENT_TIMESTAMP,
	dt_deleted datetime DEFAULT null,
	active bit DEFAULT 1,
	report_owner int(11) not null,
	FOREIGN KEY (report_owner) REFERENCES users(user_id)
);

CREATE TABLE questions (
	question_set integer not null AUTO_INCREMENT PRIMARY KEY,
	question_1 text not null,
	question_2 text not null,
	question_3 text not null,
	question_4 text not null,
	question_5 text not null,
	question_6 text not null,
	question_7 text not null,
	question_8 text not null,
	question_9 text not null,
	question_10 text not null,
	dt_created datetime DEFAULT CURRENT_TIMESTAMP,
	dt_modified datetime DEFAULT null ON UPDATE CURRENT_TIMESTAMP,
	dt_deleted datetime DEFAULT null
);
