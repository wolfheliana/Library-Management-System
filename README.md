# Library-Management-System
System for managing day-to-day admin tasks of an imaginary library.

## Web-based system emulating a real library management system on a smaller scale through relational database access and manipulation. Database schema normalized in BCNF. Frontend designed for maximum ease of use with simple navigation and UI.
Features include:
-Catalog of all books in library collection with the ability to add or delete individual copies and search by title, author, genre, ISBN-13.
-List of all library cards in system with the ability to register new cards, look up or change personal information of card holders, or print replacement cards (card info opened on blank page).
-Check out books in the collection to individual library card holders (max 10 books may be checked out by one card at once)
-Check books back into the library
-Print an invoice of a library card holder's past and current checkouts and owed overdue fees (higher fees for new releases)

## Languages/tools used:
Database Management System: Postgres
Languages: SQL, HTML, PHP

## How to run:
Download all files.
Run create.sql in postgres to create the database (prepopulated with 50 unique book titles, some have multiple copies)
PHP files may be run on a locally hosted web-server (e.g. localhost:8080)
