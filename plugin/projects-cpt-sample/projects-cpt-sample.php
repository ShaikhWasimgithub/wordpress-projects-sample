<?php
/*
Plugin Name: Projects CPT + Samples
Description: Registers a "Project" Custom Post Type, a "Project Categories" taxonomy, adds a "Short Description" meta box and creates 9 sample projects (3 per category).
Version: 1.0
Author: wasim
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function pcs_register_post_type_taxonomy() {
    $args = array(
        'labels' => array('name'=>'Projects','singular_name'=>'Project'),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug'=>'projects'),
        'supports' => array('title','editor','excerpt','thumbnail'),
        'menu_icon' => 'dashicons-portfolio'
    );
    register_post_type('project',$args);

    register_taxonomy('project_category','project',array(
        'labels'=>array('name'=>'Project Categories'),
        'hierarchical'=>true,
        'show_in_rest'=>true,
        'rewrite'=>array('slug'=>'project-category')
    ));
}
add_action('init','pcs_register_post_type_taxonomy');

function pcs_add_meta_boxes() {
    add_meta_box('pcs_short_desc','Short Description','pcs_short_desc_callback','project','normal','default');
}
add_action('add_meta_boxes','pcs_add_meta_boxes');
function pcs_short_desc_callback($post) {
    $val = get_post_meta($post->ID,'short_description',true);
    echo '<textarea style="width:100%" rows="3" name="short_description">'.esc_textarea($val).'</textarea>';
}
function pcs_save_post_meta($post_id) {
    if(isset($_POST['short_description'])){
        update_post_meta($post_id,'short_description',sanitize_textarea_field($_POST['short_description']));
    }
}
add_action('save_post','pcs_save_post_meta');

function pcs_create_terms_and_samples() {
    pcs_register_post_type_taxonomy();
    $terms = array('Frontend','Backend','Full Stack');
    foreach($terms as $t){ if(!term_exists($t,'project_category')) wp_insert_term($t,'project_category'); }

    if(get_option('pcs_samples_installed')) return;

    $samples = array(
        array('Responsive Portfolio Site','Portfolio with animations','Frontend'),
        array('Interactive To-Do App','To-do app with drag drop','Frontend'),
        array('Landing Page with Animations','Landing page design','Frontend'),
        array('RESTful API for Tasks','CRUD API project','Backend'),
        array('User Authentication System','Login & Register system','Backend'),
        array('E-commerce Order Processor','Order service','Backend'),
        array('Real-time Chat App','Chat using websockets','Full Stack'),
        array('Blog Platform','Blog with editor','Full Stack'),
        array('Marketplace with Payments','Marketplace app','Full Stack'),
    );
    foreach($samples as $s){
        $id = wp_insert_post(array(
            'post_title'=>$s[0],
            'post_content'=>'यह project का long description है।',
            'post_type'=>'project',
            'post_status'=>'publish'
        ));
        if($id){ update_post_meta($id,'short_description',$s[1]); wp_set_object_terms($id,$s[2],'project_category'); }
    }
    update_option('pcs_samples_installed',1);
    flush_rewrite_rules();
}
register_activation_hook(__FILE__,'pcs_create_terms_and_samples');
