function form_button() {
    const button = document.querySelector(".back-button");

    window.addEventListener("keyup", (e) => {
        if(e.key == "Escape") button.click();
    });
}

if(document.readyState === "loading") document.addEventListener("DOMContentLoaded", form_button);
else form_button();