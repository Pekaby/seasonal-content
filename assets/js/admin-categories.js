
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
            <input type="text" class="category_name" placeholder="${seasonalcontent_security.translation.title}">
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
                    nonce: seasonalcontent_security.nonce,
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
                categories[index]['id'] = parseInt(element.querySelector('.category_name').dataset.id);
                console.log(typeof categories[index]['id']);
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
                nonce: seasonalcontent_security.nonce,
                data: categories
            },
            success: (r) => {
                document.querySelectorAll('.category').forEach((element, index) => {
                    category = r['data'].find((obj) => obj.title == element.querySelector('.category_name').value)
                    if(!category) {
                        return;
                    }
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
    
});