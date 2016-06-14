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

DELETE FROM Congress

DELETE FROM CongressManagerOfCongress
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
GO

/* On first run CHECKIDENT in comment!!!*/
DBCC CHECKIDENT ('Person', RESEED, 0);
DBCC CHECKIDENT ('Congress', RESEED, 0);

INSERT INTO PersonType (TypeName) VALUES ('Algemene beheerder'),
										 ('Congresbeheerder'),
										 ('Bezoeker'),
										 ('Spreker'),
										 ('Reviewboard')

INSERT INTO Person (FirstName, LastName, MailAddress, PhoneNumber) VALUES ('Erik', 'Evers', 'erikevers1996@gmail.com', '0613334002'),
																		  ('Daniël', 'de Jong', 'danieldejong@hotmail.com', '0612345678'),
																		  ('Niels', 'Bergervoet', 'nielsbergervoet@hotmail.com', '0654897852'),
																		  ('Enzo', 'van Arum', 'enzovanarum@hotmail.com', '0678945236'),
																		  ('Onno', 'Hols', 'onnohols@hotmail.com', '0694858595'),
																		  ('Dave', 'Snowden', 'davesnowden@gmail.com', '0610493923'),
																		  ('Barry', 'Devlin', 'barrydevlin@gmail.com', '0645785126');

INSERT INTO PersonTypeOfPerson (PersonNo, TypeName) VALUES (1, 'Algemene beheerder'),
														   (2, 'Algemene beheerder'),
														   (4, 'Algemene beheerder'),
														   (3, 'Congresbeheerder'),
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

INSERT INTO Speaker (PersonNo, Description, PicturePath, Owner) VALUES (1, 'Erik Evers is een ervaren PHP en JavaScript developer en komt vertellen over web development.', 'img/Speakers/Speaker1.png', 1),
																	   (2, 'Daniel de Jong is een ervaren projectleider die veel projecten tot een goed einde heeft gebracht.', 'img/Speakers/Speaker2.png', 1),
															   	       (3, 'Niels Bergervoet is een ervaren PHP en JavaScript developer en komt vertellen over web development.', 'img/Speakers/Speaker3.png', 1),
															   	       (4, 'Enzo van Arum is een informatieanalist die veel kennis heeft van BPMN.', 'img/Speakers/Speaker4.png', 1),
															   	       (5, 'Onno Hols is een ervaren PHP en JavaScript developer en komt vertellen over web development.', 'img/Speakers/Speaker5.png', 1),
															   	       (6, 'Dave Snowden is the founder and chief scientific officer...', 'img/Speakers/Speaker6.png', 1),
															   	       (7, 'Dr. Barry Devlin is among the foremost authorities on...', 'img/Speakers/Speaker7.png', 1);



INSERT INTO GeneralManager (PersonNo, Password) VALUES (1, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
													   (2, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
													   (4, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO Visitor (PersonNo, Password) VALUES (1, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
												(2, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
												(3, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
												(4, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
												(5, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
												(6, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
												(7, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO CongressManager (PersonNo, Password) VALUES (5, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937'),
														(3, 'dc00c903852bb19eb250aeba05e534a6d211629d77d055033806b783bae09937');

INSERT INTO [Subject]([Subject]) VALUES ('DataModeling'),
										('ICT'),
										('BusinessIntelligence'),
										('BigData'),
										('Data'),
										('Database'),
										('Javascript'),
										('Programmeren');

INSERT INTO Location (LocationName, City) VALUES ('Abion Spreebogen', 'Berlijn'),
											     ('HAN', 'Nijmegen'),
												 ('HAN', 'Arnhem'),
												 ('Van der Valk Hotel Arnhem', 'Arnhem'),
												 ('Hotel Papendal', 'Arnhem');

INSERT INTO Building (LocationName, City, BName, Street, HouseNo, PostalCode) VALUES ('Abion Spreebogen', 'Berlijn', 'Ameron Hotel', 'Alt-Moabit', '99', '10559'),
																					 ('HAN', 'Nijmegen', 'PABO', 'Kapittelweg', '35', '6525EN'),
																					 ('HAN', 'Arnhem', 'Automotive', 'Ruitenberglaan', '29', '6826CC'),
																					 ('Van der Valk Hotel Arnhem', 'Arnhem', 'Hotel', 'Amsterdamseweg', '505', '6816VK'),
																					 ('Hotel Papendal', 'Arnhem', 'Congresgebouw', 'Papendallaan', '3', '6816VD');

INSERT INTO Room (LocationName, City, BName, RName, Description, MaxNumberOfParticipants) VALUES ('Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '101', 'Bevat een beamer', 30),
																								 ('Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102', 'Bevat een beamer', 30),
																								 ('Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '103', 'Bevat een beamer', 30),
																								 ('HAN', 'Nijmegen', 'PABO', '201', 'Bevat een beamer', 20),
																								 ('HAN', 'Nijmegen', 'PABO', '202', 'Bevat een beamer', 20),
																								 ('HAN', 'Nijmegen', 'PABO', '203', 'Bevat een beamer', 20),
																								 ('HAN', 'Arnhem', 'Automotive', '305', 'Luxe stoelen', 10),
																								 ('Van der Valk Hotel Arnhem', 'Arnhem', 'Hotel', 'Zaal 1', 'Met geluidsinstallatie', 100),
																								 ('Hotel Papendal', 'Arnhem', 'Congresgebouw', 'Zaal 2', 'Met geluidsinstallatie', 750);

INSERT INTO Congress (CName, LocationName, City, Startdate, Enddate, Price, Description, Banner, [Public]) VALUES ('Data Modeling Zone', 'Abion Spreebogen', 'Berlijn', '2016-10-10', '2016-10-11', 950, 'Omschrijving', 'img/Banners/Congress1.png', 0),
																												  ('HAN NIOC 2013', 'HAN', 'Nijmegen', '2013-04-04', '2013-04-06', 500, 'Omschrijving', 'img/Banners/Congress2.png', 0);  

INSERT INTO CongressManagerOfCongress (PersonNo, CongressNo) VALUES (5, 1),
																	(3, 2);
									

INSERT INTO Track (CongressNo, TrackNo, Description, TName) VALUES (1, 1, 'Een track over programmeren', 'NodeJS'),
																   (1, 2, 'Een track over Business Intelligence', 'All about BI'),
																   (1, 3, 'Een track over Datawarehousing', 'Datawarehouseing'),
																   (2, 1, 'Een track over NoSQL', 'Big Data(NoSQL)'),
																   (2, 2, 'Een track over Datawarehousing', 'Datawarehousing'),
																   (2, 3, 'Een track over Business Intelligence', 'All about BI');

INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 1, 'Multi-core CPU/GPU', 'Lezing', 50, NULL, 'Congresses/Congress1/Event1/', 'Dit evenement leert je alles over..'),
																											(1, 2, 'Mobiel betalen', 'Workshop', 40, 50, 'Congresses/Congress1/Event2/', 'Leer alles over betalingssystemen!'),
																											(1, 3, 'Open Educational Resources', 'Lezing', 50, NULL, 'Congresses/Congress1/Event3/', 'Dit evenement leert je alles over..'),
																											(1, 4, 'Power-BI', 'Workshop', 20, 100, 'Congresses/Congress1/Event4/', 'Dit evenement over Power-BI...'),
																											(1, 5, '360 graden BI', 'Lezing', 30, NULL, 'Congresses/Congress1/Event5/', 'Algemene informatie over BI..'),
																											(1, 6, 'Analyse en Rapportage', 'Lezing', 40, NULL, 'Congresses/Congress1/Event6/', 'Dit evenement leert je alles over..'),
																											(1, 7, 'Data Vault to Star', 'Lezing', 60, NULL, 'Congresses/Congress1/Event7/', 'Evenement over Data Vault naar Ster..'),
																											(1, 8, 'Raw Data', 'Lezing', 50, NULL, 'Congresses/Congress1/Event8/', 'Dit evenement leert je alles over..'),
																											(1, 9, 'Meta Data', 'Lezing', 50, NULL, 'Congresses/Congress1/Event9/', 'Dit evenement leert je alles over..'),
																											(2, 1, 'Hadoop', 'Lezing', 40, NULL, 'Congresses/Congress2/Event1/', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin posuere finibus accumsan. Nulla facilisi. Praesent ullamcorper quam sed lacus gravida, id commodo nunc fringilla. Maecenas non blandit velit, lacinia mollis ipsum. Integer interdum a tellus sed sollicitudin. Nullam varius tellus et lorem fermentum porta non quis sapien. Nunc a dictum erat. Nunc accumsan bibendum suscipit. Morbi dignissim tellus at erat pellentesque imperdiet. Phasellus interdum risus vel lectus consectetur elementum. Morbi sit amet lectus vitae odio efficitur tempus non eget neque. Aenean lacinia justo quam, vitae malesuada nibh volutpat id. Cras ut fermentum nulla, eget sodales lectus. Aenean sit amet consequat enim. Aliquam lectus arcu, molestie in arcu eu, vulputate vulputate velit. Pellentesque interdum lacus id mi rhoncus vulputate. Aliquam in viverra mauris. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur quis finibus nisi. Maecenas sit amet sapien maximus magna lacinia posuere'),
																											(2, 2, 'NoSQL vs. Relationele Database', 'Lezing', 40, NULL, 'Congresses/Congress2/Event2/', 'Leer alles over betalingssystemen!'),
																											(2, 3, 'NoTables', 'Lezing', 40, NULL, 'Congresses/Congress2/Event3/', 'Leer alles over betalingssystemen!'),
																											(2, 4, 'Power-BI', 'Workshop', 20, 100, 'Congresses/Congress2/Event4/', 'Dit evenement over Power-BI...'),
																											(2, 5, '360 graden BI', 'Lezing', 30, NULL, 'Congresses/Congress2/Event5/', 'Algemene informatie over BI..'),
																											(2, 6, 'Analyse en Rapportage', 'Lezing', 40, NULL, 'Congresses/Congress2/Event6/', 'Dit evenement leert je alles over..'),
																											(2, 7, 'Data Vault to Star', 'Lezing', 60, NULL, 'Congresses/Congress2/Event7/', 'Evenement over Data Vault naar Ster..'),
																											(2, 8, 'Raw Data', 'Lezing', 50, NULL, 'Congresses/Congress2/Event8/', 'Dit evenement leert je alles over..'),
																											(2, 9, 'Meta Data', 'Lezing', 50, NULL, 'Congresses/Congress2/Event9/', 'Dit evenement leert je alles over..');
																																																				
INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 1, 1, '2016-10-10 12:00', '2016-10-10 13:00'),
																			 (1, 1, 2, '2016-10-10 13:30', '2016-10-10 15:00'),
																		     (1, 1, 3, '2016-10-11 09:00', '2016-10-11 11:30'),
																			 (2, 1, 4, '2016-10-10 10:00', '2016-10-10 12:00'),
																			 (2, 1, 5, '2016-10-11 09:30', '2016-10-11 12:00'),
																			 (2, 1, 6, '2016-10-11 13:00', '2016-10-11 15:00'),
																			 (3, 1, 7, '2016-10-11 13:00', '2016-10-11 15:00'),
																			 (3, 1, 8, '2016-10-11 15:00', '2016-10-11 16:00'),
																			 (3, 1, 9, '2016-10-11 16:00', '2016-10-11 17:00'),
																			 (1, 2, 1, '2013-04-04 10:00', '2013-04-04 12:00'),
																			 (1, 2, 2, '2013-04-04 13:00', '2013-04-04 15:00'),
																			 (1, 2, 3, '2013-04-04 15:30', '2013-04-04 17:00'),
																			 (2, 2, 4, '2013-04-04 10:00', '2013-04-04 12:00'),
																			 (2, 2, 5, '2013-04-04 13:00', '2013-04-04 14:00'),
																			 (2, 2, 6, '2013-04-05 09:00', '2013-04-05 11:30'),
																			 (3, 2, 7, '2013-04-04 10:00', '2013-04-04 12:00'),
																			 (3, 2, 8, '2013-04-05 10:00', '2013-04-05 12:00'),
																			 (3, 2, 9, '2013-04-06 10:00', '2013-04-06 12:00');

INSERT INTO EventInRoom (CongressNo, TrackNo, EventNo, LocationName, City, BName, RName) VALUES (1, 1, 1, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '101'),
																								(1, 1, 2, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '101'),
																								(1, 1, 3, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '101'),
																								(1, 2, 4, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102'),
																								(1, 2, 5, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102'),
																								(1, 2, 6, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102'),
																								(1, 3, 7, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '103'),
																								(1, 3, 8, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '103'),
																								(1, 3, 9, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '103'),
																								(2, 1, 1, 'HAN', 'Nijmegen', 'PABO', '201'),
																								(2, 1, 2, 'HAN', 'Nijmegen', 'PABO', '201'),
																								(2, 1, 3, 'HAN', 'Nijmegen', 'PABO', '201'),
																								(2, 2, 4, 'HAN', 'Nijmegen', 'PABO', '202'),
																								(2, 2, 5, 'HAN', 'Nijmegen', 'PABO', '202'),
																								(2, 2, 6, 'HAN', 'Nijmegen', 'PABO', '202'),
																								(2, 3, 7, 'HAN', 'Nijmegen', 'PABO', '203'),
																								(2, 3, 8, 'HAN', 'Nijmegen', 'PABO', '203'),
																								(2, 3, 9, 'HAN', 'Nijmegen', 'PABO', '203');

INSERT INTO VisitorOfCongress (PersonNo, CongressNo, HasPaid) VALUES (1, 1, 0),
																	 (2, 1, 1),
																	 (1, 2, 0),
																	 (2, 2, 1);

INSERT INTO EventOfVisitorOfCongress (PersonNo, CongressNo, TrackNo, EventNo) VALUES (1, 1, 1, 1),
																					 (1, 1, 1, 2),
																					 (1, 1, 1, 3),
																					 (2, 1, 2, 4),
																					 (2, 1, 2, 5),
																					 (2, 1, 2, 6),
																					 (1, 2, 2, 4),
																					 (1, 2, 2, 5),
																					 (1, 2, 2, 6),
																					 (2, 2, 3, 7),
																					 (2, 2, 3, 8),
																					 (2, 2, 3, 9);

INSERT INTO SpeakerOfCongress (PersonNo, CongressNo, Agreement) VALUES (1, 1, 'Om 2 uur afhalen van het vliegveld'),
																	   (2, 1, 'Om 5 uur afhalen van het vliegveld'),
																	   (6, 1, 'Om 1 uur afhalen van het vliegveld'),
																	   (4, 2, 'Om 7 uur afhalen van het vliegveld'),
																	   (5, 2, 'Om 9 uur afhalen van het vliegveld'),
																	   (7, 2, 'In de ochtend niet beschikbaar');

INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (1, 1, 1),
																  (1, 1, 2),
																  (1, 1, 3),
																  (2, 1, 4),
																  (2, 1, 5),
																  (2, 1, 6),
																  (6, 1, 7),
																  (6, 1, 8),
																  (6, 1, 9),
																  (1, 2, 1),
																  (2, 2, 1),
																  (3, 2, 1),
																  (4, 2, 1),
																  (5, 2, 1),
																  (4, 2, 2),
																  (4, 2, 3),
																  (5, 2, 4),
																  (5, 2, 5),
																  (5, 2, 6),
																  (7, 2, 7),
																  (7, 2, 8),
																  (7, 2, 9);

INSERT INTO SubjectOfCongress ([Subject], CongressNo) VALUES ('DataModeling', 1),
															 ('ICT', 1),
															 ('ICT', 2);

INSERT INTO SubjectOfEvent (CongressNo, [Subject], EventNo) VALUES (1, 'Javascript', 1),
																   (1, 'Programmeren', 1),
																   (1, 'Javascript', 2),
																   (1, 'Javascript', 3),
																   (1, 'BusinessIntelligence', 4),
																   (1, 'BusinessIntelligence', 5),
																   (1, 'BusinessIntelligence', 6),
																   (1, 'Data', 7),
																   (1, 'BigData', 7),
																   (1, 'Data', 8),
																   (1, 'Data', 9),
																   (2, 'ICT', 1),
																   (2, 'BigData', 1),
																   (2, 'Data', 1),
																   (2, 'Data', 2),
																   (2, 'Database', 3),
																   (2, 'BusinessIntelligence', 4),
																   (2, 'ICT', 4),
																   (2, 'BusinessIntelligence', 5),
																   (2, 'ICT', 5),
																   (2, 'BusinessIntelligence', 6),
																   (2, 'BusinessIntelligence', 7),
																   (2, 'BusinessIntelligence', 8),
																   (2, 'ICT', 9);


									