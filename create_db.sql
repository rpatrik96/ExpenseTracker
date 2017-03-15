use ExpenseTracker;

DROP TABLE User;
DROP TABLE Category;
DROP TABLE Transaction;

CREATE TABLE User
(
    UserID int PRIMARY KEY auto_increment,
    UserName VARCHAR(100),
    Email VARCHAR(100),
    Pswd VARCHAR(100)
);

CREATE TABLE Category
(
    CategoryID int PRIMARY KEY auto_increment,
    Description VARCHAR(100)
)

CREATE TABLE Transaction 
(
    TransactionID int PRIMARY KEY auto_increment,
    TransactionDate DATE,
    TransactionDescription VARCHAR(100),
    TransactionValue INT,
    CategoryID int,
    TransactionOwnerID int,
    foreign KEY (CategoryID) references Category(CategoryID),
    foreign KEY (TransactionOwnerID) references User(UserID)
);
