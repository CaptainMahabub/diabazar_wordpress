<?php

if ( ! class_exists( 'WP_Customize_Control' ) ) return NULL;

class Storevilla_Pro_General_Repeater extends WP_Customize_Control {
    private $options = array();

    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );
        $this->options = $args;
    }

    public function render_content() {

        $this_default = json_decode($this->setting->default);
        $values = $this->value();
        $json = json_decode($values);
        if(!is_array($json)) $json = array($values);
        $it = 0;
        $options = $this->options;
        if(!empty($options['image_control'])){
            $image_control = $options['image_control'];
        } else {
            $image_control = false;
        }
        if(!empty($options['icon_control'])){
            $icon_control = $options['icon_control'];
            $icons_array = array( 'No Icon','icon-social-blogger','icon-social-blogger-circle','icon-social-blogger-square');
        } else {
         $icon_control = false;
     }
     if(!empty($options['title_control'])){
        $title_control = $options['title_control'];
    } else {
        $title_control = false;
    }                           
    if(!empty($options['text_control'])){
        $text_control = $options['text_control'];
    } else {
        $text_control = false;
    }
    if(!empty($options['link_control'])){
        $link_control = $options['link_control'];
    } else {
        $link_control = false;
    }
    if(!empty($options['subtitle_control'])){
        $subtitle_control = $options['subtitle_control'];
    } else {
        $subtitle_control = false;
    } 
    ?>

    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
    <div class="store_villa_general_control_repeater store_villa_general_control_droppable">
        <?php if(empty($json)) { ?>
        <div class="store_villa_general_control_repeater_container">
            <div class="storevilla-customize-control-title"><?php esc_html_e('storevilla','storevilla')?></div>
            <div class="storevilla-box-content-hidden">
                <?php
                if($image_control == true && $icon_control == true){ ?>
                <span class="customize-control-title"><?php esc_html_e('Image type','storevilla');?></span>
                <select class="store_villa_image_choice">
                    <option value="storevilla_icon" selected><?php esc_html_e('Icon','storevilla'); ?></option>
                    <option value="storevilla_image"><?php esc_html_e('Image','storevilla'); ?></option>
                    <option value="storevilla_none"><?php esc_html_e('None','storevilla'); ?></option>
                </select>

                <p class="store_villa_image_control" style="display:none">
                    <span class="customize-control-title"><?php esc_html_e('Image','storevilla')?></span>
                    <input type="text" class="widefat custom_media_url">
                    <input type="button" class="button button-primary custom_media_button_store_villa" value="<?php esc_html_e('Upload Image','storevilla'); ?>" />
                </p>

                <div class="store_villa_general_control_icon">
                    <span class="customize-control-title"><?php esc_html_e('Icon','storevilla');?></span>
                    <select class="store_villa_icon_control">
                        <?php
                        foreach($icons_array as $contact_icon) {
                            echo '<option value="'.esc_attr($contact_icon).'">'.esc_attr($contact_icon).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php
            } else {
                if($image_control ==true){	?>
                <span class="customize-control-title"><?php esc_html_e('Image','storevilla')?></span>
                <p class="store_villa_image_control">
                    <input type="text" class="widefat custom_media_url">
                    <input type="button" class="button button-primary custom_media_button_store_villa" value="<?php esc_html_e('Upload Image','storevilla'); ?>" />
                </p>
                <?php
            }

            if($icon_control ==true){
                ?>
                <span class="customize-control-title"><?php esc_html_e('Icon','storevilla')?></span>
                <select name="<?php echo esc_attr($this->id); ?>" class="store_villa_icon_control">
                    <?php
                    foreach($icons_array as $contact_icon) {
                        echo '<option value="'.esc_attr($contact_icon).'">'.esc_attr($contact_icon).'</option>';
                    }
                    ?>
                </select>
                <?php   }
            }

            if($title_control==true){
                ?>
                <span class="customize-control-title"><?php esc_html_e('Title','storevilla')?></span>
                <input type="text" class="store_villa_title_control" placeholder="<?php esc_html_e('Title','storevilla'); ?>"/>
                <?php
            }

            if($text_control==true){?>
            <span class="customize-control-title"><?php esc_html_e('Text','storevilla')?></span>
            <textarea class="store_villa_text_control" placeholder="<?php esc_html_e('Text','storevilla'); ?>"></textarea>
            <?php }

            if($link_control==true){ ?>
            <span class="customize-control-title"><?php esc_html_e('Link','storevilla')?></span>
            <input type="text" class="store_villa_link_control" placeholder="<?php esc_html_e('Link','storevilla'); ?>"/>
            <?php }

            if($subtitle_control==true){
                ?>
                <span class="customize-control-title"><?php esc_html_e('Button Text','storevilla')?></span>
                <input type="text" class="store_villa_subtitle_control" placeholder="<?php esc_html_e('Button Text','storevilla'); ?>"/>
                <?php
            }
            ?>
            <input type="hidden" class="store_villa_box_id">
            <button type="button" class="store_villa_general_control_remove_field button" style="display:none;"><?php esc_html_e('Delete field','storevilla'); ?></button>
        </div>
    </div>
    <?php } else {
        if ( !empty($this_default) && empty($json)) {
            foreach($this_default as $icon){
    ?>
        <div class="store_villa_general_control_repeater_container store_villa_draggable">
            <div class="storevilla-customize-control-title"><?php esc_html_e('storevilla','storevilla')?></div>
            <div class="storevilla-box-content-hidden">
                <?php  if($image_control == true && $icon_control == true){ ?>
                    <span class="customize-control-title"><?php esc_html_e('Image type','storevilla');?></span>
                    <select class="store_villa_image_choice">
                        <option value="storevilla_icon" <?php selected($icon->choice,'storevilla_icon');?>><?php esc_html_e('Icon','storevilla');?></option>
                        <option value="storevilla_image" <?php selected($icon->choice,'storevilla_image');?>><?php esc_html_e('Image','storevilla');?></option>
                        <option value="storevilla_none" <?php selected($icon->choice,'storevilla_none');?>><?php esc_html_e('None','storevilla');?></option>
                    </select>

                    <p class="store_villa_image_control"  <?php if(!empty($icon->choice) && $icon->choice!='storevilla_image'){ echo 'style="display:none"';}?>>
                        <span class="customize-control-title"><?php esc_html_e('Image','storevilla');?></span>
                        <input type="text" class="widefat custom_media_url" value="<?php if(!empty($icon->image_url)) {echo esc_attr($icon->image_url);} ?>">
                        <input type="button" class="button button-primary custom_media_button_store_villa" value="<?php esc_html_e('Upload Image','storevilla'); ?>" />
                    </p>

                    <div class="store_villa_general_control_icon" <?php  if(!empty($icon->choice) && $icon->choice!='storevilla_icon'){ echo 'style="display:none"';}?>>
                        <span class="customize-control-title"><?php esc_html_e('Icon','storevilla');?></span>
                        <select name="<?php echo esc_attr($this->id); ?>" class="store_villa_icon_control">
                            <?php
                                foreach($icons_array as $contact_icon) {
                                    echo '<option value="'.esc_attr($contact_icon).'" '.selected($icon->icon_value,$contact_icon).'">'.esc_attr($contact_icon).'</option>';
                                }
                            ?>
                        </select>
                    </div>
                <?php  } else { ?>
                <?php	if($image_control==true){ ?>
                    <span class="customize-control-title"><?php esc_html_e('Image','storevilla')?></span>
                    <p class="store_villa_image_control">
                        <input type="text" class="widefat custom_media_url" value="<?php if(!empty($icon->image_url)) {echo esc_attr($icon->image_url);} ?>">
                        <input type="button" class="button button-primary custom_media_button_store_villa" value="<?php esc_html_e('Upload Image','storevilla'); ?>" />
                    </p>
                <?php	}  if($icon_control==true){ ?>
                    <span class="customize-control-title"><?php esc_html_e('Icon','storevilla')?></span>
                    <select name="<?php echo esc_attr($this->id); ?>" class="store_villa_icon_control">
                        <?php
                            foreach($icons_array as $contact_icon) {
                                echo '<option value="'.esc_attr($contact_icon).'" '.selected($icon->icon_value,$contact_icon).'">'.esc_attr($contact_icon).'</option>';
                            }
                        ?>
                    </select>
                <?php  } }
                    if($title_control==true){
                        ?>
                        <span class="customize-control-title"><?php esc_html_e('Title','storevilla')?></span>
                        <input type="text" value="<?php if(!empty($icon->title)) echo esc_attr($icon->title); ?>" class="store_villa_title_control" placeholder="<?php esc_html_e('Title','storevilla'); ?>"/>
                        <?php
                    }

                    if($text_control==true){ ?>
                        <span class="customize-control-title"><?php esc_html_e('Text','storevilla')?></span>
                        <textarea placeholder="<?php esc_html_e('Text','storevilla'); ?>" class="store_villa_text_control"><?php if(!empty($icon->text)) {echo esc_attr($icon->text);} ?></textarea>
                    <?php	}
                    if($link_control){ ?>
                        <span class="customize-control-title"><?php esc_html_e('Link','storevilla')?></span>
                        <input type="text" value="<?php if(!empty($icon->link)) echo esc_url($icon->link); ?>" class="store_villa_link_control" placeholder="<?php esc_html_e('Link','storevilla'); ?>"/>
                    <?php	}  if($subtitle_control==true){ ?>
                        <span class="customize-control-title"><?php esc_html_e('Button Text','storevilla')?></span>
                        <input type="text" value="<?php if(!empty($icon->subtitle)) echo esc_attr($icon->subtitle); ?>" class="store_villa_subtitle_control" placeholder="<?php esc_html_e('Button Text','storevilla'); ?>"/>
                        <?php  } ?>
                        <input type="hidden" class="store_villa_box_id" value="<?php if(!empty($icon->id)) echo esc_attr($icon->id); ?>">
                        <button type="button" class="store_villa_general_control_remove_field button" <?php if ($it == 0) echo 'style="display:none;"'; ?>><?php esc_html_e('Delete field','storevilla'); ?></button>
            </div>
        </div>

    <?php
        $it++; }  } else {
        foreach($json as $icon){
    ?>
    <div class="store_villa_general_control_repeater_container store_villa_draggable">
        <div class="storevilla-customize-control-title"><?php esc_html_e('Store Villa','storevilla')?></div>
        <div class="storevilla-box-content-hidden">
                <?php if($image_control == true && $icon_control == true){ ?>
                    <span class="customize-control-title"><?php esc_html_e('Image type','storevilla');?></span>
                    <select class="store_villa_image_choice">
                        <option value="storevilla_icon" <?php selected($icon->choice,'storevilla_icon');?>><?php esc_html_e('Icon','storevilla');?></option>
                        <option value="storevilla_image" <?php selected($icon->choice,'storevilla_image');?>><?php esc_html_e('Image','storevilla');?></option>
                        <option value="storevilla_none" <?php selected($icon->choice,'storevilla_none');?>><?php esc_html_e('None','storevilla');?></option>
                    </select>

                    <p class="store_villa_image_control" <?php if(!empty($icon->choice) && $icon->choice!='storevilla_image'){ echo 'style="display:none"';}?>>
                        <span class="customize-control-title"><?php esc_html_e('Image','storevilla');?></span>
                        <input type="text" class="widefat custom_media_url" value="<?php if(!empty($icon->image_url)) {echo esc_attr($icon->image_url);} ?>">
                        <input type="button" class="button button-primary custom_media_button_store_villa" value="<?php esc_html_e('Upload Image','storevilla'); ?>" />
                    </p>

                    <div class="store_villa_general_control_icon" <?php  if(!empty($icon->choice) && $icon->choice!='storevilla_icon'){ echo 'style="display:none"';}?>>
                        <span class="customize-control-title"><?php esc_html_e('Icon','storevilla');?></span>
                        <select name="<?php echo esc_attr($this->id); ?>" class="store_villa_icon_control">
                            <?php
                                foreach($icons_array as $contact_icon) {
                                    echo '<option value="'.esc_attr($contact_icon).'" '.selected($icon->icon_value,$contact_icon).'">'.esc_attr($contact_icon).'</option>';
                                }
                            ?>
                        </select>
                    </div>
                <?php } else { ?>
                    <?php if($image_control == true){ ?>
                    <span class="customize-control-title"><?php esc_html_e('Image','storevilla')?></span>
                    <p class="store_villa_image_control">
                        <input type="text" class="widefat custom_media_url" value="<?php if(!empty($icon->image_url)) {echo esc_attr($icon->image_url);} ?>">
                        <input type="button" class="button button-primary custom_media_button_store_villa" value="<?php esc_html_e('Upload Image','storevilla'); ?>" />
                    </p>
                <?php } if($icon_control==true){ ?>
                    <span class="customize-control-title"><?php esc_html_e('Icon','storevilla')?></span>
                    <select name="<?php echo esc_attr($this->id); ?>" class="store_villa_icon_control">
                        <?php
                            foreach($icons_array as $contact_icon) {
                                echo '<option value="'.esc_attr($contact_icon).'" '.selected($icon->icon_value,$contact_icon).'">'.esc_attr($contact_icon).'</option>';
                            }
                        ?>
                    </select>
                <?php } }  if($title_control==true){ ?>
                <span class="customize-control-title"><?php esc_html_e('Title','storevilla')?></span>
                <input type="text" value="<?php if(!empty($icon->title)) echo esc_attr($icon->title); ?>" class="store_villa_title_control" placeholder="<?php esc_html_e('Title','storevilla'); ?>"/>
                <?php } if($text_control==true ){?>
                <span class="customize-control-title"><?php esc_html_e('Text','storevilla')?></span>
                <textarea class="store_villa_text_control" placeholder="<?php esc_html_e('Text','storevilla'); ?>"><?php if(!empty($icon->text)) {echo esc_attr($icon->text);} ?></textarea>
                <?php }  if($link_control){ ?>
                <span class="customize-control-title"><?php esc_html_e('Link','storevilla')?></span>
                <input type="text" value="<?php if(!empty($icon->link)) echo esc_url($icon->link); ?>" class="store_villa_link_control" placeholder="<?php esc_html_e('Link','storevilla'); ?>"/>
                <?php } if($subtitle_control==true){  ?>
                    <span class="customize-control-title"><?php esc_html_e('Button Text','storevilla')?></span>
                    <input type="text" value="<?php if(!empty($icon->subtitle)) echo esc_attr($icon->subtitle); ?>" class="store_villa_subtitle_control" placeholder="<?php esc_html_e('Button Text','storevilla'); ?>"/>
                    <?php   }   ?>
                <input type="hidden" class="store_villa_box_id" value="<?php if(!empty($icon->id)) echo esc_attr($icon->id); ?>">
                <button type="button" class="store_villa_general_control_remove_field button" <?php if ($it == 0) echo 'style="display:none;"'; ?>><?php esc_html_e('Delete field','storevilla'); ?></button>
        </div>
    </div>
    <?php $it++; } } } if ( !empty($this_default) && empty($json)) { ?>
    <input type="hidden" id="store_villa_<?php echo $options['section']; ?>_repeater_colector" <?php $this->link(); ?> class="store_villa_repeater_colector" value="<?php  echo esc_textarea( json_encode($this_default )); ?>" />
    <?php } else {	?>
    <input type="hidden" id="store_villa_<?php echo $options['section']; ?>_repeater_colector" <?php $this->link(); ?> class="store_villa_repeater_colector" value="<?php echo esc_textarea( $this->value() ); ?>" />
    <?php } ?>
</div>
<button type="button"   class="button add_field store_villa_general_control_new_field"><?php esc_html_e('Add new field','storevilla'); ?></button>
<?php } }