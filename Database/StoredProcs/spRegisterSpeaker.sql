ALTER PROC spRegisterSpeaker 
		@firstname D_Name, 
		@lastname D_Name, 
		@mailAddress D_Mail, 
		@phonenum D_telnr,
		@description D_DESCRIPTION,
		@fileUploaded BIT,
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
			IF EXISTS ( SELECT 1 
						FROM Person
						WHERE MailAddress != @mailAddress)
			BEGIN		
				INSERT INTO PERSON 
				VALUES(@firstname, @lastname, @mailAddress, @phonenum)
			END

			DECLARE @personNo INT = (SELECT PersonNo 
									 FROM PERSON 
									 WHERE  MAILADDRESS = @mailAddress)
			INSERT INTO PERSONTYPEOFPERSON VALUES(@personNo, 'Spreker')
			IF @fileUploaded = 1
			BEGIN
				INSERT INTO Speaker 
				VALUES(@personNo,@description,'img/Speakers/speaker' + CAST(@personNo AS VARCHAR) + '.png' ,@owner)
			END
			ELSE
			BEGIN
				INSERT INTO Speaker 
				VALUES(@personNo,@description,null,@owner)
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