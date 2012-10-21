<?php get_header() ?>

<div id="content">
    <div class="padder">

        <?php do_action('bp_before_blog_single_post') ?>

        <div class="page" id="blog-single">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php
                        global $cc_post_options;
                        $cc_post_options = cc_get_post_meta();
                        $single_class = false;
                        if (isset($cc_post_options) && $cc_post_options['cc_post_template_on'] == 1) {

                            switch ($cc_post_options['cc_post_template_type']) {
                                case 'img-left-content-right':
                                    $single_class = 'single-img-left-content-right';
                                    break;
                                case 'img-right-content-left':
                                    $single_class = 'single-img-right-content-left';
                                    break;
                                case 'img-over-content':
                                    $single_class = 'single-img-over-content';
                                    break;
                                case 'img-under-content':
                                    $single_class = 'single-img-under-content';
                                    break;
                                default:
                                    $single_class = false;
                                    break;
                            }
                        }
                        ?>		
                        <?php if ($cc_post_options['cc_post_template_avatar'] != '1') { ?>
                            <div class="author-box">
                                <?php echo get_avatar(get_the_author_meta('user_email'), '50'); ?>
                                <?php if (defined('BP_VERSION')) { ?>
                                    <p><?php printf(__('by %s', 'cc'), bp_core_get_userlink($post->post_author)) ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="post-content" style="<?php if ($cc_post_options['cc_post_template_avatar'] == '1') {
                            echo 'margin-left:0;';
                        } ?>">	
                                <?php if ($single_class != false) { ?>
                                <div class="<?php echo $single_class ?>">
        <?php } ?>

                                <h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'cc') ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                <?php if ($cc_post_options['cc_post_template_date'] != '1') { ?>
                                    <p class="date"><?php the_time('F j, Y') ?> <em><?php _e('in', 'cc') ?> <?php the_category(', ') ?> <?php if (defined('BP_VERSION')) {
                            printf(__(' by %s', 'cc'), bp_core_get_userlink($post->post_author));
                        } ?></em></p>
                                    <?php } ?> 

                                <div class="entry">
                                    <?php if ($single_class == 'single-img-left-content-right' || $single_class == 'single-img-right-content-left' || $single_class == 'single-img-over-content') { ?>
                                        <?php the_post_thumbnail() ?>
                                    <?php } ?>
                                    <?php the_content(__('Read the rest of this entry &rarr;', 'cc')); ?>
                                    <?php if ($single_class == 'single-img-under-content') { ?>
                                        <?php the_post_thumbnail() ?>
        <?php } ?>		
                                    <div class="clear"></div>
                                    <?php wp_link_pages(array('before' => __('<p class="cc_pagecount"><strong>Pages:</strong> ', 'cc'), 'after' => '</p>', 'next_or_number' => 'number')); ?>
                                </div>
                                <div class="relcont">

                                    <?
                                    $cat = get_the_category_list();
                                    $cssclass='relpostperson';
                                    if (strpos($cat, "Show")){
                                        $isShow = true;
                                        $cssclass='relpostshow';
                                    }
                                    $list = get_related_posts_list($isShow);
                                    //var_dump($list);
                                   
                                    for ($i = 1; $i < count($list) + 1; $i++) {
                                        //echo "<div style='height:80px; width:400px; background-color:red; float:left; margin-bottom:20px;'>asdasd</div>";
                                        echo "<div class='$cssclass'>".$list[$i] . "</div>";
                                    }
                                   
                                    ?>
                                    </div>
                                    <div>
                                    <?
                                    if (!strpos($cat, "Person")) {
                                        $list = get_episodes($isShow);
                                        $first = $isShow ? 1 : 0;
                                        $count= $isShow ? count($list) : 2;
                                        $nav=array();
                                        for ($i = $first; $i < $count + 1; $i++) {
                                            
                                            if($first==1)
                                                echo "<div >".$list[$i] . "</div>";
                                            else if($first==0){
                                                
                                                if($list[$i]['epno']==$list[0]['epno'])
                                                    $nav["center"]="<div class='alignmid' >".$list[$i]['link'] . "</div>";
                                                else if(strcmp ($list[$i]['epno'],"e".$list[0]['epno'])==0)
                                                    $nav["right"]= "<div class='alignright' >".$list[$i]['link'] . "</div>";
                                                else if(strcmp ($list[$i]['epno'],"e".$list[0]['epno'])<0)
                                                    $nav["left"]="<div class='alignleft' >".$list[$i]['link'] . "</div>";
                                                
                                                if($i==2) {
                                                    echo $nav["left"].$nav["center"].$nav["right"];
                                                    break;
                                                    
                                               }
                                                
                                            }
                                        }
                                    }
                                    ?>
                                </div>	
                                <div class="clear"></div>

                                <?php if ($cc_post_options['cc_post_template_tags'] != '1') { ?>
                                    <?php $tags = get_the_tags();
                                    if ($tags) { ?>
                                        <p class="postmetadata"><span class="tags"><?php the_tags(__('Tags: ', 'cc'), ', ', '<br />'); ?></span></p>
                                    <?php } ?> 
                                <?php } ?>	 

                                <?php if ($cc_post_options['cc_post_template_comments_info'] != '1') { ?>
                                    <p class="postmetadata"><span class="comments"><?php comments_popup_link(__('No Comments &#187;', 'cc'), __('1 Comment &#187;', 'cc'), __('% Comments &#187;', 'cc')); ?></span></p>
                            <?php } ?>

        <?php if ($single_class != false) { ?>
                                </div>
        <?php } ?>	

                            <div class="alignleft"><?php previous_post_link('%link', '<span class="meta-nav">' . _x('&larr;', 'Previous post link', 'cc') . '</span> %title'); ?></div>
                            <div class="alignright"><?php next_post_link('%link', '%title <span class="meta-nav">' . _x('&rarr;', 'Next post link', 'cc') . '</span>'); ?></div>
                        </div>
                    </div>

                    <?php edit_post_link(__('Edit this entry.', 'cc'), '<p>', '</p>'); ?>

                    <?php comments_template(); ?>

            <?php endwhile;
        else: ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'cc') ?></p>
<?php endif; ?>
        </div>

<?php do_action('bp_after_blog_single_post') ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer() ?>