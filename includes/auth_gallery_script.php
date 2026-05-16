<script>
(function(){
    var slides = $('.auth-gallery-slide');
    var dots = $('.auth-gallery-dot');
    var current = 0;
    var timer;

    function showSlide(index){
        if(!slides.length) return;
        current = index;
        slides.removeClass('active').eq(current).addClass('active');
        dots.removeClass('active').eq(current).addClass('active');
    }

    function nextSlide(){
        if(slides.length < 2) return;
        showSlide((current + 1) % slides.length);
    }

    dots.on('click', function(){
        showSlide(parseInt($(this).data('index'), 10));
        resetTimer();
    });

    function resetTimer(){
        clearInterval(timer);
        if(slides.length > 1){
            timer = setInterval(nextSlide, 4500);
        }
    }
    resetTimer();
})();
</script>
