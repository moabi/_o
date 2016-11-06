/**
 * FUNCTIONS
 */
//console.warn('add cdn jQuery V3');
/**
 * setActivityStatus
 * set the activity level
 * 0 : trip is not visible, no user validation
 * 1 : user has validated and ask for validation (can't edit anymore)
 * 2 : trip is refused
 * 3 : trip is validated by vendors
 * 4 : trip is validated by project manager && vendors
 * 5 : trip is validated by project manager && vendors && client
 * 6 : trip is done
 * 7 : trip is archived
 * @param status integer
 * @param activity_uuid integer
 */
function setActivityStatus(status,activity_uuid) {
    var statusInt = (status) ? parseInt(status,10) : false;
    var uuid = (activity_uuid) ? parseInt(activity_uuid,10) : false;
    if(statusInt && uuid){
        jQuery.ajax({
            url: ajaxUrl,
            data:{
                'action':'do_ajax',
                'type': 'setActivityStatus',
                'activity_status':status,
                'uuid'  : activity_uuid
            },
            //JSON can cause issues on Chrome ? use text instead ?
            dataType: 'JSON',
            success:function(data){
                console.log(data);
                var n = noty({
                    text: 'Changement effectué !',
                    template: '<div id="add_success" class="active"><span class="noty_text"></span><div class="noty_close"></div></div>'
                });

            },
            error: function(errorThrown){
                console.warn(errorThrown);
                var n = noty({
                    text: 'Echec du filtre de recherche :(',
                    template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
                });
            }
        });
    } else {
        var n = noty({
            text: 'Echec du changement de statut :(',
            template: '<div id="add_success" class="active error"><span class="noty_text"></span><div class="noty_close"></div></div>'
        });
    }

}

function ajaxPostRequest_vendor( id,target ){
    //console.log(type);
    jQuery.ajax({
        url: ajaxUrl,
        settings:{
            cache : true
        },
        data:{
            'action':'do_ajax',
            'id':id
        },
        //JSON can cause issues on Chrome ? use text instead ?
        dataType: 'JSON',
        success:function(data){

        },
        error: function(errorThrown){
            console.log(errorThrown);
            $(target).empty().append($('<div>', {
                html : 'Erreur du chargement,désolé pour cet inconvénient !'
            }));
        }
    });


}

function validateEditProduct(){

}
/**
 * ACTIONS
 */
jQuery(function () {
    $('#save_button').click(function (e) {
        console.log('test');
        $("[data-rules='required']:empty").each(function () {
           el = '#' + $(this).parents('.tabs-content').attr('id');
            //console.log(el);
            $('a[href="'+ el +'"]').css('color','red');
        });
    });
});