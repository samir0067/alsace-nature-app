/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'bootstrap';
import 'select2/dist/css/select2.css';

// any CSS you require will output into a single css file (app.css in this case)
require('../scss/app.scss');


// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
const autocomplete = require('autocomplete.js');
const select2 = require('select2/dist/js/select2.js');


// create global $ and jQuery variable
global.$ = $;
global.jQuery = $;
