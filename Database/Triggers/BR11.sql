/*
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack en de tabel EventOfVisitorOfCongress.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden zou het dus mogelijk zijn om iets aan te passen.
	Er is echter een andere businness rule die die er voor zorgt dat de tijden van evenementen niet meer aangepast mogen worden als het congres waar de evenementen bijhoren publiek is.
	Daarnaast zal deze trigger alleen afgevuurd worden als iemand op de site, wanneer het congres dus al publiek is, zich gaat inschrijven. Er is dan geen mogelijkheid meer om tijden van evenementen aan te passen.
	Daarom voldoet read committed hier toch, omdat tussen de if exists en de raiserror vanwege een andere business rule geen gegevens die van invloed zijn aangepast kunnen worden.

	
*/

CREATE TRIGGER trMultipleEventsOfVisitorOverlap_BR11
ON dbo.EventOfVisitorOfCongress
AFTER INSERT
AS
BEGIN
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY
		IF EXISTS(SELECT 1
				  FROM Inserted AS I INNER JOIN EventOfVisitorOfCongress EVC 
					ON I.PersonNo = EVC.PersonNo AND I.CongressNo = EVC.CongressNo AND I.TrackNo = EVC.TrackNo AND I.EventNo = EVC.EventNo INNER JOIN EventInTrack EIT 
						ON EIT.CongressNo = EVC.CongressNo AND EIT.TrackNo = EVC.TrackNo AND EIT.EventNo = EVC.EventNo INNER JOIN EventOfVisitorOfCongress EVC2
							ON EVC.PersonNo = EVC2.PersonNo AND EVC.CongressNo = EVC2.CongressNo INNER JOIN EventInTrack EIT2
								ON EVC2.CongressNo = EIT2.CongressNo AND EVC2.TrackNo = EIT2.TrackNo AND EVC2.EventNo = EIT2.EventNo 
				  WHERE ((EIT.Start > EIT2.Start AND EIT.Start < EIT2.[End]) OR (EIT.[End] > EIT2.Start AND EIT.[End] < EIT2.[End]) OR (EIT.Start <= EIT2.Start AND EIT.[End] >= EIT2.[End])) AND (EIT.EventNo != EIT2.EventNo))
		BEGIN
			RAISERROR('U kunt geen overlappende evenementen kiezen.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END

--Goede Insert

SELECT * FROM EventInTrack WHERE Congressno = 1
BEGIN TRAN
	DELETE FROM EventOfVisitorOfCongress
	DBCC CHECKIDENT('Congress', 'RESEED', 2);
	INSERT INTO Congress (LocationName, City, CName, Startdate, Enddate, Price, Description, Banner, [Public]) VALUES ('Abion Spreebogen', 'Berlijn', 'Test Congres', '2016-10-10', '2016-10-11', 50, 'Omschrijving', 'img/banner.jpg', 0);
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (3, 1, 'Test Event', 'Lezing', 40, NULL, 'file', 'Omschrijving');
	INSERT INTO Track (CongressNo, TrackNo, Description, TName) VALUES (3, 1, 'Trackomschrijving', 'Track1');
	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (3, 1, 3, 1, '2016-10-10 11:50:00', '2016-10-10 12:30:00');
	INSERT INTO VisitorOfCongress (CongressNo, PersonNo, HasPaid) VALUES (3, 1, 0);
	INSERT INTO EventOfVisitorOfCongress (PersonNo, CongressNo, EVE_CongressNo, TrackNo, EventNo, TRA_CongressNo) VALUES (1, 1, 1, 1, 1, 1),
																														 (1, 1, 1, 1, 2, 1),
																														 (1, 1, 1, 2, 4, 1),
																														 (1, 3, 3, 1, 1, 3); 
ROLLBACK TRAN

-- Foute Insert
BEGIN TRAN
	DELETE FROM EventOfVisitorOfCongress
	INSERT INTO EventOfVisitorOfCongress (PersonNo, CongressNo, EVE_CongressNo, TrackNo, EventNo, TRA_CongressNo) VALUES (1, 1, 1, 1, 1, 1),
																														 (1, 1, 1, 1, 3, 1),
																														 (1, 1, 1, 2, 4, 1),
																														 (1, 1, 1, 2, 5, 1); 
ROLLBACK TRAN

SELECT *
				  FROM (SELECT 1 AS PersonNo, 1 AS CongressNo, 1 AS EVE_CongressNo, 1 AS TrackNo, 1 AS EventNo, 1 AS TRA_CongressNo) AS I INNER JOIN EventOfVisitorOfCongress EVC 
					ON I.PersonNo = EVC.PersonNo AND I.CongressNo = EVC.CongressNo AND I.TrackNo = EVC.TrackNo AND I.EventNo = EVC.EventNo INNER JOIN EventInTrack EIT 
						ON EIT.CongressNo = EVC.CongressNo AND EIT.TrackNo = EVC.TrackNo AND EIT.EventNo = EVC.EventNo INNER JOIN EventOfVisitorOfCongress EVC2
							ON EVC.PersonNo = EVC2.PersonNo AND EVC.CongressNo = EVC2.CongressNo INNER JOIN EventInTrack EIT2
								ON EVC2.CongressNo = EIT2.CongressNo AND EVC2.TrackNo = EIT2.TrackNo AND EVC2.EventNo = EIT2.EventNo 
				  WHERE ((EIT.Start > EIT2.Start AND EIT.Start < EIT2.[End]) OR (EIT.[End] > EIT2.Start AND EIT.[End] < EIT2.[End]) OR (EIT.Start <= EIT2.Start AND EIT.[End] >= EIT2.[End])) AND (EIT.EventNo != EIT2.EventNo)

SELECT * FROM EventOfVisitorOfCongress