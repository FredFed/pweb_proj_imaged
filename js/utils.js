// Contiene varie funzioni di utilità

(function (global) {
    var utils = {}  // fake namespace

    var homepagePath = "../php/elements/homepage.php";

    // funzione che inserisce codice HTML nell'elemento passato in ingresso
    var addHtml = function(selector, code) {
        var element = document.querySelector(selector); // recupero l'elemento indicato dal selettore
        element.innerHTML = code;   // inserisco il codice nell'inner HTML dell'elemento
    };

    // funzione che mostra un'animazione durante il caricamento dell'elemento richiesto in una Ajax-request
    var displayLoading = function(selector) {
        var loadingCode = "<div class='loading'><img src='../resources/icons/ajax-loader.gif'></div>";
        addHtml(selector, loadingCode); // utilizza la funzione sopra definita per aggiungere il codice all'elemento
    };

    document.addEventListener("DOMContentLoaded", function(event) {
        displayLoading("#main-content");
        ajaxUtils.ajaxGetRequest(homepagePath, function(response) {
            document.querySelector("#main-content").innerHTML = response;
        }, false);
    });

    global.utils = utils;
})(window);