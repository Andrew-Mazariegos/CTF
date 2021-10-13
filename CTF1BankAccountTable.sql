DROP DATABASE IF EXISTS CTF1Accounts;
CREATE DATABASE CTF1Accounts;

USE CTF1Accounts;

CREATE TABLE `Users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(256) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC));
  
CREATE TABLE `Balances` (
	balance_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	user_id INT NOT NULL,
    balance DECIMAL(15,2) NOT NULL,
    -- constraint to make sure data stays consistent, IDs must exist in both tables to update/insert, etc.
    FOREIGN KEY fk1(user_id) REFERENCES Users(user_id)
);
