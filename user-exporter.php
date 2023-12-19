<?php
/*
Plugin Name: FDK DevOps WP User Exporter
Description: Export user details to a CSV file.
Version: 1.0
Author: Faisal Dad Khan
*/

// Hook to WordPress admin menu
add_action('admin_menu', 'user_exporter_menu');

function user_exporter_menu() {
    // Add a menu item under "Tools" for user export
    add_submenu_page(
        'tools.php',
        'User Exporter',
        'User Exporter',
        'manage_options',
        'user-exporter',
        'user_exporter_page'
    );
}

function user_exporter_page() {
    ?>
    <div class="wrap">
        <h1>User Exporter</h1>
        <p>Click the button below to export user details to a CSV file.</p>
        <form method="post" action="">
            <?php wp_nonce_field('user_exporter_nonce', 'user_exporter_nonce_field'); ?>
            <input type="submit" class="button button-primary" value="Export Users" name="export_users">
        </form>
        <?php
        if (isset($_POST['export_users']) && check_admin_referer('user_exporter_nonce', 'user_exporter_nonce_field')) {
            // Perform the user export
            perform_user_export();
        }
        ?>
    </div>
    <?php
}

function perform_user_export() {
    // Get all users
    $users = get_users();

    // Prepare CSV data
    $csv_data = "ID,Username,Email,Registered\n";
    foreach ($users as $user) {
        $csv_data .= "{$user->ID},{$user->user_login},{$user->user_email},{$user->user_registered}\n";
    }

    // Set headers for file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="user_export.csv"');

    // Output CSV data
    echo $csv_data;

    // Ensure the script stops here
    exit;
}
