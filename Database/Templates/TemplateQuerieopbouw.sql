SELECT *
FROM Table T1 
	INNER JOIN Table2 T2
		ON T1.a = T2.a 
	INNER JOIN TableNaam TN
		ON TN.a = TN.a
WHERE EXISTS	(	
					SELECT 1 
					FROM Table T
					WHERE 1 = 1
				)
