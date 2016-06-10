CREATE PROC spAddCongressManagerToCongress

/*  Isolation level: read committed
	
	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency. In de eerste select wordt er op personNo gezocht. 
	Dit is een identity column en daardoor kan deze niet tussentijds veranderen.
*/

@PersonNo D_PersonNo, @CongressNo D_CongressNo
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
						FROM CongressManager
						WHERE PersonNo = @PersonNo)
		BEGIN
			INSERT INTO CongressManager (PersonNo, Password) VALUES (@PersonNo, (SELECT password FROM GeneralManager WHERE PersonNo = @personNo))
		END

		INSERT INTO CongressManagerOfCongress(PersonNo,CongressNo)
		VALUES(@PersonNo,@CongressNo)

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


-- Goed moet nieuwe congresmanager toevoegen in CongressManager en CongressManagerOfCongress.
BEGIN TRAN
	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress
	EXEC spAddCongressManagerToCongress
	@PersonNo = 1,
	@CongressNo = 1

	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress

ROLLBACK TRAN

--Goed moet toevoegen in CongressManagerOfCongress niet in CongressManager.
BEGIN TRAN
	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress
	EXEC spAddCongressManagerToCongress
	@PersonNo = 3,
	@CongressNo = 1

	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress
ROLLBACK TRAN

	EXEC spAddCongressManagerToCongress
	@PersonNo = 3,
	@CongressNo = 40