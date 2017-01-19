<?php

App::uses('AMULib', 'AjaxMultiUpload.Lib');

/**
 *
 * Dual-licensed under the GNU GPL v3 and the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Suman (srs81 @ GitHub)
 * @package       plugin
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *                and/or GNU GPL v3 (http://www.gnu.org/copyleft/gpl.html)
 */
class UploadsController extends AjaxMultiUploadAppController {

    public $name = "Upload";
    public $uses = null;
    // list of valid extensions, ex. array("jpeg", "xml", "bmp")
    public $allowedExtensions = array();

    public function beforeFilter() {
        parent::beforeFilter();
        if (isset($this->Security))
            $this->Security->unlockedActions = array('upload', 'delete','ajaxOneFileDownloadUpdate','deleteAllFiles');
        if (isset($this->Auth))
            $this->Auth->allow('upload', 'delete','ajaxOneFileDownloadUpdate','deleteAllFiles');
    }

    // get a list of files in a directory
    function getDirFileList($lastDir){
        $dir = Configure::read('AMU.directory');
        $directory = WWW_ROOT . DS . $dir . DS . $lastDir;
        $files = glob("$directory/*");
        return $files;
    }
    
    // delete all but the newest file in a specific directory
    function deleteOlderFiles($lastDir){
        
        $files = $this->getDirFileList($lastDir);
        $created_time = array_map("filemtime", $files);
        if (count($files)==0)
            return;
        $newestfile = $files[array_search(max($created_time), $created_time)];

        if (($key = array_search($newestfile, $files)) !== false) {
            unset($files[$key]);
        }

        array_map('unlink', $files);    
    }
    
    // delete all files in a specific directory
    public function deleteAllFiles($model, $id){
        $lastDir = AMULib::last_dir($model, $id);
        array_map('unlink', $this->getDirFileList($lastDir));
    }
    
    // update the download link of a file through ajax
    function ajaxOneFileDownloadUpdate() {
        $this->autoRender = false;
        
        $last_dir = AMULib::decodeAMUString($this->params['data']['vals']['0']);
        
        $new_file = $this->params['data']['vals']['1'];        
        $this->deleteOlderFiles($last_dir, $new_file);
        $this->set('mesg', AMULib::onefiledownloadlink($last_dir,"", false));
        
        $this->render('/Elements/ajax_mesg', 'ajax');
    }

    public function upload($dir = null) {
        // max file size in bytes
        $size = Configure::read('AMU.filesizeMB');
        if (strlen($size) < 1)
            $size = 4;
        $relPath = Configure::read('AMU.directory');
        if (strlen($relPath) < 1)
            $relPath = "files";

        $sizeLimit = $size * 1024 * 1024;
        $this->layout = "ajax";
        Configure::write('debug', 0);
        $directory = WWW_ROOT . DS . $relPath;

        if ($dir === null) {
            $this->set("result", "{\"error\":\"Upload controller was passed a null value.\"}");
            return;
        }
        // Replace underscores delimiter with slash
        //$dir = str_replace("___", "/", $dir);
        $dir = AMULib::decodeAMUString($dir);
        $dir = $directory . DS . "$dir/";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $uploader = new qqFileUploader($this->allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($dir);
        $this->set("result", htmlspecialchars(json_encode($result), ENT_NOQUOTES));
    }

    /**
     *
     * delete a file
     *
     * Thanks to traedamatic @ github
     */
    public function delete($file = null) {
        if (is_null($file)) {
            $this->Session->setFlash(__('File parameter is missing'));
            $this->redirect($this->referer());
        }
        $file = base64_decode($file);
        if (file_exists($file)) {
            if (unlink($file)) {
                $this->Session->setFlash(__('File deleted!'));
            } else {
                $this->Session->setFlash(__('Unable to delete File'));
            }
        } else {
            $this->Session->setFlash(__('File does not exist!'));
        }

        $this->redirect($this->referer());
    }

}

?>
