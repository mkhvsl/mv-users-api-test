<h2><?php echo $this->properties->name() ?></h2>
<form action="options.php" method="post">
    <?php 
    settings_fields( $this->prefix . '_settings' );
    do_settings_sections( $this->prefix );
    ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
</form>