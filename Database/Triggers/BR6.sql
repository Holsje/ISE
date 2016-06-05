CREATE TRIGGER trDeleteSubjectFromEvent
ON SubjectOfEvent
AFTER UPDATE,DELETE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY
		DELETE FROM Subject
		WHERE Subject IN(	SELECT S.Subject 
							FROM deleted D INNER JOIN Subject S
								ON D.Subject = S.Subject
							WHERE NOT EXISTS(	SELECT 1
												FROM SubjectOfCongress SOC
												WHERE SOC.Subject = D.Subject) AND NOT EXISTS(	SELECT 1
																								FROM SubjectOfEvent SOE
																								WHERE SOE.Subject = D.Subject)) 
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END

GO

CREATE TRIGGER trDeleteSubjectFromCongress
ON SubjectOfCongress
AFTER UPDATE,DELETE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;

	BEGIN TRY
		DELETE FROM Subject
		WHERE Subject IN(	SELECT S.Subject 
							FROM deleted D INNER JOIN Subject S
								ON D.Subject = S.Subject
							WHERE NOT EXISTS(	SELECT 1
												FROM SubjectOfCongress SOC
												WHERE SOC.Subject = D.Subject) AND NOT EXISTS(	SELECT 1
																								FROM SubjectOfEvent SOE
																								WHERE SOE.Subject = D.Subject)) 
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END

--Goed verwijderd Javascript uit SubjectOfEvent en Subject
BEGIN TRAN
DELETE FROM  SubjectOfEvent
WHERE [Subject] = 'Javascript'  AND (CongressNo <> 1 OR EventNo <> 1) 
SELECT * FROM Subject
DELETE FROM SubjectOfEvent
WHERE [Subject] = 'Javascript' AND CongressNo = 1 AND EventNo = 1
SELECT * FROM Subject
ROLLBACK

--Goed verwijderd ICT van Congres 2 alleen uit SubjectOfEvent
BEGIN TRAN
DELETE FROM SubjectOfEvent
WHERE [Subject] = 'ICT' AND (CongressNo <> 2 OR EventNo <> 5)

SELECT * FROM SubjectOfEvent

DELETE FROM SubjectOfEvent
WHERE [Subject] = 'ICT' AND (CongressNo = 2 AND EventNo = 5)

SELECT * From Subject
SELECT * FROM SubjectOfCongress
SELECT * FROM SubjectOfEvent
ROLLBACK

--Goed verwijderd ICT alleen uit SubjectOfCongress
BEGIN TRAN
DELETE FROM SubjectOfCongress
WHERE Subject = 'ICT'

DELETE FROM SubjectOfEvent
WHERE [Subject] = 'ICT' AND (CongressNo = 2 AND EventNo = 5)
SELECT * FROM SubjectOfEvent
SELECT * FROM Subject
ROLLBACK


--------Congres----------------------

--Goed verwijderd Datamodeling ook uit tabel Subject
BEGIN TRAN
DELETE FROM SubjectOfCongress
WHERE Subject = 'DataModeling' AND CongressNo != 1

DELETE FROM SubjectOfCongress
WHERE Subject = 'DataModeling' AND CongressNo = 1

SELECT * From Subject
SELECT * FROM SubjectOfCongress
SELECT * FROM SubjectOfEvent
ROLLBACK

--Goed verwijderd ICT van Congres 1 alleen uit SubjectOfCongress
BEGIN TRAN
DELETE FROM SubjectOfCongress
WHERE Subject = 'ICT' AND CongressNo=1

SELECT * From Subject
SELECT * FROM SubjectOfCongress
SELECT * FROM SubjectOfEvent
ROLLBACK

--Goed verwijderd ICT alleen uit SubjectOfCongress
BEGIN TRAN
DELETE FROM SubjectOfCongress
WHERE Subject = 'ICT'

SELECT * From Subject
SELECT * FROM SubjectOfCongress
SELECT * FROM SubjectOfEvent
ROLLBACK