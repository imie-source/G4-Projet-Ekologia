$(function () {
    var prototype = '<li><a href="__GROUP_URL__">__GROUP_NAME__</a></li>';

    $.ajax({
               url: Routing.generate('ekologia_user_group_list'),
               method: 'GET',
               success: function (data) {
                   $tbody = $('#group-list-search');
                   $tbody.empty();
                   for (var i = 0, c = data.groups.length; i < c; i++) {
                       var group = data.groups[i];
                       $tbody.append($(
                           prototype.replace('__GROUP_URL__', Routing.generate('ekologia_user_group_view', {id: group.id, _locale: constants.locale}))
                               .replace('__GROUP_NAME__', group.name)
                       ));
                   }
               },
               error: function (data) {
                   console.error(data.responseText)
               }
           })
});