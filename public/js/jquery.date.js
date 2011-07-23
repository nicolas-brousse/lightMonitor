/**
 * @author Trey Shugart
 * @date 2007-03-06
 * @license GNU LGPL (http://www.gnu.org/licenses/lgpl.html)
 */
(function($) {
        $.__GMTTimezoneOffset = 0, // Allows $.date and $.mktime to work with the default timezone set by javascript
        
        $.date = function(stringFormat, timestamp) {
                var date, dateString = '';
                
                // defaults
                if (typeof(stringFormat) === 'undefined')
                        stringFormat = 'Y-m-d h:i:s';
                if (typeof(timestamp) === 'undefined' || isNaN(timestamp))
                        timestamp = $.mktime();
                
                // date object, the timestamp should be in seconds not milliseconds
                date = new Date(timestamp * 1000);
                
                // store the different dates
                var dates = new Object;
                
                // day
                dates.d = _zeroPad(date.getDate()); // day number, with leading zeros, 01-31
                dates.D = _abbr(_dayName(date)); // day abbreviated, Sun to Sat
                dates.j = date.getDate(); // day number, no leading zeros, 1-31
                dates.l = _dayName(date); // day name, Sunday to Saturday
                dates.N = date.getDay() + 1 // day number, 1-7
                dates.S = _suffix(date); // day number suffix, st, nd, rd, th
                dates.w = date.getDay(); // day number as index, 0-6
                dates.z = (date.getMonth() + 1) * date.getDate(); // day number of the year, 0 - 365
                // week
                // month
                dates.F = _monthName(date); // month full textual representation, January to February
                dates.m = _zeroPad(date.getMonth() + 1); // month number, with leading zeroes, 01-12
                dates.M = _abbr(_monthName(date)); // month abbreviated, Jan to Feb
                dates.n = date.getMonth() + 1; // month number, no leading zeros,  1-12
                dates.t = _daysPerMonth(date); // days per month, 28 to 31
                // year
                dates.y = date.getFullYear().toString().substring(2, 4);
                dates.Y = date.getFullYear(); // full year representation, i.e. 2008
                // time
                dates.h = _zeroPad((date.getHours() > 12) ? (date.getHours() - 12) : date.getHours() == 0 ? 12 : date.getHours());
                dates.H = _zeroPad(date.getHours());
                dates.g = (date.getHours() > 12) ? (date.getHours() - 12) : date.getHours() == 0 ? 12 : date.getHours();
                dates.G = date.getHours();
                dates.i = _zeroPad(date.getMinutes());
                dates.s = _zeroPad(date.getSeconds());
                dates.a = (date.getHours () < 12) ? 'am' : 'pm';
                dates.A = (date.getHours () < 12) ? 'AM' : 'PM';
                // timezone
                        // to add later
                // full date/time
                        // to add later
                
                // format the date time string
                for (var i = 0; i < stringFormat.length; i++) {
                        var curChar = stringFormat.charAt(i);
                        if (dates[curChar] !== undefined)
                                dateString += dates[curChar];
                        else
                                dateString += curChar;
                };
                
                // return it
                return dateString;
                
                
                
                
                /* ---- UTILITY FUNCTIONS ---- */
                
                
                function _zeroPad(str, len) {
                        var pad;
                        var s = str.toString();
                        if (len === undefined)
                                len = 2;
                        for (var i = s.length; i < len; i++)
                                s = '0'+s;
                        return s;
                };
                
                function _dayName(date) {
                        var d = date.getDay();
                        var days = new Array(
                                'Sunday',
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday',
                                'Sunday'
                        );
                        return days[d];
                };
                
                function _monthName(date) {
                        var m = date.getMonth();
                        var months = new Array(
                                'January',
                                'February',
                                'March',
                                'April',
                                'May',
                                'June',
                                'July',
                                'August',
                                'September',
                                'October',
                                'November',
                                'December'
                        );
                        return months[m];
                };
                
                function _abbr(str, len) {
                        if (typeof(str) === 'undefined')
                                return false;
                        if (typeof(len) === 'undefined')
                                var len = 3;
                        var s = str.toString();
                        return s.substring(0, len);
                };
                
                function _suffix(date) {
                        var d = date.getDate();
                        var st = new Array(1, 21, 31);
                        var nd = new Array(2, 22);
                        var rd = new Array(3, 23);
                        if ($.inArray(d, st) != -1)
                                return 'st';
                        if ($.inArray(d, nd) != -1)
                                return 'nd';
                        if ($.inArray(d, rd) != -1)
                                return 'rd';
                        return 'th';
                };
                
                function _daysPerMonth(date) {
                        var d = date.getMonth();
                        var dpm = new Array(
                                31, // jan
                                ((_isLeapYear(date)) ? 29 : 28), // feb
                                31, // mar
                                30, // apr
                                31, // may
                                30, // jun
                                31, // jul
                                31, // aug
                                30, // sep
                                31, // oct
                                30, // nov
                                31 // dec
                        );
                        return dpm[d];
                };
                
                function _isLeapYear(date) {
                        var y = date.getYear();
                        if (y % 100 === 0) {
                                if (y % 400 === 0)
                                        return true;
                                return false;
                        }
                        if (y % 4 === 0)
                                return true;
                        return false;
                };
        };
        
        $.mktime = function() {
                var d = new Date(), thisDate = new Date();
                
                // time
                d.setSeconds((typeof arguments[2] !== 'undefined') ? parseInt(parseFloat(arguments[2])) : thisDate.getSeconds());
                d.setMinutes((typeof arguments[1] !== 'undefined') ? parseInt(parseFloat(arguments[1])) : thisDate.getMinutes());
                d.setHours((typeof arguments[0] !== 'undefined') ? parseInt(parseFloat(arguments[0])) : thisDate.getHours());
                // date
                d.setDate((typeof arguments[4] !== 'undefined') ? parseInt(parseFloat(arguments[4])) : thisDate.getDate());
                d.setMonth((typeof arguments[3] !== 'undefined') ? parseInt(parseFloat(arguments[3])) - 1 : thisDate.getMonth());
                d.setFullYear((typeof arguments[5] !== 'undefined') ? parseInt(parseFloat(arguments[5])) : thisDate.getFullYear());
                
                return Math.floor((d.getTime()) / 1000);
        };
        
        /**
         * Throw in the GMT Timezone offset hours (i.e. -8 for US, 0 for UK, 10 for AU) and 
         * $.date and $.mktime will work in relation to that timezone.
         * 
         * @param {Number} gmtHourOffset
         */
        $.timezone = function(gmtHourOffset) {
                if (typeof gmtHourOffset === 'undefined')
                        return $.__GMTTimezoneOffset;
                
                var date = new Date();
                var msec = date.getTime();
                var offset = -999999;
                
                for (var j = 0; j < 4; ++j) {
                        date.setTime(msec + j * 7884000000);
                        offset = Math.max(offset, date.getTimezoneOffset());
                }
                
                var localOffset = offset / 60;
                var differenceFromTarget = localOffset + gmtHourOffset;
                
                $.__GMTTimezoneOffset = differenceFromTarget;
        };
})(jQuery);