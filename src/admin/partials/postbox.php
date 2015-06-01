<?php
/**
 * LTI SEO plugin
 *
 *
 * Box that appears on post types
 *
 * @see \Lti\Seo\Admin::metadata_box
 */
?>
<div id="plseo">
	<?php if ( $this->settings->get( 'description_support' ) === true ) : ?>
		<div class="form-group">
			<div class="input-group">
				<label for="lti_seo_description"><?php echo ltint( 'box.description' ); ?></label>
				<textarea name="lti_seo[description]" id="lti_seo_description"
					><?php echo ltiopt( 'description' ); ?></textarea>
				<span id="wlti_seo_description" class="char-counter"><?php echo ltint( 'general.char_count' ); ?>
					&nbsp;<span
						id="clti_seo_description"></span></span>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( $this->settings->get( 'keyword_support' ) === true ) : ?>
		<div class="form-group">
			<div class="input-group">
				<label for="lti_seo_keywords"><?php echo ltint( 'box.keywords' ); ?></label>
				<input type="text" name="lti_seo[keywords]" id="lti_seo_keywords"
				       value="<?php echo ltiopt( 'keywords' ); ?>"/>
				<?php $kw = ltiopt( 'keywords_suggestion' );
				if ( ! is_null( $kw ) && ! empty( $kw ) ) :?>
					<span id="keywords_suggestion_box"><?php echo ltint( 'box.keywords_suggestion' ); ?>&nbsp;<span
							id="lti_seo_keywords_suggestion"><?php echo ltiopt( 'keywords_suggestion' ); ?></span><a
							onclick="document.getElementById('lti_seo_keywords').setAttribute('value',document.getElementById('lti_seo_keywords_suggestion').textContent);">
							<?php echo ltint( 'box.text_copy' ); ?></a></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( $this->settings->get( 'open_graph_support' ) === true || $this->settings->get( 'twitter_card_support' ) === true ) : ?>
		<div class="form-group">
			<div class="input-group">
				<label for="meta_description"><?php echo ltint( 'box.meta_description' ); ?></label>

				<div class="input-group file-selector">
					<input id="lti_social_img" type="text" name="lti_seo[social_img_url]"
					       value="<?php echo ltiopt( 'social_img_url' ); ?>"
					       readonly="readonly"/>

					<div class="btn-group">
						<input id="lti_social_img_button" class="button-primary upload_image_button"
						       type="button"
						       value="<?php echo ltint( 'general.choose_img' ); ?>"/>
						<input id="lti_social_reset" class="button-primary"
						       type="button"
						       value="<?php echo ltint( 'general.reset' ); ?>"/>
					</div>
					<input id="lti_social_img_id" type="hidden"
					       name="lti_seo[social_img_id]"
					       value="<?php echo ltiopt( 'social_img_id' ); ?>"/>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $this->settings->get( 'robot_support' ) === true ) : ?>
		<div class="form-group">
			<div class="input-group">
				<label><?php echo ltint( 'box.group.robots' ); ?></label>

				<div class="checkbox-group">
					<label for="post_robot_noindex"><?php echo ltint( 'box.post_robot_noindex' ); ?>
						<input type="checkbox" name="lti_seo[post_robot_noindex]"
						       id="post_robot_noindex" <?php echo ltichk( 'post_robot_noindex' ); ?>/>
					</label>
					<label for="post_robot_nofollow"><?php echo ltint( 'box.post_robot_nofollow' ); ?>
						<input type="checkbox" name="lti_seo[post_robot_nofollow]"
						       id="post_robot_nofollow" <?php echo ltichk( 'post_robot_nofollow' ); ?>/>
					</label>
					<label for="post_robot_noodp"><?php echo ltint( 'box.post_robot_noodp' ); ?>
						<input type="checkbox" name="lti_seo[post_robot_noodp]"
						       id="post_robot_noodp" <?php echo ltichk( 'post_robot_noodp' ); ?>/>
					</label>
					<label for="post_robot_noydir"><?php echo ltint( 'box.post_robot_noydir' ); ?>
						<input type="checkbox" name="lti_seo[post_robot_noydir]"
						       id="post_robot_noydir" <?php echo ltichk( 'post_robot_noydir' ); ?>/>
					</label>
					<label for="post_robot_noarchive"><?php echo ltint( 'box.post_robot_noarchive' ); ?>
						<input type="checkbox" name="lti_seo[post_robot_noarchive]"
						       id="post_robot_noarchive" <?php echo ltichk( 'post_robot_noarchive' ); ?>/>
					</label>
					<label for="post_robot_nosnippet"><?php echo ltint( 'box.post_robot_nosnippet' ); ?>
						<input type="checkbox" name="lti_seo[post_robot_nosnippet]"
						       id="post_robot_nosnippet" <?php echo ltichk( 'post_robot_nosnippet' ); ?>/>
					</label>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<input id="lti_seo_word_count" type="hidden" name="lti_seo[word_count]" value=""/>
</div>
