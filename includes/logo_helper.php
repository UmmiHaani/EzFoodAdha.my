<?php
/**
 * Logo for light backgrounds (auth forms, white panels).
 * Prefers dark logo; falls back to white logo if dark not uploaded.
 */
function get_light_bg_logo() {
    if (!empty($_SESSION['setting_logo_img_dark'])) {
        return $_SESSION['setting_logo_img_dark'];
    }
    return $_SESSION['setting_logo_img'] ?? '';
}
