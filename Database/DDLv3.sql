/*==============================================================*/
/* DBMS name:      Microsoft SQL Server 2008                    */
/* Created on:     19-5-2016 11:50:25                           */
/*==============================================================*/


if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('CONGRESS') and o.name = 'FK_CONGRESS_RT_CONGRE_SUBJECT')
alter table CONGRESS
   drop constraint FK_CONGRESS_RT_CONGRE_SUBJECT
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('CONGRESSMANAGEROFCONGRESS') and o.name = 'FK_CONGRESS_RT_CONGRE_CONGRESS')
alter table CONGRESSMANAGEROFCONGRESS
   drop constraint FK_CONGRESS_RT_CONGRE_CONGRESS
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('CONGRESSMANAGEROFCONGRESS') and o.name = 'FK2_CONGRESS_RT_CONGRE_CONGRESS')
alter table CONGRESSMANAGEROFCONGRESS
   drop constraint FK2_CONGRESS_RT_CONGRE_CONGRESS
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('EVENTINROOM') and o.name = 'FK_EVENTINR_RT_EVENT__EVENT')
alter table EVENTINROOM
   drop constraint FK_EVENTINR_RT_EVENT__EVENT
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('EVENTINROOM') and o.name = 'FK_EVENTINR_RT_EVENT__ROOM')
alter table EVENTINROOM
   drop constraint FK_EVENTINR_RT_EVENT__ROOM
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('EVENTINTRACK') and o.name = 'FK_EVENTINT_RT_EVENT__TRACK')
alter table EVENTINTRACK
   drop constraint FK_EVENTINT_RT_EVENT__TRACK
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('EVENTINTRACK') and o.name = 'FK_EVENTINT_RT_EVENT__EVENT')
alter table EVENTINTRACK
   drop constraint FK_EVENTINT_RT_EVENT__EVENT
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('EVENTOFVISITOROFCONGRESS') and o.name = 'FK_EVENTOFV_RT_EVENT__VISITORO')
alter table EVENTOFVISITOROFCONGRESS
   drop constraint FK_EVENTOFV_RT_EVENT__VISITORO
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('EVENTOFVISITOROFCONGRESS') and o.name = 'FK_EVENTOFV_RT_EVENT__EVENT')
alter table EVENTOFVISITOROFCONGRESS
   drop constraint FK_EVENTOFV_RT_EVENT__EVENT
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('PERSONTYPEOFPERSON') and o.name = 'FK_PERSONTY_RT_PERSON_PERSON')
alter table PERSONTYPEOFPERSON
   drop constraint FK_PERSONTY_RT_PERSON_PERSON
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('PERSONTYPEOFPERSON') and o.name = 'FK_PERSONTY_RT_PERSON_PERSONTY')
alter table PERSONTYPEOFPERSON
   drop constraint FK_PERSONTY_RT_PERSON_PERSONTY
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('REVIEWBOARD') and o.name = 'FK_REVIEWBO_INHERITAN_PERSON')
alter table REVIEWBOARD
   drop constraint FK_REVIEWBO_INHERITAN_PERSON
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('REVIEWBOARDOFCONGRESS') and o.name = 'FK_REVIEWBO_RT_REVIEW_REVIEWBO')
alter table REVIEWBOARDOFCONGRESS
   drop constraint FK_REVIEWBO_RT_REVIEW_REVIEWBO
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('REVIEWBOARDOFCONGRESS') and o.name = 'FK_REVIEWBO_RT_REVIEW_CONGRESS')
alter table REVIEWBOARDOFCONGRESS
   drop constraint FK_REVIEWBO_RT_REVIEW_CONGRESS
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('ROOM') and o.name = 'FK_ROOM_RT_ROOM_I_BUILDING')
alter table ROOM
   drop constraint FK_ROOM_RT_ROOM_I_BUILDING
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('SPEAKER') and o.name = 'FK_SPEAKER_INHERITAN_PERSON')
alter table SPEAKER
   drop constraint FK_SPEAKER_INHERITAN_PERSON
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('SPEAKEROFCONGRESS') and o.name = 'FK_SPEAKERO_RT_CONGRE_CONGRESS')
alter table SPEAKEROFCONGRESS
   drop constraint FK_SPEAKERO_RT_CONGRE_CONGRESS
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('SPEAKEROFCONGRESS') and o.name = 'FK2_SPEAKERO_RT_SPEAKE_SPEAKER')
alter table SPEAKEROFCONGRESS
   drop constraint FK2_SPEAKERO_RT_SPEAKE_SPEAKER
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('SPEAKEROFEVENT') and o.name = 'FK_SPEAKERO_RT_SPEAKE_SPEAKER')
alter table SPEAKEROFEVENT
   drop constraint FK_SPEAKERO_RT_SPEAKE_SPEAKER
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('SPEAKEROFEVENT') and o.name = 'FK_SPEAKERO_RT_SPEAKE_EVENT')
alter table SPEAKEROFEVENT
   drop constraint FK_SPEAKERO_RT_SPEAKE_EVENT
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('VISITOROFCONGRESS') and o.name = 'FK_VISITORO_RT_CONGRE_CONGRESS')
alter table VISITOROFCONGRESS
   drop constraint FK_VISITORO_RT_CONGRE_CONGRESS
go

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('VISITOROFCONGRESS') and o.name = 'FK_VISITORO_RT_VISITO_VISITOR')
alter table VISITOROFCONGRESS
   drop constraint FK_VISITORO_RT_VISITO_VISITOR
go

if exists (select 1
            from  sysobjects
           where  id = object_id('BUILDING')
            and   type = 'U')
   drop table BUILDING
go

if exists (select 1
            from  sysobjects
           where  id = object_id('CONGRESS')
            and   type = 'U')
   drop table CONGRESS
go

if exists (select 1
            from  sysobjects
           where  id = object_id('CONGRESSMANAGER')
            and   type = 'U')
   drop table CONGRESSMANAGER
go

if exists (select 1
            from  sysobjects
           where  id = object_id('CONGRESSMANAGEROFCONGRESS')
            and   type = 'U')
   drop table CONGRESSMANAGEROFCONGRESS
go

if exists (select 1
            from  sysobjects
           where  id = object_id('EVENT')
            and   type = 'U')
   drop table EVENT
go

if exists (select 1
            from  sysobjects
           where  id = object_id('EVENTINROOM')
            and   type = 'U')
   drop table EVENTINROOM
go

if exists (select 1
            from  sysobjects
           where  id = object_id('EVENTINTRACK')
            and   type = 'U')
   drop table EVENTINTRACK
go

if exists (select 1
            from  sysobjects
           where  id = object_id('EVENTOFVISITOROFCONGRESS')
            and   type = 'U')
   drop table EVENTOFVISITOROFCONGRESS
go

if exists (select 1
            from  sysobjects
           where  id = object_id('GENERALMANAGER')
            and   type = 'U')
   drop table GENERALMANAGER
go

if exists (select 1
            from  sysobjects
           where  id = object_id('LOCATION')
            and   type = 'U')
   drop table LOCATION
go

if exists (select 1
            from  sysobjects
           where  id = object_id('PERSON')
            and   type = 'U')
   drop table PERSON
go

if exists (select 1
            from  sysobjects
           where  id = object_id('PERSONTYPE')
            and   type = 'U')
   drop table PERSONTYPE
go

if exists (select 1
            from  sysobjects
           where  id = object_id('PERSONTYPEOFPERSON')
            and   type = 'U')
   drop table PERSONTYPEOFPERSON
go

if exists (select 1
            from  sysobjects
           where  id = object_id('REVIEWBOARD')
            and   type = 'U')
   drop table REVIEWBOARD
go

if exists (select 1
            from  sysobjects
           where  id = object_id('REVIEWBOARDOFCONGRESS')
            and   type = 'U')
   drop table REVIEWBOARDOFCONGRESS
go

if exists (select 1
            from  sysobjects
           where  id = object_id('ROOM')
            and   type = 'U')
   drop table ROOM
go

if exists (select 1
            from  sysobjects
           where  id = object_id('SPEAKER')
            and   type = 'U')
   drop table SPEAKER
go

if exists (select 1
            from  sysobjects
           where  id = object_id('SPEAKEROFCONGRESS')
            and   type = 'U')
   drop table SPEAKEROFCONGRESS
go

if exists (select 1
            from  sysobjects
           where  id = object_id('SPEAKEROFEVENT')
            and   type = 'U')
   drop table SPEAKEROFEVENT
go

if exists (select 1
            from  sysobjects
           where  id = object_id('SUBJECT')
            and   type = 'U')
   drop table SUBJECT
go

if exists (select 1
            from  sysobjects
           where  id = object_id('TRACK')
            and   type = 'U')
   drop table TRACK
go

if exists (select 1
            from  sysobjects
           where  id = object_id('VISITOR')
            and   type = 'U')
   drop table VISITOR
go

if exists (select 1
            from  sysobjects
           where  id = object_id('VISITOROFCONGRESS')
            and   type = 'U')
   drop table VISITOROFCONGRESS
go

if exists(select 1 from systypes where name='D_BOOLEAN')
   drop type D_BOOLEAN
go

if exists(select 1 from systypes where name='D_CAPACITY')
   drop type D_CAPACITY
go

if exists(select 1 from systypes where name='D_CONGRESSNO')
   drop type D_CONGRESSNO
go

if exists(select 1 from systypes where name='D_DATE')
   drop type D_DATE
go

if exists(select 1 from systypes where name='D_DATETIME')
   drop type D_DATETIME
go

if exists(select 1 from systypes where name='D_DESCRIPTION')
   drop type D_DESCRIPTION
go

if exists(select 1 from systypes where name='D_EVENTNO')
   drop type D_EVENTNO
go

if exists(select 1 from systypes where name='D_FILE')
   drop type D_FILE
go

if exists(select 1 from systypes where name='D_HOUSENO')
   drop type D_HOUSENO
go

if exists(select 1 from systypes where name='D_LOCATION')
   drop type D_LOCATION
go

if exists(select 1 from systypes where name='D_MAIL')
   drop type D_MAIL
go

if exists(select 1 from systypes where name='D_NAME')
   drop type D_NAME
go

if exists(select 1 from systypes where name='D_PASSWORD')
   drop type D_PASSWORD
go

if exists(select 1 from systypes where name='D_PERSONNO')
   drop type D_PERSONNO
go

if exists(select 1 from systypes where name='D_PRICE')
   drop type D_PRICE
go

if exists(select 1 from systypes where name='D_SUBJECT')
   drop type D_SUBJECT
go

if exists(select 1 from systypes where name='D_TELNR')
   drop type D_TELNR
go

if exists(select 1 from systypes where name='D_TRACKNO')
   drop type D_TRACKNO
go

if exists(select 1 from systypes where name='D_TYPE')
   drop type D_TYPE
go

if exists(select 1 from systypes where name='D_USERNAME')
   drop type D_USERNAME
go

if exists(select 1 from systypes where name='D_ZIPCODE')
   drop type D_ZIPCODE
go

/*==============================================================*/
/* Domain: D_BOOLEAN                                            */
/*==============================================================*/
create type D_BOOLEAN
   from bit
go

/*==============================================================*/
/* Domain: D_CAPACITY                                           */
/*==============================================================*/
create type D_CAPACITY
   from smallint
go

/*==============================================================*/
/* Domain: D_CONGRESSNO                                         */
/*==============================================================*/
create type D_CONGRESSNO
   from int
go

/*==============================================================*/
/* Domain: D_DATE                                               */
/*==============================================================*/
create type D_DATE
   from datetime
go

/*==============================================================*/
/* Domain: D_DATETIME                                           */
/*==============================================================*/
create type D_DATETIME
   from datetime
go

/*==============================================================*/
/* Domain: D_DESCRIPTION                                        */
/*==============================================================*/
create type D_DESCRIPTION
   from varchar(150)
go

/*==============================================================*/
/* Domain: D_EVENTNO                                            */
/*==============================================================*/
create type D_EVENTNO
   from int
go

/*==============================================================*/
/* Domain: D_FILE                                               */
/*==============================================================*/
create type D_FILE
   from varchar(500)
go

/*==============================================================*/
/* Domain: D_HOUSENO                                            */
/*==============================================================*/
create type D_HOUSENO
   from smallint
go

/*==============================================================*/
/* Domain: D_LOCATION                                           */
/*==============================================================*/
create type D_LOCATION
   from varchar(20)
go

/*==============================================================*/
/* Domain: D_MAIL                                               */
/*==============================================================*/
create type D_MAIL
   from varchar(50)
go

/*==============================================================*/
/* Domain: D_NAME                                               */
/*==============================================================*/
create type D_NAME
   from varchar(50)
go

/*==============================================================*/
/* Domain: D_PASSWORD                                           */
/*==============================================================*/
create type D_PASSWORD
   from varchar(64)
go

/*==============================================================*/
/* Domain: D_PERSONNO                                           */
/*==============================================================*/
create type D_PERSONNO
   from int
go

/*==============================================================*/
/* Domain: D_PRICE                                              */
/*==============================================================*/
create type D_PRICE
   from money
go

/*==============================================================*/
/* Domain: D_SUBJECT                                            */
/*==============================================================*/
create type D_SUBJECT
   from varchar(50)
go

/*==============================================================*/
/* Domain: D_TELNR                                              */
/*==============================================================*/
create type D_TELNR
   from varchar(25)
go

/*==============================================================*/
/* Domain: D_TRACKNO                                            */
/*==============================================================*/
create type D_TRACKNO
   from int
go

/*==============================================================*/
/* Domain: D_TYPE                                               */
/*==============================================================*/
create type D_TYPE
   from varchar(18)
go

/*==============================================================*/
/* Domain: D_USERNAME                                           */
/*==============================================================*/
create type D_USERNAME
   from varchar(20)
go

/*==============================================================*/
/* Domain: D_ZIPCODE                                            */
/*==============================================================*/
create type D_ZIPCODE
   from varchar(6)
go

/*==============================================================*/
/* Table: LOCATION                                              */
/*==============================================================*/
create table LOCATION (
   LOCATIONNAME         D_NAME               not null,
   CITY2                D_LOCATION           not null,
   constraint PK_LOCATION primary key nonclustered (LOCATIONNAME, CITY2)
)
go

/*==============================================================*/
/* Table: BUILDING                                              */
/*==============================================================*/
create table BUILDING (
   LOCATIONNAME         D_NAME               not null,
   CITY2                D_LOCATION           not null,
   BNAME                D_NAME               not null,
   STREET               D_NAME               not null,
   HOUSENO              D_HOUSENO            not null,
   POSTALCODE           D_ZIPCODE            not null,
   constraint PK_BUILDING primary key nonclustered (LOCATIONNAME, CITY2, BNAME),
   constraint FK_BUILDING_RT_BUILDI_LOCATION foreign key (LOCATIONNAME, CITY2)
      references LOCATION (LOCATIONNAME, CITY2)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: SUBJECT                                               */
/*==============================================================*/
create table SUBJECT (
   SUBJECT4             D_SUBJECT            not null,
   constraint PK_SUBJECT primary key nonclustered (SUBJECT4)
)
go

/*==============================================================*/
/* Table: CONGRESS                                              */
/*==============================================================*/
create table CONGRESS (
   CONGRESSNO2          D_CONGRESSNO         not null,
   LOCATIONNAME         D_NAME               null,
   CITY2                D_LOCATION           null,
   SUBJECT4             D_SUBJECT            null,
   CNAME                D_NAME               not null,
   STARTDATE            D_DATE               null,
   ENDDATE              D_DATE               null,
   PRICE                D_PRICE              null,
   "PUBLIC"             D_BOOLEAN            null,
   constraint PK_CONGRESS primary key nonclustered (CONGRESSNO2),
   constraint FK_CONGRESS_RT_CONGRE_SUBJECT foreign key (SUBJECT4)
      references SUBJECT (SUBJECT4)
         on update cascade,
   constraint FK_CONGRESS_RT_CONGRE_LOCATION foreign key (LOCATIONNAME, CITY2)
      references LOCATION (LOCATIONNAME, CITY2)
         on update cascade
)
go

/*==============================================================*/
/* Table: PERSON                                                */
/*==============================================================*/
create table PERSON (
   PERSONNO             D_PERSONNO           not null,
   FIRSTNAME            D_NAME               not null,
   LASTNAME             D_NAME               not null,
   MAILADDRESS          D_MAIL               not null,
   PHONENUMBER          D_TELNR              not null,
   constraint PK_PERSON primary key nonclustered (PERSONNO)
)
go

/*==============================================================*/
/* Table: CONGRESSMANAGER                                       */
/*==============================================================*/
create table CONGRESSMANAGER (
   PERSONNO             D_PERSONNO           not null,
   USERNAME             D_USERNAME           not null,
   PASSWORD             D_PASSWORD           not null,
   constraint PK_CONGRESSMANAGER primary key (PERSONNO),
   constraint FK_CONGRESS_INHERITAN_PERSON foreign key (PERSONNO)
      references PERSON (PERSONNO)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: CONGRESSMANAGEROFCONGRESS                             */
/*==============================================================*/
create table CONGRESSMANAGEROFCONGRESS (
   PERSONNO             D_PERSONNO           not null,
   CONGRESSNO2          D_CONGRESSNO         not null,
   constraint PK_CONGRESSMANAGEROFCONGRESS primary key (PERSONNO, CONGRESSNO2),
   constraint FK_CONGRESS_RT_CONGRE_CONGRESS foreign key (PERSONNO)
      references CONGRESSMANAGER (PERSONNO)
         on update cascade on delete cascade,
   constraint FK2_CONGRESS_RT_CONGRE_CONGRESS foreign key (CONGRESSNO2)
      references CONGRESS (CONGRESSNO2)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: EVENT                                                 */
/*==============================================================*/
create table EVENT (
   CONGRESSNO2          D_CONGRESSNO         not null,
   EVENTNO              D_EVENTNO            not null,
   ENAME                D_NAME               not null,
   TYPE2                D_TYPE               not null,
   MAX_VISITORS         D_CAPACITY           not null,
   START                D_DATETIME           null,
   "END"                D_DATETIME           null,
   SUBJECT              D_SUBJECT            not null,
   PRICE                D_PRICE              null,
   constraint PK_EVENT primary key nonclustered (CONGRESSNO2, EVENTNO),
   constraint FK_EVENT_RT_EVENT__CONGRESS foreign key (CONGRESSNO2)
      references CONGRESS (CONGRESSNO2)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: ROOM                                                  */
/*==============================================================*/
create table ROOM (
   LOCATIONNAME         D_NAME               not null,
   CITY2                D_LOCATION           not null,
   BNAME                D_NAME               not null,
   RNAME                D_NAME               not null,
   DESCRIPTION          D_DESCRIPTION        not null,
   MAXNUMBEROFPARTICIPANTS D_CAPACITY           not null,
   constraint PK_ROOM primary key nonclustered (LOCATIONNAME, CITY2, BNAME, RNAME),
   constraint FK_ROOM_RT_ROOM_I_BUILDING foreign key (LOCATIONNAME, CITY2, BNAME)
      references BUILDING (LOCATIONNAME, CITY2, BNAME)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: EVENTINROOM                                           */
/*==============================================================*/
create table EVENTINROOM (
   CONGRESSNO2          D_CONGRESSNO         not null,
   EVENTNO              D_EVENTNO            not null,
   LOCATIONNAME         D_NAME               not null,
   CITY2                D_LOCATION           not null,
   BNAME                D_NAME               not null,
   RNAME                D_NAME               not null,
   constraint PK_EVENTINROOM primary key (LOCATIONNAME, CITY2, BNAME, CONGRESSNO2, EVENTNO, RNAME),
   constraint FK_EVENTINR_RT_EVENT__EVENT foreign key (CONGRESSNO2, EVENTNO)
      references EVENT (CONGRESSNO2, EVENTNO)
         on update cascade on delete cascade,
   constraint FK_EVENTINR_RT_EVENT__ROOM foreign key (LOCATIONNAME, CITY2, BNAME, RNAME)
      references ROOM (LOCATIONNAME, CITY2, BNAME, RNAME)
         on update cascade
)
go

/*==============================================================*/
/* Table: TRACK                                                 */
/*==============================================================*/
create table TRACK (
   CONGRESSNO2          D_CONGRESSNO         not null,
   TRACKNO              D_TRACKNO            not null,
   DESCRIPTION          D_DESCRIPTION        null,
   TRACKNAME            D_NAME               null,
   constraint PK_TRACK primary key nonclustered (CONGRESSNO2, TRACKNO),
   constraint FK_TRACK_RT_TRACK__CONGRESS foreign key (CONGRESSNO2)
      references CONGRESS (CONGRESSNO2)
         on update cascade
)
go

/*==============================================================*/
/* Table: EVENTINTRACK                                          */
/*==============================================================*/
create table EVENTINTRACK (
   TRA_CONGRESSNO2      D_CONGRESSNO         not null,
   TRACKNO              D_TRACKNO            not null,
   CONGRESSNO2          D_CONGRESSNO         not null,
   EVENTNO              D_EVENTNO            not null,
   constraint PK_EVENTINTRACK primary key (TRA_CONGRESSNO2, TRACKNO, CONGRESSNO2, EVENTNO),
   constraint FK_EVENTINT_RT_EVENT__TRACK foreign key (TRA_CONGRESSNO2, TRACKNO)
      references TRACK (CONGRESSNO2, TRACKNO)
         on update cascade,
   constraint FK_EVENTINT_RT_EVENT__EVENT foreign key (CONGRESSNO2, EVENTNO)
      references EVENT (CONGRESSNO2, EVENTNO)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: VISITOR                                               */
/*==============================================================*/
create table VISITOR (
   PERSONNO             D_PERSONNO           not null,
   USERNAME             D_USERNAME           not null,
   PASSWORD             D_PASSWORD           not null,
   constraint PK_VISITOR primary key (PERSONNO),
   constraint FK_VISITOR_INHERITAN_PERSON foreign key (PERSONNO)
      references PERSON (PERSONNO)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: VISITOROFCONGRESS                                     */
/*==============================================================*/
create table VISITOROFCONGRESS (
   PERSONNO             D_PERSONNO           not null,
   CONGRESSNO2          D_CONGRESSNO         not null,
   HASPAID              D_BOOLEAN            not null,
   constraint PK_VISITOROFCONGRESS primary key (PERSONNO, CONGRESSNO2),
   constraint FK_VISITORO_RT_VISITO_VISITOR foreign key (PERSONNO)
      references VISITOR (PERSONNO)
         on update cascade,
   constraint FK_VISITORO_RT_CONGRE_CONGRESS foreign key (CONGRESSNO2)
      references CONGRESS (CONGRESSNO2)
         on update cascade
)
go

/*==============================================================*/
/* Table: EVENTOFVISITOROFCONGRESS                              */
/*==============================================================*/
create table EVENTOFVISITOROFCONGRESS (
   PERSONNO             D_PERSONNO           not null,
   CONGRESSNO2          D_CONGRESSNO         not null,
   EVE_CONGRESSNO2      D_CONGRESSNO         not null,
   EVENTNO              D_EVENTNO            not null,
   constraint PK_EVENTOFVISITOROFCONGRESS primary key (PERSONNO, CONGRESSNO2, EVE_CONGRESSNO2, EVENTNO),
   constraint FK_EVENTOFV_RT_EVENT__VISITORO foreign key (PERSONNO, CONGRESSNO2)
      references VISITOROFCONGRESS (PERSONNO, CONGRESSNO2),
   constraint FK_EVENTOFV_RT_EVENT__EVENT foreign key (EVE_CONGRESSNO2, EVENTNO)
      references EVENT (CONGRESSNO2, EVENTNO)
         on update cascade
)
go

/*==============================================================*/
/* Table: GENERALMANAGER                                        */
/*==============================================================*/
create table GENERALMANAGER (
   PERSONNO             D_PERSONNO           not null,
   USERNAME             D_USERNAME           not null,
   PASSWORD             D_PASSWORD           not null,
   constraint PK_GENERALMANAGER primary key (PERSONNO),
   constraint FK_GENERALM_INHERITAN_PERSON foreign key (PERSONNO)
      references PERSON (PERSONNO)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: PERSONTYPE                                            */
/*==============================================================*/
create table PERSONTYPE (
   TYPENAME             D_TYPE               not null,
   constraint PK_PERSONTYPE primary key nonclustered (TYPENAME)
)
go

/*==============================================================*/
/* Table: PERSONTYPEOFPERSON                                    */
/*==============================================================*/
create table PERSONTYPEOFPERSON (
   PERSONNO             D_PERSONNO           not null,
   TYPENAME             D_TYPE               not null,
   constraint PK_PERSONTYPEOFPERSON primary key (PERSONNO, TYPENAME),
   constraint FK_PERSONTY_RT_PERSON_PERSON foreign key (PERSONNO)
      references PERSON (PERSONNO)
         on update cascade on delete cascade,
   constraint FK_PERSONTY_RT_PERSON_PERSONTY foreign key (TYPENAME)
      references PERSONTYPE (TYPENAME)
         on update cascade
)
go

/*==============================================================*/
/* Table: REVIEWBOARD                                           */
/*==============================================================*/
create table REVIEWBOARD (
   PERSONNO             D_PERSONNO           not null,
   constraint PK_REVIEWBOARD primary key (PERSONNO),
   constraint FK_REVIEWBO_INHERITAN_PERSON foreign key (PERSONNO)
      references PERSON (PERSONNO)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: REVIEWBOARDOFCONGRESS                                 */
/*==============================================================*/
create table REVIEWBOARDOFCONGRESS (
   PERSONNO             D_PERSONNO           not null,
   CONGRESSNO2          D_CONGRESSNO         not null,
   constraint PK_REVIEWBOARDOFCONGRESS primary key (PERSONNO, CONGRESSNO2),
   constraint FK_REVIEWBO_RT_REVIEW_REVIEWBO foreign key (PERSONNO)
      references REVIEWBOARD (PERSONNO)
         on update cascade,
   constraint FK_REVIEWBO_RT_REVIEW_CONGRESS foreign key (CONGRESSNO2)
      references CONGRESS (CONGRESSNO2)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: SPEAKER                                               */
/*==============================================================*/
create table SPEAKER (
   PERSONNO             D_PERSONNO           not null,
   DESCRIPTION          D_DESCRIPTION        not null,
   PICTUREPATH          D_FILE               not null,
   constraint PK_SPEAKER primary key (PERSONNO),
   constraint FK_SPEAKER_INHERITAN_PERSON foreign key (PERSONNO)
      references PERSON (PERSONNO)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: SPEAKEROFCONGRESS                                     */
/*==============================================================*/
create table SPEAKEROFCONGRESS (
   PERSONNO             D_PERSONNO           not null,
   CONGRESSNO2          D_CONGRESSNO         not null,
   AGREEMENT            D_DESCRIPTION        not null,
   constraint PK_SPEAKEROFCONGRESS primary key (PERSONNO, CONGRESSNO2),
   constraint FK2_SPEAKERO_RT_SPEAKE_SPEAKER foreign key (PERSONNO)
      references SPEAKER (PERSONNO)
         on update cascade,
   constraint FK_SPEAKERO_RT_CONGRE_CONGRESS foreign key (CONGRESSNO2)
      references CONGRESS (CONGRESSNO2)
         on update cascade on delete cascade
)
go

/*==============================================================*/
/* Table: SPEAKEROFEVENT                                        */
/*==============================================================*/
create table SPEAKEROFEVENT (
   PERSONNO             D_PERSONNO           not null,
   CONGRESSNO2          D_CONGRESSNO         not null,
   EVENTNO              D_EVENTNO            not null,
   constraint PK_SPEAKEROFEVENT primary key (CONGRESSNO2, PERSONNO, EVENTNO),
   constraint FK_SPEAKERO_RT_SPEAKE_SPEAKER foreign key (PERSONNO)
      references SPEAKER (PERSONNO)
         on update cascade,
   constraint FK_SPEAKERO_RT_SPEAKE_EVENT foreign key (CONGRESSNO2, EVENTNO)
      references EVENT (CONGRESSNO2, EVENTNO)
         on update cascade on delete cascade
)
go

