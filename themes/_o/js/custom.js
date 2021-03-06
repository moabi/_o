jQuery(function () {

    var $ = jQuery,
        isModern = $('html').hasClass('lt-ie9');
    //VENDOR MENU
    $('.js-toggle-dashboard-menu').click(function (e) {
        $('.pure-menu').toggleClass('mobile-view');
    });

    //smooth scroll
    $('.js-smooth-scroll').on('click',function (e) {
        e.preventDefault();
        var page = $(this).attr('href'); // Page cible
        var speed = 750; // Durée de l'animation (en ms)
        $('html, body').animate( { scrollTop: $(page).offset().top - 110 }, speed ); // Go
        return false;
    });
    //$menu
    $('#menuToggle.one-target').click(function (e) {
        e.preventDefault();
        $('#site-navigation').toggleClass('active');
    });
    $(".lazy").lazyload({
        effect: "fadeIn"
    });
    var wpImg = $("a[href$='jpg'],a[href$='png'],a[href$='jpeg']").has('img');
    var wpImgTarget = $(wpImg).attr('href');
    if (wpImgTarget !== '#' || wpImgTarget !== '') {
        $(wpImg).magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
            image: {
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 300 // don't forget to change the duration also in CSS
            }
            // other options
        });
    }

    $("#mob-site-navigation.full-menu").mmenu();
    //DESKTOP -- BIND SCROLL TO MENU
    $("#top-menu a,a[href='#page-wrapper']").mPageScroll2id({
        highlightSelector: "#top-menu a",
        highlightClass: 'activeMenu',
        forceSingleHighlight: true,
        keepHighlightUntilNext: true,
        offset: 85,
        onStart: function () {
            $('#site-navigation').removeClass('active');
        }
    });
    /* jquery-hashchange fn */
    $(window).hashchange(function () {
        var loc = window.location,
            to = loc.hash.split("/")[1] || "#main-wrapper";
        $.mPageScroll2id("scrollTo", to, {
            clicked: $("a[href='" + loc + "'],a[href='" + loc.hash + "']")
        });
    });

    /*in view effect*/
    function oneAfterTheOther($this) {
        $this.find('.block').each(function () {
            var block = $(this),
                blockindex = block.index();
            setInterval(function () {
                block.addClass('animated fadeIn');
            }, 700 * blockindex);
        });
    }

    if (!isModern) {
        $('.inView').bind('inview', function (event, isInView, visiblePartX, visiblePartY) {
            if (isInView) {
                // element is now visible in the viewport
                var $this = $(this);
                oneAfterTheOther($this);
            } else {
                // element has gone out of viewport
            }
        });
    }

    //ajax post
    $('.ajax-popup-link').magnificPopup({
        type: 'ajax',
        ajax: {
            settings: null, // Ajax settings http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
            cursor: 'mfp-ajax-cur', // CSS class that will be added to body during the loading (adds "progress" cursor)
            tError: 'Sorry we could not load the content...'
        },
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = 'mfp-move-horizontal';
                $('.mfp-content').addClass('mfp-with-anim');
            },
            parseAjax: function (mfpResponse) {
                //console.log('Ajax content loaded:', mfpResponse);
            },
            ajaxContentAdded: function () {
                // Ajax content is loaded and appended to DOM
                //console.log(this.content);
            }
        },
        alignTop: false,
        overflowY: 'scroll' // as we know that popup content is tall we set scroll overflow by default to avoid jump
    });

    //carousel SLICK
    //enqueue script if exists

    var slickEl = $('.slick'),
        slickWitness = $('.slick-witness'),
        slickMulti = $('.slick-multi');
    if (slickEl.length) {
        slickEl.slick({
            autoplay: false,
            dots: true,
            arrows: false
        });
    }
    if (slickWitness.length) {

        slickWitness.slick({
            autoplay: true,
            dots: true,
            infinie: true,
            arrows: false,
            prevArrow: '<i class="fa fa-angle-left prevmulti"></i>',
            nextArrow: '<i class="fa fa-angle-right nextmulti"></i>',
            slidesToShow: 1,
            slidesToScroll: 1,
            adaptiveHeight: false
        });
    }
    if (slickMulti.length) {
        slickMulti.slick({
            autoplay: true,
            dots: false,
            arrows: true,
            prevArrow: '<i class="fa fa-angle-left prevmulti"></i>',
            nextArrow: '<i class="fa fa-angle-right nextmulti"></i>',
            slidesToShow: 3,
            slidesToScroll: 1,
            centerMode: true,
            centerPadding : '10px',
            responsive: [
                {
                    breakpoint: 900,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: false,
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
    /*FILTERING*/
    // init Isotope
    /*
    var $grid = $('.filteringContent').isotope({
        // options
        itemSelector: '.blocks_image',
        layoutMode: 'fitRows',
        transitionDuration: '0.8s'
    });*/
// filter items on button click
    /*
    $('.filtering').on('click', 'button', function () {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({filter: filterValue});
        $(this).addClass('current').siblings().removeClass('current');
    });*/
    /*Parallax window */
    $('.parallax-window').each(function () {
        $(this).parallax("50%", 0.2);
    });

    /*TABS*/

    $('.leftnav a').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        $target = $(this).attr('data-tab');
        $('.leftnav.blocks').addClass('active');
        $('.blocks_news.blocks .block_news.block.active').addClass('animated fadeOut');
        setTimeout(function () {
            $this.parent().addClass('active').siblings().removeClass('active');
            $($target).addClass('active animated fadein').siblings().removeClass('active animated fadein fadeOut');
        }, 500);
    });
    $('.fs1.closer').click(function (e) {
        e.preventDefault();
        $('.leftnav.blocks').removeClass('active');
    });
    $(window).resize(function () {
        $('.leftnav.blocks').removeClass('active');
    });


    //RENDER A MAP
    /*
     *  render_map
     *
     *  This function will render a Google Map onto the selected jQuery element
     *
     *  @type	function
     *  @date	8/11/2013
     *  @since	4.3.0
     *
     *  @param	$el (jQuery element)
     *  @return	n/a
     */
    markers_ob = {};
    map_ob = null;
    function render_map($el) {

        // var
        var $markers = $el.find('.marker');

        // vars
        var args = {
            scrollwheel: false,
            zoom: 12,
            center: new google.maps.LatLng(0, 0),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        // create map
        var map = new google.maps.Map($el[0], args);

        // add a markers reference
        map.markers = [];

        // add markers
        $markers.each(function () {
            add_marker($(this), map);
        });

        // center map
        center_map(map);

        return map;

    }



    /*
     *  add_marker
     *
     *  This function will add a marker to the selected Google Map
     *
     *  @type	function
     *  @date	8/11/2013
     *  @since	4.3.0
     *
     *  @param	$marker (jQuery element)
     *  @param	map (Google Map object)
     *  @return	n/a
     */

    function add_marker($marker, map) {

        // var
        var getlat = parseFloat($marker.attr('data-lat'));
        var getlng = parseFloat($marker.attr('data-lng'));
        var markerid = $marker.attr('id');
        console.log(markerid);

        var lat = ( getlat !== 'NaN' && getlat !== '' ) ? getlat : false;
        var lng = ( getlng !== 'NaN' && getlng !== '') ? getlng : false;

        if(lat && lng){

            //build lat&lng google obj
            var latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng'));

            //depending on class, get a circle or a marker
            if($marker.hasClass('circle-type')){
                var activityCenter = {lat: lat, lng: lng};
                var marker = new google.maps.Circle({
                    strokeColor: '#e88708',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#e88708',
                    fillOpacity: 0.35,
                    map: map,
                    center: activityCenter,
                    radius: 3000,
                    id : markerid
                });
            } else {
                // create marker
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    id : markerid
                });
            }
            // add to array
            map.markers.push(marker);
            markers_ob[markerid] = marker;
            map_ob = map;
            // if marker contains HTML, add it to an infoWindow
            if ($marker.html()) {
                // create info window
                var infowindow = new google.maps.InfoWindow({
                    content: $marker.html()
                });

                // show info window when marker is clicked
                google.maps.event.addListener(marker, 'click', function () {
                    if($marker.hasClass('circle-type')){
                        infowindow.setPosition(marker.getCenter());
                    }
                    infowindow.open(map, marker);
                });


            }
        }

    }


    /*
     *  center_map
     *
     *  This function will center the map, showing all markers attached to this map
     *
     *  @type	function
     *  @date	8/11/2013
     *  @since	4.3.0
     *
     *  @param	map (Google Map object)
     *  @return	n/a
     */

    function center_map(map) {

        // vars
        var bounds = new google.maps.LatLngBounds();

        // loop through all markers and create bounds
        $.each(map.markers, function (i, marker) {
            //depends if is circle or marker
            if(marker.center.lat()){
                var latlng = new google.maps.LatLng(marker.center.lat(), marker.center.lng());
            } else {
                var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
            }
            //extend bounds to obj
            bounds.extend(latlng);

        });

        // only 1 marker?
        if (map.markers.length == 1) {
            // set center of map
            map.setCenter(bounds.getCenter());
            map.setZoom(12);
        }
        else {
            // fit to bounds
            map.fitBounds(bounds);
        }

    }

    $maxExist = $('.acf-map').length;
    if ($maxExist) {
        $('.acf-map').each(function () {
            render_map($(this));
        });
    }
    

    /**
     * Toggle pure-menu
     */
    function toggleHorizontal() {
      $('.pure-menu').toggleClass('pure-menu-horizontal');
    }

     $('.mobile-only').on('click', function() {
       
       if(!$('.pure-menu').hasClass('mobile-view')) {
         setTimeout(toggleHorizontal, 350);
       }
       else {
        toggleHorizontal();
      }
     });
     
   // Toggle single-product - lieu sur plusieurs lignes
   
   $lieu  = $('.lieu');
   $lieuPlus  = $('.lieu-plus');
   $lieuH = $('.lieu-content').height();
   
   if( $lieuH > 45 ) {
     $lieuPlus.show();
   }
   
   $lieuPlus.on('click', function () {
     $lieu.toggleClass('open');
   });
   
   // Sidebar cart stick on scroll
   
  var $windowW = $(window).width();

if($windowW >1204) {
  
  $(window).scroll(function() {
    //console.log('scroll');
    if ($(this).scrollTop() > 120){  
        $('#side-stick').addClass("sticked");
      }
     if ($(this).scrollTop() <= 120) {
        $('#side-stick').removeClass("sticked");
      }

  });
}

});