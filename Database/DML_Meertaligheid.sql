--ALTER TABLE ScreenObject
--ALTER COLUMN VALUE varchar(75) NOT NULL

DELETE FROM ScreenObject
DELETE FROM Language

INSERT INTO [LANGUAGE] VALUES ('NL'),
							  ('EN'),
							  ('DE')

								  /* Nederlands */
INSERT INTO [SCREENOBJECT] VALUES ('NL', 'welcomeText', 'Welkom', 'Text'),
								  ('NL', 'planCongress', 'Plan je Congres', 'Button'),
								  ('NL', 'backToHome', 'Terug naar homepagina', 'Button'),
								  ('NL', 'login', 'Login', 'Button'),
								  ('NL', 'logout', 'Uitloggen', 'Button'),
								  ('NL', 'moreInfo', 'Meer Info', 'Button'),
								  ('NL', 'Registreren', 'Registreren', 'Button'),
								  ('NL', 'submitLogin', 'Inloggen', 'Button'),
								  ('NL', 'NextDayButton', 'Volgende dag', 'Button'),
								  ('NL', 'previousDayButton', 'Vorige dag', 'Button'),
								  ('NL', 'signUpForCongressButton', 'Inschrijven', 'Button'),

								  ('NL', 'congressInfo', 'Congres Informatie', 'Text'),
								  ('NL', 'congressSubjects', 'Onderwerpen', 'Text'),
								  ('NL', 'loginTitle', 'Inloggen', 'Text'),
								  
								  ('NL', 'emailLabel', 'E-mailadres', 'Label'),
								  ('NL', 'passwordLabel', 'Wachtwoord', 'Label'),
								  ('NL', 'ConName', 'Naam', 'Label'),
								  ('NL', 'ConDescription', 'Omschrijving', 'Label'),
								  ('NL', 'ConStart', 'Begindatum', 'Label'),
								  ('NL', 'ConEnd', 'Eindatum', 'Label'),
								  ('NL', 'ConLocation', 'Locatie', 'Label'),
								  ('NL', 'ConCity', 'Plaats', 'Label'),
								  ('NL', 'ConPrice', 'Prijs', 'Label'),
								  ('NL', 'eventDescription', 'Over evenement', 'Label'),
								  ('NL', 'subjects', 'Onderwerp(en)', 'Label'),
								  ('NL', 'speakers', 'Spreker(s)', 'Label'),

								  ('NL', 'registerTitle', 'Registreren', 'Text'),
								  ('NL', 'firstNameRegister', 'Voornaam', 'Label'),
								  ('NL', 'lastNameRegister', 'Achternaam', 'Label'),
								  ('NL', 'phoneNumberRegister', 'Telefoonnummer', 'Label'),
								  ('NL', 'sendRegister', 'Registreren', 'Label'),

								  ('NL', 'noEventsOnDay', 'Er zijn geen evenementen ingepland voor deze dag', 'Text'),
								  ('NL', 'alreadyRegisteredCongress', 'U kunt zich niet inschrijven. U bent al ingeschreven voor dit congres.', 'Text'),
								  ('NL', 'referralHome', 'U wordt doorverwezen naar de homepagina.', 'Text'),
								  
								  ('NL', 'confirmRegistrationCongress', 'Bevestiging inschrijven voor congres:', 'Text'),
								  ('NL', 'chosenEventsText', 'U heeft gekozen voor de volgende evenementen:', 'Text'),
								  ('NL', 'confirmButton', 'Bevestigen', 'Button'),
								  ('NL', 'cancelButton', 'Annuleren', 'Button'),

								  ('NL', 'loginFailed', 'Gebruikersnaam en/of wachtwoord zijn onjuist', 'Text'),
								  /* Engels */
								  ('EN', 'welcomeText', 'Welcome', 'Text'),
								  ('EN', 'planCongress', 'Plan Congress', 'Button'),
								  ('EN', 'backToHome', 'Back to homepage', 'Button'),
								  ('EN', 'login', 'Sign up', 'Button'),
								  ('EN', 'logout', 'Logout', 'Button'),
								  ('EN', 'moreInfo', 'More Info', 'Button'),
								  ('EN', 'Registreren', 'Register', 'Button'),
								  ('EN', 'submitLogin', 'Sign up', 'Button'),
								  ('EN', 'nextDayButton', 'Next day', 'Button'),
								  ('EN', 'previousDayButton', 'Previous day', 'Button'),
								  ('EN', 'signUpForCongressButton', 'Submit', 'Button'),
									
								  ('EN', 'congressInfo', 'Congress Information', 'Text'),
								  ('EN', 'congressSubjects', 'Subjects', 'Text'),
								  ('EN', 'loginTitle', 'Sign up', 'Text'),
								
								  ('EN', 'emailLabel', 'E-mailadress', 'Label'),
								  ('EN', 'passwordLabel', 'Password', 'Label'),
								  ('EN', 'ConName', 'Name', 'Label'),
								  ('EN', 'ConDescription', 'Description', 'Label'),
								  ('EN', 'ConStart', 'Startdate', 'Label'),
								  ('EN', 'ConEnd', 'Enddate', 'Label'),
								  ('EN', 'ConLocation', 'Location', 'Label'),
								  ('EN', 'ConCity', 'City', 'Label'),
								  ('EN', 'ConPrice', 'Price', 'Label'),
								  ('EN', 'eventDescription', 'About event', 'Label'),
								  ('EN', 'subjects', 'Subject(s)', 'Label'),
								  ('EN', 'speakers', 'Speaker(s)', 'Label'),

								  ('EN', 'registerTitle', 'Register', 'Text'),
								  ('EN', 'firstNameRegister', 'First Name', 'Label'),
								  ('EN', 'lastNameRegister', 'Surname', 'Label'),
								  ('EN', 'phoneNumberRegister', 'Phone number', 'Label'),
								  ('EN', 'sendRegister', 'Register', 'Label'),

								  ('EN', 'noEventsOnDay', 'There are no events scheduled for this day', 'Text'),
								  ('EN', 'alreadyRegisteredCongress', 'You can not enroll. You are already registered for this congress.', 'Text'),
								  ('EN', 'referralHome', 'You will be redirected to the homepage.', 'Text'),
								  
								  ('EN', 'confirmRegistrationCongress', 'Confirmation registration for congress:', 'Text'),
								  ('EN', 'chosenEventsText', 'You have chosen for the following events:', 'Text'),
								  ('EN', 'confirmButton', 'Confirm', 'Button'),
								  ('EN', 'cancelButton', 'Cancel', 'Button'),
								  
								  ('EN', 'loginFailed', 'Username and/or password are incorrect', 'Text'),
								  /* Duits */
								  ('DE', 'welcomeText', 'Willkommen', 'Text'),
								  ('DE', 'planCongress', 'Plan Konferenz', 'Button'),
								  ('DE', 'backToHome', 'Zurück zur Startseite', 'Button'),
								  ('DE', 'login', 'Login', 'Button'),
								  ('DE', 'logout', 'Ausloggen', 'Button'),
								  ('DE', 'moreInfo', 'Weitere Infos', 'Button'),
								  ('DE', 'Registreren', 'Registrieren', 'Button'),
								  ('DE', 'submitLogin', 'Login', 'Button'),
								  ('DE', 'nextDayButton', 'Nächsten Tag', 'Button'),
								  ('DE', 'previousDayButton', 'Vortag', 'Button'),
								  ('DE', 'signUpForCongressButton', 'Registrieren', 'Button'),
									
								  ('DE', 'congressInfo', 'Konferenz Informationen', 'Text'),
								  ('DE', 'congressSubjects', 'Themas', 'Text'),
								  ('DE', 'loginTitle', 'Login', 'Text'),
								
								  ('DE', 'emailLabel', 'E-Mail', 'Label'),
								  ('DE', 'passwordLabel', 'Passwort', 'Label'),
								  ('DE', 'ConName', 'Name', 'Label'),
								  ('DE', 'ConDescription', 'Umschreibung', 'Label'),
								  ('DE', 'ConStart', 'Startdatum', 'Label'),
								  ('DE', 'ConEnd', 'Enddatum', 'Label'),
								  ('DE', 'ConLocation', 'Lokation', 'Label'),
								  ('DE', 'ConCity', 'Ort', 'Label'),
								  ('DE', 'ConPrice', 'Preis', 'Label'),
								  ('DE', 'eventDescription', 'Über Event', 'Label'),
								  ('DE', 'subjects', 'Thema(s)', 'Label'),
								  ('DE', 'speakers', 'Sprecher', 'Label'),
			  
								  ('DE', 'registerTitle', 'Registrieren', 'Text'),
								  ('DE', 'firstNameRegister', 'Vorname', 'Label'),
								  ('DE', 'lastNameRegister', 'Nachname', 'Label'),
								  ('DE', 'phoneNumberRegister', 'Telefonnummer', 'Label'),
								  ('DE', 'sendRegister', 'Registrieren', 'Label'),

								  ('DE', 'noEventsOnDay', 'Es gibt keine Events für diesen Tag', 'Text'),
								  ('DE', 'alreadyRegisteredCongress', 'Sie können nicht anmelden. Sie sind bereits für die Konferenz registriert.', 'Text'),
								  ('DE', 'referralHome', 'Sie werden auf die Homepage weitergeleitet', 'Text'),

								  ('DE', 'confirmRegistrationCongress', 'Anmeldebestätigung für Konferenz:', 'Text'),
								  ('DE', 'chosenEventsText', 'Sie haben die folgenden Events gewählt:', 'Text'),
								  ('DE', 'confirmButton', 'Bestätigen', 'Button'),
								  ('DE', 'cancelButton', 'Stornieren', 'Button'),

								  ('DE', 'loginFailed', 'Benutzername und/oder Passwort sind falsch', 'Text')