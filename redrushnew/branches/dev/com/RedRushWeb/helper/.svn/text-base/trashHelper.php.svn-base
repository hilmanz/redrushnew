<?php
	/*
	 *@Babar 07/03/2012
	 */
	class trashHelper{
		var $trash_folder;
		function __construct(){
			$this->trash_folder = "../trash/"; // Folder Trash
			@chmod($this->trash_folder,0777); // chmod folder trash 0777
		}
		
		function execute($folder='',$file='',$nfolder=''){
			//echo $folder.$file."<hr>".$this->trash_folder.$nfolder."/".$file;exit;
			$folderArr = explode('/',$nfolder);
			$strFolder = $this->trash_folder;
			
			// jika directory belum ada
			if(!is_dir($this->trash_folder.$nfolder)){
				//mkdir($this->trash_folder.$nfolder);
				foreach($folderArr as $f){
					$strFolder .= $f."/";
					if(! is_dir($strFolder)){
						mkdir($strFolder);
					}
				}
				return copy($folder.$file,$this->trash_folder.$nfolder."/".$file);
			}
			// jika directory sudah ada
			else{
				return copy($folder.$file,$this->trash_folder.$nfolder."/".$file);
			}
		}
		
	}
?>