// Fa in modo che cliccare sulla "X" pulisca la barra di ricerca
function searchbox_clear() {
    const clearIcon = document.querySelector(".searchbox-clear");
    const searchbox = document.querySelector(".searchbox");

    clearIcon.addEventListener("click", function() {
        searchbox.value = "";
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", searchbox_clear);
else searchbox_clear();