CREATE PROC spUpdateTrack
	@congressNo D_CongressNo,
	@trackNo D_TrackNo,
	@trackName D_Name,
	@trackDescription D_Description,

	@oldTrackName D_Name,
	@oldTrackDescription D_Description
AS
BEGIN
	SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;
	SET NOCOUNT ON;
	DECLARE @TranCounter INT;
	SET @TranCounter = @@TRANCOUNT;

	IF @TranCounter > 0
		SAVE TRANSACTION ProcedureSave;
	ELSE
		BEGIN TRANSACTION;

	BEGIN TRY
		IF NOT EXISTS(	SELECT 1 
						FROM Track
						WHERE congressNo = @congressNo AND TName = @oldTrackName AND Description = @oldTrackDescription)
		BEGIN
			RAISERROR('Tijdens het opslaan zijn er nog wijzigingen doorgevoerd',16,2);
		END
	
		UPDATE Track SET TName = @trackName, Description = @trackDescription WHERE CongressNo = @congressNo	AND TrackNo = @trackNo
	
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

SELECT * FROM Track

--Goed
BEGIN TRAN
EXEC spUpdateTrack
	@congressNo = 1,
	@trackNo = 1,
	@trackName = 'Nieuwe naam' ,
	@trackDescription = 'Nieuwe omschrijving',

	@oldTrackName = 'NodeJS',
	@oldTrackDescription = 'Een track over programmeren'
ROLLBACK

--Fout Oude waardes komen niet overeen met de waardes in track.
BEGIN TRAN
EXEC spUpdateTrack
	@congressNo = 1,
	@trackNo = 1,
	@trackName = 'Nieuwe naam' ,
	@trackDescription = 'Nieuwe omschrijving',

	@oldTrackName = 'NodeJS',
	@oldTrackDescription = 'Een track over NodeJS'
ROLLBACK

