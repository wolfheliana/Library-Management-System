--Create tables--------------------------------------------
CREATE TABLE books (
    isbn CHAR(13) NOT NULL PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(80) NOT NULL,
    genre VARCHAR(25) NOT NULL,
    releasedate DATE NOT NULL,
    quantity INT NOT NULL,
    checkedout INT NOT NULL DEFAULT 0,
    CHECK (checkedout <= quantity)
);

CREATE TABLE copies (
    barcode CHAR(4) NOT NULL PRIMARY KEY,
    isbn CHAR(13) NOT NULL,
    FOREIGN KEY (isbn) REFERENCES books(isbn)
);

CREATE TABLE cards (
    id CHAR(6) NOT NULL PRIMARY KEY, 
    name VARCHAR(80) NOT NULL,
    address VARCHAR(200) NOT NULL,
    checkoutcount INT NOT NULL DEFAULT 0 CHECK (checkoutcount <= 10)
);

CREATE TABLE currentcheckouts(
    barcode CHAR(4) NOT NULL PRIMARY KEY,
    id CHAR(6) NOT NULL,
    checkoutdate DATE NOT NULL,
    FOREIGN KEY (barcode) REFERENCES copies(barcode),
    FOREIGN KEY (id) REFERENCES cards(id)
);

CREATE TABLE pastcheckouts(
    barcode CHAR(4) NOT NULL,
    id CHAR(6) NOT NULL,
    checkoutdate DATE NOT NULL,
    returndate DATE NOT NULL,
    PRIMARY KEY (barcode, checkoutdate),
    FOREIGN KEY (id) REFERENCES cards(id)
);
---------------------------------------------------------------

--Populate database--------------------------------------------
INSERT INTO books(isbn, title, author, genre, releasedate, quantity)
VALUES
('9780452262935', '1984', 'George Orwell', 'Dystopian', '1948-06-08', 2),
('9780446310789', 'To Kill A Mockingbird', 'Harper Lee', 'Fiction', '1960-07-11', 1),
('9780743273565', 'The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', '1925-04-10', 1),
('9781451673319', 'Fahrenheit 451', 'Ray Bradbury', 'Dystopian', '1953-10-19', 2),
('9780399501487', 'Lord of the Flies', 'William Golding', 'Fiction', '1954-09-17', 1), --#5
('9780679720201', 'The Stranger', 'Albert Camus', 'Philosophical Fiction', '1942-05-19', 1),
('9780008108281', 'The Hobbit', 'J.R.R. Tolkien', 'Fantasy', '1937-09-21', 3),
('9780142407332', 'The Outsiders', 'S.E. Hinton', 'Young Adult', '1967-04-24', 1),
('9780143039983', 'The Haunting of Hill House', 'Shirley Jackson', 'Horror', '1954-10-16', 1),
('9780330258647', 'The Hitchhikers Guide to the Galaxy', 'Douglas Adams', 'Sci-Fi', '1979-10-12', 1), --#10
('9780805210576', 'The Metamorphosis', 'Franz Kafka', 'Philosophical Fiction', '1915-10-01', 2),
('9780345806567', 'Giovannis Room', 'James Baldwin', 'Fiction', '1956-01-01', 1),
('9781400031702', 'The Secret History', 'Donna Tartt', 'Fiction', '1992-09-16', 2),
('9781400031696', 'The Little Friend', 'Donna Tartt', 'Fiction', '2002-10-22', 1),
('9780316055444', 'The Goldfinch', 'Donna Tartt', 'Fiction', '2013-09-23', 3), --#15
('9780375842207', 'The Book Thief', 'Markus Zusak', 'Fiction', '2005-03-14', 1),
('9781594631931', 'The Kite Runner', 'Khaled Hosseini', 'Fiction', '2003-05-29', 1),
('9780156012195', 'The Little Prince', 'Antoine de Saint-Exupery', 'Philosophical Fiction', '1943-04-06', 1),
('9780141441146', 'Jane Eyre', 'Charlotte Bronte', 'Fiction', '1847-10-19', 3),
('9780141439556', 'Wuthering Heights', 'Emily Bronte', 'Fiction', '1847-11-24', 1), --#20
('9780063021426', 'Babel', 'R. F. Kuang', 'Fiction', '2022-08-23', 1),
('9781250095299', 'If We Were Villains', 'M. L. Rio', 'Mystery', '2017-04-11', 1),
('9781524759780', 'Recursion', 'Blake Crouch', 'Sci-Fi', '2019-07-11', 1),
('9780451450524', 'The Last Unicorn', 'Peter S. Beagle', 'Fantasy', '1968-01-01', 2),
('9780141439570', 'The Picture of Dorian Gray', 'Oscar Wilde', 'Philosophical Fiction', '1890-07-01', 2), --#25
('9780140449136', 'Crime and Punishment', 'Fyodor Dostoevsky', 'Philosophical Fiction', '1866-12-01', 1),
('9780143131847', 'Frankenstein', 'Mary Shelley', 'Sci-Fi', '1818-01-01', 2),
('9780141196886', 'Dracula', 'Bram Stoker', 'Horror', '1897-05-26', 1),
('9780544336261', 'The Giver', 'Lois Lowry', 'Young Adult', '1993-04-01', 1),
('9780156027328', 'Life of Pi', 'Yann Martel', 'Philosophical Fiction', '2001-09-11', 2), --#30
('9780062073488', 'And Then There Were None', 'Agatha Christie', 'Mystery', '1939-11-06', 1), 
('9781840226447', 'The King in Yellow', 'Robert W. Chambers', 'Weird Fiction', '1895-01-01', 1),
('9780345404473', 'Do Androids Dream of Electric Sheep?', 'Phillip K. Dick', 'Sci-Fi', '1968-03-01', 1),
('9780380789658', 'Death is A Lonely Business', 'Ray Bradbury', 'Fiction', '1985-10-12', 1),
('9780307887443', 'Ready Player One', 'Ernest Cline', 'Sci-Fi', '2011-08-16', 1), --#35
('9780593321447', 'Sea of Tranquility', 'Emily St. John Mandel', 'Sci-Fi', '2022-04-05', 1),
('9780547773742', 'A Wizard of Earthsea', 'Ursula K. LeGuin', 'Fantasy', '1968-11-01', 1),
('9781982150921', 'Tender is the Flesh', 'Agustina Bazterrica', 'Dystopian', '2020-08-04', 1),
('9780156439619', 'If on a Winters Night a Traveler', 'Italo Calvino', 'Metafiction', '1979-01-01', 1),
('9781635575637', 'Piranesi', 'Susanna Clarke', 'Fantasy', '2020-09-28', 1), --#40
('9780375703768', 'House of Leaves', 'Mark Z. Danielewski', 'Metafiction', '2000-03-07', 1),
('9780765387561', 'The Invisible Life of Addie LaRue', 'V. E. Schwab', 'Fantasy', '2020-10-06', 1),
('9780307744432', 'The Night Circus', 'Erin Morgenstern', 'Fantasy', '2012-07-03', 1),
('9780061478789', 'Howls Moving Castle', 'Diana Wynne Jones', 'Young Adult', '1986-04-14', 1),
('9780060838676', 'Their Eyes Were Watching God', 'Zora Neale Hurston', 'Fiction', '1937-09-13', 1), --#45
('9781400033430', 'Sula', 'Toni Morrison', 'Fiction', '1973-11-01', 1),
('9780375753770', 'Heart of Darkness', 'Joseph Conrad', 'Philosophical Fiction', '1899-04-01', 1),
('9781501144264', 'The Long Walk', 'Stephen King', 'Dystopian', '1979-07-03', 1),
('9780140177398', 'Of Mice and Men', 'John Steinbeck', 'Fiction', '1937-02-06', 1),
('9781250048677', 'A Street Cat Named Bob', 'James Bowen', 'Nonfiction', '2013-07-30', 1) --#50
;

INSERT INTO copies(barcode, isbn)
VALUES
('B001', '9780452262935'), --1984
('B002', '9780452262935'), --1984
('B003', '9780446310789'), --To Kill a Mockingbird
('B004', '9780743273565'), --The Great Gatsby
('B005', '9781451673319'), --Fahrenheit 451
('B006', '9781451673319'), --Fahrenheit 451
('B007', '9780399501487'), --Lord of the Flies
('B008', '9780679720201'), --The Stranger
('B009', '9780008108281'), --The Hobbit
('B010', '9780008108281'), --The Hobbit
('B011', '9780008108281'), --The Hobbit
('B012', '9780142407332'), --The Outsiders
('B013', '9780143039983'), --The Haunting of Hill House
('B014', '9780330258647'), --The Hitchikers Guide to the Galaxy
('B015', '9780805210576'), --The Metamorphosis
('B016', '9780805210576'), --The Metamorphosis
('B017', '9780345806567'), --Giovannis Room
('B018', '9781400031702'), --The Secret History
('B019', '9781400031702'), --The Secret History
('B020', '9781400031696'), --The Little Friend
('B021', '9780316055444'), --The Goldfinch
('B022', '9780316055444'), --The Goldfinch
('B023', '9780316055444'), --The Goldfinch
('B024', '9780375842207'), --The Book Thief
('B025', '9781594631931'), --The Kite Runner
('B026', '9780156012195'), --The Little Prince
('B027', '9780141441146'), --Jane EYre
('B028', '9780141441146'), --Jane EYre
('B029', '9780141441146'), --Jane EYre
('B030', '9780141439556'), --Wuthering Heights
('B031', '9780063021426'), --Babel
('B032', '9781250095299'), --If We Were Villains
('B033', '9781524759780'), --Recursion
('B034', '9780451450524'), --The Last Unicorn
('B035', '9780451450524'), --The Last Unicorn
('B036', '9780141439570'), --The Picture of Dorian Gray
('B037', '9780141439570'), --The Picture of Dorian Gray
('B038', '9780140449136'), --Crime and Punishment
('B039', '9780143131847'), --Frankenstein
('B040', '9780143131847'), --Frankenstein
('B041', '9780141196886'), --Dracula
('B042', '9780544336261'), --The Giver
('B043', '9780156027328'), --Life of Pi
('B044', '9780156027328'), --Life of Pi
('B045', '9780062073488'), --And Then There Were None
('B046', '9781840226447'), --The King In Yellow
('B047', '9780345404473'), --Do Androids Dream of Electric Sheep
('B048', '9780380789658'), --Death is a Lonely Business
('B049', '9780307887443'), --Ready Player One
('B050', '9780593321447'), --Sea of Tranquility
('B051', '9780547773742'), -- A Wizard of EarthSea
('B052', '9781982150921'), --Tender is the Flesh
('B053', '9780156439619'), --If on a Winters Night a Traveler
('B054', '9781635575637'), --Piranesi
('B055', '9780375703768'), --House of Leaves
('B056', '9780765387561'), --The Invisible Life of Addie LaRue
('B057', '9780307744432'), --The Night Circus
('B058', '9780061478789'), --Howls Moving Castle
('B059', '9780060838676'), --Their Eyes Were Watching God
('B060', '9781400033430'), --Sula
('B061', '9780375753770'), --Heart if Darkness
('B062', '9781501144264'), --The Long Walk
('B063', '9780140177398'), --Of Mice and Men
('B064', '9781250048677') -- A Street Cat Named Bob
;
---------------------------------------------------------------