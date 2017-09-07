/**
 * Created by wanghenshuai on 16/11/17.
 */

function processSelectedFile(filePath, requestingField) {
    $('#' + requestingField).val(filePath).trigger('change');
    $('#img_'+requestingField).attr('src',filePath);
}