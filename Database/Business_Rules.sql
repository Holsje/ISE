/* Business Rules */

/* BR1: De einddatum mag niet vóór de begindatum van een congres/evenement liggen. */

ALTER TABLE Congress
ADD CONSTRAINT CHK_StartBeforeEndCongress CHECK (StartDate <= EndDate) 

ALTER TABLE EventInTrack
ADD CONSTRAINT CHK_StartBeforeEndEventInTrack CHECK (Start < [End])
GO

/* BR2: Prijs is mandatory als een event van het type workshop is. */

ALTER TABLE Event
ADD CONSTRAINT CHK_PriceEventType CHECK((Price IS NOT NULL AND [Type] = 'Workshop') OR (Price IS NULL AND [Type] = 'Lezing'))
GO

/* BR3: Publiceren van een congres(verplichte velden worden allemaal gecontroleerd). */

CREATE PROC spPublishCongress
	@congressno D_CONGRESSNO
AS
BEGIN
/*
	Isolation level: Serializable
	Uitgaande van repeatable read:
	Wanneer alle waardes gecontroleerd worden en er wordt een nieuwe 
	bijvoorbeeld track toegevoegd die leeg is dan zit er geen range lock op.

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

/* BR4: Er mag maar één event in één room tegelijkertijd plaatsvinden. */

CREATE TRIGGER trMultipleEventsAtTheSameTimeInOneBuilding_BR4
/*
	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInRoom en uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen van een room van een event.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar één congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
	Daarnaast is samen met de opdrachtgever afgesproken dat voor deze gevallen het isolation level read committed voldoende is.
*/
ON EventInRoom
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	BEGIN TRY	
		IF EXISTS(
			SELECT 1
			FROM EventInRoom EIR INNER JOIN Inserted I 
			ON EIR.LocationName = I.LocationName AND EIR.City = I.City AND EIR.BName = I.BName AND EIR.RName = I.RName
			WHERE EIR.EventNo IN (SELECT ET.EventNo
									FROM EventInTrack ET INNER JOIN EventInTrack ET2
									ON ET.EventNo != ET2.EventNo AND ET2.EventNo = I.EventNo
									WHERE (ET2.Start > ET.Start AND ET2.Start < ET.[End]) OR
										(ET2.[End] > ET.Start AND ET2.[End] < ET.[End]) OR
										(ET2.Start <= ET.Start AND ET2.[End] >= ET.[End]))			
		)		
		BEGIN
			RAISERROR('Er mag maar één event in één room tegelijkertijd plaatsvinden.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
 END
 GO

 /* BR5: Wanneer er een spreker wordt toegevoegd moet hij/zij direct aan een congres worden toegewezen. */

CREATE PROC spAddSpeakerToCongress
@FirstName D_Name, 
@LastName D_Name, 
@MailAddress D_Mail,
@PhoneNumber D_TELNR ,
@Owner D_Personno,
@fileUploaded BIT, 
@Description D_Description,

@CongressNo D_CongressNO, 
@Agreement D_Description 

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
-----------Begin Eigen implementatie
		IF NOT EXISTS(	SELECT 1
						FROM Person
						WHERE MailAddress = @MailAddress)
		BEGIN
			INSERT INTO Person(FirstName, LastName, MailAddress, PhoneNumber)
			VALUES(@FirstName, @LastName, @MailAddress, @PhoneNumber)
		END
		DECLARE @PersonNo D_PersonNo
		SET @PersonNo = (SELECT PersonNo
						FROM Person
						WHERE MailAddress = @MailAddress)

		INSERT INTO PersonTypeOfPerson(PersonNo, TypeName)
		VALUES(@PersonNo, 'Spreker')

		IF (@fileUploaded = 1) 
		BEGIN
			INSERT INTO Speaker(PersonNo, Description, PicturePath,[Owner])
			VALUES(@PersonNo, @Description,'img/Speakers/speaker' + CAST(@personNo AS VARCHAR) + '.' +  @fileExtension,@Owner)
		END
		ELSE
		BEGIN
			INSERT INTO Speaker(PersonNo, Description, PicturePath,[Owner])
			VALUES(@PersonNo, @Description,null,@Owner)
		END
		INSERT INTO SpeakerOfCongress(PersonNo, CongressNo, Agreement)
		VALUES(@PersonNo, @CongressNo, @Agreement)

-----------Eind eigen implementatie
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

/* BR6: Een onderwerp moet altijd bij een congres of evenement horen. */

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
GO

/* BR7: Als eenzelfde evenement in meerdere tracks gehouden wordt, mogen de start- en eindtijden niet overlappen. */

CREATE TRIGGER trEventInMultipleTracksOverlap_BR7
/*
   	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het aanpassen van een tijdstip van een evenement.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar één congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
	Daarnaast is samen met de opdrachtgever afgesproken dat voor deze gevallen het isolation level read committed voldoende is.

*/
ON dbo.EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN

	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	BEGIN TRY

		IF EXISTS(SELECT 1
					FROM Inserted I INNER JOIN EventInTrack EIT 
						ON I.EventNo = EIT.EventNo AND I.CongressNo = EIT.CongressNo AND I.TrackNo != EIT.TrackNo
					WHERE ((I.Start > EIT.Start AND I.Start < EIT.[End]) OR (I.[End] > EIT.Start AND I.Start < EIT.[End]) OR (I.Start <= EIT.Start AND I.[End] >= EIT.[End]))
					)
		BEGIN
			RAISERROR('Hetzelfde evenement wordt al in een andere track op hetzelfde tijdstip gehouden!', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END
GO

/* BR8: Evenementen in een track mogen elkaar qua tijd niet overlappen. */

CREATE TRIGGER trEventInTrackOverlap_BR8
/*
	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen van een room van een event.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar één congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
	Daarnaast is samen met de opdrachtgever afgesproken dat voor deze gevallen het isolation level read committed voldoende is.

*/
ON EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	BEGIN TRY
	IF EXISTS( 
		SELECT 1
		FROM EVENTINTRACK ET INNER JOIN Inserted I 
			ON ET.EVENTNO != I.EVENTNO AND ET.CongressNo = I.CongressNo AND ET.TRACKNO = I.TRACKNO
		WHERE ((I.Start > ET.Start AND I.Start < ET.[End]) OR (I.[End] > ET.Start AND I.[End] < ET.[End]) OR (I.Start <= ET.Start AND I.[End] >= ET.[End])))
		RAISERROR('Evenementen in een track mogen elkaar qua tijd niet overlappen.', 16, 1);
	END TRY
	BEGIN CATCH
		THROW
	END CATCH
END
GO

/* BR9: Verschillende evenementen van een spreker mogen elkaar niet overlappen qua tijd. */

CREATE TRIGGER trMultipleEventsOfSpeakerOverlap_BR9
/*
	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack en de tabel SpeakerOfCongress.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen van de tijden van een ander evenement van een spreker.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar één congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
	Daarnaast is samen met de opdrachtgever afgesproken dat voor deze gevallen het isolation level read committed voldoende is.

*/
ON dbo.EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	BEGIN TRY

		IF EXISTS(SELECT 1
				  FROM Inserted I INNER JOIN EventInTrack EIT 
					ON EIT.CongressNo = I.CongressNo AND EIT.EventNo = I.EventNo AND EIT.TrackNo = I.TrackNo INNER JOIN SpeakerOfEvent SOE 
						ON EIT.CongressNo = SOE.CongressNo AND EIT.EventNo = SOE.EventNo INNER JOIN SpeakerOfEvent SOE2 
							ON SOE.PersonNo = SOE2.PersonNo AND SOE.CongressNo = SOE2.CongressNo INNER JOIN EventInTrack EIT2 
								ON SOE2.CongressNo = EIT2.CongressNo AND SOE2.EventNo = EIT2.EventNo AND EIT.EventNo != EIT2.EventNo
				  WHERE  ((I.Start > EIT2.Start AND I.Start < EIT2.[End]) OR (I.[End] > EIT2.Start AND I.[End] < EIT2.[End]) OR (I.Start <= EIT2.Start AND I.[End] >= EIT2.[End]))	
					)
		BEGIN
			RAISERROR('De spreker van het evenement spreekt al tegelijkertijd bij een ander evenement.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END
GO

/* BR10: De start- en eindtijden van een evenement moeten binnen de start- en einddatum van het congres vallen. */

CREATE TRIGGER trEventDateNotInBetweenCongressDate_BR10
/*
	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack en Congres.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden is het dus mogelijk om iets aan te passen. Zoals het veranderen de start of eind datum van het congres.
	Daardoor zou de melding onterecht op het scherm kunnen komen bij het isolation level read committed. 
	Vanwege een betere performance bij een lager isolation level en het feit dat er vaak maar één congresbeheerder bezig is komt dit echter niet vaak voor is er toch gekozen voor read committed.
	Daarnaast is samen met de opdrachtgever afgesproken dat voor deze gevallen het isolation level read committed voldoende is.

*/
ON EventInTrack
AFTER INSERT, UPDATE
AS 
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
	BEGIN TRY
		IF EXISTS(
			SELECT 1
			FROM EVENTINTRACK ET INNER JOIN Inserted I 
				ON ET.EventNo = I.EventNo AND ET.TrackNo = I.TrackNo AND ET.CongressNo = I.CongressNo INNER JOIN Congress C 
				ON I.CongressNo = C.CongressNo
			WHERE CAST(I.[End] AS Date) > C.Enddate OR CAST(I.Start AS Date) < C.Startdate
		)
	BEGIN
		RAISERROR('De start- en eindtijden van een evenement moeten binnen de start- en einddatum van het congres vallen.', 16, 1)
	END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
 END
 GO

 /* BR11: Evenementen waar een bezoeker zich voor wilt inschrijven mogen niet overlappen. */

 CREATE TRIGGER trMultipleEventsOfVisitorOverlap_BR11
/*
	Isolation level:
	Uitgaande van standaard transaction isolation level: Read committed.
	Bij de select in de if exists komt er een s-lock op de gelezen data uit de tabel EventInTrack en de tabel EventOfVisitorOfCongress.
	Deze s-lock blijft staan tot de data gelezen is, daarna wordt de s-lock gereleased. 
	Voordat de error geraist kan worden zou het dus mogelijk zijn om iets aan te passen.
	Er is echter een andere businness rule die die er voor zorgt dat de tijden van evenementen niet meer aangepast mogen worden als het congres waar de evenementen bijhoren publiek is.
	Daarnaast zal deze trigger alleen afgevuurd worden als iemand op de site, wanneer het congres dus al publiek is, zich gaat inschrijven. Er is dan geen mogelijkheid meer om tijden van evenementen aan te passen.
	Daarom voldoet read committed hier toch, omdat tussen de if exists en de raiserror vanwege een andere business rule geen gegevens die van invloed zijn aangepast kunnen worden.	
*/
ON dbo.EventOfVisitorOfCongress
AFTER INSERT, UPDATE
AS
BEGIN
	IF @@ROWCOUNT = 0 RETURN;
	SET NOCOUNT ON;
	SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

	BEGIN TRY
		IF EXISTS(SELECT 1
				  FROM Inserted AS I INNER JOIN EventOfVisitorOfCongress EVC 
					ON I.PersonNo = EVC.PersonNo AND I.CongressNo = EVC.CongressNo AND I.TrackNo = EVC.TrackNo AND I.EventNo = EVC.EventNo INNER JOIN EventInTrack EIT 
						ON EIT.CongressNo = EVC.CongressNo AND EIT.TrackNo = EVC.TrackNo AND EIT.EventNo = EVC.EventNo INNER JOIN EventOfVisitorOfCongress EVC2
							ON EVC.PersonNo = EVC2.PersonNo AND EVC.CongressNo = EVC2.CongressNo INNER JOIN EventInTrack EIT2
								ON EVC2.CongressNo = EIT2.CongressNo AND EVC2.TrackNo = EIT2.TrackNo AND EVC2.EventNo = EIT2.EventNo 
				  WHERE ((EIT.Start > EIT2.Start AND EIT.Start < EIT2.[End]) OR (EIT.[End] > EIT2.Start AND EIT.[End] < EIT2.[End]) OR (EIT.Start <= EIT2.Start AND EIT.[End] >= EIT2.[End])) AND (EIT.EventNo != EIT2.EventNo))
		BEGIN
			RAISERROR('U kunt geen overlappende evenementen kiezen.', 16, 1);
		END
	END TRY
	BEGIN CATCH
		THROW;
	END CATCH
END
GO

/* BR12: */

/* BR13: Als een spreker verwijdert wordt dan moet deze ook uit de PersonTypeOfPerson verwijdert worden. */ 

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
