<?php


require_once plugin_dir_path(__FILE__) . 'ProcessPost.php';

class ProcessFile
{
    static public $request;
    public static function processRequest($request)
    {
        // check for availiability of uploads folder

        static::$request = $request;
        $status = static::processFile();

        if ($status) {
            wp_send_json_success($status, 200);
        };
    }

    static function checkFolder()
    {
        if (!is_dir(PATH . '/uploads')) {
            mkdir(PATH . '/uploads');
        }
    }


    private static function processFile()
    {
        $file = file_get_contents('php://input');
        $csvArray = str_getcsv($file);

        //clean last and first elements
        $csvArray[0] = substr($csvArray[0], -8);
        $csvArray[count($csvArray) - 1] = substr($csvArray[count($csvArray) - 1], 0, 8);

        return (new ProcessPost('codes', $csvArray))->createPosts();
    }
}
