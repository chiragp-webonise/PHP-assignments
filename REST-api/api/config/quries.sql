CREATE TABLE Categories(
id SERIAL PRIMARY KEY,
name varchar(100) NOT NULL,
description varchar NOT NULL,
tax float NOT NULL
);

CREATE TABLE Products(
id SERIAL PRIMARY KEY,
name varchar(100) NOT NULL,
description varchar NOT NULL,
price float NOT NULL,
discount float NOT NULL,
category_id int REFERENCES Categories(id)
);

CREATE TABLE Carts(
id SERIAL PRIMARY KEY,
product_id int NOT NULL REFERENCES Products(id),
cutomer_id int NOT NULL REFERENCES Customers(id)
);

CREATE TABLE Customers(
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    address VARCHAR NOT NULL,
    phno VARCHAR NOT NULL
);

INSERT INTO Categories (name, description, tax) VALUES
( 'Fashion', 'Category for anything related to fashion.',5),
( 'Electronics', 'Gadgets, drones and more.',5),
( 'Motors', 'Motor sports and more',5),
( 'Movies', 'Movie products.',5),
( 'Books', 'Kindle books, audio books and more.',5),
( 'Sports', 'Drop into new winter gear.',5);

INSERT INTO Products (name,description,price,discount,category_id) VALUES
( 'LG P880 4X HD', 'My first awesome phone',50000,10,2),
( 'Google Nexus 4', 'My first awesome phone',70000,10,2),
( 'Samsung Galaxy S4', 'My first awesome phone',55000,10,2),
( 'Bench Shirt', 'Movie products.',1200,10,1),
( 'Nike Shoes for Men', 'Nike Shoes.',1700,10,6),
( 'Data Strctures','Audio books and more.',550,10,5);

INSERT INTO Customers (name,address,phno) VALUES
('CHIRAG','PUNE','7874875444'),
('SMIT','PUNE','7845454545'),
( 'SAGAR', 'PUNE','9645785621'),
( 'VINAY', 'PUNE','8965212345'),
( 'VAIBHAV','PUNE','8245612356');

INSERT INTO Carts (product_id,cutomer_id) VALUES
(1,1),
(2,1),
(3,1),
(4,2),
(5,3),
(6,4);