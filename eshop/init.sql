CREATE TABLE products(id INTEGER NOT NULL AUTO_INCREMENT, img VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, price INTEGER NOT NULL, PRIMARY KEY(id));
INSERT INTO products(img, name, price) VALUES('&#127820', 'banana', '29');
INSERT INTO products(img, name, price) VALUES('&#127823', 'apple', '39');
INSERT INTO products(img, name, price) VALUES('&#127817', 'watermelon', '59');
INSERT INTO products(img, name, price) VALUES('&#129364', 'potato', '19');

CREATE TABLE users(id INTEGER NOT NULL AUTO_INCREMENT, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id));

CREATE TABLE orders(id INTEGER NOT NULL AUTO_INCREMENT, id_user INTEGER NOT NULL, PRIMARY KEY(id), FOREIGN KEY(id_user) REFERENCES users(id));

CREATE TABLE orderedItems(id INTEGER NOT NULL AUTO_INCREMENT, id_order INTEGER NOT NULL, id_product INTEGER NOT NULL,
 quantity INTEGER NOT NULL, pricePerPiece INTEGER NOT NULL, PRIMARY KEY(id), FOREIGN KEY(id_order) REFERENCES orders(id), FOREIGN KEY(id_product) REFERENCES products(id));