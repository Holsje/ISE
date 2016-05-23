--BR4. Er mag maar één event in één room tegelijkertijd plaatsvinden.
CREATE TRIGGER trMultipleEventsAtTheSameTimeInOneBuilding_BR4
  ON EventInRoom
  AFTER INSERT, UPDATE
  AS 
  BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY

		IF EXISTS(SELECT 1
				  FROM Inserted I INNER JOIN EventInRoom EIR 
					ON I.LocationName = EIR.LocationName AND I.City = EIR.City AND I.BName = EIR.BName AND I.RName = EIR.RName
				  

				 )
		BEGIN
			RAISERROR('Error.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
  END

  BEGIN TRAN

  ROLLBACK TRAN


  SELECT * FROM ROOM