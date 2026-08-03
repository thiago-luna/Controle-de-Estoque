-- database.sql
-- Sistema de Controle de Estoque
-- Baseado no DER definido na Entrega Parcial 1

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255)
);

CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cnpj VARCHAR(20),
    telefone VARCHAR(20),
    email VARCHAR(150)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil ENUM('administrador', 'usuario_comum') NOT NULL DEFAULT 'usuario_comum'
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao VARCHAR(255),
    categoria_id INT,
    fornecedor_id INT,
    quantidade INT NOT NULL DEFAULT 0,
    preco_unitario DECIMAL(10,2) NOT NULL DEFAULT 0,
    estoque_minimo INT NOT NULL DEFAULT 0,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id)
);

CREATE TABLE movimentacoes_estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    quantidade INT NOT NULL,
    data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observacao VARCHAR(255),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Dados de exemplo (opcional, útil para testar a partir da Entrega Parcial 3)

-- Usuários de teste (Entrega Parcial 5 - Login/Perfis).
-- Senha de ambos: "admin123" e "usuario123", respectivamente.
-- O hash abaixo foi gerado com password_hash() (bcrypt) — nunca grave
-- senha em texto puro no banco.
INSERT INTO usuarios (nome, email, senha_hash, perfil) VALUES
    ('Administrador', 'admin@estoque.com', '$2y$10$3gVmD7OG39CtRydYKhwEqO7CMZ3m1/vY8f5tU3eRG1hdj.0FQuGX.', 'administrador'),
    ('Usuário Comum', 'usuario@estoque.com', '$2y$10$5KqRmoA9RleQ1D4WRIROEerc1cnY25hdBVjh0VHA0u6.Tndq53n3S', 'usuario_comum');

INSERT INTO categorias (nome, descricao) VALUES
    ('Ferragens', 'Parafusos, porcas e afins'),
    ('Elétrica', 'Fios, cabos e componentes elétricos'),
    ('EPI', 'Equipamentos de proteção individual');

INSERT INTO fornecedores (nome, cnpj, telefone, email) VALUES
    ('Distribuidora ABC', '12.345.678/0001-90', '(87) 3333-1111', 'contato@abc.com'),
    ('Fornecedor XYZ', '98.765.432/0001-10', '(87) 3333-2222', 'vendas@xyz.com');

INSERT INTO produtos (nome, descricao, categoria_id, fornecedor_id, quantidade, preco_unitario, estoque_minimo) VALUES
    ('Parafuso M6', 'Parafuso sextavado M6', 1, 1, 1200, 0.15, 100),
    ('Cabo Flexível 2,5mm', 'Cabo elétrico flexível', 2, 2, 300, 3.20, 50),
    ('Luva de Proteção', 'Luva de raspa de couro', 3, 1, 45, 12.90, 20);
