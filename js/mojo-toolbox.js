
jQuery(document).ready(function() {

jQuery('#login_logo_button').click(function() {
 formfield = jQuery('#login_logo').attr('name');
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});

window.send_to_editor = function(html) {
 imgurl = jQuery('img',html).attr('src');
 jQuery('#login_logo').val(imgurl);
 tb_remove();
}

});
