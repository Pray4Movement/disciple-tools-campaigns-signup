<?php get_header(); ?>

    <h1 class="section-title center"><?php the_title() ?></h1>
    <div class="content two-col">
        <div class="content__text">

            <?php the_content() ?>

        </div>
        <div class="" style="padding:0 2rem 0 2rem">
            <h2>I'm creating a:</h2>


            <h3 style="display: flex; align-items: center; gap:1rem">
                <img style="width: 50px; filter: invert(40%) sepia(94%) saturate(728%) hue-rotate(333deg) brightness(98%) contrast(106%);" src="<?php echo esc_html( get_template_directory_uri() . '/assets/images/prayer-network.svg' ) ?>"/>
                Normal Campaign
                <a class=" bg-primary" href="create" style="padding:1rem">
                    Create
                </a>
            </h3>
            <h3 style="display: flex; align-items: center; gap:1rem">
                <img style="width: 50px; filter: invert(40%) sepia(94%) saturate(728%) hue-rotate(333deg) brightness(98%) contrast(106%);" src="<?php echo esc_html( get_template_directory_uri() . '/assets/images/ramadan.svg' ) ?>"/>
                Campaign for Ramadan
                <a class=" bg-primary" href="create?ramadan" style="padding:1rem">
                    Create
                </a>
            </h3>

            <br>
            <p>
                Note: If you have already created a campaign in the past, please sign in and create a new campaign from the settings area.
            </p>
        </div>
    </div>

<?php get_footer(); ?>
