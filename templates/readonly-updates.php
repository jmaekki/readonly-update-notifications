<?php
/**
 * Readonly updates template.
 */

namespace JMaekki;

$current_core_version = \get_bloginfo( 'version' );
$plugin_updates       = \get_plugin_updates();
$core_updates         = \get_core_updates();

// Bail early if all plugins are up-to-date.
if ( empty( $plugin_updates ) ) {
    echo esc_attr( '<div class="wrap">' );
        echo '<h1 class="wp-heading-inline">' . esc_attr__( 'Updates', 'readonly-update-notifications' ) . '</h1>';
        echo '<p>' . esc_attr__( 'Your plugins are all up to date.', 'readonly-update-notifications' ) . '</p>';
    echo esc_attr( '</div>' );

    return;
}

if ( empty( $core_updates[0]->response && $core_updates[0]->response === 'upgrade' ) ) {
    $core_update_version = false;
} else {
    $core_update_version = $core_updates[0]->current;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_attr_e( 'Updates', 'readonly-update-notifications' ); ?></h1>
    <p><?php esc_attr_e( 'The following plugins have new versions available. To update please contact site administrator.', 'readonly-update-notifications' ); ?></p>

    <table class="widefat updates-table" id="update-plugins-table">
        <thead>
            <tr>
                <td class="manage-column"><?php esc_attr_e( 'Plugins', 'readonly-update-notifications' ); ?></td>
            </tr>
        </thead>

        <tbody class="plugins">
            <?php foreach ( $plugin_updates as $plugin_data ) : ?>
                <?php

                // Get plugin icon. Fallbacks to dashicon.
                if ( ! empty( $plugin_data->update->icons['default'] ) ) {
                    $icon = '<img src="' . esc_url( $plugin_data->update->icons['default'] ) . '" alt="" />';
                } else {
                    $icon = '<span class="dashicons dashicons-admin-plugins"></span>';
                }

                // Get plugin compat for running version of WordPress.
                if ( ! empty( $plugin_data->update->tested ) && version_compare( $plugin_data->update->tested, $current_core_version, '>=' ) ) {

                    /* translators: 1: WordPress version */
                    $compat = '<br />' . sprintf( __( 'Compatibility with WordPress %1$s: 100%% (according to its author)', 'readonly-update-notifications' ), $current_core_version );
                } elseif ( ! empty( $plugin_data->update->compatibility->{$current_core_version} ) ) {
                    $compat = $plugin_data->update->compatibility->{$current_core_version};

                    /* translators: 1: WordPress version, 2: Compatibility percentage, 3: Compatibility votes count, 4: Compatibility votes count total */
                    $compat = '<br />' . sprintf( __( 'Compatibility with WordPress %1$s: %2$d%% (%3$d "works" votes out of %4$d total)', 'readonly-update-notifications' ), $current_core_version, $compat->percent, $compat->votes, $compat->total_votes );
                } else {

                    /* translators: 1: WordPress version */
                    $compat = '<br />' . sprintf( __( 'Compatibility with WordPress %1$s: Unknown', 'readonly-update-notifications' ), $current_core_version );
                }

                // Get plugin compat for updated version of WordPress.
                if ( $core_update_version ) {
                    if ( ! empty( $plugin_data->update->tested ) && version_compare( $plugin_data->update->tested, $core_update_version, '>=' ) ) {

                        /* translators: 1: WordPress version */
                        $compat .= '<br />' . sprintf( __( 'Compatibility with WordPress %1$s: 100%% (according to its author)', 'readonly-update-notifications' ), $core_update_version );
                    } elseif ( ! empty( $plugin_data->update->compatibility->{$core_update_version} ) ) {
                        $update_compat = $plugin_data->update->compatibility->{$core_update_version};

                        /* translators: 1: WordPress version, 2: Compatibility percentage, 3: Compatibility votes count, 4: Compatibility votes count total */
                        $compat .= '<br />' . sprintf( __( 'Compatibility with WordPress %1$s: %2$d%% (%3$d "works" votes out of %4$d total)', 'readonly-update-notifications' ), $core_update_version, $update_compat->percent, $update_compat->votes, $update_compat->total_votes );
                    } else {

                        /* translators: 1: WordPress version */
                        $compat .= '<br />' . sprintf( __( 'Compatibility with WordPress %1$s: Unknown', 'readonly-update-notifications' ), $core_update_version );
                    }
                }
                ?>

                <tr>
                    <td class="plugin-title">
                        <p>
                            <?php echo $icon; // phpcs:ignore ?>

                            <strong>
                                <?php echo esc_attr( $plugin_data->Name ); // phpcs:ignore ?>
                            </strong>

                            <?php

                                /* translators: 1: plugin version, 2: new version */
                                printf( esc_attr__( 'You have version %1$s installed. Update to %2$s.', 'readonly-update-notifications' ),
                                    $plugin_data->Version, // phpcs:ignore
                                    $plugin_data->update->new_version // phpcs:ignore
                                );

                                echo $compat; // phpcs:ignore
                            ?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
