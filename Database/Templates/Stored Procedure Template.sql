CREATE PROC SaveTranExample
AS
BEGIN
	SET NOCOUNT ON;
	/* Detect wether the procedure was called from an active transaction and save
	that for later use. In the procedure, @TranCounter = 0 means there was no active
	transaction and the procedure started one. @TranCounter > 0 means an active
	transaction was started before the procedure was called. */
	DECLARE @TranCounter INT;
	SET @TranCounter = @@TRANCOUNT;

	IF @TranCounter > 0
		/* Procedure called when there is an active transaction. Create a savepoint
		to be able to roll back only the work done in the procedure if there is an error. */
		SAVE TRANSACTION ProcedureSave;
	ELSE
		/* Procedure must start its own transaction */
		BEGIN TRANSACTION;

	BEGIN TRY
		/* Database actions executed here.
		
		Get here if no errors: must commit any transaction started in the procedure,
		but no commit a transaction started before the transaction was called. */
		IF @TranCounter = 0 AND XACT_STATE() = 1
			/* @TranCounter = 0 means no transaction was started before the procedure
			was called. The procedure must commit the transaction it started. */
			COMMIT TRANSACTION;
	END TRY
	BEGIN CATCH
		/* An error occured; must determine which type of rollback will roll
		back only the work done in the procedure. */
		IF @TranCounter = 0 
		BEGIN
			IF XACT_STATE() = 1
			/* Transaction started in procedure. Roll back complete transaction but only if Transaction is still valid. */
				ROLLBACK TRANSACTION;
		END
		ELSE
			/* Transaction started before procedure called, do not roll back
			modifications made before the procedure was called. */
			IF XACT_STATE() <> -1
				/* If the transaction is still valid, just roll back to the savepoint set at the start of the stored procedure. */
				ROLLBACK TRANSACTION ProcedureSave;
			/* If the transaction is uncommitable, a rollback to the savepoint
			is not allowed because the savepoint rollback writes to the log.
			Just return to the caller, which should roll back the outer transaction. */
		THROW;
	END CATCH
END