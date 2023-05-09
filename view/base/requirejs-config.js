var config = {
    map: {
        '*': {
            intlTelInput: 'CodeLands_MobileLogin/js/intl-tel-input',
            intlTelInputUtils: 'CodeLands_MobileLogin/js/utils',
            countryCode: 'CodeLands_MobileLogin/js/country-code'
        }
    },
    shim: {
        intlTelInput: {
            deps: ['jquery']
        }
    }
};
