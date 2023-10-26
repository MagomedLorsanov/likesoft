<?php
if (isset($_GET['path'])) {
    $path = $_GET['path'];
    $pattern = '/^[A-Za-z0-9\s,.-]+\.ixt$/';

    echo getFiles($path, $pattern);
}


/**
 * @param $path path of destination folder to get files
 * @param $pattern rules to get .ixt file containing numeric,latin
 * @return false|string returns string containing JSON with file names or error message
 */
function getFiles($path, $pattern)
{
    $files = [];
    if (is_dir($path)) {
        foreach (scandir($path) as $file) {
            if (preg_match($pattern, $file)) {
                $files[] = $file;
            }
        }
        if (!empty($files)) {
            asort($files);
        }
        return json_encode($files);
    } else {
        return 'Wrong path ' . $path;
    }
}
