/*
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het aanpassen van een tijdstip van een evenement.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Het isolation level repeatable read zal hier dan wel voldoende zijn. Die houdt namelijk de s-lock vast tot het einde van de transactie.
	Het einde van de transactie is na dat de trigger is uitgevoerd door de auto commit.
	Daardoor kan niet voor het raisen van de error eventueel iets aangepast worden waardoor de melding onterecht is.

*/

CREATE TRIGGER trEventInMultipleTracksOverlap_BR7
ON dbo.EventInTrack
AFTER INSERT
AS 
BEGIN
	SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

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
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, 50, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 2, 1, 1, '2016-10-10 13:05:00', '2016-10-10 13:50:00'),
																								 (1, 2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN

--Foute inserts, Begintijd voor en eindtijd voor
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, 50, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 2, 1, 1, '2016-10-10 11:00:00', '2016-10-10 12:05:00'),
																								 (1, 2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN

--Foute inserts, Begintijd na en eindtijd voor
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, 50, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 2, 1, 1, '2016-10-10 12:01:00', '2016-10-10 12:05:00'),
																								 (1, 2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN

--Foute inserts, Begintijd na en eindtijd na
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, 50, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 2, 1, 1, '2016-10-10 12:01:00', '2016-10-10 13:05:00'),
																								 (1, 2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN

--Foute inserts, Begintijd voor en eindtijd na
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (1, 100, 'Test Event', 'Lezing', 50, 50, 'img/', 'Omschrijving');
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 2, 1, 1, '2016-10-10 11:00:00', '2016-10-10 14:00:00'),
																								 (1, 2, 1, 100, '2016-10-10 14:10:00', '2016-10-10 14:15:00');
ROLLBACK TRAN








  