import 'dropzone/dist/dropzone.css';

const Dropzone = require('dropzone/dist/dropzone');
// require('dropzone/dist/dropzone-amd-module');

const token: HTMLMetaElement | null = document.head.querySelector('meta[name="csrf-token"]');

if (document.getElementsByClassName('dropzone').length > 0) {
    Dropzone.autoDiscover = false;

    const myDropzone = new Dropzone('.dropzone', {
        // Setup chunking
        chunking: true,
        method: 'POST',
        maxFilesize: 400000000,
        chunkSize: 1000000,
        // If true, the individual chunks of a file are being uploaded simultaneously.
        // parallelChunkUploads: true,
        acceptedFiles: 'image/*',
        retryChunks: true,
        maxThumbnailFilesize: 25,
        // paramName: 'picture'
        headers: {
            'X-CSRF-Token': token && token.content,
        },
    });
}
