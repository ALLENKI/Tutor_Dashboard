$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


if($.fn.modalmanager)
{
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
      '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">' +
          '<div class="progress-bar" style="width: 100%;"></div>' +
        '</div>' +
      '</div>';

    $.fn.modalmanager.defaults.resize = true;

    var $modal = $('#ajax-modal');

    function openAjaxModal(identifier)
    {
        var url = $(identifier).data('url');

        $('body').modalmanager('loading');

        setTimeout(function(){
              $modal.load(url, '', function(){
              $modal.modal();
            });
          }, 1000);
    }

    function openAjaxModalViaUrl(url,params)
    {
        $('body').modalmanager('loading');

        setTimeout(function(){
              $modal.load(url, params, function(){
              $modal.modal();
            });
          }, 1000);
    }
}