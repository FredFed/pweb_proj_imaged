// Fa in modo che l'ombreggiatura alla navbar venga aggiunta dopo aver fatto scroll
function navbar_scroll() {
    const navbar = document.querySelector("nav");
    const header = document.querySelector("header");
    const galleryHeader = document.querySelector(".gallery-selector-frame");

    window.addEventListener("scroll", function() {
        if(this.scrollY > 10) navbar.classList.add("active-nav");
        else navbar.classList.remove("active-nav");

        if(this.scrollY > header.offsetHeight) {
            galleryHeader.classList.add("gallery-selector-frame-scrolled");
        }
        else {
            galleryHeader.classList.remove("gallery-selector-frame-scrolled");
        }
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", navbar_scroll);
else navbar_scroll();