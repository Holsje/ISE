CREATE PROC spRegisterVisitor 
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@password D_password
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
		IF NOT EXISTS(SELECT 1 FROM Person WHERE mailAddress = @mailAddress)
		BEGIN
			INSERT INTO PERSON VALUES(@firstname, @lastname, @mailAddress, @phonenum)
		END
		
		DECLARE @personNo INT =	(SELECT PersonNo 
								 FROM PERSON 
								 WHERE MailAddress = @mailAddress)
		
		IF NOT EXISTS(SELECT 1 FROM PersonTypeOfPerson WHERE PersonNo = @personNo AND TypeName = 'Bezoeker')
		BEGIN
			INSERT INTO PERSONTYPEOFPERSON VALUES(@personNo, 'Bezoeker')
		END
		
		IF NOT EXISTS(SELECT 1 FROM Visitor WHERE PersonNo = @personNo)
		BEGIN
			INSERT INTO VISITOR VALUES(@personNo, @password)
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
--Insert waarbij de persoon nog niet bestaat
BEGIN TRAN
SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@gmail.com'
SELECT * FROM Visitor WHERE PersonNo IN(SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
EXECUTE spRegisterVisitor 'Niels', 'Bergervoet', 'nielsbergervoet@gmail.com', '0612314567', 'wachtwoord'

SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@gmail.com'
SELECT * FROM Visitor WHERE PersonNo IN(SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
ROLLBACK TRAN

--Insert waarbij de persoon al wel in Person staat, niet in Visitor en ook niet als bezoeker in PersonTypeOfPerson. 
--Aan het eind van het registreren moet een record in alle drie tabellen te vinden zijn van deze persoon
BEGIN TRAN
SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com'
SELECT * FROM Visitor WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
EXECUTE spRegisterVisitor 'Niels', 'Bergervoet', 'nielsbergervoet@hotmail.com', '0612314567', 'wachtwoord'

SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com'
SELECT * FROM Visitor WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
ROLLBACK TRAN