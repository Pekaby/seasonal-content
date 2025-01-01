document.addEventListener("DOMContentLoaded", () => {

    //backup
    elementor.channels.editor.on( 'secoel_restore_main_backup', () => {
        const loader = document.querySelector('#elementor-loading');
        console.log(loader);
        loader.style.display = 'block';

        urlParams = new URLSearchParams(window.location.search);

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data:{
                action: "season_handler",
                method: "restoreMainBackup",
                data: [urlParams.get('post')]
            },
            success: (r) => {
                console.log(r);
                location.reload();
            }
            
        })

    });

    // update content by category
    elementor.channels.editor.on( 'secoel_update_content', () => {
        const loader = document.querySelector('#elementor-loading');
        console.log(loader);
        loader.style.display = 'block';

        urlParams = new URLSearchParams(window.location.search);

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data:{
                action: "season_handler",
                method: "updateSeasonContent",
                data: [urlParams.get('post')]
            },
            success: (r) => {
                console.log(r);
                location.reload();
            }
            
        })
    });

    elementor.channels.editor.on( 'secoel_update_current_category', () => {
        alert("Update Current Category");
    });

    // update main backup
    elementor.channels.editor.on( 'secoel_set_as_main_backup', () => {
        const loader = document.querySelector('#elementor-loading');
        console.log(loader);
        loader.style.display = 'block';

        urlParams = new URLSearchParams(window.location.search);

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data:{
                action: "season_handler",
                method: "updateMainBackup",
                data: [urlParams.get('post')]
            },
            success: (r) => {
                console.log(r);
                location.reload();
            }
            
        })
    });
});