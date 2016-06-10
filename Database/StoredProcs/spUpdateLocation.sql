CREATE PROC spUpdateLocation 

/*	Isolation level: read committed

	Er wordt een error opgegooid bij de eerste check en anders wordt er een update statement gedaan.
	Er kan hier niets misgaan op het gebied van concurrency.
*/

	@locationName D_NAME, 
	@city D_LOCATION, 
	@oldLocationName D_NAME,
	@oldCity D_LOCATION
AS
BEGIN
	SET NOCOUNT ON;

	DECLARE @TranCounter INT;
	SET @TranCounter = @@TRANCOUNT;

	IF @TranCounter > 0
		SAVE TRANSACTION ProcedureSave;
	ELSE
		BEGIN TRANSACTION;
	BEGIN TRY
		IF NOT EXISTS(	SELECT 1 
						FROM Location
						WHERE LocationName = @oldlocationName AND City = @oldcity)
		BEGIN
			RAISERROR('Tijdens het opslaan zijn er nog wijzigingen doorgevoerd',16,2);
		END

		UPDATE Location 
		SET LocationName = @locationName, City = @city
		WHERE LocationName = @oldLocationName AND City = @oldCity
		
		IF @TranCounter = 0 AND XACT_STATE() = 1
			COMMIT TRANSACTION;
	END TRY
	BEGIN CATCH
		IF @TranCounter = 0 
		BEGIN
			IF XACT_STATE() = 1
				ROLLBACK TRANSACTION;
		END
		ELSE
			IF XACT_STATE() <> -1
				ROLLBACK TRANSACTION ProcedureSave;
		THROW;
	END CATCH
END

--Testdata
--Goede update
BEGIN TRAN
EXEC spUpdateLocation 'HAN Campus', 'Arnhem', 'HAN', 'Nijmegen'
SELECT * FROM Location
ROLLBACK TRAN

--Volgende twee aanroepen zijn om de lost updates te demonstreren.
--Start beide transacties en voer dan beide sp uit.
BEGIN TRAN
EXEC spUpdateLocation 'HAN Campus', 'Nijmegen', 'HAN', 'Nijmegen'
--ROLLBACK TRAN

BEGIN TRAN
EXEC spUpdateLocation 'HAN Campus', 'Arnhem', 'HAN', 'Nijmegen'
--ROLLBACK TRAN