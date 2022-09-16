const navbar = document.querySelector('.navbar');

if (navbar) {
    window.addEventListener('scroll', () => {
        if (
            document.body.scrollTop > navbar.offsetHeight ||
            document.documentElement.scrollTop > navbar.offsetHeight
        ) {
            navbar.classList.add('fixed-top');
        } else {
            navbar.classList.remove('fixed-top');
        }
    });
}