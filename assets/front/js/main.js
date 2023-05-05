jQuery(document).ready(function ($) {

    let loadMoreButton = $('.load-more-button');
    let page = 1;
    let maxPage = Number(loadMoreButton.attr('data-all-page'));
    let loadLine = $('.load-line');
    let line = loadLine.find('.line');

    if (maxPage !== page) {
        line.css('width', page / maxPage * 100 + '%');
    } else {
        loadLine.remove();
        loadMoreButton.remove();
    }

    /**
     * Ajax loading services on the page
     */
    loadMoreButton.on('click', function (e) {
        e.preventDefault();
        let $button = $(this),
            data = {
                'action': 'childtwentytwentyone_load_posts',
                'type': $button.data('type'),
                'paged': $button.data('paged'),
                'all-page': $button.data('all-page'),
            };

        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            dataType: 'html',
            type: 'POST',
            beforeSend: function (xhr) {
                $button.text('Loading...');
            },
            success: function (response) {
                if ($button.data('all-page') === $button.data('paged')) {
                    $button.hide();
                }
                if (response) {
                    $('.loop-services ul').append(response)
                    $button.data('paged', parseInt($button.data('paged')) + 1);
                    $button.text('Load more');
                    line.css('width', ($button.data('paged') - 1) / $button.data('all-page') * 100 + '%');
                }
            }
        });
    });

})