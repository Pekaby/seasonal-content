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
        <h1><?php echo esc_html__('Categories', 'seasonal-content')?></h1>
    </div>
    <div class="sub-title">
        <span><?php echo esc_html__('Add, change and delete categories', 'seasonal-content')?></span>
    </div>
    </header>
</section>
<section>
    <table class="categories">
        <thead>
            <tr>
                <th><?php echo esc_html__('Category', 'seasonal-content')?></th>
                <th><?php echo esc_html__('Start', 'seasonal-content')?></th>
                <th><?php echo esc_html__('End', 'seasonal-content')?></th>
                <th><?php echo esc_html__('Action', 'seasonal-content')?></th>
            </tr>
        </thead>
        <tbody class="categories_content">
<?php 
if(empty($categories)){
    $empty_category =  '<tr class="category">
                <td>
                    <input type="text" class="category_name" placeholder="'.__('Title', 'seasonal-content').'">
                </td>
                <td>
                    <input type="date" class="category_start_date">
                </td>
                <td>
                    <input type="date" class="category_end_date">
                </td>
                <td>
                    <button class="category_delete">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                    </button>
                </td>
            </tr>';
    echo wp_kses($empty_category, [
        'tr' =>[
            "class"           => true,
        ],
        'td' => [],
        'input' => [
            "id"              => true,
            "type"            => true, 
            "class"           => true,
            "placeholder"     => true,
        ],
        'button' => [
            "class"           => true,
        ],
        'svg'   => [
            'class'           => true,
            'aria-hidden'     => true,
            'aria-labelledby' => true,
            'role'            => true,
            'xmlns'           => true,
            'width'           => true,
            'height'          => true,
            'viewbox'         => true, 
        ],
        'g'     => [ 'fill'   => true ],
        'title' => [ 'title'  => true ],
        'path'  => [
            'd'               => true, 
            'fill'            => true,  
        ]
    ]);
} else {
    $categories_echo = '';
    foreach ($categories as $category) {
        $categories_echo .= '<tr class="category">';

        $categories_echo .= '<td>';
        $categories_echo .= "<input type=\"text\" class=\"category_name\" value=\"".esc_html($category->title)."\" data-id=\"".esc_html($category->id)."\" placeholder=\"".esc_html__('Title', 'seasonal-content')."\">";
        $categories_echo .= '</td>';

        $categories_echo .= '<td>';
        $categories_echo .= "<input type=\"date\" class=\"category_start_date\" value=\"".esc_html($category->date_start)."\" data-id=\"".esc_html($category->id)."\">";
        $categories_echo .= '</td>';

        $categories_echo .= '<td>';
        $categories_echo .= "<input type=\"date\" class=\"category_end_date\" value=\"".esc_html($category->date_end)."\" data-id=\"".esc_html($category->id)."\">";
        $categories_echo .= '</td>';

        $categories_echo .= '<td>';
        $categories_echo .= "<button class=\"category_delete\" data-id=\"".esc_html($category->id)."\">" . '
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
        '."</button>";
        $categories_echo .= '</td>';
    }
    echo wp_kses($categories_echo, [
        'tr' =>[
            "class"           => true,
        ],
        'td' => [],
        'input' => [
            "id"              => true,
            "type"            => true, 
            "class"           => true,
            "placeholder"     => true,
            "value"           => true,
            "data-id"         => true,
        ],
        'button' => [
            "class"           => true,
            "data-id"         => true,
        ],
        'svg'   => [
            'class'           => true,
            'aria-hidden'     => true,
            'aria-labelledby' => true,
            'role'            => true,
            'xmlns'           => true,
            'width'           => true,
            'height'          => true,
            'viewbox'         => true, 
        ],
        'g'     => [ 'fill'   => true ],
        'title' => [ 'title'  => true ],
        'path'  => [
            'd'               => true, 
            'fill'            => true,  
        ]
    ]);
}
?>
        </tbody>
    </table>
</section>
<div class="system_message">

</div>
<div class="system_controls">
    <button class="save_categories"><?php echo esc_html__('Save', 'seasonal-content')?></button>
    <button class="add_category">
        +
    </button>
</div>