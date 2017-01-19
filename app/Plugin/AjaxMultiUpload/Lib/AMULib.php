<?php

/**
 * Random Lib
 *
 * @author Mark Scherer
 * @license MIT
 * 2009-07-24 ms
 */
class AMULib {

    /*
     * Get a listing of files in a certain directory
     * 
     * Returns an array with the following information:
     * baseUrl = WWW directory
     * directory = directory in which files reside
     * files = an array of files in the directory
     * created_time = time of creation of each file
     */
    public static function listing($model, $id) {
        $dir = Configure::read('AMU.directory');
        if (strlen($dir) < 1)
            $dir = "files";

        $lastDir = AMULib::last_dir($model, $id);
        $directory = WWW_ROOT . DS . $dir . DS . $lastDir;
        $baseUrl = Router::url("/") . $dir . "/" . $lastDir;
        $files = glob("$directory/*");
        $created_time = array_map("filemtime", $files);
        return array("baseUrl" => $baseUrl, "directory" => $directory, "files" => $files, "created_time" => $created_time);
    }

    /*
     * Last mile directory in which files reside
     */
    public static function last_dir($model, $id) {
        if (Configure::read('AMU.MD5Paths') == true)
            return md5($model . "/" . $id);
        else
            return $model . "/" . $id;
    }
    
    public static function encodeAMUString($str){        
         $str = str_replace("/", "___",$str);
         return str_replace(" ", "__",$str);
    }
    
    public static function decodeAMUString($str){        
        $str = str_replace("___","/",$str);
        return str_replace("__"," ",$str);
    }
    
    // returns the first file in a mode/id
    // If no file present then returns false
    public static function oneFilePathLocation($model, $id) {
        
        $results = AMULib::listing($model, $id);
        $files = $results['files'];
        $baseUrl = $results['baseUrl'];
        
        if (count($files) > 0) {
            $f = basename($files[0]);
            $url = $baseUrl . "/".$f;
                        
            return $url;
        }
        else{
            return false;
        }
    }

    // returns filename and download link
    public static function oneFileDownloadLink($model, $id, $includeDivs = true, $label = null) {

        $results = AMULib::listing($model, $id);
        $files = $results['files'];
        $baseUrl = $results['baseUrl'];
        if ($includeDivs == true)
            $returnval = "<div id='onefiledownloadlink" . AMULib::encodeAMUString(AMULib::last_dir($model, $id)) . "'>";
        else
            $returnval = "";
        
        if (count($files) > 0) {
            $f = basename($files[0]);
            $url = $baseUrl . "/$f";
            if (isset($label))
                $returnval .= "<a href='$url'>" . $label . "</a>";
            else
                $returnval .= "<a href='$url'>" . $f . "</a>";

            if (count($files) > 1)
                $returnval .= " Warning: more than one!";
            
            if ($includeDivs == true)
                $returnval .= "</div>";
            
            return $returnval;
        }
        else{
            $returnval .= "N/A";
            
            if ($includeDivs == true)
                $returnval .= "</div>";

            return $returnval;
        }

    }

}
