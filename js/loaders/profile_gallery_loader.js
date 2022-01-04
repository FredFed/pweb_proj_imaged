// Gestisce il caricamento della galleria pubblica, privata e degli elementi salvati via AJAX



function load_profile_gallery() {
    load_profile_gallery.postsIncrement = 12;
    load_profile_gallery.postsCount = [0, 0, 0];
    load_profile_gallery.isGallerySet = [false, false, false];
    load_profile_gallery.isGalleryLoaded = [false, false, false];

    const gallerySelectors = document.querySelectorAll(".gallery-selector");    // recupero i selettori delle gallerie profilo
    const galleries = document.querySelectorAll(".gallery");   // gallerie profilo
    const profileName = document.querySelector(".profile-name").textContent;    // recupero il nome profilo
    const galleryDelimiter = document.querySelector(".gallery-delimiter-lower");  // delimitatore galleria profilo
    var currentActiveGallery;     // conterrà il riferimento alla galleria correntemente attiva



    // Imposta la galleria profilo pubblica come predefinita da mostrare
    galleries.forEach(function(el) {el.classList.add("hidden"); loadImages(el)});
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

    // creazione dell'intersection observer
    let options = {
        root: null,
        rootMargin: '0px',
        threshold: 1
    }
    const galleryObs = new IntersectionObserver(intersectHandler, options);
    galleryObs.observe(galleryDelimiter);

    // funzione che permette di caricare immagini progressivamente quando ci si avvicina alla fine della galleria
    function intersectHandler(entries) {
        entries.forEach(function intersectionAction(el) {
            // si attiva all'intersezione della galleria attiva
            if(el.isIntersecting) {
                // controlla che tutte le gallerie siano pronte, altrimenti ritorna
                for(var i=0; i<galleries.length; i++) if(!load_profile_gallery.isGallerySet[i]) return;
                // controlla quale galleria è attiva per caricare le relative immagini
                currentActiveGallery = document.querySelector(".gallery:not(.hidden)");
                loadImages(currentActiveGallery);
            }
        })
    }

    function loadImages(currentActiveGallery) {
        // se la galleria selezionata è stata caricata completamente, non serve proseguire
        for(var currentGalleryIndex=0; currentGalleryIndex<galleries.length; currentGalleryIndex++)
            if(currentActiveGallery == galleries[currentGalleryIndex])
                if(load_profile_gallery.isGalleryLoaded[currentGalleryIndex]) return;
                else break;
        
        // creo un oggetto info galleria da passare mediante POST al server
        var galleryInfo = {};
        galleryInfo.user = profileName;
        galleryInfo.type = currentActiveGallery.id;
        galleryInfo.count = load_profile_gallery.postsCount[currentGalleryIndex];
        galleryInfo.increment = load_profile_gallery.postsIncrement;

        // aggiorno il numero di post richiesti finora
        load_profile_gallery.postsCount[currentGalleryIndex] += load_profile_gallery.postsIncrement;

        // richiesta Ajax POST che comunica la galleria attuale e recupera le immagini
        ajaxUtils.ajaxSendRequest("./php/ajax/profile_gallery_loader.php", function(response) {

            // controllo se ci sono stati problemi nel caricamento delle immagini
            if(response.errorCode != 0) {
                if(response.errorMsg == "empty gallery") {  // se la galleria selezionata è vuota
                    load_profile_gallery.isGalleryLoaded[currentGalleryIndex] = true; // setta come completamente caricata

                    if(response.isOwnGallery && currentGalleryIndex!=2) {     // è la propria galleria, mostra tasto upload
                        var emptyElement = document.createElement("a");   // genero il placeholder per la galleria vuota
                        emptyElement.setAttribute("href", "./upload");  // imposto il link alla pagina di upload
                        emptyElement.setAttribute("class", "gallery-empty-button button-text");    // assegno le relative classi
                        emptyElement.textContent = "Gallery empty, upload some content! "; // aggiungo il contenuto testuale
                        var iconEmpty = document.createElement("i");    // aggiungo l'icona
                        iconEmpty.setAttribute("class", "bx bx-images");
                        emptyElement.appendChild(iconEmpty);    // assegno l'icona come child dell'elemento vuoto
                        currentActiveGallery.appendChild(emptyElement); // appendo l'elemento vuoto alla galleria
                        return emptyElement;
                    }
                    // è la galleria degli elementi salvati, mostra un semplice messaggio testuale
                    else if(response.isOwnGallery && currentGalleryIndex==2) {
                        var emptyElement = document.createElement("p"); // genero il placeholder per la galleria vuota
                        emptyElement.setAttribute("class", "gallery-empty-text button-text");
                        emptyElement.textContent = "You haven't saved any element yet";
                        currentActiveGallery.appendChild(emptyElement);
                        return emptyElement;
                    }
                    else {  // non è la propria galleria, mostra un semplice messaggio testuale
                        var emptyElement = document.createElement("p"); // genero il placeholder per la galleria vuota
                        emptyElement.setAttribute("class", "gallery-empty-text button-text");
                        emptyElement.textContent = "This gallery doesn't contain any posts";
                        currentActiveGallery.appendChild(emptyElement);
                        return emptyElement;
                    }
                }
                else return null;    // errore fatale
            }

            var currentImage;   // conterrà l'immagine corrente
            for(var j=0; j<response.data.length; j++) {
                currentImage = response.data[j];     // recupero l'array contenente le informazioni relative all'immagine corrente

                var imageFrame = document.createElement("div"); // genero il frame dell'immagine
                imageFrame.setAttribute("class", "gallery-image-frame");    // imposto la classe cornice immagine

                var imageLink = document.createElement("a"); // genero il wrapper per l'immagine
                imageLink.setAttribute("href", "./image?id=" + (currentImage.imgName).replace(/\.[^.]+$/, ""));   // imposto il link per la visualizzazione
                imageLink.setAttribute("class", "gallery-image");    // imposto la classe corretta
                imageLink.setAttribute("style", "background-image: url(./resources/users/" + currentImage.imgAuthorId +
                                                                                             "/gallery/" + currentImage.imgName);
                // TODO sostituire con versione cropped

                var imageInfo = document.createElement("div");  // genero lo slider contenente le info dell'immagine
                imageInfo.setAttribute("class", "gallery-image-info");  // imposto la classe corretta

                var imageGrip = document.createElement("div");    // genero il grip che mostra lo slider info
                imageGrip.setAttribute("class", "gallery-image-grip");  // imposto la classe corretta
                imageInfo.appendChild(imageGrip);   // appendo il grip allo slider dell'info
                
                var imageTitle = document.createElement("a");   // genero il titolo
                // imposto il link per l'immagine
                imageTitle.setAttribute("href", "./image?id=" + (currentImage.imgName).replace(/\.[^.]+$/, ""));
                imageTitle.setAttribute("class", "gallery-image-title");    // imposto la classe titolo
                imageTitle.textContent = currentImage.imgTitle;  // recupero il titolo dall'immagine
                imageInfo.appendChild(imageTitle);  // appendo il titolo allo slider dell'info

                var imageDesc = document.createElement("p");    // genero la descrizione
                imageDesc.setAttribute("class", "gallery-image-desc");  // imposto la classe descrizione
                imageDesc.textContent = currentImage.imgDesc;    // recupero la descrizione dell'immagine
                imageInfo.appendChild(imageDesc);   // appendo la descrizione allo slider dell'info


                var likeButton = document.createElement("button");     // genero il pulsante like
                if(currentImage.isLiked) {
                    likeButton.setAttribute("class", "gallery-image-buttons liked");  // imposto la classe pulsante immagine
                    var likeIcon = document.createElement("i");     // genero l'icona
                    likeIcon.setAttribute("class", "bx bxs-heart");  // genero imposto la classe dell'icona
                }
                else {
                    likeButton.setAttribute("class", "gallery-image-buttons");  // imposto la classe pulsante immagine
                    var likeIcon = document.createElement("i");     // genero l'icona
                    likeIcon.setAttribute("class", "bx bx-heart");  // genero imposto la classe dell'icona
                }
                likeButton.setAttribute("id", "like_"+currentImage.imgId);  // imposto l'ID del pulsante like al valore dell'ID dell'immagine
                likeButton.addEventListener("click", imageUtils.like);      // aggiungo il listener per l'evento like/unlike su click
                likeButton.appendChild(likeIcon);   // appendo l'icona al pulsante like
                imageInfo.appendChild(likeButton);  // appendo il pulsante like allo slider dell'info

                if(!currentImage.isOwnImage) {  // se l'utente non è il proprietario dell'immagine, visualizza il pulsante save
                    var saveButton = document.createElement("button");     // genero il pulsante save
                    if(currentImage.isSaved) {
                        saveButton.setAttribute("class", "gallery-image-buttons saved");  // imposto la classe pulsante immagine
                        var saveIcon = document.createElement("i");     // genero l'icona
                        saveIcon.setAttribute("class", "bx bxs-bookmark");  // genero imposto la classe dell'icona
                    }
                    else {
                        saveButton.setAttribute("class", "gallery-image-buttons");  // imposto la classe pulsante immagine
                        var saveIcon = document.createElement("i");     // genero l'icona
                        saveIcon.setAttribute("class", "bx bx-bookmark");  // genero imposto la classe dell'icona
                    }
                    saveButton.setAttribute("id", "save_"+currentImage.imgId);  // imposto l'ID del pulsante save al valore dell'ID dell'immagine
                    saveButton.addEventListener("click", imageUtils.save);      // aggiungo il listener per l'evento save/unsave su click
                    saveButton.appendChild(saveIcon);   // appendo l'icona al pulsante save
                    imageInfo.appendChild(saveButton);  // appendo il pulsante save allo slider dell'info
                }


                imageFrame.appendChild(imageLink);  // setto l'immagine con link come figlia di imageFrame
                imageFrame.appendChild(imageInfo);  // setto lo slider info come figlio di imageFrame
                currentActiveGallery.appendChild(imageFrame);    // inserisco l'immagine nella galleria
                
            }
            
            // se le immagini sono state tutte caricate, setta la galleria come completamente caricata
            if(response.data.length == 0) {     // TODO implementare galleria saved
                load_profile_gallery.isGalleryLoaded[currentGalleryIndex] = true;
                // se tutte le gallerie sono state completamente caricate, distruggi l'observer
                if( load_profile_gallery.isGalleryLoaded[0] &&
                    load_profile_gallery.isGalleryLoaded[1])
                        galleryObs.unobserve(galleryDelimiter);
            }

            // imposta la galleria come "set" (primo caricamento completato)
            load_profile_gallery[currentGalleryIndex] = true;

            return imageLink;
        }, JSON.stringify(galleryInfo));
    }
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", load_profile_gallery);
else load_profile_gallery();