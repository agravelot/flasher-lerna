import 'dropzone/dist/dropzone.css';
// require('dropzone/dist/dropzone-amd-module');
import Dropzone, { autoDiscover } from 'dropzone/dist/dropzone';

if (document.getElementsByClassName('dropzone').length > 0) {
    autoDiscover = false;

    // var token = $('input[name=_token]').val();

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
        // paramName: 'picture'
    });
}
