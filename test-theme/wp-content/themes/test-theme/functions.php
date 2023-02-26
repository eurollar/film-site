<?php
/**
 * Register css styles and JS scripts
 *
 * @return void
 */
function load_style_script() {
    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'load_style_script' );

/**
 * Register taxonomies for custom post type (genre, country, actor)
 *
 * @return void
 */
function genre_taxonomy() {
    register_taxonomy( 'genre', 'film_post_type', array(
        'label'             => 'Genre',
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => true,
        'show_admin_column' => true
    ) );

}
add_action( 'init', 'genre_taxonomy' );

function country_taxonomy() {
    register_taxonomy( 'country', 'film_post_type', array(
        'label'             => 'Country',
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => true,
        'show_admin_column' => true
    ) );
}
add_action( 'init', 'country_taxonomy' );

function actor_taxonomy() {
    register_taxonomy( 'actor', 'film_post_type', array(
        'label'             => 'Actor',
        'public'            => true,
        'show_in_rest'      => true,
        'hierarchical'      => true,
        'show_admin_column' => true
    ) );
}
add_action( 'init', 'actor_taxonomy' );

/**
 * Register custom post type (film)
 *
 * @return void
 */
function register_film_post_type() {
    register_post_type( 'film_post_type', array(
        'label'        => 'Films',
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-editor-video',
        'supports'     => array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
        'taxonomies'   => array( 'genre', 'country', 'actor' ),
        'rewrite' => array(
                'slug' => 'films'
        )
    ) );
}
add_action( 'init', 'register_film_post_type' );

/**
 * Allow using thumbnails for custom post type
 *
 * @return void
 */
function add_thumbnail_theme(){
    add_theme_support('post-thumbnails', array( 'film_post_type' ));
}
add_action('after_setup_theme', 'add_thumbnail_theme');

/**
 * Add metabox for metafields
 *
 * @return void
 */
function film_meta_fields() {
    add_meta_box(
        'meta_fields',
        'Film Meta Box',
        'film_meta_field_box',
        'film_post_type',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'film_meta_fields', 1 );

/**
 * Form for metafields
 *
 * @param object $post
 * @return void
 */
function film_meta_field_box( $post ) {
    ?>
    <p>
        <label>Film Cost $:
            <input type="number"
                   name="meta[cost]"
                   value="<?php echo get_post_meta( $post->ID, 'cost', 1 ); ?>" />
        </label>
    </p>
    <p>
        <label>Release Date:
            <input type="date"
                   name="meta[release]"
                   value="<?php echo get_post_meta( $post->ID, 'release', 1 ); ?>" />
        </label>
    </p>
    <input type="hidden" name="meta_fields_nonce" value="<?php echo wp_create_nonce( __FILE__ ); ?>" />
    <?php
}

/**
 * Save, update or delete metafields for custom post type
 *
 * @param int $post_id
 * @return int
 */
function film_meta_fields_update( int $post_id ) {

    if (
        empty( $_POST['meta'] )
        || ! wp_verify_nonce( $_POST['meta_fields_nonce'], __FILE__ )
        || wp_is_post_autosave( $post_id )
        || wp_is_post_revision( $post_id )
    )
        return false;

    $_POST['meta'] = array_map( 'sanitize_text_field', $_POST['meta'] );
    foreach( $_POST['meta'] as $key => $value ) {
        if( empty( $value ) ) {
            delete_post_meta( $post_id, $key );
            continue;
        }

        update_post_meta( $post_id, $key, $value );
    }

    return $post_id;
}
add_action( 'save_post', 'film_meta_fields_update', 0 );

/**
 * Filtering and sorting function for AJAX
 *
 * @return void
 */
function custom_filter_function() {
    // Genre filter checkbox
    if( $genres = get_terms( array( 'taxonomy' => 'genre' ) ) ) :
        $genres_terms = array();

        foreach( $genres as $genre ) {
            if( isset( $_POST['genre_' . $genre->term_id ] ) && $_POST['genre_' . $genre->term_id] == 'on' )
                $genres_terms[] = $genre->name;
        }
    endif;

    // Country filter checkbox
    if( $countries = get_terms( array( 'taxonomy' => 'country' ) ) ) :
        $countries_terms = array();

        foreach( $countries as $country ) {
            if( isset( $_POST['country_' . $country->term_id ] ) && $_POST['country_' . $country->term_id] == 'on' )
                $countries_terms[] = $country->name;
        }
    endif;

    // Actor filter checkbox
    if( $actors = get_terms( array( 'taxonomy' => 'actor' ) ) ) :
        $actors_terms = array();

        foreach( $actors as $actor ) {
            if( isset( $_POST['actor_' . $actor->term_id ] ) && $_POST['actor_' . $actor->term_id] == 'on' )
                $actors_terms[] = $actor->name;
        }
    endif;

    // Create array to build taxonomy query for tax filtering
    $tax_query = array( 'relation' => 'AND' );

    // Create query for genre tax
    if ( !empty( $genres_terms ) ) {
        $tax_query[] = array(
            'taxonomy' => 'genre',
            'field'    => 'name',
            'terms'    => $genres_terms,
        );
    }

    // Create query for country tax
    if ( !empty( $countries_terms ) ) {
        $tax_query[] = array(
            'taxonomy' => 'country',
            'field'    => 'name',
            'terms'    => $countries_terms,
        );
    }

    // Create query for actor tax
    if ( !empty( $actors_terms ) ) {
        $tax_query[] = array(
            'taxonomy' => 'actor',
            'field'    => 'name',
            'terms'    => $actors_terms,
        );
    }

    // Create array to build meta query for meta fields filtering
    $meta_query = array( 'relation' => 'AND' );

    // if minimum cost and maximum cost are set
    if( isset( $_POST['cost_min'] ) && $_POST['cost_min'] && isset( $_POST['cost_max'] ) && $_POST['cost_max'] ) {
        $meta_query[] = array(
            'key'     => 'cost',
            'value'   => array( $_POST['cost_min'], $_POST['cost_max'] ),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    } else {
        // if only min cost is set
        if( isset( $_POST['cost_min'] ) && $_POST['cost_min'] ) {
            $meta_query[] = array(
                'key'     => 'cost',
                'value'   => $_POST['cost_min'],
                'type'    => 'NUMERIC',
                'compare' => '>='
            );
        }
        // if only max price is set
        if( isset( $_POST['cost_max'] ) && $_POST['cost_max'] ) {
            $meta_query[] = array(
                'key'     => 'cost',
                'value'   => $_POST['cost_max'],
                'type'    => 'NUMERIC',
                'compare' => '<='
            );
        }
    }

    // if start date and end date are set
    if( isset( $_POST['date_start'] ) && $_POST['date_start'] && isset( $_POST['date_end'] ) && $_POST['date_end'] ) {
        $meta_query[] = array(
            'key'     => 'release',
            'value'   => array( $_POST['date_start'], $_POST['date_end'] ),
            'type'    => 'DATE',
            'compare' => 'BETWEEN'
        );
    } else {
        // if only start date is set
        if( isset( $_POST['date_start'] ) && $_POST['date_start'] ) {
            $meta_query[] = array(
                'key'     => 'release',
                'value'   => $_POST['date_start'],
                'type'    => 'DATE',
                'compare' => '>='
            );
        }
        // if only end date is set
        if( isset( $_POST['date_end'] ) && $_POST['date_end'] ) {
            $meta_query[] = array(
                'key'     => 'release',
                'value'   => $_POST['date_end'],
                'type'    => 'DATE',
                'compare' => '<='
            );
        }
    }

    // Default sorting by ID
    $order_by = 'ID';

    // Date and cost sorting
    if( isset( $_POST['sorting'] ) && $_POST['sorting'] ) {
        $sort_type = str_replace( '_release', '', $_POST['sorting'] );
        if( str_contains( $_POST['sorting'], '_release' ) ) {
            $meta_query[] = array(
                'film_release' => array(
                    'key' => 'release'
                )
            );
            $order_by = array(
                'film_release' => $sort_type
            );
        } elseif ( str_contains( $_POST['sorting'], '_cost' ) ) {
            $sort_type = str_replace( '_cost', '', $_POST['sorting'] );
            $meta_query[] = array(
                'film_cost' => array(
                    'key' => 'cost'
                )
            );
            $order_by = array(
                'film_cost' => $sort_type
            );
        }
    }

    // Args for building filter query
    $args = array(
        'post_type'      => 'film_post_type',
        'posts_per_page' => -1,
        'tax_query'      => $tax_query,
        'meta_query'     => $meta_query,
        'orderby'        => $order_by
    );

    // Build filter query
    $query = new WP_Query( $args );

    if( $query->have_posts() ) {
        while ( $query->have_posts() ): $query->the_post(); ?>

            <div class="film-preview">
                <div class="film-preview-thumbnail">
                    <?php $film_post_id = $query->post->ID;
                    echo get_the_post_thumbnail( $film_post_id, 'thumbnail' );
                    ?>
                </div>
                <div class="film-preview-description">
                    <a href="<?php echo get_permalink( $film_post_id ); ?>">
                        <h3><?php echo esc_html( $query->post->post_title ); ?></h3>
                    </a>
                    <p>
                        <?php
                        echo get_the_term_list( $film_post_id, 'genre', 'Genre: ', ', ' );
                        ?>
                    </p>
                    <p>
                        <?php
                        echo get_the_term_list( $film_post_id, 'country', 'Country: ', ', ' );
                        ?>
                    </p>
                    <p>
                        Release date: <?php
                        $release_year = get_post_meta( $film_post_id, 'release', true );
                        echo date( 'Y', strtotime( $release_year ) );
                        ?>
                    </p>
                </div>
            </div>

        <?php endwhile;
        wp_reset_postdata();
    } else {
        echo '<h2>No films found</h2>';
    }

    die();
}
add_action( 'wp_ajax_custom_filter', 'custom_filter_function' );
add_action( 'wp_ajax_nopriv_custom_filter', 'custom_filter_function' );
