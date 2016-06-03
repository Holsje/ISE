CREATE PROC spAddSpeakerToCongress
@FirstName D_Name, 
@LastName D_Name, 
@MailAddress D_Mail,
@PhoneNumber D_TELNR ,

@PicturePath D_FILE, 
@Description D_Description,

@CongressNo D_CongressNO, 
@Agreement D_Description 

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
-----------Begin Eigen implementatie
		INSERT INTO Person(FirstName, LastName, MailAddress, PhoneNumber)
		VALUES(@FirstName, @LastName, @MailAddress, @PhoneNumber)
		DECLARE @PersonNo D_PersonNo
		SET @PersonNo = (SELECT PersonNo
						FROM Person
						WHERE MailAddress = @MailAddress)

		INSERT INTO PersonTypeOfPerson(PersonNo, TypeName)
		VALUES(@PersonNo, 'Spreker')

		INSERT INTO Speaker(PersonNo, Description, PicturePath)
		VALUES(@PersonNo, @Description,@PicturePath)

		INSERT INTO SpeakerOfCongress(PersonNo, CongressNo, Agreement)
		VALUES(@PersonNo, @CongressNo, @Agreement)

-----------Eind eigen implementatie
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

--Goed
BEGIN TRAN
EXEC spAddSpeakerToCongress
@FirstName = 'Test',
@LastName = 'Test 2', 
@MailAddress = 'Email@Test1.nl',
@PhoneNumber = '0612345678',

@PicturePath = 'path/img/img.png', 
@Description = 'Dit is een korte beschrijving van een spreker',

@CongressNo = 1, 
@Agreement = 'Dit zijn de afspraken met een spreker'

SELECT * 
FROM Person P INNER JOIN Speaker S
	ON P.PersonNo = S.PersonNo INNER JOIN PersonTypeOfPerson PTOP
		ON P.PersonNo = PTOP.PersonNo INNER JOIN SpeakerOfCongress SOC
			ON SOC.PersonNo = P.PersonNo AND SOC.CongressNo = 1
WHERE P.PersonNo = (SELECT TOP 1 PersonNo 
					FROM Person
					ORDER BY PersonNo DESC)
ROLLBACK

--Fout Congresnummer bestaat niet
BEGIN TRAN
EXEC spAddSpeakerToCongress
@FirstName = 'Test',
@LastName = 'Test 2', 
@MailAddress = 'Email@Test1.nl',
@PhoneNumber = '0612345678',

@PicturePath = 'path/img/img.png', 
@Description = 'Dit is een korte beschrijving van een spreker',

@CongressNo = 543, 
@Agreement = 'Dit zijn de afspraken met een spreker'

SELECT * 
FROM Person P INNER JOIN Speaker S
	ON P.PersonNo = S.PersonNo INNER JOIN PersonTypeOfPerson PTOP
		ON P.PersonNo = PTOP.PersonNo INNER JOIN SpeakerOfCongress SOC
			ON SOC.PersonNo = P.PersonNo AND SOC.CongressNo = 1
WHERE P.PersonNo = (SELECT TOP 1 PersonNo 
					FROM Person
					ORDER BY PersonNo DESC)
SELECT TOP 1 PersonNo 
FROM Person

ROLLBACK