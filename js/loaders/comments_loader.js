// Gestisce il caricamento dei commenti dell'immagine


function load_comments() {
    load_comments.commentInc = 1000;
    load_comments.commentCount = 0;
    load_comments.lastId = 0;
    load_comments.commentEnd = false;

    // Selettori per inviare i commenti
    const submitComment = document.querySelector(".submit-comment-button");
    const inputComment = document.querySelector("[name='comment']");
    const commentCounter = document.querySelector(".comment-counter");
    const commentFrame = document.querySelector(".comment-frame");
    const imageId = document.querySelector(".image-title").id.replace("like_", "");


    // event listener per inviare i commenti
    submitComment.addEventListener("click", () => {
        let commentInfo = {}
        commentInfo.commentText = inputComment.value;
        commentInfo.imageId = imageId;
        inputComment.value="";
        if(commentInfo.commentText) ajaxUtils.ajaxSendRequest("./php/ajax/comments_manager.php", response => {
            if(response.errorCode != 0) {
                // HANDLE ERROR
                if(response.errorCode == -2) {
                    // se l'utente non è loggato, rimandalo al login
                    window.location.href="./login";
                }
                else return null;   // fatal error
            }
            else {
                let firstComment = document.querySelector(".comment");

                let newComment = document.createElement("div");     // genero il commento
                newComment.setAttribute("class", "comment");    // imposto la classe commento

                let commentHeader = document.createElement("div");  // genero il contenitore per immagineProfilo+nomeAutore
                commentHeader.setAttribute("class", "comment-header");  // imposto la casse corretta

                let authorImgLink = document.createElement("a");     // genero il link per l'immagine profilo
                authorImgLink.setAttribute("href", "./profile?user="+response.data["authorName"]);  // imposto il collegamento al profilo
                authorImgLink.setAttribute("class", "comment-profile-image-link");  // imposto la classe corretta per il link

                let authorImg = document.createElement("img");   // genero l'immagine profilo
                authorImg.setAttribute("src", response.data["authorPImg"]);       // imposto il percorso dell'immagine profilo
                authorImg.setAttribute("class", "comment-profile-image");   // imposto la classe dell'immagine profilo
                authorImgLink.appendChild(authorImg);
                commentHeader.appendChild(authorImgLink);

                let authorName = document.createElement("a");    // genero il link per il nome profilo
                authorName.setAttribute("href", "./profile?user="+response.data["authorName"]);   // imposto il collegamento al profilo
                authorName.setAttribute("class", "comment-header-text");   // imposto la classe corretta
                authorName.textContent=response.data["authorName"];   // imposto il nome dell'autore
                commentHeader.appendChild(authorName);

                let commentDate = document.createElement("p");      // genero il testo per la data del commento
                commentDate.setAttribute("class", "comment-header-text");   // imposto la classe corretta
                commentDate.textContent = ": ("+response.data["commentDate"]+')';   // recupero la data del commento
                commentHeader.appendChild(commentDate);
                newComment.appendChild(commentHeader);  // inserisco l'header del commento nel nuovo commento

                let commentText = document.createElement("p");   // genero il paragrafo per il testo del commento
                commentText.setAttribute("class", "comment-text");   // imposto la classe comment-text
                commentText.textContent = response.data["commentText"];   // recupero il contenuto del commento
                newComment.appendChild(commentText);    // inserisco il testo nel nuovo commento

                let delimiter = document.createElement("div");   // genero il delimitatore dei commenti
                delimiter.setAttribute("class", "delimiter");
                newComment.appendChild(delimiter);      // completo il commento aggiungendo il delimitatore

                commentFrame.insertBefore(newComment, firstComment);
                commentCounter.textContent="Comments: "+response.isOwnGallery;
            }

            return;

        }, JSON.stringify(commentInfo));
    });

    inputComment.addEventListener("keydown", event => {
        if(event.keyCode === 13) submitComment.click();
    });

    // Selettori per caricare i commenti
    const footer = document.querySelector("footer");

    // creazione dell'intersection observer
    let options = {
        root: null,
        rootMargin: '0px',
        threshold: 1
    }
    const commentObs = new IntersectionObserver(intersectHandler, options);
    commentObs.observe(footer)

    // funzione che permette di caricare i commenti progressivamente quando si arriva a fine pagina
    function intersectHandler(entries) {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                loadComments();
            }
        });
    }

    // caricamento dei commenti
    function loadComments() {
        // se tutti i commenti sono stati caricati, ritorna
        if(load_comments.commentEnd) return;

        let commentInfo = {};
        commentInfo.counter = load_comments.commentCount;
        commentInfo.increment = load_comments.commentInc;
        commentInfo.imageId = imageId;

        // richiesta Ajax POST che comunica il numero di commenti attualmente caricati ed ottiene il resto
        ajaxUtils.ajaxSendRequest("./php/ajax/comments_loader.php", response => {

            // controllo se ci sono stati problemi nel caricamento dei commenti
            if(response.errorCode != 0) {
                if(response.errorMsg == "empty") {  // se non ci sono commenti
                    load_comments.commentEnd = true; // setta come completamente caricato
                    commentCounter.textContent="Comments: 0";
                    commentObs.unobserve(footer);

                    return;
                }
                else return null;    // errore fatale
            }

            // aggiorno il numero di dati richiesti finora
            load_comments.commentCount += response.data.length;

            let currentComment;     // conterrà il commento corrente
            for(let i=0; i<response.data.length; i++) {
                currentComment=response.data[i];    // scorciatoia al commento corrente

                let newComment = document.createElement("div");     // genero il commento
                newComment.setAttribute("class", "comment");    // imposto la classe commento

                let commentHeader = document.createElement("div");  // genero il contenitore per immagineProfilo+nomeAutore
                commentHeader.setAttribute("class", "comment-header");  // imposto la casse corretta

                let authorImgLink = document.createElement("a");     // genero il link per l'immagine profilo
                authorImgLink.setAttribute("href", "./profile?user="+currentComment.authorName);  // imposto il collegamento al profilo
                authorImgLink.setAttribute("class", "comment-profile-image-link");  // imposto la classe corretta per il link

                let authorImg = document.createElement("img");   // genero l'immagine profilo
                authorImg.setAttribute("src", currentComment.authorPImg);       // imposto il percorso dell'immagine profilo
                authorImg.setAttribute("class", "comment-profile-image");   // imposto la classe dell'immagine profilo
                authorImgLink.appendChild(authorImg);
                commentHeader.appendChild(authorImgLink);

                let authorName = document.createElement("a");    // genero il link per il nome profilo
                authorName.setAttribute("href", "./profile?user="+currentComment.authorName);   // imposto il collegamento al profilo
                authorName.setAttribute("class", "comment-header-text");   // imposto la classe corretta
                authorName.textContent=currentComment.authorName;   // imposto il nome dell'autore
                commentHeader.appendChild(authorName);

                let commentDate = document.createElement("p");      // genero il testo per la data del commento
                commentDate.setAttribute("class", "comment-header-text");   // imposto la classe corretta
                commentDate.textContent = ": ("+currentComment.commentDate+')';   // recupero la data del commento
                commentHeader.appendChild(commentDate);
                newComment.appendChild(commentHeader);  // inserisco l'header del commento nel nuovo commento

                let commentText = document.createElement("p");   // genero il paragrafo per il testo del commento
                commentText.setAttribute("class", "comment-text");   // imposto la classe comment-text
                commentText.textContent = currentComment.commentText;   // recupero il contenuto del commento
                newComment.appendChild(commentText);    // inserisco il testo nel nuovo commento

                let delimiter = document.createElement("div");   // genero il delimitatore dei commenti
                delimiter.setAttribute("class", "delimiter");
                newComment.appendChild(delimiter);      // completo il commento aggiungendo il delimitatore

                commentFrame.appendChild(newComment);   // inserisco il commento nella pagina
                commentCounter.textContent="Comments: "+response.isOwnGallery;
            }

            if(response.data.length==0) commentObs.unobserve(footer);

            return;
        }, JSON.stringify(commentInfo));
    }
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", load_comments);
else load_comments();