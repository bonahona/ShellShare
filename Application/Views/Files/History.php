<h1><?php echo $Document->GetName();?> history</h1>

<div class="row">
    <div class="col-lg-12">
        <h2>Description</h2>
        <p>
            <?php echo $Document->ShortDescription;?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <h2>Version history</h2>

        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Uploaded date</th>
                    <th>&nbsp;</th>
                    <th>Uploaded by</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($Document->UploadedFiles->OrderByDescending('Id') as $uploadedFile):?>
                    <tr>
                        <td><?php echo $uploadedFile->GetLastUpdated();?></td>
                        <td><a href="<?php echo $uploadedFile->GetDirectDownloadLink();?>" download="<?php echo $uploadedFile->GetOldName();?>"><span class="glyphicon glyphicon-download-alt"></span></a></td>
                        <td><?php echo $uploadedFile->GetUploadedBy();?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

<?php if($this->CanUploadFile()):?>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo $Document->GetUpdatePath();?>"><span class="btn btn-medium btn-primary col-lg-2">Upload new file</span></a>
        </div>
    </div>
<?php endif;?>

<div class="row margin-top">
    <div class="col-lg-12">
        <a href="<?php echo $Document->Directory->GetLinkPath();?>" class="btn btn-medium btn-default col-lg-2">Back</a>
    </div>
</div>