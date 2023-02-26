jQuery(document).ready(function($){
    $('#filter').submit(function(){
        let filter = $('#filter');
        $.ajax({
            url:filter.attr('action'),
            data:filter.serialize(),
            type:filter.attr('method'),

            success:function(data){
                $('#response_data').html(data);
            }
        });
        return false;
    });
});
