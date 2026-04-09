<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'icey_gt_heading' );
delete_option( 'icey_gt_explanation' );
delete_option( 'icey_gt_btn_cancel' );
delete_option( 'icey_gt_btn_translate' );
delete_option( 'icey_gt_default_lang' );
delete_option( 'icey_gt_active_langs' );
?>