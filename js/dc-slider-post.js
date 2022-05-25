class WidgetPostSwiper extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
        return {
            selectors: {
                container: '.dc-slider-post',
                slide: '.swiper-slide',
            },
            attributes: {
                dataSliderOptions: 'swiper-options',
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


	onInit(){ 
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

        this.initSlider();
	}
}

// When the frontend of Elementor is created, add our handler
jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( WidgetPostSwiper, {
            $element,
        } );
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/slider_post.default', addHandler );
} );