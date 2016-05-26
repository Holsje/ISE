--TEST CK_MAILADDRESS

-- Goede insert/update
BEGIN TRAN
	UPDATE Person
	SET MailAddress = 'info@mail.com'
	WHERE PersonNo = 1
ROLLBACK TRAN

-- Foute insert/update
BEGIN TRAN
	UPDATE Person
	SET MailAddress = 'info.@mail.com'
	WHERE PersonNo = 1
ROLLBACK TRAN

-- Foute insert/update
BEGIN TRAN
	UPDATE Person
	SET MailAddress = 'info@mail..com'
	WHERE PersonNo = 1
ROLLBACK TRAN

-- Foute insert/update
BEGIN TRAN
	UPDATE Person
	SET MailAddress = 'info@mail,com'
	WHERE PersonNo = 1
ROLLBACK TRAN

-- Foute insert/update
BEGIN TRAN
	UPDATE Person
	SET MailAddress = 'in{fo@mail.com'
	WHERE PersonNo = 1
ROLLBACK TRAN

-- Foute insert/update
BEGIN TRAN
	UPDATE Person
	SET MailAddress = 'in}fo@mail.com'
	WHERE PersonNo = 1
ROLLBACK TRAN