<?php
class DbCreation implements IDatabaseMigration
{
    public function GetUniqueName()
    {
        return 'x76ysjvcn1av60lbqkaa';
    }

    public function GetSortOrder()
    {
        return 0;
    }

    public function Up($migrator)
    {
        $migrator->RunSql('
create table if not exists localuser(
  Id int not null primary key auto_increment,
  ShellUserId int not null,
  key(ShellUserId)
);
        ');

        $migrator->RunSql('
create table if not exists virtualdirectory (
  Id int not null primary key auto_increment,
  Name varchar(128),
  OwnerId int not null,
  ParentDirectoryId int,
  AccessRightsMask int,
  NavigationName varchar(512),
  foreign key(OwnerId) references localuser(ShellUserId) on delete CASCADE on update cascade,
  foreign key(ParentDirectoryId) references virtualdirectory(Id) on delete CASCADE on update cascade
);
        ');

        $migrator->RunSql('
create table if not exists document(
  Id int not null primary key auto_increment,
  Name varchar(128),
  ShortDescription varchar(4096),
  OwnerId int not null,
  DirectoryId int not null,
  NavigationName varchar(512),
  foreign key(OwnerId) references localuser(ShellUserId) on delete CASCADE on update cascade,
  foreign key(DirectoryId) references virtualdirectory(Id)  on delete CASCADE on update cascade
);
        ');

        $migrator->RunSql('
create table if not exists uploadedfile(
  Id int not null primary key auto_increment,
  LocalFilePath varchar(1024),
  CreateDate varchar(128),
  MimeType varchar(512),
  FileExtension varchar(32),
  DocumentId int not null,
  UploadedById int not null,
  foreign key(DocumentId) references document(Id) on delete CASCADE on update cascade,
  foreign key(UploadedById) references localuser(ShellUserId) on delete CASCADE on update cascade
);
        ');
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {

    }
}