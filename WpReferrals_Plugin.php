<?php


include_once('WpReferrals_LifeCycle.php');

class WpReferrals_Plugin extends WpReferrals_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
        //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
        'Commission' => array(__('Affiliate Commission', 'wp-referrals')),
        'Commission type' => array(__('Dynamic (percent-based) or fixed', 'wp-referrals'), 'Dynamic', 'Fixed')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'WP Referrals';
    }

    protected function getMainPluginFileName() {
        return 'wp-referrals.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
                global $wpdb;
                $tableName = $this->prefixTableName('user_referrals');
                $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
                    `id` INTEGER AUTO_INCREMENT,
					`user_id` INTEGER NOT NULL,
					`referral_id` INTEGER NOT NULL,
					`date_visited` DATE,
					`date_registered` DATE");
                $tableName = $this->prefixTableName('referral_earnings');
                $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
                    `id` INTEGER AUTO_INCREMENT,
					`user_id` INTEGER NOT NULL,
					`earnings` FLOAT");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
                  global $wpdb;
                  $tableName = $this->prefixTableName('user_referrals');
                  $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
                  $tableName = $this->prefixTableName('referral_earnings');
                  $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
		
		
         add_action('woocommerce_before_my_account', array('WpReferrals_Plugin', 'displayRefUrl'));
             
		 add_action('init', array('WpReferrals_Plugin', 'setRefCookie'));
		 
		 add_action( 'user_register', array('WpReferrals_Plugin', 'saveRefID'), 10, 1 );
		 
		 add_action( 'woocommerce_payment_complete', array('WpReferrals_Plugin', 'saveRefCommission'), 10, 1 );
		 }


}
