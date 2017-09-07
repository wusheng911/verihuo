function processSelectedFile(filePath, requestingField) {
    $('#' + requestingField).val(filePath).trigger('change');
    $('#img_'+requestingField).attr('src',filePath);
    $('#' + requestingField).after('<button type="button" class="btn btn-default btn-sm p-img-remove"><span class="glyphicon glyphicon-remove"></span></button>');
    $('.p-img-remove').click(function () {
        $(this).prev().attr('value','');
        $(this).parent().find('img').attr('src','/assets/img/article_icon_1_1.jpg');
        $(this).remove();
    });
}
$(document).ready(function () {
    $("#sortable li").each(function(){
        if($(this).find('input').attr('value')!='') {
            $(this).append('<button type="button" class="btn btn-default btn-sm p-img-remove"><span class="glyphicon glyphicon-remove"></span></button>');
        }
    });
    $('.p-img-remove').click(function () {
        $(this).prev().attr('value','');
        $(this).parent().find('img').attr('src','/assets/img/article_icon_1_1.jpg');
        $(this).remove();
    });
})