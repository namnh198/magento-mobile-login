var config = {
    map: {
        '*': {
            'Magento_Checkout/template/authentication.html':    'CodeLands_MobileLogin/template/authentication.html',
            changeEmailMobilePassword: 'CodeLands_MobileLogin/js/change-email-mobile-password'
        }
    },
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/authentication': {
                'CodeLands_MobileLogin/js/view/authentication': true
            }
        }
    }
};
