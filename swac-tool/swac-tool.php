<?php
/**
 * Plugin Name: SWAC Tool
 * Plugin URI:  https://wordpress.org/plugins/swac-tool/
 * Description: WhatsApp Chat Floating Tool. Enhance user engagement with the SWAC Tool, a WordPress plugin that effortlessly adds a floating WhatsApp chat button to your website.
 * Version:     2.1
 * Author:      DIZETECH
 * Author URI:  https://dizetech.in/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Tested up to: 6.8.1
 * Requires PHP: 7.0
 */



// Output the WhatsApp floating button
function swac_tool_output() {
    $mobile_number = get_option( 'swac_tool_mobile_number', '51955081075' );
    $chat_text = get_option( 'swac_tool_chat_text', 'Hello' );
    $position = get_option( 'swac_tool_button_position', 'right' );

    // Image path (make sure the file exists in the plugin folder)
    $whatsapp_logo_url = plugin_dir_url( __FILE__ ) . 'whatsapp.png';

    ob_start();
    ?>
    <style>
        .float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            <?php echo $position === 'left' ? 'left' : 'right'; ?>: 40px;
            background-color: #25d366;
            border-radius: 50px;
            text-align: center;
/*             box-shadow: 2px 2px 3px #999; */
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .float img {
            width: 30px;
            height: 30px;
        }
    </style>

    <a href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $mobile_number ); ?>&text=<?php echo urlencode( $chat_text ); ?>" class="float" target="_blank">
        <img src="<?php echo esc_url( $whatsapp_logo_url ); ?>" alt="WhatsApp Chat">
    </a>
    <?php
    echo ob_get_clean();
}
add_action( 'wp_footer', 'swac_tool_output' );

// Add admin menu page
function swac_tool_menu_page() {
    add_menu_page(
        'SWAC Tool',
        'SWAC Tool',
        'manage_options',
        'swac-tool',
        'swac_tool_settings_page',
        'dashicons-whatsapp'
    );
}
add_action( 'admin_menu', 'swac_tool_menu_page' );

// Register plugin settings
function swac_tool_register_settings() {
    register_setting( 'swac_tool_settings', 'swac_tool_mobile_number' );
    register_setting( 'swac_tool_settings', 'swac_tool_chat_text' );
    register_setting( 'swac_tool_settings', 'swac_tool_button_position' );
}
add_action( 'admin_init', 'swac_tool_register_settings' );

// Settings page content
function swac_tool_settings_page() {
    ?>
    <div class="wrap">
        <h1>SWAC Tool Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'swac_tool_settings' ); ?>
            <?php do_settings_sections( 'swac_tool_settings' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Mobile Number</th>
                    <td>
                        <input type="text" name="swac_tool_mobile_number" placeholder="+9196XXXXXXXX" value="<?php echo esc_attr( get_option( 'swac_tool_mobile_number', '' ) ); ?>" />
                        <p class="description">Note: Enter your WhatsApp phone number with the country code.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Chat Text</th>
                    <td>
                        <input type="text" name="swac_tool_chat_text" placeholder="Enter default chat text" value="<?php echo esc_attr( get_option( 'swac_tool_chat_text', 'Hello' ) ); ?>" />
                        <p class="description">Note: Default text for the initial chat message.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Button Position</th>
                    <td>
                        <select name="swac_tool_button_position">
                            <option value="right" <?php selected( get_option( 'swac_tool_button_position', 'right' ), 'right' ); ?>>Right</option>
                            <option value="left" <?php selected( get_option( 'swac_tool_button_position', 'right' ), 'left' ); ?>>Left</option>
                        </select>
                        <p class="description">Note: Choose the position for the WhatsApp floating button.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
