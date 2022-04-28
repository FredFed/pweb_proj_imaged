<?php
// classi utilizzate per gestire le richieste Ajax del client

require_once("../utils/functions_script.php");

// classe utilizzata per inviare risposte a richieste Ajax
class AjaxResponse {
    public $data;
    public $isOwnGallery;
    public $errorCode;
    public $errorMsg;

    function AjaxResponse($data = null, $isOwnGallery = false, $errorCode = -1, $errorMsg = "server error") {
        $this->data = $data;
        $this->isOwnGallery = $isOwnGallery;
        $this->errorCode = $errorCode;
        $this->errorMsg = $errorMsg;
    }
}

// classe utilizzata per trasferire le immagini alla galleria del client
class Image {
    public $imgAuthorId;     // ID dell'autore dell'immagine
    public $imgAuthorName;  // nome dell'autore dell'immagine
    public $imgId;      // ID dell'immagine
    public $imgName;   // nome dell'immagine
    public $imgCrop;    // nome immagine cropped
    public $imgTitle;   // titolo dell'immagine
    public $imgDesc;    // descrizione dell'immagine
    public $imgTags;    // tags dell'immagine
    public $imgBlock;   // flag immagine bloccata
    public $imgHidden;  // flag immagine nascosta
    public $imgDate;    // data di caricamento dell'immagine

    public $isOwnImage; // indica se l'immagine è dell'utente (loggato)
    public $likeCount;   //contatore dei likes correnti
    public $isLiked;    // indica se l'immagine ha già un like dall'utente
    public $isSaved;    // indica se l'immagine è già salvata dall'utente

    function Image() {
        $this->imgAuthorId=null;
        $this->imgAuthorName=null;
        $this->imgId=null;
        $this->imgName=null;
        $this->imgCrop=null;
        $this->imgTitle=null;
        $this->imgDesc=null;
        $this->imgTags=null;
        $this->imgBlock=null;
        $this->imgHidden=null;
        $this->imgDate=null;

        $this->isOwnImage=null;
        $this->likeCount=null;
        $this->isLiked=null;
        $this->isSaved=null;
        return;
    }

    function buildImage($imgResult, $isProfileGallery) {
        // build an Image object from the DB result given

        if(!$isProfileGallery && $imgResult["usrId"] != null) {
            $this->imgAuthorName = $imgResult["usrName"];
        }
        else {
            $this->imgAuthorName = null;
        }
        $this->imgAuthorId = $imgResult["usrId"];
        $this->imgId = $imgResult["imgId"];
        $this->imgName = $imgResult["imgName"];
        $this->imgCrop = (drop_ext($this->imgName))."cropped.".(get_ext($this->imgName));
        $this->imgTitle = $imgResult["imgTitle"];
        $this->imgDesc = $imgResult["imgDesc"];
        $this->imgTags = $imgResult["imgTags"];
        $this->imgBlock = $imgResult["imgBlock"];
        $this->imgHidden = $imgResult["imgHidden"];
        $this->imgDate = $imgResult["imgDate"];
        return;
    }

    function fillImageData($isOwnImage, $likeCount, $isLiked, $isSaved) {
        $this->isOwnImage = $isOwnImage;
        $this->likeCount = $likeCount;
        $this->isLiked = $isLiked;
        $this->isSaved = $isSaved;
    }
}

// classe utilizzata per trasferire informazioni su likes e immagini salvate
class ImageInteraction {
    public $isLogged;   // indica se l'utente è loggato (abilitato a like/save)
    public $isOwnImage; // indica se l'immagine è dell'utente (loggato)
    public $likeCount;   //contatore dei likes correnti
    public $isLiked;    // indica se l'immagine ha già un like dall'utente
    public $isSaved;    // indica se l'immagine è già salvata dall'utente

    function ImageInteraction() {
        $this->isLogged=null;
        $this->isOwnImage=null;
        $this->likeCount=null;
        $this->isLiked=null;
        $this->isSaved=null;
    }

    function buildResult($isLogged, $isOwnImage, $likeCount, $isLiked, $isSaved) {
        $this->isLogged=$isLogged;
        $this->isOwnImage=$isOwnImage;
        $this->likeCount=$likeCount;
        $this->isLiked=$isLiked;
        $this->isSaved=$isSaved;
    }
}

// classe utilizzata per trasferire i commenti di un'immmagine al client
class Comment {
    public $authorName;     // nome dell'autore del commento
    public $authorPImg;     // path dell'immagine profilo dell'autore
    public $commentText;    // contenuto del commento
    public $commentDate;    // data del commento

    function Comment() {
        $this->authorName=null;
        $this->authorPImg=null;
        $this->commentText=null;
        $this->commentDate=null;
    }

    function buildResult($authorName, $authorPImg, $commentText, $commentDate) {
        $this->authorName=$authorName;
        $this->authorPImg=$authorPImg;
        $this->commentText=$commentText;
        $this->commentDate=$commentDate;
    }
}

?>