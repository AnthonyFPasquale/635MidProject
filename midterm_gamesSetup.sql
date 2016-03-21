drop table if exists games;
create table games
(
   gameId INT(11) primary key auto_increment,
   gamename VARCHAR(32),
   releasedate DATE,
   status VARCHAR(16) default 'NORMAL'
);