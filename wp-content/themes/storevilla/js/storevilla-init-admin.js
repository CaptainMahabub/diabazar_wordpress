jQuery(document).ready(function($){

	var storevilla_upload;
	var storevilla_selector;
    function storevilla_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		storevilla_selector = selector;

		event.preventDefault();
		if ( storevilla_upload ) {
			storevilla_upload.open();
		} else {
			storevilla_upload = wp.media.frames.storevilla_upload =  wp.media({
				title: $el.data('choose'),
				button: {
					text: $el.data('update'),
					close: false
				}
			});


			storevilla_upload.on( 'select', function() {
				var attachment = storevilla_upload.state().get('selection').first();
				storevilla_upload.close();
                storevilla_selector.find('.upload').val(attachment.attributes.url);
				if ( attachment.attributes.type == 'image' ) {
					storevilla_selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">Remove</a>').slideDown('fast');
				}
				storevilla_selector.find('.upload-button-wdgt').unbind().addClass('remove-file').removeClass('upload-button-wdgt').val(storevilla_l10n.remove);
				storevilla_selector.find('.of-background-properties').slideDown();
				storevilla_selector.find('.remove-image, .remove-file').on('click', function() {
					storevilla_remove_file( $(this).parents('.section') );
				});
			});
		}
		storevilla_upload.open();
	}

	function storevilla_remove_file(selector) {
		selector.find('.remove-image').hide();
		selector.find('.upload').val('');
		selector.find('.of-background-properties').hide();
		selector.find('.screenshot').slideUp();
		selector.find('.remove-file').unbind().addClass('upload-button-wdgt').removeClass('remove-file').val(storevilla_l10n.upload);
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.upload-button-wdgt').remove();
		}
		selector.find('.upload-button-wdgt').on('click', function(event) {
			storevilla_add_file(event, $(this).parents('.section'));
            
		});
	}

	$('body').on('click','.remove-image, .remove-file', function() {
		storevilla_remove_file( $(this).parents('.section') );
    });

    $(document).on('click', '.upload-button-wdgt', function( event ) {
    	storevilla_add_file(event, $(this).parents('.section'));
    });

});


/**
 * Store Villa Repeater Scripts
 */

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

function store_villa_uniqid(prefix, more_entropy) {

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

  if (!this.php_js) {
    this.php_js = {};
  }
  if (!this.php_js.uniqidSeed) { // init seed with big random int
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
  }
  this.php_js.uniqidSeed++;

  retId = prefix; // start with prefix, add current milliseconds hex string
  retId += formatSeed(parseInt(new Date()
    .getTime() / 1000, 10), 8);
  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
  if (more_entropy) {
    retId += (Math.random() * 10)
      .toFixed(8)
      .toString();
  }

  return retId;
}

function store_villa_refresh_general_control_values(){
    jQuery(".store_villa_general_control_repeater").each(function(){
        var values = [];
        var th = jQuery(this);
        th.find(".store_villa_general_control_repeater_container").each(function(){
            var icon_value = jQuery(this).find('.store_villa_icon_control').val();
            var text = jQuery(this).find(".store_villa_text_control").val();
            var link = jQuery(this).find(".store_villa_link_control").val();
            var image_url = jQuery(this).find(".custom_media_url").val();
            var choice = jQuery(this).find(".store_villa_image_choice").val();
            var title = jQuery(this).find(".store_villa_title_control").val();
            var subtitle = jQuery(this).find(".store_villa_subtitle_control").val();
            var id = jQuery(this).find(".store_villa_box_id").val();
            if( text !='' || image_url!='' || title!='' || subtitle!='' ){
                values.push({
                    "icon_value" : (choice === 'storevilla_none' ? "" : icon_value) ,
                    "text" : text,
                    "link" : link,
                    "image_url" : (choice === 'storevilla_none' ? "" : image_url),
                    "choice" : choice,
                    "title" : title,
                    "subtitle" : subtitle,
                    "id" : id
                });
            }

        });

        th.find('.store_villa_repeater_colector').val(JSON.stringify(values));
        th.find('.store_villa_repeater_colector').trigger('change');
    });
}

jQuery(document).ready(function(){
    jQuery('#customize-theme-controls').on('click','.storevilla-customize-control-title',function(){
        jQuery(this).next().show();
        jQuery(this).addClass('shown');
    });
    
    jQuery('#customize-theme-controls').on('click','.storevilla-customize-control-title.shown',function(){
        jQuery(this).next().hide();
        jQuery(this).removeClass('shown');
    });

    jQuery('#customize-theme-controls').on('change','.store_villa_image_choice',function() {
        if(jQuery(this).val() == 'storevilla_image'){
            jQuery(this).parent().parent().find('.store_villa_general_control_icon').hide();
            jQuery(this).parent().parent().find('.store_villa_image_control').show();
        }
        if(jQuery(this).val() == 'storevilla_icon'){
            jQuery(this).parent().parent().find('.store_villa_general_control_icon').show();
            jQuery(this).parent().parent().find('.store_villa_image_control').hide();
        }
        if(jQuery(this).val() == 'storevilla_none'){
            jQuery(this).parent().parent().find('.store_villa_general_control_icon').hide();
            jQuery(this).parent().parent().find('.store_villa_image_control').hide();
        }
        
        store_villa_refresh_general_control_values();
        return false;        
    });
    media_upload('.custom_media_button_store_villa');
    jQuery(".custom_media_url").live('change',function(){
        store_villa_refresh_general_control_values();
        return false;
    });
    

    jQuery("#customize-theme-controls").on('change', '.store_villa_icon_control',function(){
        store_villa_refresh_general_control_values();
        return false; 
    });

    jQuery(".store_villa_general_control_new_field").on("click",function(){
     
        var th = jQuery(this).parent();
        var id = 'store_villa_'+store_villa_uniqid();
        if(typeof th != 'undefined') {
            
            var field = th.find(".store_villa_general_control_repeater_container:first").clone();
            if(typeof field != 'undefined'){
                field.find(".store_villa_image_choice").val('storevilla_icon');
                field.find('.store_villa_general_control_icon').show();
                if(field.find('.store_villa_general_control_icon').length > 0){
                    field.find('.store_villa_image_control').hide();
                }
                field.find(".store_villa_general_control_remove_field").show();
                field.find(".store_villa_icon_control").val('');
                field.find(".store_villa_text_control").val('');
                field.find(".store_villa_link_control").val('');
                field.find(".store_villa_box_id").val(id);
                field.find(".custom_media_url").val('');
                field.find(".store_villa_title_control").val('');
                field.find(".store_villa_subtitle_control").val('');
                th.find(".store_villa_general_control_repeater_container:first").parent().append(field);
                store_villa_refresh_general_control_values();
            }
            
        }
        return false;
     });
     
    jQuery("#customize-theme-controls").on("click", ".store_villa_general_control_remove_field",function(){
        if( typeof  jQuery(this).parent() != 'undefined'){
            jQuery(this).parent().parent().remove();
            store_villa_refresh_general_control_values();
        }
        return false;
    });


    jQuery("#customize-theme-controls").on('keyup', '.store_villa_title_control',function(){
         store_villa_refresh_general_control_values();
    });

    jQuery("#customize-theme-controls").on('keyup', '.store_villa_subtitle_control',function(){
         store_villa_refresh_general_control_values();
    });
    
    jQuery("#customize-theme-controls").on('keyup', '.store_villa_text_control',function(){
         store_villa_refresh_general_control_values();
    });
    
    jQuery("#customize-theme-controls").on('keyup', '.store_villa_link_control',function(){
        store_villa_refresh_general_control_values();
    });
    
    /*Drag and drop to change icons order*/
    jQuery(".store_villa_general_control_droppable").sortable({
        update: function( event, ui ) {
            store_villa_refresh_general_control_values();
        }
    }); 
});

var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;',
};

function escapeHtml(string) {
  string = String(string).replace(new RegExp('\r?\n','g'), '<br />');
  string = String(string).replace(/\\/g,'&#92;');
  return String(string).replace(/[&<>"'\/]/g, function (s) {
    return entityMap[s];
  });      
}