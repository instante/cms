/**

 */
define('instante/icmsEditor', ['instante/configurator', 'summernote'], function (configurator, summernote) {
    return new function () {
        var that = this;
        this.config = configurator.getConfig('instante/icmsEditor');

        this.saveText = function (content, ident) {
            $.ajax({
                url: that.config.saveUrl,
                data: {
                    ident: ident,
                    text: content
                }
            })
        };
        this.init = function () {
            $('[data-icms-button=edit]').on('click', function () {
                $(this).closest('[data-icms=container]').find('[data-icms=content]').summernote({
                    focus: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                        ['text', ['save','reset', 'close']]
                    ],
                    buttons: {
                        close: that.CloseButton,
                        save: that.SaveButton,
                        reset: that.ResetButton
                    }

                });
            });
        };
        this.CloseButton = function () {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-trash"/> Close',
                tooltip: 'close',
                click: function (e, a) {
                    var content = $(this).closest('[data-icms=container]').find('[data-icms=content]');
                    content.summernote('destroy');
                    $(".tooltip[role=tooltip]").remove();
                }
            });
            return button.render();
        }
        this.ResetButton = function () {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-trash"/> Reset',
                tooltip: 'reset',
                click: function (e, a) {
                    var content = $(this).closest('[data-icms=container]').find('[data-icms=content]');
                    content.summernote('reset');
                    content.summernote('destroy');
                    $(".tooltip[role=tooltip]").remove();
                }
            });
            return button.render();
        }
        this.SaveButton = function () {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-save"/> Save',
                tooltip: 'save',
                click: function (e, a) {
                    var content = $(this).closest('[data-icms=container]').find('[data-icms=content]');
                    that.saveText(content.summernote('code'), content.attr('data-icms-id'));
                    content.summernote('destroy');
                    $(".tooltip[role=tooltip]").remove();
                }
            });
            return button.render();
        }

        if(this.config.autoInit){
            this.init();
        }
    };
});
