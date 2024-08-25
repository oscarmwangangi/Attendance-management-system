-- Create an event to update the attache status
CREATE EVENT IF NOT EXISTS update_attache_status
ON SCHEDULE EVERY 1 DAY
DO
UPDATE attache
SET status = CASE
    WHEN date_leaving <= CURRENT_DATE THEN 'finished'
    ELSE 'ongoing'
END;
