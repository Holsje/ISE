CREATE PROC spRegisterSpeaker 


/*	Isolation level: read committed

	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency.
	Tussen de check of er al een person is en de insert zou er in een andere transactie een person met dezelfde naam toegevoegd kunnen worden.
	In dit geval geeft de stored procedure een primary key error en wordt de transactie gerollbackt, maar dit is niet erg.
	 
*/

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

	--Testdata
--Insert waarbij de persoon al in Person bestaat en ook al in PersonTypeOfPerson. Verwacht een UNIQUE constraint
BEGIN TRAN
	SELECT * FROM Person
	SELECT * FROM PersonTypeOfPerson
	SELECT * FROM Speaker

	EXECUTE spRegisterSpeaker 'Erik', 'Evers', 'erikevers1996@gmail.com', '0613334002', 'Omschrijving van erik', 1, null

	SELECT * FROM Person
	SELECT * FROM PersonTypeOfPerson
	SELECT * FROM Speaker
ROLLBACK TRAN

--Insert waarbij de persoon nog niet in Person bestaat en waar geen file geupload is. Verwacht null In speaker - Picturepath. Owner is Erik met personNo 1
BEGIN TRAN
	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'

	DECLARE @personNoSpeaker INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PersonNo = @personNoSpeaker
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker

	EXECUTE spRegisterSpeaker 'TestnaamSP', 'TestnaamSP', 'testmail@mail.com', '0613334002', 'Omschrijving', 0, 1

	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'
	
	DECLARE @personNoSpeaker2 INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PErsonNo = @personNoSpeaker2
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker2
ROLLBACK TRAN

--Insert waarbij er wel een file geupload is
BEGIN TRAN
	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'

	DECLARE @personNoSpeaker3 INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PersonNo = @personNoSpeaker3
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker3

	EXECUTE spRegisterSpeaker 'TestnaamSP', 'TestnaamSP', 'testmail@mail.com', '0613334002', 'Omschrijving', 1, 1

	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'
	
	DECLARE @personNoSpeaker4 INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PErsonNo = @personNoSpeaker4
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker4
ROLLBACK TRAN