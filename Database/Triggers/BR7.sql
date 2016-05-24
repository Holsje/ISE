CREATE TRIGGER trEventInMultipleTracksOverlap_BR7
ON dbo.EventInTrack
AFTER INSERT
AS 
BEGIN
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








  