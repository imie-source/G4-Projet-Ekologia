$(function () {
    $('.join-group').on('click', function () {
        $.ajax({
                   url: Routing.generate('ekologia_user_group_requestfromuser', {groupid: current_group.id, _locale: constants.locale, type: 'compose'}),
                   method: 'POST',
                   data: $('#form-request form').serialize(),
                   success: function (data) {
                       location.reload()
                   },
                   error: function (data) {
                       console.log(data.responseText)
                   }
               })
    })
})