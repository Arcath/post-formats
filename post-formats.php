<?php
class PostFormats{
  public function __construct($formats, $screens){
    $this->screens = $screens;

    add_theme_support('post-formats', $formats);

    foreach($formats as $format){
      if(method_exists($this, 'register_' . $format)){
        add_action('add_meta_boxes', array($this, 'register_' . $format));
        add_action('save_post', array($this, $format . '_meta_box_save'));
      }
    }

    add_action('init', array($this, 'init'), 11);

    add_action('admin_enqueue_scripts', array($this, 'enqueue'));
  }

  public function init(){
    foreach($this->screens as $screen){
      add_post_type_support($screen, 'post-formats');
      register_taxonomy_for_object_type('post_format', $screen);
    }
  }

  public function get_base_path(){
    $base = get_template_directory_uri();

    $parts = explode(get_template(), dirname(__FILE__));

    $url = str_replace('\\', '/', $base . array_pop($parts) );

    return $url;
  }

  public function enqueue(){
    global $typenow;
    if(in_array($typenow, $this->screens)){
      wp_enqueue_style('post_formats_css', $this->get_base_path() . '/post-formats.css');
      wp_enqueue_script('post_formats_js', $this->get_base_path() . '/post-formats.js', array('jquery'));
    }
  }

  public function register_gallery(){
    foreach($this->screens as $screen){
      add_meta_box(
        'post_formats_gallery',
        __('Gallery', 'post-formats'),
        array($this, 'gallery_meta_box'),
        $screen,
        'normal',
        'default'
      );
    }
  }

  public function register_image(){
    foreach($this->screens as $screen){
      add_meta_box(
        'post_formats_image',
        __('Image', 'post-formats'),
        array($this, 'image_meta_box'),
        $screen,
        'normal',
        'default'
      );
    }
  }

  public function register_link(){
    foreach($this->screens as $screen){
      add_meta_box(
        'post_formats_link',
        __('Link', 'post-formats'),
        array($this, 'link_meta_box'),
        $screen,
        'normal',
        'default'
      );
    }
  }

  public function register_quote(){
    foreach($this->screens as $screen){
      add_meta_box(
        'post_formats_quote',
        __('Quote', 'post-formats'),
        array($this, 'quote_meta_box'),
        $screen,
        'normal',
        'default'
      );
    }
  }

  public function register_audio(){
    foreach($this->screens as $screen){
      add_meta_box(
        'post_formats_audio',
        __('Audio', 'post-formats'),
        array($this, 'audio_meta_box'),
        $screen,
        'normal',
        'default'
      );
    }
  }

  public function register_video(){
    foreach($this->screens as $screen){
      add_meta_box(
        'post_formats_video',
        __('Video', 'post-formats'),
        array($this, 'video_meta_box'),
        $screen,
        'normal',
        'default'
      );
    }
  }

  public function register_chat(){
    foreach($this->screens as $screen){
      add_meta_box(
        'post_formats_chat',
        __('Chat', 'post-formats'),
        array($this, 'chat_meta_box'),
        $screen,
        'normal',
        'default'
      );
    }
  }

  public function gallery_meta_box($post){
    wp_nonce_field('post_format_gallery_nonce', 'post_format_gallery_nonce');
    $gallery = get_post_meta($post->ID, '_post_format_gallery', true);

    if(!$gallery){
      $gallery = array();
    }

    ?>
    <p>
      <?php _e('Select Images to add to your gallery here.', 'post-formats'); ?>
      <input type="button" value="Add Images" id="post_format_gallery_add" />
    </p>
    <ul id="post_format_gallery_list">
      <?php foreach($gallery as $image): ?>
        <li>
          <img src="<?php echo(wp_get_attachment_image_src($image, 'thumbnail')[0]); ?>" /><br />
          <input type="hidden" name="post_format_gallery[]" value="<?php echo($image); ?>" />
          <a href="#" class="gallery_remove"><?php _e('Remove', 'post-formats'); ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php
  }

  public function gallery_meta_box_save($post_id){
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'post_format_gallery_nonce' ]) && wp_verify_nonce($_POST['post_format_gallery_nonce'], 'post_format_gallery_nonce')) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
    }

    if(isset($_POST['post_format_gallery'])){
      update_post_meta($post_id, '_post_format_gallery', $_POST['post_format_gallery']);
    }
  }

  public function image_meta_box($post){
    wp_nonce_field('post_format_image_nonce', 'post_format_image_nonce');
    $image = get_post_meta($post->ID, '_post_format_image', true);
    ?>
      <p style="text-align:center;">
        <img src="<?php echo(wp_get_attachment_image_src($image, 'thumbnail')[0]); ?>" id="post_format_image_thumb" />
      </p>
      <input type="hidden" id="post_format_image" name="post_format_image" value="<?php echo($image); ?>" />
      <input type="button" id="post_format_image_select" value="<?php _e('Select Image', 'post-formats'); ?>" />
    <?php
  }

  public function image_meta_box_save($post_id){
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'post_format_image_nonce' ]) && wp_verify_nonce($_POST['post_format_image_nonce'], 'post_format_image_nonce')) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
    }

    if(isset($_POST['post_format_image'])){
      update_post_meta($post_id, '_post_format_image', $_POST['post_format_image']);
    }
  }

  public function link_meta_box($post){
    wp_nonce_field('post_format_link_nonce', 'post_format_link_nonce');
    $linkURL = get_post_meta($post->ID, '_post_format_link_url', true);
    $linkText = get_post_meta($post->ID, '_post_format_link_text', true);
    ?>
      <p>
        <label>
          <?php _e('Link Text', 'post_formats'); ?>
          <input type="text" value="<?php echo($linkText); ?>" name="post_format_link_text" />
        </label>
      </p>
      <p>
        <label>
          <?php _e('Link URL', 'post_formats'); ?>
          <input type="text" value="<?php echo($linkURL); ?>" name="post_format_link_url" />
        </label>
      </p>
    <?php
  }

  public function link_meta_box_save($post_id){
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'post_format_link_nonce' ]) && wp_verify_nonce($_POST['post_format_link_nonce'], 'post_format_link_nonce')) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
    }

    if(isset($_POST['post_format_link_url'])){
      update_post_meta($post_id, '_post_format_link_url', $_POST['post_format_link_url']);
    }

    if(isset($_POST['post_format_link_text'])){
      update_post_meta($post_id, '_post_format_link_text', $_POST['post_format_link_text']);
    }
  }

  public function quote_meta_box($post){
    wp_nonce_field('post_format_quote_nonce', 'post_format_quote_nonce');
    $quoteAuthor = get_post_meta($post->ID, '_post_format_quote_author', true);
    $quoteBody = get_post_meta($post->ID, '_post_format_quote_body', true);
    ?>
      <p>
        <label>
          <?php _e('Quote Author', 'post_formats'); ?><br />
          <input type="text" value="<?php echo($quoteAuthor); ?>" name="post_format_quote_author" />
        </label>
      </p>
      <p>
        <label>
          <?php _e('Quote Body', 'post_formats'); ?><br />
          <textarea name="post_format_quote_body"><?php echo($quoteBody); ?></textarea>
        </label>
      </p>
    <?php
  }

  public function quote_meta_box_save($post_id){
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'post_format_quote_nonce' ]) && wp_verify_nonce($_POST['post_format_quote_nonce'], 'post_format_quote_nonce')) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
    }

    if(isset($_POST['post_format_quote_author'])){
      update_post_meta($post_id, '_post_format_quote_author', $_POST['post_format_quote_author']);
    }

    if(isset($_POST['post_format_quote_body'])){
      update_post_meta($post_id, '_post_format_quote_body', $_POST['post_format_quote_body']);
    }
  }

  public function audio_meta_box($post){
    wp_nonce_field('post_format_audio_nonce', 'post_format_audio_nonce');
    $audio = get_post_meta($post->ID, '_post_format_audio', true);
    ?>
      <p style="text-align:center;">
        <audio src="<?php echo(wp_get_attachment_url($audio)); ?>" id="post_formats_audio_preview" controls="controls"></audio>
      </p>
      <input type="hidden" id="post_format_audio" name="post_format_audio" value="<?php echo($audio); ?>" />
      <input type="button" id="post_format_audio_select" value="<?php _e('Select Audio', 'post_formats'); ?>" />
    <?php
  }

  public function audio_meta_box_save($post_id){
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'post_format_audio_nonce' ]) && wp_verify_nonce($_POST['post_format_audio_nonce'], 'post_format_audio_nonce')) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
    }

    if(isset($_POST['post_format_audio'])){
      update_post_meta($post_id, '_post_format_audio', $_POST['post_format_audio']);
    }
  }

  public function video_meta_box($post){
    wp_nonce_field('post_format_video_nonce', 'post_format_video_nonce');
    $video = get_post_meta($post->ID, '_post_format_video', true);
    ?>
      <p style="text-align:center;">
        <video src="<?php echo(wp_get_attachment_url($video)); ?>" id="post_formats_video_preview" controls="controls"></video>
      </p>
      <input type="hidden" id="post_format_video" name="post_format_video" value="<?php echo($video); ?>" />
      <input type="button" id="post_format_video_select" value="<?php _e('Select Video', 'post_formats'); ?>" />
    <?php
  }

  public function video_meta_box_save($post_id){
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'post_format_video_nonce' ]) && wp_verify_nonce($_POST['post_format_video_nonce'], 'post_format_video_nonce')) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
    }

    if(isset($_POST['post_format_video'])){
      update_post_meta($post_id, '_post_format_video', $_POST['post_format_video']);
    }
  }

  public function chat_meta_box($post){
    wp_nonce_field('post_format_chat_nonce', 'post_format_chat_nonce');
    $chat = get_post_meta($post->ID, '_post_format_chat', true);

    if(!$chat){
      $chat = array();
    }

    $i = 0;
    ?>
      <ul id="post_format_chat_list">
        <?php foreach($chat as $line): ?>
          <li>
            <input type="text" name="post_format_chat[<?php echo($i); ?>][author]" value="<?php echo($line['author']); ?>" placeholder="Author" /><br />
            Message: <br />
            <textarea name="post_format_chat[<?php echo($i); ?>][body]"><?php echo($line['body']); ?></textarea>
          </li>
        <?php $i++; endforeach; ?>
      </ul>
      <hr>
      <p>
        <script type="text/javascript">
          window.postFormatsNextChat = <?php echo($i); ?>;
        </script>
        <input type="button" value="<?php _e('Add Chat Line', 'post_formats'); ?>" id="post_format_chat_add" />
      </p>
    <?php
  }

  public function chat_meta_box_save($post_id){
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'post_format_chat_nonce' ]) && wp_verify_nonce($_POST['post_format_chat_nonce'], 'post_format_chat_nonce')) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
    }

    if(isset($_POST['post_format_chat'])){
      print_r($_POST['post_format_chat']);
      update_post_meta($post_id, '_post_format_chat', $_POST['post_format_chat']);
    }
  }
}
?>
