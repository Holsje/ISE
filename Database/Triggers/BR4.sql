--BR4. Er mag maar één event in één room tegelijkertijd plaatsvinden.
CREATE TRIGGER trMultipleEventsAtTheSameTimeInOneBuilding_BR4
ON EventInRoom
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

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
			RAISERROR('Er mag maar één event in één room tegelijkertijd plaatsvinden.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
 END

--Testdata BR4.
--Deze gaat fout, omdat event 5 van track 2 van congres 1 in room 101 zou overlappen met:
-- - Event 3 van congres 1 uit track 1 in room 101
BEGIN TRAN
UPDATE EventInRoom
SET RName = 101 WHERE EVENTNO = 5 AND TrackNo = 2 AND CongressNo = 1
ROLLBACK TRAN

--Multiple insert test. We weten dat Event 5 niet naar 101 kan worden gezet (zie boven)
BEGIN TRAN
UPDATE EventInRoom
SET RName = 101
WHERE EventNo > 6 AND CongressNo = 1
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