CREATE PROC spRegisterSpeaker 
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@congressno D_CongressNo,
	@agreement D_DESCRIPTION,
	@description D_DESCRIPTION,
	@picturePath D_FILE
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
			INSERT INTO PERSON VALUES(@firstname, @lastname, @mailAddress, @phonenum)
			DECLARE @personNo INT = (SELECT PersonNo 
									 FROM PERSON 
									 WHERE FIRSTNAME = @firstname AND LASTNAME = @lastname AND MAILADDRESS = @mailAddress AND PHONENUMBER = @phonenum)
			INSERT INTO PERSONTYPEOFPERSON VALUES(@personNo, 'Spreker')
			INSERT INTO Speaker VALUES(@personNo,@description,@picturePath)
			INSERT INTO SpeakerOfCongress VALUES(@personNo, @congressno,@agreement)
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