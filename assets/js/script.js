window.onload = function() {
	var nav = document.querySelector('nav');

    // Event click -> show the navigation
    document.querySelector('#menu-hamburger').onclick = function(e) {
        e.stopPropagation();
        nav.classList.toggle('open');
    };

    // Event click -> hide the navigation
    document.querySelector('body').onclick = function(e) {
        if (nav.classList.contains('open')) {
            nav.classList.remove('open');
        }
    };
};