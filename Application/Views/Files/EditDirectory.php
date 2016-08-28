<h1>Edit directory <?php echo $VirtualDirectory->Name;?></h1>

<div class="row">
    <div class="col-lg-4">
        <?php echo $this->Form->Start('VirtualDirectory');?>
        <?php echo $this->Form->Hidden('Id');?>
        <?php echo $this->Form->Hidden('OwnerId');?>
        <div class="form-group">
            <label>Name</label>
            <?php echo $this->Form->Input('Name', array('attributes' => array('class' => 'form-control', 'required' => 'true')));?>
        </div>
        <div class="form-group">
            <label>Parent directory</label>
            <?php echo $this->Form->Select('ParentDirectoryId', $VirtualDirectories, array('attributes' => array('class' => 'form-control')));?>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-medium btn-default')));?>
            </div>
        </div>
        <?php echo $this->Form->End();?>
    </div>
</div>