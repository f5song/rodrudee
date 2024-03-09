document.addEventListener("DOMContentLoaded", function () {
    window.addEventListener("scroll", function () {
        var navbar = document.getElementById("navbar");
        if (window.scrollY > 0) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });
});




let slideIndex = 0;
showSlides();

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("mySlides");

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slideIndex++;

    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    slides[slideIndex - 1].style.display = "block";

    setTimeout(showSlides, 4000); // Change the time delay as needed
}



document.addEventListener('scroll', function () {
    const body = document.body;
    const scrolled = window.scrollY > 0;
    if (scrolled) {
        body.classList.add('scrolled-down');
    } else {
        body.classList.remove('scrolled-down');
    }
});
