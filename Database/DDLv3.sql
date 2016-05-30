USE master
GO

IF db_id('CongressDB2') IS NOT NULL
BEGIN
	DROP DATABASE CongressDB2
END
GO

CREATE DATABASE CongressDB2
GO

USE CongressDB2
GO

/*==============================================================*/
/* Domain: D_BOOLEAN                                            */
/*==============================================================*/
CREATE TYPE D_BOOLEAN
   FROM BIT
GO

/*==============================================================*/
/* Domain: D_CAPACITY                                           */
/*==============================================================*/
CREATE TYPE D_CAPACITY
   FROM SMALLINT
GO

/*==============================================================*/
/* Domain: D_CONGRESSNO                                         */
/*==============================================================*/
CREATE TYPE D_CONGRESSNO
   FROM INT
GO

/*==============================================================*/
/* Domain: D_DATE                                               */
/*==============================================================*/
CREATE TYPE D_DATE
   FROM DATE
GO

/*==============================================================*/
/* Domain: D_DATETIME                                           */
/*==============================================================*/
CREATE TYPE D_DATETIME
   FROM DATETIME
GO

/*==============================================================*/
/* Domain: D_DESCRIPTION                                        */
/*==============================================================*/
CREATE TYPE D_DESCRIPTION
   FROM VARCHAR(1000)
GO

/*==============================================================*/
/* Domain: D_EVENTNO                                            */
/*==============================================================*/
CREATE TYPE D_EVENTNO
   FROM INT
GO

/*==============================================================*/
/* Domain: D_FILE                                               */
/*==============================================================*/
CREATE TYPE D_FILE
   FROM VARCHAR(500)
GO

/*==============================================================*/
/* Domain: D_HOUSENO                                            */
/*==============================================================*/
CREATE TYPE D_HOUSENO
   FROM SMALLINT
GO

/*==============================================================*/
/* Domain: D_LOCATION                                           */
/*==============================================================*/
CREATE TYPE D_LOCATION
   FROM VARCHAR(20)
GO

/*==============================================================*/
/* Domain: D_MAIL                                               */
/*==============================================================*/
CREATE TYPE D_MAIL
   FROM VARCHAR(50)
GO

/*==============================================================*/
/* Domain: D_NAME                                               */
/*==============================================================*/
CREATE TYPE D_NAME
   FROM VARCHAR(50)
GO

/*==============================================================*/
/* Domain: D_PASSWORD                                           */
/*==============================================================*/
CREATE TYPE D_PASSWORD
   FROM VARCHAR(64)
GO

/*==============================================================*/
/* Domain: D_PERSONNO                                           */
/*==============================================================*/
CREATE TYPE D_PERSONNO
   FROM INT
GO

/*==============================================================*/
/* Domain: D_PRICE                                              */
/*==============================================================*/
CREATE TYPE D_PRICE
   FROM MONEY
GO

/*==============================================================*/
/* Domain: D_SUBJECT                                            */
/*==============================================================*/
CREATE TYPE D_SUBJECT
   FROM VARCHAR(50)
GO

/*==============================================================*/
/* Domain: D_TELNR                                              */
/*==============================================================*/
CREATE TYPE D_TELNR
   FROM VARCHAR(25)
GO

/*==============================================================*/
/* Domain: D_TRACKNO                                            */
/*==============================================================*/
CREATE TYPE D_TRACKNO
   FROM INT
GO

/*==============================================================*/
/* Domain: D_TYPE                                               */
/*==============================================================*/
CREATE TYPE D_TYPE
   FROM VARCHAR(18)
GO

/*==============================================================*/
/* Domain: D_ZIPCODE                                            */
/*==============================================================*/
CREATE TYPE D_ZIPCODE
   FROM VARCHAR(6)
GO

/*==============================================================*/
/* Table: Location                                              */
/*==============================================================*/
CREATE TABLE Location (
   LocationName         D_NAME               NOT NULL,
   City                 D_LOCATION           NOT NULL,
   CONSTRAINT PK_Location PRIMARY KEY (LocationName, City) 
)
GO

/*==============================================================*/
/* Table: Building                                              */
/*==============================================================*/
CREATE TABLE Building (
   LocationName         D_NAME               NOT NULL,
   City                 D_LOCATION           NOT NULL,
   BName                D_NAME               NOT NULL,
   Street               D_NAME               NOT NULL,
   HouseNo              D_HOUSENO            NOT NULL,
   PostalCode           D_ZIPCODE            NOT NULL,
   CONSTRAINT PK_BUILDING PRIMARY KEY (LocationName, City, BName),
   CONSTRAINT FK_BUILDING_RT_BUILDI_LOCATION FOREIGN KEY (LocationName, City)
      REFERENCES Location (LocationName, City)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: Congress                                              */
/*==============================================================*/
CREATE TABLE Congress (
   CongressNo           D_CONGRESSNO IDENTITY NOT NULL,
   LocationName         D_NAME               NULL,
   City                 D_LOCATION           NULL,
   CName                D_NAME               NOT NULL,
   Startdate            D_DATE               NULL,
   Enddate              D_DATE               NULL,
   Price                D_PRICE              NULL,
   Description			D_DESCRIPTION	     NULL,
   Banner				D_FILE				 NULL,
   [Public]             D_BOOLEAN            NOT NULL DEFAULT 0,
   CONSTRAINT PK_CONGRESS PRIMARY KEY (CongressNo),
   CONSTRAINT FK_CONGRESS_RT_CONGRE_LOCATION FOREIGN KEY (LocationName, City)
      REFERENCES Location (LocationName, City)
							ON UPDATE CASCADE
							ON DELETE NO ACTION
)
GO

/*==============================================================*/
/* Table: Person                                                */
/*==============================================================*/
CREATE TABLE Person (
   PersonNo             D_PERSONNO IDENTITY  NOT NULL,
   FirstName            D_NAME               NOT NULL,
   LastName             D_NAME               NOT NULL,
   MailAddress          D_MAIL               NOT NULL,
   PhoneNumber          D_TELNR              NOT,
   CONSTRAINT PK_PERSON PRIMARY KEY (PersonNo),
   CONSTRAINT AK_MAILADDRES UNIQUE(MailAddress),
   CONSTRAINT CK_MAILADDRESS CHECK (MailAddress LIKE '_%[@]_%[.][a-z][a-z]%'
				AND CHARINDEX('.@', MailAddress) = 0
				AND	CHARINDEX('..', MailAddress) = 0
				AND CHARINDEX(',', MailAddress) = 0
				AND CHARINDEX('{', MailAddress) = 0
				AND CHARINDEX('}', MailAddress) = 0
				AND RIGHT(MailAddress, 1) BETWEEN 'a' AND 'Z')
)
GO

/*==============================================================*/
/* Table: CongressManager                                       */
/*==============================================================*/
CREATE TABLE CongressManager (
   PersonNo             D_PERSONNO           NOT NULL,
   Password             D_PASSWORD           NOT NULL,
   CONSTRAINT PK_CONGRESSMANAGER PRIMARY KEY (PersonNo),
   CONSTRAINT FK_CONGRESS_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: CongressManagerOfCongress                             */
/*==============================================================*/
CREATE TABLE CongressManagerOfCongress (
   PersonNo             D_PERSONNO           NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   CONSTRAINT PK_CONGRESSMANAGEROFCONGRESS PRIMARY KEY (PersonNo, CongressNo),
   CONSTRAINT FK_CONGRESS_RT_CONGRE_CONGRESS FOREIGN KEY (PersonNo)
      REFERENCES CongressManager (PersonNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK2_CONGRESS_RT_CONGRE_CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: Event                                                 */
/*==============================================================*/
CREATE TABLE Event (
   CongressNo           D_CONGRESSNO         NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   EName                D_NAME               NOT NULL,
   Type                 D_TYPE               NOT NULL,
   MaxVisitors          D_CAPACITY           NULL,
   Price                D_PRICE              NULL,
   FileDirectory        D_FILE               NULL,
   Description          D_DESCRIPTION        NULL,
   CONSTRAINT PK_EVENT PRIMARY KEY (CongressNo, EventNo),
   CONSTRAINT FK_EVENT_RT_EVENT__CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							--ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: Track                                                 */
/*==============================================================*/
CREATE TABLE Track (
   CongressNo           D_CONGRESSNO         NOT NULL,
   TrackNo              D_TRACKNO		     NOT NULL,
   Description          D_DESCRIPTION        NULL,
   TName                D_NAME               NOT NULL,
   CONSTRAINT PK_TRACK PRIMARY KEY (CongressNo, TrackNo),
   CONSTRAINT FK_TRACK_RT_TRACK__CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							--ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: EventInTrack                                          */
/*==============================================================*/
CREATE TABLE EventInTrack (
   TrackNo              D_TRACKNO            NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   Start                D_DATETIME           NULL,
   [End]                D_DATETIME           NULL,
   CONSTRAINT PK_EVENTINTRACK PRIMARY KEY (CongressNo, TrackNo, EventNo),
   CONSTRAINT FK_EVENTINT_RT_TRACK__TRACK FOREIGN KEY (CongressNo, TrackNo)
      REFERENCES Track (CongressNo, TrackNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK_EVENTINT_RT_EVENT__EVENT FOREIGN KEY (CongressNo, EventNo)
      REFERENCES Event (CongressNo, EventNo)
							ON UPDATE CASCADE 
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: Room                                                  */
/*==============================================================*/
CREATE TABLE Room (
   LocationName         D_NAME               NOT NULL,
   City                 D_LOCATION           NOT NULL,
   BName                D_NAME               NOT NULL,
   RName                D_NAME               NOT NULL,
   Description          D_DESCRIPTION        NULL,
   MaxNumberOfParticipants D_CAPACITY           NOT NULL,
   CONSTRAINT PK_ROOM PRIMARY KEY (LocationName, City, BName, RName),
   CONSTRAINT FK_ROOM_RT_ROOM_I_BUILDING FOREIGN KEY (LocationName, City, BName)
      REFERENCES Building (LocationName, City, BName)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: EventInRoom                                           */
/*==============================================================*/
CREATE TABLE EventInRoom (
   CongressNo           D_CONGRESSNO         NOT NULL,
   TrackNo              D_TRACKNO            NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   LocationName         D_NAME               NOT NULL,
   City                 D_LOCATION           NOT NULL,
   BName                D_NAME               NOT NULL,
   RName                D_NAME               NOT NULL,
   CONSTRAINT PK_EVENTINROOM PRIMARY KEY (LocationName, TrackNo, City, BName, CongressNo, EventNo, RName),
   CONSTRAINT FK_EVENTINR_RT_EVENT__EVENTINT FOREIGN KEY (CongressNo, TrackNo, EventNo)
      REFERENCES EventInTrack (CongressNo, TrackNo, EventNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE,
   CONSTRAINT FK_EVENTINR_RT_EVENT__ROOM FOREIGN KEY (LocationName, City, BName, RName)
      REFERENCES Room (LocationName, City, BName, RName)
							ON UPDATE CASCADE 
							ON DELETE NO ACTION
)
GO

/*==============================================================*/
/* Table: Visitor                                               */
/*==============================================================*/
CREATE TABLE Visitor (
   PersonNo             D_PERSONNO           NOT NULL,
   Password             D_PASSWORD           NOT NULL,
   CONSTRAINT PK_VISITOR PRIMARY KEY (PersonNo),
   CONSTRAINT FK_VISITOR_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: VisitorOfCongress                                     */
/*==============================================================*/
CREATE TABLE VisitorOfCongress (
   PersonNo             D_PERSONNO           NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   HasPaid              D_BOOLEAN            NOT NULL,
   CONSTRAINT PK_VISITOROFCONGRESS PRIMARY KEY (PersonNo, CongressNo),
   CONSTRAINT FK_VISITORO_RT_VISITO_VISITOR FOREIGN KEY (PersonNo)
      REFERENCES Visitor (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE,
   CONSTRAINT FK_VISITORO_RT_CONGRE_CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION
)
GO

/*==============================================================*/
/* Table: EventOfVisitorOfCongress                              */
/*==============================================================*/
CREATE TABLE EventOfVisitorOfCongress (
   PersonNo             D_PERSONNO           NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   TrackNo              D_TRACKNO            NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   CONSTRAINT PK_EVENTOFVISITOROFCONGRESS PRIMARY KEY (PersonNo, CongressNo, TrackNo, EventNo),
   CONSTRAINT FK_EVENTOFV_RT_EVENT__VISITORO FOREIGN KEY (PersonNo, CongressNo)
      REFERENCES VisitorOfCongress (PersonNo, CongressNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE,
   CONSTRAINT FK_EVENTOFV_RT_EVENT__EVENTINT FOREIGN KEY (CongressNo, TrackNo, EventNo)
      REFERENCES EventInTrack (CongressNo, TrackNo, EventNo)
							ON UPDATE CASCADE 
							ON DELETE NO ACTION
)
GO

/*==============================================================*/
/* Table: GeneralManager                                        */
/*==============================================================*/
CREATE TABLE GeneralManager (
   PersonNo             D_PERSONNO           NOT NULL,
   Password             D_PASSWORD           NOT NULL,
   CONSTRAINT PK_GENERALMANAGER PRIMARY KEY (PersonNo),
   CONSTRAINT FK_GENERALM_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: PersonType                                            */
/*==============================================================*/
CREATE TABLE PersonType (
   TypeName             D_TYPE               NOT NULL,
   CONSTRAINT PK_PERSONTYPE PRIMARY KEY (TypeName),
   CONSTRAINT CK_TYPENAME CHECK (TypeName IN ('Algemene beheerder', 'Congresbeheerder', 'Bezoeker', 'Spreker', 'Reviewboard'))
)
GO

/*==============================================================*/
/* Table: PersonTypeOFPerson                                    */
/*==============================================================*/
CREATE TABLE PersonTypeOfPerson (
   PersonNo             D_PERSONNO           NOT NULL,
   TypeName             D_TYPE               NOT NULL,
   CONSTRAINT PK_PERSONTYPEOFPERSON PRIMARY KEY (PersonNo, TypeName),
   CONSTRAINT FK_PERSONTY_RT_PERSON_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE,
   CONSTRAINT FK_PERSONTY_RT_PERSON_PERSONTY FOREIGN KEY (TypeName)
      REFERENCES PersonType (TypeName)
							ON UPDATE CASCADE
							ON DELETE NO ACTION
)
GO

/*==============================================================*/
/* Table: Reviewboard                                           */
/*==============================================================*/
CREATE TABLE Reviewboard (	
   PersonNo             D_PERSONNO           NOT NULL,
   CONSTRAINT PK_REVIEWBOARD PRIMARY KEY (PersonNo),
   CONSTRAINT FK_REVIEWBO_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: ReviewboardOfCongress                                 */
/*==============================================================*/
CREATE TABLE ReviewboardOfCongress (
   PersonNo             D_PERSONNO           NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   CONSTRAINT PK_REVIEWBOARDOFCONGRESS PRIMARY KEY (PersonNo, CongressNo),
   CONSTRAINT FK_REVIEWBO_RT_REVIEW_REVIEWBO FOREIGN KEY (PersonNo)
      REFERENCES Reviewboard (PersonNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK_REVIEWBO_RT_REVIEW_CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: Speaker                                               */
/*==============================================================*/
CREATE TABLE Speaker (
   PersonNo             D_PERSONNO           NOT NULL,
   Description          D_DESCRIPTION        NULL,
   PicturePath          D_FILE               NULL,
   CONSTRAINT PK_SPEAKER PRIMARY KEY (PersonNo),
   CONSTRAINT FK_SPEAKER_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: SpeakerOfCongress                                    */
/*==============================================================*/
CREATE TABLE SpeakerOfCongress (
   PersonNo             D_PERSONNO			 NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   Agreement            D_DESCRIPTION        NULL,
   CONSTRAINT PK_SPEAKEROFCONGRESS PRIMARY KEY (PersonNo, CongressNo),
   CONSTRAINT FK_SPEAKERO_RT_SPEAKE_SPEAKER FOREIGN KEY (PersonNo)
      REFERENCES Speaker (PersonNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK_SPEAKERO_RT_CONGRE_CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: SpeakerOfEvent                                        */
/*==============================================================*/
CREATE TABLE SpeakerOfEvent (
   PersonNo             D_PERSONNO           NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   CONSTRAINT PK_SPEAKEROFEVENT PRIMARY KEY (CongressNo, PersonNo, EventNo),
   CONSTRAINT FK2_SPEAKERO_RT_SPEAKE_SPEAKER FOREIGN KEY (PersonNo)
      REFERENCES Speaker (PersonNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK_SPEAKERO_RT_SPEAKE_EVENT FOREIGN KEY (CongressNo, EventNo)
      REFERENCES Event (CongressNo, EventNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: Subject                                               */
/*==============================================================*/
CREATE TABLE Subject (
   Subject              D_SUBJECT            NOT NULL,
   CONSTRAINT PK_Subject PRIMARY KEY (Subject)
)
GO

/*==============================================================*/
/* Table: SubjectOfCongress                                     */
/*==============================================================*/
CREATE TABLE SubjectOfCongress (
   Subject              D_SUBJECT            NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   CONSTRAINT PK_SUBJECTOFCONGRESS PRIMARY KEY (Subject, CongressNo),
   CONSTRAINT FK_SUBJECTO_RT_CONGRE_SUBJECT FOREIGN KEY (Subject)
      REFERENCES Subject (Subject)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK_SUBJECTO_RT_CONGRE_CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
GO

/*==============================================================*/
/* Table: SubjectOfEvent                                        */
/*==============================================================*/
CREATE TABLE SubjectOfEvent (
   CongressNo           D_CONGRESSNO         NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   Subject              D_SUBJECT            NOT NULL,
   CONSTRAINT PK_SUBJECTOFEVENT PRIMARY KEY (CongressNo, EventNo, Subject),
   CONSTRAINT FK_SUBJECTO_RT_EVENT__EVENT FOREIGN KEY (CongressNo, EventNo)
      REFERENCES EVENT (CongressNo, EventNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE,
   CONSTRAINT FK_SUBJECTO_RT_EVENT__SUBJECT FOREIGN KEY (Subject)
      REFERENCES Subject (Subject)
							ON UPDATE CASCADE
							ON DELETE NO ACTION
)
GO

