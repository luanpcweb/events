$(function () {

    $('.datepicker').datepicker({
        clearBtn: true,
        format: "mm/dd/yyyy"
    });

    $('.time').mask('00:00:00');

});



function processando(faca)
{
    if (faca == "1") {
        $('#modal_processing').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }
    if (faca == "0") {
        $("#modal_processing").modal("hide");
    }

}

$(function() {
    function getDoc(frame) {
        var doc = null;

        // IE8 cascading access check
        try {
            if (frame.contentWindow) {
                doc = frame.contentWindow.document;
            }
        } catch(err) {
        }

        if (doc) { // successful getting content
            return doc;
        }

        try { // simply checking may throw in ie8 under ssl or mismatched protocol
            doc = frame.contentDocument ? frame.contentDocument : frame.document;
        } catch(err) {
            // last attempt
            doc = frame.document;
        }
        return doc;
    }

    $("#form").submit(function(e)
    {

        processando(1);

        var formObj = $(this);
        var formURL = route;
        var typeRequest = type;

        if(window.FormData !== undefined)  // for HTML5 browsers
            //  if(false)
        {

            var formData = new FormData(this);
            $.ajax({
                url: formURL,
                type: typeRequest,
                data:  formData,
                mimeType:"multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                success: function(data, textStatus, jqXHR)
                {
                    $("#statusForm").html(data);
                    setTimeout(function(){
                        processando(0);
                    }, 500);

                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    $("#statusForm").html(jqXHR.responseText);
                    setTimeout(function(){
                        processando(0);
                    }, 500);

                }
            });
            e.preventDefault();
            // e.unbind();
        }
        else  //for olden browsers
        {
            //generate a random id
            var  iframeId = 'unique' + (new Date().getTime());

            //create an empty iframe
            var iframe = $('<iframe src="javascript:false;" name="'+iframeId+'" />');

            //hide it
            iframe.hide();

            //set form target to iframe
            formObj.attr('target',iframeId);

            //Add iframe to body
            iframe.appendTo('body');
            iframe.load(function(e)
            {
                var doc = getDoc(iframe[0]);
                var docRoot = doc.body ? doc.body : doc.documentElement;
                var data = docRoot.innerHTML;

                processando(0);
                $("#statusForm").html('');

            });

        }

    });

    $(document).on("click", "#btnAction" ,function(){

        route = $(this).attr('route');
        type = 'POST';
        $("#form").submit();
    });

});


$(function() {
    $(document).on("click", ".btnActionDelete", function () {

        route = $(this).attr('route');
        id  = $(this).attr('id');

        bootbox.confirm({
            message: "Delete record??",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result) {

                    processando(1);
                    $.ajax({
                        type: "DELETE",
                        url: route,
                        data: {},
                        success: function(msg){
                            $('#status').html(msg);
                            setTimeout(function(){
                                processando(0);
                            }, 500);
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            $("#status").html(jqXHR.responseText);
                            setTimeout(function(){
                                processando(0);
                            }, 500);

                        }
                    });

                }
            }
        });
    })
});