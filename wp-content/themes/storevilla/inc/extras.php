<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Store_Villa
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function storevilla_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	
	if(is_singular(array( 'post','page' ))){
        global $post;
        $post_sidebar = get_post_meta($post->ID, 'storevilla_page_layouts', true);
        if(!$post_sidebar){
            $post_sidebar = 'rightsidebar';
        }
        $classes[] = $post_sidebar;
    }

    if ( is_woocommerce_activated() ) {
        
        if( is_product_category() || is_shop() ) {
            $woo_page_layout = get_theme_mod( 'storevilla_woocommerce_products_page_layout','rightsidebar' );
            if(!$woo_page_layout){
                $woo_page_layout = 'rightsidebar';
            }
            $classes[] = $woo_page_layout;
        }

        if( is_singular('product') ) {
            $woo_page_layout = get_theme_mod( 'storevilla_woocommerce_single_products_page_layout','rightsidebar' );
            if(!$woo_page_layout){
                $woo_page_layout = 'rightsidebar';
            }
            $classes[] = $woo_page_layout;
        }
    }

    $web_layout = get_theme_mod( 'storevilla_web_page_layout_options', 'disable' );
    if($web_layout == 'enable'){
        $classes[] = 'boxlayout';
    }else{
        $classes[] = 'fulllayout';
    }


	return $classes;
}
add_filter( 'body_class', 'storevilla_body_classes' );



/**
 * Query WooCommerce activation
 * @since  1.0.0
 */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/**
 * Schema type
 * @return string schema itemprop type
 * @since  1.0.0
 */
function storevilla_html_tag_schema() {
	$schema 	= 'http://schema.org/';
	$type 		= 'WebPage';

	// Is single post
	if ( is_singular( 'post' ) ) {
		$type 	= 'Article';
	}

	// Is author page
	elseif ( is_author() ) {
		$type 	= 'ProfilePage';
	}

	// Is search results page
	elseif ( is_search() ) {
		$type 	= 'SearchResultsPage';
	}

	echo 'itemscope="itemscope" itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}

/**
 * Storevilla Woocommerce Query
*/
if ( is_woocommerce_activated() ) {
    
    function storevilla_woocommerce_query($product_type, $product_category, $product_number){
    
        $product_args       =   '';
        
        global $product_label_custom;
    
        if($product_type == 'category'){
            $product_args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array('taxonomy'  => 'product_cat',
                     'field'     => 'id', 
                     'terms'     => $product_category                                                                 
                    )
                ),
                'posts_per_page' => $product_number
            );
        }
        
        elseif($product_type == 'latest_product'){
            $product_label_custom = __('New', 'storevilla');
            $product_args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array('taxonomy'  => 'product_cat',
                     'field'     => 'id', 
                     'terms'     => $product_category                                                                 
                    )
                ),
                'posts_per_page' => $product_number
            );
        }
        
        elseif($product_type == 'feature_product'){
            $product_args = array(
                'post_type'        => 'product',  
                'meta_key'         => '_featured',  
                'meta_value'       => 'yes', 
                'tax_query' => array(
                    array('taxonomy'  => 'product_cat',
                     'field'     => 'id', 
                     'terms'     => $product_category                                                                 
                    )
                ), 
                'posts_per_page'   => $product_number   
            );
        }
    
        elseif($product_type == 'upsell_product'){
            $product_args = array(
                'post_type'         => 'product',
                'posts_per_page'    => 10,
                'meta_key'          => 'total_sales',
                'orderby'           => 'meta_value_num',
                'posts_per_page'    => $product_number
            );
        }
    
        elseif($product_type == 'on_sale'){
            $product_args = array(
            'post_type'      => 'product',
            'posts_per_page'    => $product_number,
            'meta_query'     => array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'           => '_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                ),
                array( // Variable products type
                    'key'           => '_min_variation_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                )
            ));
        }
        
        return $product_args;
    }
}



/**
 * Advance WooCommerce Product Search With Category
*/
if(!function_exists ('storevilla_product_search')){
	
	function storevilla_product_search(){
		
		if ( is_woocommerce_activated() ) {
			
			$args = array(
				'number'     => '',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true
			);
			$product_categories = get_terms( 'product_cat', $args ); 
			$categories_show = '<option value="">'.__('All Categories','storevilla').'</option>';
			$check = '';
			if(is_search()){
				if(isset($_GET['term']) && $_GET['term']!=''){
					$check = $_GET['term'];	
				}
			}
			$checked = '';
			$allcat = __('All Categories','storevilla');
			$categories_show .= '<optgroup class="sv-advance-search" label="'.$allcat.'">';
			foreach($product_categories as $category){
				if(isset($category->slug)){
					if(trim($category->slug) == trim($check)){
						$checked = 'selected="selected"';
					}
					$categories_show  .= '<option '.$checked.' value="'.$category->slug.'">'.$category->name.'</option>';
					$checked = '';
				}
			}
			$categories_show .= '</optgroup>';
			$form = '<form role="search" method="get" id="searchform"  action="' . esc_url( home_url( '/'  ) ) . '">
						 <div class="sv_search_wrap">
                            <select class="sv_search_product false" name="term">'.$categories_show.'</select>
						 </div>
                         <div class="sv_search_form">
							 <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' .__('search entire store here','storevilla'). '" />
							 <button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
							 <input type="hidden" name="post_type" value="product" />
							 <input type="hidden" name="taxonomy" value="product_cat" />
						 </div>
					</form>';			
			echo $form;
		}		 
	}
}



/**
** Store_Villa payment logo section
**/

if ( ! function_exists( 'storevilla_payment_logo' ) ) {
	
    function storevilla_payment_logo() { 
      $payment_logo_one = esc_url( get_theme_mod('paymentlogo_image_one') );
      $payment_logo_two = esc_url( get_theme_mod('paymentlogo_image_two') );
      $payment_logo_three = esc_url( get_theme_mod('paymentlogo_image_three') );
      $payment_logo_four = esc_url( get_theme_mod('paymentlogo_image_four') );
      $payment_logo_five = esc_url( get_theme_mod('paymentlogo_image_five') );
      $payment_logo_six = esc_url( get_theme_mod('paymentlogo_image_six') );
  	?>
	    <div class="payment-accept">
	      <?php if(!empty($payment_logo_one)) { ?>
	          <img src="<?php echo esc_url($payment_logo_one)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_two)) { ?>
	          <img src="<?php echo esc_url($payment_logo_two)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_three)) { ?>
	          <img src="<?php echo esc_url($payment_logo_three)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_four)) { ?>
	          <img src="<?php echo esc_url($payment_logo_four)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_five)) { ?>
	          <img src="<?php echo esc_url($payment_logo_five)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_six)) { ?>
	          <img src="<?php echo esc_url($payment_logo_six)?>" alt="" />
	      <?php } ?>
	    </div>
  	<?php
	} 
}

/**
 * Limit word function 
 */
 
if ( ! function_exists( 'storevilla_word_count' ) ) {
	
    function storevilla_word_count($string, $limit) {
        $stringtags = strip_tags($string);
        $stringtags = strip_shortcodes($stringtags);
        $words = explode(' ', $stringtags);
        return implode(' ', array_slice($words, 0, $limit));
    }
}


/**
 * Store Villa Header Promo Function Area 
 */
 
if ( ! function_exists( 'storevilla_promo_area' ) ) {
	
    function storevilla_promo_area() {
        
        $header_promo = esc_attr( get_theme_mod( 'storevilla_main_header_promo_area', 'enable' ) );
        
        $promo_one_image = esc_url( get_theme_mod( 'storevilla_promo_area_one_image' ) );
        $promo_one_title = get_theme_mod( 'storevilla_promo_area_one_title' );
        $promo_one_desc = esc_textarea( get_theme_mod( 'storevilla_promo_area_one_desc' ) );
        $promo_one_link = esc_url( get_theme_mod( 'storevilla_promo_area_one_link' ) );
        
        $promo_two_image = esc_url( get_theme_mod( 'storevilla_promo_area_two_image' ) );
        $promo_two_title = get_theme_mod( 'storevilla_promo_area_two_title' );
        $promo_two_desc = esc_textarea( get_theme_mod( 'storevilla_promo_area_two_desc' ) );
        $promo_two_link = esc_url( get_theme_mod( 'storevilla_promo_area_two_link' ) );
    ?>
        <div class="banner-header-promo">
            <div class="store-promo-wrap">
                <a href="<?php echo $promo_one_link; ?>"/>
                    <div class="sv-promo-area promo-one" <?php if(!empty( $promo_one_image )) { ?> style="background-image:url(<?php echo $promo_one_image; ?>);"<?php } ?>>
                        <div class="promo-wrapper">
                            <?php if(!empty( $promo_one_title ) ) { ?><h2><?php echo $promo_one_title; ?></h2><?php } ?>
                            <?php if(!empty( $promo_one_desc ) ) { ?><p><?php echo $promo_one_desc; ?></p><?php } ?>
                        </div>
                    </div>
                </a>
            </div>

            <div class="store-promo-wrap">
                <a href="<?php echo $promo_two_link; ?>"/>
                    <div class="sv-promo-area" <?php if(!empty( $promo_two_image )) { ?> style="background-image:url(<?php echo $promo_two_image; ?>);"<?php } ?>>
                        <div class="promo-wrapper">
                            <?php if(!empty( $promo_two_title ) ) { ?><h2><?php echo $promo_two_title; ?></h2><?php } ?>
                            <?php if(!empty( $promo_two_desc ) ) { ?><p><?php echo $promo_two_desc; ?></p><?php } ?>
                        </div>
                    </div>
                </a>
            </div>
            
        </div>
    <?php
    }
}




/**
** Store Villa Service section
**/

if ( ! function_exists( 'storevilla_service_section' ) ) {
    
  function storevilla_service_section() {  

        $services_icon_one = esc_attr( get_theme_mod( 'storevilla_services_icon_one', 'fa fa-truck' ) );
        $service_title_one = esc_attr( get_theme_mod( 'storevilla_service_title_one','FREE SHIPPING WORLDWIDE' ) );
        $service_desc_one = esc_attr( get_theme_mod( 'storevilla_service_desc_one' ) );

        $services_icon_two = esc_attr( get_theme_mod( 'storevilla_services_icon_two', 'fa fa-headphones' ) );
        $service_title_two = esc_attr( get_theme_mod( 'storevilla_service_title_two', '24X7 CUSTOMER SUPPORT' ) );
        $service_desc_two = esc_attr( get_theme_mod( 'storevilla_service_desc_two' ) );

        $services_icon_three = esc_attr( get_theme_mod( 'storevilla_services_icon_three', 'fa fa-dollar' ) );
        $service_title_three = esc_attr( get_theme_mod( 'storevilla_service_title_three', 'MONEY BACK GUARANTEE' ) );
        $service_desc_three = esc_attr( get_theme_mod( 'storevilla_service_desc_three' ) );
       
        $service_area = esc_attr( get_theme_mod( 'storevilla_services_area_settings','enable' ) );

    if(!empty( $service_area ) && $service_area == 'enable') {
      ?>
      
        <div class="our-features-box clearfix">
        
            <div class="store-container">
             
                <div class="feature-box">
                  <span><i class="<?php if(!empty( $services_icon_one )) { echo $services_icon_one; } ?>">&nbsp;</i></span>
                  <div class="content">
                    <?php if(!empty( $service_title_one )) { ?>
                    <h3><?php echo $service_title_one; ?></h3>
                    <?php }  if(!empty( $service_desc_one )) { ?>
                    <p><?php echo $service_desc_one; ?></p>
                    <?php } ?>
                  </div>
                </div>

                <div class="feature-box">
                  <span><i class="<?php if(!empty( $services_icon_two )) { echo $services_icon_two; } ?>">&nbsp;</i></span>
                  <div class="content">
                    <?php if(!empty( $service_title_two )) { ?>
                    <h3><?php echo $service_title_two; ?></h3>
                    <?php }  if(!empty( $service_desc_two )) { ?>
                    <p><?php echo $service_desc_two; ?></p>
                    <?php } ?>
                  </div>
                </div>

                <div class="feature-box">
                  <span><i class="<?php if(!empty( $services_icon_three )) { echo $services_icon_three; } ?>">&nbsp;</i></span>
                  <div class="content">
                    <?php if(!empty( $service_title_three )) { ?>
                    <h3><?php echo $service_title_three; ?></h3>
                    <?php }  if(!empty( $service_desc_three )) { ?>
                    <p><?php echo $service_desc_three; ?></p>
                    <?php } ?>
                  </div>
                </div>
          
            </div>

        </div>
    <?php  }
    
    }
}






/**
 * Page and Post Page Display Layout Metabox function
 */
 
add_action('add_meta_boxes', 'storevilla_metabox_section');

if ( ! function_exists( 'storevilla_metabox_section' ) ) {
	
    function storevilla_metabox_section(){   
        add_meta_box('storevilla_display_layout', 
            __( 'Display Layout Options', 'storevilla' ), 
            'storevilla_display_layout_callback', 
            array('page','post'), 
            'normal', 
            'high'
        );
    }
}

$storevilla_page_layouts =array(

    'leftsidebar' => array(
        'value'     => 'leftsidebar',
        'label'     => __( 'Left Sidebar', 'storevilla' ),
        'thumbnail' => get_template_directory_uri() . '/images/left-sidebar.png',
    ),
    'rightsidebar' => array(
        'value'     => 'rightsidebar',
        'label'     => __( 'Right Sidebar(Default)', 'storevilla' ),
        'thumbnail' => get_template_directory_uri() . '/images/right-sidebar.png',
    ),
     'nosidebar' => array(
        'value'     => 'nosidebar',
        'label'     => __( 'Full width', 'storevilla' ),
        'thumbnail' => get_template_directory_uri() . '/images/no-sidebar.png',
    ),
    'bothsidebar' => array(
        'value'     => 'bothsidebar',
        'label'     => __( 'Both Sidebar', 'storevilla' ),
        'thumbnail' => get_template_directory_uri() . '/images/both-sidebar.png',
    )
);

/**
 * Function for Page layout meta box
*/

if ( ! function_exists( 'storevilla_display_layout_callback' ) ) {
    function storevilla_display_layout_callback(){
        global $post, $storevilla_page_layouts;
        wp_nonce_field( basename( __FILE__ ), 'storevilla_settings_nonce' );
    ?>
        <table class="form-table">
            <tr>
              <td>            
                <?php
                  $i = 0;  
                  foreach ($storevilla_page_layouts as $field) {  
                  $storevilla_page_metalayouts = get_post_meta( $post->ID, 'storevilla_page_layouts', true ); 
                ?>            
                  <div class="radio-image-wrapper slidercat" id="slider-<?php echo $i; ?>" style="float:left; margin-right:30px;">
                    <label class="description">
                        <span>
                          <img src="<?php echo esc_url( $field['thumbnail'] ); ?>" />
                        </span></br>
                        <input type="radio" name="storevilla_page_layouts" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], 
                            $storevilla_page_metalayouts ); if(empty($storevilla_page_metalayouts) && $field['value']=='rightsidebar'){ echo "checked='checked'";  } ?>/>
                         <?php echo $field['label']; ?>
                    </label>
                  </div>
                <?php  $i++; }  ?>
              </td>
            </tr>            
        </table>
    <?php
    }
}

/**
 * Save the custom metabox data
 */
 
if ( ! function_exists( 'storevilla_save_page_settings' ) ) {
    function storevilla_save_page_settings( $post_id ) { 
        global $storevilla_page_layouts, $post; 
        if ( !isset( $_POST[ 'storevilla_settings_nonce' ] ) || !wp_verify_nonce( $_POST[ 'storevilla_settings_nonce' ], basename( __FILE__ ) ) )
            return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
            return;        
        if ('page' == $_POST['post_type']) {  
            if (!current_user_can( 'edit_page', $post_id ) )  
                return $post_id;  
        } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
                return $post_id;  
        }    
        foreach ($storevilla_page_layouts as $field) {  
            $old = get_post_meta( $post_id, 'storevilla_page_layouts', true); 
            $new = sanitize_text_field($_POST['storevilla_page_layouts']);
            if ($new && $new != $old) {  
                update_post_meta($post_id, 'storevilla_page_layouts', $new);  
            } elseif ('' == $new && $old) {  
                delete_post_meta($post_id,'storevilla_page_layouts', $old);  
            } 
         } 
    }
}
add_action('save_post', 'storevilla_save_page_settings');


/* Custom Customizer Class */

if(class_exists( 'WP_Customize_control')) :
    
    class Storevilla_Image_Radio_Control extends WP_Customize_Control {
        public $type = 'radioimage';
        public function render_content() {
            $name = '_customize-radio-' . $this->id;
            ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <div id="input_<?php echo $this->id; ?>" class="image">
                <?php foreach ( $this->choices as $value => $label ) : ?>                
                        <label for="<?php echo $this->id . $value; ?>">
                            <input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo $this->id . $value; ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
                            <img src="<?php echo esc_html( $label ); ?>"/>
                        </label>
                <?php endforeach; ?>
            </div>
            <?php 
        }
    }

endif;



/* WooCommerce Action and filter ADD and REMOVE Section */

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
function storevilla_woocommerce_template_loop_product_thumbnail(){ ?>
    <div class="item-img">          
        
        <?php global $post, $product; if ( $product->is_on_sale() ) : 
            echo apply_filters( 'woocommerce_sale_flash', '<div class="new-label new-top-right">' . __( 'Sale!', 'storevilla' ) . '</div>', $post, $product ); ?>
        <?php endif; ?>
        <?php
            global $product_label_custom;
            if ($product_label_custom != ''){
                echo '<div class="new-label new-top-left">'.$product_label_custom.'</div>';
            }
        ?>
        <a class="product-image" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
            <?php echo woocommerce_get_product_thumbnail(); ?>
        </a>           
    </div>
<?php 
}
add_action( 'woocommerce_before_shop_loop_item_title', 'storevilla_woocommerce_template_loop_product_thumbnail', 10 );


/* Product Block Title Area */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
function storevilla_woocommerce_template_loop_product_title(){
    global $product;
    if( is_home() || is_front_page() ) {    
        $term = wp_get_post_terms($product->id,'product_cat',array('fields'=>'ids'));
        if(!empty( $term[0] )) {
            $procut_cat = get_term_by( 'id', $term[0], 'product_cat' );
            $category_link = get_term_link( $term[0],'product_cat' ); 
        } 
    }   
 ?>
    <div class="block-item-title">
        <?php  if(!empty( $term[0] )) { ?>
            <span>
                <a href="<?php esc_url( $category_link ); ?>">
                    <?php  echo esc_attr( $procut_cat->name ); ?>
                </a>
            </span>
        <?php } ?>
        <h3><a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    </div>
<?php }
add_action( 'woocommerce_shop_loop_item_title', 'storevilla_woocommerce_template_loop_product_title', 10 );

/* Product Add to Cart and View Details */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
function storevilla_woocommerce_template_loop_add_to_cart(){
?>
    <div class="product-button-wrap clearfix">
        <?php woocommerce_template_loop_add_to_cart(); ?>
        
            <a class="villa-details" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
                <?php _e('View Details','storevilla'); ?>
            </a>
        
    </div>
<?php
}
add_action( 'woocommerce_after_shop_loop_item_title' ,'storevilla_woocommerce_template_loop_add_to_cart', 11 );


remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
function storevilla_woocommerce_template_loop_price(){
?>
    <div class="product-price-wrap">
        <?php woocommerce_template_loop_price(); ?>        
    </div>
<?php
}
add_action( 'woocommerce_after_shop_loop_item_title' ,'storevilla_woocommerce_template_loop_price', 12 );

function storevilla_woocommerce_template_loop_quick_info(){
?>
    <ul class="add-to-links">
        <?php 
            global $product;
            if( function_exists( 'YITH_WCQV' ) ){
                $quick_view = YITH_WCQV_Frontend();
                remove_action( 'woocommerce_after_shop_loop_item', array( $quick_view, 'yith_add_quick_view_button' ), 15 );
                $label = esc_html( get_option( 'yith-wcqv-button-label' ) );
                echo '<li><a href="#" class="link-quickview yith-wcqv-button" data-product_id="' . $product->id . '">' . $label . '</a></li>';
            }
        
          if( function_exists( 'YITH_WCWL' ) ){
            $url = add_query_arg( 'add_to_wishlist', $product->id );
            ?>
            <li>
                <a class="link-wishlist" href="<?php echo $url ?>">
                    <?php _e('Add To Wishlist','storevilla'); ?>
                </a>
            </li>
            <?php
          }
        ?>
    </ul>
<?php
}
add_action( 'woocommerce_after_shop_loop_item' ,'storevilla_woocommerce_template_loop_quick_info', 11 );



/**
 * Woo Commerce Number of row filter Function
**/

add_filter('loop_shop_columns', 'storevilla_loop_columns');
if (!function_exists('storevilla_loop_columns')) {
    function storevilla_loop_columns() {
        if(get_theme_mod('storevilla_woocommerce_product_row','3')){
            $xr = get_theme_mod('storevilla_woocommerce_product_row','3');
        } else {
            $xr = 3;
        }
        return $xr;
    }
}

add_action( 'body_class', 'storevilla_woo_body_class');
if (!function_exists('storevilla_woo_body_class')) {
    function storevilla_woo_body_class( $class ) {
           $class[] = 'columns-'.storevilla_loop_columns();
           return $class;
    }
}

/**
 * Woo Commerce Number of Columns filter Function
**/
$column = get_theme_mod('storevilla_woocommerce_display_product_number','12');
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.$column.';' ), 20 );


/**
 * Woo Commerce Add Content Primary Div Function
**/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
if (!function_exists('storevilla_woocommerce_output_content_wrapper')) {
    function storevilla_woocommerce_output_content_wrapper(){ ?>
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
    <?php   }
}
add_action( 'woocommerce_before_main_content', 'storevilla_woocommerce_output_content_wrapper', 10 );

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
if (!function_exists('storevilla_woocommerce_output_content_wrapper_end')) {
    function storevilla_woocommerce_output_content_wrapper_end(){ ?>
            </main><!-- #main -->
        </div><!-- #primary -->
    <?php   }
}
add_action( 'woocommerce_after_main_content', 'storevilla_woocommerce_output_content_wrapper_end', 10 );


/**
 * Remove WooCommerce Default Sidebar
**/
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
function storevilla_woocommerce_get_sidebar(){
    get_sidebar('woocommerce');
}
add_action( 'woocommerce_sidebar', 'storevilla_woocommerce_get_sidebar', 10);



/**
 * The Excerpt [...] remove function
 **/
function storevilla_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'storevilla_excerpt_more');

/**
 * Change the Breadcrumb Arrow Function
 **/
add_filter( 'woocommerce_breadcrumb_defaults', 'storevilla_change_breadcrumb_delimiter' );
function storevilla_change_breadcrumb_delimiter( $defaults ) {
    $defaults['delimiter'] = ' &gt; ';
    return $defaults;
}

/**
 * Woo Commerce Social Share
**/

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 55 );
function storevilla_woocommerce_template_single_sharing() { ?>
    <div class="storevilla-social">
        <?php
            if ( is_plugin_active( 'accesspress-social-share/accesspress-social-share.php' ) ) {
                echo do_shortcode("[apss-share share_text='Share this']");
            }
        ?>
    </div>
<?php }
add_action( 'woocommerce_single_product_summary', 'storevilla_woocommerce_template_single_sharing', 50 );

/**
 * Woo Commerce Related product
**/
//add_filter( 'woocommerce_output_related_products_args', 'storevilla_related_products_args' );
function storevilla_related_products_args( $args ) {
    $args['posts_per_page']     = 6;
    $args['columns']            = 3;
    return $args;
}


/**
 ** Retina images image generate function 
**/
function retina_support_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }
 
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'retina_support_attachment_meta', 10, 2 );

/**
 * Create retina-ready images
 * Referenced via retina_support_attachment_meta().
**/
function retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
 
            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );
 
            $info = $resized_file->get_size();
 
            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}


/**
 * Delete retina-ready images
 * This function is attached to the 'delete_attachment' filter hook.
**/
function delete_retina_support_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
    $path = pathinfo( $meta['file'] );
    foreach ( $meta as $key => $value ) {
        if ( 'sizes' === $key ) {
            foreach ( $value as $sizes => $size ) {
                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
                if ( file_exists( $retina_filename ) )
                    unlink( $retina_filename );
            }
        }
    }
}
add_filter( 'delete_attachment', 'delete_retina_support_images' );
