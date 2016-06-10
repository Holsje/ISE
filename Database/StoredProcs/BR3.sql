CREATE PROC spPublishCongress
	@congressno D_CONGRESSNO
AS
BEGIN
--BR3 Alle velden moeten ingevuld zijn.

/*
	Isolation level: Serializable
	Uitgaande van repeatable read:
	Wanneer alle waardes gecontroleerd worden en er wordt een nieuwe 
	bijvoorbeeld track toegevoegd die leeg is dan zit er geen range lock op.
	Wanneer het Serializable is dan komt er wel een range lock op.

*/
	SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
	SET NOCOUNT ON;
	DECLARE @TranCounter INT;
	SET @TranCounter = @@TRANCOUNT;

	IF @TranCounter > 0
		SAVE TRANSACTION ProcedureSave;
	ELSE
		BEGIN TRANSACTION;

	BEGIN TRY
		DECLARE @test VARCHAR(1000);
		SET @test = 'ERROR';
		--Check if congress has all mandatory fields
		IF EXISTS(SELECT 1 FROM Congress 
					WHERE CongressNo = @congressno AND (LocationName IS NULL OR City IS NULL 
					OR Startdate IS NULL OR Enddate IS NULL OR Description IS NULL OR Banner IS NULL OR PRICE IS NULL)) 
		BEGIN
			SET @test+= 'Congress heeft niet alle verplichte velden [NR]';
		END
		--Check if track has all mandatory fields
		IF EXISTS(SELECT 1 FROM TRACK 
					WHERE CongressNo = @congressno AND Description IS NULL)
		BEGIN
			SET @test+= 'Track heeft niet alle mandatory fields  [NR]';
		END

		--Check if eventintrack has all mandatory fields
		IF EXISTS(SELECT 1 FROM EventInTrack 
					WHERE CongressNo = @congressno AND (START IS NULL OR [END] IS NULL))
		BEGIN
			SET @test+= 'Event in track heeft niet alle mandatory fields [NR]';
		END
		
		--Check if event has all mandatory fields
		IF EXISTS(SELECT 1 FROM Event 
					WHERE CongressNo = @congressno AND DESCRIPTION IS NULL)
		BEGIN
			SET @test+= 'Event heeft niet alle mandatory fields [NR]';
		END

		--Check if all speakers have all mandatory fields
		IF EXISTS(SELECT 1 FROM SpeakerOfCongress SOC
					INNER JOIN Speaker S ON SOC.PersonNo = S.PersonNo
					WHERE CongressNo = @congressno AND (SOC.Agreement IS NULL OR S.Description IS NULL OR S.PicturePath IS NULL))
		BEGIN
			SET @test+= 'Sprekers hebben niet alle mandatory fields [NR]';
		END

		--Check if speakers of congress have an agreement has all mandatory fields
		IF EXISTS(SELECT 1 FROM SpeakerOfCongress
					WHERE CongressNo = @congressno AND Agreement IS NULL)
		BEGIN
			SET @test+= 'Spreker van congres heeft niet alle mandatory fields [NR]';
		END

		--Check if all events are in a track
		IF EXISTS(SELECT 1 FROM EVENT E
					LEFT JOIN EventInTrack EIT ON E.CongressNo = EIT.CongressNo AND EIT.EventNo = E.EventNo
					WHERE E.CongressNo = @congressno AND EIT.EventNo IS NULL)
		BEGIN
			SET @test+= 'Niet alle events hebben een track [NR]';
		END

		--Check if all events have atleast one speaker
		IF EXISTS(SELECT 1 FROM Event E
					LEFT JOIN SpeakerOfEvent SOE ON E.CongressNo = SOE.CongressNo AND E.EventNo = SOE.EventNo
					WHERE E.CongressNo = @congressno AND SOE.EventNo IS NULL)
		BEGIN
			SET @test+= 'Niet alle events hebben een spreker [NR]';
		END

		--Check if Congress has atleast one event
		IF NOT EXISTS(SELECT 1 FROM Track 
					WHERE CongressNo = @congressno)
		BEGIN
			SET @test+= 'Het congres heeft geen track [NR]';
		END

		--Check if all tracks have atleast one event
		IF EXISTS(SELECT 1 FROM Track T 
					LEFT JOIN EventInTrack EIT ON T.CongressNo = EIT.CongressNo AND T.TrackNo = EIT.TrackNo		
					WHERE T.CongressNo = @congressno AND EIT.TrackNo IS NULL)
		BEGIN
			SET @test+= 'Er is een track zonder events [NR]';
		END

		--Check if all events have atleast one subject
		IF EXISTS(SELECT 1 FROM EVENT E
					LEFT JOIN SubjectOfEvent SOE ON E.CongressNo = SOE.CongressNo AND E.EventNo = SOE.EventNo
					WHERE E.CongressNo = @congressno AND SOE.EventNo IS NULL)
		BEGIN
			SET @test+= 'Er is een event zonder onderwerp [NR]';
		END

		--Check if congress has atleast one subject.
		IF EXISTS(SELECT 1 FROM Congress C
					LEFT JOIN SubjectOfCongress SOC ON C.CongressNo = SOC.CongressNo
					WHERE C.CongressNo = @congressno AND SOC.Subject IS NULL)
		BEGIN
			SET @test+= 'Het congres heeft geen onderwerp.';
		END

		--Check if every event have atleast one room
		IF EXISTS(SELECT 1 FROM EventInTrack E
					LEFT JOIN EventInRoom EIR ON E.CongressNo = EIR.CongressNo AND E.EventNo = EIR.EventNo
					WHERE E.CongressNo = @congressno AND EIR.RName IS NULL)
		BEGIN
			SET @test+= 'Een evenement heeft geen zaal';
		END

		--Check if every speaker of congress are a speaker of an event
		IF EXISTS(SELECT 1 FROM SpeakerOfCongress SOC
			LEFT JOIN SpeakerOfEvent SOE ON SOE.CongressNo = SOC.CongressNo AND SOC.PersonNo = SOE.PersonNo
			WHERE SOC.CongressNo = @congressno AND SOE.EventNo IS NULL)
		BEGIN
			SET @test+= 'Er is een spreker binnen het congres die niet aan een evenement gekoppelt is';
		END
		IF(@test NOT LIKE 'ERROR')
		BEGIN
			RAISERROR(@test,16,1);
		END
		ELSE
		BEGIN
			UPDATE Congress SET [Public] = 1 WHERE CongressNo = 1
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

GO
--Check of alle waarden binnen de congres worden gecontroleerd.
BEGIN TRAN
	UPDATE Congress SET LocationName = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
BEGIN TRAN
	UPDATE Congress SET City = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
BEGIN TRAN
	UPDATE Congress SET Startdate = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
BEGIN TRAN
	UPDATE Congress SET Enddate = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
BEGIN TRAN
	UPDATE Congress SET Price = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
BEGIN TRAN
	UPDATE Congress SET Description = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
BEGIN TRAN
	UPDATE Congress SET Banner = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
--Check if it still works with a double null value
BEGIN TRAN
	UPDATE Congress SET Description = NULL WHERE CongressNo = 1
	UPDATE Congress SET Banner = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
--Check if track has all mandatory fields
BEGIN TRAN
	UPDATE Track SET Description = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN
--Check if EventInTrack  has all mandatory fields
BEGIN TRAN
	UPDATE EventInTrack SET Start = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

BEGIN TRAN
	UPDATE EventInTrack SET [END] = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if event has all mandatory fields
BEGIN TRAN
	UPDATE Event SET Description = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if speaker has all mandatory fields
BEGIN TRAN
	UPDATE Speaker SET Description = NULL
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

BEGIN TRAN
	UPDATE Speaker SET PicturePath = NULL
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if SpeakerOfCongress has all mandatory fields
BEGIN TRAN
	UPDATE SpeakerOfCongress SET Agreement = NULL WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if all events must have a tracl
BEGIN TRAN
	DELETE FROM EventOfVisitorOfCongress WHERE CongressNo = 1 AND EventNo = 1
	DELETE FROM EventInTrack WHERE CongressNo = 1 AND EventNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if congress must have a track
BEGIN TRAN
	DELETE FROM EventInRoom WHERE CongressNo = 1
	DELETE FROM EventOfVisitorOfCongress WHERE CongressNo = 1
	DELETE FROM EventInTrack WHERE CongressNo = 1
	DELETE FROM Event WHERE CongressNo = 1
	DELETE FROM SpeakerOfCongress WHERE CongressNo = 1
	DELETE FROM Track WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if a track must have an event
BEGIN TRAN
	DELETE FROM EventOfVisitorOfCongress WHERE CongressNo = 1
	DELETE FROM EventInTrack WHERE CongressNo = 1 AND Trackno = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if an event must have a subject
BEGIN TRAN
	DELETE FROM SubjectOfEvent WHERE CongressNo = 1 AND EventNo =1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if congress must have a subject
BEGIN TRAN
	DELETE FROM SubjectOfCongress WHERE CongressNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if event must be in a room
BEGIN TRAN
	DELETE FROM EventInRoom WHERE EventNo = 1
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check if all speakers in the congress must speak
BEGIN TRAN
	DELETE FROM SpeakerOfEvent WHERE PersonNo = 6
	EXEC spPublishCongress 1
	GO
ROLLBACK TRAN

--Check working
BEGIN TRAN
	SELECT * FROM Congress WHERE CongressNo = 1
	EXEC spPublishCongress 1
	SELECT * FROM Congress WHERE CongressNo = 1
ROLLBACK TRAN

