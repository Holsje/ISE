CREATE PROC spUpdateSpeaker 
	@personNo D_Personno,
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@agreement D_DESCRIPTION,
	@description D_DESCRIPTION,
	@picturePath D_FILE,
	@congressno D_CongressNo
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
		
		UPDATE SpeakerOfCongress 
		SET Agreement = @agreement
		WHERE PersonNo = @personNo AND CongressNo = @congressno

		UPDATE Speaker 
		SET Description = @description, PicturePath = @picturePath
		WHERE PersonNo = @personNo

		UPDATE Person
		SET FirstName = @firstname, LastName = @lastname, MailAddress = @mailAddress, PhoneNumber = @phonenum
		WHERE PersonNo = @personNo

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