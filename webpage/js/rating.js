document.addEventListener("DOMContentLoaded", function () {
    const ratingImage = document.getElementById('ratingDisplay');
    const ratingInput = document.getElementById('rating');

    if (!ratingImage || !ratingInput) return;

    ratingImage.addEventListener('click', function (e) {
        const rect = ratingImage.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const totalWidth = rect.width;

        const rating = Math.ceil((clickX / totalWidth) * 5);
        ratingInput.value = rating;
        ratingImage.src = `../../images/stars-${rating}.PNG`;
    });
});
