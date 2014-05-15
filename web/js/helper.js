$(function() {
    function tabs(id) {
        $('#'+id+' .links li').on('click', function(event) {
            $('#'+id+' .links li').removeClass('active')
            $(this).addClass('active')
            $('#'+id+' .tabs-content').addClass('hidden')
            $($(this).find('a').attr('href')).removeClass('hidden')
            event.preventDefault()
        })
        $('#'+id+' .tabs-content').addClass('hidden')
        $('#'+id+' .links li:first').click()
    }
    $('.tabs').each(function(index, element){
        tabs($(element).attr('id'))
    })
});