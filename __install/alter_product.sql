ALTER TABLE products ADD type int(11)

update products set type = 2 where visibility < 3