<?php
require_once('/../../ShellLib/Core/ScriptCore.php');
class ClearCacheCore extends ScriptCore
{
    public function __construct($subPath = null, $primaryCore = null)
    {
        $this->IgnoreDatabase = true;
        parent::__construct($subPath, $primaryCore);
    }

    public function ClearModels()
    {
        echo "Clearing model cache";

        // Make sure the cache folder and cache folders exists
        $cacheFilePath = Directory(MODEL_CACHE_FOLDER);
        if(!is_dir($cacheFilePath)) {
            echo "Model cache folder does not exists";
        }


        $currentFile = getcwd();
        print_r($currentFile);

        $basePath = '../../' . MODEL_CACHE_FOLDER;
        $modelCaches = GetAllFiles($basePath);

        foreach($modelCaches as $modelCache){
            $modelCacheFilePath = $basePath . $modelCache;
            unlink($modelCacheFilePath);
        }
    }
}

// Create the object and actually run the code. See this as the 'main()'
$clearCacheObject = new ClearCacheCore();
$clearCacheObject->ClearModels();
