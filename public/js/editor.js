var textarea = document.querySelector('#editor');

if (window.tinyMCE) {
  tinyMCE.init({
    selector: '#editor',
    plugins: 'image,paste',
    paste_data_images: true,
    automatic_uploads: true,
    images_upload_handler: function(blobinfo, success, failure) {
      var data = new FormData();
      data.append('attachable_id', textarea.dataset.id);
      data.append('attachable_type', textarea.dataset.type);
      data.append('image', blobinfo.blob(), blobinfo.filename());

      axios.post(textarea.dataset.url, data)
        .then(function(res) {
          success(res.data.url);
        })
        .catch(function(err) {
          failure(err.response.statusText);
        });
    }
  });
}
