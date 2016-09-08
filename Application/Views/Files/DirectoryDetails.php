<h1><?php echo $VirtualDirectory->Name;?></h1>

<?php if(count($VirtualDirectory->Documents) > 0):?>
<div class="row">
    <div class="col-lg-8 col-md-9 col-sm-12">
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <?php if($this->CanEditDirectory($VirtualDirectory->Id)):?>
                        <th class="col-lg-2">Filename</th>
                        <th class="col-lg-1">&nbsp;</th>
                        <th class="col-lg-3">Description</th>
                        <th class="col-lg-2">Last updated</th>
                        <th class="col-lg-2">Uploaded by</th>
                        <th class="col-lg-2">&nbsp;</th>
                    <?php else:?>
                        <th class="col-lg-3">Filename</th>
                        <th class="col-lg-1">&nbsp;</th>
                        <th class="col-lg-4">Description</th>
                        <th class="col-lg-2">Last updated</th>
                        <th class="col-lg-2">Uploaded by</th>
                        <th class="">&nbsp;</th>
                    <?php endif;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($VirtualDirectory->Documents as $document):?>
                    <tr>
                        <td><?php echo $this->Html->Link($document->GetHistoryPath(), $document->GetName());?></td>
                        <td><a href="<?php echo $document->GetDownloadPath();?>" download="<?php echo $document->GetName();?>"><span class="glyphicon glyphicon-download-alt"</a></td>
                        <td><?php echo $document->ShortDescription;?></td>
                        <td><?php echo $document->GetLastUpdated();?></td>
                        <td><?php echo $document->GetUploadedBy();?></td>
                        <?php if($this->CanEditDirectory($VirtualDirectory->Id)):?>
                            <td class="">
                                <a class="btn btn-medium btn-default" href="<?php echo $document->GetEditPath();?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                <a class="btn btn-medium btn-default" href="<?php echo $document->GetDeletePath();?>"><span class="glyphicon glyphicon-trash"></span></a>
                            </td>
                        <?php endif;?>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<?php else:?>
    <h2 class="light-grey">This directory is empty</h2>
<?php endif;?>
<?php if($this->CanUploadFile()):?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <a href="/Upload/<?php echo $VirtualDirectory->Id;?>" class="btn btn-md btn-primary col-lg-2">Upload file</a>
        </div>
    </div>
<?php endif;?>

<?php if($this->CanCreateFolder()):?>
    <div class="row margin-top">
        <div class="col-lg-12">
            <a href="/VirtualDirectory/Create/<?php echo $VirtualDirectory->Id;?>" class="btn btn-md btn-primary col-lg-2">Create Directory</a>
        </div>
    </div>
<?php endif;?>

<?php if($this->CanEditDirectory($VirtualDirectory->Id)):?>
    <div class="row margin-top">
        <div class="col-lg-12">
            <a href="<?php echo $VirtualDirectory->GetEditPath();?>" class="btn btn-md btn-default col-lg-2">Edit directory</a>
        </div>
    </div>
<?php endif;?>
