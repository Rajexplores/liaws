//Run on Load
window.addEventListener('load', function () {
    // Home Banner Image
    if(document.getElementById('homeBanner')){
        var folder = '/images/hero-images/ffs/hero-random-', bannerArr = [1,2,3,4,5];
        var bImgWidth = '';
        if(parseInt(screen.width) >= 450 && parseInt(screen.width) <= 1024){
            bImgWidth = 'med.jpg';
        }else if(parseInt(screen.width) > 1024){
            bImgWidth = 'lrg.jpg'; 
        }
    
        if (bImgWidth) {
            document.getElementById('homeBanner').style.backgroundImage = "url("+folder+bannerArr[Math.floor(Math.random()*bannerArr.length)]+bImgWidth+")";
        }
    }
});
/*
document.addEventListener( 'DOMContentLoaded', function () {
    new Splide( '#featured-franchises-slider', {
        type  : 'loop',
            perPage : 3,
            breakpoints: {
                '992': {
                    perPage: 2,
                    gap    : '1rem',
                },
                '767': {
                    perPage: 1,
                    gap    : '1rem',
                }
            }
    }).mount(); 

    new Splide( '#lowcost-slider', {
        type  : 'loop',
        perPage : 1,
        breakpoints: {
            '992': {
                perPage: 2,
                gap    : '1rem',
            },
            '767': {
                perPage: 1,
                gap    : '1rem',
            }
        }
    }).mount(); 

    new Splide( '#hotfran-slider', {
        type  : 'loop',
        perPage : 1,
        breakpoints: {
            '992': {
                perPage: 2,
                gap    : '1rem',
            },
            '767': {
                perPage: 1,
                gap    : '1rem',
            }
        }
    }).mount(); 

    new Splide( '#newFran-slider', {
        type  : 'loop',
        perPage : 1,
        breakpoints: {
            '992': {
                perPage: 2,
                gap    : '1rem',
            },
            '767': {
                perPage: 1,
                gap    : '1rem',
            }
        }
    }).mount(); 
     
});
*/