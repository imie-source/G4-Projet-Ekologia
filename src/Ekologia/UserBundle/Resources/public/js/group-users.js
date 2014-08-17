$(function () {
    $('#form-request *[name="ekologia_usergroup_request[confirm]"]').attr('checked', true);

    $('#user-list').on('click', '.accept-user', function () {
        var $that = $(this)
        $.ajax({
                   url: Routing.generate('ekologia_user_group_acceptfromgroup', {groupid: $(this).data('groupid'), userid: $(this).data('userid')}),
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