<?php

$metaboxes = array(
    'post_meta' => array(
        'title' => 'Extra Post Information',
        'post_type' => 'post',
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
    if($fields){
        foreach ($fields as $fieldID => $field) {
            switch($field['type']){
                case 'text':
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<input type="text" name="'.$fieldID.'" class="inputField">';
                break;
                case 'number':
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<input type="number" name="'.$fieldID.'" class="inputField">';
                break;
                case 'textarea':
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<textarea class="inputField" name="'.$fieldID.'" rows="'.$field['rows'].'"></textarea>';
                break;
                case 'select':
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<select name="'.$fieldID.'" class="inputField customSelect">';
                        echo '<option class="customSelect"> -- Please Enter a value -- </option>';
                        foreach($field['choices'] as $choice){
                            echo '<option class="customSelect" value="'.$choice.'">'.$choice.'</option>';
                        }
                    echo '</select>';
                break;
                default:
                    echo '<label for="'.$fieldID.'">'.$field['title'].'</label>';
                    echo '<input type="text" name="'.$fieldID.'" class="inputField">';
                break;
            }
        }
    }
    // var_dump($fields);
}
