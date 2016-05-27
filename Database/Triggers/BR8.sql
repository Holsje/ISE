
CREATE TRIGGER trEventInTrackOverlap_BR8
/*
    BR8. Evenementen in een track mogen elkaar qua tijd niet overlappen.

	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen van een room van een event.
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
			ON ET.EVENTNO != I.EVENTNO AND ET.CongressNo = I.CongressNo AND ET.TRACKNO = I.TRACKNO
		WHERE ((I.Start > ET.Start AND I.Start < ET.[End]) OR (I.[End] > ET.Start AND I.[End] < ET.[End]) OR (I.Start <= ET.Start AND I.[End] >= ET.[End])))
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
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 100, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK (TrackNo, CongressNo, EventNo, Start, [End]) VALUES(1, 1, 100, '2016-10-10 09:00:00', '2016-10-10 14:00:00') 
ROLLBACK TRANSACTION 

-- goed, Starttijd voor en eindtijd voor
BEGIN TRANSACTION
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 100, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK (TrackNo, CongressNo, EventNo, Start, [End]) VALUES(1, 1, 100, '2016-10-10 09:05:00', '2016-10-10 11:50:00') 
ROLLBACK TRANSACTION

-- fout, Starttijd na en eindtijd na
BEGIN TRANSACTION
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 100, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 101, 'Nog een event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK (TrackNo, CongressNo, EventNo, Start, [End]) VALUES(1, 1, 101, '2016-10-10 13:10:00', '2016-10-10 13:50:00'),
																			(1, 1, 100, '2016-10-10 12:05:00', '2016-10-10 13:05:00') 
ROLLBACK TRANSACTION

-- fout, Starttijd na en eindtijd voor
BEGIN TRANSACTION
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 100, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENT (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES(1, 101, 'Nog een event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK (TrackNo, CongressNo, EventNo, Start, [End]) VALUES(1, 1, 101, '2016-10-10 13:10:00', '2016-10-10 13:50:00'), 
																			(1, 1, 100, '2016-10-10 12:10:00', '2016-10-10 12:50:00') 
ROLLBACK TRANSACTION