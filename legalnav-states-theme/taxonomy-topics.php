<?php
/**
 * Taxonomy-Topics Template
 */
get_template_part("/template-parts/taxonomy-topics-functions");
get_header();
$user_state_id = $_SESSION['user_state_id'];
?>

<main class="site-main">

    <div class="container topics-page">

        <?php
        // Check that user state is set.
        if (isset($user_state_id)) {

            //Get Topic
            $topic = get_term_by('slug', get_query_var('topics'), 'topics');
            //Get Subqueries
            $sub_topics = query_subtopics($topic);
            //Determine if topic has children
            $is_leaf_topic = topic_is_leaf_node($sub_topics);

            //Render the breadcrumbs
            render_breadcrumbs($topic->term_id); ?>

            <div class="topics-page-container">

                <?php if (!$is_leaf_topic) {
                    //If the topic has children, render a branch page.
                    render_topics_branch($topic, $sub_topics, $user_state_id);
                } else {
                    //If the topic doesn't have children render the resource page.
                    render_topics_leaf($topic, $user_state_id);
                } ?>

            </div>

            <div class="clear"></div>

        <?php } else { ?>

            <h5 class="error-text">Please select a location to view topics.</h5>

        <?php } ?>

    </div>

<?php
    // Topic Page Text Callout
    if($user_state_id && $is_leaf_topic) {
        $cta = get_field('topic_page_callout', get_term_by('id', $user_state_id, 'states'));

        $heading = $cta['heading'];
        $content = $cta['content'];
        $button = $cta['button_link'];

        if($cta && $cta['heading']) { ?>
            <section class="topic-page-callout">
                <h4><?php echo $heading; ?></h4>
                <div class="content">
                    <?php echo $content; ?>
                </div>
                <?php if($button) {?>
                    <a class="btn btn-primary" href="<?php echo $button['url']; ?>" target="_blank"><?php echo $button['title']; ?></a>
                <?php } ?>
            </section>
        <?php }
    }

    // Retrieve state phone number
    if($user_state_id) {
        $state_emergency_phone = get_field('emergency_phone_number', get_term_by('id', $user_state_id, 'states'));
        if($state_emergency_phone) { ?>
            <section class="emergency-phone-block">
                <p class="emergency-phone-message">Are you safe? Call 
                    <a href="tel:<?php echo $state_emergency_phone; ?>" class="emergency-phone-number">
                        <?php echo $state_emergency_phone; ?>
                    </a> to get help.
                </p>
            </section>
        <?php }
    }
?>
</main>

<?php get_footer(); ?>