// Gestisce il blocco/sblocco di utenti da parte degli amministratori

function user_block_loader() {
    const blockButton = document.querySelector(".block-button");
    const username = document.querySelector(".profile-name").textContent;

     if(blockButton) blockButton.addEventListener("click", () => {
        this.disabled = true;
        let modButton = document.querySelector(".mod-button");

        let request = {};
        request.username = username;
        if(blockButton.classList.contains("blocked")) {
            request.action=0;   // 0: UNBLOCK
            ajaxUtils.ajaxSendRequest("./php/ajax/user_blocks_manager.php", (response) => {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                blockButton.classList.remove("blocked");
                blockButton.childNodes[0].classList.remove("bxs-lock");
                blockButton.childNodes[0].classList.add("bx-lock-open");

                return blockButton;

            }, JSON.stringify(request));
        }
        else {
            if (modButton && modButton.classList.contains("mod")) return;
            request.action=1;   // 1: BLOCK
            ajaxUtils.ajaxSendRequest("./php/ajax/user_blocks_manager.php", (response) => {

                // gestione errori
                if(response.errorCode != 0) {
                    if(response.errorCode == -2) {
                        // se l'utente non è loggato, rimandalo al login
                        window.location.href="./login";
                    }
                    else return null; // errore fatale
                }

                blockButton.classList.add("blocked");
                blockButton.childNodes[0].classList.remove("bx-lock-open");
                blockButton.childNodes[0].classList.add("bxs-lock");

                return blockButton;

            }, JSON.stringify(request));
        }
        this.disabled = false;
    })
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", user_block_loader);
else user_block_loader();