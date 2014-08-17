$(function () {
    $('#form-request *[name="ekologia_usergroup_request[confirm]"]').attr('checked', true);

    $('#group-list').on('click', '.accept-group', function () {
        var $that = $(this)
        $.ajax({
                   url: Routing.generate('ekologia_user_group_acceptfromuser', {groupid: $(this).data('groupid')}),
                   method: 'POST',
                   data: $('#form-request form').serialize(),
                   success: function (data) {
                       if (data.valid) {
                           location.reload()
                       } else {
                           alert(data.data)
                       }
                   },
                   error: function (data) {
                       console.error(data.responseText)
                   }
               });
    });
});
