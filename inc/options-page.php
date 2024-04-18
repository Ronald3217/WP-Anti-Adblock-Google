<?php

$text_domain = 'wp_anti_adblock_google_ronaldvillagran';

function wp_anti_adblock_google_ronaldvillagran_options_page()
{
    global $text_domain;
    $my_prefix = 'wpaag_';
    /**
     * Registers main options page menu item and form.
     */
    $args = array(
        'id' => 'wp_anti_adblock_google_options_page',
        'title' => 'WP Anti AdBlock Google - Google Adsense AdBlock Integration',
        'menu_title' => 'WP Anti AdBlock Google',
        'object_types' => array('options-page'),
        'option_key' => $my_prefix . 'plugin_options',
        'tab_group' => 'wp_anti_adblock_google_plugin_options',
        'tab_title' => 'Configuracion General',
    );

    // 'tab_group' property is supported in > 2.4.0.
    if (version_compare(CMB2_VERSION, '2.4.0')) {
        $args['display_cb'] = 'wp_anti_adblock_google_options_page_display';
    }


    $main_options = new_cmb2_box($args);

    /**
     * Options fields ids only need
     * to be unique within this box.
     * Prefix is not needed.
     */

    $main_options->add_field(
        array(
            'name' => esc_html__('Habilitar', $text_domain),
            'desc' => esc_html__('Marca esta casilla para habilitar', $text_domain),
            'id' => $my_prefix . 'enabled',
            'type' => 'select',
            'show_option_none' => false,
            'options' => array(
                false => esc_html__('Disabled', $text_domain),
                true => esc_html__('Enabled', $text_domain),
            ),
        )
    );

    $main_options->add_field(
        array(
            'name' => esc_html__('Google AdSense ID', $text_domain),
            'desc' => esc_html__('La ID del Mensaje de Google AdSense ej: pub-123456', $text_domain),
            'id' => $my_prefix . 'adsense_id',
            'type' => 'text',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'pub-1234567890123456',
            ),
        )
    );

    $main_options->add_field(
        array(
            'name' => esc_html__('Google Nonce Key', $text_domain),
            'desc' => esc_html__('La clave de Nonce de Google', $text_domain),
            'id' => $my_prefix . 'nonce_key',
            'type' => 'text',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'ggfyf692oxydDPyABC123D',
            )
        )
    );

    $main_options->add_field(
        array(
            'name' => esc_html__('Codigo del Mensaje de Google AdSense', $text_domain),
            'desc' => esc_html__('El Código de Google AdSense', $text_domain),
            'id' => $my_prefix . 'adsense_code',
            'type' => 'textarea_code',
        )
    );

    $main_options->add_field(
        array(
            'name' => esc_html__('Habilitar Proteccion de errores', $text_domain),
            'desc' => esc_html__('Habilitar protección de errores de Google', $text_domain),
            'id' => $my_prefix . 'error_enabled',
            'type' => 'select',
            'show_option_none' => false,
            'options' => array(
                false => esc_html__('Disabled', $text_domain),
                true => esc_html__('Enabled', $text_domain),
            )
        )
        );

    $main_options->add_field(
        array(
            'name' => esc_html__('Mensaje de protección de errores', $text_domain),
            'desc' => esc_html__('Código de protección de errores de Google', $text_domain),
            'id' => $my_prefix . 'error_code',
            'type' => 'textarea_code',
        )
        );
}

add_action('cmb2_admin_init', 'wp_anti_adblock_google_ronaldvillagran_options_page');

/**
 * A CMB2 options-page display callback override which adds tab navigation among
 * CMB2 options pages which share this same display callback.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 */
function wp_anti_adblock_google_options_page_display($cmb_options)
{
    $tabs = wp_anti_adblock_google_options_page_tabs($cmb_options);
?>
    <div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
        <?php if (get_admin_page_title()) : ?>
            <h2>
                <?php echo wp_kses_post(get_admin_page_title()); ?>
            </h2>
        <?php endif; ?>
        <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs as $option_key => $tab_title) : ?>
                <a class="nav-tab<?php if (isset($_GET['page']) && $option_key === $_GET['page']) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url($option_key); ?>"><?php echo wp_kses_post($tab_title); ?></a>
            <?php endforeach; ?>
        </h2>
        <form class="cmb-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" id="<?php echo $cmb_options->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo esc_attr($cmb_options->option_key); ?>">
            <?php $cmb_options->options_page_metabox(); ?>
            <?php submit_button(esc_attr($cmb_options->cmb->prop('save_button')), 'primary', 'submit-cmb'); ?>
        </form>
    </div>
<?php
}

/**
 * Gets navigation tabs array for CMB2 options pages which share the given
 * display_cb param.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 *
 * @return array Array of tab information.
 */
function wp_anti_adblock_google_options_page_tabs($cmb_options)
{
    $tab_group = $cmb_options->cmb->prop('tab_group');
    $tabs = array();

    foreach (CMB2_Boxes::get_all() as $cmb_id => $cmb) {
        if ($tab_group === $cmb->prop('tab_group')) {
            $tabs[$cmb->options_page_keys()[0]] = $cmb->prop('tab_title')
                ? $cmb->prop('tab_title')
                : $cmb->prop('title');
        }
    }

    return $tabs;
}
