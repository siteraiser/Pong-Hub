CREATE TABLE products (
	id smallint(6) unsigned NOT NULL auto_increment,
	pid smallint(6) unsigned NULL,
	user smallint(6) unsigned NULL,
	label varchar(1500) NULL,
	image blob NULL,
	inventory int(25),
	lastupdate timestamp DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE i_addresses (
	id smallint(6) unsigned NOT NULL auto_increment,
	iaddr_id varchar(100) NULL,
	user smallint(6) unsigned NULL,
	product_id varchar(100) NULL,
	iaddr varchar(1500) NULL,
	ask_amount int(25) NULL,
	comment varchar(500) NULL,
	status tinyint(1) NULL,
	ia_inventory int(25) NULL,	
	lastupdate timestamp DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE orders (
	id smallint(6) unsigned NOT NULL auto_increment,
	uuid varchar(128) NULL,
	lastupdate timestamp DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
) ENGINE=InnoDB;


CREATE TABLE users (
	userid smallint(6) unsigned NOT NULL auto_increment,
	username varchar(128) NULL,
	wallet varchar(128) NULL,
	uuid varchar(128) NULL,
	status tinyint(1) NULL,
	lastupdate timestamp DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (userid)
) ENGINE=InnoDB;