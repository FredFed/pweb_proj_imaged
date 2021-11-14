<?php
// classi utilizzate per gestire le richieste Ajax del client

require_once("../utils/functions_script.php");

// classe utilizzata per inviare risposte a richieste Ajax
class AjaxResponse {
    public $data;
    public $errorCode;
    public $errorMsg;

    function AjaxResponse($data = null, $errorCode = -1, $errorMsg = "server error") {
        $this->data = $data;
        $this->errorCode = $errorCode;
        $this->errorMsg = $errorMsg;
    }
}

// classe utilizzata per trasferire le immagini alla galleria del client
class Image {
    public $imgName;   // nome dell'immagine
    public $imgCrop;    // nome immagine cropped
    public $imgTitle;   // titolo dell'immagine
    public $imgDesc;    // descrizione dell'immagine
    public $imgTags;    // tags dell'immagine
    public $imgLsMode;  // flag modalità landscape
    public $imgBlock;   // flag immagine bloccata
    public $imgHidden;  // flag immagine nascosta
    public $imgDate;    // data di caricamento dell'immagine

    function Image() {
        $this->imgName=null;
        $this->imgCrop=null;
        $this->imgTitle=null;
        $this->imgDesc=null;
        $this->imgTags=null;
        $this->imgLsMode=null;
        $this->imgBlock=null;
        $this->imgHidden=null;
        $this->imgDate=null;
        return;
    }

    function buildImage($imgResult) {
        // build an Image object from the DB result given
        $this->imgName = $imgResult["imgName"];
        $this->imgCrop = drop_ext($this->imgName)."_crop.".get_ext($this->imgName);
        $this->imgTitle = $imgResult["imgTitle"];
        $this->imgDesc = $imgResult["imgDesc"];
        $this->imgTags = $imgResult["imgTags"];
        $this->imgLsMode = $imgResult["imgLsMode"];
        $this->imgBlock = $imgResult["imgBlock"];
        $this->imgHidden = $imgResult["imgHidden"];
        $this->imgDate = $imgResult["imgDate"];
        return;
    }
}

?>