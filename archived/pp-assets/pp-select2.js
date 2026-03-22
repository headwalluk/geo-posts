/**
 * pp-select2.js
 */

(function ($) {
  'use strict';
  $(window).on('load', () => {
    $('.pp-select2').each((index, element) => {
      let options = element.dataset.ppSelect2;
      if (!options) {
        options = '{}';
      }

      options = JSON.parse(options);

      // console.log('PP Select2');
      // console.log(element);
      // console.log(options);

      $(window).trigger('ppSelect2Options', [options, element]);

      // console.log(options);

      $(element).select2(options);

      // ...
    });
  });
})(jQuery);
