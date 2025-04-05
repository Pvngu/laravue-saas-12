import moment from "moment";
import './axiosBase';
import './axiosAdmin';
import './axiosFront';

moment.suppressDeprecationWarnings = true;
window.moment = moment;