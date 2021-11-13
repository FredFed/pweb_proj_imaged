// Gestisce il caricamento della galleria pubblica, privata e degli elementi salvati via AJAX



function load_profile_gallery() {
    load_profile_gallery.postsIncrement = 2;
    load_profile_gallery.postsCount = [0, 0, 0];
    load_profile_gallery.isGalleryLoaded = [false, false, false];
    load_profile_gallery.isInProgress = false;

    const gallerySelectors = document.querySelectorAll(".gallery-selector");    // recupero i selettori delle gallerie profilo
    const galleries = document.querySelectorAll(".gallery");   // gallerie profilo
    var currentActiveGallery;     // conterrà il riferimento alla galleria correntemente attiva


    

    // Imposta la galleria profilo pubblica come predefinita da mostrare
    galleries.forEach(function(el) {el.classList.add("hidden");});
    gallerySelectors[0].classList.add("gallery-selected");
    galleries[0].classList.remove("hidden");

    // aggiungo event listeners ad ogni selettore per mostrare soltanto la galleria selezionata
    for(var i=0; i<gallerySelectors.length; i++) {
        gallerySelectors[i].addEventListener("click", function() {
            for(var j=0; j<gallerySelectors.length; j++) {
                if(gallerySelectors[j] === this) {
                    gallerySelectors[j].classList.add("gallery-selected");
                    gallerySelectors[j].blur();
                    galleries[j].classList.remove("hidden");
                }
                else {
                    gallerySelectors[j].classList.remove("gallery-selected");
                    galleries[j].classList.add("hidden");
                }
            }
        });
    }

    // event listener che carica le immagini quando si scrolla in fondo
    window.addEventListener("scroll", function() {
        // se una stessa richiesta di caricamento è in corso, non accettarne altre
        if(load_profile_gallery.isInProgress) return;
        else load_profile_gallery.isInProgress = true;

        // controlla se si è alla fine della pagina
        if((window.scrollY + window.innerHeight) > (document.body.clientHeight / 2)) {

            // controlla quale galleria è attiva
            currentActiveGallery = document.querySelector(".gallery:not(.hidden)");
            console.log(currentActiveGallery.id);   // TODO remove

            // se la galleria selezionata è stata caricata completamente, non serve proseguire
            for(var i=0; i<galleries.length; i++)
                if(currentActiveGallery == galleries[i])
                    if(load_profile_gallery.isGalleryLoaded[i]) {load_profile_gallery.isInProgress = false; return;}
                    else break;
            
            // creo un oggetto info galleria da passare mediante POST al server
            var galleryInfo = {};
            galleryInfo.type = currentActiveGallery.id;
            galleryInfo.count = load_profile_gallery.postsCount[i];
            galleryInfo.increment = load_profile_gallery.postsIncrement;

            // aggiorno il numero di post richiesti finora
            load_profile_gallery.postsCount[i] += load_profile_gallery.postsIncrement;

            // richiesta Ajax POST che comunica la galleria attuale e recupera le immagini
            ajaxUtils.ajaxSendRequest("./php/ajax/profile_gallery_loader.php", function(response) {
                console.log(response);  // TODO remove
                console.log(response.data);
                // controllo se ci sono stati problemi nel caricamento delle immagini
                if(response.errorCode != 0) {
                    if(response.errorMsg == "empty gallery") {  // se la galleria selezionata è vuota
                        load_profile_gallery.isGalleryLoaded[i] = true; // setta come completamente caricata

                        var emptyElement = document.createElement("a");   // genero il placeholder per la galleria vuota
                        emptyElement.setAttribute("href", "./post");
                        emptyElement.setAttribute("class", "gallery-empty button-text");
                        emptyElement.textContent = "Upload some content!";
                        var iconEmpty = document.createElement("i");
                        iconEmpty.setAttribute("class", "bx bx-images");
                        emptyElement.appendChild(iconEmpty);
                        currentActiveGallery.appendChild(emptyElement);

                        // TODO remove
                        console.log(emptyElement);
                        console.log(iconEmpty);

                        load_profile_gallery.isInProgress = false;
                        return emptyElement;
                    }
                    else {  // errore fatale
                        load_profile_gallery.isInProgress = false;
                        return; // TODO gestisci errore
                    }
                }

                var currentImage;   // conterrà l'immagine corrente
                for(var j=0; j<response.data.length; j++) {
                    currentImage = response.data[j];     // recupero l'array contenente le informazioni relative all'immagine corrente
                    var imageLink = document.createElement("a"); // genero il wrapper per l'immagine
                    imageLink.setAttribute("href", "./post?" + currentImage.imgName);   // imposto il link per la visualizzazione
                    imageLink.setAttribute("class", "gallery-image");    // imposto la classe corretta
                    imageLink.setAttribute("style", "background-image: url(./resources/gallery/" + currentImage.imgName);
                    // TODO sostituire con versione cropped

                    var imageInfo = document.createElement("div");  // genero lo slider contenente le info dell'immagine
                    imageInfo.setAttribute("class", "gallery-image-info");  // imposto la classe corretta

                    imageLink.appendChild(imageInfo);    // setto imageInfo come figlio di imageLink
                    currentActiveGallery.appendChild(imageLink);    // inserisco l'immagine nella galleria
                }
                
                // se le immagini sono state tutte caricate, setta come completamente caricata
                if(response.data.length <= load_profile_gallery.postsCount[i]) load_profile_gallery.isGalleryLoaded[i] = true;

                load_profile_gallery.isInProgress = false;
                return imageLink;
            }, JSON.stringify(galleryInfo));
        }
    })
    window.scroll();    // avvia l'evento per caricare le prime immagini al caricamento della pagina
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", load_profile_gallery);
else load_profile_gallery();