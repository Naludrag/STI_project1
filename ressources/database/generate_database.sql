-- -----------------------------------------------------
-- Table User
-- -----------------------------------------------------
DROP TABLE IF EXISTS User ;

CREATE TABLE IF NOT EXISTS User (
  username VARCHAR(32) NOT NULL,
  passwordHash VARCHAR(255) NOT NULL,
  validity TINYINT(1) NOT NULL,
  admin TINYINT(1) NOT NULL,
  PRIMARY KEY (username));


-- -----------------------------------------------------
-- Table Message
-- -----------------------------------------------------
DROP TABLE IF EXISTS Message ;

CREATE TABLE IF NOT EXISTS Message (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  object VARCHAR(45) NOT NULL,
  body LONGTEXT NOT NULL,
  receptionDate DATE NOT NULL,
  fk_sender VARCHAR(32) NOT NULL,
  fk_receiver VARCHAR(32) NOT NULL,
  CONSTRAINT fk_Message_User1
    FOREIGN KEY (fk_sender)
    REFERENCES User (username)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Message_User2
    FOREIGN KEY (fk_receiver)
    REFERENCES User (username)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Insert into tables
-- -----------------------------------------------------
INSERT INTO User (username, passwordHash, validity, admin) 
VALUES ('patrick', '$2y$10$2LgUrrRRYHb8G8NtOP3OGeMTvwE.Jr11zLx2AwXrMBl.tQIYIEGJK', 1, 0),
       ('richard', '$2y$10$iaJSGNd0Lb6y3TdgBqvCruvadFrp91IfOSFplYVhgSRCxW84O58fG', 1, 1);

INSERT INTO Message (object, body, receptionDate, fk_sender, fk_receiver) 
VALUES ('Pétanque', 'Oublie pas les boules pour la pétanque',  '2020-09-18 13:05:42', 'patrick', 'richard'),
       ('Ricard',   'J ai pris du ricard pour la pétanque',    '2020-09-18 13:09:13', 'richard', 'patrick'),
       ('Bières',   'J ai oublié les bières pour la pétanque', '2020-09-18 13:13:33', 'patrick', 'richard');