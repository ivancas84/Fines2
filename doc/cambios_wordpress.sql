update wpwt_psmsc_threads set seen = null where seen = '0000-00-00 00:00:00';
update wpwt_psmsc_threads set customer = null where customer  = 0;
update wpwt_psmsc_tickets set date_closed = null where date_closed = '0000-00-00 00:00:00';
update wpwt_psmsc_tickets set last_reply_on = null where last_reply_on = '0000-00-00 00:00:00';
ALTER TABLE wpwt_psmsc_statuses MODIFY COLUMN id INT(11) auto_increment NOT NULL;
ALTER TABLE wpwt_psmsc_priorities MODIFY COLUMN id INT(11) auto_increment NOT NULL;
ALTER TABLE wpwt_psmsc_categories MODIFY COLUMN id INT(11) auto_increment NOT NULL;

ALTER TABLE wpwt_psmsc_attachments MODIFY COLUMN date_created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL;
ALTER TABLE wpwt_psmsc_attachments MODIFY COLUMN is_active int(1) DEFAULT 1 NOT NULL;

ALTER TABLE wpwt_psmsc_agents ADD CONSTRAINT agents_customers_FK FOREIGN KEY (customer) REFERENCES wpwt_psmsc_customers(id);

ALTER TABLE wpwt_psmsc_attachments ADD CONSTRAINT attachments_tickets_FK FOREIGN KEY (ticket_id) REFERENCES wpwt_psmsc_tickets(id) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE wpwt_psmsc_logs ADD CONSTRAINT logs_customers_FK FOREIGN KEY (modified_by) REFERENCES wpwt_psmsc_customers(id);

ALTER TABLE wpwt_psmsc_threads ADD CONSTRAINT threads_tickets_FK FOREIGN KEY (ticket) REFERENCES wpwt_psmsc_tickets(id);
ALTER TABLE wpwt_psmsc_threads ADD CONSTRAINT threads_customers_FK FOREIGN KEY (customer) REFERENCES wpwt_psmsc_customers(id);

ALTER TABLE wpwt_psmsc_tickets ADD CONSTRAINT tickets_customers_FK FOREIGN KEY (customer) REFERENCES wpwt_psmsc_customers(id);

ALTER TABLE wpwt_psmsc_tickets ADD CONSTRAINT tickets_statuses_FK FOREIGN KEY (status) REFERENCES wpwt_psmsc_statuses(id);

ALTER TABLE wpwt_psmsc_tickets ADD CONSTRAINT tickets_priorities_FK FOREIGN KEY (priority) REFERENCES wpwt_psmsc_priorities(id);

ALTER TABLE wpwt_psmsc_tickets ADD CONSTRAINT tickets_categories_FK FOREIGN KEY (category) REFERENCES wpwt_psmsc_categories(id);




