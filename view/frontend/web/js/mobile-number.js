define([
    'jquery',
    'countryCode'
], function ($, countryCode) {
    'use strict';

    let mobileNumber = $('#mobile_number'),
        countryCodeInput = $('input[name="country_code"]'),
        fullMobileNumber = $('input[name="full_mobile_number"]');

    if($('.codelands-mobile-number-login-option').length) {
        countryCode.changeLoginUser($('input[name="user_option"]'), 'login[username]');
    }
    countryCode.setCountryDropdown(mobileNumber, countryCodeInput);
    countryCode.validateMobileNumber(fullMobileNumber, countryCodeInput);
});
