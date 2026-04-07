<?php
/**
 * Plugin Name: Icey for Google Translate
* Description: Integrates Google Translate into WordPress through a configurable modal dialog with a custom language selector.
 * Version: 1.0.1
 * Author: Icey
 * Author URI: https://icey.se
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: icey-for-google-translate
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'ICEY_GT_VERSION', '1.0.1' );

function icey_gt_get_available_languages() {
    return [
        'af' => 'Afrikaans', 'sq' => 'Shqip', 'am' => 'አማርኛ', 'ar' => 'العربية', 'hy' => 'Հայերեն',
        'az' => 'Azərbaycan dili', 'eu' => 'Euskara', 'be' => 'Беларуская', 'bn' => 'বাংলা', 'bs' => 'Bosanski',
        'bg' => 'Български', 'ca' => 'Català', 'ceb' => 'Cebuano', 'ny' => 'Chichewa', 'zh-CN' => '中文 (Simplified)',
        'zh-TW' => '中文 (Traditional)', 'co' => 'Corsu', 'hr' => 'Hrvatski', 'cs' => 'Čeština', 'da' => 'Dansk',
        'nl' => 'Nederlands', 'en' => 'English', 'eo' => 'Esperanto', 'et' => 'Eesti', 'tl' => 'Filipino',
        'fi' => 'Suomi', 'fr' => 'Français', 'fy' => 'Frysk', 'gl' => 'Galego', 'ka' => 'ქართული',
        'de' => 'Deutsch', 'el' => 'Ελληνικά', 'gu' => 'ગુજરાતી', 'ht' => 'Kreyòl ayisyen', 'ha' => 'Hausa',
        'haw' => 'Ōlelo Hawai\'i', 'iw' => 'עברית', 'hi' => 'हिन्दी', 'hmn' => 'Hmong', 'hu' => 'Magyar',
        'is' => 'Íslenska', 'ig' => 'Igbo', 'id' => 'Bahasa Indonesia', 'ga' => 'Gaeilge', 'it' => 'Italiano',
        'ja' => '日本語', 'jw' => 'Basa Jawa', 'kn' => 'ಕನ್ನಡ', 'kk' => 'Қазақ тілі', 'km' => 'ភាសាខ្មែរ',
        'ko' => '한국어', 'ku' => 'Kurdî', 'ky' => 'Кыргызча', 'lo' => 'ລາວ', 'la' => 'Latina',
        'lv' => 'Latviešu', 'lt' => 'Lietuvių', 'lb' => 'Lëtzebuergesch', 'mk' => 'Македонски', 'mg' => 'Malagasy',
        'ms' => 'Bahasa Melayu', 'ml' => 'മലയാളം', 'mt' => 'Malti', 'mi' => 'Te Reo Māori', 'mr' => 'मराठी',
        'mn' => 'Монгол', 'my' => 'ဗမာစာ', 'ne' => 'नेपाली', 'no' => 'Norsk', 'ps' => 'پښتو',
        'fa' => 'فارسی', 'pl' => 'Polski', 'pt' => 'Português', 'pa' => 'ਪੰਜਾਬੀ', 'ro' => 'Română',
        'ru' => 'Русский', 'sm' => 'Gagana fa\'a Sāmoa', 'gd' => 'Gàidhlig', 'sr' => 'Српски', 'st' => 'Sesotho',
        'sn' => 'ChiShona', 'sd' => 'سنڌي', 'si' => 'සිංහල', 'sk' => 'Slovenčina', 'sl' => 'Slovenščina',
        'so' => 'Soomaali', 'es' => 'Español', 'su' => 'Basa Sunda', 'sw' => 'Kiswahili', 'sv' => 'Svenska',
        'tg' => 'Тоҷикӣ', 'ta' => 'தமிழ்', 'te' => 'తెలుగు', 'th' => 'ไทย', 'tr' => 'Türkçe',
        'uk' => 'Українська', 'ur' => 'اردو', 'uz' => 'O\'zbekcha', 'vi' => 'Tiếng Việt', 'cy' => 'Cymraeg',
        'xh' => 'isiXhosa', 'yi' => 'ייִדיש', 'yo' => 'Yorùbá', 'zu' => 'isiZulu'
    ];
}

add_action( 'admin_init', 'icey_gt_register_settings' );
function icey_gt_register_settings() {
    register_setting( 'icey_gt_settings_group', 'icey_gt_heading', [ 'type' => 'string', 'default' => 'Choose language', 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_explanation', [ 'type' => 'string', 'default' => 'Translations are handled automatically by Google Translate.', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_btn_cancel', [ 'type' => 'string', 'default' => 'Cancel', 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_btn_translate', [ 'type' => 'string', 'default' => 'Translate', 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_default_lang', [ 'type' => 'string', 'default' => 'sv', 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_active_langs', [ 'type' => 'string', 'default' => 'en,zh-CN,de,fr', 'sanitize_callback' => 'sanitize_text_field' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_custom_css', [ 'type' => 'string', 'default' => '', 'sanitize_callback' => 'wp_strip_all_tags' ] );
}

add_action( 'admin_menu', 'icey_gt_add_admin_menu' );
function icey_gt_add_admin_menu() {
    global $icey_gt_page_hook;
    $icey_gt_page_hook = add_options_page( 'Icey Google Translate', 'Google Translate', 'manage_options', 'icey-for-google-translate', 'icey_gt_settings_page' );
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'icey_gt_settings_link' );
function icey_gt_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=icey-for-google-translate">' . esc_html__( 'Settings', 'icey-for-google-translate' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

add_action( 'admin_enqueue_scripts', 'icey_gt_admin_scripts' );
function icey_gt_admin_scripts( $hook ) {
    global $icey_gt_page_hook;
    if ( $hook !== $icey_gt_page_hook ) { return; }

    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'icey-gt-admin-script', plugin_dir_url( __FILE__ ) . 'js/admin.js', ['jquery', 'jquery-ui-sortable'], ICEY_GT_VERSION, true );
    wp_localize_script( 'icey-gt-admin-script', 'iceyGTAdminVars', [
        'langExistsMsg' => __( 'Language already exists in the list.', 'icey-for-google-translate' )
    ]);
}

function icey_gt_settings_page() {
    $all_langs = icey_gt_get_available_languages();
    $active_langs_str = get_option( 'icey_gt_active_langs', 'en,zh-CN,de,fr' );
    $active_langs = array_filter( explode( ',', $active_langs_str ) );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Icey Google Translate Settings', 'icey-for-google-translate' ); ?></h1>
        <p class="description" style="margin-bottom: 20px; font-size: 14px;">
            <?php esc_html_e( 'To open the translation modal from a link, button or menu item, add the CSS class', 'icey-for-google-translate' ); ?> <strong><code>icey_language_toggle</code></strong> <?php esc_html_e( 'to that item.', 'icey-for-google-translate' ); ?>
        </p>
        <form method="post" action="options.php">
            <?php settings_fields( 'icey_gt_settings_group' ); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Modal Heading', 'icey-for-google-translate' ); ?></th>
                    <td><input type="text" name="icey_gt_heading" value="<?php echo esc_attr( get_option('icey_gt_heading') ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Explanation Text', 'icey-for-google-translate' ); ?></th>
                    <td><textarea name="icey_gt_explanation" rows="4" class="large-text"><?php echo esc_textarea( get_option('icey_gt_explanation') ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Button: Cancel', 'icey-for-google-translate' ); ?></th>
                    <td><input type="text" name="icey_gt_btn_cancel" value="<?php echo esc_attr( get_option('icey_gt_btn_cancel') ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Button: Translate', 'icey-for-google-translate' ); ?></th>
                    <td><input type="text" name="icey_gt_btn_translate" value="<?php echo esc_attr( get_option('icey_gt_btn_translate') ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Site Default Language', 'icey-for-google-translate' ); ?></th>
                    <td>
                        <select name="icey_gt_default_lang">
                            <?php foreach ( $all_langs as $code => $name ) : ?>
                                <option value="<?php echo esc_attr($code); ?>" <?php selected( get_option('icey_gt_default_lang'), $code ); ?>><?php echo esc_html($name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Dropdown Languages', 'icey-for-google-translate' ); ?><br>
                        <small><?php esc_html_e( 'Drag and drop to sort.', 'icey-for-google-translate' ); ?></small>
                    </th>
                    <td>
                        <div style="margin-bottom: 10px;">
                            <select id="icey_gt_add_lang_select">
                                <option value=""><?php esc_html_e( '-- Select language to add --', 'icey-for-google-translate' ); ?></option>
                                <?php foreach ( $all_langs as $code => $name ) : ?>
                                    <option value="<?php echo esc_attr($code); ?>" data-name="<?php echo esc_attr($name); ?>"><?php echo esc_html($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="button" id="icey_gt_add_lang_btn"><?php esc_html_e( 'Add', 'icey-for-google-translate' ); ?></button>
                        </div>

                        <ul id="icey_gt_pills_container" style="display: flex; flex-wrap: wrap; gap: 8px; padding: 0; margin: 0; list-style: none;">
                            <?php foreach ( $active_langs as $code ) : 
                                if ( ! isset($all_langs[$code]) ) continue;
                            ?>
                                <li data-code="<?php echo esc_attr($code); ?>" style="background: #fff; border: 1px solid #ccc; padding: 5px 10px; border-radius: 20px; cursor: move; display: flex; align-items: center; gap: 8px;">
                                    <?php echo esc_html($all_langs[$code]); ?>
                                    <span class="remove-lang" style="color: red; cursor: pointer; font-weight: bold;">&times;</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <input type="hidden" name="icey_gt_active_langs" id="icey_gt_active_langs" value="<?php echo esc_attr($active_langs_str); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Custom CSS', 'icey-for-google-translate' ); ?></th>
                    <td>
                        <textarea name="icey_gt_custom_css" rows="6" class="large-text" style="font-family: monospace;"><?php echo esc_textarea( get_option('icey_gt_custom_css') ); ?></textarea>
                        <p class="description"><?php esc_html_e( 'Add custom CSS to override default styling.', 'icey-for-google-translate' ); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action( 'wp_enqueue_scripts', 'icey_gt_enqueue_scripts' );
function icey_gt_enqueue_scripts() {
    wp_enqueue_style( 'icey-gt-style', plugin_dir_url( __FILE__ ) . 'css/frontend.css', [], ICEY_GT_VERSION );
    
    $custom_css = get_option( 'icey_gt_custom_css', '' );
    if ( ! empty( $custom_css ) ) {
        wp_add_inline_style( 'icey-gt-style', $custom_css );
    }

    wp_enqueue_script( 'icey-gt-script', plugin_dir_url( __FILE__ ) . 'js/frontend.js', [], ICEY_GT_VERSION, true );

    $default_lang = get_option( 'icey_gt_default_lang', 'sv' );
    $googtrans = isset( $_COOKIE['googtrans'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['googtrans'] ) ) : '';

    if ( ! empty( $googtrans ) && $googtrans !== '/' . $default_lang . '/' . $default_lang ) {
        wp_enqueue_script( 'icey-gt-google-translate', 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit', [ 'icey-gt-script' ], ICEY_GT_VERSION, true );
    }

    $active_langs_str = get_option( 'icey_gt_active_langs', 'en,zh-CN,de,fr' );
    wp_localize_script( 'icey-gt-script', 'iceyGTVars', [
        'defaultLang' => get_option( 'icey_gt_default_lang', 'sv' ),
        'activeLangs' => $active_langs_str
    ]);
}

add_action( 'wp_footer', 'icey_gt_render_modal_html' );
function icey_gt_render_modal_html() {
    $heading = get_option( 'icey_gt_heading', 'Choose language' );
    $explanation = get_option( 'icey_gt_explanation', 'Translations are handled automatically by Google Translate.' );
    $btn_cancel = get_option( 'icey_gt_btn_cancel', 'Cancel' );
    $btn_translate = get_option( 'icey_gt_btn_translate', 'Translate' );
    
    $all_langs = icey_gt_get_available_languages();
    $active_langs_str = get_option( 'icey_gt_active_langs', 'en,zh-CN,de,fr' );
    $active_langs = array_filter( explode( ',', $active_langs_str ) );
    ?>
    <div id="icey_language_modal_backdrop" class="icey_language_modal_backdrop" style="display: none;"></div>
    <div id="icey_language_modal" class="icey_language_modal" role="dialog" aria-modal="true" aria-labelledby="icey_language_modal_title" style="display: none;">
        <div class="icey_language_modal_inner">
            <h2 id="icey_language_modal_title" class="icey_modal_title"><?php echo esc_html( $heading ); ?></h2>
            <div class="icey_modal_content">
                <p><?php echo nl2br( esc_html( $explanation ) ); ?></p>
                <div class="icey_select_wrapper">
                    <select id="icey_language_select" class="icey_language_select">
                        <?php foreach ( $active_langs as $code ) : 
                            if ( isset( $all_langs[$code] ) ) : ?>
                                <option value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html( $all_langs[$code] ); ?></option>
                            <?php endif; 
                        endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="icey_modal_buttons">
                <button id="icey_stay_swedish" class="icey_btn icey_btn_secondary"><?php echo esc_html( $btn_cancel ); ?></button>
                <button id="icey_proceed_translate" class="icey_btn icey_btn_primary"><?php echo esc_html( $btn_translate ); ?></button>
            </div>
        </div>
    </div>
    <div id="google_translate_element" style="display:none;"></div>
    <?php
}