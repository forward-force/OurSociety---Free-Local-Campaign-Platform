SELECT
    DATE_FORMAT(created, '%Y') AS period,
    COUNT(*) as count
FROM users
WHERE
	created IS NOT NULL
GROUP BY
	YEAR(created)
ORDER BY
	created DESC
LIMIT 10
