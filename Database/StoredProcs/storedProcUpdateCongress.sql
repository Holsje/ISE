/*Moet geupdatet worden*/

CREATE PROC spUpdateCongress
	@congressNo D_CongressNo,
	@name D_Name,
	@subject D_Subject,
	@location D_Location,
	@startDate D_Date,
	@endDate D_Date,

	@oldName D_Name,
	@oldSubject D_Subject,
	@oldLocation D_Location,
	@oldstartDate D_Date,
	@oldEndDate D_Date
AS
BEGIN
	SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
	SET NOCOUNT ON;
	DECLARE @TranCounter INT;
	SET @TranCounter = @@TRANCOUNT;

	IF @TranCounter > 0
		SAVE TRANSACTION ProcedureSave;
	ELSE
		BEGIN TRANSACTION;

	BEGIN TRY
		IF NOT EXISTS(SELECT 1 FROM Congress WHERE CongressNo = @congressNo)
		BEGIN
			RAISERROR('Congress does not exist',16,2);
		END

		IF NOT EXISTS(	SELECT 1 
						FROM Congress
						WHERE congressNo = @congressNo AND name = @oldName AND [subject] = @oldSubject AND location = @oldLocation AND startDate = @oldstartDate AND endDate = @oldEndDate)
		BEGIN
			RAISERROR('Tijdens het opslaan zijn er nog wijzigingen doorgevoerd',16,2);
		END

		IF NOT EXISTS(SELECT 1 FROM [Subject] WHERE [Subject] = @subject) 
		BEGIN			
			INSERT INTO [Subject] 
			VALUES(@subject)
		END
	
		UPDATE Congress SET Name = @name,LOCATION = @location, [Subject] = @subject,StartDate = @startDate, EndDate = @endDate WHERE CongressNo = @congressNo	
	
		IF NOT EXISTS(SELECT 1 FROM Congress WHERE CongressNo = @congressNo AND [Subject] = @subject)
		BEGIN
			IF NOT EXISTS(SELECT 1 FROM Congress WHERE [Subject] = @oldSubject)
			BEGIN
				DELETE FROM [Subject] WHERE [Subject] = @oldSubject
			END
		END
	
	
		
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