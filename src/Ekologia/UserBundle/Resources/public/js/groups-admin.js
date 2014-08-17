$(function(){
    var prototypes = {
        trGroup: '<tr data-id="__GROUP_ID__">' +
            '<td>__GROUP_NAME__</td>' +
            '<td>__GROUP_DESCRIPTION__</td>' +
            '<td><a href="__GROUP_ADMINISTRATOR_ROUTE__">__GROUP_ADMINISTRATOR_NAME__</a></td>' +
            '<td>__GROUP_TYPE__</td>' +
            '<td>' +
                '<i class="glyphicon glyphicon-pencil group-update"></i> ' +
                '<i class="glyphicon glyphicon-remove group-remove"></i>' +
            '</td>' +
        '</tr>'
    }
    
    function createLine(group) {
        return $(prototypes.trGroup
            .replace('__GROUP_ID__', group.id)
            .replace('__GROUP_NAME__', group.name)
            .replace('__GROUP_DESCRIPTION__', group.description)
            .replace('__GROUP_ADMINISTRATOR_ROUTE__', '#')
            .replace('__GROUP_ADMINISTRATOR_NAME__', group.administrator.name)
            .replace('__GROUP_TYPE__', 'TODO'))
            .data('group', group)
    }
    
    function insertLine(group, prepend) {
        prepend = prepend ? prepend : false
        var $tr = createLine(group)
        if (prepend) {
            $('#group-list tbody').prepend($tr)
        } else {
            $('#group-list tbody').append($tr)
        }
    }
    
    function updateLine(group) {
        var $newLine = createLine(group)
        $newLine.replaceAll('#group-list tbody tr[data-id="' + group.id + '"]')
    }
    
    function deleteLine(groupId) {
        $('#group-list tbody tr[data-id="' + groupId + '"]').remove()
    }
    
    $('#group-create form').on('submit', function(event){
        event.preventDefault()
        $.ajax({
            url: Routing.generate('ekologia_user_group_create'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                if (data.valid) {
                    insertLine(data.group, true)
                } else {
                    console.error(data)
                }
            }, error: function(data) {
                console.error(data.responseText)
            }
        })
    })
    
    $('#group-update form').on('submit', function(event){
        event.preventDefault()
        var $form = $(this)
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function(data) {
                if (data.valid) {
                    updateLine(data.group)
                } else {
                    console.error(data)
                }
            }, error: function(data) {
                console.error(data.responseText)
            }
        })
    })
    
    $('#group-delete form').on('submit', function(event){
        event.preventDefault()
        var $form = $(this)
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function(data) {
                if (data.valid) {
                    deleteLine($form.data('groupid'))
                } else {
                    console.error(data)
                }
            }, error: function(data) {
                console.error(data.responseText)
            }
        })
    })
    
    $('#group-list tbody').on('click', '.group-update', function(event){
        var group = $(this).closest('tr').data('group')
        $('#group-update *[name="ekologia_user_grouptype[name]"]').val(group.name)
        $('#group-update *[name="ekologia_user_grouptype[description]"]').val(group.description)
        $('#group-update *[name="ekologia_user_grouptype[administrator]"]').val(group.administrator.id)
        $('#group-update form').attr('action', Routing.generate('ekologia_user_group_update', {id: group.id}))
    })
    
    $('#group-list tbody').on('click', '.group-remove', function(event){
        var group = $(this).closest('tr').data('group')
        $('#group-delete form')
                .attr('action', Routing.generate('ekologia_user_group_delete', {id: group.id}))
                .data('groupid', group.id)
    })
    
    $.ajax({
        url: Routing.generate('ekologia_user_group_list', {_locale: constants.locale}),
        method: 'GET',
        success: function(data){
            $('#group-list tbody').empty()
            for (var i = 0, c = data.groups.length ; i < c ; i++) {
                insertLine(data.groups[i])
            }
        }, error: function(data) {
            console.error(data.responseText)
        }
    })
})