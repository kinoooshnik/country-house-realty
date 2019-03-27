var win = $(window);
var slider = $('#gallery-t-group').royalSlider({
    controlNavigation: 'thumbnails',
    thumbs: {
        fitInViewport: (win.width() < 760) ? false : true,
        autoCenter: true,
        arrows: false,
        orientation: 'vertical',
        paddingBottom: 0,
        paddingTop: 0,
        spacing: 1,
        firstMargin: false,
        columns: 3
    },
    deeplinking: {
        enabled: true,
        change: true,
        prefix: 'image-'
    },
    // fullscreen: {
    //     // fullscreen options go gere
    //     enabled: true,
    //     nativeFS: false
    // },
    globalCaption: false,
    numImagesToPreload: 2,
    fadeinLoadedSlide: false,
    imageAlignCenter: true,
    imageScaleMode: 'fit',
    transitionType: 'move',
    transitionSpeed: 400,
    autoScaleSlider: true,
    autoScaleSliderWidth: 400,
    autoScaleSliderHeight: 400,
    imageScalePadding: 0,
    loop: true,
    arrowsNav: false,
    keyboardNavEnabled: true,
}).data('royalSlider');

var counter = document.getElementById('slider-counter');
var viewer = document.getElementsByClassName('rsOverflow grab-cursor')[0];
viewer.appendChild(counter);
var num = slider.currSlideId;
counter.innerText = (num + 1) + " / " + slider.numSlides;
slider.ev.on('rsBeforeMove', function (event, type, userAction) {
    num = slider.currSlideId;
    switch (type) {
        case 'next':
            num++;
            break;
        case 'prev':
            num--;
            break;
        default:
            num = type;
            break;
    }
    if (num === slider.numSlides) {
        num = 0;
    }
    counter.innerText = (num + 1) + " / " + slider.numSlides;
});
win.resize(function () {
    slider.updateThumbsSize();
    if (win.width() < 760) {
        slider.st.thumbs.fitInViewport = false;
    } else {
        slider.st.thumbs.fitInViewport = true;
    }
});
$('#btn').click(function () {
    console.log('click');
    return false;
});