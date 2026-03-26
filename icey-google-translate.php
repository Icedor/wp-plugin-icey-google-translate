<?php
/**
 * Plugin Name: Icey Google Translate
 * Plugin URI: https://github.com/Icedor/wp-plugin-icey-google-translate
 * Description: A clean, customizable Google Translate modal for WordPress.
 * Version: 1.0.0
 * Author: Icey
 * Author URI: https://icey.se
 * Text Domain: icey-google-translate
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// --- 0. Ladda textdomän för översättningar ---
add_action( 'plugins_loaded', 'icey_gt_load_textdomain' );
function icey_gt_load_textdomain() {
    load_plugin_textdomain( 'icey-google-translate', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// --- 1. Definiera tillgängliga språk ---
function icey_gt_get_available_languages() {
    return [
        'en' => 'English', 'da' => 'Dansk', 'de' => 'Deutsch', 
        'fi' => 'Suomi', 'fr' => 'Français', 'it' => 'Italiano', 
        'no' => 'Norsk', 'sv' => 'Svenska', 'es' => 'Español', 
        'pt' => 'Português', 'nl' => 'Nederlands', 'pl' => 'Polski'
    ];
}

// --- 2. Registrera inställningar ---
add_action( 'admin_init', 'icey_gt_register_settings' );
function icey_gt_register_settings() {
    register_setting( 'icey_gt_settings_group', 'icey_gt_heading', [ 'default' => 'Choose language' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_explanation', [ 'default' => 'Translations are handled automatically by Google Translate.' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_btn_cancel', [ 'default' => 'Cancel' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_btn_translate', [ 'default' => 'Translate' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_default_lang', [ 'default' => 'sv' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_active_langs', [ 'default' => 'en,da,de,fi,fr,it,no,sv' ] );
    register_setting( 'icey_gt_settings_group', 'icey_gt_custom_css', [ 'default' => '' ] );
}

// --- 3. Skapa menysida ---
add_action( 'admin_menu', 'icey_gt_add_admin_menu' );
function icey_gt_add_admin_menu() {
    $page = add_options_page( 'Icey Google Translate', 'Google Translate', 'manage_options', 'icey-google-translate', 'icey_gt_settings_page' );
    add_action( "admin_print_scripts-{$page}", 'icey_gt_admin_scripts' );
}

function icey_gt_admin_scripts() {
    wp_enqueue_script( 'jquery-ui-sortable' );
}

// --- 4. Bygg Inställningssidan (HTML/JS/CSS för Admin) ---
function icey_gt_settings_page() {
    $all_langs = icey_gt_get_available_languages();
    $active_langs_str = get_option( 'icey_gt_active_langs', 'en,da,de,fi,fr,it,no,sv' );
    $active_langs = array_filter( explode( ',', $active_langs_str ) );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Icey Google Translate Settings', 'icey-google-translate' ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'icey_gt_settings_group' ); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Modal Heading', 'icey-google-translate' ); ?></th>
                    <td><input type="text" name="icey_gt_heading" value="<?php echo esc_attr( get_option('icey_gt_heading') ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Explanation Text', 'icey-google-translate' ); ?></th>
                    <td><textarea name="icey_gt_explanation" rows="4" class="large-text"><?php echo esc_textarea( get_option('icey_gt_explanation') ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Button: Cancel', 'icey-google-translate' ); ?></th>
                    <td><input type="text" name="icey_gt_btn_cancel" value="<?php echo esc_attr( get_option('icey_gt_btn_cancel') ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Button: Translate', 'icey-google-translate' ); ?></th>
                    <td><input type="text" name="icey_gt_btn_translate" value="<?php echo esc_attr( get_option('icey_gt_btn_translate') ); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Site Default Language', 'icey-google-translate' ); ?></th>
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
                        <?php esc_html_e( 'Dropdown Languages', 'icey-google-translate' ); ?><br>
                        <small><?php esc_html_e( 'Drag and drop to sort.', 'icey-google-translate' ); ?></small>
                    </th>
                    <td>
                        <div style="margin-bottom: 10px;">
                            <select id="icey_gt_add_lang_select">
                                <option value=""><?php esc_html_e( '-- Select language to add --', 'icey-google-translate' ); ?></option>
                                <?php foreach ( $all_langs as $code => $name ) : ?>
                                    <option value="<?php echo esc_attr($code); ?>" data-name="<?php echo esc_attr($name); ?>"><?php echo esc_html($name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="button" id="icey_gt_add_lang_btn"><?php esc_html_e( 'Add', 'icey-google-translate' ); ?></button>
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
                    <th scope="row"><?php esc_html_e( 'Custom CSS', 'icey-google-translate' ); ?></th>
                    <td>
                        <textarea name="icey_gt_custom_css" rows="6" class="large-text" style="font-family: monospace;"><?php echo esc_textarea( get_option('icey_gt_custom_css') ); ?></textarea>
                        <p class="description"><?php esc_html_e( 'Add custom CSS to override default styling.', 'icey-google-translate' ); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function updateHiddenInput() {
            var langs = [];
            $('#icey_gt_pills_container li').each(function() {
                langs.push($(this).data('code'));
            });
            $('#icey_gt_active_langs').val(langs.join(','));
        }

        // Initiera sortable
        $('#icey_gt_pills_container').sortable({
            update: function(event, ui) { updateHiddenInput(); }
        });

        // Lägg till språk
        $('#icey_gt_add_lang_btn').on('click', function() {
            var selected = $('#icey_gt_add_lang_select').find(':selected');
            var code = selected.val();
            var name = selected.data('name');

            if (!code) return;
            if ($('#icey_gt_pills_container li[data-code="'+code+'"]').length > 0) {
                alert('<?php echo esc_js( __( 'Language already exists in the list.', 'icey-google-translate' ) ); ?>');
                return;
            }

            var pill = $('<li data-code="'+code+'" style="background: #fff; border: 1px solid #ccc; padding: 5px 10px; border-radius: 20px; cursor: move; display: flex; align-items: center; gap: 8px;">'+name+' <span class="remove-lang" style="color: red; cursor: pointer; font-weight: bold;">&times;</span></li>');
            $('#icey_gt_pills_container').append(pill);
            updateHiddenInput();
        });

        // Ta bort språk
        $(document).on('click', '.remove-lang', function() {
            $(this).parent('li').remove();
            updateHiddenInput();
        });
    });
    </script>
    <?php
}

// --- 5. Ladda CSS och JS på Frontenden ---
add_action( 'wp_enqueue_scripts', 'icey_gt_enqueue_scripts' );
function icey_gt_enqueue_scripts() {
    wp_enqueue_style( 'icey-gt-style', plugin_dir_url( __FILE__ ) . 'css/frontend.css', [], '1.0.0' );
    
    // Lägg in Custom CSS inline
    $custom_css = get_option( 'icey_gt_custom_css', '' );
    if ( ! empty( $custom_css ) ) {
        wp_add_inline_style( 'icey-gt-style', $custom_css );
    }

    wp_enqueue_script( 'icey-gt-script', plugin_dir_url( __FILE__ ) . 'js/frontend.js', [], '1.0.0', true );
    
    // Skicka variabler till JS
    wp_localize_script( 'icey-gt-script', 'iceyGTVars', [
        'defaultLang' => get_option( 'icey_gt_default_lang', 'sv' )
    ]);
}

// --- 6. Rendera HTML Modalen ---
add_action( 'wp_footer', 'icey_gt_render_modal_html' );
function icey_gt_render_modal_html() {
    $heading = get_option( 'icey_gt_heading', 'Choose language' );
    $explanation = get_option( 'icey_gt_explanation', 'Translations are handled automatically by Google Translate.' );
    $btn_cancel = get_option( 'icey_gt_btn_cancel', 'Cancel' );
    $btn_translate = get_option( 'icey_gt_btn_translate', 'Translate' );
    
    $all_langs = icey_gt_get_available_languages();
    $active_langs_str = get_option( 'icey_gt_active_langs', 'en,da,de,fi,fr,it,no,sv' );
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