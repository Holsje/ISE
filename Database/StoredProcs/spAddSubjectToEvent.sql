CREATE PROC addSubjectToEvent
@Subject D_Subject, @CongressNo D_CongressNo, @EventNo D_EventNo
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

		INSERT INTO SubjectOfEvent(Subject, CongressNo, EventNo)
		VALUES(@Subject,@CongressNo,@EventNo)

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

-- Goed moet nieuw subject 'test' toevoegen in Subject en SubjectOfEvent.
BEGIN TRAN
	EXEC addSubjectToEvent
	@CongressNo = 1,
	@Subject = 'test',
	@EventNo = 1

	SELECT * FROM Subject
	SELECT * FROM SubjectOfEvent

ROLLBACK

--Goed moet 'Data' toevoegen in SubjectOfEvent niet in Subject.
BEGIN TRAN
	EXEC addSubjectToEvent
	@CongressNo = 1,
	@Subject = 'Data',
	@EventNo = 1

	SELECT * FROM Subject
	SELECT * FROM SubjectOfEvent
ROLLBACK