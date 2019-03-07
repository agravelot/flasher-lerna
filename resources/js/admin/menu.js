document.addEventListener('DOMContentLoaded', () => {
  const $adminMenu = document.getElementById('admin-menu');
  const $collapseAdminMenuButton = document.getElementById('collapse-admin-menu');
  const $icon = $collapseAdminMenuButton
    .getElementsByTagName('span')
    .item(0)
    .getElementsByTagName('i')
    .item(0);

  $collapseAdminMenuButton.addEventListener('click', () => {
    if ($adminMenu.style.display === 'none') {
      $adminMenu.style.display = 'block';
      $icon.classList.remove('fa-flip-horizontal');
    } else {
      $adminMenu.style.display = 'none';
      $icon.classList.add('fa-flip-horizontal');
    }
  });
});
