<section class="secoel_content_header">
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
                    <input type="text" class="category_name" placeholder="'.__('Title').'">
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
    echo $empty_category;
} else {
    foreach ($categories as $category) {
        echo '<tr class="category">';

        echo '<td>';
        echo "<input type=\"text\" class=\"category_name\" value=\"".esc_html($category->title)."\" data-id=\"".esc_html($category->id)."\" placeholder=\"".esc_html__('Title', 'seasonal-content')."\">";
        echo '</td>';

        echo '<td>';
        echo "<input type=\"date\" class=\"category_start_date\" value=\"".esc_html($category->date_start)."\" data-id=\"".esc_html($category->id)."\">";
        echo '</td>';

        echo '<td>';
        echo "<input type=\"date\" class=\"category_end_date\" value=\"".esc_html($category->date_end)."\" data-id=\"".esc_html($category->id)."\">";
        echo '</td>';

        echo '<td>';
        echo "<button class=\"category_delete\" data-id=\"".esc_html($category->id)."\">" . '
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
        '."</button>";
        echo '</td>';
    }
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