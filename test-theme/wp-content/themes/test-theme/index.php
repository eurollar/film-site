<?php get_header(); ?>
<section>
    <form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">

        <div>
            <p>Filter by genres</p>
            <?php if( $genres = get_terms( array( 'taxonomy' => 'genre' ) ) ) : ?>
                <ul class="genres-list">
                <?php foreach( $genres as $genre ) : ?>
                    <li style="">
                        <label for="genre_<?php echo $genre->term_id ;?>">
                            <input type="checkbox"
                                   class=""
                                   id="genre_<?php echo $genre->term_id; ?>"
                                   name="genre_<?php echo $genre->term_id; ?>" />
                            <?php echo $genre->name; ?>
                        </label>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <hr />

        <div>
            <p>Filter by countries</p>
            <?php if( $countries = get_terms( array( 'taxonomy' => 'country' ) ) ) : ?>
                <ul class="countries-list">
                    <?php foreach( $countries as $country ) : ?>
                        <li style="">
                            <label for="country_<?php echo $country->term_id ;?>">
                                <input type="checkbox"
                                       class=""
                                       id="country_<?php echo $country->term_id; ?>"
                                       name="country_<?php echo $country->term_id; ?>" />
                                <?php echo $country->name; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <hr />

        <div>
            <p>Filter by actors</p>
            <?php if( $actors = get_terms( array( 'taxonomy' => 'actor' ) ) ) : ?>
                <ul class="actors-list">
                    <?php foreach( $actors as $actor ) : ?>
                        <li style="">
                            <label for="actor_<?php echo $actor->term_id ;?>">
                                <input type="checkbox"
                                       class=""
                                       id="actor_<?php echo $actor->term_id; ?>"
                                       name="actor_<?php echo $actor->term_id; ?>" />
                                <?php echo $actor->name; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <hr />

        <div>
            <p>Filter by cost</p>
            <input type="number" name="cost_min" placeholder="Min cost value" />
            <input type="number" name="cost_max" placeholder="Max cost value" />
        </div>

        <div>
            <p>Filter by release date</p>
            <input type="date" name="date_start" placeholder="Start date" />
            <input type="date" name="date_end" placeholder="End date" />
        </div>
        <hr />

        <div>
            <p>Sorting by release date</p>
            <label><input type="radio" name="sorting" value="ASC_release" /> Date: Ascended</label>
            <label><input type="radio" name="sorting" value="DESC_release" /> Date: Descended</label>
            <p>Sorting by cost</p>
            <label><input type="radio" name="sorting" value="ASC_cost" /> Cost: Ascended</label>
            <label><input type="radio" name="sorting" value="DESC_cost" /> Cost: Descended</label>
        </div>

        <button class="btn-apply-filter">Apply</button><input type="hidden" name="action" value="custom_filter">
        <a href="/" class="btn-reset-filter">Reset</a>
    </form>

    <div id="response_data">

        <?php
        // Get all posts
        $all_posts = new WP_Query;

        $film_posts = $all_posts->query( array(
            'post_type' => 'film_post_type'
        ) );

        foreach( $film_posts as $film_post ): ?>
            <div class="film-preview">
                <div class="film-preview-thumbnail">
                    <?php $film_post_id = $film_post->ID;
                    echo get_the_post_thumbnail( $film_post_id, 'thumbnail' );
                    ?>
                </div>
                <div class="film-preview-description">
                    <a href="<?php echo get_permalink( $film_post_id ); ?>">
                        <h3><?php echo esc_html( $film_post->post_title ); ?></h3>
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

        <?php endforeach;

        wp_reset_postdata();?>

    </div>
</section>
<?php get_footer(); ?>
