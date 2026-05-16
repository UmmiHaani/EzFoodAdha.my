<?php
function get_school_gallery_images() {
    $images = array();
    if(!empty($_SESSION['setting_school_gallery'])){
        $decoded = json_decode($_SESSION['setting_school_gallery'], true);
        if(is_array($decoded)){
            $images = array_values(array_filter($decoded));
        }
    }
    if(empty($images) && !empty($_SESSION['setting_cover_img'])){
        $images[] = $_SESSION['setting_cover_img'];
    }
    return $images;
}
