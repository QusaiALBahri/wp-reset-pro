(function($){
    function getParts(){
        var parts=[];
        $('input[name="parts[]"]:checked').each(function(){ parts.push($(this).val()); });
        return parts;
    }
    function refreshCounts(){
        var parts = getParts();
        $.post(ajaxurl, {
            action: 'wp_reset_pro_counts',
            nonce: WPResetProCounts.nonce,
            parts: parts
        }, function(resp){
            if(resp && resp.success && resp.data){
                for (var k in resp.data){
                    $('[data-count="'+k+'"]').text(resp.data[k]);
                }
            }
        });
    }
    $(document).on('change', 'input[name="parts[]"]', refreshCounts);
    $(document).ready(function(){
        refreshCounts();
        // Modal confirm
        $('#wp-reset-pro-run').on('click', function(){
            $('#wp-reset-pro-modal').removeAttr('hidden');
        });
        $('.wp-reset-pro-cancel').on('click', function(){
            $('#wp-reset-pro-modal').attr('hidden', 'hidden');
        });
    });
})(jQuery);