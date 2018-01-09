create table VirtualDirectory (
  Id int not null primary key auto_increment,
  Name varchar(128),
  OwnerId int not null,
  ParentDirectoryId int,
  AccessRightsMask int,
  NavigationName varchar(512),
  foreign key(OwnerId) references LocalUser(ShellUserId) on delete CASCADE on update cascade,
  foreign key(ParentDirectoryId) references VirtualDirectory(Id) on delete CASCADE on update cascade
)