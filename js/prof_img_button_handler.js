// Fa in modo che al caricamento dell'immagine profilo il form venga automaticamente inviato
function prof_img_button_handler() {
    const inputButton = document.querySelector("#up-img-pic");
    const submitButton = document.querySelector("#sub-img-pic");
    const profImgFrame = document.querySelector(".change-prof-img-frame");

    inputButton.addEventListener("input", function() {
        if(inputButton.value) {
            submitButton.click();
        }
    });

    profImgFrame.addEventListener("click", function() {
        inputButton.click();
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", prof_img_button_handler);
else prof_img_button_handler();