document.addEventListener("DOMContentLoaded", () => {
    const sideMenu = document.querySelector("aside");
    const menuBtn = document.querySelector("#menu-btn");
    const closeBtn = document.querySelector("#close-btn");
    const themeToggler = document.querySelector(".theme-toggler");

    // Show sidebar
    menuBtn.addEventListener("click", () => {
        sideMenu.style.display = "block";
    });

    // Close sidebar
    closeBtn.addEventListener("click", () => {
        sideMenu.style.display = "none";
    });

    // Change theme and save preference
    themeToggler.addEventListener("click", () => {
        document.body.classList.toggle("dark-theme-variables");
        themeToggler.querySelector("span:nth-child(1)").classList.toggle("active");
        themeToggler.querySelector("span:nth-child(2)").classList.toggle("active");

        const currentTheme = document.body.classList.contains("dark-theme-variables") ? "dark" : "light";

        // Save theme preference via AJAX
        fetch("save_theme.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ theme: currentTheme })
        }).then(response => response.json())
          .then(data => console.log("Theme saved:", data))
          .catch(error => console.error("Error saving theme:", error));
    });
});