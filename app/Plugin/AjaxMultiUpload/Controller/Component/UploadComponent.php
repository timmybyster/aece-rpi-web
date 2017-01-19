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
class UploadComponent extends Component {

    public $controller = null;
    public $settings = array();

    /**
     * Constructor
     *
     * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
     * @param array $settings Array of configuration settings
     * @access public
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        $this->controller = $collection->getController();
        $this->settings = $settings;
    }

    /**
     * Component startup method.
     * Called after the Controller::beforeFilter() and before the controller action
     * @param object $controller A reference to the instantiating controller object
     * @access public
     */
    public function startup(Controller $controller) {
        if (!in_array('AjaxMultiUpload.Upload', $this->controller->helpers) && !array_key_exists('AjaxMultiUpload.Upload', $this->controller->helpers)) {
            $this->controller->helpers[] = 'AjaxMultiUpload.Upload';
        }
    }

    public function moveAll($srcModel, $srcId, $destModel, $destId) {
        $dir = Configure::read('AMU.directory');
        if (strlen($dir) < 1)
            $dir = "files";
        $newLastDir = $destModel . DS . $destId . '/';
        $newdirectory = WWW_ROOT . DS . $dir . DS . $newLastDir;
        mkdir($newdirectory);
        
        $lastDir = AMULib::last_dir($srcModel, $srcId);
        $dirPath = WWW_ROOT . DS . $dir . DS . $lastDir . DS;
        $files = glob($dirPath . '*', GLOB_MARK);
        
        foreach ($files as $file) {
            $newfileLoc = $newdirectory . basename($file);
            rename($file, $newfileLoc);
        }
    }
    
    public function moveSelection($selection, $srcModel, $srcId, $destModel, $destId) {
        $dir = Configure::read('AMU.directory');
        if (strlen($dir) < 1)
            $dir = "files";
        $newLastDir = $destModel . DS . $destId . '/';
        $newdirectory = WWW_ROOT . DS . $dir . DS . $newLastDir;
        mkdir($newdirectory);
        
        $lastDir = AMULib::last_dir($srcModel, $srcId);
        $dirPath = WWW_ROOT . DS . $dir . DS . $lastDir . DS;
        $files = glob($dirPath . '*', GLOB_MARK);
        	
        foreach ($files as $file) {
	    if (in_array(basename($file), $selection))
	    {
		$newfileLoc = $newdirectory . basename($file);
		rename($file, $newfileLoc);
	    }
        }
    }

    /*
     * Get a listing of the files inside a certain location
     * See AMULib::listing for a more elaborate description
     */
    public function listing($model, $id) {
        return AMULib::listing($model, $id);                
    }
    
    public function deleteAll($model, $id) {
        $dir = Configure::read('AMU.directory');
        if (strlen($dir) < 1)
            $dir = "files";

        $lastDir = AMULib::last_dir($model, $id);
        $dirPath = WWW_ROOT . DS . $dir . DS . $lastDir . DS;
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            unlink($file);
        }
        if (is_dir($dirPath))
            rmdir($dirPath);
    }

    // The "last mile" of the directory path for where the files get uploaded
//	public function last_dir ($model, $id) {
//        if (Configure::read ('AMU.MD5Paths') == true)
//            return md5($model . "/" . $id);
//        else
//            return $model . "/" . $id;
//	}
}
