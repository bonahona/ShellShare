<h1>Files</h1>

<?php if($this->IsLoggedIn()):?>
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-6">
                    <a href="/Documents/Create/" class="btn btn-md btn-default">Upload file</a>
                </div>
                <div class="col-lg-6">
                    <a href="/VirtualDirectory/Create/" class="btn btn-md btn-default">Create root directory</a>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>
