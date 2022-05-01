// Fa in modo che cliccare sulla "X" pulisca la barra di ricerca
function searchbox_clear() {
    const clearIcon = document.querySelector(".searchbox-clear");
    const searchButton = document.querySelector(".search-icon-frame");
    const searchbox = document.querySelector(".searchbox");

    // se è stata effettuata una ricerca, ne inserisce il valore nel searchbox
    window.location.search.substr(1).split("&").forEach((field) => {
        param = field.split("=");
        if(param[0] === "search") searchbox.value = param[1];
    });


    // il pulsante è inizialmente disabilitato
    searchButton.disabled = true;

    // fare in modo che cliccare la clearIcon non rimuova il focus
    clearIcon.addEventListener("click", () => {
        searchbox.value = "";
    });

    // fare in modo che non si possano fare ricerche vuote
    searchbox.addEventListener("keyup", (e) => {
        if(e.target.value == "") searchButton.disabled=true;
        else searchButton.disabled=false;
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", searchbox_clear);
else searchbox_clear();