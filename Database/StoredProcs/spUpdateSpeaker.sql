CREATE PROC spUpdateSpeaker
	@personNo D_Personno,
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@description D_DESCRIPTION,
	@fileUploaded BIT
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
		IF (@fileUploaded = 1)
		BEGIN
			UPDATE Speaker 
			SET PicturePath = 'img/Speakers/speaker' + CAST(@personNo AS VARCHAR) + '.png'
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

--Testdata
--Update van speaker 1, verwacht dat Erik verandert naar Testnaam en dat description NULL wordt
BEGIN TRAN
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
EXECUTE spUpdateSpeaker 1, 'Testnaam', 'Testnaam', 'testmail@mail.com', '123456789', null, 0
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
ROLLBACK TRAN

--Update van speaker 1, verwacht dat PicturePath
BEGIN TRAN
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
EXECUTE spUpdateSpeaker 1, 'Testnaam', 'Testnaam', 'testmail@mail.com', '123456789', null, 1
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
ROLLBACK TRAN