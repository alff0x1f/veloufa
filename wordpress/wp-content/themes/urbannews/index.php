<?php global $data; ?>

<?php if($data['layout'] !='no-left-sidebar') { ?>
<?php include( trailingslashit( get_template_directory() ). '/template-home-sidebar.php' ); ?>
<?php } else if($data['layout'] == 'no-left-sidebar') {?>
<?php include( trailingslashit( get_template_directory() ). '/template-home-no-sidebar.php' ); ?>
<?php } ?>