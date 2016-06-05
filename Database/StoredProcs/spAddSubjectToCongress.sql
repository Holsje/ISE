CREATE PROC spAddSubjectToCongress
@Subject D_Subject, @CongressNo D_CongressNo
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
		IF NOT EXISTS(	SELECT 1
						FROM Subject
						WHERE Subject = @Subject)
		BEGIN
			INSERT INTO Subject
			VALUES(@Subject)
		END

		INSERT INTO SubjectOfCongress(Subject,CongressNo)
		VALUES(@Subject,@CongressNo)

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


-- Goed moet nieuw subject toevoegen in Subject en SubjectOfCongress.
BEGIN TRAN
	EXEC addSubjectToCongress
	@CongressNo = 1,
	@Subject = 'test'

	SELECT * FROM Subject
	SELECT * FROM SubjectOfCongress

ROLLBACK

--Goed moet toevoegen in SubjectOfCongress niet in Subject.
BEGIN TRAN
	EXEC addSubjectToCongress
	@CongressNo = 1,
	@Subject = 'Data'

	SELECT * FROM Subject
	SELECT * FROM SubjectOfCongress
ROLLBACK