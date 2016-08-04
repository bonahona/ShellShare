<?php
function PrintBranch($directories)
{
    foreach($directories as $directory){
?>
    <li folder-link="/<?php echo $directory->GetFullPath();?>">
        <?php /*
        <a href="#">
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $directory->Name;?>
                </div>
            </div>
        </a>
        */?>
        <a href="#">
            <?php echo $directory->Name;?>
        </a>
        <ul>
            <?php PrintBranch($directory->VirtualDirectories);?>
        </ul>
    </li>
<?php
    }
}
?>

<ul id="SideBarTree">
    <li folder-link="/">
        <a href="#"><?php echo $this->Html->SafeHtml('<root>');?></a>
        <ul>
            <li>
                <?php PrintBranch($RootDirectories);?>
            </li>
        </ul>
    </li>
</ul>