/*Moet geupdatet worden*/

CREATE PROC spInsertCongress
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
	IF NOT EXISTS(SELECT 1 FROM [Subject] WHERE [Subject] = @subject) 
	BEGIN		
		INSERT INTO [Subject] 
		VALUES(@subject)
	END
	INSERT INTO Congress(Name,Location,[Subject],StartDate,EndDate) 
	VALUES(@name,@location,@subject,@startDate,@endDate)

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