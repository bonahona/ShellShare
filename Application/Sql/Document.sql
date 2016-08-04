create table Document(
  Id int not null primary key auto_increment,
  Name varchar(128),
  ShortDescription varchar(4096),
  OwnerId int not null,
  DirectoryId int not null,
  foreign key(OwnerId) references LocalUser(ShellUserId) on delete CASCADE on update cascade,
  foreign key(DirectoryId) references VirtualDirectory(Id)  on delete CASCADE on update cascade
);