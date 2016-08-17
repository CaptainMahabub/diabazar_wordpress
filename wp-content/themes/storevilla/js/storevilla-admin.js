
/********************************************
*** General Repeater ***
*********************************************/
function media_upload(button_class) {

    jQuery('body').on('click', button_class, function(e) {
        var button_id ='#'+jQuery(this).attr('id');
        var display_field = jQuery(this).parent().children('input:text');
        var _custom_media = true;

        wp.media.editor.send.attachment = function(props, attachment){

            if ( _custom_media  ) {
                if(typeof display_field != 'undefined'){
                    switch(props.size){
                        case 'full':
                            display_field.val(attachment.sizes.full.url);
                            display_field.trigger('change');
                            break;                       
                        default:
                            display_field.val(attachment.url);
                            display_field.trigger('change');
                    }
                }
                _custom_media = false;
            } else {
                return wp.media.editor.send.attachment( button_id, [props, attachment] );
            }
        }
        wp.media.editor.open(button_class);
        window.send_to_editor = function(html) {

        }
        return false;
    });
}

function accesspress_store_uniqid(prefix, more_entropy) {

  if (typeof prefix === 'undefined') {
    prefix = '';
  }

  var retId;
  var formatSeed = function(seed, reqWidth) {
    seed = parseInt(seed, 10)
      .toString(16); // to hex str
    if (reqWidth < seed.length) { // so long we split
      return seed.slice(seed.length - reqWidth);
    }
    if (reqWidth > seed.length) { // so short we pad
      return Array(1 + (reqWidth - seed.length))
        .join('0') + seed;
    }
    return seed;
  };

  // BEGIN REDUNDANT
  if (!this.php_js) {
    this.php_js = {};
  }
  // END REDUNDANT
  if (!this.php_js.uniqidSeed) { // init seed with big random int
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
  }
  this.php_js.uniqidSeed++;

  retId = prefix; // start with prefix, add current milliseconds hex string
  retId += formatSeed(parseInt(new Date()
    .getTime() / 1000, 10), 8);
  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
  if (more_entropy) {
    // for more entropy we add a float lower to 10
    retId += (Math.random() * 10)
      .toFixed(8)
      .toString();
  }

  return retId;
}

function accesspress_store_refresh_general_control_values(){
    jQuery(".accesspress_store_general_control_repeater").each(function(){
        var values = [];
        var th = jQuery(this);
        th.find(".accesspress_store_general_control_repeater_container").each(function(){
            var icon_value = jQuery(this).find('.accesspress_store_icon_control').val();
            var text = jQuery(this).find(".accesspress_store_text_control").val();
            var link = jQuery(this).find(".accesspress_store_link_control").val();
            var image_url = jQuery(this).find(".custom_media_url").val();
            var choice = jQuery(this).find(".accesspress_store_image_choice").val();
            var title = jQuery(this).find(".accesspress_store_title_control").val();
            var subtitle = jQuery(this).find(".accesspress_store_subtitle_control").val();
            var id = jQuery(this).find(".accesspress_store_box_id").val();
            if( text !='' || image_url!='' || title!='' || subtitle!='' ){
                values.push({
                    "icon_value" : (choice === 'accesspress_none' ? "" : icon_value) ,
                    "text" : text,
                    "link" : link,
                    "image_url" : (choice === 'accesspress_none' ? "" : image_url),
                    "choice" : choice,
                    "title" : title,
                    "subtitle" : subtitle,
                    "id" : id
                });
            }

        });

        th.find('.accesspress_store_repeater_colector').val(JSON.stringify(values));
        th.find('.accesspress_store_repeater_colector').trigger('change');
    });
}

jQuery(document).ready(function(){
    
     jQuery(".accesspress_store_general_control_new_field").on("click",function(){
        
        alert('sdfasdfasdfasdfasdf');
     
        var th = jQuery(this).parent();
        var id = 'accesspress_store_'+accesspress_store_uniqid();
        if(typeof th != 'undefined') {
            
            var field = th.find(".accesspress_store_general_control_repeater_container:first").clone();
            if(typeof field != 'undefined'){
                field.find(".accesspress_store_image_choice").val('accesspress_icon');
                field.find('.accesspress_store_general_control_icon').show();
                if(field.find('.accesspress_store_general_control_icon').length > 0){
                    field.find('.accesspress_store_image_control').hide();
                }
                field.find(".accesspress_store_general_control_remove_field").show();
                field.find(".accesspress_store_icon_control").val('');
                field.find(".accesspress_store_text_control").val('');
                field.find(".accesspress_store_link_control").val('');
                field.find(".accesspress_store_box_id").val(id);
                field.find(".custom_media_url").val('');
                field.find(".accesspress_store_title_control").val('');
                field.find(".accesspress_store_subtitle_control").val('');
                th.find(".accesspress_store_general_control_repeater_container:first").parent().append(field);
                accesspress_store_refresh_general_control_values();
            }
            
        }
        return false;
     });
   
    
    jQuery('#customize-theme-controls').on('click','.parallax-customize-control-title',function(){
        jQuery(this).next().show();
        jQuery(this).addClass('shown');
    });
    
    jQuery('#customize-theme-controls').on('click','.parallax-customize-control-title.shown',function(){
        jQuery(this).next().hide();
        jQuery(this).removeClass('shown');
    });

    jQuery('#customize-theme-controls').on('change','.accesspress_store_image_choice',function() {
        if(jQuery(this).val() == 'accesspress_image'){
            jQuery(this).parent().parent().find('.accesspress_store_general_control_icon').hide();
            jQuery(this).parent().parent().find('.accesspress_store_image_control').show();
        }
        if(jQuery(this).val() == 'accesspress_icon'){
            jQuery(this).parent().parent().find('.accesspress_store_general_control_icon').show();
            jQuery(this).parent().parent().find('.accesspress_store_image_control').hide();
        }
        if(jQuery(this).val() == 'accesspress_none'){
            jQuery(this).parent().parent().find('.accesspress_store_general_control_icon').hide();
            jQuery(this).parent().parent().find('.accesspress_store_image_control').hide();
        }
        
        accesspress_store_refresh_general_control_values();
        return false;        
    });
    media_upload('.custom_media_button_accesspress_store');
    jQuery(".custom_media_url").live('change',function(){
        accesspress_store_refresh_general_control_values();
        return false;
    });
    

    jQuery("#customize-theme-controls").on('change', '.accesspress_store_icon_control',function(){
        accesspress_store_refresh_general_control_values();
        return false; 
    });

     
    jQuery("#customize-theme-controls").on("click", ".accesspress_store_general_control_remove_field",function(){
        if( typeof  jQuery(this).parent() != 'undefined'){
            jQuery(this).parent().parent().remove();
            accesspress_store_refresh_general_control_values();
        }
        return false;
    });


    jQuery("#customize-theme-controls").on('keyup', '.accesspress_store_title_control',function(){
         accesspress_store_refresh_general_control_values();
    });

    jQuery("#customize-theme-controls").on('keyup', '.accesspress_store_subtitle_control',function(){
         accesspress_store_refresh_general_control_values();
    });
    
    jQuery("#customize-theme-controls").on('keyup', '.accesspress_store_text_control',function(){
         accesspress_store_refresh_general_control_values();
    });
    
    jQuery("#customize-theme-controls").on('keyup', '.accesspress_store_link_control',function(){
        accesspress_store_refresh_general_control_values();
    });
    
    /*Drag and drop to change icons order*/
    jQuery(".accesspress_store_general_control_droppable").sortable({
        update: function( event, ui ) {
            accesspress_store_refresh_general_control_values();
        }
    }); 
});