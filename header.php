<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <?php wp_head(); ?>
    </head>


    <header class="header">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center">
            <div class="logo">
                <a href="/" class="logo__link"><img class="logo__image" src="https://s3.prayer.tools/pt-logo.png" alt=""></a>
            </div>
            <a href="https://prayer.tools">Prayer.Tools Home</a>
        </div>

    </header>

    <div class="container wrapper">