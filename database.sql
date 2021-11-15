CREATE TABLE usuarios(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	nombre VARCHAR(60),
	apellidos VARCHAR(60),
	email VARCHAR(30),
	telefono VARCHAR(10),
	admin TINYINT(1),
	confirmado TINYINT(1),
	token VARCHAR(15)
);

CREATE TABLE servicios(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	nombre VARCHAR(60),
	precio DECIMAL(5,2)
);

INSERT INTO `servicios` (`id`, `nombre`, `precio`) VALUES
(null, 'Corte de Cabello Mujer', 90.00),
(null, 'Corte de Cabello Hombre', 80.00),
(null, 'Corte de Cabello Niño', 60.00),
(null, 'Peinado Mujer', 80.00),
(null, 'Peinado Hombre', 60.00),
(null, 'Peinado Niño', 60.00),
(null, 'Corte de Barba', 60.00),
(null, 'Tinte Mujer', 300.00),
(null, 'Uñas', 400.00),
(null, 'Lavado de Cabello', 50.00),
(null, 'Tratamiento Capilar', 150.00);

CREATE TABLE citas(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	idUsuario INT(11),
	fecha DATE,
	hora TIME,
	FOREIGN KEY (idUsuario) REFERENCES usuarios (id)
);

CREATE TABLE citasServicios(
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	idCita INT(11),
    idServicios INT(11),
	FOREIGN KEY (idCita) REFERENCES citas (id),
    FOREIGN KEY (idServicios) REFERENCES servicios (id)
);