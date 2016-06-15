CREATE PROC spDeleteCongressManagerOfCongress
@CongressNo D_CongressNo, 
@PersonNo D_PersonNo
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
