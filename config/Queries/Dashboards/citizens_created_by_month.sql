SELECT
    DATE_FORMAT(created, '%m-%Y') AS period,
    COUNT(*) as count
FROM users
WHERE
    role = 'citizen' AND
	created IS NOT NULL
GROUP BY
	YEAR(created), MONTH(created)
ORDER BY
	created DESC
LIMIT 10
