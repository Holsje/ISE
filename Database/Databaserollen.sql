USE CongressDB
GO

CREATE LOGIN [manageUser] WITH PASSWORD = 'wachtwoord',
               DEFAULT_DATABASE = [CongressDB],
               CHECK_EXPIRATION=OFF,
               CHECK_POLICY=OFF
GO

CREATE LOGIN [websiteUser] WITH PASSWORD = 'wachtwoord',
               DEFAULT_DATABASE = [CongressDB],
               CHECK_EXPIRATION=OFF,
               CHECK_POLICY=OFF
GO

CREATE USER [Website] FOR LOGIN [websiteUser] WITH DEFAULT_SCHEMA=[dbo]
GO

CREATE USER [ManagerApplication] FOR LOGIN [manageUser] WITH DEFAULT_SCHEMA=[dbo]
GO

CREATE ROLE [Manager]
GO

CREATE ROLE [Web]
GO

ALTER ROLE [Manager] ADD MEMBER [ManagerApplication]
GO

ALTER ROLE [Web] ADD MEMBER [Website]
GO


/* Table Congress */

	/* Manager */

	GRANT ALL ON Congress TO [Manager]
	GO

	REVOKE REFERENCES ON Congress TO [Manager]
	GO

	GRANT ALTER ON Congress TO [Manager]
	GO

	/* Website */

	GRANT SELECT ON Congress TO [Web]
	GO

	REVOKE UPDATE ON Congress TO [Web]
	GO

	REVOKE INSERT ON Congress TO [Web]
	GO

	REVOKE DELETE ON Congress TO [Web]
	GO

	REVOKE REFERENCES ON Congress TO [Web]
	GO

	REVOKE ALTER ON Congress TO [Web]
	GO	 

/* Table Event */

	/* Manager */

	GRANT ALL ON Event TO [Manager]
	GO

	REVOKE REFERENCES ON Event TO [Manager]
	GO

	REVOKE ALTER ON Congress TO [Manager]
	GO

	/* Website */

	GRANT SELECT ON Event TO [Web]
	GO

	REVOKE UPDATE ON Event TO [Web]
	GO

	REVOKE INSERT ON Event TO [Web]
	GO

	REVOKE DELETE ON Event TO [Web]
	GO

	REVOKE REFERENCES ON Event TO [Web]
	GO

	REVOKE ALTER ON Event TO [Web]
	GO	 


/* Table Track */

	/* Manager */

	GRANT ALL ON Track TO [Manager]
	GO

	REVOKE REFERENCES ON Track TO [Manager]
	GO

	REVOKE ALTER ON Track TO [Manager]
	GO

	/* Website */

	GRANT SELECT ON Track TO [Web]
	GO

	REVOKE UPDATE ON Track TO [Web]
	GO

	REVOKE INSERT ON Track TO [Web]
	GO

	REVOKE DELETE ON Track TO [Web]
	GO

	REVOKE REFERENCES ON Track TO [Web]
	GO

	REVOKE ALTER ON Track TO [Web]
	GO	 

/* Table EventInTrack */

	/* Manager */

	GRANT ALL ON EventInTrack TO [Manager]
	GO

	REVOKE REFERENCES ON EventInTrack TO [Manager]
	GO

	REVOKE ALTER ON EventInTrack TO [Manager]
	GO

	/* Website */

	GRANT SELECT ON EventInTrack TO [Web]
	GO

	REVOKE UPDATE ON EventInTrack TO [Web]
	GO

	REVOKE INSERT ON EventInTrack TO [Web]
	GO

	REVOKE DELETE ON EventInTrack TO [Web]
	GO

	REVOKE REFERENCES ON EventInTrack TO [Web]
	GO

	REVOKE ALTER ON EventInTrack TO [Web]
	GO	 


/* Table EventInRoom */

	/* Manager */

	GRANT ALL ON EventInRoom TO [Manager]
	GO

	REVOKE REFERENCES ON EventInRoom TO [Manager]
	GO

	REVOKE ALTER ON EventInRoom TO [Manager]
	GO

	/* Website */

	GRANT SELECT ON EventInRoom TO [Web]
	GO

	REVOKE UPDATE ON EventInRoom TO [Web]
	GO

	REVOKE INSERT ON EventInRoom TO [Web]
	GO

	REVOKE DELETE ON EventInRoom TO [Web]
	GO

	REVOKE REFERENCES ON EventInRoom TO [Web]
	GO

	REVOKE ALTER ON EventInRoom TO [Web]
	GO	 


/* Table Person */

	/* Manager */

	GRANT SELECT ON Person TO [Manager]
	GO

	GRANT UPDATE ON Person TO [Manager]
	GO
	
	REVOKE INSERT ON Person TO [Manager]
	GO

	REVOKE DELETE ON Person TO [Manager]
	GO

	REVOKE REFERENCES ON Person TO [Manager]
	GO

	REVOKE ALTER ON Person TO [Manager]
	GO

	/* Website */

	GRANT SELECT ON Person TO [Web]
	GO

	REVOKE UPDATE ON Person TO [Web]
	GO

	REVOKE INSERT ON Person TO [Web]
	GO

	REVOKE DELETE ON Person TO [Web]
	GO

	REVOKE REFERENCES ON Person TO [Web]
	GO

	REVOKE ALTER ON Person TO [Web]
	GO	 

/* Table PersonType */
 
 	/* Manager */

	GRANT SELECT ON PersonType TO [Manager]
	GO

	REVOKE UPDATE ON PersonType TO [Manager]
	GO
	
	REVOKE INSERT ON PersonType TO [Manager]
	GO

	REVOKE DELETE ON PersonType TO [Manager]
	GO

	REVOKE REFERENCES ON PersonType TO [Manager]
	GO

	REVOKE ALTER ON PersonType TO [Manager]
	GO

	/* Website */

	REVOKE SELECT ON PersonType TO [Web]
	GO

	REVOKE UPDATE ON PersonType TO [Web]
	GO

	REVOKE INSERT ON PersonType TO [Web]
	GO

	REVOKE DELETE ON PersonType TO [Web]
	GO

	REVOKE REFERENCES ON PersonType TO [Web]
	GO

	REVOKE ALTER ON PersonType TO [Web]
	GO

/* Table PersonTypeOfPerson */
	
	/* Manager */

	GRANT SELECT ON PersonTypeOfPerson TO [Manager]
	GO

	REVOKE UPDATE ON PersonTypeOfPerson TO [Manager]
	GO
	
	GRANT INSERT ON PersonTypeOfPerson TO [Manager]
	GO

	REVOKE DELETE ON PersonTypeOfPerson TO [Manager]
	GO

	REVOKE REFERENCES ON PersonTypeOfPerson TO [Manager]
	GO

	REVOKE ALTER ON PersonTypeOfPerson TO [Manager]
	GO

	/* Website */

	REVOKE SELECT ON PersonTypeOfPerson TO [Web]
	GO

	REVOKE UPDATE ON PersonTypeOfPerson TO [Web]
	GO

	REVOKE INSERT ON PersonTypeOfPerson TO [Web]
	GO

	REVOKE DELETE ON PersonTypeOfPerson TO [Web]
	GO

	REVOKE REFERENCES ON PersonTypeOfPerson TO [Web]
	GO

	REVOKE ALTER ON PersonTypeOfPerson TO [Web]
	GO

/* Table Visitor */
	
	/* Manager */
	
	GRANT SELECT ON Visitor TO [Web]
	GO

	REVOKE UPDATE ON Visitor TO [Web]
	GO

	REVOKE INSERT ON Visitor TO [Web]
	GO

	REVOKE DELETE ON Visitor TO [Web]
	GO

	REVOKE REFERENCES ON Visitor TO [Web]
	GO

	REVOKE ALTER ON Visitor TO [Web]
	GO

	/* Website */

	GRANT SELECT ON Visitor TO [Web]
	GO

	REVOKE UPDATE ON Visitor TO [Web]
	GO

	GRANT INSERT ON Visitor TO [Web]
	GO

	REVOKE DELETE ON Visitor TO [Web]
	GO

	REVOKE REFERENCES ON Visitor TO [Web]
	GO

	REVOKE ALTER ON Visitor TO [Web]
	GO


/* Table VisitorOfCongress */
	
	/* Manager */

  	GRANT SELECT ON VisitorOfCongress TO [Manager]
	GO

	GRANT UPDATE ON VisitorOfCongress TO [Manager]
	GO
	
	REVOKE INSERT ON VisitorOfCongress TO [Manager]
	GO

	GRANT DELETE ON VisitorOfCongress TO [Manager]
	GO

	REVOKE REFERENCES ON VisitorOfCongress TO [Manager]
	GO

	REVOKE ALTER ON VisitorOfCongress TO [Manager]
	GO

	/* Website */

  	GRANT SELECT ON VisitorOfCongress TO [Web]
	GO

	REVOKE UPDATE ON VisitorOfCongress TO [Web]
	GO
	
	GRANT INSERT ON VisitorOfCongress TO [Web]
	GO

	REVOKE DELETE ON VisitorOfCongress TO [Web]
	GO

	REVOKE REFERENCES ON VisitorOfCongress TO [Web]
	GO

	REVOKE ALTER ON VisitorOfCongress TO [Web]
	GO

/* Table EventOfVisitorOfCongress */

	/* Manager */

	GRANT SELECT ON EventOfVisitorOfCongress TO [Manager]
	GO

	REVOKE UPDATE ON EventOfVisitorOfCongress TO [Manager]
	GO
	
	REVOKE INSERT ON EventOfVisitorOfCongress TO [Manager]
	GO

	REVOKE DELETE ON EventOfVisitorOfCongress TO [Manager]
	GO

	REVOKE REFERENCES ON EventOfVisitorOfCongress TO [Manager]
	GO

	REVOKE ALTER ON EventOfVisitorOfCongress TO [Manager]
	GO

	/* Website */

  	GRANT SELECT ON EventOfVisitorOfCongress TO [Web]
	GO

	REVOKE UPDATE ON EventOfVisitorOfCongress TO [Web]
	GO
	
	GRANT INSERT ON EventOfVisitorOfCongress TO [Web]
	GO

	REVOKE DELETE ON EventOfVisitorOfCongress TO [Web]
	GO

	REVOKE REFERENCES ON EventOfVisitorOfCongress TO [Web]
	GO

	REVOKE ALTER ON EventOfVisitorOfCongress TO [Web]
	GO

/* Table CongressManager */

	/* Manager */

	GRANT ALL ON CongressManager TO [Manager]
	GO

	REVOKE REFERENCES ON CongressManager TO [Manager]
	GO

	REVOKE ALTER ON CongressManager TO [Manager]
	GO

	/* Website */

	REVOKE ALL ON CongressManager TO [Web]
	GO

	REVOKE ALTER ON CongressManager TO [Web]
	GO

/* Table CongressManagerOfCongress */

	/* Manager */

	GRANT SELECT ON CongressManagerOfCongress TO [Manager]
	GO

	REVOKE UPDATE ON CongressManagerOfCongress TO [Manager]
	GO
	
	GRANT INSERT ON CongressManagerOfCongress TO [Manager]
	GO

	REVOKE DELETE ON CongressManagerOfCongress TO [Manager]
	GO

	REVOKE REFERENCES ON CongressManagerOfCongress TO [Manager]
	GO

	REVOKE ALTER ON CongressManagerOfCongress TO [Manager]
	GO

	/* Website */

	REVOKE ALL ON CongressManagerOfCongress TO [Web]
	GO

	REVOKE ALTER ON CongressManagerOfCongress TO [Web]
	GO

/* Table GeneralManager */

	/* Manager */
		
	GRANT ALL ON GeneralManager TO [Manager]
	GO

	REVOKE REFERENCES ON GeneralManager TO [Manager]
	GO

	REVOKE ALTER ON GeneralManager TO [Manager]
	GO

	/* Website */

	REVOKE ALL ON GeneralManager TO [Web]
	GO

	REVOKE ALTER ON GeneralManager TO [Web]
	GO

/* Table Reviewboard */

	/* Manager */
		
	GRANT ALL ON Reviewboard TO [Manager]
	GO

	REVOKE REFERENCES ON Reviewboard TO [Manager]
	GO

	REVOKE ALTER ON Reviewboard TO [Manager]
	GO

	/* Website */

	REVOKE ALL ON Reviewboard TO [Web]
	GO

	REVOKE ALTER ON Reviewboard TO [Web]
	GO

/* Table ReviewboardOfCongress */

	/* Manager */
		
	GRANT SELECT ON ReviewboardOfCongress TO [Manager]
	GO

	REVOKE UPDATE ON ReviewboardOfCongress TO [Manager]
	GO
	
	GRANT INSERT ON ReviewboardOfCongress TO [Manager]
	GO

	GRANT DELETE ON ReviewboardOfCongress TO [Manager]
	GO

	REVOKE REFERENCES ON ReviewboardOfCongress TO [Manager]
	GO

	REVOKE ALTER ON ReviewboardOfCongress TO [Manager]
	GO

	/* Website */

	REVOKE ALL ON ReviewboardOfCongress TO [Web]
	GO

	REVOKE ALTER ON ReviewboardOfCongress TO [Web]
	GO


/* Table Speaker */

	/* Manager */
		
	GRANT SELECT ON Speaker TO [Manager]
	GO

	REVOKE UPDATE ON Speaker TO [Manager]
	GO
	
	REVOKE INSERT ON Speaker TO [Manager]
	GO

	GRANT DELETE ON Speaker TO [Manager]
	GO

	REVOKE REFERENCES ON Speaker TO [Manager]
	GO

	REVOKE ALTER ON Speaker TO [Manager]
	GO

	/* Website */
		
	GRANT SELECT ON Speaker TO [Web]
	GO

	REVOKE UPDATE ON Speaker TO [Web]
	GO
	
	REVOKE INSERT ON Speaker TO [Web]
	GO

	REVOKE DELETE ON Speaker TO [Web]
	GO

	REVOKE REFERENCES ON Speaker TO [Web]
	GO

	REVOKE ALTER ON Speaker TO [Web]
	GO

/* Table SpeakerOfCongress */

	/* Manager */
		
	GRANT SELECT ON SpeakerOfCongress TO [Manager]
	GO

	REVOKE UPDATE ON SpeakerOfCongress TO [Manager]
	GO
	
	GRANT INSERT ON SpeakerOfCongress TO [Manager]
	GO

	GRANT DELETE ON SpeakerOfCongress TO [Manager]
	GO

	REVOKE REFERENCES ON SpeakerOfCongress TO [Manager]
	GO

	REVOKE ALTER ON SpeakerOfCongress TO [Manager]
	GO

	/* Website */

	REVOKE ALL ON SpeakerOfCongress TO [Web]
	GO

	REVOKE ALTER ON SpeakerOfCongress TO [Web]
	GO

/* Table SpeakerOfEvent */

	/* Manager */
		
	GRANT SELECT ON SpeakerOfEvent TO [Manager]
	GO

	REVOKE UPDATE ON SpeakerOfEvent TO [Manager]
	GO
	
	GRANT INSERT ON SpeakerOfEvent TO [Manager]
	GO

	GRANT DELETE ON SpeakerOfEvent TO [Manager]
	GO

	REVOKE REFERENCES ON SpeakerOfEvent TO [Manager]
	GO

	REVOKE ALTER ON SpeakerOfEvent TO [Manager]
	GO

	/* Website */
		
	GRANT SELECT ON SpeakerOfEvent TO [Web]
	GO

	REVOKE UPDATE ON SpeakerOfEvent TO [Web]
	GO
	
	REVOKE INSERT ON SpeakerOfEvent TO [Web]
	GO

	REVOKE DELETE ON SpeakerOfEvent TO [Web]
	GO

	REVOKE REFERENCES ON SpeakerOfEvent TO [Web]
	GO

	REVOKE ALTER ON SpeakerOfEvent TO [Web]
	GO

/* Table Building */

	/* Manager */
		
	GRANT ALL ON Building TO [Manager]
	GO

	REVOKE REFERENCES ON Building TO [Manager]
	GO

	REVOKE ALTER ON Building TO [Manager]
	GO

	/* Website */
		
	GRANT SELECT ON Building TO [Web]
	GO

	REVOKE UPDATE ON Building TO [Web]
	GO
	
	REVOKE INSERT ON Building TO [Web]
	GO

	REVOKE DELETE ON Building TO [Web]
	GO

	REVOKE REFERENCES ON Building TO [Web]
	GO

	REVOKE ALTER ON Building TO [Web]
	GO

/* Table Room */

	/* Manager */
		
	GRANT ALL ON Room TO [Manager]
	GO

	REVOKE REFERENCES ON Room TO [Manager]
	GO

	REVOKE ALTER ON Room TO [Manager]
	GO

	/* Website */
		
	GRANT SELECT ON Room TO [Web]
	GO

	REVOKE UPDATE ON Room TO [Web]
	GO
	
	REVOKE INSERT ON Room TO [Web]
	GO

	REVOKE DELETE ON Room TO [Web]
	GO

	REVOKE REFERENCES ON Room TO [Web]
	GO

	REVOKE ALTER ON Room TO [Web]
	GO

/* Table Location */

	/* Manager */
		
	GRANT SELECT ON Location TO [Manager]
	GO

	REVOKE UPDATE ON Location TO [Manager]
	GO
	
	GRANT INSERT ON Location TO [Manager]
	GO

	GRANT DELETE ON Location TO [Manager]
	GO

	REVOKE REFERENCES ON Location TO [Manager]
	GO

	REVOKE ALTER ON Location TO [Manager]
	GO

	/* Website */
		
	REVOKE ALL ON Location TO [Web]
	GO

	REVOKE ALTER ON Location TO [Web]
	GO

/* Table Subject */

	/* Manager */
		
	GRANT SELECT ON Subject TO [Manager]
	GO

	REVOKE UPDATE ON Subject TO [Manager]
	GO
	
	REVOKE INSERT ON Subject TO [Manager]
	GO

	REVOKE DELETE ON Subject TO [Manager]
	GO

	REVOKE REFERENCES ON Subject TO [Manager]
	GO

	REVOKE ALTER ON Subject TO [Manager]
	GO

	/* Website */
		
	REVOKE ALL ON Subject TO [Web]
	GO

	REVOKE ALTER ON Subject TO [Web]
	GO

/* Table SubjectOfCongress */

	/* Manager */
		
	GRANT SELECT ON SubjectOfCongress TO [Manager]
	GO

	REVOKE UPDATE ON SubjectOfCongress TO [Manager]
	GO
	
	REVOKE INSERT ON SubjectOfCongress TO [Manager]
	GO

	GRANT DELETE ON SubjectOfCongress TO [Manager]
	GO

	REVOKE REFERENCES ON SubjectOfCongress TO [Manager]
	GO

	REVOKE ALTER ON SubjectOfCongress TO [Manager]
	GO

	/* Website */
		
	GRANT SELECT ON SubjectOfCongress TO [Web]
	GO

	REVOKE UPDATE ON SubjectOfCongress TO [Web]
	GO
	
	REVOKE INSERT ON SubjectOfCongress TO [Web]
	GO

	REVOKE DELETE ON SubjectOfCongress TO [Web]
	GO

	REVOKE REFERENCES ON SubjectOfCongress TO [Web]
	GO

	REVOKE ALTER ON SubjectOfCongress TO [Web]
	GO

/* Table SubjectOfEvent */

	/* Manager */
		
	GRANT SELECT ON SubjectOfEvent TO [Manager]
	GO

	REVOKE UPDATE ON SubjectOfEvent TO [Manager]
	GO
	
	REVOKE INSERT ON SubjectOfEvent TO [Manager]
	GO

	GRANT DELETE ON SubjectOfEvent TO [Manager]
	GO

	REVOKE REFERENCES ON SubjectOfEvent TO [Manager]
	GO

	REVOKE ALTER ON SubjectOfEvent TO [Manager]
	GO

	/* Website */
		
	GRANT SELECT ON SubjectOfEvent TO [Web]
	GO

	REVOKE UPDATE ON SubjectOfEvent TO [Web]
	GO
	
	REVOKE INSERT ON SubjectOfEvent TO [Web]
	GO

	REVOKE DELETE ON SubjectOfEvent TO [Web]
	GO

	REVOKE REFERENCES ON SubjectOfEvent TO [Web]
	GO

	REVOKE ALTER ON SubjectOfEvent TO [Web]
	GO




GRANT EXECUTE TO [Manager]
GO

GRANT EXECUTE TO [Web]
GO

