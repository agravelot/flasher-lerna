// Core
require('tinymce');

// Themes
require('tinymce/themes/modern/theme');
require('tinymce/skins/lightgray/skin.min.css');
require('tinymce/skins/lightgray/content.min.css');

// Localization
require('tinymce-i18n/langs/fr_FR');

// Plugins
require('tinymce/plugins/advlist');
require('tinymce/plugins/autolink');
require('tinymce/plugins/lists');
require('tinymce/plugins/link');
require('tinymce/plugins/image');
// require('tinymce/plugins/charmap');
// require('tinymce/plugins/print');
require('tinymce/plugins/preview');
require('tinymce/plugins/hr');
require('tinymce/plugins/anchor');
// require('tinymce/plugins/pagebreak');
require('tinymce/plugins/searchreplace');
require('tinymce/plugins/wordcount');
require('tinymce/plugins/visualblocks');
require('tinymce/plugins/visualchars');
// require('tinymce/plugins/code');
require('tinymce/plugins/fullscreen');
require('tinymce/plugins/insertdatetime');
require('tinymce/plugins/media');
require('tinymce/plugins/nonbreaking');
require('tinymce/plugins/save');
// require('tinymce/plugins/table');
require('tinymce/plugins/contextmenu');
require('tinymce/plugins/directionality');
// require('tinymce/plugins/emoticons/index');
// require('tinymce/plugins/emoticons/plugin');
require('tinymce/plugins/template');
require('tinymce/plugins/paste');
require('tinymce/plugins/colorpicker');
require('tinymce/plugins/textpattern');
require('tinymce/plugins/textcolor');

const editorConfig = {
  path_absolute: '/',
  selector: 'textarea.tinymce',
  language: 'fr_FR',
  themes: 'modern',
  skin: false,
  browser_spellcheck: true,
  contextmenu: false,
  branding: false,
  plugins: [
    'advlist autolink lists link image preview hr anchor',
    'searchreplace wordcount visualblocks visualchars fullscreen',
    'insertdatetime media nonbreaking save contextmenu directionality',
    'template paste textcolor colorpicker textpattern',
  ],
  toolbar:
        'insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media',
  relative_urls: false,
  file_browser_callback(fieldName, url, type, win) {
    const x = window.innerWidth
            || document.documentElement.clientWidth
            || document.getElementsByTagName('body')[0].clientWidth;
    const y = window.innerHeight
            || document.documentElement.clientHeight
            || document.getElementsByTagName('body')[0].clientHeight;

    let cmsURL = `${editorConfig.path_absolute}laravel-filemanager?field_name=${fieldName}`;
    if (type === 'image') {
      cmsURL += '&type=Images';
    } else {
      cmsURL += '&type=Files';
    }

    tinyMCE.activeEditor.windowManager.open({
      file: cmsURL,
      title: 'Filemanager',
      width: x * 0.8,
      height: y * 0.8,
      resizable: 'yes',
      close_previous: 'no',
    });
  },
};

tinymce.init(editorConfig);
