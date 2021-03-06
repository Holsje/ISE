

CREATE TRIGGER trMultipleEventsOfSpeakerOverlap_BR9
/*
	BR9. Verschillende evenementen van een spreker mogen elkaar niet overlappen qua tijd.

	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack en de tabel SpeakerOfCongress.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen van de tijden van een ander evenement van een spreker.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar ��n congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
	Daarnaast is samen met de opdrachtgever afgesproken dat voor deze gevallen het isolation level read committed voldoende is.

*/
ON dbo.EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	BEGIN TRY

		IF EXISTS(SELECT 1
				  FROM Inserted I INNER JOIN EventInTrack EIT 
					ON EIT.CongressNo = I.CongressNo AND EIT.EventNo = I.EventNo AND EIT.TrackNo = I.TrackNo INNER JOIN SpeakerOfEvent SOE 
						ON EIT.CongressNo = SOE.CongressNo AND EIT.EventNo = SOE.EventNo INNER JOIN SpeakerOfEvent SOE2 
							ON SOE.PersonNo = SOE2.PersonNo AND SOE.CongressNo = SOE2.CongressNo INNER JOIN EventInTrack EIT2 
								ON SOE2.CongressNo = EIT2.CongressNo AND SOE2.EventNo = EIT2.EventNo AND EIT.EventNo != EIT2.EventNo
				  WHERE  ((I.Start > EIT2.Start AND I.Start < EIT2.[End]) OR (I.[End] > EIT2.Start AND I.[End] < EIT2.[End]) OR (I.Start <= EIT2.Start AND I.[End] >= EIT2.[End]))	
					)
		BEGIN
			RAISERROR('De spreker van het evenement spreekt al tegelijkertijd bij een ander evenement.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END


-- Goede inserts
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 10, 'Test Event', 'Lezing', 40, NULL, 'img/', 'Omschrijving'),
																										        (1, 11, 'Test Event2', 'Lezing', 40, NULL, 'img/', 'Omschrijving');
	INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (1, 1, 10),
																	  (2, 1, 11);
	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 10, '2016-10-10 13:00:00', '2016-10-10 13:30:00'),
																				 (2, 1, 11, '2016-10-10 13:30:00', '2016-10-10 14:00:00');																						 									 
ROLLBACK TRAN


-- Foute inserts, begintijd voor en eindtijd voor
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 10, 'Test Event', 'Lezing', 40, NULL, 'img/', 'Omschrijving'),
																										        (1, 11, 'Test Event2', 'Lezing', 40, NULL, 'img/', 'Omschrijving');
	INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (1, 1, 10),
																	  (2, 1, 11);

	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 10, '2016-10-10 11:50:00', '2016-10-10 12:10:00'),
																				 (2, 1, 11, '2016-10-10 13:30:00', '2016-10-10 14:00:00');							 									 
ROLLBACK TRAN

-- Foute inserts, begintijd na en eindtijd voor
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 10, 'Test Event', 'Lezing', 40, NULL, 'img/', 'Omschrijving'),
																										        (1, 11, 'Test Event2', 'Lezing', 40, NULL, 'img/', 'Omschrijving');
	INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (1, 1, 10),
																	  (2, 1, 11);

	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 10, '2016-10-10 12:01:00', '2016-10-10 12:10:00'),
																				 (2, 1, 11, '2016-10-10 13:30:00', '2016-10-10 14:00:00');							 									 
ROLLBACK TRAN

-- Foute inserts, begintijd na en eindtijd na
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 10, 'Test Event', 'Lezing', 40, NULL, 'img/', 'Omschrijving'),
																										        (1, 11, 'Test Event2', 'Lezing', 40, NULL, 'img/', 'Omschrijving');
	INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (1, 1, 10),
																	  (2, 1, 11);

	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 10, '2016-10-10 12:01:00', '2016-10-10 13:01:00'),
																				 (2, 1, 11, '2016-10-10 13:30:00', '2016-10-10 14:00:00');							 									 
ROLLBACK TRAN

-- Foute inserts, begintijd voor en eindtijd na
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 10, 'Test Event', 'Lezing', 40, NULL, 'img/', 'Omschrijving'),
																										        (1, 11, 'Test Event2', 'Lezing', 40, NULL, 'img/', 'Omschrijving');
	INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (1, 1, 10),
																	  (2, 1, 11);

	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 10, '2016-10-10 11:59:00', '2016-10-10 13:01:00'),
																				 (2, 1, 11, '2016-10-10 13:30:00', '2016-10-10 14:00:00');							 									 
ROLLBACK TRAN




	
			