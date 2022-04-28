// applica i listener per il like ed il save ai rispettivi pulsanti
function like_save_script() {
    const likeButton = document.querySelector(".like-button");
    const saveButton = document.querySelector(".save-button");
    
    // rimuove il testo nullo presente prima del contatore like
    nodeToRemove = likeButton.nextSibling;
    likeButton.parentElement.removeChild(nodeToRemove);

    likeButton.addEventListener("click", () => {
        likeButton.disabled = true;
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (likeButton.id).replace("like_", "");    // recupero l'ID dell'immagine dal pulsante like

        if((likeButton.classList.contains("liked")) == true) {  // se l'utente ha un like sull'immagine, unlike
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

                likeButton.classList.remove("liked");   // rimuovo il mark del like
                likeButton.childNodes[0].classList.remove("bxs-heart");   // rimuovo l'icona che indica il like
                likeButton.childNodes[0].classList.add("bx-heart");   // aggiungo l'icona che indica il mancato like
                // aggiorno il contatore dei likes
                (likeButton.nextSibling).textContent = response.data;
                likeButton.disabled = false;

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

                likeButton.classList.add("liked");  // aggiungo il mark del like
                likeButton.childNodes[0].classList.remove("bx-heart");    // rimuovo l'icona che indica il mancato like
                likeButton.childNodes[0].classList.add("bxs-heart");  // aggiungo l'icona che indica il like
                // aggiorno il contatore dei likes
                (likeButton.nextSibling).textContent = response.data;
                likeButton.disabled = false;

                return likeButton;

            }, JSON.stringify(imageInfo));
        }
    });
    saveButton.addEventListener("click", () => {
        saveButton.disabled = true;
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (saveButton.id).replace("save_", "");    // recupero l'ID dell'immagine dal pulsante save

        if((saveButton.classList.contains("saved")) == true) {  // se l'utente ha un save sull'immagine, unsave
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

                saveButton.classList.remove("saved");   // rimuovo il mark del save
                saveButton.childNodes[0].classList.remove("bxs-bookmark");   // rimuovo l'icona che indica il save
                saveButton.childNodes[0].classList.add("bx-bookmark");   // aggiungo l'icona che indica il mancato save
                saveButton.disabled = false;

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

                saveButton.classList.add("saved");  // aggiungo il mark del save
                saveButton.childNodes[0].classList.remove("bx-bookmark");    // rimuovo l'icona che indica il mancato save
                saveButton.childNodes[0].classList.add("bxs-bookmark");  // aggiungo l'icona che indica il save
                saveButton.disabled = false;

                return saveButton;

            }, JSON.stringify(imageInfo));
        }
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", like_save_script);
else like_save_script();