<h1><?php echo htmlentities('<root>');?></h1>

<h2 class="light-grey">This directory is always empty</h2>

<?php if($this->IsLoggedIn()):?>
    <div class="row">
        <div class="col-lg-12">
            <a href="/VirtualDirectory/Create/" class="btn btn-md, btn-default">Create folder</a>
        </div>
    </div>
<?php endif;?>
