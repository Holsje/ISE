ALTER PROC spUpdateCongress
	@congressNo D_CongressNo,
	@name D_Name,
	@location D_Location,
	@city D_Location,
	@startDate D_Date,
	@endDate D_Date,

	@oldName D_Name,
	@oldLocation D_Location,
	@oldCity D_Location,
	@oldstartDate D_Date,
	@oldEndDate D_Date
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
						FROM Congress
						WHERE congressNo = @congressNo AND CName = @oldName AND LocationName = @oldLocation AND City = @oldCity AND startDate = @oldstartDate AND endDate = @oldEndDate)
		BEGIN
			RAISERROR('Tijdens het opslaan zijn er nog wijzigingen doorgevoerd',16,2);
		END
	
		UPDATE Congress SET CName = @name,LocationName = @location, city = @city, StartDate = @startDate, EndDate = @endDate WHERE CongressNo = @congressNo	
	
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

--Goed
BEGIN TRAN
EXEC spUpdateCongress
	@congressNo = 1,
	@name = 'test' ,
	@location ='HAN',
	@city= 'Nijmegen',
	@startDate = '11-11-11',
	@endDate = '12-12-12',

	@oldName = 'Data Modeling Zone',
	@oldLocation = 'Abion Spreebogen',
	@oldCity = 'Berlijn',
	@oldstartDate = '2016-10-10',
	@oldEndDate = '2016-10-11'
ROLLBACK

--Fout Oude waardes komen niet overeen met de waardes in congres.
BEGIN TRAN
EXEC spUpdateCongress
	@congressNo = 2,
	@name = 'test' ,
	@location ='HAN',
	@city= 'Nijmegen',
	@startDate = '11-11-11',
	@endDate = '12-12-12',

	@oldName = 'Data Modeling Zone',
	@oldLocation = 'Abion Spreebogen',
	@oldCity = 'Berlijn',
	@oldstartDate = '2016-10-10',
	@oldEndDate = '2016-10-11'
ROLLBACK
