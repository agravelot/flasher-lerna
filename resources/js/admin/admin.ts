import './menu';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

function deleteAlbumPicture(element : HTMLMediaElement) {
  return axios.delete(`/api/admin/album-pictures/${element.dataset.albumSlug}`, {
    data: {
      media_id: element.dataset.pictureId,
    },
  })
    .then(() => {
      element.parentNode.removeChild(element);
    })
    .catch((err) => {
      alert('Something want wrong.');
      throw err;
    });
}

const deleteAlbumPictureBtn = document.getElementsByClassName('delete-album-picture');
Array.from(deleteAlbumPictureBtn).forEach((el) => {
  el.addEventListener('click', () => {
    deleteAlbumPicture(el.parentNode);
  });
});
