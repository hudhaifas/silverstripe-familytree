
var rebindAjaxmodal = function () {
    $(".ajax-modal").unbind("click");

    // Open modal in AJAX callback
    $('.ajax-modal').click(function (event) {
        event.preventDefault();
        loadModal(this.href);
    });

};

var rebindAutocomplete = function () {
    $('.field.autocomplete input.text').unbind('focus');

    // Load autocomplete functionality when field gets focused
    $('.field.autocomplete input.text').on('focus', function () {

        var input = $(this);

        // Prevent this field from loading itself multiple times
        if (input.attr('data-loaded') == 'true')
            return;
        input.attr('data-loaded', 'true');

        // load autocomplete into this field
        input.autocomplete({
            source: input.attr('data-source'),
            minLength: input.attr('data-min-length'),
            change: function (event, ui) {
                var hiddenInput = input.parent().find(':hidden');

                // Accept if item selected from list
                if (ui.item) {
                    hiddenInput.val(ui.item.stored);
                    return true;
                }

                // Check if a selection from the list is required
                if (!input.attr('data-require-selection')) {
                    // free text is allowed, use it
                    hiddenInput.val(input[0].value);

                    return true;
                }

                // remove invalid value, as it didn't match anything
                input.val("");
                input.data("autocomplete").term = "";
                return false;
            }
        });
    });

};

var loadModal = function (url) {
    $.get(url, function (html) {
//            console.log(html);
        var $content = $(html);
        $content.appendTo('body').modal({
            body: '#tree-container',
            closeText: '<span aria-hidden="true">×</span>',
            closeClass: 'close',
            fadeDuration: 400
        });

        $content.on($.modal.BEFORE_OPEN, function (event, modal) {
            $('.ajax-modal-nested').click(function (event) {
                event.preventDefault();
                loadModal(this.href);
            });
            $("body").addClass("modal-open");
        });

        $content.on($.modal.AFTER_CLOSE, function (event, modal) {
            $content.remove();
            $("body").removeClass("modal-open")
        });

        rebindAutocomplete();
    });
};