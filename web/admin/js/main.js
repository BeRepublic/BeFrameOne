$(document).on('ready', function(){
    $('select[multiple="multiple"]').multiselect().multiselectfilter();;


    activeBag();

    if($('#promotion_bag').length > 0){
        $('#promotion_bag').on('change', function(){
            activeBag();
        });
    }
});

function activeBag()
{
    if($('#promotion_bag').is(':checked')){
        $('.bag').removeAttr('disabled');
        $('.map').show();
    }
    else{
        $('.bag').attr('disabled', true);
        $('.map').hide();
    }
}