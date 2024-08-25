DELIMITER //

CREATE TRIGGER before_attache_insert
BEFORE INSERT ON attache
FOR EACH ROW
BEGIN
    SET NEW.status = CASE
        WHEN NEW.date_leaving <= CURRENT_DATE THEN 'finished'
        ELSE 'ongoing'
    END;
END//

CREATE TRIGGER before_attache_update
BEFORE UPDATE ON attache
FOR EACH ROW
BEGIN
    SET NEW.status = CASE
        WHEN NEW.date_leaving <= CURRENT_DATE THEN 'finished'
        ELSE 'ongoing'
    END;
END//

DELIMITER ;
