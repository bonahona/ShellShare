<h1>Upload new file for <?php echo $Document->Name;?></h1>

<div class="row">
    <div class="col-lg-4">
        <?php echo $this->Form->Start('UploadedFile', array('attributes' => array('enctype' => 'multipart/form-data')));?>
        <?php echo $this->Form->Hidden('DocumentId');?>

        <div class="form-group">
            <label>File</label>
            <span class="btn btn-medium btn-primary btn-file form-control">
                Browse
                <?php echo $this->Form->File('UploadedFile');?>
            </span>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Form->Submit('Upload', array('attributes' => array('class' => 'btn btn-medium btn-default')));?>
            </div>
        </div>
        <?php echo $this->Form->End();?>
    </div>
</div>