CREATE TABLE users
(
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf BIGINT NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    telefone BIGINT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    sexo VARCHAR(10) NOT NULL,
    foto VARCHAR(225)
);

CREATE TABLE address (
    id SERIAL PRIMARY KEY,
    cep VARCHAR(9) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero INT NOT NULL,
    bairro VARCHAR(255) NOT NULL,
    cidade VARCHAR(255) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    complemento VARCHAR(255)
);