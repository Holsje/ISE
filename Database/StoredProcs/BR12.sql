CREATE PROC spDeleteCongressManagerOfCongress
@CongressNo D_CongressNo, 
@PersonNo D_PersonNo

/*  Isolation level: Serializable
	
	Omdat er een selectie op de hoeveelheid regels gedaan wordt gaat de select niet over één waarde. Hierdoor kunnen phantoms voorkomen. 
	Om dit te voorkomen moet een rangelock gezet worden.
	
*/
AS
BEGIN
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
	DECLARE @TranCounter INT;
	SET @TranCounter = @@TRANCOUNT;

	IF @TranCounter > 0
		SAVE TRANSACTION ProcedureSave;
	ELSE
		BEGIN TRANSACTION;

	BEGIN TRY
		IF (SELECT COUNT(*)
					FROM CongressManagerOfCongress
					WHERE CongressNo = @CongressNo ) = 1
		BEGIN
			RAISERROR('U kunt deze congresbeheerder niet verwijderen. Elk congres moet minimaal één congresbeheerder hebben.',16,1)
		END

		DELETE FROM CongressManagerOfCongress
		WHERE CongressNo = @CongressNo AND PersonNo = @PersonNo

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

BEGIN TRAN
EXEC spDeleteCongressManagerOfCongress
@CongressNo = 1,
@PersonNo = 5
ROLLBACK
