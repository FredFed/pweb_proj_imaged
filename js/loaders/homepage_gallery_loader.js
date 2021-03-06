// Gestisce il caricamento della galleria della Homepage via AJAX

// TODO fare in modo che le foto siano caricate su schermi di ogni res, ciclando all'inizio finché sub-margin è invisibile



function load_homepage_gallery() {
    load_homepage_gallery.postsIncrement = 6;
    load_homepage_gallery.postsCount = 0;
    load_homepage_gallery.isGalleryLoaded = false;

    const gallery = document.querySelector("#homepage-gallery");   // gallerie profilo
    const galleryDelimiter = document.querySelector(".gallery-delimiter-lower");  // delimitatore galleria profilo

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
        entries.forEach( entry => {
            // si attiva al raggiungimento del termine della galleria attiva
            if(entry.isIntersecting) {
                loadImages();
            }
        });
    }

    function loadImages() {
        // se la galleria selezionata è stata caricata completamente, non serve proseguire
        if(load_homepage_gallery.isGalleryLoaded) return;
        
        // creo un oggetto info galleria da passare mediante POST al server
        let galleryInfo = {};
        galleryInfo.count = load_homepage_gallery.postsCount;
        galleryInfo.increment = load_homepage_gallery.postsIncrement;

        // richiesta Ajax POST che comunica la galleria attuale e recupera le immagini
        ajaxUtils.ajaxSendRequest("./php/ajax/homepage_gallery_loader.php", response => {

            // controllo se ci sono stati problemi nel caricamento delle immagini
            if(response.errorCode != 0) {
                if(response.errorMsg == "empty gallery") {  // se la galleria selezionata è vuota
                    load_homepage_gallery.isGalleryLoaded = true; // setta come completamente caricata

                    let emptyElement = document.createElement("p"); // genero il placeholder per la galleria vuota
                    emptyElement.setAttribute("class", "gallery-empty-text button-text");
                    emptyElement.textContent = "The gallery doesn't contain any posts";
                    gallery.appendChild(emptyElement);

                    return emptyElement;
                }
                else return null;    // errore fatale
            }

            // aggiorno il numero di post richiesti finora
            load_homepage_gallery.postsCount += load_homepage_gallery.postsIncrement;

            let currentImage;   // conterrà l'immagine corrente
            for(let j=0; j<response.data.length; j++) {
                currentImage = response.data[j];     // recupero l'array contenente le informazioni relative all'immagine corrente

                let imageFrame = document.createElement("div"); // genero il frame dell'immagine
                imageFrame.setAttribute("class", "gallery-image-frame");    // imposto la classe cornice immagine

                let imageLink = document.createElement("a"); // genero il wrapper per l'immagine
                // imposto il link per la visualizzazione dell'immagine
                imageLink.setAttribute("href", "./image?id=" + (currentImage.imgName).replace(/\.[^.]+$/, ""));
                imageLink.setAttribute("class", "gallery-image");    // imposto la classe corretta
                imageLink.setAttribute("style", "background-image: url(./resources/users/" + currentImage.imgAuthorId +
                                                                                             "/gallery/" + currentImage.imgName+')');
                // TODO sostituire con versione cropped

                let imageInfo = document.createElement("div");  // genero lo slider contenente le info dell'immagine
                imageInfo.setAttribute("class", "gallery-image-info");  // imposto la classe corretta

                let imageGrip = document.createElement("div");    // genero il grip che mostra lo slider info
                imageGrip.setAttribute("class", "gallery-image-grip");  // imposto la classe corretta
                imageInfo.appendChild(imageGrip);   // appendo il grip allo slider dell'info

                let imageTitle = document.createElement("a");   // genero il titolo
                imageTitle.setAttribute("id", currentImage.imgId);  // salvo l'id immagine nel titolo
                // imposto il link per l'immagine
                imageTitle.setAttribute("href", "./image?id=" + (currentImage.imgName).replace(/\.[^.]+$/, ""));
                imageTitle.setAttribute("class", "gallery-image-title");    // imposto la classe titolo
                imageTitle.textContent = currentImage.imgTitle;  // recupero il titolo dall'immagine
                imageInfo.appendChild(imageTitle);  // appendo il titolo allo slider dell'info

                let imageDesc = document.createElement("p");    // genero la descrizione
                imageDesc.setAttribute("class", "gallery-image-desc");  // imposto la classe descrizione
                imageDesc.textContent = currentImage.imgDesc;    // recupero la descrizione dell'immagine
                imageInfo.appendChild(imageDesc);   // appendo la descrizione allo slider dell'info
                
                let imageAuth = document.createElement("a");    // genero l'autore con link per il suo profilo
                imageAuth.setAttribute("class", "gallery-image-auth");  // imposto la classe autore
                imageAuth.setAttribute("href", "./profile?user=" + currentImage.imgAuthorName); // imposto il link per il profilo
                imageAuth.textContent = "Author: " + currentImage.imgAuthorName;  // recupero l'autore dell'immagine
                imageInfo.appendChild(imageAuth);   // appendo la descrizione allo slider dell'info

                let likeButton = document.createElement("button");     // genero il pulsante like
                if(currentImage.isLiked) {  // se l'utente ha un like sull'immagine...
                    likeButton.setAttribute("class", "gallery-image-buttons liked");  // imposto la classe pulsante immagine
                    var likeIcon = document.createElement("i");     // genero l'icona
                    likeIcon.setAttribute("class", "bx bxs-heart");  // genero imposto la classe dell'icona
                }
                else {  // se l'utente non ha un like sull'immagine...
                    likeButton.setAttribute("class", "gallery-image-buttons");  // imposto la classe pulsante immagine
                    var likeIcon = document.createElement("i");     // genero l'icona
                    likeIcon.setAttribute("class", "bx bx-heart");  // genero imposto la classe dell'icona
                    likeIcon.setAttribute("background-image", './resources/icons/heart.svg');
                }
                likeButton.setAttribute("id", "like_"+currentImage.imgId);  // imposto l'ID del pulsante like al valore dell'ID dell'immagine
                likeButton.addEventListener("click", imageUtils.like);      // aggiungo il listener per l'evento like/unlike su click
                likeButton.appendChild(likeIcon);   // appendo l'icona al pulsante like
                imageInfo.appendChild(likeButton);  // appendo il pulsante like allo slider dell'info
                let likeCount = document.createElement("p");  // genero il contatore dei like
                likeCount.setAttribute("class", "gallery-image-counter");   // imposto la classe contatore like
                likeCount.textContent = currentImage.likeCount;     // recupero il numero esatto dei likes
                imageInfo.appendChild(likeCount);   // appendo il contatore like allo slider dell'info


                let saveButton = document.createElement("button");     // genero il pulsante save
                if(currentImage.isSaved) {  // se l'utente ha salvato l'immagine...
                    saveButton.setAttribute("class", "gallery-image-buttons saved");  // imposto la classe pulsante immagine
                    var saveIcon = document.createElement("i");     // genero l'icona
                    saveIcon.setAttribute("class", "bx bxs-bookmark");  // genero imposto la classe dell'icona
                }
                else {  // se l'utente non ha salvato l'immagine...
                    saveButton.setAttribute("class", "gallery-image-buttons");  // imposto la classe pulsante immagine
                    var saveIcon = document.createElement("i");     // genero l'icona
                    saveIcon.setAttribute("class", "bx bx-bookmark");  // genero imposto la classe dell'icona
                }
                saveButton.setAttribute("id", "save_"+currentImage.imgId);  // imposto l'ID del pulsante save al valore dell'ID dell'immagine
                saveButton.addEventListener("click", imageUtils.save);      // aggiungo il listener per l'evento save/unsave su click
                saveButton.appendChild(saveIcon);   // appendo l'icona al pulsante save
                imageInfo.appendChild(saveButton);  // appendo il pulsante save allo slider dell'info
                

                imageFrame.appendChild(imageLink);  // setto l'immagine con link come figlia di imageFrame
                imageFrame.appendChild(imageInfo);  // setto lo slider info come figlio di imageFrame
                gallery.appendChild(imageFrame);    // inserisco l'immagine nella galleria
            }

            // se le immagini sono state tutte caricate, distruggi l'observer
            if(response.data.length == 0) galleryObs.unobserve(galleryDelimiter);

            return;
        }, JSON.stringify(galleryInfo));
    }
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", load_homepage_gallery);
else load_homepage_gallery();