// Fa in modo che l'ombreggiatura alla navbar venga aggiunta dopo aver fatto scroll
function navbar_interaction() {
    const navbar = document.querySelector("nav");
    const header = document.querySelector("header");
    const galleryHeader = document.querySelector(".gallery-selector-frame");

    const userFrame = document.querySelector(".nav-profile-frame");
    const userMenu = document.querySelector(".user-menu-frame");

    window.addEventListener("scroll", function() {
        if(this.scrollY > 10) navbar.classList.add("active-nav");
        else navbar.classList.remove("active-nav");

        if(galleryHeader) {
            if(this.scrollY > header.offsetHeight) {
                galleryHeader.classList.add("gallery-selector-frame-scrolled");
            }
            else {
                galleryHeader.classList.remove("gallery-selector-frame-scrolled");
            }
        }
    });

    if(userFrame && userMenu) userFrame.addEventListener("mouseover", () => {
        userMenu.classList.remove("hidden");
    });

    if(userFrame && userMenu) navbar.addEventListener("mouseleave", () => {
        userMenu.classList.add("hidden");
    });

    if(userFrame && userMenu) document.addEventListener("click", (event) => {
        if(!(event.target.closest(".user-menu-frame"))) userMenu.classList.add("hidden");
    });

}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", navbar_interaction);
else navbar_interaction();