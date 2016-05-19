DELETE FROM PersonType
DELETE FROM Person
DELETE FROM PersonTypeOfPerson
DELETE FROM GeneralManager
DELETE FROM CongressManager
DELETE FROM Subject
DELETE FROM Location
DELETE FROM Congress
DELETE FROM CongressManagerOfCongress 

INSERT INTO PersonType VALUES ('Algemene beheerder'),
							  ('Congresbeheerder')

INSERT INTO Person VALUES (1, 'Erik', 'Evers', 'erikevers1996@gmail.com', '0613334002'),
						  (2, 'Daniël', 'de Jong', 'danieldejong@hotmail.com', '0612345678'),
						  (3, 'Niels', 'Bergervoet', 'nielsbergervoet@hotmail.com', '0654897852'),
						  (4, 'Enzo', 'van Arum', 'enzovanarum@hotmail.com', '0678945236'),
						  (5, 'Onno', 'Hols', 'onnohols@hotmail.com', '0694858595');


INSERT INTO PersonTypeOfPerson VALUES (1, 'Algemene beheerder'),
									  (2, 'Algemene beheerder'),
									  (3, 'Algemene beheerder'),
									  (4, 'Algemene beheerder'),
									  (5, 'Congresbeheerder');

INSERT INTO GeneralManager VALUES (1, 'ErikEvers' , 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
								  (2, 'DaniëldeJong', 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
								  (3, 'NielsBergervoet', 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
								  (4, 'EnzoVanArum', 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO CongressManager VALUES (5, 'OnnoHols', 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');


INSERT INTO [Subject]([Subject]) VALUES ('DataModelling'),('DataImplementation');

INSERT INTO Location VALUES ('HAN Campus Arnhem', 'Arnhem');

INSERT INTO Congress VALUES (1, 'HAN Campus Arnhem', 'Arnhem', 'DataModelling', 'HAN Congress', GETDATE(), DATEADD(DAY, 1, GETDATE()), 20.50, 0),
							(2, 'HAN Campus Arnhem', 'Arnhem', 'DataImplementation', 'HAN Congress 2', GETDATE(), DATEADD(DAY, 1, GETDATE()), 20.50, 0);

INSERT INTO CongressManagerOfCongress VALUES (5, 1),
											 (5, 2);