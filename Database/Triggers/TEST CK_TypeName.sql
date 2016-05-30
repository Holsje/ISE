-- TEST CK_TYPENAME

-- Goede Insert/Update
BEGIN TRAN
	UPDATE PersonType
	SET TypeName = 'Algemene beheerder' WHERE TypeName = 'Algemene beheerder'
ROLLBACK TRAN


-- Foute Insert/Update
BEGIN TRAN
	UPDATE PersonType
	SET TypeName = 'Algehele beheerder' WHERE TypeName = 'Algemene beheerder'
ROLLBACK TRAN