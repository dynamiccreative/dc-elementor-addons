class WidgetScrollmagic extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
        return {
            selectors: {
                wrapper: '.dc-scrollmagic',
                loader: '.sm-loader',
            },
            attributes: {
                dataImages: 'images',
                dataTrigger: 'trigger',
                dataRepaires: 'repaires',
                dataDurations: 'durations'
            }
        };
    }

	getDefaultElements() {
	    const selectors = this.getSettings( 'selectors' );
        var elements = {
            $smWrapper: this.$element.find( selectors.wrapper ),
            $smLoader: this.$element.find( selectors.loader )
        };
        return elements;
	}

	initSlider() {
		var $sm = this.elements.$smWrapper,
			$loader = this.elements.$smLoader,
            settings = this.getSettings(),
            images = $sm.data(settings.attributes.dataImages),
            durations = $sm.data(settings.attributes.dataDurations),
            duration = durations[0],
            repaires = $sm.data(settings.attributes.dataRepaires),
            w = jQuery( window ).width(),
            trigger = $sm.data(settings.attributes.dataTrigger);

        // hauteur trigger responsive
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

        // prelaod
		function preloadImages(urls, allImagesLoadedCallback){
			var loadedCounter = 0;
			var toBeLoadedNumber = urls.length;
			urls.forEach(function(url){
				preloadImage(url, function(){
					loadedCounter++;
					console.log('Number of loaded images: ' + loadedCounter);
					if(loadedCounter == toBeLoadedNumber){
						allImagesLoadedCallback();
					}
				});
			});
			function preloadImage(url, anImageLoadedCallback){
				var img = new Image();
				img.onload = anImageLoadedCallback;
				img.src = url;
			}
		}

		preloadImages(images, function(){
			console.log('All images were loaded');
			$loader.hide();
		});

		// animation
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
            // debug            
            if (repaires == 'yes') scene.addIndicators();
	}

	onInit(){ 
		elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

        this.initSlider();
	}

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