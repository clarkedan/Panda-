<?php

$metaboxes = array(
    'post_meta' => array(
        'title' => 'Extra Post Information',
        'post_type' => 'event',
        'fields' => array(
            'location' => array(
                'title' => 'Post Location',
                'type' => 'text',
                'description' => 'Where was this post located?'
            ),
            'price' => array(
                'title' => 'Post Price',
                'type' => 'number',
                'description' => 'The price of the post'
            ),
            'side' => array(
                'title' => 'What side is it on?',
                'type' => 'select',
                'description' => '',
                'choices' => array('Left', 'Right')
            ),
            'extra_content' => array(
                'title' => 'Extra Content',
                'type' => 'textarea',
                'description' => '',
                'rows' => 5
            )
        )
    ),
    'page_meta' => array(
        'title' => 'Extra Page Information',
        'post_type' => 'page'
    ),
    'events_meta' => array(
        'title' => 'Extra Event Information',
        'post_type' => 'event'
    ),
    'staff_meta' => array(
        'title' => 'Extra Staff Information',
        'post_type' => 'staff',
        'fields' => array(
            'location' => array(
                'title' => 'Role',
                'type' => 'text',
                'description' => 'Where is your role?'
            ),
        )
    ),
    'post_formats_meta' => array(
        'title' => 'Post Formats Feilds',
        'post_type' => 'post',
        'fields' => array(
            'video_link' => array(
                'title' => 'Video Link',
                'type' => 'text',
                'condition' => 'video'
            ),
            'audio_link' => array(
                'title' => 'Audio Link',
                'type' => 'text',
                'condition' => 'audio'
            ),
            'image_url' => array(
                'title' => 'Image URL',
                'type' => 'text',
                'condition' => 'image'
            )
        )
    )
);

function create_custom_meta_boxes(){
    global $metaboxes;

    if(!empty($metaboxes)){
        foreach ($metaboxes as $metaboxID => $metabox) {
            add_meta_box($metaboxID, $metabox['title'], 'output_custom_meta_box', $metabox['post_type'],
            'normal', 'high', $metabox);
        };
    }
}

add_action('admin_init', 'create_custom_meta_boxes');

function output_custom_meta_box($post, $metabox){
    $fields = $metabox['args']['fields'];

    $customValues = get_post_custom($post->ID);

    echo '<input type="hidden" name="post_format_meta_box_nonce" value="'.wp_create_nonce( basename(__FILE__) ).'">';

    if($fields){
        foreach ($fields as $fieldID => $field) {
            if($customValues[$fieldID][0]){
                $value = $customValues[$fieldID][0];
            }

            if(isset($field['condition'])){
                $condition = 'class="conditionalField" data-condition="'.$field['condition'].'"';
            } else {
                $condition = '';
            }

            switch($field['type']){
                case 'text':
                    echo '<div id="'.$fieldID.'" '.$condition.' >';
                        echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                        echo '<input type="text" name="'.$fieldID.'" class="inputField" value="'.isset($value).'">';
                    echo '</div>';
                break;
                case 'number':
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<input type="number" name="'.$fieldID.'" class="inputField" value="'.$customValues[$fieldID][0].'">';
                break;
                case 'textarea':
                    echo $customValues[$fieldID][0];
                    echo '<br>';
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<textarea class="inputField" name="'.$fieldID.'" rows="'.$field['rows'].'"></textarea>';
                break;
                case 'select':
                    echo $customValues[$fieldID][0];
                    echo '<br>';
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<select name="'.$fieldID.'" class="inputField customSelect">';
                        echo '<option class="customSelect"> -- Please Enter a value -- </option>';
                        foreach($field['choices'] as $choice){
                            echo '<option class="customSelect" value="'.$choice.'">'.$choice.'</option>';
                        }
                    echo '</select>';
                break;
                default:
                    echo $customValues[$fieldID][0];
                    echo '<br>';
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<input type="text" name="'.$fieldID.'" class="inputField">';
                break;
            }
        }
    }
}



function save_custom_metaboxes($postID){
    global $metaboxes;

    if(! wp_verify_nonce( $_POST['post_format_meta_box_nonce'], basename(__FILE__) ) ){
        return $postID;
    }

    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
        return $postID;
    }

    if( $_POST['post_type'] == 'page'){
        if(! current_user_can('edit_page', $postID) ){
            return $postID;
        }
    } elseif(! current_user_can('edit_post', $postID)){
        return $postID;
    }

    $postType = get_post_type();

    foreach ($metaboxes as $metaboxID => $metabox) {
        if($metabox['post_type'] == $postType){
            $fields = $metabox['fields'];
            foreach ($fields as $fieldID => $field) {
                $oldValue = get_post_meta($postID, $fieldID, true);
                $newValue = $_POST[$fieldID];

                if($newValue && $newValue != $oldValue){
                    update_post_meta($postID, $fieldID, $newValue);
                } elseif($newValue == '' || ! isset($_POST[$fieldID]) ){
                    delete_post_meta($postID, $fieldID, $oldValue);
                }

            }
        }
    }




}
add_action('save_post', 'save_custom_metaboxes');
