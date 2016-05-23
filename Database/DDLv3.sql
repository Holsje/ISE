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
create Type D_BOOLEAN
   from bit
go

/*==============================================================*/
/* Domain: D_CAPACITY                                           */
/*==============================================================*/
create Type D_CAPACITY
   from smallint
go

/*==============================================================*/
/* Domain: D_CONGRESSNO                                         */
/*==============================================================*/
create Type D_CONGRESSNO
   from int
go

/*==============================================================*/
/* Domain: D_DATE                                               */
/*==============================================================*/
create Type D_DATE
   from datetime
go

/*==============================================================*/
/* Domain: D_DATETIME                                           */
/*==============================================================*/
create Type D_DATETIME
   from datetime
go

/*==============================================================*/
/* Domain: D_DESCRIPTION                                        */
/*==============================================================*/
create Type D_DESCRIPTION
   from varchar(150)
go

/*==============================================================*/
/* Domain: D_EVENTNO                                            */
/*==============================================================*/
create Type D_EVENTNO
   from int
go

/*==============================================================*/
/* Domain: D_FILE                                               */
/*==============================================================*/
create Type D_FILE
   from varchar(500)
go

/*==============================================================*/
/* Domain: D_HOUSENO                                            */
/*==============================================================*/
create Type D_HOUSENO
   from smallint
go

/*==============================================================*/
/* Domain: D_LOCATION                                           */
/*==============================================================*/
create Type D_LOCATION
   from varchar(20)
go

/*==============================================================*/
/* Domain: D_MAIL                                               */
/*==============================================================*/
create Type D_MAIL
   from varchar(50)
go

/*==============================================================*/
/* Domain: D_NAME                                               */
/*==============================================================*/
create Type D_NAME
   from varchar(50)
go

/*==============================================================*/
/* Domain: D_PASSWORD                                           */
/*==============================================================*/
create Type D_PASSWORD
   from varchar(64)
go

/*==============================================================*/
/* Domain: D_PERSONNO                                           */
/*==============================================================*/
create Type D_PERSONNO
   from int
go

/*==============================================================*/
/* Domain: D_PRICE                                              */
/*==============================================================*/
create Type D_PRICE
   from money
go

/*==============================================================*/
/* Domain: D_SUBJECT                                            */
/*==============================================================*/
create Type D_SUBJECT
   from varchar(50)
go

/*==============================================================*/
/* Domain: D_TELNR                                              */
/*==============================================================*/
create Type D_TELNR
   from varchar(25)
go

/*==============================================================*/
/* Domain: D_TRACKNO                                            */
/*==============================================================*/
create Type D_TRACKNO
   from int
go

/*==============================================================*/
/* Domain: D_TYPE                                               */
/*==============================================================*/
create Type D_TYPE
   from varchar(18)
go

/*==============================================================*/
/* Domain: D_USERNAME                                           */
/*==============================================================*/
create Type D_USERNAME
   from varchar(20)
go

/*==============================================================*/
/* Domain: D_ZIPCODE                                            */
/*==============================================================*/
create Type D_ZIPCODE
   from varchar(6)
go

/*==============================================================*/
/* Table: Location                                              */
/*==============================================================*/
create table Location (
   LocationName         D_NAME               NOT NULL,
   City                 D_LOCATION           NOT NULL,
   CONSTRAINT PK_Location PRIMARY KEY (LocationName, City)
)
go

/*==============================================================*/
/* Table: Building                                              */
/*==============================================================*/
create table Building (
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
go

/*==============================================================*/
/* Table: Congress                                              */
/*==============================================================*/
create table Congress (
   CongressNo           D_CONGRESSNO         NOT NULL,
   LocationName         D_NAME               NULL,
   City                 D_LOCATION           NULL,
   CName                D_NAME               NOT NULL,
   Startdate            D_DATE               NULL,
   Enddate              D_DATE               NULL,
   Price                D_PRICE              NULL,
   [Public]             D_BOOLEAN            NOT NULL,
   CONSTRAINT PK_CONGRESS PRIMARY KEY (CongressNo),
   CONSTRAINT FK_CONGRESS_RT_CONGRE_LOCATION FOREIGN KEY (LocationName, City)
      REFERENCES Location (LocationName, City)
							ON UPDATE CASCADE
							ON DELETE NO ACTION
)
go

/*==============================================================*/
/* Table: Person                                                */
/*==============================================================*/
create table Person (
   PersonNo             D_PERSONNO           NOT NULL,
   FirstName            D_NAME               NOT NULL,
   LastName             D_NAME               NOT NULL,
   MailAddress          D_MAIL               NOT NULL,
   PhoneNumber          D_TELNR              NOT NULL,
   CONSTRAINT PK_PERSON PRIMARY KEY (PersonNo)
)
go

/*==============================================================*/
/* Table: CongressManager                                       */
/*==============================================================*/
create table CongressManager (
   PersonNo             D_PERSONNO           NOT NULL,
   Password             D_PASSWORD           NOT NULL,
   CONSTRAINT PK_CONGRESSMANAGER PRIMARY KEY (PersonNo),
   CONSTRAINT FK_CONGRESS_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
go

/*==============================================================*/
/* Table: CongressManagerOfCongress                             */
/*==============================================================*/
create table CongressManagerOfCongress (
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
go

/*==============================================================*/
/* Table: Event                                                 */
/*==============================================================*/
create table Event (
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
							ON DELETE NO ACTION
)
go

/*==============================================================*/
/* Table: Track                                                 */
/*==============================================================*/
create table Track (
   CongressNo           D_CONGRESSNO         NOT NULL,
   TrackNo              D_TRACKNO            NOT NULL,
   Description          D_DESCRIPTION        NULL,
   TName                D_NAME               NOT NULL,
   CONSTRAINT PK_TRACK PRIMARY KEY (CongressNo, TrackNo),
   CONSTRAINT FK_TRACK_RT_TRACK__CONGRESS FOREIGN KEY (CongressNo)
      REFERENCES Congress (CongressNo)
							--ON UPDATE CASCADE
							ON DELETE NO ACTION
)
go

/*==============================================================*/
/* Table: EventInTrack                                          */
/*==============================================================*/
create table EventInTrack (
   TRA_CongressNo       D_CONGRESSNO         NOT NULL,
   TrackNo              D_TRACKNO            NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   START                D_DATETIME           NULL,
   [End]                D_DATETIME           NULL,
   CONSTRAINT PK_EVENTINTRACK PRIMARY KEY (TRA_CongressNo, CongressNo, TrackNo, EventNo),
   CONSTRAINT FK_EVENTINT_RT_TRACK__TRACK FOREIGN KEY (TRA_CongressNo, TrackNo)
      REFERENCES Track (CongressNo, TrackNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK_EVENTINT_RT_EVENT__EVENT FOREIGN KEY (CongressNo, EventNo)
      REFERENCES Event (CongressNo, EventNo)
							ON UPDATE CASCADE -- DEZE EVENTUEEL ANDERS	
							ON DELETE CASCADE
)
go

/*==============================================================*/
/* Table: Room                                                  */
/*==============================================================*/
create table Room (
   LocationName         D_NAME               NOT NULL,
   City                 D_LOCATION           NOT NULL,
   BName                D_NAME               NOT NULL,
   RName                D_NAME               NOT NULL,
   Description          D_DESCRIPTION        NOT NULL,
   MaxNumberOfParticipants D_CAPACITY           NOT NULL,
   CONSTRAINT PK_ROOM PRIMARY KEY (LocationName, City, BName, RName),
   CONSTRAINT FK_ROOM_RT_ROOM_I_BUILDING FOREIGN KEY (LocationName, City, BName)
      REFERENCES Building (LocationName, City, BName)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
go

/*==============================================================*/
/* Table: EventInRoom                                           */
/*==============================================================*/
create table EventInRoom (
   CongressNo           D_CONGRESSNO         NOT NULL,
   TrackNo              D_TRACKNO            NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   LocationName         D_NAME               NOT NULL,
   City                 D_LOCATION           NOT NULL,
   BName                D_NAME               NOT NULL,
   RName                D_NAME               NOT NULL,
   TRA_CongressNo       D_CONGRESSNO         NOT NULL,
   CONSTRAINT PK_EVENTINROOM PRIMARY KEY (LocationName, TrackNo, City, BName, CongressNo, EventNo, RName, TRA_CongressNo),
   CONSTRAINT FK_EVENTINR_RT_EVENT__EVENTINT FOREIGN KEY (TRA_CongressNo, CongressNo, TrackNo, EventNo)
      REFERENCES EventInTrack (TRA_CongressNo, CongressNo, TrackNo, EventNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE,
   CONSTRAINT FK_EVENTINR_RT_EVENT__ROOM FOREIGN KEY (LocationName, City, BName, RName)
      REFERENCES Room (LocationName, City, BName, RName)
							ON UPDATE CASCADE -- DEZE EVENTUEEL ANDERS
							ON DELETE NO ACTION
)
go

/*==============================================================*/
/* Table: Visitor                                               */
/*==============================================================*/
create table Visitor (
   PersonNo             D_PERSONNO           NOT NULL,
   Password             D_PASSWORD           NOT NULL,
   CONSTRAINT PK_VISITOR PRIMARY KEY (PersonNo),
   CONSTRAINT FK_VISITOR_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
go

/*==============================================================*/
/* Table: VisitorOfCongress                                     */
/*==============================================================*/
create table VisitorOfCongress (
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
go

/*==============================================================*/
/* Table: EventOfVisitorOfCongress                              */
/*==============================================================*/
create table EventOfVisitorOfCongress (
   PersonNo             D_PERSONNO           NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   EVE_CongressNo       D_CONGRESSNO         NOT NULL,
   TrackNo              D_TRACKNO            NOT NULL,
   EventNo              D_EVENTNO            NOT NULL,
   TRA_CongressNo       D_CONGRESSNO         NOT NULL,
   CONSTRAINT PK_EVENTOFVISITOROFCONGRESS PRIMARY KEY (PersonNo, CongressNo, EVE_CongressNo, TrackNo, EventNo, TRA_CongressNo),
   CONSTRAINT FK_EVENTOFV_RT_EVENT__VISITORO FOREIGN KEY (PersonNo, CongressNo)
      REFERENCES VisitorOfCongress (PersonNo, CongressNo)
							ON UPDATE CASCADE
							ON DELETE NO ACTION,
   CONSTRAINT FK_EVENTOFV_RT_EVENT__EVENTINT FOREIGN KEY (TRA_CongressNo, EVE_CongressNo, TrackNo, EventNo)
      REFERENCES EventInTrack (TRA_CongressNo, CongressNo, TrackNo, EventNo)
							ON UPDATE CASCADE --DEZE EVENTUEEL ANDERS
							ON DELETE NO ACTION
)
go

/*==============================================================*/
/* Table: GeneralManager                                        */
/*==============================================================*/
create table GeneralManager (
   PersonNo             D_PERSONNO           NOT NULL,
   Password             D_PASSWORD           NOT NULL,
   CONSTRAINT PK_GENERALMANAGER PRIMARY KEY (PersonNo),
   CONSTRAINT FK_GENERALM_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
go

/*==============================================================*/
/* Table: PersonType                                            */
/*==============================================================*/
create table PersonType (
   TypeName             D_TYPE               NOT NULL,
   CONSTRAINT PK_PERSONTYPE PRIMARY KEY (TypeName)
)
go

/*==============================================================*/
/* Table: PersonTypeOFPerson                                    */
/*==============================================================*/
create table PersonTypeOfPerson (
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
go

/*==============================================================*/
/* Table: Reviewboard                                           */
/*==============================================================*/
create table Reviewboard (	
   PersonNo             D_PERSONNO           NOT NULL,
   CONSTRAINT PK_REVIEWBOARD PRIMARY KEY (PersonNo),
   CONSTRAINT FK_REVIEWBO_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
go

/*==============================================================*/
/* Table: ReviewboardOfCongress                                 */
/*==============================================================*/
create table ReviewboardOfCongress (
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
go

/*==============================================================*/
/* Table: Speaker                                               */
/*==============================================================*/
create table Speaker (
   PersonNo             D_PERSONNO           NOT NULL,
   Description          D_DESCRIPTION        NOT NULL,
   PicturePath          D_FILE               NOT NULL,
   CONSTRAINT PK_SPEAKER PRIMARY KEY (PersonNo),
   CONSTRAINT FK_SPEAKER_INHERITAN_PERSON FOREIGN KEY (PersonNo)
      REFERENCES Person (PersonNo)
							ON UPDATE CASCADE
							ON DELETE CASCADE
)
go

/*==============================================================*/
/* Table: SpeakerOfCongress                                    */
/*==============================================================*/
create table SpeakerOfCongress (
   PersonNo             D_PERSONNO          NOT NULL,
   CongressNo           D_CONGRESSNO         NOT NULL,
   Agreement            D_DESCRIPTION        NOT NULL,
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
go

/*==============================================================*/
/* Table: SpeakerOfEvent                                        */
/*==============================================================*/
create table SpeakerOfEvent (
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
go

/*==============================================================*/
/* Table: Subject                                               */
/*==============================================================*/
create table Subject (
   Subject              D_SUBJECT            NOT NULL,
   CONSTRAINT PK_Subject PRIMARY KEY (Subject)
)
go

/*==============================================================*/
/* Table: SubjectOfCongress                                     */
/*==============================================================*/
create table SubjectOfCongress (
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
go

/*==============================================================*/
/* Table: SubjectOfEvent                                        */
/*==============================================================*/
create table SubjectOfEvent (
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
go

