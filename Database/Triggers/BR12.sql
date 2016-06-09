CREATE TRIGGER trCongressAlwaysHasCongressManager_BR12
  ON CongressManagerOfCongress
  AFTER UPDATE, DELETE
  AS 
  BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY
		IF NOT EXISTS(SELECT 1 
					 FROM Congress C INNER JOIN deleted d 
					 ON d.CongressNo = C.CongressNo INNER JOIN CongressManagerOfCongress CMOC
					 ON CMOC.CongressNo = C.CongressNo)
		BEGIN
			RAISERROR('Een congres moet altijd een congresbeheerder hebben.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END

--Testdata
--Mag niet, want anders zou Congres 1 geen congresmanager hebben
  BEGIN TRAN
	DELETE FROM CongressManagerOfCongress WHERE PersonNo = 5 AND CongressNo = 1
  ROLLBACK TRAN

--Multiple delete waar congres 1 geen congresmanager heeft
BEGIN TRAN
	INSERT INTO CongressManager VALUES(1, 'wachtwoord'), (2, 'wachtwoord')
	INSERT INTO CongressManagerOfCongress VALUES(1, 1), (2, 1);
	
	DELETE FROM CongressManagerOfCongress WHERE CongressNo = 1
ROLLBACK TRAN

--Multiple delete waar één congresbeheerder overblijft bij congres 1
BEGIN TRAN
	INSERT INTO CongressManager VALUES(1, 'wachtwoord'), (2, 'wachtwoord')
	INSERT INTO CongressManagerOfCongress VALUES(1, 1), (2, 1);
	
	DELETE FROM CongressManagerOfCongress WHERE (PersonNo = 1 AND CongressNo = 1) OR (PersonNo = 2 AND CongressNo = 1)
ROLLBACK TRAN