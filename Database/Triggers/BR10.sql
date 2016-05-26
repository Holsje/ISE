
CREATE TRIGGER trEventDateNotInBetweenCongressDate_BR10
/*
	BR10. De start- en eindtijden van een evenement moeten binnen de start- en einddatum van het congres vallen.
	
	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack en Congres.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen de start of eind datum van het congres.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar één congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
	Daarnaast is samen met de opdrachtgever afgesproken dat voor deze gevallen het isolation level read committed voldoende is.

*/
ON EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	BEGIN TRY
		IF EXISTS(
			SELECT 1
			FROM EVENTINTRACK ET INNER JOIN Inserted I 
				ON ET.EventNo = I.EventNo AND ET.TrackNo = I.TrackNo AND ET.CongressNo = I.CongressNo INNER JOIN Congress C 
				ON I.CongressNo = C.CongressNo
			WHERE CAST(I.[End] AS Date) > C.Enddate OR CAST(I.Start AS Date) < C.Startdate
		)
	BEGIN
		RAISERROR('De start- en eindtijden van een evenement moeten binnen de start- en einddatum van het congres vallen.', 16, 1)
	END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
 END


 --BR10 Testdata
-- fout, het event is eerder dan startdatum van het congres
BEGIN TRANSACTION
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 100, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK (TrackNo, CongressNo, EventNo, Start, [End]) VALUES(1, 1, 100, '2015-04-10 11:00:00', '2015-04-10 11:50:00') 
ROLLBACK TRANSACTION

-- fout, het event is later dan einddatum van het congres
BEGIN TRANSACTION
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 100, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK (TrackNo, CongressNo, EventNo, Start, [End]) VALUES(1, 1, 100, '2017-10-10 11:00:00', '2017-10-10 11:50:00') 
ROLLBACK TRANSACTION

-- goed, event start en end date(time) ligt tussen congres start en end date
BEGIN TRANSACTION
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 100, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK (TrackNo, CongressNo, EventNo, Start, [End]) VALUES(1, 1, 100, '2016-10-10 18:05:00', '2016-10-10 18:30:00') 
ROLLBACK TRANSACTION