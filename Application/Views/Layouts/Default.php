<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">
    <?php echo $this->Html->Favicon('fyrvall-favicon.png');?>

    <title><?php echo $title;?></title>

    <?php echo $this->Html->Css('bootstrap.min.css');?>
    <?php echo $this->Html->Css('dashboard.css');?>
    <?php echo $this->Html->Css('sh_style.css');?>
    <?php echo $this->Html->Css('bootstrap-treeview.css');?>

</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top dark-green">
    <div class="container-fluid">
        <div class="navbar-header">
            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a href="/" class="dropdown-toggle navbar-brand light-grey" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Share
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="navbar-brand"><a href="http://documentation.fyrvall.com">Documentation</a></li>
                        <li class="navbar-brand"><a href="/">Share</a></li>
                    </ul>
                </li>
            </ul>
            <span class="navbar-brand light-grey">|</span>
            <a class="navbar-brand light-grey" href="http://fyrvall.com">Fyrvall.com</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php if($this->IsLoggedIn()):?>
                    <li><a class="light-grey" href="/User/Logout">Log out</a></li>
                <?php endif;?>
            </ul>
            <form method="get" action="/Home/Search" class="navbar-form navbar-right">
                <?php if(isset($SearchQuery)):?>
                    <input type="text" name="keywords" class="form-control" placeholder="Search..."/ value="<?php echo $SearchQuery;?>">
                <?php else:?>
                    <input type="text" name="keywords" class="form-control" placeholder="Search..."/>
                <?php endif;?>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <?php if(isset($RootDirectories)):?>
                <?php echo $this->PartialView('Sidebar', array('RootDirectories' => $RootDirectories));?>
            <?php endif;?>
        </div>
        <div id="file-container" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <?php if(isset($BreadCrumbs)):?>
                <?php echo $this->PartialView('Breadcrumbs', array('BreadCrumbs' => $BreadCrumbs));?>
            <?php endif;?>
            <?php echo $view;?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<?php echo $this->Html->Js('bootstrap.min.js');?>
<?php echo $this->Html->Js('dashboard.js');?>
<?php if($this->IsLoggedIn()):?>
    <?php echo $this->Html->Js('dashboard_admin.js');?>
<?php endif;?>
<?php echo $this->Html->Js('sh_main.min.js');?>
<?php echo $this->Html->Js('sh_cpp.min.js');?>
<?php echo $this->Html->Js('sh_csharp.min.js');?>
<?php echo $this->Html->Js('sh_php.min.js');?>
<?php echo $this->Html->Js('bootstrap-treeview.js');?>
</body>
</html>
