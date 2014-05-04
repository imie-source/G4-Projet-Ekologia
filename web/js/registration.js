$(function(){
    var $subuser = $('#part-subuser')
    var $subuserRemoved = $('#part-subuser-removed')
    var $cuser = $('#part-cuser')
    var $puser = $('#part-puser')
    var $userType = $('#fos_user_registration_form_userType')

    $userType.on('change', function(){
        if ($userType.val() === 'cuser') {
            $subuser.append($cuser)
            $subuserRemoved.append($puser)
        } else {
            $subuser.append($puser)
            $subuserRemoved.append($cuser)
        }
    })
    $userType.change()
})
$(function(){
    var defaultSrc = $('#fos_user_registration_form_avatar').val();
    $('#fos_user_registration_form_avatar').on('change', function(){
        if ($(this).val() === '') {
            $(this).val(defaultSrc)
        }
        $('#registration-avatar-show').attr('src', $(this).val())
    })
})
$(function(){
    var index = $('input[name*="fos_user_registration_form[interests]').length
    var protoInput = $('#form-registration-interests').data('prototype')
    var protoList = $('#table-interest').data('prototype')
    
    $('#form-add-interest-add').on('click', function(e){
        e.preventDefault()
        var value = $('#form-add-interest-tag').val()
        if (value !== '') {
            var exists = false
            $('input[name*="fos_user_registration_form[interests]"]').each(function(){
                if ($(this).val() === value) {
                    exists = true
                }
            })
            
            if (!exists) {
                var $protoInput = $(protoInput.replace(/__name__/g, index))
                $protoInput.find('input').val(value)
                
                var $protoList = $(protoList.replace(/__value__/g, value).replace(/__name__/g, index))
                
                index++
                $('#form-registration-interests').append($protoInput)
                $('#table-interest tbody').append($protoList)
            }
        }
    })
    
    $('#table-interest').on('click', '.btn-danger', function(e){
        e.preventDefault()
        console.log($(this).data('target'))
        console.log()
        $('#fos_user_registration_form_interests_' + $(this).data('target')).parent().parent().remove()
        $(this).parent().parent().remove()
    })
    
    $('#form-registration').on('reset', function(){
        $('input[name*="fos_user_registration_form[interests]"]').parent().parent().remove()
        $('#table-interest tbody tr').remove()
    })
})