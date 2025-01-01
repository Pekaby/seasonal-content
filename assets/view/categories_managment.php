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
    .category {
        padding: 10px;
        margin: auto;
    }
    td {
        border-bottom: 1px solid #f2f2f2;
    }
    .categories {
        width: calc(100% + 20px);
        background-color: #fff;
        margin-top: 30px;
        margin-left: -20px;
        padding-left: 20px;
    }
    td input{
        width: 95%;
        padding: 5px !important;
        border: none !important; 
    }
    .category_delete {
        border: 1px solid var(--secoel-accent-color);
        background: #fff;
        cursor: pointer;
    }
    .category_delete svg {
        width: 18px;
        height: 18px;
    }
    .category_delete svg path {
        fill: var(--secoel-accent-color);
    }
    .save_categories {
        border: 1px solid var(--secoel-accent-color);
        background: var(--secoel-accent-color);
        color: #fff;
        padding: 5px 15px;
        cursor: pointer;
        font-size: 18px;
        margin-top: 20px;
    }
    .add_category {
        margin-left: 10px;
        border: 1px solid var(--secoel-accent-color);
        background: #fff;
        padding: 5px 10px;
        cursor: pointer;
        color: var(--secoel-accent-color);
        font-size: 18px;
    }
</style>
<!-- <div id="categories"> -->
<?php 

// foreach ($categories as $category) {
//     echo '<div class="category">';
//     echo '<label>';
//     echo '<span>Название категории</span>';
//     echo "<input type=\"text\" class=\"category_name\" value=\"{$category->title}\" data-id=\"{$category->id}\">";
//     echo '</label>';
//     echo '<label>';
//     echo '<span>C</span>';
//     echo "<input type=\"date\" class=\"category_start_date\" value=\"{$category->date_start}\" data-id=\"{$category->id}\">";
//     echo '</label>';
//     echo '<label>';
//     echo '<span>По</span>';
//     echo "<input type=\"date\" class=\"category_end_date\" value=\"{$category->date_end}\" data-id=\"{$category->id}\">";
//     echo '</label>';
//     echo "<button class=\"category_delete\" data-id=\"{$category->id}\">Удалить</button>";
//     echo '</div>';
// }

?>
<section class="secoel_content_header">
    <div class="t_line"></div>
    <div class="loader"></div>
    <header >
    <div class="title">
        <h1><?=esc_html__('Categories', 'seasonal-content')?></h1>
    </div>
    <div class="sub-title">
        <span><?=esc_html__('Add, change and delete categories', 'seasonal-content')?></span>
    </div>
    </header>
</section>
<section>
    <table class="categories">
        <thead>
            <tr>
                <th><?=esc_html__('Category', 'seasonal-content')?></th>
                <th><?=esc_html__('Start', 'seasonal-content')?></th>
                <th><?=esc_html__('End', 'seasonal-content')?></th>
                <th><?=esc_html__('Action', 'seasonal-content')?></th>
            </tr>
        </thead>
        <tbody class="categories_content">
<?php 
if(empty($categories)){
    $empty_category =  '<tr class="category">
                <td>
                    <input type="text" class="category_name" placeholder="%s">
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
    printf(
        __($empty_category, 'seasonal-content'),
        'Title'
    );
} else {
    foreach ($categories as $category) {
        echo '<tr class="category">';

        echo '<td>';
        printf( 
            __("<input type=\"text\" class=\"category_name\" value=\"{$category->title}\" data-id=\"{$category->id}\" placeholder=\"%s\">", 'seasonal-content'),
            'Title'
        );
        echo '</td>';

        echo '<td>';
        echo "<input type=\"date\" class=\"category_start_date\" value=\"{$category->date_start}\" data-id=\"{$category->id}\">";
        echo '</td>';

        echo '<td>';
        echo "<input type=\"date\" class=\"category_end_date\" value=\"{$category->date_end}\" data-id=\"{$category->id}\">";
        echo '</td>';

        echo '<td>';
        echo "<button class=\"category_delete\" data-id=\"{$category->id}\">" . '
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
        '."</button>";
        echo '</td>';
    }
}
?>
            <!-- <tr class="category">
                <td>
                    <input type="text" class="category_name" placeholder="Название">
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
            </tr> -->
        </tbody>
    </table>
</section>
<div class="system_message">

</div>
<div class="system_controls">
        <button class="save_categories"><?=esc_html__('Save', 'seasonal-content')?></button>
        <button class="add_category">
            +
        </button>
    </div>
<script>

    const start_loader = () => {
        const loader = document.querySelector('.loader');
        loader.style.display = 'block';
        loader.classList.add('loader_animate');
    }

    const stop_loader_success = () => {
        const loader = document.querySelector('.loader');
        const line = document.querySelector('.t_line');

        loader.style.display = 'none';
        loader.classList.remove('loader_animate');

        line.classList.add('t_success');

        setTimeout(() => {
            line.classList.remove('t_success');
        }, 2000);
    }

    const category_element = `
        <tr class="category">
            <td>
                <input type="text" class="category_name" placeholder="<?=esc_html__('Title', 'seasonal-content')?>">
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
        </tr>
    `;

    let year = new Date().getFullYear();

    document.addEventListener('DOMContentLoaded', (de) => {
        document.querySelectorAll('input[type=date]').forEach((el) => {
            el.setAttribute("min", year + "-01-01");
            el.setAttribute("max", year + "-12-31");
        });

        document.querySelector('.add_category').addEventListener('click', (e) => {
            document.querySelector('.categories_content').insertAdjacentHTML('beforeEnd', category_element);
            document.querySelectorAll('input[type=date]').forEach((el) => {
                el.setAttribute("min", year + "-01-01");
                el.setAttribute("max", year + "-12-31");
            });
        });
        
        //delete
        document.querySelector('.categories_content').addEventListener('click', (e) => {
            if(e.target.closest('.category_delete')) {
                if(!e.target.closest('.category_delete').dataset.id) {
                    e.target.closest('.category_delete').closest('.category').remove();
                    return;
                }
                start_loader();
                jQuery.ajax({
                    url:ajaxurl,
                    type: "post",
                    data: {
                        action: 'season_handler',
                        method: 'deleteCategory',
                        data: [e.target.closest('.category_delete').dataset.id]
                    },
                    success: (r) => {
                        e.target.closest('.category_delete').closest('.category').remove();
                        stop_loader_success();
                    }       
                });
            }
        });

        // save categories
        document.querySelector('.save_categories').addEventListener('click', (e) => {
            start_loader();

            let categories = {};
            document.querySelectorAll('.category').forEach((element, index) => {
                if(!element.querySelector('.category_name').value) return;

                categories[index] = {};

                if(element.querySelector('.category_name').dataset.id != null){
                    categories[index]['id'] = element.querySelector('.category_name').dataset.id;
                }

                categories[index]['title'] = element.querySelector('.category_name').value;
                categories[index]['date_start'] = element.querySelector('.category_start_date').value;
                categories[index]['date_end'] = element.querySelector('.category_end_date').value;

            });

            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'season_handler',
                    method: 'saveCategories',
                    data: categories
                },
                success: (r) => {
                    document.querySelectorAll('.category').forEach((element, index) => {
                        category = r['data'].find((obj) => obj.title == element.querySelector('.category_name').value)
                        if(category.title == element.querySelector('.category_name').value) {
                            element.querySelector('.category_name').dataset.id = category.id;
                            element.querySelector('.category_start_date').dataset.id = category.id;
                            element.querySelector('.category_end_date').dataset.id = category.id;
                            element.querySelector('.category_delete').dataset.id = category.id;
                        }
                    })
                    stop_loader_success();
                }
            });
        });
        
    })
</script>