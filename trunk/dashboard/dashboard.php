<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
require_once SITECHECKUPPATH . 'wizard/wizard.php';
global $site_checkup_label_tabs;
$site_checkup_label_tabs =  [
    'Dashboard',
    'Wizard'
];
function site_checkup_page_content()
{
?>

    <div id="site-checkup-logo-container">
        <img id="site-checkup-logo" src="<?php echo esc_attr(SITECHECKUPURL); ?>/assets/imagens/logo.png" alt="Site Checkup Logo" width="200px">
    </div>


    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'sitecheckup-nonce')) {
            //if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'sitecheckup-nonce')) {
            $site_checkup_active_tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : '0';
            switch ($site_checkup_active_tab) {
                case '1':
                    site_checkup_tab_wizard(1);
                    break;
                default:
                    site_checkup_tab_dashboard(0);
                    break;
            }
        } else {
            echo '<p>Nonce verification failed!</p>';
            site_checkup_tab_dashboard(0);
        }
    } else {
        site_checkup_tab_dashboard(0);
    }
    ?>
    </div>
<?php
}
// Function to render the Dashboard page
function site_checkup_tab_dashboard($active_tab)
{
    global $site_checkup_label_tabs;
    // site_checkup_render_nav_tabs($site_checkup_active_tab); // Pass the active tab
    site_checkup_render_nav_tabs($active_tab); // Pass the active tab
?>
    <div class="content">
        <?php
        echo '<h2>' . esc_html($site_checkup_label_tabs[$active_tab]) . '</h2>';
        ?>
        To begin using the Site Checkup Wizard Plugin, click over the tab "Wizard".
        <br>
        You will find a series of 5 steps in the wizard.
        Simply click "Next" on each step to proceed through the checks.<br>
        The wizard will guide you through each phase, helping you ensure your site is in optimal condition.<br>
        Visit the <a href="https://sitecheckup.eu" target="_blank">plugin site</a> if you need more information.
    </div>
<?php
}
function site_checkup_render_nav_tabs($active_tab)
{
    global $site_checkup_label_tabs;
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($site_checkup_label_tabs as $tab => $label) {
        $active_class = $active_tab === $tab ? ' nav-tab-active' : '';
        echo '<form method="post" action="">';
        wp_nonce_field('sitecheckup-nonce');
        echo '<input type="hidden" name="page" value="sitecheckup">';
        echo '<input type="hidden" name="tab" value="' . esc_attr($tab) . '">';
        echo '<button type="submit" class="nav-tab' . $active_class . '">' . esc_html($label) . '</button>';
        echo '</form>';
    }
    echo '</h2>';
}
