if (document.getElementsByClassName('dropzone').length > 0) {
    require('dropzone/dist/dropzone.css');
    // require('dropzone/dist/dropzone-amd-module');
    let Dropzone = require('dropzone/dist/dropzone');

    Dropzone.autoDiscover = false;

    // var token = $('input[name=_token]').val();

    let myDropzone = new Dropzone('.dropzone', {
        // Setup chunking
        chunking: true,
        method: 'POST',
        maxFilesize: 400000000,
        chunkSize: 1000000,
        // If true, the individual chunks of a file are being uploaded simultaneously.
        // parallelChunkUploads: true,
        acceptedFiles: 'image/*',
        retryChunks: true,
        // paramName: 'picture'
    });
}
