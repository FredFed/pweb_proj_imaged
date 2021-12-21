// Gestisce il caricamento della galleria della Homepage via AJAX



function load_homepage_gallery() {
    load_homepage_gallery.postsIncrement = 24;
    load_homepage_gallery.postsCount = 0;
    load_homepage_gallery.isGalleryLoaded = false;

    const gallery = document.querySelector("#homepage-gallery");   // gallerie profilo
    const galleryDelimiter = document.querySelector(".gallery-delimiter-lower");  // delimitatore galleria profilo

    // creazione dell'intersection observer
    let options = {
        root: null,
        rootMargin: '200px',
        threshold: 1
    }
    const galleryObs = new IntersectionObserver(intersectHandler, options);
    galleryObs.observe(galleryDelimiter);

    // funzione che permette di caricare immagini progressivamente quando ci si avvicina alla fine della galleria
    function intersectHandler(entries) {
        entries.forEach(function intersectionAction(el) {
            // si attiva al raggiungimento del termine della galleria attiva
            if(el.isIntersecting) {
                loadImages();
            }
        })
    }

    function loadImages() {
        // se la galleria selezionata è stata caricata completamente, non serve proseguire
        if(load_homepage_gallery.isGalleryLoaded) return;
        
        // creo un oggetto info galleria da passare mediante POST al server
        var galleryInfo = {};
        galleryInfo.count = load_homepage_gallery.postsCount;
        galleryInfo.increment = load_homepage_gallery.postsIncrement;

        // aggiorno il numero di post richiesti finora
        load_homepage_gallery.postsCount += load_homepage_gallery.postsIncrement;

        // richiesta Ajax POST che comunica la galleria attuale e recupera le immagini
        ajaxUtils.ajaxSendRequest("./php/ajax/homepage_gallery_loader.php", function(response) {
            // controllo se ci sono stati problemi nel caricamento delle immagini
            if(response.errorCode != 0) {
                if(response.errorMsg == "empty gallery") {  // se la galleria selezionata è vuota
                    load_homepage_gallery.isGalleryLoaded = true; // setta come completamente caricata

                    var emptyElement = document.createElement("p"); // genero il placeholder per la galleria vuota
                    emptyElement.setAttribute("class", "gallery-empty-text button-text");
                    emptyElement.textContent = "The gallery doesn't contain any posts";
                    gallery.appendChild(emptyElement);

                    return emptyElement;
                }
                else return;    // errore fatale
            }

            var currentImage;   // conterrà l'immagine corrente
            for(var j=0; j<response.data.length; j++) {
                currentImage = response.data[j];     // recupero l'array contenente le informazioni relative all'immagine corrente
                var imageLink = document.createElement("a"); // genero il wrapper per l'immagine
                imageLink.setAttribute("href", "./image?id=" + (currentImage.imgName).replace(/\.[^.]+$/, ""));   // imposto il link per la visualizzazione
                imageLink.setAttribute("class", "gallery-image");    // imposto la classe corretta
                imageLink.setAttribute("style", "background-image: url(./resources/users/" + currentImage.imgAuthor +
                                                                                             "/gallery/" + currentImage.imgName);
                // TODO sostituire con versione cropped

                var imageInfo = document.createElement("div");  // genero lo slider contenente le info dell'immagine
                imageInfo.setAttribute("class", "gallery-image-info");  // imposto la classe corretta

                imageLink.appendChild(imageInfo);    // setto imageInfo come figlio di imageLink
                gallery.appendChild(imageLink);    // inserisco l'immagine nella galleria
            }
            
            // se le immagini sono state tutte caricate, setta come completamente caricata
            //if(response.data.length == 0) load_homepage_gallery.isGalleryLoaded = true;
            // se le immagini sono state tutte caricate, distruggi l'observer
            if(response.data.length == 0) galleryObs.unobserve(galleryDelimiter);

            return imageLink;
        }, JSON.stringify(galleryInfo));
    }
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", load_homepage_gallery);
else load_homepage_gallery();