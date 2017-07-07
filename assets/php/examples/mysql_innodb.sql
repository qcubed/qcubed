#========================================================================== #
#  Tables                                                                   #
#========================================================================== #

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS person;
CREATE TABLE person (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    CONSTRAINT PK_person PRIMARY KEY (id),
    KEY IDX_person_1(last_name)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS login;
CREATE TABLE login (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    person_id INTEGER UNSIGNED,
    username VARCHAR(20) NOT NULL,
    password VARCHAR(20),
    is_enabled TINYINT(1) NOT NULL,
    CONSTRAINT PK_login PRIMARY KEY (id),
    UNIQUE KEY IDX_login_1(person_id),
    UNIQUE KEY IDX_login_2(username)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS project;
CREATE TABLE project (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    project_status_type_id INTEGER UNSIGNED NOT NULL,
    manager_person_id INTEGER UNSIGNED,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    budget DECIMAL(12,2),
    spent DECIMAL(12,2),
    CONSTRAINT PK_project PRIMARY KEY (id),
    KEY IDX_project_1(project_status_type_id),
    KEY IDX_project_2(manager_person_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS team_member_project_assn;
CREATE TABLE team_member_project_assn (
    person_id INTEGER UNSIGNED NOT NULL,
    project_id INTEGER UNSIGNED NOT NULL,
    CONSTRAINT PK_team_member_project_assn PRIMARY KEY (person_id, project_id),
    KEY IDX_teammemberprojectassn_2(project_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS project_status_type;
CREATE TABLE project_status_type (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    guidelines TEXT,
    is_active tinyint(1) NOT NULL,
    CONSTRAINT PK_project_status_type PRIMARY KEY (id),
    UNIQUE KEY IDX_projectstatustype_1(name)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS person_with_lock;
CREATE TABLE person_with_lock (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    sys_timestamp TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT PK_person_with_lock PRIMARY KEY (id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS related_project_assn;
CREATE TABLE related_project_assn (
  project_id INTEGER UNSIGNED NOT NULL,
  child_project_id INTEGER UNSIGNED NOT NULL,
    CONSTRAINT PK_related_project_assn PRIMARY KEY (project_id, child_project_id),
    KEY IDX_relatedprojectassn_2(child_project_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS milestone;
CREATE TABLE milestone (
  id INTEGER UNSIGNED NOT NULL,
  project_id INTEGER UNSIGNED NOT NULL,
  name VARCHAR(50) NOT NULL,
    CONSTRAINT PK_milestone PRIMARY KEY (id),
    KEY IDX_milestoneproj_1(project_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS address;
CREATE TABLE address (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    person_id INTEGER UNSIGNED,
    street VARCHAR(100) NOT NULL,
    city VARCHAR(100),
    CONSTRAINT PK_address PRIMARY KEY (id),
    KEY IDX_address_1(person_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS person_type;
CREATE TABLE person_type (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY name (name)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS person_persontype_assn;
CREATE TABLE person_persontype_assn (
  person_id int(10) unsigned NOT NULL,
  person_type_id int(10) unsigned NOT NULL,
  PRIMARY KEY (person_id,person_type_id),
  KEY person_type_id (person_type_id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS two_key;
CREATE TABLE two_key (
  server varchar(50) NOT NULL,
  directory varchar(50) NOT NULL,
  file_name varchar(50) NOT NULL,
  person_id int(11) unsigned NOT NULL,
  project_id int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (server,directory),
  KEY person_id (person_id),
  KEY project_id (project_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS qc_watchers;
CREATE TABLE qc_watchers (
  table_key varchar(200) NOT NULL,
  ts varchar(40) NOT NULL,
  PRIMARY KEY (table_key)
);

DROP TABLE IF EXISTS type_test;
CREATE TABLE type_test (
  id int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  date_time datetime DEFAULT NULL,
  test_int int(11) DEFAULT NULL,
  test_float float DEFAULT NULL,
  test_text text,
  test_bit tinyint(1) DEFAULT NULL,
  test_varchar varchar(10) DEFAULT NULL,
  PRIMARY KEY (id)
);

#========================================================================== #
#  Foreign Keys                                                             #
#========================================================================== #

ALTER TABLE login ADD CONSTRAINT person_login FOREIGN KEY (person_id) REFERENCES person (id);
ALTER TABLE project ADD CONSTRAINT person_project FOREIGN KEY (manager_person_id) REFERENCES person (id);
ALTER TABLE project ADD CONSTRAINT project_status_type_project FOREIGN KEY (project_status_type_id) REFERENCES project_status_type (id);
ALTER TABLE address ADD CONSTRAINT person_address FOREIGN KEY (person_id) REFERENCES person (id);
ALTER TABLE team_member_project_assn ADD CONSTRAINT person_team_member_project_assn FOREIGN KEY (person_id) REFERENCES person (id);
ALTER TABLE team_member_project_assn ADD CONSTRAINT project_team_member_project_assn FOREIGN KEY (project_id) REFERENCES project (id);
ALTER TABLE milestone ADD CONSTRAINT project_milestone FOREIGN KEY (project_id) REFERENCES project (id);

ALTER TABLE related_project_assn ADD CONSTRAINT related_project_assn_1 FOREIGN KEY (project_id) REFERENCES project (id);
ALTER TABLE related_project_assn ADD CONSTRAINT related_project_assn_2 FOREIGN KEY (child_project_id) REFERENCES project (id);
ALTER TABLE two_key ADD CONSTRAINT two_key_project FOREIGN KEY (project_id) REFERENCES project (id);
ALTER TABLE two_key ADD CONSTRAINT two_key_person FOREIGN KEY (person_id) REFERENCES person (id);

ALTER TABLE `person_persontype_assn` ADD CONSTRAINT person_persontype_assn_1 FOREIGN KEY (person_type_id) REFERENCES person_type (id);
ALTER TABLE `person_persontype_assn` ADD CONSTRAINT person_persontype_assn_2 FOREIGN KEY (person_id) REFERENCES person (id);


#========================================================================== #
#  Type Data                                                                #
#========================================================================== #

INSERT INTO project_status_type (name, description, guidelines, is_active) VALUES ('Open', 'The project is currently active', 'All projects that we are working on should be in this state', 1);
INSERT INTO project_status_type (name, description, guidelines, is_active) VALUES ('Cancelled', 'The project has been canned', null, 1);
INSERT INTO project_status_type (name, description, guidelines, is_active) VALUES ('Completed', 'The project has been completed successfully', 'Celebrate successes!', 1);

INSERT INTO person_type VALUES(1, 'Contractor');
INSERT INTO person_type VALUES(2, 'Manager');
INSERT INTO person_type VALUES(3, 'Inactive');
INSERT INTO person_type VALUES(4, 'Company Car');
INSERT INTO person_type VALUES(5, 'Works From Home');

#========================================================================== #
#  Example Data                                                             #
#========================================================================== #

INSERT INTO person(first_name, last_name) VALUES ('John', 'Doe');
INSERT INTO person(first_name, last_name) VALUES ('Kendall', 'Public');
INSERT INTO person(first_name, last_name) VALUES ('Ben', 'Robinson');
INSERT INTO person(first_name, last_name) VALUES ('Mike', 'Ho');
INSERT INTO person(first_name, last_name) VALUES ('Alex', 'Smith'); 
INSERT INTO person(first_name, last_name) VALUES ('Wendy', 'Smith');
INSERT INTO person(first_name, last_name) VALUES ('Karen', 'Wolfe');
INSERT INTO person(first_name, last_name) VALUES ('Samantha', 'Jones');
INSERT INTO person(first_name, last_name) VALUES ('Linda', 'Brady');
INSERT INTO person(first_name, last_name) VALUES ('Jennifer', 'Smith');
INSERT INTO person(first_name, last_name) VALUES ('Brett', 'Carlisle');
INSERT INTO person(first_name, last_name) VALUES ('Jacob', 'Pratt');

INSERT INTO person_with_lock(first_name, last_name) VALUES ('John', 'Doe');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Kendall', 'Public');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Ben', 'Robinson');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Mike', 'Ho');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Alfred', 'Newman');  
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Wendy', 'Johnson');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Karen', 'Wolfe');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Samantha', 'Jones');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Linda', 'Brady');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Jennifer', 'Smith');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Brett', 'Carlisle');
INSERT INTO person_with_lock(first_name, last_name) VALUES ('Jacob', 'Pratt');

INSERT INTO login(person_id, username, password, is_enabled) VALUES (1, 'jdoe', 'p@$$.w0rd', 0);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (3, 'brobinson', 'p@$$.w0rd', 1);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (4, 'mho', 'p@$$.w0rd', 1);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (7, 'kwolfe', 'p@$$.w0rd', 0);
INSERT INTO login(person_id, username, password, is_enabled) VALUES (NULL, 'system', 'p@$$.w0rd', 1);


INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (3, 7, 'ACME Website Redesign', 'The redesign of the main website for ACME Incorporated', '2004-03-01', '2004-07-01', '9560.25', '10250.75');
INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (1, 4, 'State College HR System', 'Implementation of a back-office Human Resources system for State College', '2006-02-15', NULL, '80500.00', '73200.00');
INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (1, 1, 'Blueman Industrial Site Architecture', 'Main website architecture for the Blueman Industrial Group', '2006-03-01', '2006-04-15', '2500.00', '4200.50');
INSERT INTO project(project_status_type_id, manager_person_id, name, description, start_date, end_date, budget, spent) VALUES
  (2, 7, 'ACME Payment System', 'Accounts Payable payment system for ACME Incorporated', '2005-08-15', '2005-10-20', '5124.67', '5175.30');

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (2, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (5, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (6, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (7, 1);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (8, 1);

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (2, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (4, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (5, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (7, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (9, 2);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (10, 2);

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (1, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (4, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (6, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (8, 3);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (10, 3);

INSERT INTO team_member_project_assn (person_id, project_id) VALUES (1, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (2, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (3, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (5, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (8, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (11, 4);
INSERT INTO team_member_project_assn (person_id, project_id) VALUES (12, 4);

INSERT INTO related_project_assn (project_id, child_project_id) VALUES(1, 3);
INSERT INTO related_project_assn (project_id, child_project_id) VALUES(1, 4);

INSERT INTO related_project_assn (project_id, child_project_id) VALUES(4, 1);

INSERT INTO address (person_id, street, city) VALUES(1, '1 Love Drive', 'Phoenix');
INSERT INTO address (person_id, street, city) VALUES(2, '2 Doves and a Pine Cone Dr.', 'Dallas');
INSERT INTO address (person_id, street, city) VALUES(3, '3 Gold Fish Pl.', 'New York');
INSERT INTO address (person_id, street, city) VALUES(3, '323 W QCubed', 'New York');
INSERT INTO address (person_id, street, city) VALUES(5, '22 Elm St', 'Palo Alto');
INSERT INTO address (person_id, street, city) VALUES(7, '1 Pine St', 'San Jose');
INSERT INTO address (person_id, street, city) VALUES(7, '421 Central Expw', 'Mountain View');

INSERT INTO milestone (id, project_id, name) VALUES (1, 1, 'Milestone A');
INSERT INTO milestone (id, project_id, name) VALUES (2, 1, 'Milestone B');
INSERT INTO milestone (id, project_id, name) VALUES (3, 1, 'Milestone C');
INSERT INTO milestone (id, project_id, name) VALUES (4, 2, 'Milestone D');
INSERT INTO milestone (id, project_id, name) VALUES (5, 2, 'Milestone E');
INSERT INTO milestone (id, project_id, name) VALUES (6, 3, 'Milestone F');
INSERT INTO milestone (id, project_id, name) VALUES (7, 4, 'Milestone G');
INSERT INTO milestone (id, project_id, name) VALUES (8, 4, 'Milestone H');
INSERT INTO milestone (id, project_id, name) VALUES (9, 4, 'Milestone I');
INSERT INTO milestone (id, project_id, name) VALUES (10, 4, 'Milestone J');

INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('cnn.com', 'us', 'news', 1, 1);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('google.com', 'drive', '', 2, 2);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('google.com', 'mail', 'mail.html', 3, 2);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('google.com', 'news', 'news.php', 4, 3);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('mail.google.com', 'mail', 'inbox', 5, NULL);
INSERT INTO two_key (server, directory, file_name, person_id, project_id) VALUES('yahoo.com', '', '', 6, NULL);

INSERT INTO person_persontype_assn VALUES(3, 1);
INSERT INTO person_persontype_assn VALUES(10, 1);
INSERT INTO person_persontype_assn VALUES(1, 2);
INSERT INTO person_persontype_assn VALUES(3, 2);
INSERT INTO person_persontype_assn VALUES(1, 3);
INSERT INTO person_persontype_assn VALUES(3, 3);
INSERT INTO person_persontype_assn VALUES(9, 3);
INSERT INTO person_persontype_assn VALUES(2, 4);
INSERT INTO person_persontype_assn VALUES(2, 5);
INSERT INTO person_persontype_assn VALUES(5, 5);
INSERT INTO person_persontype_assn VALUES(7, 2);
INSERT INTO person_persontype_assn VALUES(7, 4);

SET FOREIGN_KEY_CHECKS = 1;
