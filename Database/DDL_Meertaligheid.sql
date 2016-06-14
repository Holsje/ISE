/*==============================================================*/
/* DBMS name:      Microsoft SQL Server 2008                    */
/* Created on:     2-6-2016 11:49:54                            */
/*==============================================================*/

USE master
GO

DROP DATABASE MeertaligheidDB
GO

CREATE DATABASE MeertaligheidDB
GO

USE MeertaligheidDB
GO

if exists (select 1
   from sys.sysreferences r join sys.sysobjects o on (o.id = r.constid and o.type = 'F')
   where r.fkeyid = object_id('SCREENOBJECT') and o.name = 'FK_SCREENOB_RT_SCREEN_LANGUAGE')
alter table SCREENOBJECT
   drop constraint FK_SCREENOB_RT_SCREEN_LANGUAGE
go

if exists (select 1
            from  sysobjects
           where  id = object_id('LANGUAGE')
            and   type = 'U')
   drop table LANGUAGE
go

if exists (select 1
            from  sysindexes
           where  id    = object_id('SCREENOBJECT')
            and   name  = 'RT_SCREENOBJECT_IN_LANGUAGE_FK'
            and   indid > 0
            and   indid < 255)
   drop index SCREENOBJECT.RT_SCREENOBJECT_IN_LANGUAGE_FK
go

if exists (select 1
            from  sysobjects
           where  id = object_id('SCREENOBJECT')
            and   type = 'U')
   drop table SCREENOBJECT
go

if exists(select 1 from systypes where name='D_LANGUAGE')
   drop type D_LANGUAGE
go

if exists(select 1 from systypes where name='D_NAME')
   drop type D_NAME
go

if exists(select 1 from systypes where name='D_TYPE')
   drop type D_TYPE
go

if exists(select 1 from systypes where name='D_VALUE')
   drop type D_VALUE
go

/*==============================================================*/
/* Domain: D_LANGUAGE                                           */
/*==============================================================*/
create type D_LANGUAGE
   from char(2)
go

/*==============================================================*/
/* Domain: D_NAME                                               */
/*==============================================================*/
create type D_NAME
   from varchar(100)
go

/*==============================================================*/
/* Domain: D_TYPE                                               */
/*==============================================================*/
create type D_TYPE
   from varchar(25)
go

/*==============================================================*/
/* Domain: D_VALUE                                              */
/*==============================================================*/
create type D_VALUE
   from varchar(75)
go

/*==============================================================*/
/* Table: LANGUAGE                                              */
/*==============================================================*/
create table LANGUAGE (
   LANGUAGE             D_LANGUAGE           not null,
   constraint PK_LANGUAGE primary key nonclustered (LANGUAGE)
)
go

/*==============================================================*/
/* Table: SCREENOBJECT                                          */
/*==============================================================*/
create table SCREENOBJECT (
   LANGUAGE             D_LANGUAGE           not null,
   NAME                 D_NAME               not null,
   VALUE                D_VALUE              not null,
   TYPE                 D_TYPE               not null,
   constraint PK_SCREENOBJECT primary key nonclustered (LANGUAGE, NAME)
)
go

/*==============================================================*/
/* Index: RT_SCREENOBJECT_IN_LANGUAGE_FK                        */
/*==============================================================*/
create index RT_SCREENOBJECT_IN_LANGUAGE_FK on SCREENOBJECT (
LANGUAGE ASC
)
go

alter table SCREENOBJECT
   add constraint FK_SCREENOB_RT_SCREEN_LANGUAGE foreign key (LANGUAGE)
      references LANGUAGE (LANGUAGE)
go

