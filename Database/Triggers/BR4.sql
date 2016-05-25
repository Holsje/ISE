--BR4. Er mag maar ��n event in ��n room tegelijkertijd plaatsvinden.
/*
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInRoom en uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen van een room van een event.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Het isolation level repeatable read zal hier dan wel voldoende zijn. Die houdt namelijk de s-lock vast tot het einde van de transactie.
	Het einde van de transactie is na dat de trigger is uitgevoerd door de auto commit.
	Daardoor kan niet voor het raisen van de error eventueel iets aangepast worden waardoor de melding onterecht is.

*/

CREATE TRIGGER trMultipleEventsAtTheSameTimeInOneBuilding_BR4
ON EventInRoom
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
	BEGIN TRY	
		IF EXISTS(
			SELECT 1
			FROM EventInRoom EIR INNER JOIN Inserted I 
			ON EIR.LocationName = I.LocationName AND EIR.City = I.City AND EIR.BName = I.BName AND EIR.RName = I.RName
			WHERE EIR.EventNo IN (SELECT ET.EventNo
									FROM EventInTrack ET INNER JOIN EventInTrack ET2
									ON ET.EventNo != ET2.EventNo AND ET2.EventNo = I.EventNo
									WHERE (ET2.Start > ET.Start AND ET2.Start < ET.[End]) OR
										(ET2.[End] > ET.Start AND ET2.[End] < ET.[End]) OR
										(ET2.Start <= ET.Start AND ET2.[End] >= ET.[End]))			
		)		
		BEGIN
			RAISERROR('Er mag maar ��n event in ��n room tegelijkertijd plaatsvinden.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
 END

--Testdata BR4.
--Goede insert
BEGIN TRAN
	INSERT INTO Event (CongressNo, EventNo, EName, Type, MaxVisitors, Price, FileDirectory, Description) VALUES (2, 100, 'Test Event', 'Lezing', 40, NULL, 'img/', 'Omschrijving'),
																												(2, 101, 'Test Event2', 'Lezing', 40, NuLL, 'img/', 'Omschrijving');

	INSERT INTO EventInTrack (TRA_CongressNo, TrackNo, CongressNo, EventNo, Start, [End]) VALUES (2, 1, 2, 100, '2013-04-04 07:00:00', '2013-04-04 08:00:00'),
																								 (2, 2, 2, 101, '2013-04-04 08:00:00', '2013-04-04 09:00:00');

	INSERT INTO EventInRoom (CongressNo, TrackNo, EventNo, LocationName, City, BName, RName, TRA_CongressNo) VALUES (2, 1, 100, 'HAN', 'Nijmegen', 'PABO', '201', 2),
																													(2, 2, 101, 'HAN', 'Nijmegen', 'PABO', '201', 2); 
ROLLBACK TRAN

--Deze gaat fout, omdat event 5 van track 2 van congres 1 in room 101 zou overlappen met:
-- - Event 3 van congres 1 uit track 1 in room 101
BEGIN TRAN
	UPDATE EventInRoom
	SET RName = 101 WHERE EVENTNO = 5 AND TrackNo = 2 AND CongressNo = 1
ROLLBACK TRAN

--Multiple insert test. 
BEGIN TRAN
	UPDATE EventInRoom 
	SET RName = 203
	WHERE CongressNo = 2 AND ((TrackNo = 1 AND EventNo = 1) OR (TrackNo = 2 AND EventNo = 4))
ROLLBACK TRAN


--Voorbeeld van de query resultaat in de trigger wanneer de volgende query wordt uitgevoerd 
--(eerst trigger uitzetten en dan onderstaande update uitvoeren):
/*UPDATE EventInRoom 
  SET RName = 101 
  WHERE EVENTNO = 5 AND TrackNo = 2 AND CongressNo = 1 
*/
SELECT *
FROM EventInRoom EIR INNER JOIN (SELECT * FROM EventInRoom WHERE RName = 101 AND EVENTNO = 5 AND TRACKNO = 2 AND CONGRESSNO = 1) I 
ON EIR.LocationName = I.LocationName AND EIR.City = I.City 
	AND EIR.BName = I.BName AND EIR.RName = I.RName
WHERE EIR.EventNo IN (SELECT ET.EventNo
					  FROM EventInTrack ET INNER JOIN EventInTrack ET2
						ON ET.EventNo != ET2.EventNo AND ET2.EventNo = (SELECT EventNo 
																		FROM EventInRoom 
																		WHERE RName = 102 AND EVENTNO = 5 
																		AND TRACKNO = 2 AND CONGRESSNO = 1)
					  WHERE (ET2.Start > ET.Start AND ET2.Start < ET.[End]) OR
						  (ET2.[End] > ET.Start AND ET2.[End] < ET.[End]) OR
						  (ET2.Start <= ET.Start AND ET2.[End] >= ET.[End]))