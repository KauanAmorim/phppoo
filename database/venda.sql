CREATE TABLE venda (
    id INTEGER AUTO_INCREMENT PRIMARY KEY NOT NULL,
    data_venda DATE NOT NULL
);

CREATE TABLE item_venda (
    id INTEGER PRIMARY KEY NOT NULL,
    id_produto INTEGER NOT NULL,
    id_venda INTEGER NOT NULL,
    quantidade FLOAT,
    preco FLOAT,
    FOREIGN KEY (id_produto) REFERENCES produto(id),
    FOREIGN KEY (id_venda) REFERENCES venda(id)
);

SELECT * FROM venda;
SELECT * FROM item_venda;

