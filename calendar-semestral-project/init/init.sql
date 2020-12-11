CREATE TABLE avatar(id_avatar INTEGER NOT NULL AUTO_INCREMENT, path VARCHAR(255) NOT NULL, PRIMARY KEY(id_avatar));
CREATE TABLE users(id_user INTEGER NOT NULL AUTO_INCREMENT, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL,
 firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL DEFAULT 'user', AVATAR_id_avatar INTEGER NOT NULL,
 PRIMARY KEY(id_user), FOREIGN KEY(AVATAR_id_avatar) REFERENCES avatar(id_avatar));
CREATE TABLE calendar(id_calendar INTEGER NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, valid_until DATE, PRIMARY KEY(id_calendar));
CREATE TABLE category(id_category INTEGER NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, PRIMARY KEY(id_category));
CREATE TABLE event_calendar(id_event_calendar INTEGER NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, 
start DATE NOT NULL, end DATE NOT NULL, CALENDAR_id_calendar INTEGER NOT NULL, CATEGORY_id_category INTEGER NOT NULL, PRIMARY KEY(id_event_calendar),
  FOREIGN KEY(CALENDAR_id_calendar) REFERENCES calendar(id_calendar), FOREIGN KEY(CATEGORY_id_category) REFERENCES category(id_category));
CREATE TABLE users_calendars(USER_id_user INTEGER NOT NULL, CALENDAR_id_calendar INTEGER NOT NULL, access VARCHAR(255) NOT NULL,
 FOREIGN KEY(USER_id_user) REFERENCES users(id_user), FOREIGN KEY(CALENDAR_id_calendar) REFERENCES calendar(id_calendar));

INSERT INTO avatar(id_avatar, path) VALUES(1, 'default-avatar.png');
INSERT INTO category(name) VALUES('Meeting');
INSERT INTO category(name) VALUES('Long term');
INSERT INTO category(name) VALUES('Short term');
INSERT INTO category(name) VALUES('Other');