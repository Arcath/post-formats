jQuery(document).ready(function(){
  hide_format_boxes()

  if(jQuery("#post-formats-select").length) {
    selectedPostFormat = jQuery("input[name='post_format']:checked").val()

    post_formats = ['gallery', 'image', 'link', 'quote', 'audio', 'video', 'chat']

    if(jQuery.inArray(selectedPostFormat, post_formats) != '-1') {
			jQuery('#post_formats_' + selectedPostFormat).show();
		}

    jQuery("input[name='post_format']:radio").change(function() {
			hide_format_boxes();
			if(jQuery.inArray(jQuery(this).val(),post_formats) != '-1') {
				jQuery('#post_formats_' + jQuery(this).val()).show()
			}
		})
  }

  gallery_box()
  image_box()
  audio_box()
  video_box()
  chat_box()
})

function hide_format_boxes(){
  jQuery('#post_formats_gallery, #post_formats_image, #post_formats_link, #post_formats_quote, #post_formats_audio, #post_formats_video, #post_formats_chat').hide()
}

function gallery_box(){
  jQuery('.gallery_remove').on('click', function(){
    event.preventDefault()

    jQuery(jQuery(this).parent('li')).html('')
  })

  jQuery('#post_format_gallery_add').on('click', function(event){
    event.preventDefault()

    if(wp.media.frames.galleryBox){
      wp.media.frames.galleryBox.open()
      return;
    }

    wp.media.frames.galleryBox = wp.media({
      title: 'Gallery',
      button: { text: 'Select Image(s)' },
      library: { type: 'image' },
      multiple: true
    })

    wp.media.frames.galleryBox.on('select', function(){
      media_attachment = wp.media.frames.galleryBox.state().get('selection').first().toJSON()
      jQuery('#post_format_gallery_list').append('<li>\
      <img src="' + media_attachment.sizes.thumbnail.url + '" /><br />\
      <input type="hidden" name="post_format_gallery[]" value="' + media_attachment.id + '" />\
      <a href="#" class="gallery_remove">Remove</a>\
      </li>')

      jQuery('.gallery_remove').on('click', function(){
        event.preventDefault()

        jQuery(jQuery(this).parent('li')).html('')
      })
    })

    wp.media.frames.galleryBox.open()
  })
}

function image_box(){
  jQuery('#post_format_image_select').on('click', function(event){
    event.preventDefault()

    if(wp.media.frames.imageBox){
      wp.media.frames.imageBox.open()
      return;
    }

    wp.media.frames.imageBox = wp.media({
      title: 'Image',
      button: { text: 'Select Image' },
      library: { type: 'image' },
      multiple: false
    })

    wp.media.frames.imageBox.on('select', function(){
      media_attachment = wp.media.frames.imageBox.state().get('selection').first().toJSON()
      jQuery('#post_format_image_thumb').attr('src', media_attachment.sizes.thumbnail.url)
      jQuery('#post_format_image').val(media_attachment.id)
    })

    wp.media.frames.imageBox.open()
  })
}

function audio_box(){
  jQuery('#post_format_audio_select').on('click', function(event){
    event.preventDefault()

    if(wp.media.frames.audioBox){
      wp.media.frames.audioBox.open()
      return;
    }

    wp.media.frames.audioBox = wp.media({
      title: 'Audio',
      button: { text: 'Select Audio' },
      library: { type: 'audio' },
      multiple: false
    })

    wp.media.frames.audioBox.on('select', function(){
      media_attachment = wp.media.frames.audioBox.state().get('selection').first().toJSON()
      jQuery('#post_format_audio').val(media_attachment.id)
      jQuery('#post_formats_audio_preview').attr('src', media_attachment.url)
    })

    wp.media.frames.audioBox.open()
  })
}

function video_box(){
  jQuery('#post_format_video_select').on('click', function(event){
    event.preventDefault()

    if(wp.media.frames.videoBox){
      wp.media.frames.videoBox.open()
      return;
    }

    wp.media.frames.videoBox = wp.media({
      title: 'Video',
      button: { text: 'Select Video' },
      library: { type: 'Video' },
      multiple: false
    })

    wp.media.frames.videoBox.on('select', function(){
      media_attachment = wp.media.frames.videoBox.state().get('selection').first().toJSON()
      jQuery('#post_format_video').val(media_attachment.id)
      jQuery('#post_formats_video_preview').attr('src', media_attachment.url)
    })

    wp.media.frames.videoBox.open()
  })
}

function chat_box(){
  jQuery('#post_format_chat_add').on('click', function(event){
    event.preventDefault()

    jQuery('#post_format_chat_list').append('<li>\
      <input type="text" name="post_format_chat[' + window.postFormatsNextChat + '][author]" value="" placeholder="Author" /><br />\
      Message: <br />\
      <textarea name="post_format_chat[' + window.postFormatsNextChat + '][body]"></textarea>\
    </li>')
    window.postFormatsNextChat += 1
  })
}
