document.addEventListener("DOMContentLoaded", function () {
    const ratingImages = document.querySelectorAll('.ratingDisplay');

    ratingImages.forEach(function (ratingImage) {
        ratingImage.addEventListener('click', function (e) {
            const gameId = ratingImage.getAttribute('data-game-id');
            const ratingInput = document.querySelector(`.ratingInput[data-game-id='${gameId}']`);

            const rect = ratingImage.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const totalWidth = rect.width;

            const rating = Math.ceil((clickX / totalWidth) * 5);
            ratingInput.value = rating;
            ratingImage.src = `../../images/stars-${rating}.PNG`;
        });
    });
});
