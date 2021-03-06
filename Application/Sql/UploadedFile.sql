create table UploadedFile(
  Id int not null primary key auto_increment,
  LocalFilePath varchar(1024),
  CreateDate varchar(128),
  MimeType varchar(512),
  FileExtension varchar(32),
  DocumentId int not null,
  UploadedById int not null,
  foreign key(DocumentId) references Document(Id) on delete CASCADE on update cascade,
  foreign key(UploadedById) references LocalUser(ShellUserId) on delete CASCADE on update cascade
);