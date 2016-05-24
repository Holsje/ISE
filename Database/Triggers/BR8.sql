--BR8. Evenementen in een track mogen elkaar qua tijd niet overlappen.

/*
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen van een room van een event.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Het isolation level repeatable read zal hier dan wel voldoende zijn. Die houdt namelijk de s-lock vast tot het einde van de transactie.
	Het einde van de transactie is na dat de trigger is uitgevoerd door de auto commit.
	Daardoor kan niet voor het raisen van de error eventueel iets aangepast worden waardoor de melding onterecht is.

*/

CREATE TRIGGER trEventInTrackOverlap_BR8
ON EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN
IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY
	IF EXISTS( 
		SELECT 1
		FROM EVENTINTRACK ET INNER JOIN Inserted I 
			ON ET.EVENTNO != I.EVENTNO AND ET.CongressNo = I.CongressNo AND ET.TRACKNO = I.TRACKNO
		WHERE ((I.Start BETWEEN ET.Start AND ET.[End]) OR (I.[End] BETWEEN ET.Start AND ET.[End]) OR (I.Start <= ET.Start AND I.[End] >= ET.[End])))
		RAISERROR('Evenementen in een track mogen elkaar qua tijd niet overlappen.', 16, 1);
	END TRY
	BEGIN CATCH
		THROW
	END CATCH
END

GO

--BR8 Testdata

-- fout, Starttijd voor en eindtijd na
BEGIN TRANSACTION
INSERT INTO EVENT VALUES(1, 2, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK VALUES(1, 1, 1, 2, '2016-10-10 09:00:00', '2016-10-10 14:00:00') 
ROLLBACK TRANSACTION 

-- goed, Starttijd voor en eindtijd voor
BEGIN TRANSACTION
INSERT INTO EVENT VALUES(1, 2, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK VALUES(1, 1, 1, 2, '2016-10-10 09:05:00', '2016-10-10 11:50:00') 
ROLLBACK TRANSACTION

-- fout, Starttijd na en eindtijd na
BEGIN TRANSACTION
INSERT INTO EVENT VALUES(1, 2, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENT VALUES(1, 6, 'Nog een event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK VALUES(1, 1, 1, 6, '2016-10-10 13:10:00', '2016-10-10 13:50:00'),
							   (1, 1, 1, 2, '2016-10-10 12:05:00', '2016-10-10 13:05:00') 
ROLLBACK TRANSACTION

-- fout, Starttijd na en eindtijd voor
BEGIN TRANSACTION
INSERT INTO EVENT VALUES(1, 2, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENT VALUES(1, 6, 'Nog een event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK VALUES(1, 1, 1, 6, '2016-10-10 13:10:00', '2016-10-10 13:50:00'), 
							   (1, 1, 1, 2, '2016-10-10 12:10:00', '2016-10-10 12:50:00') 
ROLLBACK TRANSACTION