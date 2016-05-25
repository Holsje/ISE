CREATE PROC spRegisterVisitor 
	@firstname D_Name, @lastname D_Name, 
	@mailAddress D_Mail, @phonenum D_telnr,
	@password D_password, @haspaid D_Boolean,
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
		DECLARE @personNo INT = (SELECT PersonNo 
							     FROM PERSON 
								 WHERE FIRSTNAME = @firstname AND LASTNAME = @lastname AND MAILADDRESS = @mailAddress AND PHONENUMBER = @phonenum)
		
		IF NOT EXISTS(SELECT 1 FROM PERSON WHERE PERSONNO = @personNo)
		BEGIN
			INSERT INTO PERSON VALUES(@firstname, @lastname, @mailAddress, @phonenum)
			INSERT INTO PERSONTYPEOFPERSON VALUES(@personNo, 'Bezoeker')
			INSERT INTO VISITOR VALUES(@personNo, @password)
			INSERT INTO VISITOROFCONGRESS VALUES(@personNo, @congressno, @haspaid)
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

