<?php


class ProcessFile
{
    static public $request;
    public static function processRequest($request)
    {
        // check for availiability of uploads folder

        static::$request = $request;
        static::processFile();

        //static::clearFolder(PATH . '/uploads');
    }

    static function checkFolder()
    {
        if (!is_dir(PATH . '/uploads')) {
            mkdir(PATH . '/uploads');
        }
    }

    private function clearFolder($folderPath)
    {
        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            } else {
                $this->clearFolder($file);
                rmdir($file);
            }
        }
    }

    private static function processFile()
    {
        $file = file_get_contents('php://input');
        $csvArray = str_getcsv($file);


        //$file_name = $body['file']['name'];
        //$file_path = $body['file']['tmp_name'];

        //move_uploaded_file($file_path, PATH . '/uploads/' . $file_name);
    }
}
