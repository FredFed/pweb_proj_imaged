// Implementa le funzionalità like/dislike e save/unsave

(function(global) {

    var imageUtils = {};    // oggetto vuoto cui associare i metodi da esporre nell'ambiente globale

    // funzione che gestisce le funzionalità di like/unlike
    imageUtils.like = function() {
        const likeButton = document.querySelectorAll("#"+this.id);   // creo un riferimento al like button
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (likeButton[0].id).replace("like_", "");    // recupero l'ID dell'immagine dal pulsante like

        if((likeButton[0].classList.contains("liked")) == true) {  // se l'utente ha un like sull'immagine, unlike
            imageInfo.imageAction = "unlike";     // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/likes_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                likeButton.forEach(function likeUpdate(el) {
                    el.classList.remove("liked");   // rimuovo il mark del like
                    el.childNodes[0].classList.remove("bxs-heart");   // rimuovo l'icona che indica il like
                    el.childNodes[0].classList.add("bx-heart");   // aggiungo l'icona che indica il mancato like

                    // aggiorno il contatore dei likes
                    (el.nextSibling).textContent = response.data;
                });

                return likeButton;

            }, JSON.stringify(imageInfo));
        }
        else {  // se l'utente non ha un like sull'immagine, like
            imageInfo.imageAction = "like";   // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/likes_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                likeButton.forEach(function unlikeUpdate(el) {
                    el.classList.add("liked");  // aggiungo il mark del like
                    el.childNodes[0].classList.remove("bx-heart");    // rimuovo l'icona che indica il mancato like
                    el.childNodes[0].classList.add("bxs-heart");  // aggiungo l'icona che indica il like

                    // aggiorno il contatore dei likes
                    (el.nextSibling).textContent = response.data;
                });

                return likeButton;

            }, JSON.stringify(imageInfo));
        }
    };


    // funzione che gestisce le funzionalità di save/unsave
    imageUtils.save = function() {
        const saveButton = document.querySelectorAll("#"+this.id);   // creo un riferimento al save button
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (saveButton[0].id).replace("save_", "");    // recupero l'ID dell'immagine dal pulsante save

        if((saveButton[0].classList.contains("saved")) == true) {  // se l'utente ha un save sull'immagine, unsave
            imageInfo.imageAction = "unsave";     // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/saves_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                saveButton.forEach(function saveUpdate(el) {
                    el.classList.remove("saved");   // rimuovo il mark del save
                    el.childNodes[0].classList.remove("bxs-bookmark");   // rimuovo l'icona che indica il save
                    el.childNodes[0].classList.add("bx-bookmark");   // aggiungo l'icona che indica il mancato save
                });

                return saveButton;

            }, JSON.stringify(imageInfo));
        }
        else {  // se l'utente non ha un save sull'immagine, save
            imageInfo.imageAction = "save";   // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/saves_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                saveButton.forEach(function unsaveUpdate(el) {
                    el.classList.add("saved");  // aggiungo il mark del save
                    el.childNodes[0].classList.remove("bx-bookmark");    // rimuovo l'icona che indica il mancato save
                    el.childNodes[0].classList.add("bxs-bookmark");  // aggiungo l'icona che indica il save
                });

                return saveButton;

            }, JSON.stringify(imageInfo));
        }
    };

    // espongo l'oggetto all'ambiente globale
    global.imageUtils = imageUtils;

})(window);