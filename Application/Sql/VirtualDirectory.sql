create table VirtualDirectory (
  Id int not null primary key auto_increment,
  Name varchar(128),
  OwnerId int not null,
  ParentDirectory int,
  AccessRightsMask int,
  foreign key(OwnerId) references LocalUser(ShellUserId),
  foreign key(ParentDirectory) references VirtualDirectory(Id)
)