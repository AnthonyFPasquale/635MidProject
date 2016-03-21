drop table if exists login;
create table login
(
   loginId INT(11) primary key auto_increment,
   username VARCHAR(32),
   password VARCHAR(255),
   privilegeLevel INT(11) default 1,
   displayName VARCHAR(128)
);