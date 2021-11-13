// Implementa le funzionalità Ajax

(function(global) {

    var ajaxUtils = {};  // oggetto (fake namespace) vuoto cui associare i membri da esporre all'ambiente globale

    function getRequestObject() {   // restituisce un request object
        if(window.XMLHttpRequest) {
            return (new XMLHttpRequest());
        }
        else if(window.ActiveXObject) { // solo per vecchie versioni di IE
            return (new ActiveXObject("Microsoft.XMLHTTP"));
        }
        else {  // se Ajax non è supportato
            global.alert("Ajax not supported!");
            return(null);   // caso limite
        }
    }

    // effettua una richiesta Ajax per l'URL specificato; questa è l'unica funzione che verrà esposta all'ambiente globale
    ajaxUtils.ajaxSendRequest = function(requestUrl, responseHandler, postData, isJsonGet, isJsonPost) {
        var requestMethod = "GET";  // la richiesta predefinita è di tipo GET
        if(postData == undefined) postData = null;  // se non è stato passato nulla da inviare, la richiesta è GET
        if(postData != null) requestMethod = "POST";    // se sono stati passati dati da inviare, la richiesta è di tipo POST

        var ajaxRequest = getRequestObject();   // recupero un request object
        // assegno la gestione della risposta ad un handler specializzato (quando questa sarà "ready")
        ajaxRequest.onreadystatechange = function() { handleServerResponse(ajaxRequest, responseHandler, isJsonGet); };
        ajaxRequest.open(requestMethod, requestUrl, true);  // effettuo la richiesta GET per l'url in modo asincrono

        if(requestMethod === "POST") {  // se la richiesta è di tipo POST...
            if((isJsonPost == true) || (isJsonPost == undefined))   // se non specificato diversamente, il dato è JSON
                ajaxRequest.setRequestHeader("Content-Type", "application/json");

            // altrimenti, normale codifica
            else ajaxRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        }
        
        ajaxRequest.send(postData);  // solo per richieste POST
    };

    function handleServerResponse(ajaxRequest, responseHandler, isJson) {
        if(isJson == undefined) isJson = true;  // se non è stato specificato altrimenti, la risposta sarà informato JSON

        // quando la richiesta è completata (state 4) e se è stata eseguita con successo (code 200),
        // passa all'handler specializzato specificato inizialmente
        if((ajaxRequest.readyState == 4) && (ajaxRequest.status == 200)) {
            var response;
            if(isJson) response = JSON.parse(ajaxRequest.responseText); // parsing della risposta JSON
            else response = ajaxRequest.responseText;   // recupera il plain-text se la risposta non è JSON

            responseHandler(response);  // inizia l'elaborazione della risposta
        }
        else return null;   // restituisce NULL se c'è stato un errore fatale nella gestione della richiesta
    }

    // espongo l'oggetto all'ambiente globale
    global.ajaxUtils = ajaxUtils;
})(window);