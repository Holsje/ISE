CREATE PROC spRegisterVisitor 
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@password D_password,
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
		IF EXISTS(
			SELECT 1 
			FROM Person 
			WHERE FIRSTNAME = @firstname AND LASTNAME = @lastname AND MAILADDRESS = @mailAddress AND PHONENUMBER = @phonenum
		)
		RAISERROR('Er is al een persoon die zich met deze gegevens heeft geregistreerd.', 16, 1);
		ELSE
			INSERT INTO PERSON VALUES(@firstname, @lastname, @mailAddress, @phonenum)
			DECLARE @personNo INT = (SELECT PersonNo 
									 FROM PERSON 
									 WHERE FIRSTNAME = @firstname AND LASTNAME = @lastname AND MAILADDRESS = @mailAddress AND PHONENUMBER = @phonenum)
			INSERT INTO PERSONTYPEOFPERSON VALUES(@personNo, 'Bezoeker')
			INSERT INTO VISITOR VALUES(@personNo, @password)
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