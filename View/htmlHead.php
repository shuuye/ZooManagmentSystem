<?php

$content = '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . (isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Zoo Negara') . '</title>';

// Check if $cssFiles is an array and include each CSS file
if (isset($cssFiles) && is_array($cssFiles)) {
    foreach ($cssFiles as $cssFile) {
        $content .= '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">';
    }
}

$content .= '
    </head>
    <body>
    
';

// Now $content holds the entire HTML structure as a string
echo $content;
?>
