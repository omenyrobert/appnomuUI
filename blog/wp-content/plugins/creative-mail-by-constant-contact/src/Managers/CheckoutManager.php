<?php


namespace CreativeMail\Managers;

use CreativeMail\CreativeMail;
use CreativeMail\Helpers\EnvironmentHelper;
use CreativeMail\Helpers\OptionsHelper;
use CreativeMail\Modules\Contacts\Models\OptActionBy;
use stdClass;
use WC_Coupon;
use WC_Order;

/**
 * Class CheckoutManager
 *
 * @package CreativeMail\Managers
 */
class CheckoutManager
{
    /**
     * Current checkout UUID.
     *
     * @var   string
     * @since 1.3.0
     */
    protected $checkout_uuid = '';
    protected $return_to_shop = false;

    const UPDATE_CHECKOUT_DATA = 'update_checkout_data';
    const META_CHECKOUT_UUID = 'ce4wp_checkout_uuid';
    const META_CHECKOUT_RECOVERED = 'ce4wp_checkout_recovered';
    const CHECKOUT_UUID = 'checkout_uuid';
    const NONCE = 'nonce';
    const EMAIL = 'email';
    const CHECKED = 'checked';
    const DOMAIN = 'ce4wp';
    const BILLING_EMAIL = 'billing_email';
    const BILLING_EMAIL_NOTICE = 'billing_email_notice';
    const BILLING_EMAIL_NO_CONSENT = 'billing_email_no_consent';
    const CHECKOUT_UUID_PARAM = 'checkout_uuid = %s';
    const COUPONS = 'coupons';
    const PRODUCT_ID = 'product_id';
    const VARIATION_ID = 'variation_id';
    const QUANTITY = 'quantity';
    const VARIATION = 'variation';
    const USER_EMAIL = 'user_email';
    const PRODUCTS = 'products';
    const CUSTOMER = 'customer';
    const DATETIME_ZERO = "0000-00-00 00:00:00";

    /**
     * Add hooks
     *
     * @since 1.3.0
     */
    public function add_hooks()
    {
        // check if woocommerce is active
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_action('woocommerce_before_checkout_form', array($this, 'enqueue_scripts'));
            // add checkout notice field
            add_filter('woocommerce_form_field_ce4wp_notice', array($this, 'add_email_usage_notice_field'), 10, 4);
            add_filter('woocommerce_checkout_fields', array($this, 'ce4wp_filter_checkout_fields'));

            add_action('woocommerce_after_template_part', array($this, 'save_or_clear_checkout_data'), 10, 1);
            add_action('woocommerce_add_to_cart', array($this, self::UPDATE_CHECKOUT_DATA));
            add_action('woocommerce_cart_item_removed', array($this, self::UPDATE_CHECKOUT_DATA), 30, 0);
            add_action('woocommerce_cart_item_restored', array($this, self::UPDATE_CHECKOUT_DATA), 30, 0);
            add_action('woocommerce_cart_item_set_quantity', array($this, self::UPDATE_CHECKOUT_DATA), 20, 0);

            add_action('wp_ajax_ce4wp_abandoned_checkouts_capture_guest_checkout', array($this, 'maybe_capture_guest_checkout'));
            add_action('wp_ajax_nopriv_ce4wp_abandoned_checkouts_capture_guest_checkout', array($this, 'maybe_capture_guest_checkout'));

            add_action('wp_ajax_ce4wp_abandoned_checkouts_no_consent_checkout', array($this, 'no_consent_checkout'));
            add_action('wp_ajax_nopriv_ce4wp_abandoned_checkouts_no_consent_checkout', array($this, 'no_consent_checkout'));

            add_action('woocommerce_checkout_create_order', array($this, 'clear_purchased_data'), 10, 1);
            add_action('woocommerce_checkout_order_processed', array($this, 'order_processed'), 10, 1);
            add_action('woocommerce_order_status_completed', array($this, 'order_completed'), 10, 1);

            // Sanitize checkout UUID.
            $this->checkout_uuid = filter_input(INPUT_GET, 'ce4wp-recover', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $this->return_to_shop = filter_input (INPUT_GET, 'ce4wp-return-to-shop', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            if (empty($this->checkout_uuid) && empty($this->return_to_shop)) {
                return;
            }

            if (!empty($this->checkout_uuid)) {
                add_action('wp_loaded', array($this, 'recover_checkout'));
            }

            if (!empty($this->return_to_shop)) {
                add_action('wp_loaded', array($this, 'return_to_shop'));
            }
        }
    }

    /**
     * Add custom field under billing_email
     *
     * @since 1.3.0
     */
    public function ce4wp_filter_checkout_fields($fields) {
        $fields['billing'][self::BILLING_EMAIL_NOTICE] = array(
            'type' => 'ce4wp_notice',
            'required'  => false,
            'class'      => array( 'form-row-wide' ),
            'clear'     => true,
            'priority' => $fields['billing']['billing_email']['priority'] + 0.5
        );
        return $fields;
    }

    /**
     * Add logic for ce4wp_notice field type
     *
     * @since 1.3.0
     */
    public function add_email_usage_notice_field( $field, $key, $args, $value )
    {
        $field_html = '<label style="font-weight:400;">' . __( 'Your email and cart are saved so we can send you email reminders about this order.', self::DOMAIN ) .' <a href="#" id="ce4wp_no_consent">'. __( 'No thanks', self::DOMAIN ).'</a></label>';

        $container_class = esc_attr( implode( ' ', $args['class'] ) );
        $container_id = esc_attr( $args['id'] ) . '_field';

        $after = ! empty( $args['clear'] ) ? '<div class="clear"></div>' : '';

        $field_container = '<p class="form-row %1$s" id="%2$s">%3$s</p>';

        return sprintf( $field_container, $container_class, $container_id, $field_html ) . $after;
    }

    /**
     * Order has been completed
     *
     * @param int $order_id    The order id.
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function order_completed($order_id)
    {
        $this->update_checkout($order_id, '/v1.0/checkout/order_completed');
        $this->cleanup_old_checkouts($order_id);
    }

    /**
     * Order has been created and is processed
     *
     * @param int $order_id    Newly created order id.
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function order_processed( $order_id) {
        $this->update_checkout($order_id, '/v1.0/checkout/order_created');
    }

    /**
     * Cleanup previous checkouts in case old is still marked as abandoned
     *
     * @param int $order_id    Woocommerce order id.
     *
     * @since 1.3.3
     *
     * @return void
     */
    private function cleanup_old_checkouts( $order_id )
    {
        $order = wc_get_order($order_id);
        if ( empty( $order ) ) {
            return;
        }
        try
        {
            $data = $this->get_checkout_uuid_by_email($order->get_billing_email());
            foreach ($data as $checkout_data)
            {
                $endpoint = EnvironmentHelper::get_app_gateway_url('wordpress') . '/v1.0/checkout/'. $checkout_data->checkout_uuid;
                $this->ce4wp_remote_delete($endpoint);
                CreativeMail::get_instance()->get_database_manager()->remove_checkout_data($checkout_data->checkout_uuid);
            }
        }
        catch (\Exception $e)
        {
            RaygunManager::get_instance()->exception_handler($e);
        }
    }

    /**
     * Update of checkout data in the external service
     *
     * @param int      $order_id The order id.
     * @param string   $endpoint Endpoint to call
     *
     * @since 1.3.0
     */
    private function update_checkout($order_id, $endpoint) {
        $order = wc_get_order($order_id);
        if ( empty( $order ) ) {
            return;
        }

        // check if order had checkout uuid
        $uuid = $order->get_meta( self::META_CHECKOUT_UUID, true);
        // check if order is created with checkout meta
        if (empty($uuid)) {
            return;
        }

        // try find recovery date from order meta data
        $recovery_date = $order->get_meta( self::META_CHECKOUT_RECOVERED, true);
        // Remote post to ce4wp marking checkout as completed/created
        $requestItem = new stdClass();
        $requestItem->uuid = $uuid;
        $requestItem->order_id = $order->get_id();
        $requestItem->order_total = $order->get_total();
        $requestItem->order_currency = $order->get_currency();
        $requestItem->recovery_date = (empty($recovery_date) || $recovery_date === self::DATETIME_ZERO) ? null : $recovery_date;

        $endpoint = EnvironmentHelper::get_app_gateway_url('wordpress') . $endpoint;
        // call remote endpoint to update
        $this->ce4wp_remote_post($requestItem, $endpoint);
    }

    /**
     * Enqueue abandoned cart javascript files
     *
     * @since 1.3.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script( 'ce4wp-consent-checkout', CE4WP_PLUGIN_URL . 'assets/js/consent_checkout.js', [ 'wp-util' ], CE4WP_PLUGIN_VERSION, false );

        if ( is_user_logged_in() ) {
            return;
        }

        wp_enqueue_script( 'ce4wp-guest-checkout', CE4WP_PLUGIN_URL . 'assets/js/guest_checkout.js', [ 'wp-util' ], CE4WP_PLUGIN_VERSION, false );
    }

    /**
     * AJAX handler for attempting to capture guest checkouts.
     *
     * @since 1.3.0
     */
    public function maybe_capture_guest_checkout() {
        $data = filter_input_array( INPUT_POST, [
            self::NONCE => FILTER_SANITIZE_STRING,
            self::EMAIL => FILTER_SANITIZE_EMAIL
        ] );

        if ( empty( $data[self::NONCE] ) || ! wp_verify_nonce( $data[self::NONCE], 'woocommerce-process_checkout' ) ) {
            wp_send_json_error( esc_html__( 'Invalid nonce.', self::DOMAIN ) );
        }

        $email = filter_var( $data[self::EMAIL], FILTER_VALIDATE_EMAIL );

        if ( ! $email ) {
            wp_send_json_error( esc_html__( 'Invalid email.', self::DOMAIN ) );
        }

        WC()->session->set( self::BILLING_EMAIL, $email );
        $this->save_checkout_data( $email, true);

        wp_send_json_success();
    }

    /**
     * AJAX handler for opt out on abandoned cart.
     *
     * @since 1.3.0
     */
    public function no_consent_checkout()
    {
        $data = filter_input_array(INPUT_POST, [
            self::NONCE => FILTER_SANITIZE_STRING
        ]);

        if (empty($data[self::NONCE]) || !wp_verify_nonce($data[self::NONCE], 'woocommerce-process_checkout')) {
            wp_send_json_error(esc_html__('Invalid nonce.', self::DOMAIN));
        }
        // save no consent on session
        WC()->session->set( self::BILLING_EMAIL_NO_CONSENT, true);

        $checkout_id = WC()->session->get( self::CHECKOUT_UUID );
        if (empty($checkout_id)) {
            wp_send_json_success();
        }

        $endpoint = EnvironmentHelper::get_app_gateway_url('wordpress') . '/v1.0/checkout/'. $checkout_id;
        $this->ce4wp_remote_delete($endpoint);
        CreativeMail::get_instance()->get_database_manager()->change_checkout_consent($checkout_id, false);

        wp_send_json_success();
    }

    /**
     * Either call an update of checkout data which will be saved or remove checkout data based on what template we arrive at.
     *
     * @param string $template_name Current template file name.
     *
     * @since 1.3.0
     */
    public function save_or_clear_checkout_data( $template_name) {

        // If checkout page displayed, save checkout data.
        if ( 'checkout/form-checkout.php' === $template_name ) {
            $this->save_checkout_data();
        }
    }

    /**
     * Helper function to update current checkout session data in db.
     *
     * Used to strip unneeded params from callbacks.
     *
     * @since 1.3.0
     */
    public function update_checkout_data() {
        $this->save_checkout_data();
    }

    /**
     * Helper function to retrieve checkout contents based on checkout UUID.
     *
     * @param string $uuid Checkout UUID.
     *
     * @since 1.3.0
     *
     * @return array                 Checkout contents.
     */
    private function get_checkout_contents( $uuid ) {
        $checkout = CreativeMail::get_instance()->get_database_manager()->get_checkout_data( 'checkout_contents', self::CHECKOUT_UUID_PARAM, [ $uuid ] );

        if ( empty( $checkout ) ) {
            return [];
        }

        return maybe_unserialize( array_shift( $checkout )->checkout_contents );
    }

    /**
     * Helper function to retrieve checkout recovery date based on checkout UUID.
     *
     * @param string $uuid Checkout UUID.
     *
     * @since 1.3.0
     *
     * @return string|null Checkout recovery date if exists, else null.
     */
    private function get_checkout_recovery_date( $uuid ) {
        $checkout = CreativeMail::get_instance()->get_database_manager()->get_checkout_data( 'checkout_recovered', self::CHECKOUT_UUID_PARAM, [ $uuid ] );

        return ( empty( $checkout ) ? null : array_shift( $checkout )->checkout_recovered );
    }

    /**
     * Helper function to retrieve checkout UUID for current user.
     *
     * @since 1.3.0
     *
     * @return string Checkout UUID if exists, else empty string.
     */
    private function get_checkout_uuid_by_user() {
        $checkout = CreativeMail::get_instance()->get_database_manager()->get_checkout_data( self::CHECKOUT_UUID, 'user_id = %d', [ get_current_user_id() ] );

        return ( empty( $checkout ) ? '' : array_shift( $checkout )->checkout_uuid );
    }

    /**
     * Helper function to retrieve checkout UUID for email address.
     *
     * @since 1.3.3
     *
     * @return array List of checkout UUIDs if exists, else empty string.
     */
    private function get_checkout_uuid_by_email($email_address) {
        return CreativeMail::get_instance()->get_database_manager()->get_checkout_data( self::CHECKOUT_UUID, 'user_email = %s', [ $email_address ] );
    }

    /**
     * Save current checkout data to db.
     *
     * @param string  $billing_email Manually set customer billing email if provided.
     * @param boolean $is_checkout   Manually mark current page as checkout if necessary (e.g., coming from ajax callback).
     * @param boolean|null $consent_checkout   Manually mark consent value.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function save_checkout_data( string $billing_email = '', bool $is_checkout = false) {
        // Get current user email.
        $session_customer      = WC()->session->get( self::CUSTOMER );
        $session_billing_email = is_array( $session_customer ) && key_exists( self::EMAIL, $session_customer ) ? $session_customer[self::EMAIL] : '';

        if (empty($billing_email)) {
            $billing_email = $session_billing_email;
            if (empty($billing_email)) {
                $billing_email = WC()->checkout->get_value( self::BILLING_EMAIL );
                if (empty($billing_email)) {
                    $billing_email = WC()->session->get( self::BILLING_EMAIL );
                }
            }
        }
        $is_checkout           = $is_checkout ?: is_checkout();
        $uuid         = WC()->session->get( self::CHECKOUT_UUID );

        if ( empty( $billing_email ) ) {
            return;
        }

        $has_no_consent = WC()->session->get( self::BILLING_EMAIL_NO_CONSENT);
        if($has_no_consent === true)
        {
            return;
        }

        // Check for existing checkout session.
        if ( ! $uuid ) {

            // Only create session if cart is not empty.
            // This is to avoid re-creating checkout UUID during checkout process.
            if ( $is_checkout && empty( WC()->cart->get_cart() ) ) {
                return;
            }

            // Retrieve existing checkout UUID for registered users only.
            if ( is_user_logged_in() ) {
                $existing_uuid = $this->get_checkout_uuid_by_user();
            }

            // Only create session if currently on checkout page or if current user has an existing session saved.
            if ( ! $is_checkout && empty( $existing_uuid ) ) {
                return;
            }

            $uuid = isset( $existing_uuid ) && ! empty( $existing_uuid ) ? $existing_uuid : wp_generate_uuid4();

            WC()->session->set( 'checkout_uuid', $uuid );
        }

        $current_time = current_time( 'mysql', 1 );
        $user_id = get_current_user_id();

        $cart_products = WC()->cart->get_cart();
        $cart_coupons = WC()->cart->get_applied_coupons();

        $checkout_content = [
            self::PRODUCTS        => array_values($cart_products),
            self::COUPONS         => $cart_coupons,
        ];

        CreativeMail::get_instance()->get_database_manager()->upsert_checkout($uuid, $user_id, $billing_email, $checkout_content, $current_time);

        // Remote post to ce4wp create or update cart if email is provided
        $requestItem = new stdClass();
        $requestItem->data = wp_json_encode($this->get_cart_data_for_endpoint($cart_products, $cart_coupons));
        $requestItem->uuid = $uuid;
        $requestItem->user_id = $user_id;
        $requestItem->billing_email = $billing_email;
        $requestItem->timestamp = strtotime($current_time);
        $endpoint = EnvironmentHelper::get_app_gateway_url('wordpress') . '/v1.0/checkout/upsert';

        $consent = CreativeMail::get_instance()->get_database_manager()->has_checkout_consent($uuid);
        if ($consent) {
            $this->ce4wp_remote_post($requestItem, $endpoint);
        }
    }

    /**
     * Get cart object with data for each product and coupon
     *
     * @since 1.3.0
     */
    private function get_cart_data_for_endpoint($cart_products, $cart_coupons) {
        $data = new stdClass();
        $data->products = array();
        $data->coupons = array();
        $data->currency_symbol = get_woocommerce_currency_symbol();
        $data->currency = get_woocommerce_currency();

        $data->user = new stdClass();

        try
        {
            // Get user first and last name of available
            $current_user = wp_get_current_user();
            if ($current_user->exists() ) {
                $data->user->id = $current_user->ID;
                $data->user->username = $current_user->user_login;
                $data->user->display_name = $current_user->display_name;
                $data->user->first_name = $current_user->user_firstname;
                $data->user->last_name = $current_user->user_lastname;
                $data->user->email = $current_user->user_email;
            }

            $dp = 2; // decimal point

            foreach ($cart_products as $value)
            {
                $product = array_key_exists('data', $value) ? $value['data'] : wc_get_product($value[self::PRODUCT_ID]);
                $product_id = $product->get_id();
                $product_data = array(
                    'images' => array()
                );
                $attachment_ids = $product->get_gallery_image_ids();
                foreach ($attachment_ids as $attachment_id) {
                    $product_data['images'][] = wp_get_attachment_url($attachment_id);
                }

                $product_data["on_sale"] = $product->is_on_sale();
                $product_data["sale_price"] = $product->get_sale_price();
                $product_data["regular_price"] = $product->get_regular_price();
                $src = wc_placeholder_img_src();
                if ($image_id = $product->get_image_id()) {
                    list($src) = wp_get_attachment_image_src($image_id, 'full');
                }

                $line_subtotal = empty($value['line_subtotal']) ? 0: $value['line_subtotal'];
                $line_subtotal_tax =empty($value['line_subtotal_tax']) ? 0: $value['line_subtotal_tax'];
                $line_total = empty($value['line_total']) ? 0: $value['line_total'];
                $line_tax = empty($value['line_tax']) ? 0: $value['line_tax'];

                $data->products[] = array(
                    'name' => $product->get_name(),
                    'product_id' => $product_id,
                    'product_image' => $src,
                    'product_data' => $product_data,
                    'sku' => is_object($product) ? $product->get_sku() : null,
                    'product_url' => get_the_permalink($product_id),
                    'variation_id' => $value[self::VARIATION_ID],
                    'subtotal' => wc_format_decimal($line_subtotal, $dp),
                    'subtotal_tax' => wc_format_decimal($line_subtotal_tax, $dp),
                    'total' => wc_format_decimal($line_total, $dp),
                    'total_tax' => wc_format_decimal($line_tax, $dp),
                    'price' => wc_format_decimal($line_subtotal, $dp),
                    'quantity' => $value[self::QUANTITY]
                );
            }

            foreach ($cart_coupons as $coupon_code)
            {
                $coupon_id = wc_get_coupon_id_by_code($coupon_code);
                if ($coupon_id)
                {
                    $coupon = new WC_Coupon($coupon_id);

                    $data->coupons[] = array(
                        'code' => $coupon->get_code(),
                        'amount' => $coupon->get_amount(),
                        'discount_type' => $coupon->get_discount_type(),
                        'description' => $coupon->get_description(),
                        'free_shipping' => $coupon->get_free_shipping()
                    );
                }
            }
        }
        catch (\Exception $e)
        {
            RaygunManager::get_instance()->exception_handler($e);
        }

        return $data;
    }

    /**
     * Remove current checkout session data from db upon successful order submission.
     *
     * @param WC_Order $order    Newly created order object.
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function clear_purchased_data( $order) {
        $checkout_id = WC()->session->get( self::CHECKOUT_UUID );
        if (empty($checkout_id)) {
            return;
        }

        $order->update_meta_data( self::META_CHECKOUT_UUID, $checkout_id );

        // get the recovery date if recovered
        $recovery_date = $this->get_checkout_recovery_date($checkout_id);
        if (!empty($recovery_date) && $recovery_date !== self::DATETIME_ZERO)
        {
            $order->update_meta_data(self::META_CHECKOUT_RECOVERED, $recovery_date);
        }
        CreativeMail::get_instance()->get_database_manager()->remove_checkout_data($checkout_id);
        WC()->session->__unset( self::CHECKOUT_UUID );
    }

    /**
     * Recovery saved checkout from UUID.
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function recover_checkout() {

        // Set checkout session UUID.
        WC()->session->set( self::CHECKOUT_UUID, $this->checkout_uuid );

        // Clear current checkout contents.
        WC()->cart->empty_cart();

        // Get saved checkout contents.
        $checkout_contents = $this->get_checkout_contents( $this->checkout_uuid );

        if ( empty($checkout_contents) ) {
            return;
        }

        // Mark checkout as recovered
        CreativeMail::get_instance()->get_database_manager()->mark_checkout_recovered($this->checkout_uuid);

        // Recover saved products.
        $this->recover_products( $checkout_contents[self::PRODUCTS] );

        // Apply coupons.
        foreach ( $checkout_contents[self::COUPONS] as $coupon ) {
            WC()->cart->apply_coupon( $coupon );
        }

        // Maybe recover checkout email.
        $this->maybe_recover_checkout_email();

        // Update totals.
        WC()->cart->calculate_totals();

        // Redirect to checkout page.
        wp_safe_redirect( wc_get_page_permalink( 'cart' ) );

        exit();
    }

    public function return_to_shop() {
        wp_safe_redirect(wc_get_page_permalink('shop'));

        exit();
    }


    /**
     * Recover checkout email address if guest user and no email is set.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function maybe_recover_checkout_email() : void {
        $checkout_email = CreativeMail::get_instance()->get_database_manager()->get_checkout_data( self::USER_EMAIL, self::CHECKOUT_UUID_PARAM, [ $this->checkout_uuid ] );
        $checkout_email = empty( $checkout_email ) ? '' : array_shift( $checkout_email )->user_email;

        if ( is_user_logged_in() || ! empty( WC()->session->get( self::BILLING_EMAIL ) ) || empty( $checkout_email ) ) {
            return;
        }

        WC()->session->set( self::BILLING_EMAIL, $checkout_email );
        WC()->customer->set_billing_email( $checkout_email );
    }

    /**
     * Recover products from saved checkout data.
     *
     * @param array $products Array of product data.
     *
     * @since 1.3.0
     */
    protected function recover_products( $products ) {
        if (empty($products)) {
            return;
        }
        // Programmatically add each product to cart.
        $products_added = [];
        foreach ( $products as $product ) {
            $added = WC()->cart->add_to_cart(
                $product[self::PRODUCT_ID],
                $product[self::QUANTITY],
                empty( $product[self::VARIATION_ID] ) ? 0 : $product[self::VARIATION_ID],
                empty( $product[self::VARIATION] ) ? array() : $product[self::VARIATION]
            );
            if ( false !== $added ) {
                $products_added[ ( empty( $product[self::VARIATION_ID] ) ? $product[self::PRODUCT_ID] : $product[self::VARIATION_ID] ) ] = $product[self::QUANTITY];
            }
        }

        // Add product notices.
        if ( 0 < count( $products_added ) ) {
            wc_add_to_cart_message( $products_added );
        }
        if ( count( $products ) > count( $products_added ) ) {
            wc_add_notice(
                sprintf(
                /* translators: %d item count */
                    _n(
                        '%d item from your previous order is currently unavailable and could not be added to your cart.',
                        '%d items from your previous order are currently unavailable and could not be added to your cart.',
                        ( count( $products ) - count( $products_added ) ),
                        self::DOMAIN
                    ),
                    ( count( $products ) - count( $products_added ) )
                ),
                'error'
            );
        }
    }

    private function ce4wp_remote_post($requestItem, $endpoint) {
        try
        {
            // check if abandoned cart email is managed by creative mail
            $enabled = CreativeMail::get_instance()->get_email_manager()->is_email_managed('cart_abandoned_ce4wp');
            if($enabled) {
                wp_remote_post(
                    $endpoint, array(
                        'method' => 'POST',
                        'timeout' => 10,
                        'headers' => array(
                            'x-account-id' => OptionsHelper::get_connected_account_id(),
                            'x-api-key' => OptionsHelper::get_instance_api_key(),
                            'content-type' => 'application/json'
                        ),
                        'body' => wp_json_encode($requestItem)
                    )
                );
            }
        } catch (\Exception $e) {
            RaygunManager::get_instance()->exception_handler($e);
        }
    }

    private function ce4wp_remote_delete($endpoint) {
        try {
            wp_remote_request($endpoint,
                array(
                    'method' => 'DELETE',
                    'headers' => array(
                        'x-account-id' => OptionsHelper::get_connected_account_id(),
                        'x-api-key' => OptionsHelper::get_instance_api_key()
                    )
                )
            );
        } catch (\Exception $e) {
            // silent
        }
    }

    private function get_opt_action_by($products_detail) {
        return OptActionBy::Visitor;
    }

    private function get_opt_in_checkbox_value($products_detail) {
        if (!empty($products_detail["ce4wp_checkout_consent"])) {
            return $products_detail["ce4wp_checkout_consent"][0]; //this value appears to be in array;
        }

        return null;
    }

    private function get_opt_in($products_detail) {
        $checkbox_value = $this->get_opt_in_checkbox_value($products_detail);
        if ($checkbox_value == true)
           return true;
        return null;
    }

    private function get_opt_out($products_detail) {
        $checkbox_value = $this->get_opt_in_checkbox_value($products_detail);
        if ($checkbox_value == false)
            return true;
        return null;
    }

    public function add_order_completed_wc_hooks() {
        add_action('woocommerce_order_status_completed', array($this, 'order_completed_trigger_wc_hook'), 10, 1);
    }

    public function order_completed_trigger_wc_hook($order_id) {
        $order = wc_get_order($order_id);
        if ( empty( $order ) ) {
            return;
        }

        $products_detail = get_post_meta($order_id);

        $endpoint = '/v1.0/wc/order_completed';
        $decimal_point = 2;

        // General Info
        $requestItem = new stdClass();
        $requestItem->order_id = $order->get_id();
        $requestItem->order_number = $order->get_order_number();
        $requestItem->date_created = $order->get_date_created() ? $order->get_date_created()->getTimestamp() : 0;
        $requestItem->date_modified = $order->get_date_modified() ? $order->get_date_modified()->getTimestamp() : 0;
        $requestItem->date_completed = $order->get_date_completed() ? $order->get_date_completed()->getTimestamp() : 0;
        $requestItem->status = $order->get_status();
        $requestItem->order_url = $order->get_checkout_order_received_url();
        $requestItem->note = $order->get_customer_note();
        $requestItem->customer_ip = $order->get_customer_ip_address();
        $requestItem->customer_user_agent = $order->get_customer_user_agent();
        $requestItem->customer_id = $order->get_user_id();
        // Order Billing
        $requestItem->order->billing->email = $order->get_billing_email();

        $requestItem->order->billing->opt_action_by = $this->get_opt_action_by($products_detail);
        $requestItem->order->billing->opt_in = $this->get_opt_in($products_detail);
        $requestItem->order->billing->opt_out = $this->get_opt_out($products_detail);

        $requestItem->order->billing->first_name = $order->get_billing_first_name();
        $requestItem->order->billing->last_name = $order->get_billing_last_name();
        $requestItem->order->billing->is_first_time_buyer = count(wc_get_orders(array('email' => $order->get_billing_email()))) <= 1;
        $requestItem->order->billing->company = $order->get_billing_company();
        $requestItem->order->billing->address_1 = $order->get_billing_address_1();
        $requestItem->order->billing->address_2 = $order->get_billing_address_2();
        $requestItem->order->billing->city = $order->get_billing_city();
        $requestItem->order->billing->state = $order->get_billing_state();
        $requestItem->order->billing->postcode = $order->get_billing_postcode();
        $requestItem->order->billing->country = $order->get_billing_country();
        $requestItem->order->billing->email = $order->get_billing_email();
        $requestItem->order->billing->phone = $order->get_billing_phone();
        $requestItem->order->billing->shipping = array(
            'first_name' => $order->get_shipping_first_name(),
            'last_name' => $order->get_shipping_last_name(),
            'company' => $order->get_shipping_company(),
            'address_1' => $order->get_shipping_address_1(),
            'address_2' => $order->get_shipping_address_2(),
            'city' => $order->get_shipping_city(),
            'state' => $order->get_shipping_state(),
            'postcode' => $order->get_shipping_postcode(),
            'country' => $order->get_shipping_country(),
            'shipping_methods' => $order->get_shipping_method()
        );
        $requestItem->order->billing->payment_details = array(
            'method_id' => $order->get_payment_method(),
            'method_title' => $order->get_payment_method_title(),
            'paid' => !is_null($order->get_date_paid()),
        );
        // Order Currency and Total Info
        $requestItem->total = wc_format_decimal($order->get_total(), $decimal_point);
        $requestItem->subtotal = wc_format_decimal($order->get_subtotal(), $decimal_point);
        $requestItem->total_tax = wc_format_decimal($order->get_total_tax(), $decimal_point);
        $requestItem->shipping_total = wc_format_decimal($order->get_shipping_total(), $decimal_point);
        $requestItem->cart_tax = wc_format_decimal($order->get_cart_tax(), $decimal_point);
        $requestItem->shipping_tax = wc_format_decimal($order->get_shipping_tax(), $decimal_point);
        $requestItem->discount_total = wc_format_decimal($order->get_total_discount(), $decimal_point);
        $requestItem->order->currency_symbol = get_woocommerce_currency_symbol();
        $requestItem->order->currency = $order->get_currency();
        // Order Products Info
        $requestItem->order->total_line_items_quantity = $order->get_item_count();
        // Line Items / Products array for the expected endpoint
        foreach ($order->get_items() as $itemsKey => $item) {
            $product = $item->get_product();

            if (empty($product)) {
                continue;
            }

            $item_meta = $item->get_formatted_meta_data();

            foreach ($item_meta as $key => $values) {
                $item_meta[$key]->label = $values->display_key;
                unset($item_meta[$key]->display_key);
                unset($item_meta[$key]->display_value);
            }

            try {
                $product_data = array(
                    'images' => array(),
                    'downloads' => array()
                );
                $attachment_ids = $product->get_gallery_image_ids();
                foreach ($attachment_ids as $attachment_id) {
                    $product_data['images'][] = wp_get_attachment_url($attachment_id);
                }

                $product_data["on_sale"] = $product->is_on_sale();
                $product_data["sale_price"] = $product->get_sale_price();
                $product_data["regular_price"] = $product->get_regular_price();

                if ($product->is_downloadable()) {
                    $item_downloads = $item->get_item_downloads();
                    foreach ($item_downloads as $item_download)
                    {
                        $product_data["downloads"][] = array(
                            'line_item_id' => $item->get_id(),
                            'product_id' => $item->get_product_id(),
                            'download_url' => $item_download["download_url"],
                            'download_file' => $item_download["file"],
                            'download_name' => $item_download["name"],
                            'download_id' => $item_download["id"],
                            'downloads_remaining' => $item_download["downloads_remaining"],
                            'download_access_expires' => wc_format_datetime($item_download["access_expires"], 'U'),
                            'download_limit' => $product->get_download_limit(),
                            'download_expiry' => $product->get_download_expiry(),
                        );
                    }
                }
            } catch (\Exception $ex) {
                RaygunManager::get_instance()->exception_handler($ex);
            }

            $src = wc_placeholder_img_src();
            if ($image_id = $product->get_image_id() ) {
                list( $src ) = wp_get_attachment_image_src($image_id, 'full');
            }

            $requestItem->order->line_items[] = array(
                'product_id' => $item->get_product_id(),
                'item_meta' => $item->get_formatted_meta_data(),
                'subtotal' => wc_format_decimal($order->get_line_subtotal($item, false, false), $decimal_point),
                'subtotal_tax' => wc_format_decimal($item->get_subtotal_tax(), $decimal_point),
                'total' => wc_format_decimal($order->get_line_total($item, false, false), $decimal_point),
                'total_tax' => wc_format_decimal($item->get_total_tax(), $decimal_point),
                'price' => wc_format_decimal($order->get_item_total($item, false, false), $decimal_point),
                'quantity' => $item->get_quantity(),
                'tax_class' => $item->get_tax_class(),
                'name' => $item->get_name(),
                'product_image' => $src,
                'product_data' => $product_data,
                'sku' => is_object($product) ? $product->get_sku() : null,
                'meta' => array_values($item_meta),
                'product_url' => get_the_permalink($item->get_product_id()),
                'variation_id' => $item->get_variation_id()
            );
        }

        $endpoint = EnvironmentHelper::get_app_gateway_url('wordpress') . $endpoint;
        try
        {
            wp_remote_post(
                $endpoint, array(
                    'method' => 'POST',
                    'timeout' => 10,
                    'headers' => array(
                        'x-account-id' => OptionsHelper::get_connected_account_id(),
                        'x-api-key' => OptionsHelper::get_instance_api_key(),
                        'content-type' => 'application/json'
                    ),
                    'body' => wp_json_encode($requestItem)
                )
            );
        } catch (\Exception $e) {
            RaygunManager::get_instance()->exception_handler($e);
        }
    }
}
