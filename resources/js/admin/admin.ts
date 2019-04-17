import './menu';
import Vue from 'vue';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// function deleteAlbumPicture(el: HTMLOrSVGElement & Node): void {
//     Vue.axios
//         .delete(`/api/admin/album-pictures/${el.dataset.albumSlug}`, {
//             data: {
//                 media_id: el.dataset.pictureId,
//             },
//         })
//         .then(() => {
//             el.parentNode && el.parentNode.removeChild(el);
//         })
//         .catch(err => {
//             alert('Something want wrong.');
//             throw err;
//         });
// }
//
// const deleteAlbumPictureBtn = document.getElementsByClassName('delete-album-picture');
// Array.from(deleteAlbumPictureBtn).forEach((el: Element) => {
//     let parentNode = el.parentNode;
//     if (!parentNode) {
//         throw new DOMException('element does not have parent node');
//     }
//     el.addEventListener('click', () => {
//         deleteAlbumPicture(<HTMLOrSVGElement & Node & ParentNode>parentNode);
//     });
// });
