/**
 * bxSlider v4.2.12
 * Copyright 2013-2015 Steven Wanderski
 * Written while drinking Belgian ales and listening to jazz
 * Licensed under MIT (http://opensource.org/licenses/MIT)
 */

;(function($) {

  var defaults = {

    // GENERAL
    mode: 'horizontal',
    slideSelector: '',
    infiniteLoop: true,
    hideControlOnEnd: false,
    speed: 500,
    easing: null,
    slideMargin: 0,
    startSlide: 0,
    randomStart: false,
    captions: false,
    ticker: false,
    tickerHover: false,
    adaptiveHeight: false,
    adaptiveHeightSpeed: 500,
    video: false,
    useCSS: true,
    preloadImages: 'visible',
    responsive: true,
    slideZIndex: 50,
    wrapperClass: 'bx-wrapper',

    // TOUCH
    touchEnabled: true,
    swipeThreshold: 50,
    oneToOneTouch: true,
    preventDefaultSwipeX: true,
    preventDefaultSwipeY: false,

    // ACCESSIBILITY
    ariaLive: true,
    ariaHidden: true,

    // KEYBOARD
    keyboardEnabled: false,

    // PAGER
    pager: true,
    pagerType: 'full',
    pagerShortSeparator: ' / ',
    pagerSelector: null,
    buildPager: null,
    pagerCustom: null,

    // CONTROLS
    controls: true,
    nextText: 'Next',
    prevText: 'Prev',
    nextSelector: null,
    prevSelector: null,
    autoControls: false,
    startText: 'Start',
    stopText: 'Stop',
    autoControlsCombine: false,
    autoControlsSelector: null,

    // AUTO
    auto: false,
    pause: 4000,
    autoStart: true,
    autoDirection: 'next',
    stopAutoOnClick: false,
    autoHover: false,
    autoDelay: 0,
    autoSlideForOnePage: false,

    // CAROUSEL
    minSlides: 1,
    maxSlides: 1,
    moveSlides: 0,
    slideWidth: 0,
    shrinkItems: false,

    // CALLBACKS
    onSliderLoad: function() { return true; },
    onSlideBefore: function() { return true; },
    onSlideAfter: function() { return true; },
    onSlideNext: function() { return true; },
    onSlidePrev: function() { return true; },
    onSliderResize: function() { return true; }
  };

  $.fn.bxSlider = function(options) {

    if (this.length === 0) {
      return this;
    }

    // support multiple elements
    if (this.length > 1) {
      this.each(function() {
        $(this).bxSlider(options);
      });
      return this;
    }

    // create a namespace to be used throughout the plugin
    var slider = {},
    // set a reference to our slider element
    el = this,
    // get the original window dimens (thanks a lot IE)
    windowWidth = $(window).width(),
    windowHeight = $(window).height();

    // Return if slider is already initialized
    if ($(el).data('bxSlider')) { return; }

    /**
     * ===================================================================================
     * = PRIVATE FUNCTIONS
     * ===================================================================================
     */

    /**
     * Initializes namespace settings to be used throughout plugin
     */
    var init = function() {
      // Return if slider is already initialized
      if ($(el).data('bxSlider')) { return; }
      // merge user-supplied options with the defaults
      slider.settings = $.extend({}, defaults, options);
      // parse slideWidth setting
      slider.settings.slideWidth = parseInt(slider.settings.slideWidth);
      // store the original children
      slider.children = el.children(slider.settings.slideSelector);
      // check if actual number of slides is less than minSlides / maxSlides
      if (slider.children.length < slider.settings.minSlides) { slider.settings.minSlides = slider.children.length; }
      if (slider.children.length < slider.settings.maxSlides) { slider.settings.maxSlides = slider.children.length; }
      // if random start, set the startSlide setting to random number
      if (slider.settings.randomStart) { slider.settings.startSlide = Math.floor(Math.random() * slider.children.length); }
      // store active slide information
      slider.active = { index: slider.settings.startSlide };
      // store if the slider is in carousel mode (displaying / moving multiple slides)
      slider.carousel = slider.settings.minSlides > 1 || slider.settings.maxSlides > 1 ? true : false;
      // if carousel, force preloadImages = 'all'
      if (slider.carousel) { slider.settings.preloadImages = 'all'; }
      // calculate the min / max width thresholds based on min / max number of slides
      // used to setup and update carousel slides dimensions
      slider.minThreshold = (slider.settings.minSlides * slider.settings.slideWidth) + ((slider.settings.minSlides - 1) * slider.settings.slideMargin);
      slider.maxThreshold = (slider.settings.maxSlides * slider.settings.slideWidth) + ((slider.settings.maxSlides - 1) * slider.settings.slideMargin);
      // store the current state of the slider (if currently animating, working is true)
      slider.working = false;
      // initialize the controls object
      slider.controls = {};
      // initialize an auto interval
      slider.interval = null;
      // determine which property to use for transitions
      slider.animProp = slider.settings.mode === 'vertical' ? 'top' : 'left';
      // determine if hardware acceleration can be used
      slider.usingCSS = slider.settings.useCSS && slider.settings.mode !== 'fade' && (function() {
        // create our test div element
        var div = document.createElement('div'),
        // css transition properties
        props = ['WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective'];
        // test for each property
        for (var i = 0; i < props.length; i++) {
          if (div.style[props[i]] !== undefined) {
            slider.cssPrefix = props[i].replace('Perspective', '').toLowerCase();
            slider.animProp = '-' + slider.cssPrefix + '-transform';
            return true;
          }
        }
        return false;
      }());
      // if vertical mode always make maxSlides and minSlides equal
      if (slider.settings.mode === 'vertical') { slider.settings.maxSlides = slider.settings.minSlides; }
      // save original style data
      el.data('origStyle', el.attr('style'));
      el.children(slider.settings.slideSelector).each(function() {
        $(this).data('origStyle', $(this).attr('style'));
      });

      // perform all DOM / CSS modifications
      setup();
    };

    /**
     * Performs all DOM and CSS modifications
     */
    var setup = function() {
      var preloadSelector = slider.children.eq(slider.settings.startSlide); // set the default preload selector (visible)

      // wrap el in a wrapper
      el.wrap('<div class="' + slider.settings.wrapperClass + '"><div class="bx-viewport"></div></div>');
      // store a namespace reference to .bx-viewport
      slider.viewport = el.parent();

      // add aria-live if the setting is enabled and ticker mode is disabled
      if (slider.settings.ariaLive && !slider.settings.ticker) {
        slider.viewport.attr('aria-live', 'polite');
      }
      // add a loading div to display while images are loading
      slider.loader = $('<div class="bx-loading" />');
      slider.viewport.prepend(slider.loader);
      // set el to a massive width, to hold any needed slides
      // also strip any margin and padding from el
      el.css({
        width: slider.settings.mode === 'horizontal' ? (slider.children.length * 1000 + 215) + '%' : 'auto',
        position: 'relative'
      });
      // if using CSS, add the easing property
      if (slider.usingCSS && slider.settings.easing) {
        el.css('-' + slider.cssPrefix + '-transition-timing-function', slider.settings.easing);
      // if not using CSS and no easing value was supplied, use the default JS animation easing (swing)
      } else if (!slider.settings.easing) {
        slider.settings.easing = 'swing';
      }
      // make modifications to the viewport (.bx-viewport)
      slider.viewport.css({
        width: '100%',
        overflow: 'hidden',
        position: 'relative'
      });
      slider.viewport.parent().css({
        maxWidth: getViewportMaxWidth()
      });
      // apply css to all slider children
      slider.children.css({
        float: slider.settings.mode === 'horizontal' ? 'left' : 'none',
        listStyle: 'none',
        position: 'relative'
      });
      // apply the calculated width after the float is applied to prevent scrollbar interference
      slider.children.css('width', getSlideWidth());
      // if slideMargin is supplied, add the css
      if (slider.settings.mode === 'horizontal' && slider.settings.slideMargin > 0) { slider.children.css('marginRight', slider.settings.slideMargin); }
      if (slider.settings.mode === 'vertical' && slider.settings.slideMargin > 0) { slider.children.css('marginBottom', slider.settings.slideMargin); }
      // if "fade" mode, add positioning and z-index CSS
      if (slider.settings.mode === 'fade') {
        slider.children.css({
          position: 'absolute',
          zIndex: 0,
          display: 'none'
        });
        // prepare the z-index on the showing element
        slider.children.eq(slider.settings.startSlide).css({zIndex: slider.settings.slideZIndex, display: 'block'});
      }
      // create an element to contain all slider controls (pager, start / stop, etc)
      slider.controls.el = $('<div class="bx-controls" />');
      // if captions are requested, add them
      if (slider.settings.captions) { appendCaptions(); }
      // check if startSlide is last slide
      slider.active.last = slider.settings.startSlide === getPagerQty() - 1;
      // if video is true, set up the fitVids plugin
      if (slider.settings.video) { el.fitVids(); }
      if (slider.settings.preloadImages === 'all' || slider.settings.ticker) { preloadSelector = slider.children; }
      // only check for control addition if not in "ticker" mode
      if (!slider.settings.ticker) {
        // if controls are requested, add them
        if (slider.settings.controls) { appendControls(); }
        // if auto is true, and auto controls are requested, add them
        if (slider.settings.auto && slider.settings.autoControls) { appendControlsAuto(); }
        // if pager is requested, add it
        if (slider.settings.pager) { appendPager(); }
        // if any control option is requested, add the controls wrapper
        if (slider.settings.controls || slider.settings.autoControls || slider.settings.pager) { slider.viewport.after(slider.controls.el); }
      // if ticker mode, do not allow a pager
      } else {
        slider.settings.pager = false;
      }
      loadElements(preloadSelector, start);
    };

    var loadElements = function(selector, callback) {
      var total = selector.find('img:not([src=""]), iframe').length,
      count = 0;
      if (total === 0) {
        callback();
        return;
      }
      selector.find('img:not([src=""]), iframe').each(function() {
        $(this).one('load error', function() {
          if (++count === total) { callback(); }
        }).each(function() {
          if (this.complete) { $(this).trigger('load'); }
        });
      });
    };

    /**
     * Start the slider
     */
    var start = function() {
      // if infinite loop, prepare additional slides
      if (slider.settings.infiniteLoop && slider.settings.mode !== 'fade' && !slider.settings.ticker) {
        var slice    = slider.settings.mode === 'vertical' ? slider.settings.minSlides : slider.settings.maxSlides,
        sliceAppend  = slider.children.slice(0, slice).clone(true).addClass('bx-clone'),
        slicePrepend = slider.children.slice(-slice).clone(true).addClass('bx-clone');
        if (slider.settings.ariaHidden) {
          sliceAppend.attr('aria-hidden', true);
          slicePrepend.attr('aria-hidden', true);
        }
        el.append(sliceAppend).prepend(slicePrepend);
      }
      // remove the loading DOM element
      slider.loader.remove();
      // set the left / top position of "el"
      setSlidePosition();
      // if "vertical" mode, always use adaptiveHeight to prevent odd behavior
      if (slider.settings.mode === 'vertical') { slider.settings.adaptiveHeight = true; }
      // set the viewport height
      slider.viewport.height(getViewportHeight());
      // make sure everything is positioned just right (same as a window resize)
      el.redrawSlider();
      // onSliderLoad callback
      slider.settings.onSliderLoad.call(el, slider.active.index);
      // slider has been fully initialized
      slider.initialized = true;
      // bind the resize call to the window
      if (slider.settings.responsive) { $(window).bind('resize', resizeWindow); }
      // if auto is true and has more than 1 page, start the show
      if (slider.settings.auto && slider.settings.autoStart && (getPagerQty() > 1 || slider.settings.autoSlideForOnePage)) { initAuto(); }
      // if ticker is true, start the ticker
      if (slider.settings.ticker) { initTicker(); }
      // if pager is requested, make the appropriate pager link active
      if (slider.settings.pager) { updatePagerActive(slider.settings.startSlide); }
      // check for any updates to the controls (like hideControlOnEnd updates)
      if (slider.settings.controls) { updateDirectionControls(); }
      // if touchEnabled is true, setup the touch events
      if (slider.settings.touchEnabled && !slider.settings.ticker) { initTouch(); }
      // if keyboardEnabled is true, setup the keyboard events
      if (slider.settings.keyboardEnabled && !slider.settings.ticker) {
        $(document).keydown(keyPress);
      }
    };

    /**
     * Returns the calculated height of the viewport, used to determine either adaptiveHeight or the maxHeight value
     */
    var getViewportHeight = function() {
      var height = 0;
      // first determine which children (slides) should be used in our height calculation
      var children = $();
      // if mode is not "vertical" and adaptiveHeight is false, include all children
      if (slider.settings.mode !== 'vertical' && !slider.settings.adaptiveHeight) {
        children = slider.children;
      } else {
        // if not carousel, return the single active child
        if (!slider.carousel) {
          children = slider.children.eq(slider.active.index);
        // if carousel, return a slice of children
        } else {
          // get the individual slide index
          var currentIndex = slider.settings.moveSlides === 1 ? slider.active.index : slider.active.index * getMoveBy();
          // add the current slide to the children
          children = slider.children.eq(currentIndex);
          // cycle through the remaining "showing" slides
          for (i = 1; i <= slider.settings.maxSlides - 1; i++) {
            // if looped back to the start
            if (currentIndex + i >= slider.children.length) {
              children = children.add(slider.children.eq(i - 1));
            } else {
              children = children.add(slider.children.eq(currentIndex + i));
            }
          }
        }
      }
      // if "vertical" mode, calculate the sum of the heights of the children
      if (slider.settings.mode === 'vertical') {
        children.each(function(index) {
          height += $(this).outerHeight();
        });
        // add user-supplied margins
        if (slider.settings.slideMargin > 0) {
          height += slider.settings.slideMargin * (slider.settings.minSlides - 1);
        }
      // if not "vertical" mode, calculate the max height of the children
      } else {
        height = Math.max.apply(Math, children.map(function() {
          return $(this).outerHeight(false);
        }).get());
      }

      if (slider.viewport.css('box-sizing') === 'border-box') {
        height += parseFloat(slider.viewport.css('padding-top')) + parseFloat(slider.viewport.css('padding-bottom')) +
              parseFloat(slider.viewport.css('border-top-width')) + parseFloat(slider.viewport.css('border-bottom-width'));
      } else if (slider.viewport.css('box-sizing') === 'padding-box') {
        height += parseFloat(slider.viewport.css('padding-top')) + parseFloat(slider.viewport.css('padding-bottom'));
      }

      return height;
    };

    /**
     * Returns the calculated width to be used for the outer wrapper / viewport
     */
    var getViewportMaxWidth = function() {
      var width = '100%';
      if (slider.settings.slideWidth > 0) {
        if (slider.settings.mode === 'horizontal') {
          width = (slider.settings.maxSlides * slider.settings.slideWidth) + ((slider.settings.maxSlides - 1) * slider.settings.slideMargin);
        } else {
          width = slider.settings.slideWidth;
        }
      }
      return width;
    };

    /**
     * Returns the calculated width to be applied to each slide
     */
    var getSlideWidth = function() {
      var newElWidth = slider.settings.slideWidth, // start with any user-supplied slide width
      wrapWidth      = slider.viewport.width();    // get the current viewport width
      // if slide width was not supplied, or is larger than the viewport use the viewport width
      if (slider.settings.slideWidth === 0 ||
        (slider.settings.slideWidth > wrapWidth && !slider.carousel) ||
        slider.settings.mode === 'vertical') {
        newElWidth = wrapWidth;
      // if carousel, use the thresholds to determine the width
      } else if (slider.settings.maxSlides > 1 && slider.settings.mode === 'horizontal') {
        if (wrapWidth > slider.maxThreshold) {
          return newElWidth;
        } else if (wrapWidth < slider.minThreshold) {
          newElWidth = (wrapWidth - (slider.settings.slideMargin * (slider.settings.minSlides - 1))) / slider.settings.minSlides;
        } else if (slider.settings.shrinkItems) {
          newElWidth = Math.floor((wrapWidth + slider.settings.slideMargin) / (Math.ceil((wrapWidth + slider.settings.slideMargin) / (newElWidth + slider.settings.slideMargin))) - slider.settings.slideMargin);
        }
      }
      return newElWidth;
    };

    /**
     * Returns the number of slides currently visible in the viewport (includes partially visible slides)
     */
    var getNumberSlidesShowing = function() {
      var slidesShowing = 1,
      childWidth = null;
      if (slider.settings.mode === 'horizontal' && slider.settings.slideWidth > 0) {
        // if viewport is smaller than minThreshold, return minSlides
        if (slider.viewport.width() < slider.minThreshold) {
          slidesShowing = slider.settings.minSlides;
        // if viewport is larger than maxThreshold, return maxSlides
        } else if (slider.viewport.width() > slider.maxThreshold) {
          slidesShowing = slider.settings.maxSlides;
        // if viewport is between min / max thresholds, divide viewport width by first child width
        } else {
          childWidth = slider.children.first().width() + slider.settings.slideMargin;
          slidesShowing = Math.floor((slider.viewport.width() +
            slider.settings.slideMargin) / childWidth);
        }
      // if "vertical" mode, slides showing will always be minSlides
      } else if (slider.settings.mode === 'vertical') {
        slidesShowing = slider.settings.minSlides;
      }
      return slidesShowing;
    };

    /**
     * Returns the number of pages (one full viewport of slides is one "page")
     */
    var getPagerQty = function() {
      var pagerQty = 0,
      breakPoint = 0,
      counter = 0;
      // if moveSlides is specified by the user
      if (slider.settings.moveSlides > 0) {
        if (slider.settings.infiniteLoop) {
          pagerQty = Math.ceil(slider.children.length / getMoveBy());
        } else {
          // when breakpoint goes above children length, counter is the number of pages
          while (breakPoint < slider.children.length) {
            ++pagerQty;
            breakPoint = counter + getNumberSlidesShowing();
            counter += slider.settings.moveSlides <= getNumberSlidesShowing() ? slider.settings.moveSlides : getNumberSlidesShowing();
          }
        }
      // if moveSlides is 0 (auto) divide children length by sides showing, then round up
      } else {
        pagerQty = Math.ceil(slider.children.length / getNumberSlidesShowing());
      }
      return pagerQty;
    };

    /**
     * Returns the number of individual slides by which to shift the slider
     */
    var getMoveBy = function() {
      // if moveSlides was set by the user and moveSlides is less than number of slides showing
      if (slider.settings.moveSlides > 0 && slider.settings.moveSlides <= getNumberSlidesShowing()) {
        return slider.settings.moveSlides;
      }
      // if moveSlides is 0 (auto)
      return getNumberSlidesShowing();
    };

    /**
     * Sets the slider's (el) left or top position
     */
    var setSlidePosition = function() {
      var position, lastChild, lastShowingIndex;
      // if last slide, not infinite loop, and number of children is larger than specified maxSlides
      if (slider.children.length > slider.settings.maxSlides && slider.active.last && !slider.settings.infiniteLoop) {
        if (slider.settings.mode === 'horizontal') {
          // get the last child's position
          lastChild = slider.children.last();
          position = lastChild.position();
          // set the left position
          setPositionProperty(-(position.left - (slider.viewport.width() - lastChild.outerWidth())), 'reset', 0);
        } else if (slider.settings.mode === 'vertical') {
          // get the last showing index's position
          lastShowingIndex = slider.children.length - slider.settings.minSlides;
          position = slider.children.eq(lastShowingIndex).position();
          // set the top position
          setPositionProperty(-position.top, 'reset', 0);
        }
      // if not last slide
      } else {
        // get the position of the first showing slide
        position = slider.children.eq(slider.active.index * getMoveBy()).position();
        // check for last slide
        if (slider.active.index === getPagerQty() - 1) { slider.active.last = true; }
        // set the respective position
        if (position !== undefined) {
          if (slider.settings.mode === 'horizontal') { setPositionProperty(-position.left, 'reset', 0); }
          else if (slider.settings.mode === 'vertical') { setPositionProperty(-position.top, 'reset', 0); }
        }
      }
    };

    /**
     * Sets the el's animating property position (which in turn will sometimes animate el).
     * If using CSS, sets the transform property. If not using CSS, sets the top / left property.
     *
     * @param value (int)
     *  - the animating property's value
     *
     * @param type (string) 'slide', 'reset', 'ticker'
     *  - the type of instance for which the function is being
     *
     * @param duration (int)
     *  - the amount of time (in ms) the transition should occupy
     *
     * @param params (array) optional
     *  - an optional parameter containing any variables that need to be passed in
     */
    var setPositionProperty = function(value, type, duration, params) {
      var animateObj, propValue;
      // use CSS transform
      if (slider.usingCSS) {
        // determine the translate3d value
        propValue = slider.settings.mode === 'vertical' ? 'translate3d(0, ' + value + 'px, 0)' : 'translate3d(' + value + 'px, 0, 0)';
        // add the CSS transition-duration
        el.css('-' + slider.cssPrefix + '-transition-duration', duration / 1000 + 's');
        if (type === 'slide') {
          // set the property value
          el.css(slider.animProp, propValue);
          if (duration !== 0) {
            // bind a callback method - executes when CSS transition completes
            el.bind('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(e) {
              //make sure it's the correct one
              if (!$(e.target).is(el)) { return; }
              // unbind the callback
              el.unbind('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd');
              updateAfterSlideTransition();
            });
          } else { //duration = 0
            updateAfterSlideTransition();
          }
        } else if (type === 'reset') {
          el.css(slider.animProp, propValue);
        } else if (type === 'ticker') {
          // make the transition use 'linear'
          el.css('-' + slider.cssPrefix + '-transition-timing-function', 'linear');
          el.css(slider.animProp, propValue);
          if (duration !== 0) {
            el.bind('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function(e) {
              //make sure it's the correct one
              if (!$(e.target).is(el)) { return; }
              // unbind the callback
              el.unbind('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd');
              // reset the position
              setPositionProperty(params.resetValue, 'reset', 0);
              // start the loop again
              tickerLoop();
            });
          } else { //duration = 0
            setPositionProperty(params.resetValue, 'reset', 0);
            tickerLoop();
          }
        }
      // use JS animate
      } else {
        animateObj = {};
        animateObj[slider.animProp] = value;
        if (type === 'slide') {
          el.animate(animateObj, duration, slider.settings.easing, function() {
            updateAfterSlideTransition();
          });
        } else if (type === 'reset') {
          el.css(slider.animProp, value);
        } else if (type === 'ticker') {
          el.animate(animateObj, duration, 'linear', function() {
            setPositionProperty(params.resetValue, 'reset', 0);
            // run the recursive loop after animation
            tickerLoop();
          });
        }
      }
    };

    /**
     * Populates the pager with proper amount of pages
     */
    var populatePager = function() {
      var pagerHtml = '',
      linkContent = '',
      pagerQty = getPagerQty();
      // loop through each pager item
      for (var i = 0; i < pagerQty; i++) {
        linkContent = '';
        // if a buildPager function is supplied, use it to get pager link value, else use index + 1
        if (slider.settings.buildPager && $.isFunction(slider.settings.buildPager) || slider.settings.pagerCustom) {
          linkContent = slider.settings.buildPager(i);
          slider.pagerEl.addClass('bx-custom-pager');
        } else {
          linkContent = i + 1;
          slider.pagerEl.addClass('bx-default-pager');
        }
        // var linkContent = slider.settings.buildPager && $.isFunction(slider.settings.buildPager) ? slider.settings.buildPager(i) : i + 1;
        // add the markup to the string
        pagerHtml += '<div class="bx-pager-item"><a href="" data-slide-index="' + i + '" class="bx-pager-link">' + linkContent + '</a></div>';
      }
      // populate the pager element with pager links
      slider.pagerEl.html(pagerHtml);
    };

    /**
     * Appends the pager to the controls element
     */
    var appendPager = function() {
      if (!slider.settings.pagerCustom) {
        // create the pager DOM element
        slider.pagerEl = $('<div class="bx-pager" />');
        // if a pager selector was supplied, populate it with the pager
        if (slider.settings.pagerSelector) {
          $(slider.settings.pagerSelector).html(slider.pagerEl);
        // if no pager selector was supplied, add it after the wrapper
        } else {
          slider.controls.el.addClass('bx-has-pager').append(slider.pagerEl);
        }
        // populate the pager
        populatePager();
      } else {
        slider.pagerEl = $(slider.settings.pagerCustom);
      }
      // assign the pager click binding
      slider.pagerEl.on('click touchend', 'a', clickPagerBind);
    };

    /**
     * Appends prev / next controls to the controls element
     */
    var appendControls = function() {
      slider.controls.next = $('<a class="bx-next" href="">' + slider.settings.nextText + '</a>');
      slider.controls.prev = $('<a class="bx-prev" href="">' + slider.settings.prevText + '</a>');
      // bind click actions to the controls
      slider.controls.next.bind('click touchend', clickNextBind);
      slider.controls.prev.bind('click touchend', clickPrevBind);
      // if nextSelector was supplied, populate it
      if (slider.settings.nextSelector) {
        $(slider.settings.nextSelector).append(slider.controls.next);
      }
      // if prevSelector was supplied, populate it
      if (slider.settings.prevSelector) {
        $(slider.settings.prevSelector).append(slider.controls.prev);
      }
      // if no custom selectors were supplied
      if (!slider.settings.nextSelector && !slider.settings.prevSelector) {
        // add the controls to the DOM
        slider.controls.directionEl = $('<div class="bx-controls-direction" />');
        // add the control elements to the directionEl
        slider.controls.directionEl.append(slider.controls.prev).append(slider.controls.next);
        // slider.viewport.append(slider.controls.directionEl);
        slider.controls.el.addClass('bx-has-controls-direction').append(slider.controls.directionEl);
      }
    };

    /**
     * Appends start / stop auto controls to the controls element
     */
    var appendControlsAuto = function() {
      slider.controls.start = $('<div class="bx-controls-auto-item"><a class="bx-start" href="">' + slider.settings.startText + '</a></div>');
      slider.controls.stop = $('<div class="bx-controls-auto-item"><a class="bx-stop" href="">' + slider.settings.stopText + '</a></div>');
      // add the controls to the DOM
      slider.controls.autoEl = $('<div class="bx-controls-auto" />');
      // bind click actions to the controls
      slider.controls.autoEl.on('click', '.bx-start', clickStartBind);
      slider.controls.autoEl.on('click', '.bx-stop', clickStopBind);
      // if autoControlsCombine, insert only the "start" control
      if (slider.settings.autoControlsCombine) {
        slider.controls.autoEl.append(slider.controls.start);
      // if autoControlsCombine is false, insert both controls
      } else {
        slider.controls.autoEl.append(slider.controls.start).append(slider.controls.stop);
      }
      // if auto controls selector was supplied, populate it with the controls
      if (slider.settings.autoControlsSelector) {
        $(slider.settings.autoControlsSelector).html(slider.controls.autoEl);
      // if auto controls selector was not supplied, add it after the wrapper
      } else {
        slider.controls.el.addClass('bx-has-controls-auto').append(slider.controls.autoEl);
      }
      // update the auto controls
      updateAutoControls(slider.settings.autoStart ? 'stop' : 'start');
    };

    /**
     * Appends image captions to the DOM
     */
    var appendCaptions = function() {
      // cycle through each child
      slider.children.each(function(index) {
        // get the image title attribute
        var title = $(this).find('img:first').attr('title');
        // append the caption
        if (title !== undefined && ('' + title).length) {
          $(this).append('<div class="bx-caption"><span>' + title + '</span></div>');
        }
      });
    };

    /**
     * Click next binding
     *
     * @param e (event)
     *  - DOM event object
     */
    var clickNextBind = function(e) {
      e.preventDefault();
      if (slider.controls.el.hasClass('disabled')) { return; }
      // if auto show is running, stop it
      if (slider.settings.auto && slider.settings.stopAutoOnClick) { el.stopAuto(); }
      el.goToNextSlide();
    };

    /**
     * Click prev binding
     *
     * @param e (event)
     *  - DOM event object
     */
    var clickPrevBind = function(e) {
      e.preventDefault();
      if (slider.controls.el.hasClass('disabled')) { return; }
      // if auto show is running, stop it
      if (slider.settings.auto && slider.settings.stopAutoOnClick) { el.stopAuto(); }
      el.goToPrevSlide();
    };

    /**
     * Click start binding
     *
     * @param e (event)
     *  - DOM event object
     */
    var clickStartBind = function(e) {
      el.startAuto();
      e.preventDefault();
    };

    /**
     * Click stop binding
     *
     * @param e (event)
     *  - DOM event object
     */
    var clickStopBind = function(e) {
      el.stopAuto();
      e.preventDefault();
    };

    /**
     * Click pager binding
     *
     * @param e (event)
     *  - DOM event object
     */
    var clickPagerBind = function(e) {
      var pagerLink, pagerIndex;
      e.preventDefault();
      if (slider.controls.el.hasClass('disabled')) {
        return;
      }
      // if auto show is running, stop it
      if (slider.settings.auto  && slider.settings.stopAutoOnClick) { el.stopAuto(); }
      pagerLink = $(e.currentTarget);
      if (pagerLink.attr('data-slide-index') !== undefined) {
        pagerIndex = parseInt(pagerLink.attr('data-slide-index'));
        // if clicked pager link is not active, continue with the goToSlide call
        if (pagerIndex !== slider.active.index) { el.goToSlide(pagerIndex); }
      }
    };

    /**
     * Updates the pager links with an active class
     *
     * @param slideIndex (int)
     *  - index of slide to make active
     */
    var updatePagerActive = function(slideIndex) {
      // if "short" pager type
      var len = slider.children.length; // nb of children
      if (slider.settings.pagerType === 'short') {
        if (slider.settings.maxSlides > 1) {
          len = Math.ceil(slider.children.length / slider.settings.maxSlides);
        }
        slider.pagerEl.html((slideIndex + 1) + slider.settings.pagerShortSeparator + len);
        return;
      }
      // remove all pager active classes
      slider.pagerEl.find('a').removeClass('active');
      // apply the active class for all pagers
      slider.pagerEl.each(function(i, el) { $(el).find('a').eq(slideIndex).addClass('active'); });
    };

    /**
     * Performs needed actions after a slide transition
     */
    var updateAfterSlideTransition = function() {
      // if infinite loop is true
      if (slider.settings.infiniteLoop) {
        var position = '';
        // first slide
        if (slider.active.index === 0) {
          // set the new position
          position = slider.children.eq(0).position();
        // carousel, last slide
        } else if (slider.active.index === getPagerQty() - 1 && slider.carousel) {
          position = slider.children.eq((getPagerQty() - 1) * getMoveBy()).position();
        // last slide
        } else if (slider.active.index === slider.children.length - 1) {
          position = slider.children.eq(slider.children.length - 1).position();
        }
        if (position) {
          if (slider.settings.mode === 'horizontal') { setPositionProperty(-position.left, 'reset', 0); }
          else if (slider.settings.mode === 'vertical') { setPositionProperty(-position.top, 'reset', 0); }
        }
      }
      // declare that the transition is complete
      slider.working = false;
      // onSlideAfter callback
      slider.settings.onSlideAfter.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index);
    };

    /**
     * Updates the auto controls state (either active, or combined switch)
     *
     * @param state (string) "start", "stop"
     *  - the new state of the auto show
     */
    var updateAutoControls = function(state) {
      // if autoControlsCombine is true, replace the current control with the new state
      if (slider.settings.autoControlsCombine) {
        slider.controls.autoEl.html(slider.controls[state]);
      // if autoControlsCombine is false, apply the "active" class to the appropriate control
      } else {
        slider.controls.autoEl.find('a').removeClass('active');
        slider.controls.autoEl.find('a:not(.bx-' + state + ')').addClass('active');
      }
    };

    /**
     * Updates the direction controls (checks if either should be hidden)
     */
    var updateDirectionControls = function() {
      if (getPagerQty() === 1) {
        slider.controls.prev.addClass('disabled');
        slider.controls.next.addClass('disabled');
      } else if (!slider.settings.infiniteLoop && slider.settings.hideControlOnEnd) {
        // if first slide
        if (slider.active.index === 0) {
          slider.controls.prev.addClass('disabled');
          slider.controls.next.removeClass('disabled');
        // if last slide
        } else if (slider.active.index === getPagerQty() - 1) {
          slider.controls.next.addClass('disabled');
          slider.controls.prev.removeClass('disabled');
        // if any slide in the middle
        } else {
          slider.controls.prev.removeClass('disabled');
          slider.controls.next.removeClass('disabled');
        }
      }
    };

    /**
     * Initializes the auto process
     */
    var initAuto = function() {
      // if autoDelay was supplied, launch the auto show using a setTimeout() call
      if (slider.settings.autoDelay > 0) {
        var timeout = setTimeout(el.startAuto, slider.settings.autoDelay);
      // if autoDelay was not supplied, start the auto show normally
      } else {
        el.startAuto();

        //add focus and blur events to ensure its running if timeout gets paused
        $(window).focus(function() {
          el.startAuto();
        }).blur(function() {
          el.stopAuto();
        });
      }
      // if autoHover is requested
      if (slider.settings.autoHover) {
        // on el hover
        el.hover(function() {
          // if the auto show is currently playing (has an active interval)
          if (slider.interval) {
            // stop the auto show and pass true argument which will prevent control update
            el.stopAuto(true);
            // create a new autoPaused value which will be used by the relative "mouseout" event
            slider.autoPaused = true;
          }
        }, function() {
          // if the autoPaused value was created be the prior "mouseover" event
          if (slider.autoPaused) {
            // start the auto show and pass true argument which will prevent control update
            el.startAuto(true);
            // reset the autoPaused value
            slider.autoPaused = null;
          }
        });
      }
    };

    /**
     * Initializes the ticker process
     */
    var initTicker = function() {
      var startPosition = 0,
      position, transform, value, idx, ratio, property, newSpeed, totalDimens;
      // if autoDirection is "next", append a clone of the entire slider
      if (slider.settings.autoDirection === 'next') {
        el.append(slider.children.clone().addClass('bx-clone'));
      // if autoDirection is "prev", prepend a clone of the entire slider, and set the left position
      } else {
        el.prepend(slider.children.clone().addClass('bx-clone'));
        position = slider.children.first().position();
        startPosition = slider.settings.mode === 'horizontal' ? -position.left : -position.top;
      }
      setPositionProperty(startPosition, 'reset', 0);
      // do not allow controls in ticker mode
      slider.settings.pager = false;
      slider.settings.controls = false;
      slider.settings.autoControls = false;
      // if autoHover is requested
      if (slider.settings.tickerHover) {
        if (slider.usingCSS) {
          idx = slider.settings.mode === 'horizontal' ? 4 : 5;
          slider.viewport.hover(function() {
            transform = el.css('-' + slider.cssPrefix + '-transform');
            value = parseFloat(transform.split(',')[idx]);
            setPositionProperty(value, 'reset', 0);
          }, function() {
            totalDimens = 0;
            slider.children.each(function(index) {
              totalDimens += slider.settings.mode === 'horizontal' ? $(this).outerWidth(true) : $(this).outerHeight(true);
            });
            // calculate the speed ratio (used to determine the new speed to finish the paused animation)
            ratio = slider.settings.speed / totalDimens;
            // determine which property to use
            property = slider.settings.mode === 'horizontal' ? 'left' : 'top';
            // calculate the new speed
            newSpeed = ratio * (totalDimens - (Math.abs(parseInt(value))));
            tickerLoop(newSpeed);
          });
        } else {
          // on el hover
          slider.viewport.hover(function() {
            el.stop();
          }, function() {
            // calculate the total width of children (used to calculate the speed ratio)
            totalDimens = 0;
            slider.children.each(function(index) {
              totalDimens += slider.settings.mode === 'horizontal' ? $(this).outerWidth(true) : $(this).outerHeight(true);
            });
            // calculate the speed ratio (used to determine the new speed to finish the paused animation)
            ratio = slider.settings.speed / totalDimens;
            // determine which property to use
            property = slider.settings.mode === 'horizontal' ? 'left' : 'top';
            // calculate the new speed
            newSpeed = ratio * (totalDimens - (Math.abs(parseInt(el.css(property)))));
            tickerLoop(newSpeed);
          });
        }
      }
      // start the ticker loop
      tickerLoop();
    };

    /**
     * Runs a continuous loop, news ticker-style
     */
    var tickerLoop = function(resumeSpeed) {
      var speed = resumeSpeed ? resumeSpeed : slider.settings.speed,
      position = {left: 0, top: 0},
      reset = {left: 0, top: 0},
      animateProperty, resetValue, params;

      // if "next" animate left position to last child, then reset left to 0
      if (slider.settings.autoDirection === 'next') {
        position = el.find('.bx-clone').first().position();
      // if "prev" animate left position to 0, then reset left to first non-clone child
      } else {
        reset = slider.children.first().position();
      }
      animateProperty = slider.settings.mode === 'horizontal' ? -position.left : -position.top;
      resetValue = slider.settings.mode === 'horizontal' ? -reset.left : -reset.top;
      params = {resetValue: resetValue};
      setPositionProperty(animateProperty, 'ticker', speed, params);
    };

    /**
     * Check if el is on screen
     */
    var isOnScreen = function(el) {
      var win = $(window),
      viewport = {
        top: win.scrollTop(),
        left: win.scrollLeft()
      },
      bounds = el.offset();

      viewport.right = viewport.left + win.width();
      viewport.bottom = viewport.top + win.height();
      bounds.right = bounds.left + el.outerWidth();
      bounds.bottom = bounds.top + el.outerHeight();

      return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    };

    /**
     * Initializes keyboard events
     */
    var keyPress = function(e) {
      var activeElementTag = document.activeElement.tagName.toLowerCase(),
      tagFilters = 'input|textarea',
      p = new RegExp(activeElementTag,['i']),
      result = p.exec(tagFilters);

      if (result == null && isOnScreen(el)) {
        if (e.keyCode === 39) {
          clickNextBind(e);
          return false;
        } else if (e.keyCode === 37) {
          clickPrevBind(e);
          return false;
        }
      }
    };

    /**
     * Initializes touch events
     */
    var initTouch = function() {
      // initialize object to contain all touch values
      slider.touch = {
        start: {x: 0, y: 0},
        end: {x: 0, y: 0}
      };
      slider.viewport.bind('touchstart MSPointerDown pointerdown', onTouchStart);

      //for browsers that have implemented pointer events and fire a click after
      //every pointerup regardless of whether pointerup is on same screen location as pointerdown or not
      slider.viewport.on('click', '.bxslider a', function(e) {
        if (slider.viewport.hasClass('click-disabled')) {
          e.preventDefault();
          slider.viewport.removeClass('click-disabled');
        }
      });
    };

    /**
     * Event handler for "touchstart"
     *
     * @param e (event)
     *  - DOM event object
     */
    var onTouchStart = function(e) {
      //disable slider controls while user is interacting with slides to avoid slider freeze that happens on touch devices when a slide swipe happens immediately after interacting with slider controls
      slider.controls.el.addClass('disabled');

      if (slider.working) {
        e.preventDefault();
        slider.controls.el.removeClass('disabled');
      } else {
        // record the original position when touch starts
        slider.touch.originalPos = el.position();
        var orig = e.originalEvent,
        touchPoints = (typeof orig.changedTouches !== 'undefined') ? orig.changedTouches : [orig];
        // record the starting touch x, y coordinates
        slider.touch.start.x = touchPoints[0].pageX;
        slider.touch.start.y = touchPoints[0].pageY;

        if (slider.viewport.get(0).setPointerCapture) {
          slider.pointerId = orig.pointerId;
          slider.viewport.get(0).setPointerCapture(slider.pointerId);
        }
        // bind a "touchmove" event to the viewport
        slider.viewport.bind('touchmove MSPointerMove pointermove', onTouchMove);
        // bind a "touchend" event to the viewport
        slider.viewport.bind('touchend MSPointerUp pointerup', onTouchEnd);
        slider.viewport.bind('MSPointerCancel pointercancel', onPointerCancel);
      }
    };

    /**
     * Cancel Pointer for Windows Phone
     *
     * @param e (event)
     *  - DOM event object
     */
    var onPointerCancel = function(e) {
      /* onPointerCancel handler is needed to deal with situations when a touchend
      doesn't fire after a touchstart (this happens on windows phones only) */
      setPositionProperty(slider.touch.originalPos.left, 'reset', 0);

      //remove handlers
      slider.controls.el.removeClass('disabled');
      slider.viewport.unbind('MSPointerCancel pointercancel', onPointerCancel);
      slider.viewport.unbind('touchmove MSPointerMove pointermove', onTouchMove);
      slider.viewport.unbind('touchend MSPointerUp pointerup', onTouchEnd);
      if (slider.viewport.get(0).releasePointerCapture) {
        slider.viewport.get(0).releasePointerCapture(slider.pointerId);
      }
    };

    /**
     * Event handler for "touchmove"
     *
     * @param e (event)
     *  - DOM event object
     */
    var onTouchMove = function(e) {
      var orig = e.originalEvent,
      touchPoints = (typeof orig.changedTouches !== 'undefined') ? orig.changedTouches : [orig],
      // if scrolling on y axis, do not prevent default
      xMovement = Math.abs(touchPoints[0].pageX - slider.touch.start.x),
      yMovement = Math.abs(touchPoints[0].pageY - slider.touch.start.y),
      value = 0,
      change = 0;

      // x axis swipe
      if ((xMovement * 3) > yMovement && slider.settings.preventDefaultSwipeX) {
        e.preventDefault();
      // y axis swipe
      } else if ((yMovement * 3) > xMovement && slider.settings.preventDefaultSwipeY) {
        e.preventDefault();
      }
      if (slider.settings.mode !== 'fade' && slider.settings.oneToOneTouch) {
        // if horizontal, drag along x axis
        if (slider.settings.mode === 'horizontal') {
          change = touchPoints[0].pageX - slider.touch.start.x;
          value = slider.touch.originalPos.left + change;
        // if vertical, drag along y axis
        } else {
          change = touchPoints[0].pageY - slider.touch.start.y;
          value = slider.touch.originalPos.top + change;
        }
        setPositionProperty(value, 'reset', 0);
      }
    };

    /**
     * Event handler for "touchend"
     *
     * @param e (event)
     *  - DOM event object
     */
    var onTouchEnd = function(e) {
      slider.viewport.unbind('touchmove MSPointerMove pointermove', onTouchMove);
      //enable slider controls as soon as user stops interacing with slides
      slider.controls.el.removeClass('disabled');
      var orig    = e.originalEvent,
      touchPoints = (typeof orig.changedTouches !== 'undefined') ? orig.changedTouches : [orig],
      value       = 0,
      distance    = 0;
      // record end x, y positions
      slider.touch.end.x = touchPoints[0].pageX;
      slider.touch.end.y = touchPoints[0].pageY;
      // if fade mode, check if absolute x distance clears the threshold
      if (slider.settings.mode === 'fade') {
        distance = Math.abs(slider.touch.start.x - slider.touch.end.x);
        if (distance >= slider.settings.swipeThreshold) {
          if (slider.touch.start.x > slider.touch.end.x) {
            el.goToNextSlide();
          } else {
            el.goToPrevSlide();
          }
          el.stopAuto();
        }
      // not fade mode
      } else {
        // calculate distance and el's animate property
        if (slider.settings.mode === 'horizontal') {
          distance = slider.touch.end.x - slider.touch.start.x;
          value = slider.touch.originalPos.left;
        } else {
          distance = slider.touch.end.y - slider.touch.start.y;
          value = slider.touch.originalPos.top;
        }
        // if not infinite loop and first / last slide, do not attempt a slide transition
        if (!slider.settings.infiniteLoop && ((slider.active.index === 0 && distance > 0) || (slider.active.last && distance < 0))) {
          setPositionProperty(value, 'reset', 200);
        } else {
          // check if distance clears threshold
          if (Math.abs(distance) >= slider.settings.swipeThreshold) {
            if (distance < 0) {
              el.goToNextSlide();
            } else {
              el.goToPrevSlide();
            }
            el.stopAuto();
          } else {
            // el.animate(property, 200);
            setPositionProperty(value, 'reset', 200);
          }
        }
      }
      slider.viewport.unbind('touchend MSPointerUp pointerup', onTouchEnd);
      if (slider.viewport.get(0).releasePointerCapture) {
        slider.viewport.get(0).releasePointerCapture(slider.pointerId);
      }
    };

    /**
     * Window resize event callback
     */
    var resizeWindow = function(e) {
      // don't do anything if slider isn't initialized.
      if (!slider.initialized) { return; }
      // Delay if slider working.
      if (slider.working) {
        window.setTimeout(resizeWindow, 10);
      } else {
        // get the new window dimens (again, thank you IE)
        var windowWidthNew = $(window).width(),
        windowHeightNew = $(window).height();
        // make sure that it is a true window resize
        // *we must check this because our dinosaur friend IE fires a window resize event when certain DOM elements
        // are resized. Can you just die already?*
        if (windowWidth !== windowWidthNew || windowHeight !== windowHeightNew) {
          // set the new window dimens
          windowWidth = windowWidthNew;
          windowHeight = windowHeightNew;
          // update all dynamic elements
          el.redrawSlider();
          // Call user resize handler
          slider.settings.onSliderResize.call(el, slider.active.index);
        }
      }
    };

    /**
     * Adds an aria-hidden=true attribute to each element
     *
     * @param startVisibleIndex (int)
     *  - the first visible element's index
     */
    var applyAriaHiddenAttributes = function(startVisibleIndex) {
      var numberOfSlidesShowing = getNumberSlidesShowing();
      // only apply attributes if the setting is enabled and not in ticker mode
      if (slider.settings.ariaHidden && !slider.settings.ticker) {
        // add aria-hidden=true to all elements
        slider.children.attr('aria-hidden', 'true');
        // get the visible elements and change to aria-hidden=false
        slider.children.slice(startVisibleIndex, startVisibleIndex + numberOfSlidesShowing).attr('aria-hidden', 'false');
      }
    };

    /**
     * Returns index according to present page range
     *
     * @param slideOndex (int)
     *  - the desired slide index
     */
    var setSlideIndex = function(slideIndex) {
      if (slideIndex < 0) {
        if (slider.settings.infiniteLoop) {
          return getPagerQty() - 1;
        }else {
          //we don't go to undefined slides
          return slider.active.index;
        }
      // if slideIndex is greater than children length, set active index to 0 (this happens during infinite loop)
      } else if (slideIndex >= getPagerQty()) {
        if (slider.settings.infiniteLoop) {
          return 0;
        } else {
          //we don't move to undefined pages
          return slider.active.index;
        }
      // set active index to requested slide
      } else {
        return slideIndex;
      }
    };

    /**
     * ===================================================================================
     * = PUBLIC FUNCTIONS
     * ===================================================================================
     */

    /**
     * Performs slide transition to the specified slide
     *
     * @param slideIndex (int)
     *  - the destination slide's index (zero-based)
     *
     * @param direction (string)
     *  - INTERNAL USE ONLY - the direction of travel ("prev" / "next")
     */
    el.goToSlide = function(slideIndex, direction) {
      // onSlideBefore, onSlideNext, onSlidePrev callbacks
      // Allow transition canceling based on returned value
      var performTransition = true,
      moveBy = 0,
      position = {left: 0, top: 0},
      lastChild = null,
      lastShowingIndex, eq, value, requestEl;
      // store the old index
      slider.oldIndex = slider.active.index;
      //set new index
      slider.active.index = setSlideIndex(slideIndex);

      // if plugin is currently in motion, ignore request
      if (slider.working || slider.active.index === slider.oldIndex) { return; }
      // declare that plugin is in motion
      slider.working = true;

      performTransition = slider.settings.onSlideBefore.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index);

      // If transitions canceled, reset and return
      if (typeof (performTransition) !== 'undefined' && !performTransition) {
        slider.active.index = slider.oldIndex; // restore old index
        slider.working = false; // is not in motion
        return;
      }

      if (direction === 'next') {
        // Prevent canceling in future functions or lack there-of from negating previous commands to cancel
        if (!slider.settings.onSlideNext.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index)) {
          performTransition = false;
        }
      } else if (direction === 'prev') {
        // Prevent canceling in future functions or lack there-of from negating previous commands to cancel
        if (!slider.settings.onSlidePrev.call(el, slider.children.eq(slider.active.index), slider.oldIndex, slider.active.index)) {
          performTransition = false;
        }
      }

      // check if last slide
      slider.active.last = slider.active.index >= getPagerQty() - 1;
      // update the pager with active class
      if (slider.settings.pager || slider.settings.pagerCustom) { updatePagerActive(slider.active.index); }
      // // check for direction control update
      if (slider.settings.controls) { updateDirectionControls(); }
      // if slider is set to mode: "fade"
      if (slider.settings.mode === 'fade') {
        // if adaptiveHeight is true and next height is different from current height, animate to the new height
        if (slider.settings.adaptiveHeight && slider.viewport.height() !== getViewportHeight()) {
          slider.viewport.animate({height: getViewportHeight()}, slider.settings.adaptiveHeightSpeed);
        }
        // fade out the visible child and reset its z-index value
        slider.children.filter(':visible').fadeOut(slider.settings.speed).css({zIndex: 0});
        // fade in the newly requested slide
        slider.children.eq(slider.active.index).css('zIndex', slider.settings.slideZIndex + 1).fadeIn(slider.settings.speed, function() {
          $(this).css('zIndex', slider.settings.slideZIndex);
          updateAfterSlideTransition();
        });
      // slider mode is not "fade"
      } else {
        // if adaptiveHeight is true and next height is different from current height, animate to the new height
        if (slider.settings.adaptiveHeight && slider.viewport.height() !== getViewportHeight()) {
          slider.viewport.animate({height: getViewportHeight()}, slider.settings.adaptiveHeightSpeed);
        }
        // if carousel and not infinite loop
        if (!slider.settings.infiniteLoop && slider.carousel && slider.active.last) {
          if (slider.settings.mode === 'horizontal') {
            // get the last child position
            lastChild = slider.children.eq(slider.children.length - 1);
            position = lastChild.position();
            // calculate the position of the last slide
            moveBy = slider.viewport.width() - lastChild.outerWidth();
          } else {
            // get last showing index position
            lastShowingIndex = slider.children.length - slider.settings.minSlides;
            position = slider.children.eq(lastShowingIndex).position();
          }
          // horizontal carousel, going previous while on first slide (infiniteLoop mode)
        } else if (slider.carousel && slider.active.last && direction === 'prev') {
          // get the last child position
          eq = slider.settings.moveSlides === 1 ? slider.settings.maxSlides - getMoveBy() : ((getPagerQty() - 1) * getMoveBy()) - (slider.children.length - slider.settings.maxSlides);
          lastChild = el.children('.bx-clone').eq(eq);
          position = lastChild.position();
        // if infinite loop and "Next" is clicked on the last slide
        } else if (direction === 'next' && slider.active.index === 0) {
          // get the last clone position
          position = el.find('> .bx-clone').eq(slider.settings.maxSlides).position();
          slider.active.last = false;
        // normal non-zero requests
        } else if (slideIndex >= 0) {
          //parseInt is applied to allow floats for slides/page
          requestEl = slideIndex * parseInt(getMoveBy());
          position = slider.children.eq(requestEl).position();
        }

        /* If the position doesn't exist
         * (e.g. if you destroy the slider on a next click),
         * it doesn't throw an error.
         */
        if (typeof (position) !== 'undefined') {
          value = slider.settings.mode === 'horizontal' ? -(position.left - moveBy) : -position.top;
          // plugin values to be animated
          setPositionProperty(value, 'slide', slider.settings.speed);
        } else {
          slider.working = false;
        }
      }
      if (slider.settings.ariaHidden) { applyAriaHiddenAttributes(slider.active.index * getMoveBy()); }
    };

    /**
     * Transitions to the next slide in the show
     */
    el.goToNextSlide = function() {
      // if infiniteLoop is false and last page is showing, disregard call
      if (!slider.settings.infiniteLoop && slider.active.last) { return; }
      var pagerIndex = parseInt(slider.active.index) + 1;
      el.goToSlide(pagerIndex, 'next');
    };

    /**
     * Transitions to the prev slide in the show
     */
    el.goToPrevSlide = function() {
      // if infiniteLoop is false and last page is showing, disregard call
      if (!slider.settings.infiniteLoop && slider.active.index === 0) { return; }
      var pagerIndex = parseInt(slider.active.index) - 1;
      el.goToSlide(pagerIndex, 'prev');
    };

    /**
     * Starts the auto show
     *
     * @param preventControlUpdate (boolean)
     *  - if true, auto controls state will not be updated
     */
    el.startAuto = function(preventControlUpdate) {
      // if an interval already exists, disregard call
      if (slider.interval) { return; }
      // create an interval
      slider.interval = setInterval(function() {
        if (slider.settings.autoDirection === 'next') {
          el.goToNextSlide();
        } else {
          el.goToPrevSlide();
        }
      }, slider.settings.pause);
      // if auto controls are displayed and preventControlUpdate is not true
      if (slider.settings.autoControls && preventControlUpdate !== true) { updateAutoControls('stop'); }
    };

    /**
     * Stops the auto show
     *
     * @param preventControlUpdate (boolean)
     *  - if true, auto controls state will not be updated
     */
    el.stopAuto = function(preventControlUpdate) {
      // if no interval exists, disregard call
      if (!slider.interval) { return; }
      // clear the interval
      clearInterval(slider.interval);
      slider.interval = null;
      // if auto controls are displayed and preventControlUpdate is not true
      if (slider.settings.autoControls && preventControlUpdate !== true) { updateAutoControls('start'); }
    };

    /**
     * Returns current slide index (zero-based)
     */
    el.getCurrentSlide = function() {
      return slider.active.index;
    };

    /**
     * Returns current slide element
     */
    el.getCurrentSlideElement = function() {
      return slider.children.eq(slider.active.index);
    };

    /**
     * Returns a slide element
     * @param index (int)
     *  - The index (zero-based) of the element you want returned.
     */
    el.getSlideElement = function(index) {
      return slider.children.eq(index);
    };

    /**
     * Returns number of slides in show
     */
    el.getSlideCount = function() {
      return slider.children.length;
    };

    /**
     * Return slider.working variable
     */
    el.isWorking = function() {
      return slider.working;
    };

    /**
     * Update all dynamic slider elements
     */
    el.redrawSlider = function() {
      // resize all children in ratio to new screen size
      slider.children.add(el.find('.bx-clone')).outerWidth(getSlideWidth());
      // adjust the height
      slider.viewport.css('height', getViewportHeight());
      // update the slide position
      if (!slider.settings.ticker) { setSlidePosition(); }
      // if active.last was true before the screen resize, we want
      // to keep it last no matter what screen size we end on
      if (slider.active.last) { slider.active.index = getPagerQty() - 1; }
      // if the active index (page) no longer exists due to the resize, simply set the index as last
      if (slider.active.index >= getPagerQty()) { slider.active.last = true; }
      // if a pager is being displayed and a custom pager is not being used, update it
      if (slider.settings.pager && !slider.settings.pagerCustom) {
        populatePager();
        updatePagerActive(slider.active.index);
      }
      if (slider.settings.ariaHidden) { applyAriaHiddenAttributes(slider.active.index * getMoveBy()); }
    };

    /**
     * Destroy the current instance of the slider (revert everything back to original state)
     */
    el.destroySlider = function() {
      // don't do anything if slider has already been destroyed
      if (!slider.initialized) { return; }
      slider.initialized = false;
      $('.bx-clone', this).remove();
      slider.children.each(function() {
        if ($(this).data('origStyle') !== undefined) {
          $(this).attr('style', $(this).data('origStyle'));
        } else {
          $(this).removeAttr('style');
        }
      });
      if ($(this).data('origStyle') !== undefined) {
        this.attr('style', $(this).data('origStyle'));
      } else {
        $(this).removeAttr('style');
      }
      $(this).unwrap().unwrap();
      if (slider.controls.el) { slider.controls.el.remove(); }
      if (slider.controls.next) { slider.controls.next.remove(); }
      if (slider.controls.prev) { slider.controls.prev.remove(); }
      if (slider.pagerEl && slider.settings.controls && !slider.settings.pagerCustom) { slider.pagerEl.remove(); }
      $('.bx-caption', this).remove();
      if (slider.controls.autoEl) { slider.controls.autoEl.remove(); }
      clearInterval(slider.interval);
      if (slider.settings.responsive) { $(window).unbind('resize', resizeWindow); }
      if (slider.settings.keyboardEnabled) { $(document).unbind('keydown', keyPress); }
      //remove self reference in data
      $(this).removeData('bxSlider');
    };

    /**
     * Reload the slider (revert all DOM changes, and re-initialize)
     */
    el.reloadSlider = function(settings) {
      if (settings !== undefined) { options = settings; }
      el.destroySlider();
      init();
      //store reference to self in order to access public functions later
      $(el).data('bxSlider', this);
    };

    init();

    $(el).data('bxSlider', this);

    // returns the current jQuery object
    return this;
  };

})(jQuery);






/*
 * Lightcase - jQuery Plugin
 * The smart and flexible Lightbox Plugin.
 *
 * @author    Cornel Boppart <cornel@bopp-art.com>
 * @copyright Author
 *
 * @version   2.5.0 (11/03/2018)
 */

;(function ($) {

  'use strict';

  var _self = {
    cache: {},

    support: {},

    objects: {},

    /**
     * Initializes the plugin
     *
     * @param {object}  options
     * @return  {object}
     */
    init: function (options) {
      return this.each(function () {
        $(this).unbind('click.lightcase').bind('click.lightcase', function (event) {
          event.preventDefault();
          $(this).lightcase('start', options);
        });
      });
    },

    /**
     * Starts the plugin
     *
     * @param {object}  options
     * @return  {void}
     */
    start: function (options) {
      _self.origin = lightcase.origin = this;

      _self.settings = lightcase.settings = $.extend(true, {
        idPrefix: 'lightcase-',
        classPrefix: 'lightcase-',
        attrPrefix: 'lc-',
        transition: 'elastic',
        transitionOpen: null,
        transitionClose: null,
        transitionIn: null,
        transitionOut: null,
        cssTransitions: true,
        speedIn: 250,
        speedOut: 250,
        width: null,
        height: null,
        maxWidth: 800,
        maxHeight: 500,
        forceWidth: false,
        forceHeight: false,
        liveResize: true,
        fullScreenModeForMobile: true,
        mobileMatchExpression: /(iphone|ipod|ipad|android|blackberry|symbian)/,
        disableShrink: false,
        fixedRatio: true,
        shrinkFactor: .75,
        overlayOpacity: .9,
        slideshow: false,
        slideshowAutoStart: true,
        breakBeforeShow: false,
        timeout: 5000,
        swipe: true,
        useKeys: true,
        useCategories: true,
        useAsCollection: false,
        navigateEndless: true,
        closeOnOverlayClick: true,
        title: null,
        caption: null,
        showTitle: true,
        showCaption: true,
        showSequenceInfo: true,
        inline: {
          width: 'auto',
          height: 'auto'
        },
        ajax: {
          width: 'auto',
          height: 'auto',
          type: 'get',
          dataType: 'html',
          data: {}
        },
        iframe: {
          width: 800,
          height: 500,
          frameborder: 0
        },
        flash: {
          width: 400,
          height: 205,
          wmode: 'transparent'
        },
        video: {
          width: 400,
          height: 225,
          poster: '',
          preload: 'auto',
          controls: true,
          autobuffer: true,
          autoplay: true,
          loop: false
        },
        attr: 'data-rel',
        href: null,
        type: null,
        typeMapping: {
          'image': 'jpg,jpeg,gif,png,bmp',
          'flash': 'swf',
          'video': 'mp4,mov,ogv,ogg,webm',
          'iframe': 'html,php',
          'ajax': 'json,txt',
          'inline': '#'
        },
        errorMessage: function () {
          return '<p class="' + _self.settings.classPrefix + 'error">' + _self.settings.labels['errorMessage'] + '</p>';
        },
        labels: {
          'errorMessage': 'Source could not be found...',
          'sequenceInfo.of': ' of ',
          'close': 'Close',
          'navigator.prev': 'Prev',
          'navigator.next': 'Next',
          'navigator.play': 'Play',
          'navigator.pause': 'Pause'
        },
        markup: function () {
          _self.objects.body.append(
            _self.objects.overlay = $('<div id="' + _self.settings.idPrefix + 'overlay"></div>'),
            _self.objects.loading = $('<div id="' + _self.settings.idPrefix + 'loading" class="' + _self.settings.classPrefix + 'icon-spin"></div>'),
            _self.objects.case = $('<div id="' + _self.settings.idPrefix + 'case" aria-hidden="true" role="dialog"></div>')
          );
          _self.objects.case.after(
            _self.objects.close = $('<a href="#" class="' + _self.settings.classPrefix + 'icon-close"><span>' + _self.settings.labels['close'] + '</span></a>'),
            _self.objects.nav = $('<div id="' + _self.settings.idPrefix + 'nav"></div>')
          );
          _self.objects.nav.append(
            _self.objects.prev = $('<a href="#" class="' + _self.settings.classPrefix + 'icon-prev"><span>' + _self.settings.labels['navigator.prev'] + '</span></a>').hide(),
            _self.objects.next = $('<a href="#" class="' + _self.settings.classPrefix + 'icon-next"><span>' + _self.settings.labels['navigator.next'] + '</span></a>').hide(),
            _self.objects.play = $('<a href="#" class="' + _self.settings.classPrefix + 'icon-play"><span>' + _self.settings.labels['navigator.play'] + '</span></a>').hide(),
            _self.objects.pause = $('<a href="#" class="' + _self.settings.classPrefix + 'icon-pause"><span>' + _self.settings.labels['navigator.pause'] + '</span></a>').hide()
          );
          _self.objects.case.append(
            _self.objects.content = $('<div id="' + _self.settings.idPrefix + 'content"></div>'),
            _self.objects.info = $('<div id="' + _self.settings.idPrefix + 'info"></div>')
          );
          _self.objects.content.append(
            _self.objects.contentInner = $('<div class="' + _self.settings.classPrefix + 'contentInner"></div>')
          );
          _self.objects.info.append(
            _self.objects.sequenceInfo = $('<div id="' + _self.settings.idPrefix + 'sequenceInfo"></div>'),
            _self.objects.title = $('<h4 id="' + _self.settings.idPrefix + 'title"></h4>'),
            _self.objects.caption = $('<p id="' + _self.settings.idPrefix + 'caption"></p>')
          );
        },
        onInit: {},
        onStart: {},
        onBeforeCalculateDimensions: {},
        onAfterCalculateDimensions: {},
        onBeforeShow: {},
        onFinish: {},
        onResize: {},
        onClose: {},
        onCleanup: {}
      },
      options,
      // Load options from data-lc-options attribute
      _self.origin.data ? _self.origin.data('lc-options') : {});

      _self.objects.document = $('html');
      _self.objects.body = $('body');

      // Call onInit hook functions
      _self._callHooks(_self.settings.onInit);

      _self.objectData = _self._setObjectData(this);

      _self._addElements();
      _self._open();

      _self.dimensions = _self.getViewportDimensions();
    },

    /**
     * Getter method for objects
     *
     * @param {string}  name
     * @return  {object}
     */
    get: function (name) {
      return _self.objects[name];
    },

    /**
     * Getter method for objectData
     *
     * @return  {object}
     */
    getObjectData: function () {
      return _self.objectData;
    },

    /**
     * Sets the object data
     *
     * @param {object}  object
     * @return  {object}  objectData
     */
    _setObjectData: function (object) {
      var $object = $(object),
        objectData = {
        this: $(object),
        title: _self.settings.title || $object.attr(_self._prefixAttributeName('title')) || $object.attr('title'),
        caption: _self.settings.caption || $object.attr(_self._prefixAttributeName('caption')) || $object.children('img').attr('alt'),
        url: _self._determineUrl(),
        requestType: _self.settings.ajax.type,
        requestData: _self.settings.ajax.data,
        requestDataType: _self.settings.ajax.dataType,
        rel: $object.attr(_self._determineAttributeSelector()),
        type: _self.settings.type || _self._verifyDataType(_self._determineUrl()),
        isPartOfSequence: _self.settings.useAsCollection || _self._isPartOfSequence($object.attr(_self.settings.attr), ':'),
        isPartOfSequenceWithSlideshow: _self._isPartOfSequence($object.attr(_self.settings.attr), ':slideshow'),
        currentIndex: $(_self._determineAttributeSelector()).index($object),
        sequenceLength: $(_self._determineAttributeSelector()).length
      };

      // Add sequence info to objectData
      objectData.sequenceInfo = (objectData.currentIndex + 1) + _self.settings.labels['sequenceInfo.of'] + objectData.sequenceLength;

      // Add next/prev index
      objectData.prevIndex = objectData.currentIndex - 1;
      objectData.nextIndex = objectData.currentIndex + 1;

      return objectData;
    },

    /**
     * Prefixes a data attribute name with defined name from 'settings.attrPrefix'
     * to ensure more uniqueness for all lightcase related/used attributes.
     *
     * @param {string}  name
     * @return  {string}
     */
    _prefixAttributeName: function (name) {
      return 'data-' + _self.settings.attrPrefix + name;
    },

    /**
     * Determines the link target considering 'settings.href' and data attributes
     * but also with a fallback to the default 'href' value.
     *
     * @return  {string}
     */
    _determineLinkTarget: function () {
      return _self.settings.href || $(_self.origin).attr(_self._prefixAttributeName('href')) || $(_self.origin).attr('href');
    },

    /**
     * Determines the attribute selector to use, depending on
     * whether categorized collections are beeing used or not.
     *
     * @return  {string}  selector
     */
    _determineAttributeSelector: function () {
      var $origin = $(_self.origin),
        selector = '';

      if (typeof _self.cache.selector !== 'undefined') {
        selector = _self.cache.selector;
      } else if (_self.settings.useCategories === true && $origin.attr(_self._prefixAttributeName('categories'))) {
        var categories = $origin.attr(_self._prefixAttributeName('categories')).split(' ');

        $.each(categories, function (index, category) {
          if (index > 0) {
            selector += ',';
          }
          selector += '[' + _self._prefixAttributeName('categories') + '~="' + category + '"]';
        });
      } else {
        selector = '[' + _self.settings.attr + '="' + $origin.attr(_self.settings.attr) + '"]';
      }

      _self.cache.selector = selector;

      return selector;
    },

    /**
     * Determines the correct resource according to the
     * current viewport and density.
     *
     * @return  {string}  url
     */
    _determineUrl: function () {
      var dataUrl = _self._verifyDataUrl(_self._determineLinkTarget()),
        width = 0,
        density = 0,
        supportLevel = '',
        url;

      $.each(dataUrl, function (index, src) {
        switch (_self._verifyDataType(src.url)) {
          case 'video':
            var video = document.createElement('video'),
              videoType = _self._verifyDataType(src.url) + '/' + _self._getFileUrlSuffix(src.url);

            // Check if browser can play this type of video format
            if (supportLevel !== 'probably' && supportLevel !== video.canPlayType(videoType) && video.canPlayType(videoType) !== '') {
              supportLevel = video.canPlayType(videoType);
              url = src.url;
            }
            break;
          default:
            if (
              // Check density
              _self._devicePixelRatio() >= src.density &&
              src.density >= density &&
              // Check viewport width
              _self._matchMedia()('screen and (min-width:' + src.width + 'px)').matches &&
              src.width >= width
            ) {
              width = src.width;
              density = src.density;
              url = src.url;
            }
            break;
        }
      });

      return url;
    },

    /**
     * Normalizes an url and returns information about the resource path,
     * the viewport width as well as density if defined.
     *
     * @param {string}  url Path to resource in format of an url or srcset
     * @return  {object}
     */
    _normalizeUrl: function (url) {
      var srcExp = /^\d+$/;

      return url.split(',').map(function (str) {
        var src = {
          width: 0,
          density: 0
        };

        str.trim().split(/\s+/).forEach(function (url, i) {
          if (i === 0) {
            return src.url = url;
          }

          var value = url.substring(0, url.length - 1),
            lastChar = url[url.length - 1],
            intVal = parseInt(value, 10),
            floatVal = parseFloat(value);
          if (lastChar === 'w' && srcExp.test(value)) {
            src.width = intVal;
          } else if (lastChar === 'h' && srcExp.test(value)) {
            src.height = intVal;
          } else if (lastChar === 'x' && !isNaN(floatVal)) {
            src.density = floatVal;
          }
        });

        return src;
      });
    },

    /**
     * Verifies if the link is part of a sequence
     *
     * @param {string}  rel
     * @param {string}  expression
     * @return  {boolean}
     */
    _isPartOfSequence: function (rel, expression) {
      var getSimilarLinks = $('[' + _self.settings.attr + '="' + rel + '"]'),
        regexp = new RegExp(expression);

      return (regexp.test(rel) && getSimilarLinks.length > 1);
    },

    /**
     * Verifies if the slideshow should be enabled
     *
     * @return  {boolean}
     */
    isSlideshowEnabled: function () {
      return (_self.objectData.isPartOfSequence && (_self.settings.slideshow === true || _self.objectData.isPartOfSequenceWithSlideshow === true));
    },

    /**
     * Loads the new content to show
     *
     * @return  {void}
     */
    _loadContent: function () {
      if (_self.cache.originalObject) {
        _self._restoreObject();
      }

      _self._createObject();
    },

    /**
     * Creates a new object
     *
     * @return  {void}
     */
    _createObject: function () {
      var $object;

      // Create object
      switch (_self.objectData.type) {
        case 'image':
          $object = $(new Image());
          $object.attr({
            // The time expression is required to prevent the binding of an image load
            'src': _self.objectData.url,
            'alt': _self.objectData.title
          });
          break;
        case 'inline':
          $object = $('<div class="' + _self.settings.classPrefix + 'inlineWrap"></div>');
          $object.html(_self._cloneObject($(_self.objectData.url)));

          // Add custom attributes from _self.settings
          $.each(_self.settings.inline, function (name, value) {
            $object.attr(_self._prefixAttributeName(name), value);
          });
          break;
        case 'ajax':
          $object = $('<div class="' + _self.settings.classPrefix + 'inlineWrap"></div>');

          // Add custom attributes from _self.settings
          $.each(_self.settings.ajax, function (name, value) {
            if (name !== 'data') {
              $object.attr(_self._prefixAttributeName(name), value);
            }
          });
          break;
        case 'flash':
          $object = $('<embed src="' + _self.objectData.url + '" type="application/x-shockwave-flash"></embed>');

          // Add custom attributes from _self.settings
          $.each(_self.settings.flash, function (name, value) {
            $object.attr(name, value);
          });
          break;
        case 'video':
          $object = $('<video></video>');
          $object.attr('src', _self.objectData.url);

          // Add custom attributes from _self.settings
          $.each(_self.settings.video, function (name, value) {
            $object.attr(name, value);
          });
          break;
        default:
          $object = $('<iframe></iframe>');
          $object.attr({
            'src': _self.objectData.url
          });

          // Add custom attributes from _self.settings
          $.each(_self.settings.iframe, function (name, value) {
            $object.attr(name, value);
          });
          break;
      }

      _self._addObject($object);
      _self._loadObject($object);
    },

    /**
     * Adds the new object to the markup
     *
     * @param {object}  $object
     * @return  {void}
     */
    _addObject: function ($object) {
      // Add object to content holder
      _self.objects.contentInner.html($object);

      // Start loading
      _self._loading('start');

      // Call onStart hook functions
      _self._callHooks(_self.settings.onStart);

      // Add sequenceInfo to the content holder or hide if its empty
      if (_self.settings.showSequenceInfo === true && _self.objectData.isPartOfSequence) {
        _self.objects.sequenceInfo.html(_self.objectData.sequenceInfo);
        _self.objects.sequenceInfo.show();
      } else {
        _self.objects.sequenceInfo.empty();
        _self.objects.sequenceInfo.hide();
      }
      // Add title to the content holder or hide if its empty
      if (_self.settings.showTitle === true && _self.objectData.title !== undefined && _self.objectData.title !== '') {
        _self.objects.title.html(_self.objectData.title);
        _self.objects.title.show();
      } else {
        _self.objects.title.empty();
        _self.objects.title.hide();
      }
      // Add caption to the content holder or hide if its empty
      if (_self.settings.showCaption === true && _self.objectData.caption !== undefined && _self.objectData.caption !== '') {
        _self.objects.caption.html(_self.objectData.caption);
        _self.objects.caption.show();
      } else {
        _self.objects.caption.empty();
        _self.objects.caption.hide();
      }
    },

    /**
     * Loads the new object
     *
     * @param {object}  $object
     * @return  {void}
     */
    _loadObject: function ($object) {
      // Load the object
      switch (_self.objectData.type) {
        case 'inline':
          if ($(_self.objectData.url)) {
            _self._showContent($object);
          } else {
            _self.error();
          }
          break;
        case 'ajax':
          $.ajax(
            $.extend({}, _self.settings.ajax, {
              url: _self.objectData.url,
              type: _self.objectData.requestType,
              dataType: _self.objectData.requestDataType,
              data: _self.objectData.requestData,
              success: function (data, textStatus, jqXHR) {
                // Check for X-Ajax-Location
                if (jqXHR.getResponseHeader('X-Ajax-Location')) {
                  _self.objectData.url = jqXHR.getResponseHeader('X-Ajax-Location');
                  _self._loadObject($object);
                }
                else {
                  // Unserialize if data is transferred as json
                  if (_self.objectData.requestDataType === 'json') {
                    _self.objectData.data = data;
                  } else {
                    $object.html(data);
                  }
                  _self._showContent($object);
                }
              },
              error: function (jqXHR, textStatus, errorThrown) {
                _self.error();
              }
            })
          );
          break;
        case 'flash':
          _self._showContent($object);
          break;
        case 'video':
          if (typeof($object.get(0).canPlayType) === 'function' || _self.objects.case.find('video').length === 0) {
            _self._showContent($object);
          } else {
            _self.error();
          }
          break;
        default:
          if (_self.objectData.url) {
            $object.on('load', function () {
              _self._showContent($object);
            });
            $object.on('error', function () {
              _self.error();
            });
          } else {
            _self.error();
          }
          break;
      }
    },

    /**
     * Throws an error message if something went wrong
     *
     * @return  {void}
     */
    error: function () {
      _self.objectData.type = 'error';
      var $object = $('<div class="' + _self.settings.classPrefix + 'inlineWrap"></div>');

      $object.html(_self.settings.errorMessage);
      _self.objects.contentInner.html($object);

      _self._showContent(_self.objects.contentInner);
    },

    /**
     * Calculates the dimensions to fit content
     *
     * @param {object}  $object
     * @return  {void}
     */
    _calculateDimensions: function ($object) {
      _self._cleanupDimensions();

      if (!$object) return;

      // Set default dimensions
      var dimensions = {
        ratio: 1,
        objectWidth: $object.attr('width') ? $object.attr('width') : $object.attr(_self._prefixAttributeName('width')),
        objectHeight: $object.attr('height') ? $object.attr('height') : $object.attr(_self._prefixAttributeName('height'))
      };

      if (!_self.settings.disableShrink) {
        // Add calculated maximum width/height to dimensions
        dimensions.maxWidth = parseInt(_self.dimensions.windowWidth * _self.settings.shrinkFactor);
        dimensions.maxHeight = parseInt(_self.dimensions.windowHeight * _self.settings.shrinkFactor);

        // If the auto calculated maxWidth/maxHeight greather than the user-defined one, use that.
        if (dimensions.maxWidth > _self.settings.maxWidth) {
          dimensions.maxWidth = _self.settings.maxWidth;
        }
        if (dimensions.maxHeight > _self.settings.maxHeight) {
          dimensions.maxHeight = _self.settings.maxHeight;
        }

        // Calculate the difference between screen width/height and image width/height
        dimensions.differenceWidthAsPercent = parseInt(100 / dimensions.maxWidth * dimensions.objectWidth);
        dimensions.differenceHeightAsPercent = parseInt(100 / dimensions.maxHeight * dimensions.objectHeight);

        switch (_self.objectData.type) {
          case 'image':
          case 'flash':
          case 'video':
          case 'iframe':
          case 'ajax':
          case 'inline':
            if (_self.objectData.type === 'image' || _self.settings.fixedRatio === true) {
              if (dimensions.differenceWidthAsPercent > 100 && dimensions.differenceWidthAsPercent > dimensions.differenceHeightAsPercent) {
                dimensions.objectWidth = dimensions.maxWidth;
                dimensions.objectHeight = parseInt(dimensions.objectHeight / dimensions.differenceWidthAsPercent * 100);
              }
              if (dimensions.differenceHeightAsPercent > 100 && dimensions.differenceHeightAsPercent > dimensions.differenceWidthAsPercent) {
                dimensions.objectWidth = parseInt(dimensions.objectWidth / dimensions.differenceHeightAsPercent * 100);
                dimensions.objectHeight = dimensions.maxHeight;
              }
              if (dimensions.differenceHeightAsPercent > 100 && dimensions.differenceWidthAsPercent < dimensions.differenceHeightAsPercent) {
                dimensions.objectWidth = parseInt(dimensions.maxWidth / dimensions.differenceHeightAsPercent * dimensions.differenceWidthAsPercent);
                dimensions.objectHeight = dimensions.maxHeight;
              }
              break;
            }
          case 'error':
            if (!isNaN(dimensions.objectWidth) && dimensions.objectWidth > dimensions.maxWidth) {
              dimensions.objectWidth = dimensions.maxWidth;
            }
            break;
          default:
            if ((isNaN(dimensions.objectWidth) || dimensions.objectWidth > dimensions.maxWidth) && !_self.settings.forceWidth) {
              dimensions.objectWidth = dimensions.maxWidth;
            }
            if (((isNaN(dimensions.objectHeight) && dimensions.objectHeight !== 'auto') || dimensions.objectHeight > dimensions.maxHeight) && !_self.settings.forceHeight) {
              dimensions.objectHeight = dimensions.maxHeight;
            }
            break;
        }
      }

      if (_self.settings.forceWidth) {
        try {
          dimensions.objectWidth = _self.settings[_self.objectData.type].width;
        } catch (e) {
          dimensions.objectWidth = _self.settings.width || dimensions.objectWidth;
        }

        dimensions.maxWidth = null;
      }
      if ($object.attr(_self._prefixAttributeName('max-width'))) {
        dimensions.maxWidth = $object.attr(_self._prefixAttributeName('max-width'));
      }

      if (_self.settings.forceHeight) {
        try {
          dimensions.objectHeight = _self.settings[_self.objectData.type].height;
        } catch (e) {
          dimensions.objectHeight = _self.settings.height || dimensions.objectHeight;
        }

        dimensions.maxHeight = null;
      }
      if ($object.attr(_self._prefixAttributeName('max-height'))) {
        dimensions.maxHeight = $object.attr(_self._prefixAttributeName('max-height'));
      }
      _self._adjustDimensions($object, dimensions);
    },

    /**
     * Adjusts the dimensions
     *
     * @param {object}  $object
     * @param {object}  dimensions
     * @return  {void}
     */
    _adjustDimensions: function ($object, dimensions) {
      // Adjust width and height
      $object.css({
        'width': dimensions.objectWidth,
        'height': dimensions.objectHeight,
        'max-width': dimensions.maxWidth,
        'max-height': dimensions.maxHeight
      });

      _self.objects.contentInner.css({
        'width': $object.outerWidth(),
        'height': $object.outerHeight(),
        'max-width': '100%'
      });

      _self.objects.case.css({
        'width': _self.objects.contentInner.outerWidth(),
        'max-width': '100%'
      });

      // Adjust margin
      _self.objects.case.css({
        'margin-top': parseInt(-(_self.objects.case.outerHeight() / 2)),
        'margin-left': parseInt(-(_self.objects.case.outerWidth() / 2))
      });
    },

    /**
     * Handles the _loading
     *
     * @param {string}  process
     * @return  {void}
     */
    _loading: function (process) {
      if (process === 'start') {
        _self.objects.case.addClass(_self.settings.classPrefix + 'loading');
        _self.objects.loading.show();
      } else if (process === 'end') {
        _self.objects.case.removeClass(_self.settings.classPrefix + 'loading');
        _self.objects.loading.hide();
      }
    },


    /**
     * Gets the client screen dimensions
     *
     * @return  {object}  dimensions
     */
    getViewportDimensions: function () {
      return {
        windowWidth: $(window).innerWidth(),
        windowHeight: $(window).innerHeight()
      };
    },

    /**
     * Verifies the url
     *
     * @param {string}  dataUrl
     * @return  {object}  dataUrl Clean url for processing content
     */
    _verifyDataUrl: function (dataUrl) {
      if (!dataUrl || dataUrl === undefined || dataUrl === '') {
        return false;
      }

      if (dataUrl.indexOf('#') > -1) {
        dataUrl = dataUrl.split('#');
        dataUrl = '#' + dataUrl[dataUrl.length - 1];
      }

      return _self._normalizeUrl(dataUrl.toString());
    },

      //
    /**
     * Tries to get the (file) suffix of an url
     *
     * @param {string}  url
     * @return  {string}
     */
    _getFileUrlSuffix: function (url) {
      var re = /(?:\.([^.]+))?$/;
      return re.exec(url.toLowerCase())[1];
    },

    /**
     * Verifies the data type of the content to load
     *
     * @param {string}      url
     * @return  {string|boolean}  Array key if expression matched, else false
     */
    _verifyDataType: function (url) {
      var typeMapping = _self.settings.typeMapping;

      // Early abort if dataUrl couldn't be verified
      if (!url) {
        return false;
      }

      // Verify the dataType of url according to typeMapping which
      // has been defined in settings.
      for (var key in typeMapping) {
        if (typeMapping.hasOwnProperty(key)) {
          var suffixArr = typeMapping[key].split(',');

          for (var i = 0; i < suffixArr.length; i++) {
            var suffix = suffixArr[i].toLowerCase(),
              regexp = new RegExp('\.(' + suffix + ')$', 'i'),
              str = url.toLowerCase().split('?')[0].substr(-5);

            if (regexp.test(str) === true || (key === 'inline' && (url.indexOf(suffix) > -1))) {
              return key;
            }
          }
        }
      }

      // If no expression matched, return 'iframe'.
      return 'iframe';
    },

    /**
     * Extends html markup with the essential tags
     *
     * @return  {void}
     */
    _addElements: function () {
      if (typeof _self.objects.case !== 'undefined' && $('#' + _self.objects.case.attr('id')).length) {
        return;
      }

      _self.settings.markup();
    },

    /**
     * Shows the loaded content
     *
     * @param {object}  $object
     * @return  {void}
     */
    _showContent: function ($object) {
      // Add data attribute with the object type
      _self.objects.document.attr(_self._prefixAttributeName('type'), _self.objectData.type);

      _self.cache.object = $object;

      // Call onBeforeShow hook functions
      _self._callHooks(_self.settings.onBeforeShow);

      if (_self.settings.breakBeforeShow) return;
      _self.show();
    },

    /**
     * Starts the 'inTransition'
     * @return  {void}
     */
    _startInTransition: function () {
      switch (_self.transition.in()) {
        case 'scrollTop':
        case 'scrollRight':
        case 'scrollBottom':
        case 'scrollLeft':
        case 'scrollHorizontal':
        case 'scrollVertical':
          _self.transition.scroll(_self.objects.case, 'in', _self.settings.speedIn);
          _self.transition.fade(_self.objects.contentInner, 'in', _self.settings.speedIn);
          break;
        case 'elastic':
          if (_self.objects.case.css('opacity') < 1) {
            _self.transition.zoom(_self.objects.case, 'in', _self.settings.speedIn);
            _self.transition.fade(_self.objects.contentInner, 'in', _self.settings.speedIn);
        }
        case 'fade':
        case 'fadeInline':
          _self.transition.fade(_self.objects.case, 'in', _self.settings.speedIn);
          _self.transition.fade(_self.objects.contentInner, 'in', _self.settings.speedIn);
          break;
        default:
          _self.transition.fade(_self.objects.case, 'in', 0);
          break;
      }

      // End loading.
      _self._loading('end');
      _self.isBusy = false;

      // Set index of the first item opened
      if (!_self.cache.firstOpened) {
        _self.cache.firstOpened = _self.objectData.this;
      }

      // Fade in the info with delay
      _self.objects.info.hide();
      setTimeout(function () {
         _self.transition.fade(_self.objects.info, 'in', _self.settings.speedIn);
      }, _self.settings.speedIn);

      // Call onFinish hook functions
      _self._callHooks(_self.settings.onFinish);
    },

    /**
     * Processes the content to show
     *
     * @return  {void}
     */
    _processContent: function () {
      _self.isBusy = true;

      // Fade out the info at first
      _self.transition.fade(_self.objects.info, 'out', 0);

      switch (_self.settings.transitionOut) {
        case 'scrollTop':
        case 'scrollRight':
        case 'scrollBottom':
        case 'scrollLeft':
        case 'scrollVertical':
        case 'scrollHorizontal':
          if (_self.objects.case.is(':hidden')) {
            _self.transition.fade(_self.objects.contentInner, 'out', 0);
            _self.transition.fade(_self.objects.case, 'out', 0, 0, function () {
              _self._loadContent();
            });
          } else {
            _self.transition.scroll(_self.objects.case, 'out', _self.settings.speedOut, function () {
              _self._loadContent();
            });
          }
          break;
        case 'fade':
          if (_self.objects.case.is(':hidden')) {
            _self.transition.fade(_self.objects.case, 'out', 0, 0, function () {
              _self._loadContent();
            });
          } else {
            _self.transition.fade(_self.objects.case, 'out', _self.settings.speedOut, 0, function () {
              _self._loadContent();
            });
          }
          break;
        case 'fadeInline':
        case 'elastic':
          if (_self.objects.case.is(':hidden')) {
            _self.transition.fade(_self.objects.case, 'out', 0, 0, function () {
              _self._loadContent();
            });
          } else {
            _self.transition.fade(_self.objects.contentInner, 'out', _self.settings.speedOut, 0, function () {
              _self._loadContent();
            });
          }
          break;
        default:
          _self.transition.fade(_self.objects.case, 'out', 0, 0, function () {
            _self._loadContent();
          });
          break;
      }
    },

    /**
     * Handles events for gallery buttons
     *
     * @return  {void}
     */
    _handleEvents: function () {
      _self._unbindEvents();

      _self.objects.nav.children().not(_self.objects.close).hide();

      // If slideshow is enabled, show play/pause and start timeout.
      if (_self.isSlideshowEnabled()) {
        // Only start the timeout if slideshow autostart is enabled and slideshow is not pausing
        if (
          (_self.settings.slideshowAutoStart === true || _self.isSlideshowStarted) &&
          !_self.objects.nav.hasClass(_self.settings.classPrefix + 'paused')
        ) {
          _self._startTimeout();
        } else {
          _self._stopTimeout();
        }
      }

      if (_self.settings.liveResize) {
        _self._watchResizeInteraction();
      }

      _self.objects.close.click(function (event) {
        event.preventDefault();
        _self.close();
      });

      if (_self.settings.closeOnOverlayClick === true) {
        _self.objects.overlay.css('cursor', 'pointer').click(function (event) {
          event.preventDefault();

          _self.close();
        });
      }

      if (_self.settings.useKeys === true) {
        _self._addKeyEvents();
      }

      if (_self.objectData.isPartOfSequence) {
        _self.objects.nav.attr(_self._prefixAttributeName('ispartofsequence'), true);
        _self.objects.nav.data('items', _self._setNavigation());

        _self.objects.prev.click(function (event) {
          event.preventDefault();

          if (_self.settings.navigateEndless === true || !_self.item.isFirst()) {
            _self.objects.prev.unbind('click');
            _self.cache.action = 'prev';
            _self.objects.nav.data('items').prev.click();

            if (_self.isSlideshowEnabled()) {
              _self._stopTimeout();
            }
          }
        });

        _self.objects.next.click(function (event) {
          event.preventDefault();

          if (_self.settings.navigateEndless === true || !_self.item.isLast()) {
            _self.objects.next.unbind('click');
            _self.cache.action = 'next';
            _self.objects.nav.data('items').next.click();

            if (_self.isSlideshowEnabled()) {
              _self._stopTimeout();
            }
          }
        });

        if (_self.isSlideshowEnabled()) {
          _self.objects.play.click(function (event) {
            event.preventDefault();
            _self._startTimeout();
          });
          _self.objects.pause.click(function (event) {
            event.preventDefault();
            _self._stopTimeout();
          });
        }

        // Enable swiping if activated
        if (_self.settings.swipe === true) {
          if ($.isPlainObject($.event.special.swipeleft)) {
            _self.objects.case.on('swipeleft', function (event) {
              event.preventDefault();
              _self.objects.next.click();
              if (_self.isSlideshowEnabled()) {
                _self._stopTimeout();
              }
            });
          }
          if ($.isPlainObject($.event.special.swiperight)) {
            _self.objects.case.on('swiperight', function (event) {
              event.preventDefault();
              _self.objects.prev.click();
              if (_self.isSlideshowEnabled()) {
                _self._stopTimeout();
              }
            });
          }
        }
      }
    },

    /**
     * Adds the key events
     *
     * @return  {void}
     */
    _addKeyEvents: function () {
      $(document).bind('keyup.lightcase', function (event) {
        // Do nothing if lightcase is in process
        if (_self.isBusy) {
          return;
        }

        switch (event.keyCode) {
          // Escape key
          case 27:
            _self.objects.close.click();
            break;
          // Backward key
          case 37:
            if (_self.objectData.isPartOfSequence) {
              _self.objects.prev.click();
            }
            break;
          // Forward key
          case 39:
            if (_self.objectData.isPartOfSequence) {
              _self.objects.next.click();
            }
            break;
        }
      });
    },

    /**
     * Starts the slideshow timeout
     *
     * @return  {void}
     */
    _startTimeout: function () {
      _self.isSlideshowStarted = true;

      _self.objects.play.hide();
      _self.objects.pause.show();

      _self.cache.action = 'next';
      _self.objects.nav.removeClass(_self.settings.classPrefix + 'paused');

      _self.timeout = setTimeout(function () {
        _self.objects.nav.data('items').next.click();
      }, _self.settings.timeout);
    },

    /**
     * Stops the slideshow timeout
     *
     * @return  {void}
     */
    _stopTimeout: function () {
      _self.objects.play.show();
      _self.objects.pause.hide();

      _self.objects.nav.addClass(_self.settings.classPrefix + 'paused');

      clearTimeout(_self.timeout);
    },

    /**
     * Sets the navigator buttons (prev/next)
     *
     * @return  {object}  items
     */
    _setNavigation: function () {
      var $links = $((_self.cache.selector || _self.settings.attr)),
        sequenceLength = _self.objectData.sequenceLength - 1,
        items = {
          prev: $links.eq(_self.objectData.prevIndex),
          next: $links.eq(_self.objectData.nextIndex)
        };

      if (_self.objectData.currentIndex > 0) {
        _self.objects.prev.show();
      } else {
        items.prevItem = $links.eq(sequenceLength);
      }
      if (_self.objectData.nextIndex <= sequenceLength) {
        _self.objects.next.show();
      } else {
        items.next = $links.eq(0);
      }

      if (_self.settings.navigateEndless === true) {
        _self.objects.prev.show();
        _self.objects.next.show();
      }

      return items;
    },

    /**
     * Item information/status
     *
     */
    item: {
      /**
       * Verifies if the current item is first item.
       *
       * @return  {boolean}
       */
      isFirst: function () {
        return (_self.objectData.currentIndex === 0);
      },

      /**
       * Verifies if the current item is first item opened.
       *
       * @return  {boolean}
       */
      isFirstOpened: function () {
        return _self.objectData.this.is(_self.cache.firstOpened);
      },

      /**
       * Verifies if the current item is last item.
       *
       * @return  {boolean}
       */
      isLast: function () {
        return (_self.objectData.currentIndex === (_self.objectData.sequenceLength - 1));
      }
    },

    /**
     * Clones the object for inline elements
     *
     * @param {object}  $object
     * @return  {object}  $clone
     */
    _cloneObject: function ($object) {
      var $clone = $object.clone(),
        objectId = $object.attr('id');

      // If element is hidden, cache the object and remove
      if ($object.is(':hidden')) {
        _self._cacheObjectData($object);
        $object.attr('id', _self.settings.idPrefix + 'temp-' + objectId).empty();
      } else {
        // Prevent duplicated id's
        $clone.removeAttr('id');
      }

      return $clone.show();
    },

    /**
     * Verifies if it is a mobile device
     *
     * @return  {boolean}
     */
    isMobileDevice: function () {
      var deviceAgent = navigator.userAgent.toLowerCase(),
        agentId = deviceAgent.match(_self.settings.mobileMatchExpression);

      return agentId ? true : false;
    },

    /**
     * Verifies if css transitions are supported
     *
     * @return  {string|boolean}  The transition prefix if supported, else false.
     */
    isTransitionSupported: function () {
      var body = _self.objects.body.get(0),
        isTransitionSupported = false,
        transitionMapping = {
          'transition': '',
          'WebkitTransition': '-webkit-',
          'MozTransition': '-moz-',
          'OTransition': '-o-',
          'MsTransition': '-ms-'
        };

      for (var key in transitionMapping) {
        if (transitionMapping.hasOwnProperty(key) && key in body.style) {
          _self.support.transition = transitionMapping[key];
          isTransitionSupported = true;
        }
      }

      return isTransitionSupported;
    },

    /**
     * Transition types
     *
     */
    transition: {
      /**
       * Returns the correct transition type according to the status of interaction.
       *
       * @return  {string}  Transition type
       */
      in: function () {
        if (_self.settings.transitionOpen && !_self.cache.firstOpened) {
          return _self.settings.transitionOpen;
        }
        return _self.settings.transitionIn;
      },

      /**
       * Fades in/out the object
       *
       * @param {object}  $object
       * @param {string}  type
       * @param {number}  speed
       * @param {number}  opacity
       * @param {function}  callback
       * @return  {void}    Animates an object
       */
      fade: function ($object, type, speed, opacity, callback) {
        var isInTransition = type === 'in',
          startTransition = {},
          startOpacity = $object.css('opacity'),
          endTransition = {},
          endOpacity = opacity ? opacity: isInTransition ? 1 : 0;

        if (!_self.isOpen && isInTransition) return;

        startTransition['opacity'] = startOpacity;
        endTransition['opacity'] = endOpacity;

        $object.css(_self.support.transition + 'transition', 'none');
        $object.css(startTransition).show();

        // Css transition
        if (_self.support.transitions) {
          endTransition[_self.support.transition + 'transition'] = speed + 'ms ease';

          setTimeout(function () {
            $object.css(endTransition);

            setTimeout(function () {
              $object.css(_self.support.transition + 'transition', '');

              if (callback && (_self.isOpen || !isInTransition)) {
                callback();
              }
            }, speed);
          }, 15);
        } else {
          // Fallback to js transition
          $object.stop();
          $object.animate(endTransition, speed, callback);
        }
      },

      /**
       * Scrolls in/out the object
       *
       * @param {object}  $object
       * @param {string}  type
       * @param {number}  speed
       * @param {function}  callback
       * @return  {void}    Animates an object
       */
      scroll: function ($object, type, speed, callback) {
        var isInTransition = type === 'in',
          transition = isInTransition ? _self.settings.transitionIn : _self.settings.transitionOut,
          direction = 'left',
          startTransition = {},
          startOpacity = isInTransition ? 0 : 1,
          startOffset = isInTransition ? '-50%' : '50%',
          endTransition = {},
          endOpacity = isInTransition ? 1 : 0,
          endOffset = isInTransition ? '50%' : '-50%';

        if (!_self.isOpen && isInTransition) return;

        switch (transition) {
          case 'scrollTop':
            direction = 'top';
            break;
          case 'scrollRight':
            startOffset = isInTransition ? '150%' : '50%';
            endOffset = isInTransition ? '50%' : '150%';
            break;
          case 'scrollBottom':
            direction = 'top';
            startOffset = isInTransition ? '150%' : '50%';
            endOffset = isInTransition ? '50%' : '150%';
            break;
          case 'scrollHorizontal':
            startOffset = isInTransition ? '150%' : '50%';
            endOffset = isInTransition ? '50%' : '-50%';
            break;
          case 'scrollVertical':
            direction = 'top';
            startOffset = isInTransition ? '-50%' : '50%';
            endOffset = isInTransition ? '50%' : '150%';
            break;
        }

        if (_self.cache.action === 'prev') {
          switch (transition) {
            case 'scrollHorizontal':
              startOffset = isInTransition ? '-50%' : '50%';
              endOffset = isInTransition ? '50%' : '150%';
              break;
            case 'scrollVertical':
              startOffset = isInTransition ? '150%' : '50%';
              endOffset = isInTransition ? '50%' : '-50%';
              break;
          }
        }

        startTransition['opacity'] = startOpacity;
        startTransition[direction] = startOffset;

        endTransition['opacity'] = endOpacity;
        endTransition[direction] = endOffset;

        $object.css(_self.support.transition + 'transition', 'none');
        $object.css(startTransition).show();

        // Css transition
        if (_self.support.transitions) {
          endTransition[_self.support.transition + 'transition'] = speed + 'ms ease';

          setTimeout(function () {
            $object.css(endTransition);

            setTimeout(function () {
              $object.css(_self.support.transition + 'transition', '');

              if (callback && (_self.isOpen || !isInTransition)) {
                callback();
              }
            }, speed);
          }, 15);
        } else {
          // Fallback to js transition
          $object.stop();
          $object.animate(endTransition, speed, callback);
        }
      },

      /**
       * Zooms in/out the object
       *
       * @param {object}  $object
       * @param {string}  type
       * @param {number}  speed
       * @param {function}  callback
       * @return  {void}    Animates an object
       */
      zoom: function ($object, type, speed, callback) {
        var isInTransition = type === 'in',
          startTransition = {},
          startOpacity = $object.css('opacity'),
          startScale = isInTransition ? 'scale(0.75)' : 'scale(1)',
          endTransition = {},
          endOpacity = isInTransition ? 1 : 0,
          endScale = isInTransition ? 'scale(1)' : 'scale(0.75)';

        if (!_self.isOpen && isInTransition) return;

        startTransition['opacity'] = startOpacity;
        startTransition[_self.support.transition + 'transform'] = startScale;

        endTransition['opacity'] = endOpacity;

        $object.css(_self.support.transition + 'transition', 'none');
        $object.css(startTransition).show();

        // Css transition
        if (_self.support.transitions) {
          endTransition[_self.support.transition + 'transform'] = endScale;
          endTransition[_self.support.transition + 'transition'] = speed + 'ms ease';

          setTimeout(function () {
            $object.css(endTransition);

            setTimeout(function () {
              $object.css(_self.support.transition + 'transform', '');
              $object.css(_self.support.transition + 'transition', '');

              if (callback && (_self.isOpen || !isInTransition)) {
                callback();
              }
            }, speed);
          }, 15);
        } else {
          // Fallback to js transition
          $object.stop();
          $object.animate(endTransition, speed, callback);
        }
      }
    },

    /**
     * Calls all the registered functions of a specific hook
     *
     * @param {object}  hooks
     * @return  {void}
     */
    _callHooks: function (hooks) {
      if (typeof(hooks) === 'object') {
        $.each(hooks, function(index, hook) {
          if (typeof(hook) === 'function') {
            hook.call(_self.origin);
          }
        });
      }
    },

    /**
     * Caches the object data
     *
     * @param {object}  $object
     * @return  {void}
     */
    _cacheObjectData: function ($object) {
      $.data($object, 'cache', {
        id: $object.attr('id'),
        content: $object.html()
      });

      _self.cache.originalObject = $object;
    },

    /**
     * Restores the object from cache
     *
     * @return  void
     */
    _restoreObject: function () {
      var $object = $('[id^="' + _self.settings.idPrefix + 'temp-"]');

      $object.attr('id', $.data(_self.cache.originalObject, 'cache').id);
      $object.html($.data(_self.cache.originalObject, 'cache').content);
    },

    /**
     * Executes functions for a window resize.
     * It stops an eventual timeout and recalculates dimensions.
     *
     * @param {object}  dimensions
     * @return  {void}
     */
    resize: function (event, dimensions) {
      if (!_self.isOpen) return;

      if (_self.isSlideshowEnabled()) {
        _self._stopTimeout();
      }

      if (typeof dimensions === 'object' && dimensions !== null) {
        if (dimensions.width) {
          _self.cache.object.attr(
            _self._prefixAttributeName('width'),
            dimensions.width
          );
        }
        if (dimensions.maxWidth) {
          _self.cache.object.attr(
            _self._prefixAttributeName('max-width'),
            dimensions.maxWidth
          );
        }
        if (dimensions.height) {
          _self.cache.object.attr(
            _self._prefixAttributeName('height'),
            dimensions.height
          );
        }
        if (dimensions.maxHeight) {
          _self.cache.object.attr(
            _self._prefixAttributeName('max-height'),
            dimensions.maxHeight
          );
        }
      }

      _self.dimensions = _self.getViewportDimensions();
      _self._calculateDimensions(_self.cache.object);

      // Call onResize hook functions
      _self._callHooks(_self.settings.onResize);
    },

    /**
     * Watches for any resize interaction and caches the new sizes.
     *
     * @return  {void}
     */
    _watchResizeInteraction: function () {
      $(window).resize(_self.resize);
    },

    /**
     * Stop watching any resize interaction related to _self.
     *
     * @return  {void}
     */
    _unwatchResizeInteraction: function () {
      $(window).off('resize', _self.resize);
    },

    /**
     * Switches to the fullscreen mode
     *
     * @return  {void}
     */
    _switchToFullScreenMode: function () {
      _self.settings.shrinkFactor = 1;
      _self.settings.overlayOpacity = 1;

      $('html').addClass(_self.settings.classPrefix + 'fullScreenMode');
    },

    /**
     * Enters into the lightcase view
     *
     * @return  {void}
     */
    _open: function () {
      _self.isOpen = true;

      _self.support.transitions = _self.settings.cssTransitions ? _self.isTransitionSupported() : false;
      _self.support.mobileDevice = _self.isMobileDevice();

      if (_self.support.mobileDevice) {
        $('html').addClass(_self.settings.classPrefix + 'isMobileDevice');

        if (_self.settings.fullScreenModeForMobile) {
          _self._switchToFullScreenMode();
        }
      }

      if (!_self.settings.transitionIn) {
        _self.settings.transitionIn = _self.settings.transition;
      }
      if (!_self.settings.transitionOut) {
        _self.settings.transitionOut = _self.settings.transition;
      }

      switch (_self.transition.in()) {
        case 'fade':
        case 'fadeInline':
        case 'elastic':
        case 'scrollTop':
        case 'scrollRight':
        case 'scrollBottom':
        case 'scrollLeft':
        case 'scrollVertical':
        case 'scrollHorizontal':
          if (_self.objects.case.is(':hidden')) {
            _self.objects.close.css('opacity', 0);
            _self.objects.overlay.css('opacity', 0);
            _self.objects.case.css('opacity', 0);
            _self.objects.contentInner.css('opacity', 0);
          }
          _self.transition.fade(_self.objects.overlay, 'in', _self.settings.speedIn, _self.settings.overlayOpacity, function () {
            _self.transition.fade(_self.objects.close, 'in', _self.settings.speedIn);
            _self._handleEvents();
            _self._processContent();
          });
          break;
        default:
          _self.transition.fade(_self.objects.overlay, 'in', 0, _self.settings.overlayOpacity, function () {
            _self.transition.fade(_self.objects.close, 'in', 0);
            _self._handleEvents();
            _self._processContent();
          });
          break;
      }

      _self.objects.document.addClass(_self.settings.classPrefix + 'open');
      _self.objects.case.attr('aria-hidden', 'false');
    },

    /**
     * Shows the lightcase by starting the transition
     */
    show: function () {
      // Call onCalculateDimensions hook functions
      _self._callHooks(_self.settings.onBeforeCalculateDimensions);

      _self._calculateDimensions(_self.cache.object);

      // Call onAfterCalculateDimensions hook functions
      _self._callHooks(_self.settings.onAfterCalculateDimensions);

      _self._startInTransition();
    },

    /**
     * Escapes from the lightcase view
     *
     * @return  {void}
     */
    close: function () {
      _self.isOpen = false;

      if (_self.isSlideshowEnabled()) {
        _self._stopTimeout();
        _self.isSlideshowStarted = false;
        _self.objects.nav.removeClass(_self.settings.classPrefix + 'paused');
      }

      _self.objects.loading.hide();

      _self._unbindEvents();

      _self._unwatchResizeInteraction();

      $('html').removeClass(_self.settings.classPrefix + 'open');
      _self.objects.case.attr('aria-hidden', 'true');

      _self.objects.nav.children().hide();
      _self.objects.close.hide();

      // Call onClose hook functions
      _self._callHooks(_self.settings.onClose);

      // Fade out the info at first
      _self.transition.fade(_self.objects.info, 'out', 0);

      switch (_self.settings.transitionClose || _self.settings.transitionOut) {
        case 'fade':
        case 'fadeInline':
        case 'scrollTop':
        case 'scrollRight':
        case 'scrollBottom':
        case 'scrollLeft':
        case 'scrollHorizontal':
        case 'scrollVertical':
          _self.transition.fade(_self.objects.case, 'out', _self.settings.speedOut, 0, function () {
            _self.transition.fade(_self.objects.overlay, 'out', _self.settings.speedOut, 0, function () {
              _self.cleanup();
            });
          });
          break;
        case 'elastic':
          _self.transition.zoom(_self.objects.case, 'out', _self.settings.speedOut, function () {
            _self.transition.fade(_self.objects.overlay, 'out', _self.settings.speedOut, 0, function () {
              _self.cleanup();
            });
          });
          break;
        default:
          _self.cleanup();
          break;
      }
    },

    /**
     * Unbinds all given events
     *
     * @return  {void}
     */
    _unbindEvents: function () {
      // Unbind overlay event
      _self.objects.overlay.unbind('click');

      // Unbind key events
      $(document).unbind('keyup.lightcase');

      // Unbind swipe events
      _self.objects.case.unbind('swipeleft').unbind('swiperight');

      // Unbind navigator events
      _self.objects.prev.unbind('click');
      _self.objects.next.unbind('click');
      _self.objects.play.unbind('click');
      _self.objects.pause.unbind('click');

      // Unbind close event
      _self.objects.close.unbind('click');
    },

    /**
     * Cleans up the dimensions
     *
     * @return  {void}
     */
    _cleanupDimensions: function () {
      var opacity = _self.objects.contentInner.css('opacity');

      _self.objects.case.css({
        'width': '',
        'height': '',
        'top': '',
        'left': '',
        'margin-top': '',
        'margin-left': ''
      });

      _self.objects.contentInner.removeAttr('style').css('opacity', opacity);
      _self.objects.contentInner.children().removeAttr('style');
    },

    /**
     * Cleanup after aborting lightcase
     *
     * @return  {void}
     */
    cleanup: function () {
      _self._cleanupDimensions();

      _self.objects.loading.hide();
      _self.objects.overlay.hide();
      _self.objects.case.hide();
      _self.objects.prev.hide();
      _self.objects.next.hide();
      _self.objects.play.hide();
      _self.objects.pause.hide();

      _self.objects.document.removeAttr(_self._prefixAttributeName('type'));
      _self.objects.nav.removeAttr(_self._prefixAttributeName('ispartofsequence'));

      _self.objects.contentInner.empty().hide();
      _self.objects.info.children().empty();

      if (_self.cache.originalObject) {
        _self._restoreObject();
      }

      // Call onCleanup hook functions
      _self._callHooks(_self.settings.onCleanup);

      // Restore cache
      _self.cache = {};
    },

    /**
     * Returns the supported match media or undefined if the browser
     * doesn't support match media.
     *
     * @return  {mixed}
     */
    _matchMedia: function () {
      return window.matchMedia || window.msMatchMedia;
    },

    /**
     * Returns the devicePixelRatio if supported. Else, it simply returns
     * 1 as the default.
     *
     * @return  {number}
     */
    _devicePixelRatio: function () {
      return window.devicePixelRatio || 1;
    },

    /**
     * Checks if method is public
     *
     * @return  {boolean}
     */
    _isPublicMethod: function (method) {
      return (typeof _self[method] === 'function' && method.charAt(0) !== '_');
    },

    /**
     * Exports all public methods to be accessible, callable
     * from global scope.
     *
     * @return  {void}
     */
    _export: function () {
      window.lightcase = {};

      $.each(_self, function (property) {
        if (_self._isPublicMethod(property)) {
          lightcase[property] = _self[property];
        }
      });
    }
  };

  _self._export();

  $.fn.lightcase = function (method) {
    // Method calling logic (only public methods are applied)
    if (_self._isPublicMethod(method)) {
      return _self[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'object' || !method) {
      return _self.init.apply(this, arguments);
    } else {
      $.error('Method ' + method + ' does not exist on jQuery.lightcase');
    }
  };
})(jQuery);