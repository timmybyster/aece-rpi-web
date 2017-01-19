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
class UploadHelper extends AppHelper {

    // Get unique temp folder
    public function getTempFolder($model){
	$rnddir = substr(md5(rand()), 0, 10);
	$path = AMULib::last_dir($model, $rnddir);
	while (file_exists($path))
	{
	    $rnddir = substr(md5(rand()), 0, 10);
	    $path = AMULib::last_dir($model, $rnddir);
	}
	return $rnddir;
    }
    
    public function view($model, $id, $edit = false) {
        $results = $this->listing($model, $id);

        $directory = $results['directory'];
        $baseUrl = $results['baseUrl'];
        $files = $results['files'];

        //$str = "<dt>" . __("Files") . "</dt>\n<dd>";
        $str = "";
        $count = 0;
        $webroot = Router::url("/") . "ajax_multi_upload";
        foreach ($files as $file) {
            $type = pathinfo($file, PATHINFO_EXTENSION);
            $filesize = $this->format_bytes(filesize($file));
            $f = basename($file);
            $url = $baseUrl . "/$f";
            if ($edit) {
                $baseEncFile = base64_encode($file);
                $delUrl = "$webroot/uploads/delete/$baseEncFile/";
                $str .= "<a href='$delUrl'><img src='" . Router::url("/") .
                        "ajax_multi_upload/img/delete.png' alt='Delete' /></a> ";
            }
            //$str .= "<img src='" . Router::url("/") . "ajax_multi_upload/img/fileicons/$type.png' /> ";
            $str .= "<a href='$url'>" . $f . "</a> ($filesize)";
            $str .= "<br />\n";
        }
        $str .= "</dd>\n";
        return $str;
    }

    // get a listing of all the files in a specific directory
    public function listing($model, $id) {
        return AMULib::listing($model, $id);
    }

    // returns filename and download link
    public function oneFileDownloadLink($model, $id, $label = null) {

        return AMULib::oneFileDownloadLink($model, $id, true, $label);
    }

    // returns a filename and download link, together with a upload button
    // it only allows one file to be present
    // the download link is updated after upload
    public function oneFileUpload($model, $id, $label = null, $options = []) {
        $returnval = $this->oneFileDownloadLink($model, $id, $label = null);
        $returnval .= $this->createUploadButtonWithAJAXDownloadLink($model, $id, $options);
        return $returnval;
    }

    // allowed_extensions: must be in format 'jpg, png'
    public function createUploadButtonWithAJAXDownloadLink($model, $id, $options = []) {

        $webroot = Router::url("/") . "ajax_multi_upload";
        $str = $this->createUploadButton($model, $id, false);
        
        $allowed_extension_str = "";
        if (isset($options['allowed_extensions']))
            $allowed_extension_str = "'".implode("','",$options['allowed_extensions'])."'";
        
        $max_size = 0;        
        if (isset($options['max_size']))
            $max_size = $options['max_size'];
	
	$button_style = "qq-upload-button";
	if (isset($options['use_bootstrap']))
            $button_style = "btn btn-success qq-upload-button";
        
        $str .= <<<END
				function createUploader(){
					var amuCollection = document.getElementsByName("AjaxMultiUpload");
					for (var i = 0, max = amuCollection.length; i < max; i++) {
							action = amuCollection[i].id.replace('AjaxMultiUpload', '');
							window['uploader'+i] = new qq.FileUploader({
								element: amuCollection[i],
								action: '$webroot/uploads/upload/' + action + '/',
                                                                action_raw: action,
								debug: true,
                                                                sizeLimit: $max_size,
                                                                allowedExtensions: [$allowed_extension_str],    
                                                                onComplete: function(id, name, response) { 
                                                                                  $('#onefiledownloadlink' + this.action_raw).load('$webroot/uploads/ajaxOneFileDownloadUpdate/', { 'vals': [this.action_raw, name] });},                                                            
                                                                onSubmit: function(id, fileName){ $('#qq-upload-list').empty()},
                                                                template: '<div class="qq-uploader">' + 
                                                                    '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                                                                    '<div class="$button_style">Upload a file</div>' +
                                                                    '<ul class="qq-upload-list" id="qq-upload-list"></ul>' + 
                                                                 '</div>'
							});
						}
					}
				window.onload = createUploader;     
			</script>
END;
        return $str;
    }

    // This function is called by most views to create a simple upload button, with optional file list
    public function edit($model, $id, $showview = false, $refreshAfterSubmit = true, $onComplete = "") {

        $webroot = Router::url("/") . "ajax_multi_upload";
        $str = $this->createUploadButton($model, $id, $showview);
	$refreshStr = "";
	if ($refreshAfterSubmit == true){
	    $refreshStr = "location.reload();";
	}
	
        $str .= <<<END
                    function createUploader(){
                            var amuCollection = document.getElementsByName("AjaxMultiUpload");
                            for (var i = 0, max = amuCollection.length; i < max; i++) {
                                            action = amuCollection[i].id.replace('AjaxMultiUpload', '');
                                            window['uploader'+i] = new qq.FileUploader({
                                                    element: amuCollection[i],
                                                    action: '$webroot/uploads/upload/' + action + '/',
                                                    debug: true,                                                        
                                                    template: '<div class="qq-uploader">' + 
                                                                    '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                                                                    '<div class="qq-upload-button btn btn-success">Upload a file</div>' +
                                                                    '<ul class="qq-upload-list" id="qq-upload-list"></ul>' + 
                                                                 '</div>',
                                                   onComplete: function(id, name, response) { $refreshStr $onComplete }
                                            });
                                    }
                            }
                    window.onload = createUploader;     
            </script>
END;
        return $str;
    }

    // used by both the edit() function and createUploadButtonWithAJAXDownloadLink() function
    private function createUploadButton($model, $id, $showview = false) {

        $dir = Configure::read('AMU.directory');
        if (strlen($dir) < 1)
            $dir = "files";

        $str = '';
        if ($showview)
            $str = $this->view($model, $id, true);

        $webroot = Router::url("/") . "ajax_multi_upload";
        $lastDir = AMULib::encodeAMUString(AMULib::last_dir($model, $id));

        $str .= <<<END
						
			<script src="$webroot/js/fileuploader.js" type="text/javascript"></script>
			<div id="AjaxMultiUpload$lastDir" name="AjaxMultiUpload">
				<noscript>
					 <p>Please enable JavaScript to use file uploader.</p>
				</noscript>
			</div>
			<script src="$webroot/js/fileuploader.js" type="text/javascript"></script>
			<script>
				if (typeof document.getElementsByClassName!='function') {
				    document.getElementsByClassName = function() {
				        var elms = document.getElementsByTagName('*');
				        var ei = new Array();
				        for (i=0;i<elms.length;i++) {
				            if (elms[i].getAttribute('class')) {
				                ecl = elms[i].getAttribute('class').split(' ');
				                for (j=0;j<ecl.length;j++) {
				                    if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
				                        ei.push(elms[i]);
				                    }
				                }
				            } else if (elms[i].className) {
				                ecl = elms[i].className.split(' ');
				                for (j=0;j<ecl.length;j++) {
				                    if (ecl[j].toLowerCase() == arguments[0].toLowerCase()) {
				                        ei.push(elms[i]);
				                    }
				                }
				            }
				        }
				        return ei;
				    }
				}				
END;
        return $str;
    }

    // The "last mile" of the directory path for where the files get uploaded
//    public function last_dir($model, $id) {
//        if (Configure::read ('AMU.MD5Paths') == true)
//            return md5($model . "/" . $id);
//        else
//            return $model . "/" . $id;
//    }
    // From http://php.net/manual/en/function.filesize.php
    public function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++)
            $size /= 1024;
        return round($size, 2) . $units[$i];
    }

}
