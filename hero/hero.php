<?php

/**
 * 
 * Dyad Image Block Template.
 * 
 */

// Create class attribute allowing for custom "className" and "align" values.
$className = 'dyad-hero image-holder';
$post_id = get_the_ID();
$post_title = get_the_title($post_id);
$post_location = get_field("project_location", $post_id);

if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

$id = $block['id'] . '';
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

$image = get_field('image');
$padding_value = "";
$flex_ratio = "";
if (!$image) $className .= " image-missing";

if ($image) {
	// Image Info
	$alt = $image['alt'];
	$caption = $image['caption'];
	$image_id = $image['ID'];
	// Generate Srcset
	$srcset = wp_get_attachment_image_srcset($image_id);
	$fallback_src = wp_get_attachment_image_url($image_id, 'medium_large');
	$dataPrefix = is_admin() ? "" : "data-";

	// Image Ratio
	$w = $image['width'];
	$h = $image['height'];
	$r = round($w / $h, 4);
	$o = $w >= $h ? ' is-horizontal' : ' is-vertical';
	$className .= $o;
	// Container Styles | figure.image-holder > .ratio > img
	$padding_top = $h / $w * 100;
	$padding_value = "--padding: $padding_top%;";
	// $padding_styles = " style='padding-top: $padding_top%;'"; // goes on image parent (.ratio)
	$flex_ratio = "--ratio: $r;";
	// $flex_styles = " style='flex: $r 1 0%;'"; // goes on image grandparent (figure.image-holder)

	$focal_point_x = get_field('focal_point_x') ?? 50;
	$focal_point_y = get_field('focal_point_y') ?? 50;
	$has_custom_focal_point = $focal_point_y !== 50 || $focal_point_y !== 50;
	$focal_point = $has_custom_focal_point ? 'object-position:' . $focal_point_x . "% " . $focal_point_y . "%;" : "";
}


$allowed_blocks = array('core/paragraph');
$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
			'align'       => 'center',
			'level'       => '2',
		),
	)
);
?>
<figure id="<?= esc_attr($id); ?>" class="<?= esc_attr($className) ?>" style="<?= $padding_value . $flex_ratio; ?>">
	<?php if ($image) : ?>

		<?php if (is_admin()) : ?>
			<div class="acf-actions -hover">
				<a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a>
				<a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a>
			</div>
		<?php endif; ?>
		<img style="<?= $focal_point; ?>" class="lazyload" <?= $dataPrefix; ?>sizes="(min-aspect-ratio: 1/1) 100vw, 140vw" alt="<?= $alt; ?>" <?= $dataPrefix; ?>srcset='<?= esc_attr($srcset); ?>' <?= $dataPrefix; ?>src='<?= $fallback_src; ?>' />

		<?php if ($caption) : ?>
			<figcaption>
				<p><?= $caption; ?></p>
			</figcaption>
		<?php endif; ?>
		<?php if ($post_location) : ?>
			<div class="project-title-container">
				<h2><?= $post_title ?></h2>
				<h3><?= $post_location ?></h3>
			</div>
		<?php endif; ?>

		<?php if (!$post_location) : ?>
			<InnerBlocks className="dyad-hero-innerblock acf-innerblocks-container" orientation="horizontal" allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>" template="<?php echo esc_attr(wp_json_encode($template)); ?>" />
		<?php endif; ?>

		<button class="scroll-indicator arrow-indicator">
			<?php include(get_template_directory() . "/assets/cursors/white/arrow-left.svg") ?>
		</button>


	<?php elseif (is_user_logged_in()) : ?>
		<div class="acf-notify">

			<p>
				<?php if (is_admin()) : ?>
					<em>No image selected.</em>
					<span style="margin-top: 1em;" class="acf-button button">Add Image</span>
				<?php else : ?>
					<em>Image missing</em>
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>
	<?php if (is_admin()) : ?>
		<script>
			blockSetup(document.querySelector("#<?= esc_attr($id); ?>"));
		</script>
	<?php endif; ?>
</figure>