CREATE PROC spAddCongressManagerToCongress

/*  Isolation level: read committed
	
	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency. In de eerste select wordt er op personNo gezocht. 
	Dit is een identity column en daardoor kan deze niet tussentijds veranderen.
*/

@PersonNo D_PersonNo, @CongressNo D_CongressNo
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
						FROM CongressManager
						WHERE PersonNo = @PersonNo)
		BEGIN
			INSERT INTO CongressManager (PersonNo, Password) VALUES (@PersonNo, (SELECT password FROM GeneralManager WHERE PersonNo = @personNo))
		END

		INSERT INTO CongressManagerOfCongress(PersonNo,CongressNo)
		VALUES(@PersonNo,@CongressNo)

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
GO

CREATE PROC spUpdateTrack

/*	Isolation level: repeatable read

	Nadat de oude data gecontroleerd is, mag dit record niet tussentijds gewijzigd worden om te voorkomen dat er alsnog een lost update plaatsvindt.
*/


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
GO

CREATE PROC spUpdateSpeakerSpeakerOfCongress

/*	Isolation level: read committed
	
	In deze sp is er sprake van mogelijke lost updates. Dit kan opgelost worden door in de WHERE clause de oude waarden mee te geven. 
*/
	@personNo D_Personno,
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@agreement D_DESCRIPTION,
	@description D_DESCRIPTION,
	@fileUploaded BIT,
	@congressno D_CongressNo,
	@owner D_Personno
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
		
		IF(NOT EXISTS(SELECT 1 FROM Speaker WHERE PersonNo = @personNo AND Owner = @owner)) AND NOT EXISTS(SELECT 1 FROM PersonTypeOfPerson WHERE PersonNo = @owner AND TypeName = 'Algemene beheerder')
		BEGIN
			RAISERROR('Je kan alleen je eigen sprekers aanpassen',16,1);
		END
		
		UPDATE SpeakerOfCongress 
		SET Agreement = @agreement
		WHERE PersonNo = @personNo AND CongressNo = @congressno

		IF (@fileUploaded = 1)
		BEGIN
			UPDATE Speaker 
			SET PicturePath = 'img/Speakers/speaker' + CAST(@personNo AS VARCHAR) + '.png'
			WHERE PersonNo = @personNo
		END

		UPDATE Speaker 
			SET Description = @description
			WHERE PersonNo = @personNo

		UPDATE Person
		SET FirstName = @firstname, LastName = @lastname, MailAddress = @mailAddress, PhoneNumber = @phonenum
		WHERE PersonNo = @personNo

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

CREATE PROC spUpdateSpeaker

/*	Isolation level: read committed

	In deze stored procedure is er kans op lost updates. 
	Dit moet je oplossen door in de tweede en derde update statements in de WHERE clause ook de oude waarden te zetten.
*/

	@personNo D_Personno,
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@description D_DESCRIPTION,
	@fileUploaded BIT
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
		IF (@fileUploaded = 1)
		BEGIN
			UPDATE Speaker 
			SET PicturePath = 'img/Speakers/speaker' + CAST(@personNo AS VARCHAR) + '.png'
			WHERE PersonNo = @personNo
		END
		
		UPDATE Speaker 
			SET Description = @description
			WHERE PersonNo = @personNo

		UPDATE Person
		SET FirstName = @firstname, LastName = @lastname, MailAddress = @mailAddress, PhoneNumber = @phonenum
		WHERE PersonNo = @personNo

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

CREATE PROC spUpdateLocation 

/*	Isolation level: read committed

	Er wordt een error opgegooid bij de eerste check en anders wordt er een update statement gedaan.
	Er kan hier niets misgaan op het gebied van concurrency.
*/

	@locationName D_NAME, 
	@city D_LOCATION, 
	@oldLocationName D_NAME,
	@oldCity D_LOCATION
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
						FROM Location
						WHERE LocationName = @oldlocationName AND City = @oldcity)
		BEGIN
			RAISERROR('Tijdens het opslaan zijn er nog wijzigingen doorgevoerd',16,2);
		END

		UPDATE Location 
		SET LocationName = @locationName, City = @city
		WHERE LocationName = @oldLocationName AND City = @oldCity
		
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

CREATE PROC spRegisterVisitor 
	
/*	Isolation level: read committed

	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency.
	Tussen de checks en de insert zou er in een andere transactie een person met dezelfde naam toegevoegd kunnen worden.
	In dit geval geeft de stored procedure een primary key error en wordt de transactie gerollbackt.

	Deze stored procedure zorgt ervoor dat bij het toevoegen van een bezoeker, er tegelijkertijd geinsert wordt in de tabellen Person, PersonTypeOfPerson En Visitor. 
	Dit is nodig omdat Visitor een foreign key relatie heeft met Person. De insert in PersontypeOfPerson is nodig voor de consistentie van de database. 
	In PersonTypeOfPerson staat dan dat de persoon een bezoeker is en daarom kan de bezoeker ook in de tabel Visitor komen.
*/

	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail, 
	@phonenum D_telnr,
	@password D_password
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
		IF NOT EXISTS(SELECT 1 FROM Person WHERE mailAddress = @mailAddress)
		BEGIN
			INSERT INTO PERSON VALUES(@firstname, @lastname, @mailAddress, @phonenum)
		END
		
		DECLARE @personNo INT =	(SELECT PersonNo 
								 FROM PERSON 
								 WHERE MailAddress = @mailAddress)
		
		IF NOT EXISTS(SELECT 1 FROM PersonTypeOfPerson WHERE PersonNo = @personNo AND TypeName = 'Bezoeker')
		BEGIN
			INSERT INTO PERSONTYPEOFPERSON VALUES(@personNo, 'Bezoeker')
		END
		
		IF NOT EXISTS(SELECT 1 FROM Visitor WHERE PersonNo = @personNo)
		BEGIN
			INSERT INTO VISITOR VALUES(@personNo, @password)
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

CREATE PROC spRegisterSpeaker 


/*	Isolation level: read committed

	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency.
	Tussen de check of er al een person is en de insert zou er in een andere transactie een person met dezelfde naam toegevoegd kunnen worden.
	In dit geval geeft de stored procedure een primary key error en wordt de transactie gerollbackt, maar dit is niet erg.
	 
*/

		@firstname D_Name, 
		@lastname D_Name, 
		@mailAddress D_Mail, 
		@phonenum D_telnr,
		@description D_DESCRIPTION,
		@fileUploaded BIT,
		@owner D_Personno 
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
			IF EXISTS ( SELECT 1 
						FROM Person
						WHERE MailAddress != @mailAddress)
			BEGIN		
				INSERT INTO PERSON 
				VALUES(@firstname, @lastname, @mailAddress, @phonenum)
			END

			DECLARE @personNo INT = (SELECT PersonNo 
									 FROM PERSON 
									 WHERE  MAILADDRESS = @mailAddress)
			INSERT INTO PERSONTYPEOFPERSON VALUES(@personNo, 'Spreker')
			IF @fileUploaded = 1
			BEGIN
				INSERT INTO Speaker 
				VALUES(@personNo,@description,'img/Speakers/speaker' + CAST(@personNo AS VARCHAR) + '.png' ,@owner)
			END
			ELSE
			BEGIN
				INSERT INTO Speaker 
				VALUES(@personNo,@description,null,@owner)
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

CREATE PROC addSubjectToEvent

/*	Isolation level: read committed
	
	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency.
	Tussen de check of er al een subject is en de insert zou er in een andere transactie een subject met dezelfde naam toegevoegd kunnen worden.
	In dit geval geeft de stored procedure een primary key error en wordt de transactie gerollbackt. 
*/

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
GO

CREATE PROC spAddSubjectToCongress

/*	Isolation level: read committed
	
	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency. 
	Tussen de check of er al een subject is en de insert zou er in een andere transactie een subject met dezelfde naam toegevoegd kunnen worden.
	In dit geval geeft de stored procedure een primary key error en wordt de transactie gerollbackt. 
*/

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
GO

CREATE PROC spAddManager

/*	Isolation level: read committed

	Er kan in deze stored procedure weinig fout gaan op het gebied van concurrency.
	Tussen de check of er al een person is en de insert zou er in een andere transactie een person met dezelfde naam toegevoegd kunnen worden.
	In dit geval geeft de stored procedure een primary key error en wordt de transactie gerollbackt, maar dit is niet erg.
	 
*/
	@firstname D_Name, 
	@lastname D_Name, 
	@mailAddress D_Mail,
	@password D_Password, 
	@phonenum D_telnr,
	@managerType CHAR(1)
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
			IF NOT EXISTS ( SELECT 1 
							FROM Person
							WHERE MailAddress = @mailAddress)
			BEGIN		
				INSERT INTO Person
				VALUES(@firstname, @lastname, @mailAddress, @phonenum)
			END

			DECLARE @personNo INT = (SELECT PersonNo 
									 FROM PERSON 
									 WHERE  MAILADDRESS = @mailAddress)
			IF NOT EXISTS ( SELECT 1 
							FROM Visitor
							WHERE PersonNo = @personNo)
			BEGIN
				INSERT INTO Visitor(PersonNo,[Password])
				VALUES(@personNo, @password)
			END

			IF @managerType = 'C'
			BEGIN
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Congresbeheerder')
				INSERT INTO CongressManager VALUES(@personNo, @password)
			END
			ELSE IF @managerType = 'G'
			BEGIN
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Algemene beheerder')
				INSERT INTO GeneralManager VALUES(@personNo, @password)
			END
			ELSE IF @managerType = 'A'
			BEGIN
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Algemene beheerder')
				INSERT INTO GeneralManager VALUES(@personNo, @password)
				INSERT INTO PersonTypeOfPerson VALUES(@personNo, 'Congresbeheerder')
				INSERT INTO CongressManager VALUES(@personNo, @password)
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














