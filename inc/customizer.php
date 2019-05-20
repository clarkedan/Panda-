<?php

function mytheme_customize_register( $wp_customize ) {
   //All our sections, settings, and controls will be added here

   $wp_customize->add_section( 'custom_theme_colour_section' , array(
       'title'      => __( 'Colours', '18wdwu07Panda' ),
       'priority'   => 30,
   ) );

   $wp_customize->add_setting( 'custom_background_colour' , array(
       'default'   => '#ffffff',
       'transport' => 'refresh',
   ) );

   $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'custom_background_control', array(
   	'label'      => __( 'Background Colour', '18wdwu07Panda' ),
   	'section'    => 'custom_theme_colour_section',
   	'settings'   => 'custom_background_colour',
   ) ) );


   $wp_customize->add_setting( 'navigation_background' , array(
       'default'   => '#ffffff',
       'transport' => 'refresh',
   ) );

   $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'custom_header_background_colour', array(
   	'label'      => __( 'Header Colour', '18wdwu07Panda' ),
   	'section'    => 'custom_theme_colour_section',
   	'settings'   => 'navigation_background',
   ) ) );








   $wp_customize->add_section( 'layout_section' , array(
       'title'      => __( 'Layout Section', '18wdwu07Panda' ),
       'priority'   => 30,
   ) );

   $wp_customize->add_setting( 'sidebar_position' , array(
       'default'   => 'right',
       'transport' => 'refresh',
   ) );

   $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'custom_sidebar_control', array(
   	'label'      => __( 'Sidebar Position', '18wdwu07Panda' ),
   	'section'    => 'layout_section',
   	'settings'   => 'sidebar_position',
    'type' => 'radio',
    'choices' => array(
        'left' => 'Left Side',
        'right' => 'Right Side'
    )
   ) ) );








}
add_action( 'customize_register', 'mytheme_customize_register' );

function mytheme_customize_css()
{
    ?>
         <style type="text/css">
             body {
                 background-color: <?php echo get_theme_mod('custom_background_colour', '#000000'); ?>;
             }

             .custom_nav{
                 background-color: <?php echo get_theme_mod('navigation_background', '#ffffff'); ?>;
             }
         </style>
    <?php
}
add_action( 'wp_head', 'mytheme_customize_css');
