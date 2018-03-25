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
        $migrator->CreateTable('localuser')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('ShellUserId', 'int');

        $migrator->CreateTable('virtualdirectory')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Name', 'varchar(128)')
            ->AddColumn('AccessRightsMask', 'int')
            ->AddColumn('NavigationName', 'varchar(512)')
            ->AddReference('virtualdirectory', 'id', array(), 'ParentDirectoryId')
            ->AddReference('localuser', 'Id', array(), 'OwnerId');

        $migrator->CreateTable('document')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('Name', 'varchar(128)')
            ->AddColumn('ShortDescription', 'varchar(4096)')
            ->AddColumn('NavigationName', 'varchar(512)')
            ->AddReference('localuser', 'Id', array(), 'OwnerId')
            ->AddReference('virtualdirectory', 'Id', array(), 'DirectoryId');

        $migrator->CreateTable('uploadedfile')
            ->AddPrimaryKey('Id', 'int')
            ->AddColumn('LocalFilePath', 'varchar(1025)')
            ->AddColumn('CreateDate', 'varchar(128)')
            ->AddColumn('MimeType', 'varchar(512)')
            ->AddColumn('FileExtension', 'varchar(32)')
            ->AddReference('document', 'Id', array(), 'DocumentId')
            ->AddReference('localuser', 'Id', array(), 'UploadedById');
    }

    public function Down($migrator)
    {

    }

    public function Seed($migrator)
    {

    }
}