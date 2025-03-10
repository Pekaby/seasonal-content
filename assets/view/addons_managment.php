<section class="secoel_content_header">
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
                <h2><?php echo esc_html__(ucwords($section), 'seasonal-content')?></h2>
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
            <td class="title"><span><?php echo esc_html__($addon['title'], 'seasonal-content')?></span></td>
            <td class="description"><span><?php echo esc_html__($addon['description'], 'seasonal-content')?></span></td>
            <td class="version"><span><?php echo esc_html__($addon['version'], 'seasonal-content')?></span></td>
            <td class="action"><span><?php echo ($section == 'aviable') ? "<a href=\"{$addon['url']}\" target=\"_blank\"><button class=\"filled\">" . __( 'Buy', 'seasonal-content' ) . " ↗</button></a>" : ''?></td>
        </tr>
    <?php } ?>
    </table>
    <?php
}