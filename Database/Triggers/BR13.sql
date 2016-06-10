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
GO
CREATE TRIGGER trRemovePersonTypeOfPersonOnVisitor
  ON Visitor
  AFTER DELETE
  AS 
  BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	BEGIN TRY
		DELETE PTOP 
		FROM PersonTypeOfPerson PTOP
		INNER JOIN deleted D ON D.PersonNo = PTOP.PersonNo
		WHERE PTOP.TypeName = 'Bezoeker'
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
  END
GO
CREATE TRIGGER trRemovePersonTypeOfPersonOnCongressMananger
ON	CongressManager
AFTER DELETE
AS 
BEGIN
IF @@ROWCOUNT = 0 RETURN;
SET NOCOUNT ON;
BEGIN TRY
	DELETE PTOP 
	FROM PersonTypeOfPerson PTOP
	INNER JOIN deleted D ON D.PersonNo = PTOP.PersonNo
	WHERE PTOP.TypeName = 'Congresbeheerder'
END TRY
BEGIN CATCH
	THROW;
END CATCH
END
GO
CREATE TRIGGER trRemovePersonTypeOfPersonOnGeneralManager
ON	GeneralManager
AFTER DELETE
AS 
BEGIN
IF @@ROWCOUNT = 0 RETURN;
SET NOCOUNT ON;
BEGIN TRY
	DELETE PTOP 
	FROM PersonTypeOfPerson PTOP
	INNER JOIN deleted D ON D.PersonNo = PTOP.PersonNo
	WHERE PTOP.TypeName = 'Algemene beheerder'
END TRY
BEGIN CATCH
	THROW;
END CATCH
END

GO
CREATE TRIGGER trRemovePersonTypeOfPersonOnReviewBoard
ON	ReviewBoard
AFTER DELETE
AS 
BEGIN
IF @@ROWCOUNT = 0 RETURN;
SET NOCOUNT ON;
BEGIN TRY
	DELETE PTOP 
	FROM PersonTypeOfPerson PTOP
	INNER JOIN deleted D ON D.PersonNo = PTOP.PersonNo
	WHERE PTOP.TypeName = 'Reviewboard'
END TRY
BEGIN CATCH
	THROW;
END CATCH
END



SELECT * FROM PersonType