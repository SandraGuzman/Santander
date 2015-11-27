<?php
class UploadFileHTML{
   var $file_id;
   var $title;
   var $filename;
   var $fileType;
   var $publicationDate;
   var $year;
   var $quarterly;
   var $title_quarterly;
   var $section_id;
   
   function __construct($fileId,$title,$filename,$fileType,$publicationDate,$year,$quarterly,$sectionId){
        $this->file_id = $fileId; 
		$this->title  = $title;
		$this->filename = $filename;
		$this->fileType = $fileType;
		$this->publicationDate = $publicationDate;
		$this->year = $year;
		$this->quarterly = $quarterly;
		$this->section_id = $sectionId;
		
		switch ($this->quarterly) {
			case 1:
				$this->title_quarterly = "Primer Trimestre";
				break;
			case 2:
				$this->title_quarterly = "Segundo Trimestre";
				break;
			case 3:
				$this->title_quarterly = "Tercer Trimestre";
				break;
			case 4:
				$this->title_quarterly = "Cuarto Trimestre";
				break;
			default:
				$this->title_quarterly = "Trimestre";
				break;
		}
   }
}
?>