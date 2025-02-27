<style>
    @keyframes loader {
        0% {
            left: -20px;
        }
        100% {
            left: 100%;
        }
    }
    :root {
        --secoel-accent-color: #165DFF;
        --secoel-success-color: green;
    }
    .t_line {
        height: 5px;
        width: calc(100% + 20px);
        position: absolute;
        top: 0px;
        left: -20px;
        background: var(--secoel-accent-color);
        transition: .3s background;
        transition: .3s box-shadow;
    }
    .t_success {
        background: var(--secoel-success-color) !important;
        box-shadow: 1px 0px 5px 4px var(--secoel-success-color);
    }
    .loader {
        display: none;
        width: 50px;
        height: 5px;
        box-shadow: 1px 0px 5px 4px var(--secoel-accent-color);
        position: absolute;
        top: 0px;
        left: -20px;
        background: var(--secoel-accent-color);
    }
    .loader_animate {
        animation: loader 2s linear infinite;
    }
    .secoel_content_header {
        margin-left: -20px;
        margin-top: -15px;
        padding: 20px 20px;
        background: #fff;
    }
    .title h1 {
        font-size: 32px;
    }
    .sub-title {
        font-size: 15px;
    }
    .section_header {
        margin-top: 30px;
        background: #fff;
        margin-left: -20px;
        padding-left: 20px;
    }
    .section_header h2 {
        padding-top: 20px;
        font-size: 24px;
        margin: 0;
    }
    .addon {
        padding: 10px;
        margin: auto;
    }
    td {
        border-bottom: 1px solid #f2f2f2;
    }
    td.title {
        width: 20px;
    }
    td.description {
        width: 50%;
    }
    td.version {
        width: 10%;
        text-align: center;
    }
    td.action {
        width: 20%;
        text-align: center;
    }
    td  {
        padding: 15px;
    }
    .addons {
        width: calc(100% + 20px);
        background-color: #fff;
        /* margin-top: 30px; */
        padding-top: 40px;
        margin-left: -20px;
        padding-left: 20px;
    }
    td input{
        width: 95%;
        padding: 5px !important;
        border: none !important; 
    }
    
    button.filled {
        padding: 5px 30px;
        background: var(--secoel-accent-color);
        border: 1px solid var(--secoel-accent-color);
        color: #fff;
        cursor: pointer;
    }
    button.outline {
        padding: 5px 30px;
        border: 1px solid var(--secoel-accent-color);
        color: var(--secoel-accent-color);
        background: #fff;
        cursor: pointer;
    }
    
</style>
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