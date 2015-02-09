<div class="wrap dgtw">

    <form method="post">
        <div class="icon32" style="background:url(<?php echo KNEWS_URL; ?>/images/icon32.png) no-repeat 0 0;"><br></div>

        <input type="submit" style="float:right;margin:0 3px;" value="Save settings" class="button-primary" id="submit" name="submit"/>
        <button type="button" style="float:right;margin:0 3px;" class="button-primary" id="dg_tw_import_now"><?= __('Import Tweets now!', 'twitter-posts-to-blog') ?></button>

        <h3 class="nav-tab-wrapper-dgtw">
            <a class="nav-tab nav-tab-active" data-item=".tabs-1" href="#tabs-1"><?= __('Content selection', 'twitter-posts-to-blog') ?></a>
            <a class="nav-tab" data-item=".tabs-2" href="#tabs-2"><?= __('Post customization', 'twitter-posts-to-blog') ?></a>
            <a class="nav-tab" data-item=".tabs-3" href="#tabs-3"><?= __('Cron Settings', 'twitter-posts-to-blog') ?></a>
            <a class="nav-tab" data-item=".tabs-4" href="#tabs-4"><?= __('App settings', 'twitter-posts-to-blog') ?></a>
            <a class="nav-tab" data-item=".tabs-5" href="#tabs-5"><?= __('Advanced settings', 'twitter-posts-to-blog') ?></a>
        </h3>

        <input type="hidden" name="dg_tw_data_update" value="yes" />

        <div>
            <div class="dg_tw_tabs tabs-1">
                <p>
                    <b><?= __('Items at time', 'twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('How many item want to load each time the cron run', 'twitter-posts-to-blog') ?>:</span><br/>
                    <input type="text" size="60" name="dg_tw_ipp" class="regular-text" value="<?php echo isset($dg_tw_ft['ipp']) ? $dg_tw_ft['ipp'] : ''; ?>">
                </p>
                <br/>
                </p>

                <p>
                    <b><?= __('Words blacklist', 'twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Does not post tweets with these words (separated by comma ",")', 'twitter-posts-to-blog') ?>:</span><br/>
                    <input type="text" size="60" name="dg_tw_badwords" class="regular-text" value="<?php echo isset($dg_tw_ft['badwords']) ? $dg_tw_ft['badwords'] : ''; ?>">
                </p>
                <br/>
                </p>


                <p>
                    <b><?= __('Users blacklist','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Does not post tweets of these users (separated by comma ",")','twitter-posts-to-blog') ?>:</span><br/>
                    <input type="text" size="60" name="dg_tw_baduser" class="regular-text" value="<?php echo isset($dg_tw_ft['baduser']) ? $dg_tw_ft['baduser'] : ''; ?>">
                </p>
                <br/>
                </p>


                <p>
                    <b><?= __('Post Modifications','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <input type="checkbox" name="dg_tw_notags" <?php if (!empty($dg_tw_ft['notags'])) echo 'checked'; ?> />
                    <span class="description"><?= __('Remove all hashtags from posts','twitter-posts-to-blog') ?></span><br/>
                    <input type="checkbox" name="dg_tw_noreplies" <?php if (!empty($dg_tw_ft['noreplies'])) echo 'checked'; ?> />
                    <span class="description"><?= __('Remove all @replies from posts (removes retweet "RT @user:" text as well)','twitter-posts-to-blog') ?></span>
                </p>
                <br/>
                </p>

                <p>
                    <b><?= __('Your search queryes','twitter-posts-to-blog') ?></b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('You can add more item by click the ADD button below','twitter-posts-to-blog') ?></span><br/>
                    <input type="text" id="dg_tw_add_title" size="60" name="dg_tw_query" class="regular-text" value="">
                    <input type="button" id="dg_tw_add_element" name="add_feed" value="<?= __('Add'); ?>" class="button-primary">
                </p>
                <br/>
                </p>

                <p>
                    <span class="description"><?= __('Current queryes','twitter-posts-to-blog') ?></span><br/>
                <p class="dg_tw_horiz">
                <div id="dg_tw_elements_selected">
                    <?php if (!empty($dg_tw_queryes)) foreach ($dg_tw_queryes as $query_element) { ?>
                            <p style="text-align:left;padding:5px;">
                                <input class="button-primary dg_tw_button_remove" type="button" name="delete" value="Delete"> 
                                <input type="text" size="20" class="regular-text" name="dg_tw_item_query[<?php echo $query_element['value']; ?>][value]" value="<?php echo $query_element['value']; ?>">
                                &nbsp;&nbsp;&nbsp;tag:&nbsp;<input type="text" size="20" name="dg_tw_item_query[<?php echo $query_element['value']; ?>][tag]" value="<?php echo $query_element['tag']; ?>">
                                <span> - <a target="_blank" href="https://twitter.com/search?q=<?php echo urlencode($query_element['value']); ?>&since_id=<?php echo $query_element['last_id']; ?>"><?= __('From last','twitter-posts-to-blog') ?></a></span> 
                            </p>
                        <?php } ?>
                </div>
                </p>
                </p>
                <br/>
            </div>

            <div class="dg_tw_tabs invisible tabs-2">
                <p>
                    <b><?= __('Post tags','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Type tags you want append to each tweet (dont use query strings here)','twitter-posts-to-blog') ?></span><br/>
                    <input type="text" size="60" name="dg_tw_tag_tweets" class="regular-text" value="<?php echo $dg_tw_tags; ?>"><br/>

                    <input type="checkbox" name="dg_tw_authortag" <?php if (!empty($dg_tw_ft['authortag'])) echo 'checked'; ?> />
                    <span class="description"><?= __('Insert the author name as tag','twitter-posts-to-blog') ?></span>
                </p>
                <br/>
                </p>



                <p>
                    <b><?= __('Post category','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                <ul class="list:category categorychecklist form-no-clear">
                    <?php
                    $selected_cats = $dg_tw_cats;
                    wp_terms_checklist(0, array(
                        'taxonomy' => 'category',
                        'descendants_and_self' => 0,
                        'selected_cats' => $selected_cats,
                        'popular_cats' => false,
                        'walker' => null,
                        'checked_ontop' => false
                    ));
                    ?>
                </ul>
                </p>
                <br/>
                </p>



                <p>
                    <b><?= __('Content','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <input type="checkbox" name="dg_tw_link_hashtag" value="1" <?php if (!empty($dg_tw_ft['link_hashtag'])) echo 'checked'; ?> />
                    &nbsp;
                    <span class="description"><?= __('Make hasktag linked','twitter-posts-to-blog') ?></span><br/>

                    <input type="checkbox" name="dg_tw_link_mentions" <?php if (!empty($dg_tw_ft['link_mentions'])) echo 'checked'; ?> />
                    &nbsp;
                    <span class="description"><?= __('Make mentions linked','twitter-posts-to-blog') ?></span><br/>

                    <input type="checkbox" name="dg_tw_link_urls" <?php if (!empty($dg_tw_ft['link_urls'])) echo 'checked'; ?> />
                    &nbsp;
                    <span class="description"><?= __('Make urls linked','twitter-posts-to-blog') ?></span><br/>

                    <input type="checkbox" name="dg_tw_featured_image" <?php if (!empty($dg_tw_ft['featured_image'])) echo 'checked'; ?> />
                    &nbsp;
                    <span class="description"><?= __('Insert images ad feature image','twitter-posts-to-blog') ?></span><br/>
                </p>
                <br/>
                </p>



                <p>
                    <b><?= __('Body structure','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <textarea cols="45" name="dg_tw_body_format"><?php echo isset($dg_tw_ft['body_format']) ? $dg_tw_ft['body_format'] : "<p class='tweet_text'>%tweet%</p>"; ?></textarea>
                    <br/>
                    <span class="description"><?= __('Shortcodes','twitter-posts-to-blog') ?>: %tweet% %author% %avatar_url% %tweet_url% %tweet_images% %tweet_date%</span>
                    <br/><br/>
                </p>
                <br/>
                </p>



                <p>
                    <b><?= __('Image size','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Select user image size','twitter-posts-to-blog') ?>:</span><br/>
                    <select name="dg_tw_ft_size">
                        <option value="original"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'original') echo ' selected=selected'; ?>><?= __('Original','twitter-posts-to-blog') ?></option>
                        <option value="mini"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'mini') echo ' selected=selected'; ?>><?= __('Mini - 24px by 24px','twitter-posts-to-blog') ?></option>
                        <option value="normal"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'normal') echo ' selected=selected'; ?>><?= __('Normal - 48px by 48px','twitter-posts-to-blog') ?></option>
                        <option value="bigger"<?php if (isset($dg_tw_ft['img_size']) && $dg_tw_ft['img_size'] === 'bigger') echo ' selected=selected'; ?>><?= __('Bigger - 73px by 73px','twitter-posts-to-blog') ?></option>
                    </select>
                </p>
                <br/>
                </p>



                <p>
                    <b><?= __('Title Settings','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Title structure','twitter-posts-to-blog') ?></span><br/>
                    <textarea cols="45" name="dg_tw_title_format"><?php echo isset( $dg_tw_ft['title_format'] ) ? $dg_tw_ft['title_format'] : __('Tweet from %author%', 'twitter-posts-to-blog'); ?></textarea>
                    <br/>
                    <span class="description"><?= __('Shortcodes','twitter-posts-to-blog') ?>: %tweet% %author% %avatar_url% %tweet_url%  %tweet_date%</span>
                    <br/><br/>
                    <span class="description"><?= __('Set the maximum length in characters of the title','twitter-posts-to-blog') ?>;</span><br/>
                    <input type="text" size="60" name="dg_tw_maxtitle" class="regular-text" value="<?php echo isset($dg_tw_ft['maxtitle']) ? $dg_tw_ft['maxtitle'] : ''; ?>">
                    <br/>
                    <input type="checkbox" name="dg_tw_title_remove_url" <?php if (!empty($dg_tw_ft['title_remove_url'])) echo 'checked'; ?> />
                    <span class="description"><?= __('Remove urls from the title string','twitter-posts-to-blog') ?></span><br/>
                </p>
                <br/>
                </p>



                <p>
                    <b><?= __('Date structure','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <input type="text" name="dg_tw_date_format" value="<?php echo isset($dg_tw_ft['date_format']) ? $dg_tw_ft['date_format'] : "F j, Y, g:i a"; ?>" class="regular-text" />
                    <br/>
                    <span class="description"><?= __('See','twitter-posts-to-blog') ?> <a href="http://php.net/manual/en/function.date.php" target="_blank">PHP Date</a> <?= __('for all date structure codes','twitter-posts-to-blog') ?></span>
                    <br/><br/>
                </p>
                <br/>
                </p>
            </div>

            <div class="dg_tw_tabs invisible tabs-3">
                <p>
                    <b><?= __('Capabilities','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Who can see this page and change settings','twitter-posts-to-blog') ?>:</span><br/>
                    <select name="dg_tw_privileges">
                        <option value="activate_plugins"<?php if (isset($dg_tw_ft['privileges']) && $dg_tw_ft['privileges'] === 'activate_plugins') echo ' selected=selected'; ?>><?= __('Administrator','twitter-posts-to-blog') ?></option>
                        <option value="delete_pages"<?php if (isset($dg_tw_ft['privileges']) && $dg_tw_ft['privileges'] === 'delete_pages') echo ' selected=selected'; ?>><?= __('Editor','twitter-posts-to-blog') ?></option>
                        <option value="delete_posts"<?php if (isset($dg_tw_ft['privileges']) && $dg_tw_ft['privileges'] === 'delete_posts') echo ' selected=selected'; ?>><?= __('Author','twitter-posts-to-blog') ?></option>
                    </select>
                </p>
                <br/>
                </p>

                <p style="width:23%;">
                    <b><?= __('Cron time','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Choose how much time must pass before load new items, use "never" to disable','twitter-posts-to-blog') ?></span><br/>
                    <select name="dg_tw_time_selected" id="dg_tw_time_selected">
                        <option value="never"<?php if (isset($dg_tw_time['run']) && $dg_tw_time['run'] === 'never') echo ' selected=selected'; ?>><?= __('never','twitter-posts-to-blog') ?></option>

                        <?php
                        $recurrences = wp_get_schedules();

                        foreach ($recurrences as $slug => $recurrence) {
                            ?>
                            <option value="<?php echo $slug; ?>"<?php if (isset($dg_tw_time['run']) && $dg_tw_time['run'] == $slug) echo ' selected=selected'; ?>><?php echo $recurrence['display']; ?></option>
                        <?php }
                        ?>
                    </select><br/><br/>
                <div id="dg_tw_cycle_selectors">
                    <span class="description"><?= __('Choose the cycle time (this is the start date be carefuly)','twitter-posts-to-blog') ?></span><br/>
                    <?= __('Day of the month','twitter-posts-to-blog') ?>: 
                    <select name="dg_tw_time_month">
                        <optgroup label="Day of the Month">
                            <?php
                            for ($i = 1; $i <= 31; $i++) {
                                $selected = (isset($dg_tw_time['start']['month']) && $dg_tw_time['start']['month'] == $i) ? "selected" : "";
                                echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </optgroup>
                    </select><br/>
                    <?= __('Day of the week','twitter-posts-to-blog') ?>: 
                    <select name="dg_tw_time_week">
                        <optgroup label="Day of the Week">
                            <?php
                            $array_week = array(
                                __("Monday",'twitter-posts-to-blog'), 
                                __("Tuesday",'twitter-posts-to-blog'), 
                                __("Wednesday",'twitter-posts-to-blog'), 
                                __("Thursday",'twitter-posts-to-blog'), 
                                __("Friday",'twitter-posts-to-blog'), 
                                __("Saturday",'twitter-posts-to-blog'), 
                                __("Sunday",'twitter-posts-to-blog'));

                            foreach ($array_week as $day) {
                                $selected = (isset($dg_tw_time['start']['week']) && $dg_tw_time['start']['week'] == $day) ? "selected" : "";
                                echo '<option ' . $selected . ' value="' . $day . '">' . $day . '</option>';
                            }
                            ?>
                        </optgroup>
                    </select><br/>
                </div>

                <?= __('Time','twitter-posts-to-blog') ?>: 
                <select name="dg_tw_time_hour">
                    <optgroup label="Hour">
                        <?php
                        for ($i = 0; $i <= 23; $i++) {
                            $selected = (isset($dg_tw_time['start']['hour']) && $dg_tw_time['start']['hour'] == $i) ? "selected" : "";
                            echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </optgroup>
                </select>&nbsp;:&nbsp;
                <select name="dg_tw_time_minute">
                    <optgroup label="Minute">
                        <?php
                        for ($i = 1; $i <= 59; $i++) {
                            $selected = (isset($dg_tw_time['start']['minute']) && $dg_tw_time['start']['minute'] == $i) ? "selected" : "";
                            echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
                        }
                        ?>
                    </optgroup>
                </select>
                </p>
                <br/>
                </p>
            </div>

            <div class="dg_tw_tabs invisible tabs-4">
                <p>
                    <b><?= __('Twitter app settings','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Consumer key','twitter-posts-to-blog') ?>:</span><br/>
                    <input type="text" size="60" name="dg_tw_access_key" class="regular-text" value="<?php echo @$dg_tw_ft['access_key']; ?>"><br/><br/>
                    <span class="description"><?= __('Consumer secret','twitter-posts-to-blog') ?>:</span><br/>
                    <input type="text" size="60" name="dg_tw_access_secret" class="regular-text" value="<?php echo @$dg_tw_ft['access_secret']; ?>"><br/><br/>
                    <span class="description"><?= __('Access token','twitter-posts-to-blog') ?>:</span><br/>
                    <input type="text" size="60" name="dg_tw_access_token" class="regular-text" value="<?php echo @$dg_tw_ft['access_token']; ?>"><br/><br/>
                    <span class="description"><?= __('Access token secret','twitter-posts-to-blog') ?>:</span><br/>
                    <input type="text" size="60" name="dg_tw_access_token_secret" class="regular-text" value="<?php echo @$dg_tw_ft['access_token_secret']; ?>">
                </p>
                <br/>
                </p>
            </div>

            <div class="dg_tw_tabs invisible tabs-5">
                <p>
                    <b><?= __('Publish settings','twitter-posts-to-blog') ?>:</b><br/>
                <p class="dg_tw_horiz">
                    <span class="description"><?= __('Server call method','twitter-posts-to-blog') ?>:</span><br/>
                    <select name="dg_tw_request_method">
                        <option value="standard" <?php if (isset($dg_tw_ft['request_method']) && $dg_tw_ft['request_method'] === 'standard') echo 'selected=selected'; ?>><?= __('Standard','twitter-posts-to-blog') ?></option>
                        <option value="curl" <?php if (isset($dg_tw_ft['request_method']) && $dg_tw_ft['request_method'] === 'curl') echo 'selected=selected'; ?>><?= __('Curl','twitter-posts-to-blog') ?></option>
                    </select><br/><br/>

                    <span class="description"><?= __('Post format','twitter-posts-to-blog') ?></span><br/>
                    <select name="dg_tw_format">
                        <option value="standard" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'standard') echo 'selected=selected'; ?>><?= __('Standard','twitter-posts-to-blog') ?></option>
                        <option value="aside" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'aside') echo 'selected=selected'; ?>><?= __('Aside','twitter-posts-to-blog') ?></option>
                        <option value="gallery" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'gallery') echo 'selected=selected'; ?>><?= __('Gallery','twitter-posts-to-blog') ?></option>
                        <option value="link" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'link') echo 'selected=selected'; ?>><?= __('Link','twitter-posts-to-blog') ?></option>
                        <option value="image" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'image') echo 'selected=selected'; ?>><?= __('Image','twitter-posts-to-blog') ?></option>
                        <option value="quote" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'quote') echo 'selected=selected'; ?>><?= __('Quote','twitter-posts-to-blog') ?></option>
                        <option value="status" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'status') echo 'selected=selected'; ?>><?= __('Status','twitter-posts-to-blog') ?></option>
                        <option value="video" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'video') echo 'selected=selected'; ?>><?= __('Video','twitter-posts-to-blog') ?></option>
                        <option value="audio" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'audio') echo 'selected=selected'; ?>><?= __('Audio','twitter-posts-to-blog') ?></option>
                        <option value="chat" <?php if (isset($dg_tw_ft['format']) && $dg_tw_ft['format'] === 'chat') echo 'selected=selected'; ?>><?= __('Chat','twitter-posts-to-blog') ?></option>
                    </select><br/><br/>

                    <span class="description"><?= __('Post type','twitter-posts-to-blog') ?>:</span><br/>
                    <select name="dg_tw_post_type">
                        <?php
                        $post_types = get_post_types();

                        foreach ($post_types as $post_type => $type_name) {
                            if (!in_array(strtolower($post_type), array('revision', 'nav_menu_item', 'attachment'))) {
                                ?>
                                <option value="<?php echo strtolower($post_type); ?>" <?php if (isset($dg_tw_ft['post_type']) && $dg_tw_ft['post_type'] === $post_type) echo 'selected=selected'; ?>><?php echo ucfirst($type_name); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select><br/><br/>

                    <span class="description"><?= __('Post status: published or draft','twitter-posts-to-blog') ?></span><br/>
                    <select name="dg_tw_publish_selected">
                        <option value="publish"<?php if ($dg_tw_publish === 'publish') echo ' selected=selected'; ?>><?= __('Published','twitter-posts-to-blog') ?></option>
                        <option value="draft"<?php if ($dg_tw_publish === 'draft') echo ' selected=selected'; ?>><?= __('Draft','twitter-posts-to-blog') ?></option>
                    </select><br/><br/>

                    <span class="description"><?= __('Post method','twitter-posts-to-blog') ?></span><br/>
                    <select name="dg_tw_method">
                        <option value="multiple" <?php if (isset($dg_tw_ft['method']) && $dg_tw_ft['method'] === 'multiple') echo 'selected=selected'; ?>><?= __('One post per tweet','twitter-posts-to-blog') ?></option>
                        <option value="single" <?php if (isset($dg_tw_ft['method']) && $dg_tw_ft['method'] === 'single') echo 'selected=selected'; ?>><?= __('All tweets in one post','twitter-posts-to-blog') ?></option>
                    </select><br/><br/>

                    <span class="description"><?= __('Post author','twitter-posts-to-blog') ?>:</span><br/>
                    <?php
                    $args = array(
                        'orderby' => 'display_name',
                        'order' => 'ASC',
                        'multi' => false,
                        'show' => 'display_name',
                        'echo' => true,
                        'selected' => isset($dg_tw_ft['author']) ? $dg_tw_ft['author'] : null,
                        'include_selected' => true,
                        'name' => 'dg_tw_author',
                        'blog_id' => $GLOBALS['blog_id']
                    );

                    wp_dropdown_users($args);
                    ?>
                </p>
                <br/>
                </p>
            </div>
        </div>
    </form>
</div>