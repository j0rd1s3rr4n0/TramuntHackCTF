CREATE DATABASE IF NOT EXISTS hackermanland;
USE hackermanland;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT NOT NULL PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(100) NOT NULL,
  isAdmin INT NOT NULL DEFAULT 0
);

INSERT INTO users (id, username, password, isAdmin) VALUES
  (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1),
  (2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 0),
  (3, 'j0rd1s3rr4n0', '5f4dcc3b5aa765d61d8327deb882cf99', 1),
  (4, 'hax0r', '5f4dcc3b5aa765d61d8327deb882cf99', 0);
