use ExpenseTracker;


DROP TABLE Transaction;
DROP TABLE Description;
DROP TABLE Category;
DROP TABLE User;

CREATE TABLE User
(
    UserID int PRIMARY KEY auto_increment,
    UserName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Pswd VARCHAR(100) NOT NULL
);

CREATE TABLE Category
(
    CategoryID int PRIMARY KEY auto_increment,
    CategoryName VARCHAR(50)
);

CREATE TABLE Description
(
    DescriptionID int PRIMARY KEY auto_increment,
    UserID int,
    CategoryID int NOT NULL,
    Description VARCHAR(50),
    foreign KEY (UserID) references User(UserID),
    foreign KEY (CategoryID) references Category(CategoryID)
);

CREATE TABLE Transaction 
(
    TransactionID int PRIMARY KEY auto_increment,
    TransactionDate DATE NOT NULL,
    TransactionDescription VARCHAR(50),
    TransactionValue INT NOT NULL,
    CategoryID int,
    TransactionOwnerID int NOT NULL,
    foreign KEY (CategoryID) references Category(CategoryID),
    foreign KEY (TransactionOwnerID) references User(UserID)
);


INSERT INTO Category(CategoryName) VALUES ("Food");
INSERT INTO Category(CategoryName) VALUES ("Education");
INSERT INTO Category(CategoryName) VALUES ("Amusement");
INSERT INTO Category(CategoryName) VALUES ("Health");
INSERT INTO Category(CategoryName) VALUES ("Clothes");
INSERT INTO Category(CategoryName) VALUES ("Household");
INSERT INTO Category(CategoryName) VALUES ("Sport");
INSERT INTO Category(CategoryName) VALUES ("Scholarships");
INSERT INTO Category(CategoryName) VALUES ("Salary");
INSERT INTO Category(CategoryName) VALUES ("Other Expenses");
INSERT INTO Category(CategoryName) VALUES ("Other incomes");

