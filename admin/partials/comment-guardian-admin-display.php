<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.codepa.de
 * @since      1.0.0
 *
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="cg-admin-page">

    <div class="cg-admin-page-content">
        <img class="cg-logo" src="<?php echo plugin_dir_url( __FILE__ ) . "../img/comment-guardian.png" ?>" alt="Comment Guardian">
        <h1>Comment Guardian</h1>
        <h4><?php _e("With Comment Guardian, you can easily identify unwanted comments as spam! Just select the language of your website.
        From then on, all comments written in other languages will be definitively marked as spam and not allowed in. Simple, isn't it?", "comment-guardian"); ?></h4>

        <?php 
            if (!empty(get_option("cg-language"))) {
                ?>

                    <h4 style="color: var(--cg-color-primary) !important;margin-bottom:0">
                    <?php _e("Great! Your spam protection is activated. You are now protected from annoying spam! <br>There is nothing else for you to do.", "comment-guardian") ?>
                    </h4>

                <?php
            }
        ?>

        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php
            settings_fields('comment-guardian-options');
            do_settings_sections('comment-guardian-options');
            submit_button();
            ?>
        </form>
    </div>


</div>