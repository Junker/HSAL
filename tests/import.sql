create database hstest; 
USE hstest;
CREATE TABLE test (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(45) NULL, cnt INT NULL DEFAULT 0, PRIMARY KEY (id));

INSERT INTO test(id,name) VALUES(1, 'page 1');
INSERT INTO test(id,name) VALUES(2, 'page 2');
INSERT INTO test(id,name) VALUES(3, 'page 3');
INSERT INTO test(id,name) VALUES(4, 'page 4');
INSERT INTO test(id,name) VALUES(5, 'page 5');
INSERT INTO test(id,name) VALUES(6, 'page 6');

