CREATE TABLE produto (
    id integer PRIMARY KEY NOT NULL,
    descricao text,
    estoque float,
    preco_custo float,
    preco_venda float,
    codigo_barras text,
    data_cadastro date,
    origem char(1)
);

