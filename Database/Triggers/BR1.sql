ALTER TABLE Congress
ADD CONSTRAINT CHK_StartBeforeEndCongress CHECK (StartDate <= EndDate) 

--Goede insert
BEGIN TRAN
	INSERT INTO Congress (LocationName, City, CName, Startdate, Enddate, Price, Description, Banner, [Public]) VALUES ('HAN', 'Arnhem', 'Test Congres', GETDATE(), DATEADD(DAY, 1, GETDATE()), 80, 'Omschrijving', 'img/banners/CongressBanner.png', 0);
ROLLBACK TRAN

--Foute insert
BEGIN TRAN
	INSERT INTO Congress (LocationName, City, CName, Startdate, Enddate, Price, Description, Banner, [Public]) VALUES ('HAN', 'Arnhem', 'Test Congres', GETDATE(), DATEADD(DAY, -1, GETDATE()), 80, 'Omschrijving', 'img/banners/CongressBanner.png', 0);
ROLLBACK TRAN


ALTER TABLE EventInTrack
ADD CONSTRAINT CHK_StartBeforeEndEventInTrack CHECK (Start < [End]) 

--Goede insert
BEGIN TRAN
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 2, 1, 1, '2016-10-10 09:00:00', '2016-10-10 10:00:00');
ROLLBACK TRAN

--Foute insert
BEGIN TRAN
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (1, 2, 1, 1, '2016-10-10 10:00:00', '2016-10-10 09:00:00');
ROLLBACK TRAN