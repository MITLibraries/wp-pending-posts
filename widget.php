<?php
/**
 * The template for the urgent / pending posts widget.
 *
 * @package WP Pending Posts
 * @since 1.0.0
 */

?>
<table class="widefat">
	<thead>
		<tr>
			<th class="row-title" scope="col">Title</th>
			<th>Author</th>
		</tr>
	</thead>
	<tbody>
<?php
if ( $urgent->have_posts() ) {
	while ( $urgent->have_posts() ) {
		// Add the 'form-invalid' class to all urgent post listings so they appear red.
		$urgent->the_post();
	?>
		<tr class="urgent">
			<td class="row-title">
				<a href="<?php echo esc_url( get_edit_post_link() ); ?>">
					<?php echo esc_html( get_the_title() ); ?>
				</a>
			</td>
			<td><?php echo esc_html( get_the_author() ); ?></td>
		</tr>
	<?php
	}
} else {
	?>
		<tr>
			<td colspan="2">There are no urgent posts.</td>
		</tr>	
	<?php
}
?>
<?php
if ( $pending->have_posts() ) {
	while ( $pending->have_posts() ) {
		$pending->the_post();
	?>
		<tr>
			<td class="row-title">
				<a href="<?php echo esc_url( get_edit_post_link() ); ?>">
					<?php echo esc_html( get_the_title() ); ?>
				</a>
			</td>
			<td><?php echo esc_html( get_the_author() ); ?></td>
		</tr>
	<?php
	}
} else {
	?>
		<tr>
			<td colspan="2">There are no pending posts.</td>
		</tr>	
	<?php
}
?>
	</tbody>
</table>
