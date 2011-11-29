<!-- general title -->
<?php if( isset( $data->title ) ): ?>
<h1>
<?php echo $data->title;?>
</h1>
<?php endif;?>
<!-- general intro -->
<?php if( isset( $data->introtext ) ): ?>
<div id="introtext">
<?php echo $data->introtext;?>
</div>
<?php endif;?>