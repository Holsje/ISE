
CREATE PROC spUpdateCongress

/*	Isolation level: repeatable read

	Nadat de oude data gecontroleerd is, mag dit record niet tussentijds gewijzigd worden om te voorkomen dat er alsnog een lost update plaatsvindt.

*/

	@congressNo D_CongressNo,
	@name D_Name,
	@startDate D_Date,
	@endDate D_Date,
	@price D_Price,

	@oldName D_Name,
	@oldstartDate D_Date,
	@oldEndDate D_Date,
	@oldprice D_Price
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
						WHERE congressNo = @congressNo AND CName = @oldName AND Price = @oldprice AND startDate = @oldstartDate AND endDate = @oldEndDate)
		BEGIN
			RAISERROR('Tijdens het opslaan zijn er nog wijzigingen doorgevoerd',16,2);
		END
	
		
		UPDATE Congress SET CName = @name, Price = @price, StartDate = @startDate, EndDate = @endDate WHERE CongressNo = @congressNo	
	
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
	@startDate = '11-11-11',
	@endDate = '12-12-12',
	@price = 950.00,

	@oldName = 'Data Modeling Zone',
	@oldstartDate = '2016-10-10',
	@oldEndDate = '2016-10-11',
	@oldprice = 950.00
ROLLBACK

--Fout Oude waardes komen niet overeen met de waardes in congres.
BEGIN TRAN
EXEC spUpdateCongress
	@congressNo = 2,
	@name = 'test' ,
	@startDate = '11-11-11',
	@endDate = '12-12-12',
	@price = 500.00,

	@oldName = 'Data Modeling Zone',
	@oldstartDate = '2016-10-10',
	@oldEndDate = '2016-10-11',
	@oldprice = 950.00
ROLLBACK
