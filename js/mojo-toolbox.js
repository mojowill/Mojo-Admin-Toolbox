jQuery(document).ready(function() {

  var formlabel = 0;
  var old_send_to_editor = window.send_to_editor;
  var old_tb_remove = window.tb_remove;

  jQuery('.upload_image_button').click(function(){
    formlabel = jQuery(this).parent();
    tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    return false;
  });

  window.tb_remove = function() {
    formlabel = 0;
    old_tb_remove();
  }

  window.send_to_editor = function(html) {
    if(formlabel){
      imgurl = jQuery('img',html).attr('src');
      formlabel.find('input[type="text"]').val(imgurl);
      tb_remove();
    } else {
      old_send_to_editor();
    }
  }
});