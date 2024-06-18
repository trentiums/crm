<?php
return [
    'log_type' => array(
        "0" => "ERROR",
        "1" => "API",
    ),
    'date_format' => 'Y-m-d',
    'supported_file_format' => array(
        'general' => "image/jpg,image/jpeg,image/png,video/mp4,video/avi,application/octet-stream,video/quicktime",
        'image' => "image/jpg,image/jpeg,image/png",
        'icon' => "image/jpg,image/jpeg,image/png",
        'video' => "video/mp4,video/avi,application/octet-stream,video/quicktime",
    ),
    'file_size' => array(
        'general' => 5120, // In Kilobytes
        'image' => 5120, // In Kilobytes
        'icon' => 2048, //In Kilobytes
        'video' => 512000, //In Kilobytes
    ),
    'supported_file_extension' => array(
        'general' => "jpg,jpeg,png,mp4,avi,mov,pdf,doc",
        'icon' => "jpg,jpeg,png",
        'video' => "mp4,avi,mov",
    ),
];
