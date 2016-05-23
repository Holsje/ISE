DELETE FROM Person
DELETE FROM PersonTypeOfPerson
DELETE FROM PersonType
DELETE FROM GeneralManager
DELETE FROM CongressManager
DELETE FROM Location
DELETE FROM Congress
DELETE FROM Subject
DELETE FROM CongressManagerOfCongress 
DELETE FROM Visitor
DELETE FROM CongressManager

/*
DBCC CHECKIDENT ('Person', RESEED, 0);
*/

INSERT INTO PersonType VALUES ('Algemene beheerder'),
							  ('Congresbeheerder'),
							  ('Bezoeker'),
							  ('Spreker')

INSERT INTO Person VALUES ('Erik', 'Evers', 'erikevers1996@gmail.com', '0613334002'),
						  ('Daniël', 'de Jong', 'danieldejong@hotmail.com', '0612345678'),
						  ('Niels', 'Bergervoet', 'nielsbergervoet@hotmail.com', '0654897852'),
						  ('Enzo', 'van Arum', 'enzovanarum@hotmail.com', '0678945236'),
						  ('Onno', 'Hols', 'onnohols@hotmail.com', '0694858595');

INSERT INTO PersonTypeOfPerson VALUES (1, 'Algemene beheerder'),
									  (2, 'Algemene beheerder'),
									  (3, 'Algemene beheerder'),
									  (4, 'Algemene beheerder'),
									  (5, 'Congresbeheerder'),
									  (1, 'Spreker'),
									  (2, 'Spreker'),
									  (3, 'Spreker'),
									  (4, 'Spreker'),
									  (5, 'Spreker');

INSERT INTO GeneralManager VALUES (1, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
								  (2, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
								  (3, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
								  (4, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO CongressManager VALUES (5, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO [Subject]([Subject]) VALUES ('DataModelling'),('DataImplementation');

INSERT INTO Location VALUES ('HAN Campus Arnhem', 'Arnhem');

INSERT INTO Congress VALUES (1, 'HAN Campus Arnhem', 'Arnhem', 'DataModelling', 'HAN Congress', GETDATE(), DATEADD(DAY, 1, GETDATE()), 20.50, 0),
							(2, 'HAN Campus Arnhem', 'Arnhem', 'DataImplementation', 'HAN Congress 2', GETDATE(), DATEADD(DAY, 1, GETDATE()), 20.50, 0);

INSERT INTO CongressManagerOfCongress VALUES (5, 1),
											 (5, 2);

INSERT INTO Speaker VALUES(1, 'Erik Evers is een ervaren PHP en JavaScript developer en komt vertellen over shit.', 'img/Speakers/Speaker1.jpg'),
						  (2, 'Daniel de Jong is een ervaren projectleider die veel projecten tot een goed einde heeft gebracht.', 'img/Speakers/Speaker2.jpg'),
						  (3, 'Niels Bergervoet is een ervaren conflictoplosser met zijn best practice enz enz enz.', 'img/Speakers/Speaker3.jpg'),
						  (4, 'Enzo van Arum is de informatieanalist die alles regelt', 'img/Speakers/Speaker4.jpg'),
						  (5, 'Onno Hols is een ervaren PHP en JavaScript developer en houdt van vakantie', 'img/Speakers/Speaker5.jpg');
