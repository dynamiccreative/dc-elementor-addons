(function($) {
	console.log('test dependance 2')
	/*const dcswiper = new Swiper('.dc-slider-swiper', {
		speed: 400,
		slidesPerView: 2,
		navigation: {
          nextEl: '.elementor-swiper-button-next',
          prevEl: '.elementor-swiper-button-prev',
        },
		on: {
          init: function () {
            console.log('swiper initialized');
          },
        },
	});*/

    
})(jQuery);

class WidgetGallerySwiper extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
        return {
            selectors: {
                container: '.dc-slider-swiper',
                slide: '.swiper-slide',
            },
            attributes: {
                dataSliderOptions: 'swiper-options',
                //dataAutoplayHover: 'autoplay-hover'
                dataText : 'text'
            }
        };
    }

	getDefaultElements() {
	    const selectors = this.getSettings( 'selectors' );
	        
	    
        var elements = {
            $swiperContainer: this.$element.find( selectors.container )
        };

        elements.$swiperSlides = elements.$swiperContainer.find(selectors.slide);

        return elements;
	}

	getSlidesCount() {
        return this.elements.$swiperSlides.length;
    }  

	/*onElementChange( propertyName ) {
		console.log("widget change")
	}
	bindEvents() {
		//this.elements.$some_text
		
	}*/

	initSlider() {
		var $slider = this.elements.$swiperContainer,
            settings = this.getSettings(),
            textTop = $slider.data(settings.attributes.dataText);

        if (!$slider.length) {
            return;
        }    

		this.swiper = new Swiper(
            $slider,
            $slider.data(this.getSettings('attributes.dataSliderOptions'))
        );
	}

	/*updateTestWidgetContent(){
		console.log(this.getElementSettings( 'nb_slide' ));
	}*/

	onInit(){ 
		//this.updateTestWidgetContent();
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

        this.initSlider();
	}

	/*onElementChange( propertyName ) {
		if ( 'nb_slide' === propertyName ) {
			this.updateTestWidgetContent();
		}
	}*/
}

// When the frontend of Elementor is created, add our handler
jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( WidgetGallerySwiper, {
            $element,
        } );
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/gallery_swiper.default', addHandler );
} );