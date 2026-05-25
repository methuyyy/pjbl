USE pawerti;

ALTER TABLE message_replies ADD COLUMN is_read TINYINT(1) DEFAULT 0;
