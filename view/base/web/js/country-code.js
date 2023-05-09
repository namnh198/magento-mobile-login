define([
    'jquery',
    'jquery/validate',
    'intlTelInput',
    'intlTelInputUtils'
], function ($) {
    'use strict';

    function changeLoginUser(userInput, userName){
        $('.codelands-user-name.codelands-mobile-number').hide();
        userInput.click(function(){
            let inputValue = $(this).attr('value');
            let targetValue = $("." + inputValue);
            let user =  $('.codelands-user-name');
            user.not(targetValue).hide();
            user.find('input').removeAttr('name');
            $(targetValue).show();
            $(targetValue).find('input').attr('name', userName);
        });
    }

    function setCountryDropdown(telInput, countryCode) {
        let countryCodeValue = countryCode.val();
        telInput.intlTelInput({
            separateDialCode: true,
            autoPlaceholder: false,
            formatOnDisplay: false,
            preventInvalidNumbers: true,
            preferredCountries: [],
            initialCountry: $.trim(countryCodeValue) ? countryCodeValue : 'auto',
            geoIpLookup: function(success, failure) {
                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : '';
                    success(countryCode);
                });
            },
            utilsScript: intlTelInputUtils
        });
    }

    function validateMobileNumber(telInput, countryCode) {
        $.validator.addMethod(
            'validate-mobile-number',
            function (value, element) {
                if (value) {
                    var $element = $(element);
                    if ($element.intlTelInput('isValidNumber')) {
                        telInput.val($element.intlTelInput('getNumber'));
                        countryCode.val($element.intlTelInput('getSelectedCountryData').iso2);
                        return true;
                    }
                    return false;
                }
            },
            $.mage.__('Please enter a valid mobile number.')
        );
    }

    return {
        changeLoginUser: changeLoginUser,
        setCountryDropdown: setCountryDropdown,
        validateMobileNumber: validateMobileNumber
    };
});
