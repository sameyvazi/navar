$(document).ready(function () {
    
    Layout.setMainMenuActiveLink('match');
    
    if ($.pjax) {
        $.pjax.defaults.timeout = 20000;
        $(document).on('pjax:send', function () {
            Loading.show('pjax-loader-container', false);
        });
        $(document).on('pjax:complete', function () {
            Loading.hide('pjax-loader-container', false);
        });
    }
    if(jQuery().fancybox) {
        $(".fancybox").fancybox({
           type: 'ajax',
           beforeLoad: function () {
               var link = $(this).attr("href");
               if (link == window.location.pathname || link == window.location) {
                   return false;
               }
           }
        });
    }
});
Navar = {
    clearCacheAndAssets: function(url, container){
        Loading.show(container, false);
        HttpRequest.post(url, null, function(){}, function(){
            Loading.hide(container, false);
        });
    },
    reloadPjax: function (container)
    {
        if ($.pjax)
        {
            $.pjax.reload({
                container: '#' + container
            });
        }
    },
    ajaxSubmitForm: function (formName, container) {
        var form = $("#" + formName);
        Loading.show(container, false);
        return HttpRequest.post(form.attr("action"), form.serialize(), function(data){
            form.yiiActiveForm('updateMessages', data, false);
        }, function(){
            Loading.hide(container, false);
        }, 'JSON');
    },
    ajaxLoadHtml: function (formName, container) {
        var form = $("#" + formName);
        Loading.show(container, false);
        return HttpRequest.post(form.attr("action"), form.serialize(), function(data){
             $("#" + container).html(data);
             App.init();
        }, function(){
            Loading.hide(container, false);
        }, 'HTML');
    },
    deleteGridButton: function (url, confirmMessage, pjaxGridName, messageContainer) {
        if (!confirm(confirmMessage))
        {
            return false;
        }
        if (messageContainer !== '') {
            $('#' + messageContainer).removeClass().html('');
        }
        Loading.show('pjax-loader-container', false);
        
        HttpRequest.post(url, null, function(data){
             if (!data.error) {
                if (pjaxGridName !== '') {
                    Navar.reloadPjax(pjaxGridName);
                }
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-success').html(data.message);
                }
            }
            else {
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-danger').html(data.message);
                }
            }
        }, function(){
            Loading.hide('pjax-loader-container', false);
        }, 'json');
        
        return false;
    },
    disableGridButton: function (url, pjaxGridName, messageContainer) {
        if (messageContainer !== '') {
            $('#' + messageContainer).removeClass().html('');
        }
        Loading.show('pjax-loader-container', false);
        
        HttpRequest.post(url, null, function(data){
             if (!data.error) {
                if (pjaxGridName !== '') {
                    Navar.reloadPjax(pjaxGridName);
                }
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-success').html(data.message);
                }
            }
            else {
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-danger').html(data.message);
                }
            }
        }, function(){
            Loading.hide('pjax-loader-container', false);
        }, 'json');
        
        return false;
    },
    massGridAction: function (formId, url, confirmMessage, pjaxGridName, messageContainer, data) {
        if (!confirm(confirmMessage))
        {
            return false;
        }
        if (messageContainer !== '') {
            $('#' + messageContainer).removeClass().html('');
        }
        var params = {
            ids: $('#' + formId).yiiGridView("getSelectedRows")
        };
        
        if (params.ids.length === 0)
        {
            return false;
        }
        if (data)
        {
            params.data = data;
        }
        
        Loading.show(formId, false);
        HttpRequest.post(url, params, function(data){
             if (!data.error) {
                if (pjaxGridName !== '') {
                    Navar.reloadPjax(pjaxGridName);
                }
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-success').html(data.message);
                }
            }
            else {
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-danger').html(data.message);
                }
            }
        }, function(){
            Loading.hide(formId, false);
        }, 'json');
        return false;
    },
    dropDownStatusGridAction: function (loadingContainer, url, messageContainer, data) {

        if (messageContainer !== '') {
            $('#' + messageContainer).removeClass().html('');
        }

        Loading.show(loadingContainer, false);
        HttpRequest.post(url, {data: data}, function(data){
             if (!data.error) {
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-success').html(data.message);
                }
            }
            else {
                if (messageContainer !== '') {
                    $('#' + messageContainer).addClass('alert alert-danger').html(data.message);
                }
            }
        }, function(){
            Loading.hide(loadingContainer, false);
        }, 'json');
        return false;
    }
};

HttpRequest = {
    post: function (url, params, success, finish, type) {
        if (type === null)
        {
            type = 'JSON';
        }
        return $.post(url, params, success, type).always(finish);
    },
    get: function (url, params, success, finish, type) {
        if (type === null)
        {
            type = 'JSON';
        }
        return $.get(url, params, success, type).always(finish);
    }
};

var Loading = {
    effect: {
        bounce: 'bounce',
        rotateplane: 'rotateplane',
        stretch: 'stretch',
        orbit: 'orbit',
        roundBounce: 'roundBounce',
        win8: 'win8',
        win8_linear: 'win8_linear',
        ios: 'ios',
        facebook: 'facebook',
        rotation: 'rotation',
        timer: 'timer',
        pulse: 'pulse',
        progressbar: 'progressbar',
        bouncePulse: 'bouncePulse'
    },
    color: '#F36F21',
    text: '',
    sizeW: '',
    sizeH: '',
    show: function (container, isBody) {
        if (!isBody) {
            container = "#" + container;
        }
        $(container).waitMe({effect: Loading.effect.bounce, text: Loading.text, color: Loading.color, sizeW: Loading.sizeW, sizeH: Loading.sizeH})
    },
    hide: function (container, isBody) {
        if (!isBody) {
            container = "#" + container;
        }
        $(container).waitMe("hide");
    }
};