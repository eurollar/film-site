<?php get_header(); ?>
<div class="single-film-info">
    <?php
    the_content();
    ?>
    <p><?php echo get_post_meta( get_the_ID(), 'cost', true ); ?>$</p>
    <p><?php echo get_post_meta( get_the_ID(), 'release', true ); ?></p>
    <p><?php echo get_the_term_list( get_the_ID(), 'genre', 'Genre: ', ', ' ) ?></p>
    <p><?php echo get_the_term_list( get_the_ID(), 'country', 'Country: ', ', ' ) ?></p>
    <p><?php echo get_the_term_list( get_the_ID(), 'actor', 'Actors: ', ', ' ) ?></p>
</div>
<?php get_footer(); ?>
