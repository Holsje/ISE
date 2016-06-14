-- Goed moet nieuwe congresmanager toevoegen in CongressManager en CongressManagerOfCongress.
BEGIN TRAN
	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress
	EXEC spAddCongressManagerToCongress
	@PersonNo = 1,
	@CongressNo = 1

	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress

ROLLBACK TRAN

--Goed moet toevoegen in CongressManagerOfCongress niet in CongressManager.
BEGIN TRAN
	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress
	EXEC spAddCongressManagerToCongress
	@PersonNo = 3,
	@CongressNo = 1

	SELECT * FROM CongressManager
	SELECT * FROM CongressManagerOfCongress
ROLLBACK TRAN

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

-- Goed moet nieuw subject toevoegen in Subject en SubjectOfCongress.
BEGIN TRAN
	EXEC spAddSubjectToCongress
	@CongressNo = 1,
	@Subject = 'test'

	SELECT * FROM Subject
	SELECT * FROM SubjectOfCongress

ROLLBACK

--Goed moet toevoegen in SubjectOfCongress niet in Subject.
BEGIN TRAN
	EXEC spAddSubjectToCongress
	@CongressNo = 1,
	@Subject = 'Data'

	SELECT * FROM Subject
	SELECT * FROM SubjectOfCongress
ROLLBACK

BEGIN TRAN
EXEC spAddManager
@firstname = 'test',
@lastname = 'testasd',
@mailAddress = 'test@test.nl',
@password = 'rwdsada', 
@phonenum = '123456789',
@managerType = 'A'
ROLLBACK

--Goed WERKT NOG NIET MET BANNER?
BEGIN TRAN
EXEC spUpdateCongress
	@congressNo = 1,
	@name = 'test' ,
	@startDate = '11-11-11',
	@endDate = '12-12-12',
	@price = 950.00,
	@banner = 'img/Banners/Congress1.png',

	@oldName = 'Data Modeling Zone',
	@oldstartDate = '2016-10-10',
	@oldEndDate = '2016-10-11',
	@oldprice = 950.00,
	@oldbanner = 'img/Banners/Congress1.png'
ROLLBACK

--Fout Oude waardes komen niet overeen met de waardes in congres. WERKT NOG NIET MET BANNER?
BEGIN TRAN
EXEC spUpdateCongress
	@congressNo = 2,
	@name = 'test' ,
	@startDate = '11-11-11',
	@endDate = '12-12-12',
	@price = 500.00,
	@banner = 'img/Banners/Congress2.png',

	@oldName = 'Data Modeling Zone',
	@oldstartDate = '2016-10-10',
	@oldEndDate = '2016-10-11',
	@oldprice = 950.00,
	@oldbanner = 'img/Banners/Congress1.png'
ROLLBACK

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

BEGIN TRAN 
	--Expected succes, all data should have be changed. He's the owner AND the global manager
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,1
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --He's not the owner but is a global manager, all data should been changed
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,2
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --He's the owner but not a global manager, all data should been changed
	UPDATE Speaker SET Owner = 5 WHERE PersonNo = 1
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,5
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN


BEGIN TRAN --He's not in this congress, agreement should not change, everything else should
	UPDATE Speaker SET Owner = 5 WHERE PersonNo = 1
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,9,5
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --Picturepath should change to img/Speakers/speaker1.png
	UPDATE Speaker SET PicturePath = NULL
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,2
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN


BEGIN TRAN --Picturepath should stay null
	UPDATE Speaker SET PicturePath = NULL
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',0,1,2
	SELECT * FROM SpeakerOfCongress SOC 
	INNER JOIN Speaker S ON S.PersonNo = SOC.PersonNo
	INNER JOIN Person P ON P.PersonNo = SOC.PersonNo
	WHERE SOC.PersonNo = 1
ROLLBACK TRAN

BEGIN TRAN --He's not the owner, and no global manager. He should not be alowed to edit.
	exec spUpdateSpeakerSpeakerOfCongress 1,'Evert','Eriksen','everteriksen1996@gmail.com','0612345678','dit is een agreement','Omschrijving',1,1,5
	GO
ROLLBACK TRAN

--Testdata
--Update van speaker 1, verwacht dat Erik verandert naar Testnaam en dat description NULL wordt
BEGIN TRAN
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
EXECUTE spUpdateSpeaker 1, 'Testnaam', 'Testnaam', 'testmail@mail.com', '123456789', null, 0
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
ROLLBACK TRAN

--Update van speaker 1
BEGIN TRAN
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
EXECUTE spUpdateSpeaker 1, 'Testnaam', 'Testnaam', 'testmail@mail.com', '123456789', null, 1
SELECT * FROM Speaker WHERE personNo = 1
SELECT * FROM Person WHERE PersonNo = 1
ROLLBACK TRAN

--Testdata
--Goede update
BEGIN TRAN
EXEC spUpdateLocation 'HAN Campus', 'Arnhem', 'HAN', 'Nijmegen'
SELECT * FROM Location
ROLLBACK TRAN

--Volgende twee aanroepen zijn om de lost updates te demonstreren.
--Start beide transacties en voer dan beide sp uit.
BEGIN TRAN
EXEC spUpdateLocation 'HAN Campus', 'Nijmegen', 'HAN', 'Nijmegen'
--ROLLBACK TRAN

BEGIN TRAN
EXEC spUpdateLocation 'HAN Campus', 'Arnhem', 'HAN', 'Nijmegen'
--ROLLBACK TRAN

--Testdata
--Insert waarbij de persoon nog niet bestaat
BEGIN TRAN
SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@gmail.com'
SELECT * FROM Visitor WHERE PersonNo IN(SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
EXECUTE spRegisterVisitor 'Niels', 'Bergervoet', 'nielsbergervoet@gmail.com', '0612314567', 'wachtwoord'

SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@gmail.com'
SELECT * FROM Visitor WHERE PersonNo IN(SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE MailAddress = 'nielsbergervoet@gmail.com')
ROLLBACK TRAN

--Insert waarbij de persoon al wel in Person staat, niet in Visitor en ook niet als bezoeker in PersonTypeOfPerson. 
--Aan het eind van het registreren moet een record in alle drie tabellen te vinden zijn van deze persoon
BEGIN TRAN
SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com'
SELECT * FROM Visitor WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
EXECUTE spRegisterVisitor 'Niels', 'Bergervoet', 'nielsbergervoet@hotmail.com', '0612314567', 'wachtwoord'

SELECT * FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com'
SELECT * FROM Visitor WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
SELECT * FROM PersonTypeOfPerson WHERE PersonNo IN (SELECT PersonNo FROM Person WHERE mailAddress = 'nielsbergervoet@hotmail.com')
ROLLBACK TRAN

--Testdata
--Insert waarbij de persoon al in Person bestaat en ook al in PersonTypeOfPerson. Verwacht een UNIQUE constraint
BEGIN TRAN
	SELECT * FROM Person
	SELECT * FROM PersonTypeOfPerson
	SELECT * FROM Speaker

	EXECUTE spRegisterSpeaker 'Erik', 'Evers', 'erikevers1996@gmail.com', '0613334002', 'Omschrijving van erik', 1, null

	SELECT * FROM Person
	SELECT * FROM PersonTypeOfPerson
	SELECT * FROM Speaker
ROLLBACK TRAN

--Insert waarbij de persoon nog niet in Person bestaat en waar geen file geupload is. Verwacht null In speaker - Picturepath. Owner is Erik met personNo 1
BEGIN TRAN
	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'

	DECLARE @personNoSpeaker INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PersonNo = @personNoSpeaker
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker

	EXECUTE spRegisterSpeaker 'TestnaamSP', 'TestnaamSP', 'testmail@mail.com', '0613334002', 'Omschrijving', 0, 1

	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'
	
	DECLARE @personNoSpeaker2 INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PErsonNo = @personNoSpeaker2
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker2
ROLLBACK TRAN

--Insert waarbij er wel een file geupload is
BEGIN TRAN
	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'

	DECLARE @personNoSpeaker3 INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PersonNo = @personNoSpeaker3
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker3

	EXECUTE spRegisterSpeaker 'TestnaamSP', 'TestnaamSP', 'testmail@mail.com', '0613334002', 'Omschrijving', 1, 1

	SELECT * FROM Person WHERE MailAddress = 'testmail@mail.com'
	
	DECLARE @personNoSpeaker4 INT = (SELECT PersonNo FROM Person WHERE MailAddress = 'testmail@mail.com')

	SELECT * FROM PersonTypeOfPerson WHERE PErsonNo = @personNoSpeaker4
	SELECT * FROM Speaker WHERE PersonNo = @personNoSpeaker4
ROLLBACK TRAN