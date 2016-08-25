<h1><?php echo $VirtualDirectory->Name;?></h1>

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
