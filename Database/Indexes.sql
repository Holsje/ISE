CREATE NONCLUSTERED INDEX NCL_Event_EventNameTypePrice ON Event(EName, Type, Price)
GO

CREATE NONCLUSTERED INDEX NCL_Person_MailAddress ON Person(MailAddress)
GO

CREATE NONCLUSTERED INDEX NCL_Congres_NameStartEndDatePublic ON Congress(CName, StartDate, EndDate, [Public])
GO