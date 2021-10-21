<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
add_action( 'init', 'easl_table_register_post_type' );

add_action( 'add_meta_boxes', 'easl_tb_add_meta_box' );
add_action( 'save_post', 'easl_tb_save_table_data' );
add_action( 'admin_footer', 'easl_tb_add_script_templates' );
add_action( 'admin_enqueue_scripts', 'easl_tb_add_assets' );

function easl_table_register_post_type() {
    register_post_type( 'easl_table', array(
        'labels'          => array(
            'name'               => __( 'Programme Tables', 'total-child' ),
            'singular_name'      => __( 'Programme Table', 'total-child' ),
            'add_new'            => __( 'Add New', 'total-child' ),
            'add_new_item'       => __( 'Add New Table', 'total-child' ),
            'edit_item'          => __( 'Edit Table', 'total-child' ),
            'new_item'           => __( 'Add New Table', 'total-child' ),
            'view_item'          => __( 'View Table', 'total-child' ),
            'search_items'       => __( 'Search Tables', 'total-child' ),
            'not_found'          => __( 'No Tables Found', 'total-child' ),
            'not_found_in_trash' => __( 'No Tables Found In Trash', 'total-child' )
        ),
        'public'          => false,
        'show_ui'         => true,
        'capability_type' => 'post',
        'has_archive'     => false,
        'menu_position'   => 25,
        'rewrite'         => false,
        'supports'        => array(
            'title'
        ),
    ) );
}

function easl_tb_save_table_data( $post_id ) {
    global $wpdb;
    if ( 'easl_table' != get_post_type( $post_id ) ) {
        return $post_id;
    }
    if ( empty( $_POST['_tb_nonce'] ) || ! wp_verify_nonce( $_POST['_tb_nonce'], 'save_table_data' ) ) {
        return $post_id;
    }
    if ( empty( $_POST['easl_tb_table_data'] ) ) {
        return $post_id;
    }
    $table_rows = json_decode( wp_unslash( $_POST['easl_tb_table_data'] ) );
    if ( ! $table_rows ) {
        delete_post_meta( $post_id, '_easl_table_rows' );
        $wpdb->update(
            $wpdb->posts,
            array( 'post_content' => '' ),
            array( 'ID' => $post_id, ),
            array( '%s' ),
            array( '%d' )
        );
        
        return $post_id;
    }
    $table_rows = easl_tb_sanitize_row_data( $table_rows );
    
    if ( ! $table_rows ) {
        delete_post_meta( $post_id, '_easl_table_rows' );
        
        $wpdb->update(
            $wpdb->posts,
            array( 'post_content' => '' ),
            array( 'ID' => $post_id, ),
            array( '%s' ),
            array( '%d' )
        );
        
        return $post_id;
    }
    update_post_meta( $post_id, '_easl_table_rows', $table_rows );
    
    $html = '';
    foreach ( $table_rows as $tr ) {
        $tr_class  = 'agenda-table-row-' . $tr['bgColor'];
        $td1_Class = '';
        if ( $tr['centerCol1'] ) {
            $td1_Class = 'easl-text-center';
        }
        $td2_Class = '';
        if ( $tr['centerCol2'] ) {
            $td2_Class = 'easl-text-center';
        }
        $html .= '<tr class="' . $tr_class . '">';
        
        if ( $tr['isCaption'] ) {
            $html .= '<td class="easl-text-center" colspan="2"><h3>' . str_replace( "\n", '<br>', $tr['col1Content'] ) . '</h3></td>';
        } else {
            $html .= '<td class="nowrap ' . $td1_Class . '">' . $tr['col1Content'] . '</td>';
            $html .= '<td class="' . $td2_Class . '">' . str_replace( "\n", '<br>', $tr['col2Content'] ) . '</td>';
        }
        $html .= '</tr>';
    }
    if ( $html ) {
        $html = '<table class="agenda-table">' . $html . '</table>';
        $wpdb->update(
            $wpdb->posts,
            array( 'post_content' => $html ),
            array( 'ID' => $post_id, ),
            array( '%s' ),
            array( '%d' )
        );
    }
    
    return $post_id;
}

function easl_tb_sanitize_row_data( $rows ) {
    $sanitized = array();
    foreach ( $rows as $order => $tr ) {
        $data = array(
            'order'       => $order,
            'isCaption'   => false,
            'bgColor'     => 'white',
            'col1Content' => '',
            'centerCol1'  => false,
            'col2Content' => '',
            'centerCol2'  => false,
        );
        if ( ! empty( $tr->isCaption ) ) {
            $data['isCaption'] = true;
        }
        if ( ! empty( $tr->bgColor ) ) {
            $data['bgColor'] = $tr->bgColor;
        }
        if ( ! empty( $tr->col1Content ) ) {
            $data['col1Content'] = $tr->col1Content;
        }
        if ( ! empty( $tr->col2Content ) ) {
            $data['col2Content'] = $tr->col2Content;
        }
        if ( ! empty( $tr->centerCol1 ) ) {
            $data['centerCol1'] = true;
        }
        if ( ! empty( $tr->centerCol2 ) ) {
            $data['centerCol2'] = true;
        }
        $sanitized[] = $data;
    }
    
    return $sanitized;
}

function easl_tb_add_meta_box( $post_type ) {
    add_meta_box(
        'easl_builder_mb',
        __( 'Table Builder', 'textdomain' ),
        'easl_tb_render_mb_content',
        'easl_table',
        'normal',
        'low'
    );
}

/**
 * @param WP_Post $post
 */
function easl_tb_render_mb_content( $post ) {
    ?>
    <div id="easl-table-builder">
        <table id="easl-tb-table" class="agenda-table">

        </table>
        <div class="easl-tb-actions">
            <button class="button-secondary easl-tb-add-row">Add row</button>
        </div>
        <div id="easl-tb-editor">

        </div>
        <div style="display: none">
            <?php wp_nonce_field( 'save_table_data', '_tb_nonce' ); ?>
            <textarea id="easl-tb-table-data" name="easl_tb_table_data" style="display: none;"></textarea>
        </div>
    </div>
    <?php
}

function easl_tb_add_script_templates() {
    $screen = get_current_screen();
    if ( ! empty( $screen->post_type ) && 'easl_table' == $screen->post_type ) {
        ?>
        <script type="text/template" id="easl-tb-row-template">
            <td class="easl-tb-row-action">
                <div class="easl-tb-row-action-wrap">
                    <a href="#" class="easl-tb-sort"><span class="dashicons dashicons-move"></span></a>
                    <a href="#" class="easl-tb-edit"><span class="dashicons dashicons-edit"></span></a>
                    <a href="#" class="easl-tb-remove"><span class="dashicons dashicons-trash"></span></a>
                </div>
            </td>
            <% if(isCaption){ %>
            <td class="<%= cssClasses1 %>" colspan="2"><h3><%= col1Content %></h3></td>
            <% }else{ %>
            <td class="nowrap <%= cssClasses1 %>"><%= col1Content %></td>
            <td class="<%= cssClasses2 %>"><%= col2Content %></td>
            <% } %>
        </script>
        <script type="text/template" id="easl-tb-editor-template">
            <div class="easl-tb-editor-inner">
                <table>
                    <tr id="tbe-row-is-caption">
                        <th>
                            <label for="easl-tbe-is-caption">Is caption</label>
                        </th>
                        <td>
                            <input id="easl-tbe-is-caption" type="checkbox" value="1" <%= isCaption ? 'checked="checked"' : '' %>>
                        </td>
                    </tr>
                    <tr id="tbe-row-bgcolor">
                        <th>
                            <label for="easl-tbe-is-bg-color">Background color</label>
                        </th>
                        <td>
                            <select id="easl-tbe-is-bg-color">
                                <option value="white"
                                <%= bgColor === 'white' ? 'selected="selected"' : '' %>>Default(white)</option>
                                <option value="blue"
                                <%= bgColor === 'blue' ? 'selected="selected"' : '' %>>Blue</option>
                                <option value="lightblue"
                                <%= bgColor === 'lightblue' ? 'selected="selected"' : '' %>>Lightblue</option>
                                <option value="yellow"
                                <%= bgColor === 'yellow' ? 'selected="selected"' : '' %>>Yellow</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="tbe-row-content1">
                        <th>
                            <label for="easl-tbe-is-col1content">Column 1 content</label>
                        </th>
                        <td>
                            <textarea id="easl-tbe-is-col1content" rows="3"><%= col1Content %></textarea>
                        </td>
                    </tr>
                    <tr id="tbe-row-centercol1">
                        <th>
                            <label for="easl-tbe-centercol1">Center Column 1</label>
                        </th>
                        <td>
                            <input id="easl-tbe-centercol1" type="checkbox" value="1" <%= centerCol1 ? 'checked="checked"' : '' %>>
                        </td>
                    </tr>
                    <tr id="tbe-row-content2" class="<%= isCaption ? 'easl-tb-hide' : ''  %>">
                        <th>
                            <label for="easl-tbe-is-col2content">Column 2 content</label>
                        </th>
                        <td>
                            <textarea id="easl-tbe-is-col2content" rows="3"><%= col2Content %></textarea>
                        </td>
                    </tr>
                    <tr id="tbe-row-centercol2" class="<%= isCaption ? 'easl-tb-hide' : ''  %>">
                        <th>
                            <label for="easl-tbe-centercol2">Center Column 2</label>
                        </th>
                        <td>
                            <input id="easl-tbe-centercol2" type="checkbox" value="1" <%= centerCol2 ? 'checked="checked"' : '' %>>
                        </td>
                    </tr>
                </table>
                <div class="easl-tbe-actions">
                    <button class="easl-tb-save">Save</button>
                    <button class="easl-tb-remove">Cancel</button>
                </div>
            </div>
        </script>
        <?php
    }
}

function easl_tb_add_assets() {
    global $post;
    $screen = get_current_screen();
    if ( ! empty( $screen->post_type ) && 'easl_table' == $screen->post_type ) {
        wp_enqueue_style( 'jquery-ui-lib-style', get_stylesheet_directory_uri() . '/assets/lib/jquery-ui-1.12.1.custom/jquery-ui.css' );
        wp_enqueue_style( 'easl-table-builder', get_stylesheet_directory_uri() . '/assets/css/admin/table-builder.css', [], time() );
        wp_enqueue_script( 'easl-table-builder', get_stylesheet_directory_uri() . '/assets/js/admin/table-builder.js', [
            'jquery',
            'underscore',
            'jquery-ui-sortable',
            'backbone',
        ], time(), true );
        $script_data = array(
            'rows' => get_post_meta( $post->ID, '_easl_table_rows', true )
        );
        wp_localize_script( 'easl-table-builder', 'EASL_Table_Builder_Data', $script_data );
    }
}