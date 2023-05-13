//ハンバーガーメニュー
const ham = document.querySelector('#js-hamburger');
const nav = document.querySelector('#js-nav');

ham.addEventListener('click', function () {
    ham.classList.toggle('active');
    nav.classList.toggle('active');
});

//ヘッダー透過
$(document).ready(function() {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 0) {
        $('header').css('opacity', 0.8);
        } else {
        $('header').css('opacity', 1);
        }
    });
});