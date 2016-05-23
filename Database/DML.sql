DELETE FROM SubjectOfEvent
DELETE FROM SubjectOfCongress
DELETE FROM SpeakerOfEvent
DELETE FROM SpeakerOfCongress
DELETE FROM EventOfVisitorOfCongress
DELETE FROM VisitorOfCongress
DELETE FROM EventInRoom
DELETE FROM EventInTrack
DELETE FROM Event
DELETE FROM Track
DELETE FROM CongressManagerOfCongress
DELETE FROM Congress
DELETE FROM Room
DELETE FROM Building
DELETE FROM Location
DELETE FROM [Subject]
DELETE FROM CongressManager
DELETE FROM Visitor
DELETE FROM GeneralManager
DELETE FROM Speaker
DELETE FROM PersonTypeOfPerson
DELETE FROM Person
DELETE FROM PersonType


/*
DBCC CHECKIDENT ('Person', RESEED, 0);
DBCC CHECKIDENT ('Congress', RESEED, 0);
*/

INSERT INTO PersonType (TypeName) VALUES ('Algemene beheerder'),
							  ('Congresbeheerder'),
							  ('Bezoeker'),
							  ('Spreker'),
							  ('Reviewboard')

INSERT INTO Person (FirstName, LastName, MailAddress, PhoneNumber) VALUES ('Erik', 'Evers', 'erikevers1996@gmail.com', '0613334002'),
																					('Dani�l', 'de Jong', 'danieldejong@hotmail.com', '0612345678'),
																					('Niels', 'Bergervoet', 'nielsbergervoet@hotmail.com', '0654897852'),
																					('Enzo', 'van Arum', 'enzovanarum@hotmail.com', '0678945236'),
																					('Onno', 'Hols', 'onnohols@hotmail.com', '0694858595'),
																					('Dave', 'Snowden', 'davesnowden@gmail.com', '0610493923'),
																					('Barry', 'Devlin', 'barrydevlin@gmail.com', '0645785126');

INSERT INTO PersonTypeOfPerson (PersonNo, TypeName) VALUES (1, 'Algemene beheerder'),
														   (2, 'Algemene beheerder'),
														   (3, 'Algemene beheerder'),
														   (4, 'Algemene beheerder'),
														   (5, 'Congresbeheerder'),
														   (1, 'Bezoeker'),
														   (2, 'Bezoeker'),
														   (1, 'Spreker'),
														   (2, 'Spreker'),
														   (3, 'Spreker'),
														   (4, 'Spreker'),
														   (5, 'Spreker'),
														   (6, 'Spreker'),
														   (7, 'Spreker');

INSERT INTO Speaker (PersonNo, Description, PicturePath) VALUES(1, 'Erik Evers is een ervaren PHP en JavaScript developer en komt vertellen over shit.', 'img/Speakers/Speaker1.jpg'),
															   (2, 'Daniel de Jong is een ervaren projectleider die veel projecten tot een goed einde heeft gebracht.', 'img/Speakers/Speaker2.jpg'),
															   (3, 'Niels Bergervoet is een ervaren conflictoplosser met zijn best practice enz enz enz.', 'img/Speakers/Speaker3.jpg'),
															   (4, 'Enzo van Arum is de informatieanalist die alles regelt', 'img/Speakers/Speaker4.jpg'),
															   (5, 'Onno Hols is een ervaren PHP en JavaScript developer en houdt van vakantie', 'img/Speakers/Speaker5.jpg'),
															   (6, 'Dave Snowden is the founder and chief scientific officer...', 'img/Speakers/Speaker6.jpg'),
															   (7, 'Dr. Barry Devlin is among the foremost authorities on...', 'img/Speakers/Speaker7.jpg');



INSERT INTO GeneralManager (PersonNo, Password) VALUES (1, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
													   (2, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
													   (3, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
								                       (4, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO Visitor (PersonNo, Password) VALUES (1, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
												(2, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO CongressManager (PersonNo, Password) VALUES (5, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO [Subject]([Subject]) VALUES ('DataModeling'),('ICT');

INSERT INTO Location (LocationName, City) VALUES ('Abion Spreebogen', 'Berlijn'),
											     ('HAN', 'Nijmegen');

INSERT INTO Building (LocationName, City, BName, Street, HouseNo, PostalCode) VALUES ('Abion Spreebogen', 'Berlijn', 'Ameron Hotel', 'Alt-Moabit', '99', '10559'),
																					 ('HAN', 'Nijmegen', 'PABO', 'Kapittelweg', '35', '6525EN');

INSERT INTO Room (LocationName, City, BName, RName, Description, MaxNumberOfParticipants) VALUES ('Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '101', 'Bevat een beamer', 30),
																								 ('HAN', 'Nijmegen', 'PABO', '102', 'Bevat een beamer', 20);

INSERT INTO Congress (CName, LocationName, City, Startdate, Enddate, Price, Description, [Public]) VALUES ('Data Modeling Zone', 'Abion Spreebogen', 'Berlijn', '2016-10-10', '2016-10-11', 950, 'Omschrijving', 0),
																										  ('HAN NIOC 2013', 'HAN', 'Nijmegen', '2013-04-04', '2013-04-05', NULL, 'Omschrijving', 0);  

INSERT INTO CongressManagerOfCongress (PersonNo, CongressNo) VALUES (5, 1),
																	(5, 2);
									

INSERT INTO Track (CongressNo, TrackNo, Description, TName) VALUES (1, 1, 'Een track over databases', 'NodeJS'),
																   (2, 1, 'Een track over NoSQL', 'Big Data(NoSQL)');

INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 1, 'Multi-core CPU/GPU', 'Lezing', 50, NULL, 'img/', 'Dit evenement leert je alles over..'),
																											(2, 1, 'Mobiel betalen', 'Workshop', 40, 50, 'img/', 'Leer alles over betalingssystemen!');
																											
INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 1, 1, 1, '2016-10-10 12:00', '2016-10-10 13:00'),
																							 (2, 1, 2, 1, '2013-04-04 10:00', '2013-04-04 12:00');

INSERT INTO EventInRoom (CongressNo, TrackNo, EventNo, LocationName, City, BName, RName, TRA_CongressNo) VALUES (1, 1, 1, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '101', 1),
																												(2, 1, 1, 'HAN', 'Nijmegen', 'PABO', '102', 2);

INSERT INTO VisitorOfCongress (PersonNo, CongressNo, HasPaid) VALUES (1, 1, 0),
																	 (2, 2, 1);

INSERT INTO EventOfVisitorOfCongress (PersonNo, CongressNo, EVE_CongressNo, TrackNo, EventNo, TRA_CongressNo) VALUES (1, 1, 1, 1, 1, 1),
																													 (2, 2, 2, 1, 1, 2);

INSERT INTO SpeakerOfCongress (PersonNo, CongressNo, Agreement) VALUES (6, 1, 'Om 2 uur afhalen van het vliegveld'),
																	   (7, 2, '''s Ochtends niet beschikbaar');

INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (6, 1, 1),
																  (7, 2, 1);

INSERT INTO SubjectOfCongress ([Subject], CongressNo) VALUES ('DataModeling', 1),
															 ('ICT', 2);

INSERT INTO SubjectOfEvent (CongressNo, [Subject], EventNo) VALUES (1, 'DataModeling', 1),
																   (2, 'ICT', 1);
							

