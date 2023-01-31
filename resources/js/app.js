import './bootstrap';

import 'flowbite';
import Alpine from 'alpinejs';
import moment from "moment-timezone";
import flatpckr from 'flatpickr';

window.Alpine = Alpine;
window.moment = moment;
window.flatpckr = flatpckr;

Alpine.start();
