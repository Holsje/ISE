--BR2. Prijs is mandatory als een event van het type workshop is.
ALTER TABLE Event
ADD CONSTRAINT CHK_PriceEventType CHECK((Price IS NOT NULL AND [Type] = 'Workshop') OR (Price IS NULL AND [Type] = 'Lezing'))

--Testdata
--Fout, omdat het type niet 'Lezing' is.
INSERT INTO [Event] VALUES(1, 10, 'TestEvent', 'LEEzing', 50, NULL, NULL, NULL);

--Fout, omdat het type 'Lezing' is met een price 50 is
INSERT INTO [Event] VALUES(1, 10, 'TestEvent', 'Lezing', 50, 50, NULL, NULL);

--Fout, omdat het type 'Workshop' is zonder prijs.
INSERT INTO [Event] VALUES(1, 10, 'TestEvent', 'Workshop', 50, NULL, NULL, NULL);

--Goed, omdat het type 'Workshop' is met een prijs.
BEGIN TRAN
INSERT INTO [Event] VALUES(1, 10, 'TestEvent', 'Workshop', 50, 50, NULL, NULL)
ROLLBACK TRAN

--Goed, omdat het type 'Lezing' is zonder een prijs.
BEGIN TRAN
INSERT INTO [Event] VALUES(1, 10, 'TestEvent', 'Lezing', 50, NULL, NULL, NULL)
ROLLBACK TRAN