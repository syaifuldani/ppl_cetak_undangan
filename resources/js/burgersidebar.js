window.onload = function() {
    const burger = document.getElementById('burger');
    const navLinks = document.getElementById('nav-links');
    const centerItems = document.querySelector('.center-items');
    let menuOpen = false;

    burger.addEventListener('click', () => {
        if (!menuOpen) {
            navLinks.classList.remove('nav-close');
            navLinks.classList.add('nav-active');
            centerItems.style.display = 'block'; // Tampilkan center-items
        } else {
            navLinks.classList.remove('nav-active');
            navLinks.classList.add('nav-close');
            centerItems.style.display = 'none'; // Sembunyikan center-items
        }
        menuOpen = !menuOpen;
        burger.classList.toggle('toggle');
    });
};
