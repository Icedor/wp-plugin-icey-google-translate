jQuery(document).ready(function($) {
    function updateHiddenInput() {
        var langs = [];
        $('#icey_gt_pills_container li').each(function() {
            langs.push($(this).data('code'));
        });
        $('#icey_gt_active_langs').val(langs.join(','));
    }

    $('#icey_gt_pills_container').sortable({
        update: function(event, ui) { updateHiddenInput(); }
    });

    $('#icey_gt_add_lang_btn').on('click', function() {
        var selected = $('#icey_gt_add_lang_select').find(':selected');
        var code = selected.val();
        var name = selected.data('name');

        if (!code) return;
        if ($('#icey_gt_pills_container li[data-code="'+code+'"]').length > 0) {
            alert(iceyGTAdminVars.langExistsMsg);
            return;
        }

        var pill = $('<li data-code="'+code+'" style="background: #fff; border: 1px solid #ccc; padding: 5px 10px; border-radius: 20px; cursor: move; display: flex; align-items: center; gap: 8px;">'+name+' <span class="remove-lang" style="color: red; cursor: pointer; font-weight: bold;">&times;</span></li>');
        $('#icey_gt_pills_container').append(pill);
        updateHiddenInput();
    });

    $(document).on('click', '.remove-lang', function() {
        $(this).parent('li').remove();
        updateHiddenInput();
    });
});