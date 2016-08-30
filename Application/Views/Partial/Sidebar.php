<?php
function PrintBranch($directories, $controller)
{
    foreach($directories as $directory){
?>
    <li folder-link="/<?php echo $directory->GetFullPath();?>">
        <span class="text-nowrap header">
            <?php if($controller->IsFolderOpen($directory->Id)):?>
                <i class="indicator glyphicon glyphicon-folder-open"></i>
            <?php else:?>
                <i class="indicator glyphicon glyphicon-folder-close"></i>
            <?php endif;?>
            <a href="<?php echo $directory->GetLinkPath();?>">
                <?php echo $directory->Name;?>
            </a>
        </span>
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

<div class="panel panel-default">
    <div class="panel-heading">Directory</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <ul id="SideBarTree" class="tree">
                    <li folder-link="/" class="branch">
                        <span>
                            <i class="indicator glyphicon glyphicon-folder-open"></i>
                            <a href="/Files/Details/"><?php echo $this->Html->SafeHtml('<root>');?></a>
                        </span>
                        <ul>
                            <li>
                                <?php PrintBranch($RootDirectories, $this);?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if($this->IsAdmin()):?>
<div class="panel panel-default">
    <div class="panel-heading">Actions</div>
    <div class="panel-body">
        <div class="row margin-top ">
            <div class="col-lg-12">
                <a href="/VirtualDirectory/Create/" class="btn btn-md btn-primary col-lg-12">Add root directory</a>
            </div>
        </div>
    </div>
</div>
<?php endif;?>