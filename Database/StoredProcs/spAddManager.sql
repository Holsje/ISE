ALTER PROC spAddManager

/*	Isolation level: read committed

	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency.
	Tussen de check of er al een person is en de insert zou er in een andere transactie een person met dezelfde naam toegevoegd kunnen worden.
	In dit geval geeft de stored procedure een primary key error en wordt de transactie gerollbackt, maar dit is niet erg.
	 
*/
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail,
	@password D_Password, 
	@phonenum D_telnr,
	@managerType CHAR(1)
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
			IF NOT EXISTS ( SELECT 1 
							FROM Person
							WHERE MailAddress = @mailAddress)
			BEGIN		
				INSERT INTO Person
				VALUES(@firstname, @lastname, @mailAddress, @phonenum)
			END

			DECLARE @personNo INT = (SELECT PersonNo 
									 FROM PERSON 
									 WHERE  MAILADDRESS = @mailAddress)
			IF NOT EXISTS ( SELECT 1 
							FROM Visitor
							WHERE PersonNo = @personNo)
			BEGIN
				INSERT INTO Visitor(PersonNo,[Password])
				VALUES(@personNo, @password)
			END

			IF @managerType = 'C'
			BEGIN
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Congresbeheerder')
				INSERT INTO CongressManager VALUES(@personNo, @password)
			END
			ELSE IF @managerType = 'G'
			BEGIN
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Algemene beheerder')
				INSERT INTO GeneralManager VALUES(@personNo, @password)
			END
			ELSE IF @managerType = 'A'
			BEGIN
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Algemene beheerder')
				INSERT INTO GeneralManager VALUES(@personNo, @password)
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Congresbeheerder')
				INSERT INTO CongressManager VALUES(@personNo, @password)
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

	SELECT * FROM CongressManager
	SELECT * FROM Person
BEGIN TRAN
EXEC spAddManager
@firstname = 'test',
@lastname = 'testasd',
@mailAddress = 'test@test.nl',
@password = 'rwdsada', 
@phonenum = '123456789',
@managerType = 'A'
ROLLBACK