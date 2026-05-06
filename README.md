# Library-Management-System
Web-based system emulating a real library management system on a smaller scale through relational database access and manipulation. Database schema normalized in BCNF. Frontend designed for maximum ease of use for day-to-day admin with simple navigation and UI. <br>

Features include: <br>
-Catalog of all books in library collection with the ability to add or delete individual copies and search by title, author, genre, ISBN-13. <br>
-List of all library cards in system with the ability to register new cards, look up or change personal information of card holders, or print replacement cards (card info opened on blank page). <br>
-Check out books in the collection to individual library card holders (constraint: max 10 books may be checked out by an individual card at once) <br>
-Check books back into the library <br>
-Print an invoice of a library card holder's past and current checkouts and owed overdue fees (higher fees for new releases) <br>

## Languages/tools used:
Database Management System: Postgres <br>
Languages: SQL, HTML, PHP

## How to run:
Download all files. <br>
Run create.sql in Postgres to create the database (prepopulated with 50 unique book titles, some have multiple copies) <br>
PHP files may be run on a locally hosted web-server (e.g. localhost:8080)
