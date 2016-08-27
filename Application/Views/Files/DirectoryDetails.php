<h1><?php echo $VirtualDirectory->Name;?></h1>

<div class="row">
    <div class="col-lg-8 col-md-9 col-sm-12">
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th class="col-lg-3">Filename</th>
                    <th class="col-lg-1">&nbsp;</th>
                    <th class="col-lg-4">Description</th>
                    <th class="col-lg-2">Last updated</th>
                    <th class="col-lg-2">Uploaded by</th>
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
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<?php if($this->CanUploadFile()):?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <a href="/Files/Upload/<?php echo $VirtualDirectory->Id;?>" class="btn btn-md btn-primary">Upload file</a>
        </div>
    </div>
<?php endif;?>

<?php if($this->CanCreateFolder()):?>
    <div class="row margin-top">
        <div class="col-lg-12">
            <a href="/VirtualDirectory/Create/<?php echo $VirtualDirectory->Id;?>" class="btn btn-md btn-primary">Create Directory</a>
        </div>
    </div>
<?php endif;?>
