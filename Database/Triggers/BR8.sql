--BR8. Evenementen in een track mogen elkaar qua tijd niet overlappen.
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