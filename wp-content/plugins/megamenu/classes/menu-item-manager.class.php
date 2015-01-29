<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists('Mega_Menu_Menu_Item_Manager') ) :

/**
 *
 * @since 1.4
 */
class Mega_Menu_Menu_Item_Manager {

	var $menu_item_id = 0;

    var $menu_item_depth = 0;

	var $menu_item_meta = array();


	/**
	 * Constructor
	 *
	 * @since 1.4
	 */
	public function __construct() {

		add_action( 'wp_ajax_mm_get_lightbox_html', array( $this, 'ajax_get_lightbox_html' ) );
		add_action( 'wp_ajax_mm_save_menu_item_settings', array( $this, 'ajax_save_menu_item_settings') );

	}


    /**
     * Set up the class
     *
     * @since 1.4
     */
    private function init() {

        if ( isset( $_POST['menu_item_id'] ) ) {

            $this->menu_item_id = absint( $_POST['menu_item_id'] );
            
            $saved_settings = array_filter( (array) get_post_meta( $this->menu_item_id, '_megamenu', true ) );

            $defaults = array(
                'type' => 'flyout',
                'align' => 'bottom-left',
                'icon' => 'disabled'
            );

            $this->menu_item_meta = array_merge( $defaults, $saved_settings );

        }

        if ( isset( $_POST['menu_item_depth'] ) ) {

            $this->menu_item_depth = absint( $_POST['menu_item_depth'] );

        }

    }


    /**
     * Save custom menu item fields.
     *
     * @since 1.4
     * @param int $menu_id
     * @param int $menu_item_id
     * @param array $menu_item_args
     */
    public static function ajax_save_menu_item_settings() {

    	check_ajax_referer( 'megamenu_edit' );

        $submitted_settings = $_POST['settings'];

        $menu_item_id = absint( $_POST['menu_item_id'] );

        if ( $menu_item_id > 0 && is_array( $submitted_settings ) ) {

        	$existing_settings = get_post_meta( $menu_item_id, '_megamenu', true);

        	if ( is_array( $existing_settings ) ) {

        		$submitted_settings = array_merge( $existing_settings, $submitted_settings );

        	} 
        	
        	update_post_meta( $_POST['menu_item_id'], '_megamenu', $submitted_settings );
        	
        }

        wp_die("saved");

    }


	/**
	 * Return the HTML to display in the Lightbox
     *
     * @since 1.4
     * @return string
	 */
	public function ajax_get_lightbox_html() {

		check_ajax_referer( 'megamenu_edit' );

        $this->init();

		$tabs = array(
			'mega_menu' => array(
				'title' => __('Mega Menu', 'megamenu'),
				'content' => $this->get_mega_menu_content()
			),
			'general_settings' => array(
				'title' => __('General Settings', 'megamenu'),
				'content' => $this->get_general_settings_content()
			),
			'menu_icon' => array(
				'title' => __('Menu Icon', 'megamenu'),
				'content' => $this->get_icon_content()
			)
		);

		wp_die( json_encode( $tabs ) );
	}


	/**
	 * Return the HTML to display in the 'Mega Menu' tab
     *
     * @since 1.4
     * @return string
	 */
	private function get_mega_menu_content() {

        if ( $this->menu_item_depth > 0 ) {
            return '<em>' . __( "Mega Menus can only be created on top level menu items.", "megamenu" ) . '</em>';
        }

		$widget_manager = new Mega_Menu_Widget_Manager();

		$all_widgets = $widget_manager->get_available_widgets();

		$return = "<label class='mm_enable'><input class='toggle_menu' type='checkbox' " . checked($this->menu_item_meta['type'], 'megamenu', false)  . "/>" . __("Enable Mega Menu", "megamenu") . "</label>";

		$return .= "<select id='widget_selector'>";

		$return .= "<option value='disabled'>" . __("Select a Widget to add to the panel", "megamenu") . "</option>";

		foreach ( $all_widgets as $widget ) {
			$return .= "<option value='" . $widget['value'] . "'>" . $widget['text'] . "</option>";
		}

		$return .= "</select>";

        $return .= "<div id='widgets'>";
        
        $panel_widgets = $widget_manager->get_widgets_for_menu_id( $this->menu_item_id );

        if ( ! count( $panel_widgets ) ) {

            $return .= "<div class='message no_widgets'>" . __("No widgets found", "megamenu") . "<br /><br /><i>" . __("Use the Widget Selector (top right) to add a Widget to this panel.") . "</i></div>";
        
        } else {

            foreach ( $panel_widgets as $widget ) {
                $return .= '<div class="widget" data-columns="' . esc_attr( $widget['mega_columns'] ) . '" data-widget-id="' . esc_attr( $widget['widget_id'] ) . '">';
                $return .= '    <div class="widget-top">';
                $return .= '        <div class="widget-title-action">';
                $return .= '            <a class="widget-option widget-contract"></a>';
                $return .= '            <a class="widget-option widget-expand"></a>';
                $return .= '            <a class="widget-option widget-edit"></a>';
                $return .= '        </div>';
                $return .= '        <div class="widget-title">';
                $return .= '            <h4>' . esc_html( $widget['title'] ) . '</h4>';
                $return .= '            <span class="spinner" style="display: none;"></span>';
                $return .= '        </div>';
                $return .= '    </div>';
                $return .= '    <div class="widget-inner"></div>';
                $return .= '</div>';
            }

        }

        $return .= "</div>";

		return $return;
	}


	/**
	 * Return the HTML to display in the 'General Settings' tab
     *
     * @since 1.4
     * @return string
	 */
	private function get_general_settings_content() {

		$return  = '<form>';
        $return .= '<table>';
        $return .= '    <tr>';
        $return .= '        <td class="mega-name">' . __("Sub Menu Position", "megamenu") . '</td>';
        $return .= '        <td class="mega-value">';

        if ( $this->menu_item_depth == 0 ) {
            $return .= '            <select name="settings[align]">';
            $return .= '                <option value="bottom-left" ' . selected($this->menu_item_meta['align'], 'bottom-left', false) . '>' . __("Left", "megamenu") . '</option>';
            $return .= '                <option value="bottom-right" ' . selected($this->menu_item_meta['align'], 'bottom-right', false) . '>' . __("Right", "megamenu") . '</option>';
            $return .= '            </select>';     
        } else {
            $return .= '<em>' . __("Option only available for top level menu items", "megamenu") . '</em>';
        }

        $return .= '        </td>';
        $return .= '    </tr>';
    	$return .= '</table>';

        $return .= get_submit_button();
        $return .= '</form>';


    	return $return;
        
	}


	/**
	 * Return the HTML to display in the 'menu icon' tab
     *
     * @since 1.4
     * @return string
	 */
	private function get_icon_content() {

		$return = "<form class='icon_selector'>";

        $return .= "<div class='disabled'><input id='disabled' class='radio' type='radio' rel='disabled' name='settings[icon]' value='disabled' " . checked( $this->menu_item_meta['icon'], 'disabled', false ) . " />";
        $return .= "<label for='disabled'></label></div>";

        foreach ( $this->all_icons() as $code => $class ) {

            $name = str_replace( 'dashicons-', '', $class );
            $name = ucwords( str_replace( '-', ' ', $name ) );
            $bits = explode( "-", $code );
            $code = "&#x" . $bits[1] . "";

            $return .= "<div><input class='radio' id='{$class}' type='radio' rel='{$code}' name='settings[icon]' value='{$class}' " . checked( $this->menu_item_meta['icon'], $class, false ) . " />";
        	$return .= "<label rel='{$code}' for='{$class}'></label></div>";
        
        }
    

        $return .= "</form>";

        return $return;

	}


    /**
     * List of all available DashIcon classes.
     *
     * @since 1.0
     * @return array - Sorted list of icon classes
     */
    private function all_icons() {

        $icons = array(
            'dash-f333' => 'dashicons-menu',
            'dash-f319' => 'dashicons-admin-site',
            'dash-f226' => 'dashicons-dashboard',
            'dash-f109' => 'dashicons-admin-post',
            'dash-f104' => 'dashicons-admin-media',
            'dash-f103' => 'dashicons-admin-links',
            'dash-f105' => 'dashicons-admin-page',
            'dash-f101' => 'dashicons-admin-comments',
            'dash-f100' => 'dashicons-admin-appearance',
            'dash-f106' => 'dashicons-admin-plugins',
            'dash-f110' => 'dashicons-admin-users',
            'dash-f107' => 'dashicons-admin-tools',
            'dash-f108' => 'dashicons-admin-settings',
            'dash-f112' => 'dashicons-admin-network',
            'dash-f102' => 'dashicons-admin-home',
            'dash-f111' => 'dashicons-admin-generic',
            'dash-f148' => 'dashicons-admin-collapse',
            'dash-f119' => 'dashicons-welcome-write-blog',
            'dash-f133' => 'dashicons-welcome-add-page',
            'dash-f115' => 'dashicons-welcome-view-site',
            'dash-f116' => 'dashicons-welcome-widgets-menus',
            'dash-f117' => 'dashicons-welcome-comments',
            'dash-f118' => 'dashicons-welcome-learn-more',
            'dash-f123' => 'dashicons-format-aside',
            'dash-f128' => 'dashicons-format-image',
            'dash-f161' => 'dashicons-format-gallery',
            'dash-f126' => 'dashicons-format-video',
            'dash-f130' => 'dashicons-format-status',
            'dash-f122' => 'dashicons-format-quote',
            'dash-f125' => 'dashicons-format-chat',
            'dash-f127' => 'dashicons-format-audio',
            'dash-f306' => 'dashicons-camera',
            'dash-f232' => 'dashicons-images-alt',
            'dash-f233' => 'dashicons-images-alt2',
            'dash-f234' => 'dashicons-video-alt',
            'dash-f235' => 'dashicons-video-alt2',
            'dash-f236' => 'dashicons-video-alt3',
            'dash-f501' => 'dashicons-media-archive',
            'dash-f500' => 'dashicons-media-audio',
            'dash-f499' => 'dashicons-media-code',
            'dash-f498' => 'dashicons-media-default',
            'dash-f497' => 'dashicons-media-document',
            'dash-f496' => 'dashicons-media-interactive',
            'dash-f495' => 'dashicons-media-spreadsheet',
            'dash-f491' => 'dashicons-media-text',
            'dash-f490' => 'dashicons-media-video',
            'dash-f492' => 'dashicons-playlist-audio',
            'dash-f493' => 'dashicons-playlist-video',
            'dash-f165' => 'dashicons-image-crop',
            'dash-f166' => 'dashicons-image-rotate-left',
            'dash-f167' => 'dashicons-image-rotate-right',
            'dash-f168' => 'dashicons-image-flip-vertical',
            'dash-f169' => 'dashicons-image-flip-horizontal',
            'dash-f171' => 'dashicons-undo',
            'dash-f172' => 'dashicons-redo',
            'dash-f200' => 'dashicons-editor-bold',
            'dash-f201' => 'dashicons-editor-italic',
            'dash-f203' => 'dashicons-editor-ul',
            'dash-f204' => 'dashicons-editor-ol',
            'dash-f205' => 'dashicons-editor-quote',
            'dash-f206' => 'dashicons-editor-alignleft',
            'dash-f207' => 'dashicons-editor-aligncenter',
            'dash-f208' => 'dashicons-editor-alignright',
            'dash-f209' => 'dashicons-editor-insertmore',
            'dash-f210' => 'dashicons-editor-spellcheck',
            'dash-f211' => 'dashicons-editor-expand',
            'dash-f506' => 'dashicons-editor-contract',
            'dash-f212' => 'dashicons-editor-kitchensink',
            'dash-f213' => 'dashicons-editor-underline',
            'dash-f214' => 'dashicons-editor-justify',
            'dash-f215' => 'dashicons-editor-textcolor',
            'dash-f216' => 'dashicons-editor-paste-word',
            'dash-f217' => 'dashicons-editor-paste-text',
            'dash-f218' => 'dashicons-editor-removeformatting',
            'dash-f219' => 'dashicons-editor-video',
            'dash-f220' => 'dashicons-editor-customchar',
            'dash-f221' => 'dashicons-editor-outdent',
            'dash-f222' => 'dashicons-editor-indent',
            'dash-f223' => 'dashicons-editor-help',
            'dash-f224' => 'dashicons-editor-strikethrough',
            'dash-f225' => 'dashicons-editor-unlink',
            'dash-f320' => 'dashicons-editor-rtl',
            'dash-f464' => 'dashicons-editor-break',
            'dash-f475' => 'dashicons-editor-code',
            'dash-f476' => 'dashicons-editor-paragraph',
            'dash-f135' => 'dashicons-align-left',
            'dash-f136' => 'dashicons-align-right',
            'dash-f134' => 'dashicons-align-center',
            'dash-f138' => 'dashicons-align-none',
            'dash-f160' => 'dashicons-lock',
            'dash-f145' => 'dashicons-calendar',
            'dash-f177' => 'dashicons-visibility',
            'dash-f173' => 'dashicons-post-status',
            'dash-f464' => 'dashicons-edit',
            'dash-f182' => 'dashicons-trash',
            'dash-f504' => 'dashicons-external',
            'dash-f142' => 'dashicons-arrow-up',
            'dash-f140' => 'dashicons-arrow-down',
            'dash-f139' => 'dashicons-arrow-right',
            'dash-f141' => 'dashicons-arrow-left',
            'dash-f342' => 'dashicons-arrow-up-alt',
            'dash-f346' => 'dashicons-arrow-down-alt',
            'dash-f344' => 'dashicons-arrow-right-alt',
            'dash-f340' => 'dashicons-arrow-left-alt',
            'dash-f343' => 'dashicons-arrow-up-alt2',
            'dash-f347' => 'dashicons-arrow-down-alt2',
            'dash-f345' => 'dashicons-arrow-right-alt2',
            'dash-f341' => 'dashicons-arrow-left-alt2',
            'dash-f156' => 'dashicons-sort',
            'dash-f229' => 'dashicons-leftright',
            'dash-f503' => 'dashicons-randomize',
            'dash-f163' => 'dashicons-list-view',
            'dash-f164' => 'dashicons-exerpt-view',
            'dash-f237' => 'dashicons-share',
            'dash-f240' => 'dashicons-share-alt',
            'dash-f242' => 'dashicons-share-alt2',
            'dash-f301' => 'dashicons-twitter',
            'dash-f303' => 'dashicons-rss',
            'dash-f465' => 'dashicons-email',
            'dash-f466' => 'dashicons-email-alt',
            'dash-f304' => 'dashicons-facebook',
            'dash-f305' => 'dashicons-facebook-alt',
            'dash-f462' => 'dashicons-googleplus',
            'dash-f325' => 'dashicons-networking',
            'dash-f308' => 'dashicons-hammer',
            'dash-f309' => 'dashicons-art',
            'dash-f310' => 'dashicons-migrate',
            'dash-f311' => 'dashicons-performance',
            'dash-f483' => 'dashicons-universal-access',
            'dash-f507' => 'dashicons-universal-access-alt',
            'dash-f486' => 'dashicons-tickets',
            'dash-f484' => 'dashicons-nametag',
            'dash-f481' => 'dashicons-clipboard',
            'dash-f487' => 'dashicons-heart',
            'dash-f488' => 'dashicons-megaphone',
            'dash-f489' => 'dashicons-schedule',
            'dash-f120' => 'dashicons-wordpress',
            'dash-f324' => 'dashicons-wordpress-alt',
            'dash-f157' => 'dashicons-pressthis',
            'dash-f463' => 'dashicons-update',
            'dash-f180' => 'dashicons-screenoptions',
            'dash-f348' => 'dashicons-info',
            'dash-f174' => 'dashicons-cart',
            'dash-f175' => 'dashicons-feedback',
            'dash-f176' => 'dashicons-cloud',
            'dash-f326' => 'dashicons-translation',
            'dash-f323' => 'dashicons-tag',
            'dash-f318' => 'dashicons-category',
            'dash-f478' => 'dashicons-archive',
            'dash-f479' => 'dashicons-tagcloud',
            'dash-f480' => 'dashicons-text',
            'dash-f147' => 'dashicons-yes',
            'dash-f158' => 'dashicons-no',
            'dash-f335' => 'dashicons-no-alt',
            'dash-f132' => 'dashicons-plus',
            'dash-f502' => 'dashicons-plus-alt',
            'dash-f460' => 'dashicons-minus',
            'dash-f153' => 'dashicons-dismiss',
            'dash-f159' => 'dashicons-marker',
            'dash-f155' => 'dashicons-star-filled',
            'dash-f459' => 'dashicons-star-half',
            'dash-f154' => 'dashicons-star-empty',
            'dash-f227' => 'dashicons-flag',
            'dash-f230' => 'dashicons-location',
            'dash-f231' => 'dashicons-location-alt',
            'dash-f178' => 'dashicons-vault',
            'dash-f332' => 'dashicons-shield',
            'dash-f334' => 'dashicons-shield-alt',
            'dash-f468' => 'dashicons-sos',
            'dash-f179' => 'dashicons-search',
            'dash-f181' => 'dashicons-slides',
            'dash-f183' => 'dashicons-analytics',
            'dash-f184' => 'dashicons-chart-pie',
            'dash-f185' => 'dashicons-chart-bar',
            'dash-f238' => 'dashicons-chart-line',
            'dash-f239' => 'dashicons-chart-area',
            'dash-f307' => 'dashicons-groups',
            'dash-f338' => 'dashicons-businessman',
            'dash-f336' => 'dashicons-id',
            'dash-f337' => 'dashicons-id-alt',
            'dash-f312' => 'dashicons-products',
            'dash-f313' => 'dashicons-awards',
            'dash-f314' => 'dashicons-forms',
            'dash-f473' => 'dashicons-testimonial',
            'dash-f322' => 'dashicons-portfolio',
            'dash-f330' => 'dashicons-book',
            'dash-f331' => 'dashicons-book-alt',
            'dash-f316' => 'dashicons-download',
            'dash-f317' => 'dashicons-upload',
            'dash-f321' => 'dashicons-backup',
            'dash-f469' => 'dashicons-clock',
            'dash-f339' => 'dashicons-lightbulb',
            'dash-f482' => 'dashicons-microphone',
            'dash-f472' => 'dashicons-desktop',
            'dash-f471' => 'dashicons-tablet',
            'dash-f470' => 'dashicons-smartphone',
            'dash-f328' => 'dashicons-smiley'
        );

        $icons = apply_filters( "megamenu_icons", $icons );

        ksort( $icons );

        return $icons;
    }
}

endif;