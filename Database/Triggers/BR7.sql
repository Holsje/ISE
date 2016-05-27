
CREATE TRIGGER trEventInMultipleTracksOverlap_BR7
/*
    BR7. Als eenzelfde evenement in meerdere tracks gehouden wordt, mogen de start- en eindtijden niet overlappen.

	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het aanpassen van een tijdstip van een evenement.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar één congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
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
						ON I.EventNo = EIT.EventNo AND I.CongressNo = EIT.CongressNo AND I.TrackNo != EIT.TrackNo
					WHERE ((I.Start > EIT.Start AND I.Start < EIT.[End]) OR (I.[End] > EIT.Start AND I.Start < EIT.[End]) OR (I.Start <= EIT.Start AND I.[End] >= EIT.[End]))
					)
		BEGIN
			RAISERROR('Hetzelfde evenement wordt al in een andere track op hetzelfde tijdstip gehouden!', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END


-- Goede inserts
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, NULL, 'img/', 'Omschrijving');
	INSERT INTO SpeakerOfEvent (PersonNo, CongressNo, EventNo) VALUES (7, 1, 100);
	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 1, '2016-10-10 13:05:00', '2016-10-10 13:25:00'),
																				 (2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN

--Foute inserts, Begintijd voor en eindtijd voor
BEGIN TRAN
	DELETE FROM EventOfVisitorOfCongress
	DELETE FROM EventInTrack WHERE CongressNo = 1 AND TrackNo = 2
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, NULL, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 1, '2016-10-10 11:00:00', '2016-10-10 12:05:00'),
																				 (2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN


--Foute inserts, Begintijd na en eindtijd voor
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, NULL, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 1, '2016-10-10 12:01:00', '2016-10-10 12:05:00'),
																				 (2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN

--Foute inserts, Begintijd na en eindtijd na
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, NULL, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 1, '2016-10-10 12:01:00', '2016-10-10 13:05:00'),
																				 (2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN

--Foute inserts, Begintijd voor en eindtijd na
BEGIN TRAN
	DELETE FROM SpeakerOfEvent
	DELETE FROM EventOfVisitorOfCongress
	DELETE FROM EventInTrack WHERE CongressNo = 1 AND TrackNo = 2
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, NULL, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 1, '2016-10-10 11:00:00', '2016-10-10 14:00:00'),
																				 (2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN










  