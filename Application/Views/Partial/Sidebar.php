<?php
function PrintBranch($directories, $controller)
{
    foreach($directories as $directory){
?>
    <li folder-link="/<?php echo $directory->GetFullPath();?>">
        <?php if($controller->IsFolderOpen($directory->Id)):?>
            <i class="indicator glyphicon glyphicon-folder-open"></i>
        <?php else:?>
            <i class="indicator glyphicon glyphicon-folder-close"></i>
        <?php endif;?>
        <a href="<?php echo $directory->GetLinkPath();?>">
            <?php echo $directory->Name;?>
        </a>
        <?php if($controller->IsFolderOpen($directory->Id)):?>
            <ul>
                <?php PrintBranch($directory->VirtualDirectories, $controller);?>
            </ul>
        <?php endif;?>
    </li>
<?php
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <ul id="SideBarTree" class="tree">
            <li folder-link="/" class="branch">
                <i class="indicator glyphicon glyphicon-folder-open"></i>
                <a href="/Files/Details/"><?php echo $this->Html->SafeHtml('<root>');?></a>
                <ul>
                    <li>
                        <?php PrintBranch($RootDirectories, $this);?>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<?php if($this->IsAdmin()):?>
    <div class="row margin-top ">
        <div class="col-lg-12">
            <a href="/VirtualDirectory/Create/" class="btn btn-md btn-primary">Add root directory</a>
        </div>
    </div>
<?php endif;?>