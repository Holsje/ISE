CREATE PROC spUpdateSpeakerSpeakerOfCongress
	@personNo D_Personno,
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@agreement D_DESCRIPTION,
	@description D_DESCRIPTION,
	@fileExtension varchar(5),
	@congressno D_CongressNo,
	@owner D_Personno
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
		
		IF(NOT EXISTS(SELECT 1 FROM Speaker WHERE PersonNo = @personNo AND Owner = @owner)) OR EXISTS(SELECT 1 FROM PersonTypeOfPerson WHERE PersonNo = @personNo AND TypeName = 'Algemene beheerder')
		BEGIN
			RAISERROR('Je kan alleen je eigen sprekers aanpassen',16,1);
		END

		UPDATE SpeakerOfCongress 
		SET Agreement = @agreement
		WHERE PersonNo = @personNo AND CongressNo = @congressno

		IF (@fileExtension IS NOT NULL AND @fileExtension != '')
		BEGIN
			UPDATE Speaker 
			SET PicturePath = 'img/Speakers/speaker' + CAST(@personNo AS VARCHAR) + '.' +  @fileExtension
			WHERE PersonNo = @personNo
		END

		UPDATE Speaker 
			SET Description = @description
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