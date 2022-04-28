// Fa in modo che cliccare sulla "X" pulisca la barra di ricerca
function searchbox_clear() {
    const clearIcon = document.querySelector(".searchbox-clear");
    const searchButton = document.querySelector(".search-icon-frame");
    const searchbox = document.querySelector(".searchbox");

    // fare in modo che cliccare la clearIcon non rimuova il focus
    clearIcon.addEventListener("click", () => {
        searchbox.value = "";
    });

    // fare in modo che non si possano fare ricerche vuote
    searchbox.addEventListener("keydown", () => {
        if(searchbox.value == "") searchButton.disabled=true;
        else searchButton.disabled=false;
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", searchbox_clear);
else searchbox_clear();