--BR10. De start- en eindtijden van een evenement moeten binnen de start- en einddatum van het congres vallen.
CREATE TRIGGER trEventDateNotInBetweenCongressDate_BR10
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
				ON ET.EventNo = I.EventNo AND ET.TrackNo = I.TrackNo AND ET.CongressNo = I.CongressNo INNER JOIN Congress C 
				ON I.CongressNo = C.CongressNo
			WHERE I.[End] > C.Enddate OR I.Start < C.Startdate
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
INSERT INTO EVENT VALUES(1, 2, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK VALUES(1, 1, 1, 2, '2015-04-10 11:00:00', '2015-04-10 11:50:00') 
ROLLBACK TRANSACTION

-- fout, het event is later dan einddatum van het congres
BEGIN TRANSACTION
INSERT INTO EVENT VALUES(1, 2, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK VALUES(1, 1, 1, 2, '2017-10-10 11:00:00', '2017-10-10 11:50:00') 
ROLLBACK TRANSACTION

-- goed, event start en end date(time) ligt tussen congres start en end date
BEGIN TRANSACTION
INSERT INTO EVENT VALUES(1, 2, 'Een overlappend event', 'lezing', 50, NULL, 'img/', 'test')
INSERT INTO EVENTINTRACK VALUES(1, 1, 1, 2, '2016-10-10 18:05:00', '2016-10-10 18:30:00') 
ROLLBACK TRANSACTION