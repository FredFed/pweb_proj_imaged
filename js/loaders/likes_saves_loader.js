// Implementa le funzionalità like/dislike e save/unsave

(function(global) {

    var imageUtils = {};    // oggetto vuoto cui associare i metodi da esporre nell'ambiente globale

    // funzione che gestisce le funzionalità di like/unlike
    imageUtils.like = function() {
        const likeButton = document.getElementById(this.id);   // creo un riferimento al like button
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (likeButton.id).replace("like_", "");    // recupero l'ID dell'immagine dal pulsante like

        if((likeButton.classList.contains("liked")) == true) {  // se l'utente ha un like sull'immagine, unlike
            console.log("unliking");   // TODO remove
            imageInfo.imageAction = "unlike";     // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/likes_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // TODO caso utente non loggato
                    }
                    else return null; // errore fatale
                }

                likeButton.classList.remove("liked");   // rimuovo il mark del like
                likeButton.childNodes[0].classList.remove("bxs-heart");   // rimuovo l'icona che indica il like
                likeButton.childNodes[0].classList.add("bx-heart");   // aggiungo l'icona che indica il mancato like

                return likeButton;

            }, JSON.stringify(imageInfo));
        }
        else {  // se l'utente non ha un like sull'immagine, like
            console.log("liking");     // TODO remove
            imageInfo.imageAction = "like";   // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/likes_manager.php", function(response) {

                if(response.errorCode != 0) return null; // errore fatale

                likeButton.classList.add("liked");  // aggiungo il mark del like
                likeButton.childNodes[0].classList.remove("bx-heart");    // rimuovo l'icona che indica il mancato like
                likeButton.childNodes[0].classList.add("bxs-heart");  // aggiungo l'icona che indica il like

                return likeButton;

            }, JSON.stringify(imageInfo));
        }
    };


    // funzione che gestisce le funzionalità di save/unsave
    imageUtils.save = function() {
        const saveButton = document.getElementById(this.id);   // creo un riferimento al save button
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (saveButton.id).replace("save_", "");    // recupero l'ID dell'immagine dal pulsante save

        if((saveButton.classList.contains("saved")) == true) {  // se l'utente ha un save sull'immagine, unsave
            imageInfo.imageAction = "unsave";     // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/saves_manager.php", function(response) {
                console.log("unsaving");  // TODO remove
                if(response.errorCode != 0) return null; // errore fatale

                saveButton.classList.remove("saved");   // rimuovo il mark del save
                saveButton.childNodes[0].classList.remove("bxs-bookmark");   // rimuovo l'icona che indica il save
                saveButton.childNodes[0].classList.add("bx-bookmark");   // aggiungo l'icona che indica il mancato save

                return saveButton;

            }, JSON.stringify(imageInfo));
        }
        else {  // se l'utente non ha un save sull'immagine, save
            imageInfo.imageAction = "save";   // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/saves_manager.php", function(response) {
                console.log("saving");  // TODO remove
                if(response.errorCode != 0) return null; // errore fatale

                saveButton.classList.add("saved");  // aggiungo il mark del save
                saveButton.childNodes[0].classList.remove("bx-bookmark");    // rimuovo l'icona che indica il mancato save
                saveButton.childNodes[0].classList.add("bxs-bookmark");  // aggiungo l'icona che indica il save

                return saveButton;

            }, JSON.stringify(imageInfo));
        }
    };

    // espongo l'oggetto all'ambiente globale
    global.imageUtils = imageUtils;

})(window);