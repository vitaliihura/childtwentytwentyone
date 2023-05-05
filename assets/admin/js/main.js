jQuery(document).ready(function ($) {


    const loadingText = $('.add_services_test_data_ajax_loading');
    const addShortcodeOnHomeAjax = $('.add_shortcode_on_home_ajax');
    const addServicesTestDataAjax = $('.add_services_test_data_ajax');

    /**
     * Handler the event if the home page is static
     */
    addShortcodeOnHomeAjax.on('click', function (e) {
        e.preventDefault();

        loadingText.text('Loading...');
        loadingText.after('<p class="loading"></p>');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'add_shortcode_on_home_ajax'
            },
            success: function (response) {
                console.log('response ', response);

                loadingText.text('Done!');
                $('.wrap .loading').remove();

                setTimeout(function () {
                    loadingText.text('');
                }, 3000);
            },
            error: function (xhr, status, error) {
                console.error('Ajax request failed. Error:', error);
            }
        });
    });

    /**
     * Adding demo data for services in the admin
     */
    addServicesTestDataAjax.on('click', function (e) {
        e.preventDefault();

        loadingText.text('Loading...');
        loadingText.after('<p class="loading"></p>');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'add_services_test_data_ajax'
            },
            success: function (response) {
                console.log('response ', response);

                loadingText.text('Done!');
                $('.wrap .loading').remove();

                setTimeout(function () {
                    loadingText.text('');
                }, 3000);
            },
            error: function (xhr, status, error) {
                console.error('Ajax request failed. Error:', error);
            }
        });
    });

})