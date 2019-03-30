document.addEventListener('DOMContentLoaded', () => {
    const adminMenu: HTMLElement | null = document.getElementById('admin-menu');
    const collapseAdminMenuButton: HTMLElement | null = document.getElementById('collapse-admin-menu');
    if (collapseAdminMenuButton && adminMenu) {
        // @ts-ignore
        const icon = collapseAdminMenuButton
            .getElementsByTagName('span')
            .item(0)
            .getElementsByTagName('i')
            .item(0);

        if (icon) {
            collapseAdminMenuButton.addEventListener('click', () => {

                if (adminMenu.style.display === 'none') {
                    adminMenu.style.display = 'block';
                    icon.classList.remove('fa-flip-horizontal');
                } else {
                    adminMenu.style.display = 'none';
                    icon.classList.add('fa-flip-horizontal');
                }
            });
        }
    }
});
