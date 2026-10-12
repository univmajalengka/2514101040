// =========================
// HERO SLIDESHOW
// =========================

const slides = document.querySelectorAll(".hero-slide");

let currentSlide = 0;

setInterval(() => {
    slides[currentSlide].classList.remove("active");

    currentSlide++;

    if (currentSlide >= slides.length) {
        currentSlide = 0;
    }

    slides[currentSlide].classList.add("active");

}, 5000);


// =========================
// VIDEO DESTINASI SLIDER
// =========================

const videoSlider = document.querySelector(".video-slider");
const videoCards = document.querySelectorAll(".video-card");
const videoNext = document.getElementById("video-next");
const videoPrev = document.getElementById("video-prev");

let videoIndex = 0;

console.log("Jumlah video card:", videoCards.length);

function updateVideoSlider() {

    const cardWidth = videoCards[0].offsetWidth;
    const gap = 25;

    videoSlider.style.transform =
        `translateX(-${videoIndex * (cardWidth + gap)}px)`;
}

videoNext.addEventListener("click", () => {

    if (videoIndex < videoCards.length - 3) {
        videoIndex++;
        updateVideoSlider();
    }

});

videoPrev.addEventListener("click", () => {

    if (videoIndex > 0) {
        videoIndex--;
        updateVideoSlider();
    }

});

// =========================
// DESTINASI SLIDER
// =========================

const destinationSlider = document.querySelector(".destination-slider");
const destinationCards = document.querySelectorAll(".destination-card");
const destinationNext = document.getElementById("destination-next");
const destinationPrev = document.getElementById("destination-prev");

let destinationIndex = 0;

function updateDestinationSlider() {

    const cardWidth = destinationCards[0].offsetWidth;
    const gap = 25;

    destinationSlider.style.transform =
        `translateX(-${destinationIndex * (cardWidth + gap)}px)`;
}

destinationNext.addEventListener("click", () => {

    if (destinationIndex < destinationCards.length - 3) {
        destinationIndex++;
        updateDestinationSlider();
    }

});

destinationPrev.addEventListener("click", () => {

    if (destinationIndex > 0) {
        destinationIndex--;
        updateDestinationSlider();
    }

});

// =========================
// SCROLL REVEAL
// =========================

const revealItems = document.querySelectorAll(".reveal-item");

const revealObserver = new IntersectionObserver((entries) => {

    entries.forEach((entry) => {

        if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
        }

    });

}, {
    threshold: 0.15
});

revealItems.forEach((item) => {
    revealObserver.observe(item);
});