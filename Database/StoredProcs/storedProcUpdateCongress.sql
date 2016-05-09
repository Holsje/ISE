ALTER PROC spUpdateCongress
	@congressNo D_CongressNo,
	@name D_Name,
	@subject D_Subject,
	@location D_Location,
	@startDate D_Date,
	@EndDate D_Date
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
	IF NOT EXISTS(SELECT 1 FROM Congress WHERE CongressNo = @congressNo)
	BEGIN
		RAISERROR('Congress does not exist',16,2);
	END

	IF NOT EXISTS(SELECT 1 FROM [Subject] WHERE [Subject] = @subject) 
	BEGIN			
		INSERT INTO [Subject] 
		VALUES(@subject)
	END
	
	--IF subject is changed
	IF NOT EXISTS(SELECT 1 FROM Congress WHERE CongressNo = @congressNo AND [Subject] = @subject)
	BEGIN
		DECLARE @oldSubject D_Subject;
		SET @oldSubject = (SELECT [Subject] FROM Congress WHERE CongressNo = @congressNo)
	END
	
	UPDATE Congress SET Name = @name,LOCATION = @location, [Subject] = @subject,StartDate = @startDate, EndDate = @EndDate WHERE CongressNo = @congressNo	
	
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