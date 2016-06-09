ALTER PROC spUpdateSpeakerSpeakerOfCongress
	@personNo D_Personno,
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@agreement D_DESCRIPTION,
	@description D_DESCRIPTION,
	@fileUploaded BIT,
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
		
		IF(NOT EXISTS(SELECT 1 FROM Speaker WHERE PersonNo = @personNo AND Owner = @owner)) AND NOT EXISTS(SELECT 1 FROM PersonTypeOfPerson WHERE PersonNo = @owner AND TypeName = 'Algemene beheerder')
		BEGIN
			RAISERROR('Je kan alleen je eigen sprekers aanpassen',16,1);
		END
		
		UPDATE SpeakerOfCongress 
		SET Agreement = @agreement
		WHERE PersonNo = @personNo AND CongressNo = @congressno

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

BEGIN TRAN 
	--Expected succes, all data should have be changed. He's the owner AND the global manager
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,1
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --He's not the owner but is a global manager, all data should been changed
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,2
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --He's the owner but not a global manager, all data should been changed
	UPDATE Speaker SET Owner = 5 WHERE PersonNo = 1
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,5
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN


BEGIN TRAN --He's not in this congress, agreement should not change, everything else should
	UPDATE Speaker SET Owner = 5 WHERE PersonNo = 1
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,9,5
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --Picturepath should change to img/Speakers/speaker1.png
	UPDATE Speaker SET PicturePath = NULL
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,2
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN


BEGIN TRAN --Picturepath should stay null
	UPDATE Speaker SET PicturePath = NULL
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',0,1,2
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --He's not the owner, and no global manager. He should not be alowed to edit.
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,5
	GO
ROLLBACK TRAN