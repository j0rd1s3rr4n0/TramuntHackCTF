
CREATE DATABASE IF NOT EXISTS accounts;
USE accounts;

-- Volcando estructura para tabla accounts.challenges
CREATE TABLE IF NOT EXISTS challenges (
  id int NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  points int NOT NULL DEFAULT '0',
  description text,
  difficulty enum('Very Easy','Easy','Medium','Hard','Very Hard','Insane','UnRated','None') NOT NULL,
  flag varchar(255) NOT NULL,
  category enum('root','user','challenge','web','reversing','steganography','pwning','cryptography','misc','other','easteregg') NOT NULL DEFAULT 'misc',
  hint varchar(255) NOT NULL,
  download varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

-- Volcando datos para la tabla accounts.challenges: ~12 rows (aproximadamente)
DELETE FROM challenges;
INSERT INTO challenges (id, title, points, description, difficulty, flag, category, hint, download) VALUES
	(1, 'Forensic Case 1', 50, '<h5>Caso GEO</h5>\r\nUn archivo de imagen se ha encontrado en un dispositivo digital secuestrado durante una investigación de delitos cibernéticos. \r\nLa fotografía puede ser crucial para entender el contexto de un incidente. \r\n\r\nTu tarea es determinar dónde y cuándo se tomó esta fotografía.\r\n<br><br>\r\n¿Dónde fue tomada la fotografía?\r\n<br>\r\n<br>FORMATO:\r\n<br><sub>CTF{NAME_ADDRESS}</sub>\r\n<br><br>Ejemplo:\r\n<br><sub>30º 63'' 43.80" N, 21º 37'' 10.02" W</sub>\r\n<br><br><sub>CTF{30_63_43_80_N_21_37_10_02_W}</sub>', 'Easy', 'CTF{15_39_29_40_N_88_59_31_80_W}', 'misc', 'En las sombras de la imagen, algo yace oculto. Solo aquellos que miran más allá de lo visible, descubrirán lo que los demás no ven.', 'https://raw.githubusercontent.com/j0rd1s3rr4n0/ForenseTech-Challenges/refs/heads/main/caso1/picture.jpg'),
	(2, 'Forensic Case 2', 100, '<h5>Caso Joe Jacobs</h5>\r\n<br><h6><b>Escenario:</b></h6>\r\n<div style="text-align:justify;">\r\nJoe Jacobs, de 28 años, fue arrestado ayer acusado de vender drogas ilegales a estudiantes de secundaria. Jacobs se acercó a un oficial de policía local que se hacía pasar por un estudiante de secundaria en el estacionamiento de Smith Hill High School. Jacobs le preguntó al policía encubierto si le gustaría comprar un poco de marihuana. Antes de que el policía encubierto pudiera responder, Jacobs sacó un poco de su bolsillo y se lo mostró al oficial. Jacobs le dijo al oficial: "¡Miren esto, los más expertos no podrían cultivarlo mejor! Mi proveedor no solo me lo vende directamente, sino que él mismo lo cultiva.¨\r\n\r\nSe ha visto a Jacobs en numerosas ocasiones pasando el rato en varios estacionamientos locales de la escuela secundaria alrededor de las 2:30 p.m., la hora en que generalmente termina la escuela. Oficiales escolares de varias escuelas secundarias han llamado a la policía sobre la presencia de Jacobs en su escuela y notaron un aumento en el consumo de drogas entre los estudiantes, desde su llegada.\r\n\r\nLa policía necesita tu ayuda. Quieren intentar determinar si Joe Jacobs ha estado vendiendo drogas a estudiantes en otras escuelas además de en Smith Hill. El problema es que ningún estudiante se presentará y ayudará a la policía. Según el comentario de Joe sobre los más expertos, la policía está interesada en encontrar al proveedor/productor de marihuana de Joe Jacob.\r\n\r\nJacobs ha negado vender drogas en cualquier otra escuela además de en Smith Hill y se niega a proporcionar a la policía el nombre de su proveedor /productor de drogas. Jacobs también se niega a corroborar la declaración que hizo al oficial encubierto justo antes de su arresto. Al emitir una orden de allanamiento y registrar la casa del sospechoso, la policía pudo obtener una pequeña cantidad de marihuana. La policía también confiscó un solo disquete, pero no había ordenadores ni otros dispositivos en la casa.\r\n\r\nLa policía ha tomado una imagen del disquete del sospechoso y te ha proporcionado una copia. A ellos les gustaría que examines el disquete y proporciones respuestas a las siguientes preguntas. La policía desea que prestes especial atención a cualquier información que pueda probar que Joe Jacobs estaba vendiendo drogas en otras escuelas de secundarias además de en Smith Hill. También les gustaría que intentes determinar, si es posible, quién es el proveedor de Joe Jacob.\r\n\r\nLa fianza publicada de Jacob se fijó en $ 10,000.00. Con miedo por si pudiese salir de la ciudad, a la policía le gustaría encerrarlo lo antes posible. Para hacerlo, la policía te ha pedido que tengas los resultados completos y presentados antes del 25 de octubre de 2002. Por favor, proporciona a la policía un caso sólido que consista en el que presentes tus hallazgos en base a las preguntas planteadas, dónde se encuentran los hallazgos en el disco, los procesos y las técnicas utilizadas, y cualquier acción que el sospechoso haya realizado para eliminar, ocultar y/o alterar intencionadamente datos en el disquete. ¡Buena suerte!\r\n\r\nLos nombres, ubicaciones y situaciones presentados son ficticios. Cualquier parecido con cualquier nombre, ubicación y/o situación es pura coincidencia.\r\n\r\nSu misión es analizar un disquete recuperado y responder a las siguientes preguntas. La imagen dd del disquete recuperado se puede descargar usando el boton de descarga.\r\n\r\nMD5 (image.zip) = <b>b676147f63923e1f428131d59b1d6a72</b>\r\n<br>\r\n<i>Nota: se debe verificar la integridad del archivo de descarga.</i>\r\n\r\n</div>\r\n\r\n¿Quién es el proveedor de marihuana de Joe Jacob y cuál es su dirección?\r\n\r\n\r\n<br><br>FORMATO:<br><sub>CTF{NAME_ADDRESS}</sub><br><br>Ejemplo:<br><sub><br>John Doe<br><br>9122 Hummle St Av 7<br>Chicago, CH 270</sub><br><sub>CTF{John_Doe_9122_Hummle_St_Av_7_Chicago,_CH_270}</sub>\r\n', 'Medium', 'CTF{Jimmy_Jungle_626_Jungle_Ave_Apt_2_Jungle,_NY_11111}', 'misc', 'Aún no hay pistas disponibles', 'https://github.com/j0rd1s3rr4n0/ForenseTech-Challenges/raw/refs/heads/main/caso3/image.zip'),
	(3, 'Forensic Case 3', 150, '<h5>Caso Joe Jacobs - Análisis del disquete</h5>\r\n<br><h6><b>Escenario:</b></h6>\r\n<div style="text-align:justify;">\r\nJoe Jacobs, de 28 años, fue arrestado ayer acusado de vender drogas ilegales a estudiantes de secundaria. Jacobs se acercó a un oficial de policía local que se hacía pasar por un estudiante de secundaria en el estacionamiento de Smith Hill High School. Jacobs le preguntó al policía encubierto si le gustaría comprar un poco de marihuana. Antes de que el policía encubierto pudiera responder, Jacobs sacó un poco de su bolsillo y se lo mostró al oficial. Jacobs le dijo al oficial: "¡Miren esto, los más expertos no podrían cultivarlo mejor! Mi proveedor no solo me lo vende directamente, sino que él mismo lo cultiva.¨\r\n\r\nSe ha visto a Jacobs en numerosas ocasiones pasando el rato en varios estacionamientos locales de la escuela secundaria alrededor de las 2:30 p.m., la hora en que generalmente termina la escuela. Oficiales escolares de varias escuelas secundarias han llamado a la policía sobre la presencia de Jacobs en su escuela y notaron un aumento en el consumo de drogas entre los estudiantes, desde su llegada.\r\n\r\nLa policía necesita tu ayuda. Quieren intentar determinar si Joe Jacobs ha estado vendiendo drogas a estudiantes en otras escuelas además de en Smith Hill. El problema es que ningún estudiante se presentará y ayudará a la policía. Según el comentario de Joe sobre los más expertos, la policía está interesada en encontrar al proveedor/productor de marihuana de Joe Jacob.\r\n\r\nJacobs ha negado vender drogas en cualquier otra escuela además de en Smith Hill y se niega a proporcionar a la policía el nombre de su proveedor /productor de drogas. Jacobs también se niega a corroborar la declaración que hizo al oficial encubierto justo antes de su arresto. Al emitir una orden de allanamiento y registrar la casa del sospechoso, la policía pudo obtener una pequeña cantidad de marihuana. La policía también confiscó un solo disquete, pero no había ordenadores ni otros dispositivos en la casa.\r\n\r\nLa policía ha tomado una imagen del disquete del sospechoso y te ha proporcionado una copia. A ellos les gustaría que examines el disquete y proporciones respuestas a las siguientes preguntas. La policía desea que prestes especial atención a cualquier información que pueda probar que Joe Jacobs estaba vendiendo drogas en otras escuelas de secundarias además de en Smith Hill. También les gustaría que intentes determinar, si es posible, quién es el proveedor de Joe Jacob.\r\n\r\nLa fianza publicada de Jacob se fijó en $ 10,000.00. Con miedo por si pudiese salir de la ciudad, a la policía le gustaría encerrarlo lo antes posible. Para hacerlo, la policía te ha pedido que tengas los resultados completos y presentados antes del 25 de octubre de 2002. Por favor, proporciona a la policía un caso sólido que consista en el que presentes tus hallazgos en base a las preguntas planteadas, dónde se encuentran los hallazgos en el disco, los procesos y las técnicas utilizadas, y cualquier acción que el sospechoso haya realizado para eliminar, ocultar y/o alterar intencionadamente datos en el disquete. ¡Buena suerte!\r\n\r\nLos nombres, ubicaciones y situaciones presentados son ficticios. Cualquier parecido con cualquier nombre, ubicación y/o situación es pura coincidencia.\r\n\r\nSu misión es analizar un disquete recuperado y responder a las siguientes preguntas. La imagen dd del disquete recuperado se puede descargar usando el boton de descarga.\r\n\r\n<h6>Objetivo de la investigación:</h6>\r\nSe procederá a examinar el disquete confiscado de la residencia de Joe Jacobs con el fin de encontrar pruebas que lo vinculen con la venta de sustancias estupefacientes en otras instituciones de educación secundaria, así como para identificar a su proveedor.\r\n\r\n<b>Información del caso:</b>\r\nSospechoso: Joe Jacobs, de 28 años.\r\nAcusación: Se imputa al acusado la venta de sustancias estupefacientes a estudiantes de educación secundaria.\r\nEvidencia: Se ha confiscado un disquete en la residencia de Jacobs, que se considera relevante para la investigación del caso.\r\n\r\n</div>\r\n\r\n\r\n¿Cuál es la contraseña del archivo encriptado?\r\n¿Cuál fue la última escuela que Joe Jacobs visitó?\r\n¿Qué modelo de impresora tenía?\r\n<br><br>FORMATO:<br><sub>CTF{CONTRASEÑA_MES_AÑO_ESCUELA_IMPRESORA}</sub><br><br>Ejemplo:<br><ul>\r\n    <li>supersecret</li>\r\n    <li>IES Sagrada Familia</li>\r\n    <li>August 2024</li>\r\n    <li>EPSON Envy D981</li>\r\n</ul>\r\n<br><br><sub>CTF{supersecret_August_2024_IES_Sagrada_Familia_EPSON_Envy_D981}</sub>\r\n', 'Hard', 'CTF{goodtimes_June_2002_Leetch_High_School_HP_DeskJet_970Cxi}', 'misc', 'Si la contraseña quieres conocer, a fondo y desde la base tienes que ver.', 'https://github.com/j0rd1s3rr4n0/ForenseTech-Challenges/raw/refs/heads/main/caso3/image.zip'),
	(4, 'ExtractMyPassword Level 1', 50, '¿Podrás encontrar la flag?', 'Easy', 'CTF{Est0EsUn4ClaveAlf4Num3ric4}', 'reversing', 'A veces para tener una mejor comprension se tiene que poner uno del revés', 'https://github.com/j0rd1s3rr4n0/ExtractMyPassword/raw/refs/heads/main/Compiled/easy.exe'),
	(5, 'ExtractMyPassword Level 2', 100, '¿Podrás encontrar la flag?', 'Medium', 'CTF{Est0EsUn4ClaveAlf4Num3ric4XORizada}', 'reversing', 'Hay etapas que involucran números, operaciones con claves y una codificación bastante conocida. Cada paso tiene sentido si deshaces el proceso en orden inverso.', 'https://github.com/j0rd1s3rr4n0/ExtractMyPassword/raw/refs/heads/main/Compiled/medium.exe'),
	(6, 'ExtractMyPassword Level 3', 150, '¿Podrás encontrar la flag?', 'Hard', 'CTF{Est0EsUn4ClaveAlf4Num3ric4XORizadaRot13i0fuscada}', 'reversing', 'Un mensaje oculto en bits, transformado con una clave secreta. Cada capa cubre lo anterior, solo aquellos que sepan cómo deshacerlo verán la verdad. ¿Podrás descubrir el camino?', 'https://github.com/j0rd1s3rr4n0/ExtractMyPassword/raw/refs/heads/main/Compiled/hard.exe'),
	(10, 'Easter Egg 1', 100, 'Joker CSS', 'Easy', 'CTF{8fcc4f47a8f0261fb3d93fc62af26038}', 'easteregg', '0', '0'),
	(11, 'Easter Egg 2', 100, 'Joker html <CTF>', 'Easy', 'CTF{2613d5335779564f3dd7c44e5b6bebdb}', 'easteregg', '0', '0'),
	(12, 'Easter Egg 3', 100, 'Joker html <input>', 'Easy', 'CTF{26cff0571c90ee821fe50a106e2f4d73}', 'easteregg', '0', '0'),
	(13, 'Easter Egg 4', 100, 'Joker JS', 'Easy', 'flag{227346e1ab43adc8a9faab8491a307b7}', 'easteregg', '0', '0'),
	(14, 'Easter Egg 5', 150, 'Joker JS Med', 'Medium', 'CTF{1aa6d80a7ff6df5c57dad90ed5c37a09}', 'easteregg', '0', '0'),
	(15, 'Easter Egg 6', 100, 'Joker robots.txt', 'Easy', 'CTF{697c8a99f6ca116a4f0bd76c3ac04fb7}', 'easteregg', '0', '0');

-- Volcando estructura para tabla accounts.machines
CREATE TABLE IF NOT EXISTS machines (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  ip_address varchar(45) NOT NULL,
  os enum('Windows','Linux','MacOs','Other') DEFAULT 'Linux',
  description text,
  points int NOT NULL DEFAULT '0',
  user_flag varchar(255) NOT NULL,
  root_flag varchar(255) NOT NULL,
  hint1 varchar(255) NOT NULL,
  hint2 varchar(255) NOT NULL,
  hint3 varchar(255) NOT NULL,
  PRIMARY KEY (id)
);

-- Volcando datos para la tabla accounts.machines: ~1 rows (aproximadamente)
DELETE FROM machines;
INSERT INTO machines (id, name, ip_address, os, description, points, user_flag, root_flag, hint1, hint2, hint3) VALUES
	(1, 'VulnWeb', '172.17.0.3', 'Linux', 'Could you audit my first personal page? I will give you all my points if you become an administrator.', 100, 'flag{293aea10df6464942b3cce268c9c75af}', 'flag{570dd991217570f3a8dc417d00372183}', 'How safe is it to allow users to upload files?', 'Follow the clues related to the credentials to access the user account', 'Look for files with unusual permissions.'),
  (2, 'HackerManLand', '172.17.0.4', 'Linux', 'The alerts have been triggered, a hacker has breached the system, and we have logged their IP address. Will you be able to neutralize the threat? Access their systems and investigate the extent of their operations.', 100, 'CTF{user_flag_hackme}', 'CTF{root_flag_hackme}', 'Intenta acceder como usuario.', 'Busca archivos con permisos inusuales.', 'Revisa los servicios que están corriendo.');
  

-- Volcando estructura para tabla accounts.submissions
CREATE TABLE IF NOT EXISTS submissions (
  id int NOT NULL AUTO_INCREMENT,
  team_name varchar(100) NOT NULL,
  challenge_type enum('Machine','Challenge') NOT NULL,
  type enum('root','user','challenge','web','reversing','steganography','pwning','cryptography','misc','other','easteregg') NOT NULL,
  challenge_id int NOT NULL,
  submitted_flag varchar(255) NOT NULL,
  is_correct tinyint(1) NOT NULL,
  submission_time datetime NOT NULL,
  PRIMARY KEY (id)
);

-- Volcando datos para la tabla accounts.submissions: ~0 rows (aproximadamente)
DELETE FROM submissions;

-- Volcando estructura para tabla accounts.users
CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL AUTO_INCREMENT,
  reciept_id varchar(255) NOT NULL,
  team_name varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  solemnly_swear tinyint(1) NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

-- Volcando datos para la tabla accounts.users: ~0 rows (aproximadamente)
DELETE FROM users;

