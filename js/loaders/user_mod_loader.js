// Gestisce il cambiamento di privilegio di utenti da parte degli amministratori

function user_mod_loader() {
    const modButton = document.querySelector(".mod-button");
    const username = document.querySelector(".profile-name").textContent;

    if(modButton) modButton.addEventListener("click", () => {
        this.disabled = true;
        let blockButton = document.querySelector(".block-button");
        
        let request = {};
        request.username = username;
        if(modButton.classList.contains("mod")) {
            request.action=0;   // 0: UNMOD
            ajaxUtils.ajaxSendRequest("./php/ajax/user_mod_manager.php", (response) => {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                modButton.classList.remove("mod");
                modButton.childNodes[0].classList.remove("bxs-crown");
                modButton.childNodes[0].classList.add("bx-crown");

                return modButton;

            }, JSON.stringify(request));
        }
        else {
            if (blockButton && blockButton.classList.contains("blocked")) return;
            request.action=1;   // 1: MOD
            ajaxUtils.ajaxSendRequest("./php/ajax/user_mod_manager.php", (response) => {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                modButton.classList.add("mod");
                modButton.childNodes[0].classList.remove("bx-crown");
                modButton.childNodes[0].classList.add("bxs-crown");

                return modButton;

            }, JSON.stringify(request));
        }
        this.disabled = false;
    })
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", user_mod_loader);
else user_mod_loader();