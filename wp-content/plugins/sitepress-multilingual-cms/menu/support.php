
<div class="wrap">    
    <div id="icon-wpml" class="icon32" style="clear:both" ><br /></div>    
    <h2><?php _e('Support', 'sitepress') ?></h2>
    
    <p style="margin-top: 20px;">
        <?php _e('Technical support for clients is available via <a target="_blank" href="http://forum.wpml.org">WPML forum</a>.','sitepress'); ?>
    </p>

    <?php
    
    // Installer plugin active?
    $installer_on = defined('WPRC_VERSION') && WPRC_VERSION;

    $wp_plugins = get_plugins();
	$wpml_plugins_list = array(
		'WPML Multilingual CMS'       => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'sitepress-multilingual-cms' ),
		'WPML CMS Nav'                => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'wpml-cms-nav' ),
		'WPML String Translation'     => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'wpml-string-translation' ),
		'WPML Sticky Links'           => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'wpml-sticky-links' ),
		'WPML Translation Management' => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'wpml-translation-management' ),
		'WPML Translation Analytics'  => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'wpml-translation-analytics' ),
		'WPML XLIFF'                  => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'wpml-xliff' ),
		'WPML Media'                  => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'wpml-media' ),
		'WooCommerce Multilingual'    => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'woocommerce-multilingual' ),
		'JigoShop Multilingual'       => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'jigoshop-multilingual' ),
		'Gravity Forms Multilingual'  => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'gravityforms-multilingual' ),
		'CRED Frontend Translation'   => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'cred-frontend-translation' ),
		'Installer'                   => array( 'installed' => false,'active'=>false,'file'=>false, 'plugin'=>false, 'slug'=>'installer' ),
	);

	$wpml_plugins = false;

	foreach($wpml_plugins_list as $wpml_plugin_name => $v){
        $found = false;
        foreach($wp_plugins as $file => $plugin){
			$plugin_name = $plugin[ 'Name' ];
			if( $plugin_name == $wpml_plugin_name){
				$wpml_plugins_list[ $plugin_name ]['installed'] = true;
				$wpml_plugins_list[ $plugin_name ]['plugin'] = $plugin;
				$wpml_plugins_list[ $plugin_name ]['file'] = $file;
                $found = true;
            }
        }
    }

    unset($wp_plugins);
    
    echo '
        <table class="widefat" style="width: auto;">
            <thead>
                <tr>    
                    <th>' . __('Plugin Name', 'sitepress') . '</th>
                    <th style="text-align:right">' . __('Status', 'sitepress') . '</th>
                    <th>' . __('Active', 'sitepress') . '</th>
                    <th>' . __('Version', 'sitepress') . '</th>
                </tr>
            </thead>    
            <tbody>
        ';
    if($installer_on){
        if(!defined('ICL_WPML_ORG_REPO_ID')){ //backward compatibility
            $wpml_org_repo_id = $wpdb->get_var("
                SELECT id FROM {$wpdb->prefix}".WPRC_DB_TABLE_REPOSITORIES." WHERE repository_endpoint_url='http://api.wpml.org/'");
                define('ICL_WPML_ORG_REPO_ID', $wpml_org_repo_id);
        }
    }

	foreach ( $wpml_plugins_list as $name => $plugin_data ) {

		$plugin_name = $name;
		$file        = $plugin_data['file'];
		$dir = dirname($file);

		echo '<tr>';
		echo '<td><i class="icon18 '. $plugin_data['slug'] . '"></i>' . $plugin_name . '</td>';
		echo '<td align="right">';
		if ( empty( $plugin_data['plugin'] ) ) {
			if ( !$installer_on ) {
				echo __( 'Not installed' );
			} else {
				echo '<a href="' . admin_url( 'plugin-install.php?repos[]=' . ICL_WPML_ORG_REPO_ID . '&amp;tab=search&amp;s=' ) . urlencode( $plugin_name ) . '">' . __( 'Download', 'sitepress' ) . '</a>';
			}
		} else {
			if ( !$installer_on ) {
				echo __( 'Installed' );
			} else {
				echo '<a href="' . admin_url( 'plugin-install.php?repos[]=' . ICL_WPML_ORG_REPO_ID . '&amp;tab=search&amp;s=' ) . urlencode( $plugin_name ) . '">' . __( 'Installed', 'sitepress' ) . '</a>';
			}
		}
		echo '</td>';
		echo '<td align="center">';
		echo isset( $file ) && is_plugin_active( $file ) ? __( 'Yes', 'sitepress' ) : __( 'No', 'sitepress' );
		echo '</td>';
		echo '<td align="right">';
		echo isset( $plugin_data['plugin']['Version'] ) ? $plugin_data['plugin']['Version'] : __( 'n/a', 'sitepress' );
		echo '</td>';
		echo '</tr>';

	}

    echo '
            </tbody>
        </table>
    ';
        
    if(!$installer_on){
        echo '
            <br />
            <div class="icl_cyan_box">
                <p>' . __('The recommended way to install WPML on new sites and upgrade WPML on this site is by using our Installer plugin.', 'sitepress') . '</p>
                <br />
                <p>
                    <a class="button-primary" href="http://wp-compatibility.com/installer-plugin/">' . __('Download Installer', 'sitepress') . '</a>&nbsp;
                    <a href="http://wpml.org/faq/install-wpml/#2">' . __('Instructions', 'sitepress') . '</a>
                </p>
            </div>
        ';
    }else{
        echo '
            <br />
            <div class="icl_cyan_box">
                <p>' . __("To check for new versions, please visit your site's plugins section.", 'sitepress') . '</p>
            </div>
        ';
    }
    ?>
    
    <p style="margin-top: 20px;">
    <?php printf(__('For advanced access or to completely uninstall WPML and remove all language information, use the <a href="%s">troubleshooting</a> page.', 'sitepress'), admin_url('admin.php?page=' . ICL_PLUGIN_FOLDER . '/menu/troubleshooting.php')); ?> 
    </p>
    
    
</div>
