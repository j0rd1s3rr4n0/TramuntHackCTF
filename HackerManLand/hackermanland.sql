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
  (1, 'admin', '8e372e13fa1e720776e80ebb4a878fd9', 1),
  (2, 'user', '8e372e13fa1e720776e80ebb4a878fd9', 0),
  (3, 'j0rd1s3rr4n0', 'd5ec75d5fe70d428685510fae36492d9', 1),
  (4, 'hax0r', '8e372e13fa1e720776e80ebb4a878fd9', 0);
