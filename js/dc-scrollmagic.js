class WidgetScrollmagic extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
        return {
            selectors: {
                container: '.dc-scrollmagic',
            },
            attributes: {
                dataImages: 'images',
                dataTrigger: 'trigger',
                //dataDuration: 'duration',
                dataRepaires: 'repaires',
                dataDurations: 'durations'
            }
        };
    }

	getDefaultElements() {
	    const selectors = this.getSettings( 'selectors' );
	        
	    
        var elements = {
            $smContainer: this.$element.find( selectors.container )
        };


        return elements;
	}


	initSlider() {
		var $sm = this.elements.$smContainer,
            settings = this.getSettings(),
            images = $sm.data(settings.attributes.dataImages),
            //duration = $sm.data(settings.attributes.dataDuration),
            durations = $sm.data(settings.attributes.dataDurations),
            duration = durations[0],
            repaires = $sm.data(settings.attributes.dataRepaires),
            trigger = $sm.data(settings.attributes.dataTrigger);
           

        //var hWrapper = $sm.height();
        //var r = duration / hWrapper;
        //console.log(durations) 
        var w = jQuery( window ).width();

        switch (true) {
        	case w > 1024:
        		duration = durations[0];
        		break;
        	case w > 767 && w <= 1024:	
        		duration = durations[1];
        		break;
        	case w > 319 && w < 768:	
        	    duration = durations[2];
        		break;
        }
        if (!duration) duration = 300;
        /*if (!$slider.length) {
            return;
        }  */  

		/*this.swiper = new Swiper(
            $slider,
            $slider.data(this.getSettings('attributes.dataSliderOptions'))
        );*/

        //var images = this.getSettings('attributes.dataImages');
        console.log(duration, trigger, repaires)
        //var el = $sm.getElementsByClassName('myimg')[0];
        var obj = {curImg: 0};

            var tween = gsap.to(obj, 0.5,
            {
                curImg: images.length - 1,  // animate propery curImg to number of images
                roundProps: "curImg",               // only integers so it can be used as an array index
                //repeat: -1,                                  // repeat 3 times
                immediateRender: true,          // load first image automatically
                ease: Linear.easeNone,          // show every image the same ammount of time
                onUpdate: function () {
                  jQuery('.myimg', $sm).attr("src", images[obj.curImg]); // set the image source
                  //console.log(obj.curImg)
                }
            }
            );

            // init controller
            var controller = new ScrollMagic.Controller();

            // build scene
            var scene = new ScrollMagic.Scene({triggerElement: trigger, duration: duration})
                        .setTween(tween)
                        .addTo(controller);
            if (repaires == 'yes') scene.addIndicators();

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
        elementorFrontend.elementsHandler.addHandler( WidgetScrollmagic, {
            $element,
        } );
    };

    elementorFrontend.hooks.addAction( 'frontend/element_ready/scrollmagic.default', addHandler );
} );