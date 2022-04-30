// Implementa le funzionalità hide/show delle immagini

// funzione che gestisce le funzionalità di hide/show
function hide_loader() {
    const hideButton = document.querySelector(".hide-button");

    if(hideButton) hideButton.addEventListener("click", () => {
        this.disabled = true;
        var imageInfo = {};     // creo l'oggetto da passare alla richiesta Ajax
        imageInfo.imageId = (hideButton.id).replace("hide_", "");    // recupero l'ID dell'immagine dal pulsante hide

        if((hideButton.classList.contains("img_hidden")) == true) {  // se l'immagine è nascosta, la mostra
            imageInfo.imageAction = "show";     // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/image_hide_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                hideButton.classList.remove("img_hidden");
                hideButton.childNodes[0].classList.remove("bxs-hide");
                hideButton.childNodes[0].classList.add("bx-hide");

                return hideButton;

            }, JSON.stringify(imageInfo));
        }
        else {  // se l'immagine non è nascosta, la nasconde
            imageInfo.imageAction = "hide";   // imposto l'operazione da eseguire
            ajaxUtils.ajaxSendRequest("./php/ajax/image_hide_manager.php", function(response) {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                hideButton.classList.add("img_hidden");
                hideButton.childNodes[0].classList.remove("bx-hide");
                hideButton.childNodes[0].classList.add("bxs-hide");

                return hideButton;

            }, JSON.stringify(imageInfo));
        }
        this.disabled = false;
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", hide_loader);
else hide_loader();