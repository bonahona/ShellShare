<h1>Edit <?php echo $Document->Name;?></h1>

<div class="row">
    <div class="col-lg-4">
        <?php echo $this->Form->Start('Document', array('attributes' => array('enctype' => 'multipart/form-data')));?>
        <?php echo $this->Form->Hidden('Id');?>
        <?php echo $this->Form->Hidden('OwnerId');?>
        <div class="form-group">
            <label>Name</label>
            <?php echo $this->Form->Input('Name', array('attributes' => array('class' => 'form-control', 'required' => 'true')));?>
        </div>
        <div class="form-group">
            <label>Short Description</label>
            <?php echo $this->Form->Input('ShortDescription', array('attributes' => array('class' => 'form-control', 'required' => 'true')));?>
        </div>
        <div class="form-group">
            <label>Directory</label>
            <?php echo $this->Form->Select('DirectoryId', $VirtualDirectories, array('attributes' => array('class' => 'form-control')));?>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-medium btn-primary col-lg-6')));?>
            </div>
        </div>
        <?php echo $this->Form->End();?>
    </div>
</div>

<div class="row margin-top">
    <div class="col-lg-12">
        <a href="<?php echo $Document->Directory->GetLinkPath();?>" class="btn btn-medium btn-default col-lg-2">Back</a>
    </div>
</div>