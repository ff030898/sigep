/*
 Password Validator 0.1
 (c) 2007 Steven Levithan <stevenlevithan.com>
 MIT License
 */

function validatePassword(pw, options) {
//    // default options (allows any password)
//    var o = {
//        lower: 1,
//        upper: 1,
//        alpha: 1, /* lower + upper */
//        numeric: 1,
//        special: 1,
//        length: [8, Infinity],
//        badWords: ['lm', 'LM']
//    };
//
//    for (var property in options)
//        o[property] = options[property];
//
//    var re = {
//        lower: /[a-z]/g,
//        upper: /[A-Z]/g,
//        alpha: /[A-Z]/gi,
//        numeric: /[0-9]/g,
//        special: /[\W_]/g
//    },
//    rule, i;
//
//    // enforce min/max length
//    if (pw.length < o.length[0] || pw.length > o.length[1])
//        return false;
//    // enforce lower/upper/alpha/numeric/special rules
//    for (rule in re) {
//        if ((pw.match(re[rule]) || []).length < o[rule])
//            return false;
//    }
//
//    // enforce word ban (case insensitive)
//    for (i = 0; i < o.badWords.length; i++) {
//        if (pw.toLowerCase().indexOf(o.badWords[i].toLowerCase()) > -1)
//            return false;
//    }

    // great success!
    return true;
}