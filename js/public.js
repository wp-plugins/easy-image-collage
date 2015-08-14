// Based on https://github.com/kumailht/responsive-elements

var EIC_Responsive = {
    elementsSelector: '.eic-container',
    maxRefreshRate: 5,
    init: function() {
        var self = this;
        jQuery(function() {
            self.el = {
                window: jQuery(window),
                responsive_elements: jQuery(self.elementsSelector)
            };

            self.events();
        });
    },
    checkBreakpointOfAllElements: function() {
        var self = EIC_Responsive;
        self.el.responsive_elements.each(function(i, _el) {
            self.checkBreakpointOfElement(jQuery(_el));
        });
    },
    checkBreakpointOfElement: function(_el) {
        var frame = _el.find('.eic-frame');

        var container_width = _el.width();
        var frame_width = frame.outerWidth();
        var orig_frame_width = frame.data('orig-width');
        var frame_ratio = frame.data('ratio');

        // Frame resize required if container is smaller or frame is smaller than original width
        if(container_width < frame_width || frame_width < orig_frame_width) {
            var new_frame_width = container_width;
            if(new_frame_width > orig_frame_width) {
                new_frame_width = orig_frame_width;
            }

            var change_ratio = new_frame_width / orig_frame_width;

            // Borders
            var orig_border = frame.data('orig-border');
            var border = Math.ceil(orig_border * change_ratio);

            // Change frame styling
            frame
                .css('width', new_frame_width + 'px')
                .css('height', new_frame_width / frame_ratio + 'px')
                .css('border-width', border + 'px');

            _el.find('.eic-image').each(function() {
                var image = jQuery(this);
                var size_x = Math.ceil(image.data('size-x') * change_ratio);
                var size_y = Math.ceil(image.data('size-y') * change_ratio);
                var pos_x = Math.ceil(image.data('pos-x') * change_ratio);
                var pos_y = Math.ceil(image.data('pos-y') * change_ratio);

                // Change image styling
                image
                    .css('background-size', '' + size_x + 'px ' + size_y + 'px')
                    .css('background-position', '' + pos_x + 'px ' + pos_y + 'px')
                    .css('border-width', border + 'px');
            });
        }
    },
    events: function() {
        this.checkBreakpointOfAllElements();

        this.el.window.bind('resize', this.debounce(
            this.checkBreakpointOfAllElements, this.maxRefreshRate));
    },
    // Debounce is part of Underscore.js 1.5.2 http://underscorejs.org
    // (c) 2009-2013 Jeremy Ashkenas. Distributed under the MIT license.
    debounce: function(func, wait, immediate) {
        // Returns a function, that, as long as it continues to be invoked,
        // will not be triggered. The function will be called after it stops
        // being called for N milliseconds. If `immediate` is passed,
        // trigger the function on the leading edge, instead of the trailing.
        var result;
        var timeout = null;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) result = func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) result = func.apply(context, args);
            return result;
        };
    }
};

EIC_Responsive.init();