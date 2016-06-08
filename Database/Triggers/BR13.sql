--BR 13
CREATE TRIGGER trRemovePersonTypeOfPersonOnSpeaker
  ON Speaker
  AFTER DELETE
  AS 
  BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	BEGIN TRY
		DELETE PTOP 
		FROM PersonTypeOfPerson PTOP
		INNER JOIN deleted D ON D.PersonNo = PTOP.PersonNo
		WHERE PTOP.TypeName = 'Spreker'
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
  END

/* Testdata */
BEGIN TRAN
	SELECT * FROM PersonTypeOfPerson
	DELETE FROM SpeakerOfEvent
	DELETE FROM SpeakerOfCongress WHERE PersonNo = 1
	DELETE FROM Speaker WHERE PersonNo = 1
	SELECT * FROM PersonTypeOfPerson
ROLLBACK TRAN

BEGIN TRAN
	SELECT * FROM PersonTypeOfPerson
	DELETE FROM SpeakerOfEvent
	DELETE FROM SpeakerOfCongress WHERE PersonNo = 1 OR PersonNo = 3 OR PersonNo = 4
	DELETE FROM Speaker WHERE PersonNo = 1 OR PersonNo = 3 OR PersonNo = 4
	SELECT * FROM PersonTypeOfPerson
ROLLBACK TRAN

SELECT * FROM SPEAKER