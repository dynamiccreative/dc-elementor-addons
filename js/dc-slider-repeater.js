
class WidgetSliderRepeater extends elementorModules.frontend.handlers.SwiperBase {

	getDefaultSettings() {
        return {
            selectors: {
                container: '.dc-slider-repeater',
                slide: '.swiper-slide',
            },
            attributes: {
                dataSliderOptions: 'swiper-options',
            },
            slidesPerView: {
                widescreen: 3,
                desktop: 3,
                laptop: 3,
                tablet_extra: 3,
                tablet: 2,
                mobile_extra: 2,
                mobile: 1
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

    getDeviceSlidesPerView(device) {
        const slidesPerViewKey = 'nb_slide' + ('desktop' === device ? '' : '_' + device);
        console.log(slidesPerViewKey, Math.min(this.getSlidesCount(), +this.getElementSettings(slidesPerViewKey) || this.getSettings('slidesPerView')[device]), this.getSlidesCount(), this.getElementSettings(slidesPerViewKey), this.getSettings('slidesPerView')[device])
        return Math.min(this.getSlidesCount(), +this.getElementSettings(slidesPerViewKey) || this.getSettings('slidesPerView')[device]);
      }

    getSlidesPerView(device) {
        return this.getDeviceSlidesPerView(device);
    }

    getSpaceBetween(device) {
        let propertyName = 'padding_slide';
        if (device && 'desktop' !== device) {
            propertyName += '_' + device;
        }
        return this.getElementSettings(propertyName).size || 0;
    }


	initSlider() {
        var $slider = this.elements.$swiperContainer,
            settings = this.getSettings();
            //textTop = $slider.data(settings.attributes.dataText);
        const swiperOptions = $slider.data(this.getSettings('attributes.dataSliderOptions'));
        swiperOptions.slidesPerView = this.getSlidesPerView('desktop');
        swiperOptions.spaceBetween = this.getSpaceBetween();
        //swiperOptions.initialSlide = this.getInitialSlide();
        swiperOptions.handleElementorBreakpoints = true;
        console.log(swiperOptions.handleElementorBreakpoints)


        const breakpointsSettings = {},
        breakpoints = elementorFrontend.config.responsive.activeBreakpoints;
        console.log(breakpoints)
        /*Object.keys(breakpoints).forEach(breakpointName => { console.log(':', breakpointName, breakpoints[breakpointName].value)
            breakpointsSettings[breakpoints[breakpointName].value] = {
                slidesPerView: this.getSlidesPerView(breakpointName),
                //slidesPerGroup: this.getSlidesToScroll(breakpointName),
                spaceBetween: this.getSpaceBetween(breakpointName)
            };
        });*/

        breakpointsSettings[320] = {
            slidesPerView: this.getSlidesPerView("mobile"),
            spaceBetween: this.getSpaceBetween("mobile")
        };
        breakpointsSettings[768] = {
            slidesPerView: this.getSlidesPerView("tablet"),
            spaceBetween: this.getSpaceBetween("tablet")
        };
        breakpointsSettings[1024] = {
            slidesPerView: this.getSlidesPerView("desktop"),
            spaceBetween: this.getSpaceBetween("desktop")
        };

        swiperOptions.breakpoints = breakpointsSettings;

        console.log(breakpointsSettings)
        //console.log('settings :: ',this.getElementSettings('style_pagination'))


		

        if (!$slider.length) {
            return;
        }    

		this.swiper = new Swiper(
            $slider,
            swiperOptions
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
        elementorFrontend.elementsHandler.addHandler( WidgetSliderRepeater, {
            $element,
        } );
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/slider_repeater.default', addHandler );
} );