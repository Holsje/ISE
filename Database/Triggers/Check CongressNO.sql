/*
	EventInRoom
*/
CREATE TRIGGER trCheckCongressNoEventInRoom
ON dbo.EventInRoom
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY
		IF UPDATE(CongressNo) OR UPDATE(TRA_CongressNo)
			BEGIN
			IF EXISTS(SELECT 1
						FROM Inserted I INNER JOIN EventInRoom EIR 
						ON I.CongressNo = EIR.CongressNo AND I.TrackNo = EIR.TrackNo AND I.EventNo = EIR.EventNo AND I.LocationName = EIR.LocationName AND I.City = EIR.City AND I.BName = EIR.BName AND I.RName = EIR.RName AND I.TRA_CongressNo = EIR.TRA_CongressNo
						WHERE EIR.CongressNo != EIR.TRA_CongressNo)
			BEGIN
				RAISERROR('De verschillende congresnummers in de tabel EventInRoom dienen gelijk te zijn.', 16, 1);
			END
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END

SELECT * FROM EventInTrack
--Goede Insert
BEGIN TRAN
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 1, 1, 4, '2016-10-10 22:00:00', '2016-10-10 22:30:00'),
																							     (1, 1, 1, 5, '2016-10-10 22:30:00', '2016-10-10 23:00:00');

	INSERT INTO EventInRoom (CongressNo, TrackNo, EventNo, LocationName, City, BName, RName, TRA_CongressNo) VALUES (1, 1, 4, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102', 1),
																												    (1, 1, 5, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102', 1);
ROLLBACK TRAN

--Foute Insert
BEGIN TRAN
	DBCC CHECKIDENT('Congress','RESEED', 2);
	INSERT INTO Congress (LocationName, City, CName, Startdate, Enddate, Price, Description, Banner, [Public]) VALUES ('Abion Spreebogen', 'Berlijn', 'Test Congres', GETDATE(), DATEADD(DAY, 1, GETDATE()), 50, 'Omschrijving', 'banner.jpg', 0);
	INSERT INTO Track (CongressNo, TrackNo, Description, TName) VALUES (3, 1, 'TrackOmschrijving', 'TrackNaam');
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (3, 4, 'TestEvent', 'Lezing', 40, NULL, 'Files/EventTest', 'TestOmschrijving');
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 1, 1, 4, '2016-10-10 22:00:00', '2016-10-10 22:30:00'),
																							     (1, 1, 1, 5, '2016-10-10 22:30:00', '2016-10-10 23:00:00'),
																								 (3, 1, 3, 4, GETDATE(), DATEADD(HOUR, 2, GETDATE()));				 

	INSERT INTO EventInRoom (CongressNo, TrackNo, EventNo, LocationName, City, BName, RName, TRA_CongressNo) VALUES (3, 1, 4, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102', 1),
																												    (1, 1, 5, 'Abion Spreebogen', 'Berlijn', 'Ameron Hotel', '102', 1);
ROLLBACK TRAN

/*
	EventInTrack
*/
CREATE TRIGGER trCheckCongressNoEventInTrack
ON dbo.EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY

		IF EXISTS(SELECT 1
				  FROM Inserted I INNER JOIN EventInTrack EIT
					ON I.TRA_CongressNo = EIT.TRA_CongressNo AND I.TrackNo = EIT.TrackNo AND I.CongressNo = EIT.CongressNo AND I.EventNo = EIT.EventNo
				  WHERE EIT.TRA_CongressNo != EIT.CongressNo)
		BEGIN
			RAISERROR('De verschillende congresnummers in de tabel EventInTrack dienen gelijk te zijn.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END

/*
	EventOfVisitorOfCongress
*/
CREATE TRIGGER trCheckCongressNoEventOfVisitorOfCongress
ON dbo.EventOfVisitorOfCongress
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY

		IF EXISTS(SELECT 1
				  FROM Inserted I INNER JOIN EventOfVisitorOfCongress EVC
					ON I.PersonNo = EVC.PersonNo AND I.CongressNo = EVC.CongressNo AND I.EVE_CongressNo = EVC.EVE_CongressNo AND I.TrackNo = EVC.TrackNo AND I.EventNo = EVC.EventNo AND I.TRA_CongressNo = EVC.TRA_CongressNo
				  WHERE EVC.CongressNo != EVC.EVE_CongressNo OR EVC.CongressNo != EVC.TRA_CongressNo OR EVC.EVE_CongressNo != EVC.TRA_CongressNo
					)
		BEGIN
			RAISERROR('De verschillende congresnummers in de tabel EventOfVisitorOfCongress dienen gelijk te zijn.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END







  INSERT INTO Track VALUES (1, 4, 'Omschrijving', 'Hoi Niels')
  INSERT INTO EventInTrack VALUES (1, 4, 1, 1, '2016-10-10 08:00:00', '2016-10-10 12:00:00')