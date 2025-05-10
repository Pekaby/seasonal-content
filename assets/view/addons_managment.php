<?php 
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<section class="seasonalcontent_content_header">
    <div class="t_line"></div>
    <div class="loader"></div>
    <header >
    <div class="title">
        <h1><?php echo esc_html__('Addons', 'seasonal-content')?></h1>
    </div>
    <div class="sub-title">
        <span><?php echo esc_html__('Improve plugin functionality with this amazing addons!', 'seasonal-content')?></span>
    </div>
    </header>
</section>
<?php 

$sections = array_keys($data);

foreach($sections as $section) { ?>
    <section class="section_header">
        <div class="section_header_wrapper">
            <div class="title">
                <h2><?php echo esc_html(ucwords($section))?></h2>
            </div>
        </div>
    </section>
    <table class="addons">
        <thead>
            <tr>
                <th><?php echo esc_html__('Title', 'seasonal-content')?></th>
                <th><?php echo esc_html__('Description', 'seasonal-content')?></th>
                <th><?php echo esc_html__('Version', 'seasonal-content')?></th>
                <th><?php echo esc_html__('Action', 'seasonal-content')?></th>
            </tr>
        </thead>
        <tbody class="categories_content"></tbody>
    <?php
    foreach ($data[$section] as $slug => $addon) { ?>
        <tr class="addon">
            <td class="title"><span><?php echo esc_html($addon['title'])?></span></td>
            <td class="description"><span><?php echo esc_html($addon['description'])?></span></td>
            <td class="version"><span><?php echo esc_html($addon['version'])?></span></td>
            <td class="action"><span><?php echo esc_html__('In Development', 'seasonal-content')?></span></td>
        </tr>
    <?php } ?>
    </table>
    <?php
}