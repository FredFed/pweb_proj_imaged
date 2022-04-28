// Implementa le funzionalità block/unblock delle immagini

// funzione che gestisce le funzionalità di like/unlike
function block_loader() {
    const blockButton = document.querySelector(".block-button");

    if(blockButton) blockButton.addEventListener("click", () => {
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (blockButton.id).replace("block_", "");    // recupero l'ID dell'immagine dal pulsante block

        if((blockButton.classList.contains("blocked")) == true) {  // se l'immagine è bloccata, la sblocca
            imageInfo.imageAction = "unblock";     // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/image_block_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                blockButton.classList.remove("blocked");
                blockButton.childNodes[0].classList.remove("block-color");

                return blockButton;

            }, JSON.stringify(imageInfo));
        }
        else {  // se l'immagine non è bloccata, la blocca
            imageInfo.imageAction = "block";   // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/image_block_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                blockButton.classList.add("blocked");
                blockButton.childNodes[0].classList.add("block-color");

                return blockButton;

            }, JSON.stringify(imageInfo));
        }
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", block_loader);
else block_loader();