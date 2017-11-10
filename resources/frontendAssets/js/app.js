
window.$ = window.jQuery = require('../vendor/jquery/jquery');
require('../vendor/jquery.appear/jquery.appear');
require('../vendor/jquery.easing/jquery.easing');
require('../vendor/jquery-cookie/jquery-cookie');

require('bootstrap-sass');

require('../vendor/common/common');
require('../vendor/jquery.lazyload/jquery.lazyload');
require('../vendor/owl.carousel/owl.carousel');
require('../vendor/magnific-popup/jquery.magnific-popup');
require('../vendor/vide/vide');

require('./theme');

/*require('../vendor/rs-plugin/js/jquery.themepunch.tools.min');
require('../vendor/rs-plugin/js/jquery.themepunch.revolution.min');
require('../vendor/circle-flip-slideshow/js/jquery.flipshow');*/

require('./theme.init');

require('../vendor/light-box/js/light-box');
require('./custom');
