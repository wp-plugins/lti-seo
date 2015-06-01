<?php
/**
 * LTI SEO plugin
 *
 * Admin View
 *
 * @see \Lti\Seo\Admin::options_page
 *
 */
?>
<div id="lti_seo_wrapper">

	<div id="lti-seo-header" class="lti-seo-header <?php echo ltipagetype() ?>">
		<h2 class="lti-seo-title"><?php echo ltint( 'opt.title' ); ?></h2>

		<h2 class="lti-seo-message"><?php echo ltimessage(); ?></h2>
	</div>
	<div role="tabpanel">
		<ul id="lti_seo_tabs" class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a href="#tab_general" aria-controls="tab_general" role="tab"
				   data-toggle="tab"><?php echo ltint( 'opt.tab.general' ); ?></a>
			</li>
			<li role="presentation">
				<a href="#tab_frontpage" aria-controls="tab_frontpage" role="tab"
				   data-toggle="tab"><?php echo ltint( 'opt.tab.frontpage' ); ?></a>
			</li>
			<li role="presentation">
				<a href="#tab_post" aria-controls="tab_post" role="tab"
				   data-toggle="tab"><?php echo ltint( 'opt.tab.post' ); ?></a>
			</li>
			<li role="presentation">
				<a href="#tab_social" aria-controls="tab_social" role="tab"
				   data-toggle="tab"><?php echo ltint( 'opt.tab.social' ); ?></a>
			</li>
			<li role="presentation">
				<a href="#tab_google" aria-controls="tab_google" role="tab"
				   data-toggle="tab"><?php echo ltint( 'opt.tab.google' ); ?></a>
			</li>
		</ul>

		<form id="flseo" accept-charset="utf-8" method="POST"
		      action="<?php echo $this->get_admin_slug() ?>">
			<?php echo wp_nonce_field( 'lti_seo_options', 'lti_seo_token' ); ?>
			<div class="tab-content">
				<?php
				/***********************************************************************************************
				 *                                  GENERAL TAB
				 ***********************************************************************************************/
				?>
				<div role="tabpanel" class="tab-pane active" id="tab_general">
					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="link_rel_support"><?php echo ltint( 'opt.link_rel_support' ); ?>
									<input type="checkbox" name="link_rel_support" data-toggle="seo-options"
									       data-target="#link_rel_chk_group"
									       id="link_rel_support" <?php echo ltichk( 'link_rel_support' ); ?>/>
								</label>

								<div id="link_rel_chk_group">
									<div class="checkbox-group">
										<label for="link_rel_canonical"><?php echo ltint( 'opt.link_rel_canonical' ); ?>
											<input type="checkbox" name="link_rel_canonical"
											       id="link_rel_canonical" <?php echo ltichk( 'link_rel_canonical' ); ?>/>
										</label>
										<label for="link_rel_publisher"><?php echo ltint( 'opt.link_rel_publisher' ); ?>
											<input type="checkbox" name="link_rel_publisher"
											       id="link_rel_publisher" <?php echo ltichk( 'link_rel_publisher' ); ?>/>
										</label>
										<label for="link_rel_author"><?php echo ltint( 'opt.link_rel_author' ); ?>
											<input type="checkbox" name="link_rel_author"
											       id="link_rel_author" <?php echo ltichk( 'link_rel_author' ); ?>/>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.link_rel1' ); ?></p>
								<ul>
									<li><?php echo ltint( 'opt.hlp.link_rel2' ); ?></li>
									<li><?php echo ltint( 'opt.hlp.link_rel3' ); ?></li>
								</ul>
							</div>

						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label><?php echo ltint( 'opt.keyword_support' ); ?>
								<input type="checkbox" name="keyword_support" data-toggle="seo-options"
								       data-target="#keyword_chk_group"
								       id="keyword_support" <?php echo ltichk( 'keyword_support' ); ?>/>
							</label>

							<div id="keyword_chk_group">
								<div class="checkbox-group">
									<label for="keyword_cat_based"><?php echo ltint( 'opt.keyword_cat_based' ); ?>
										<input type="checkbox" name="keyword_cat_based"
										       id="keyword_cat_based" <?php echo ltichk( 'keyword_cat_based' ); ?>/>
									</label>
									<label for="keyword_tag_based"><?php echo ltint( 'opt.keyword_tag_based' ); ?>
										<input type="checkbox" name="keyword_tag_based"
										       id="keyword_tag_based" <?php echo ltichk( 'keyword_tag_based' ); ?>/>
									</label>
								</div>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.keyword' ); ?></p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label><?php echo ltint( 'opt.group.robots' ); ?>
								<input type="checkbox" name="robot_support" data-toggle="seo-options"
								       data-target="#robot_chk_group"
								       id="robot_support" <?php echo ltichk( 'robot_support' ); ?>/>
							</label>

							<div id="robot_chk_group">
								<div class="input-group">
									<label><?php echo ltint( 'opt.group.robot_attr' ); ?></label>
									<table class="table">
										<thead>
										<tr>
											<th colspan="1"></th>
											<th colspan="2"><?php echo ltint( 'opt.robot.col2' ); ?></th>
										</tr>
										<tr>
											<th><?php echo ltint( 'opt.robot.th1' ); ?></th>
											<th><?php echo ltint( 'opt.robot.th2' ); ?></th>
											<th><?php echo ltint( 'opt.robot.th3' ); ?></th>
										</tr>
										</thead>
										<tbody>
										<tr>
											<td><?php echo ltint( 'opt.robot_noindex' ); ?></td>
											<td><input type="checkbox" name="robot_noindex"
											           id="robot_noindex" <?php echo ltichk( 'robot_noindex' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robot_noindex"
											           id="post_robot_noindex" <?php echo ltichk( 'post_robot_noindex' ); ?>/>
											</td>
										</tr>
										<tr>
											<td><?php echo ltint( 'opt.robot_nofollow' ); ?></td>
											<td><input type="checkbox" name="robot_nofollow"
											           id="robot_nofollow" <?php echo ltichk( 'robot_nofollow' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robot_nofollow"
											           id="post_robot_nofollow" <?php echo ltichk( 'post_robot_nofollow' ); ?>/>
											</td>
										</tr>
										<tr>
											<td><?php echo ltint( 'opt.robot_noodp' ); ?></td>
											<td><input type="checkbox" name="robot_noodp"
											           id="robot_noodp" <?php echo ltichk( 'robot_noodp' ); ?>/></td>
											<td><input type="checkbox" name="post_robot_noodp"
											           id="post_robot_noodp" <?php echo ltichk( 'post_robot_noodp' ); ?>/>
											</td>
										</tr>
										<tr>
											<td><?php echo ltint( 'opt.robot_noydir' ); ?></td>
											<td><input type="checkbox" name="robot_noydir"
											           id="robot_noydir" <?php echo ltichk( 'robot_noydir' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robot_noydir"
											           id="post_robot_noydir" <?php echo ltichk( 'post_robot_noydir' ); ?>/>
											</td>
										</tr>
										<tr>
											<td><?php echo ltint( 'opt.robot_noarchive' ); ?></td>
											<td><input type="checkbox" name="robot_noarchive"
											           id="robot_noarchive" <?php echo ltichk( 'robot_noarchive' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robot_noarchive"
											           id="post_robot_noarchive" <?php echo ltichk( 'post_robot_noarchive' ); ?>/>
											</td>
										</tr>
										<tr>
											<td><?php echo ltint( 'opt.robot_nosnippet' ); ?></td>
											<td><input type="checkbox" name="robot_nosnippet"
											           id="robot_nosnippet" <?php echo ltichk( 'robot_nosnippet' ); ?>/>
											</td>
											<td><input type="checkbox" name="post_robot_nosnippet"
											           id="post_robot_nosnippet" <?php echo ltichk( 'post_robot_nosnippet' ); ?>/>
											</td>
										</tr>
										</tbody>
									</table>
								</div>

								<div class="input-group">
									<label><?php echo ltint( 'opt.group.robot2' ); ?></label>

									<div class="checkbox-group">
										<label for="robot_date_based"><?php echo ltint( 'opt.robot_date_based' ); ?>
											<input type="checkbox" name="robot_date_based"
											       id="robot_date_based" <?php echo ltichk( 'robot_date_based' ); ?>/>
										</label>
										<label for="robot_cat_based"><?php echo ltint( 'opt.robot_cat_based' ); ?>
											<input type="checkbox" name="robot_cat_based"
											       id="robot_cat_based" <?php echo ltichk( 'robot_cat_based' ); ?>/>
										</label>
										<label for="robot_tag_based"><?php echo ltint( 'opt.robot_tag_based' ); ?>
											<input type="checkbox" name="robot_tag_based"
											       id="robot_tag_based" <?php echo ltichk( 'robot_tag_based' ); ?>/>
										</label>
										<label for="robot_tax_based"><?php echo ltint( 'opt.robot_tax_based' ); ?>
											<input type="checkbox" name="robot_tax_based"
											       id="robot_tax_based" <?php echo ltichk( 'robot_tax_based' ); ?>/>
										</label>
										<label for="robot_author_based"><?php echo ltint( 'opt.robot_author_based' ); ?>
											<input type="checkbox" name="robot_author_based"
											       id="robot_author_based" <?php echo ltichk( 'robot_author_based' ); ?>/>
										</label>
										<label for="robot_search_based"><?php echo ltint( 'opt.robot_search_based' ); ?>
											<input type="checkbox" name="robot_search_based"
											       id="robot_search_based" <?php echo ltichk( 'robot_search_based' ); ?>/>
										</label>
										<label
											for="robot_notfound_based"><?php echo ltint( 'opt.robot_notfound_based' ); ?>
											<input type="checkbox" name="robot_notfound_based"
											       id="robot_notfound_based" <?php echo ltichk( 'robot_notfound_based' ); ?>/>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<?php
								if ( get_option( 'blog_public' ) != 1 ):
									?>
									<div class="help-warning">
										<?php
										echo ltint( 'opt.hlp.robot0' );
										?>
									</div>
								<?php
								endif;
								?>

								<p><?php echo ltint( 'opt.hlp.robot1' ); ?></p>

								<p><?php echo ltint( 'opt.hlp.robot2' ); ?></p>
							</div>
						</div>
					</div>
				</div>
				<?php
				/***********************************************************************************************
				 *                                      FRONTPAGE TAB
				 ***********************************************************************************************/
				?>
				<div role="tabpanel" class="tab-pane" id="tab_frontpage">
					<div class="form-group">
						<div class="input-group">
							<label for="frontpage_description"><?php echo ltint( 'opt.frontpage_description' ); ?>
								<input type="checkbox" name="frontpage_description" data-toggle="seo-options"
								       data-target="#description_group"
								       id="frontpage_description" <?php echo ltichk( 'frontpage_description' ); ?>/>
							</label>

							<div id="description_group">
							<textarea name="frontpage_description_text"
							          id="frontpage_description_text"
							          placeholder="<?php echo get_bloginfo( 'description' ); ?>"><?php echo ltiopt( 'frontpage_description_text' ); ?></textarea>
							<span id="wfrontpage_description_text"
							      class="char-counter"><?php echo ltint( 'general.char_count' ); ?>&nbsp;<span
									id="cfrontpage_description_text"></span></span>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.frontpage_description1' ); ?></p>

								<p><?php echo ltint( 'opt.hlp.frontpage_description2' ); ?></p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="frontpage_keyword"><?php echo ltint( 'opt.frontpage_keyword' ); ?>
								<input type="checkbox" name="frontpage_keyword" data-toggle="seo-options"
								       data-target="#frontpage_keyword_group"
								       id="frontpage_keyword" <?php echo ltichk( 'frontpage_keyword' ); ?>/>
							</label>

							<div id="frontpage_keyword_group">
							<textarea name="frontpage_keyword_text"
							          id="frontpage_keyword_text"
							          placeholder="<?php echo ltint( 'opt.frontpage_keyword_ph' ); ?>"><?php echo ltiopt( 'frontpage_keyword_text' ); ?></textarea>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.frontpage_keyword' ); ?></p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label><?php echo ltint( 'opt.frontpage_robot' ); ?>
								<input type="checkbox" name="frontpage_robot" data-toggle="seo-options"
								       data-target="#frontpage_robot_group"
								       id="frontpage_robot" <?php echo ltichk( 'frontpage_robot' ); ?>/>
							</label>

							<div id="frontpage_robot_group">
								<div class="checkbox-group">
									<label
										for="frontpage_robot_noindex"><?php echo ltint( 'opt.frontpage_robot_noindex' ); ?>
										<input type="checkbox" name="frontpage_robot_noindex"
										       id="frontpage_robot_noindex" <?php echo ltichk( 'frontpage_robot_noindex' ); ?>/>
									</label>
									<label
										for="frontpage_robot_nofollow"><?php echo ltint( 'opt.frontpage_robot_nofollow' ); ?>
										<input type="checkbox" name="frontpage_robot_nofollow"
										       id="frontpage_robot_nofollow" <?php echo ltichk( 'frontpage_robot_nofollow' ); ?>/>
									</label>
									<label
										for="frontpage_robot_noodp"><?php echo ltint( 'opt.frontpage_robot_noodp' ); ?>
										<input type="checkbox" name="frontpage_robot_noodp"
										       id="frontpage_robot_noodp" <?php echo ltichk( 'frontpage_robot_noodp' ); ?>/>
									</label>
									<label
										for="frontpage_robot_noydir"><?php echo ltint( 'opt.frontpage_robot_noydir' ); ?>
										<input type="checkbox" name="frontpage_robot_noydir"
										       id="frontpage_robot_noydir" <?php echo ltichk( 'frontpage_robot_noydir' ); ?>/>
									</label>
									<label
										for="frontpage_robot_noarchive"><?php echo ltint( 'opt.frontpage_robot_noarchive' ); ?>
										<input type="checkbox" name="frontpage_robot_noarchive"
										       id="frontpage_robot_noarchive" <?php echo ltichk( 'frontpage_robot_noarchive' ); ?>/>
									</label>
									<label
										for="frontpage_robot_nosnippet"><?php echo ltint( 'opt.frontpage_robot_nosnippet' ); ?>
										<input type="checkbox" name="frontpage_robot_nosnippet"
										       id="frontpage_robot_nosnippet" <?php echo ltichk( 'frontpage_robot_nosnippet' ); ?>/>
									</label>
								</div>

							</div>

						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.frontpage_robots' ); ?></p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<h3><?php echo ltint( 'opt.json_ld' ); ?></h3>

						<div class="form-group">
							<div class="input-group">
								<label for="jsonld_entity_support"><?php echo ltint( 'opt.jsonld_entity_support' ); ?>
									<input type="checkbox" name="jsonld_entity_support" data-toggle="seo-options"
									       data-target="#jsonld_entity_support_group"
									       id="jsonld_entity_support" <?php echo ltichk( 'jsonld_entity_support' ); ?>/>
								</label>

								<div id="jsonld_entity_support_group">
									<div class="input-group">
										<label>
											<input name="jsonld_entity_type"
											       type="radio" <?php echo ltirad( 'jsonld_entity_type',
												'Person' ); ?>
											       value="Person"
											       id="jsonld_entity_person"
												/><?php echo ltint( 'opt.jsonld_entity_type' ); ?>
										</label>
										<label>
											<input name="jsonld_entity_type"
											       type="radio" <?php echo ltirad( 'jsonld_entity_type',
												'Organization' ); ?>
											       value="Organization"
											       id="jsonld_entity_organization"
												/><?php echo ltint( 'opt.jsonld_organization' ); ?>
										</label>
									</div>
									<div id="jsonld_entity_organization_group">
										<div class="input-group">
											<label for="jsonld_org_name"><?php echo ltint( 'opt.jsonld_org_name' ); ?>
												<input type="text" name="jsonld_org_name" id="jsonld_org_name"
												       value="<?php echo ltiopt( 'jsonld_org_name' ); ?>"/>
											</label>
										</div>
										<div class="input-group">
											<label
												for="jsonld_org_alternate_name"><?php echo ltint( 'opt.jsonld_org_alternate_name' ); ?>
												<input type="text" name="jsonld_org_alternate_name"
												       id="jsonld_org_alternate_name"
												       value="<?php echo ltiopt( 'jsonld_org_alternate_name' ); ?>"/>
											</label>
										</div>
										<div class="input-group">
											<label
												for="jsonld_org_website_url"><?php echo ltint( 'opt.jsonld_org_website_url' ); ?>
												<input type="text" name="jsonld_org_website_url"
												       id="jsonld_org_website_url"
												       value="<?php echo ltiopt( 'jsonld_org_website_url' ); ?>"/>
											</label>
										</div>
										<div class="input-group file-selector">
											<label for="jsonld_img"><?php echo ltint( 'opt.jsonld_img' ); ?></label>
											<input id="jsonld_img" class="upload_image" type="text" readonly="readonly"
											       name="jsonld_org_logo_url"
											       value="<?php echo ltiopt( 'jsonld_org_logo_url' ); ?>"/>

											<div class="btn-group">
												<input id="jsonld_img_button" class="upload_image_button button-primary"
												       type="button"
												       value="<?php echo ltint( 'general.choose_img' ); ?>"/>
												<input id="jsonld_reset" class="button-primary"
												       type="button"
												       value="<?php echo ltint( 'general.reset' ); ?>"/>
											</div>
											<input id="jsonld_img_id" type="hidden"
											       name="jsonld_org_logo_id"
											       value="<?php echo ltiopt( 'jsonld_org_logo_id' ); ?>"/>
										</div>
									</div>
									<div id="jsonld_entity_person_group">
										<div class="input-group">
											<label
												for="jsonld_person_wp_userid"><?php echo ltint( 'opt.jsonld_person_wp_userid' ); ?></label>
											<?php wp_dropdown_users( array(
												'show_option_none'  => 'None',
												'selected'          => ltiopt( 'jsonld_person_wp_userid' ),
												'class'             => 'form-select',
												'multi'             => true,
												'name'              => 'jsonld_person_wp_userid',
												'include_selected'  => true,
												'option_none_value' => 'None'
											) ); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'opt.hlp.jsonld' ); ?></p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<h3><?php echo ltint( 'opt.group.social' ); ?></h3>

								<div class="input-group">
									<label for="account_facebook"><?php echo ltint( 'opt.account_facebook' ); ?>
										<input type="text" name="account_facebook" id="account_facebook"
										       value="<?php echo ltiopt( 'account_facebook' ); ?>"/>
									</label>
									<label for="account_twitter"><?php echo ltint( 'opt.account_twitter' ); ?>
										<input type="text" name="account_twitter" id="account_twitter"
										       value="<?php echo ltiopt( 'account_twitter' ); ?>"/>
									</label>
									<label for="account_gplus"><?php echo ltint( 'opt.account_gplus' ); ?>
										<input type="text" name="account_gplus" id="account_gplus"
										       value="<?php echo ltiopt( 'account_gplus' ); ?>"/>
									</label>
									<label for="account_instagram"><?php echo ltint( 'opt.account_instagram' ); ?>
										<input type="text" name="account_instagram" id="account_instagram"
										       value="<?php echo ltiopt( 'account_instagram' ); ?>"/>
									</label>
									<label for="account_youtube"><?php echo ltint( 'opt.account_youtube' ); ?>
										<input type="text" name="account_youtube" id="account_youtube"
										       value="<?php echo ltiopt( 'account_youtube' ); ?>"/>
									</label>
									<label for="account_linkedin"><?php echo ltint( 'opt.account_linkedin' ); ?>
										<input type="text" name="account_linkedin" id="account_linkedin"
										       value="<?php echo ltiopt( 'account_linkedin' ); ?>"/>
									</label>
									<label for="account_myspace"><?php echo ltint( 'opt.account_myspace' ); ?>
										<input type="text" name="account_myspace" id="account_myspace"
										       value="<?php echo ltiopt( 'account_myspace' ); ?>"/>
									</label>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'opt.hlp.account_social' ); ?></p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label for="jsonld_website_support"><?php echo ltint( 'out.jsonld_website_support' ); ?>
									<input type="checkbox" name="jsonld_website_support" data-toggle="seo-options"
									       data-target="#jsonld_website_group"
									       id="jsonld_website_support" <?php echo ltichk( 'jsonld_website_support' ); ?>/>
								</label>

								<div id="jsonld_website_group">
									<div class="input-group">
										<label>
											<input name="jsonld_website_type"
											       type="radio" <?php echo ltirad( 'jsonld_website_type',
												'WebSite' ); ?>
											       value="WebSite"/><?php echo ltint( 'opt.jsonld_website_website' ); ?>
										</label>
										<label><input name="jsonld_website_type"
										              type="radio" <?php echo ltirad( 'jsonld_website_type',
												'Blog' ); ?>
										              value="Blog"/><?php echo ltint( 'opt.jsonld_website_blog' ); ?>
										</label>
									</div>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'opt.help.jsonld_website' ); ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<h3><?php echo ltint( 'opt.group.social_image' ); ?></h3>

							<div class="input-group file-selector">
								<label
									for="frontpage_social_img"><?php echo ltint( 'opt.frontpage_social_img' ); ?></label>
								<input id="frontpage_social_img" type="text" name="frontpage_social_img_url"
								       value="<?php echo ltiopt( 'frontpage_social_img_url' ); ?>"
								       readonly="readonly"/>

								<div class="btn-group">
									<input id="frontpage_social_img_button"
									       class="button-primary upload_image_button"
									       type="button"
									       value="<?php echo ltint( 'general.choose_img' ); ?>"/>
									<input id="frontpage_social_reset" class="button-primary"
									       type="button"
									       value="<?php echo ltint( 'general.reset' ); ?>"/>
								</div>
								<input id="frontpage_social_img_id" type="hidden"
								       name="frontpage_social_img_id"
								       value="<?php echo ltiopt( 'frontpage_social_img_id' ); ?>"/>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.frontpage_social' ); ?></p>
							</div>
						</div>
					</div>
				</div>
				<?php
				/***********************************************************************************************
				 *                             POSTS TAB
				 ***********************************************************************************************/
				?>
				<div role="tabpanel" class="tab-pane" id="tab_post">
					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="description_support"><?php echo ltint( 'opt.description_support' ); ?>
									<input type="checkbox" name="description_support"
									       id="description_support" <?php echo ltichk( 'description_support' ); ?>/>
								</label>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.description' ); ?></p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<h3><?php echo ltint( 'opt.json_ld' ); ?></h3>

						<div class="form-group">
							<div class="input-group">
								<label for="jsonld_page_support"><?php echo ltint( 'opt.jsonld_page_support' ); ?>
									<input type="checkbox" name="jsonld_page_support"
									       id="jsonld_page_support" <?php echo ltichk( 'jsonld_page_support' ); ?>/>
								</label>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'opt.hlp.jsonld_page' ); ?></p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label for="jsonld_post_support"><?php echo ltint( 'opt.jsonld_post_support' ); ?>
									<input type="checkbox" name="jsonld_post_support"
									       id="jsonld_post_support" <?php echo ltichk( 'jsonld_post_support' ); ?>
									       data-toggle="seo-options"
									       data-target="#jsonld_post_support_group"/>
								</label>

								<div id="jsonld_post_support_group">
									<div class="input-group">
										<label>
											<input name="jsonld_post_type"
											       type="radio" <?php echo ltirad( 'jsonld_post_type', 'Article' ); ?>
											       value="Article"/><?php echo ltint( 'opt.jsonld_post_article' ); ?>
										</label>
										<label><input name="jsonld_post_type"
										              type="radio" <?php echo ltirad( 'jsonld_post_type',
												'BlogPosting' ); ?>
										              value="BlogPosting"/><?php echo ltint( 'opt.jsonld_post_blogposting' ); ?>
										</label>
										<label><input name="jsonld_post_type"
										              type="radio" <?php echo ltirad( 'jsonld_post_type',
												'NewsArticle' ); ?>
										              value="NewsArticle"/><?php echo ltint( 'opt.jsonld_post_news' ); ?>
										</label>
										<label><input name="jsonld_post_type"
										              type="radio" <?php echo ltirad( 'jsonld_post_type',
												'ScholarlyArticle' ); ?>
										              value="ScholarlyArticle"/><?php echo ltint( 'opt.jsonld_post_scholar' ); ?>
										</label>
										<label><input name="jsonld_post_type"
										              type="radio" <?php echo ltirad( 'jsonld_post_type',
												'TechArticle' ); ?>
										              value="TechArticle"/><?php echo ltint( 'opt.jsonld_post_tech' ); ?>
										</label>
									</div>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'opt.hlp.jsonld_post1' ); ?></p>

									<p><?php echo ltint( 'opt.hlp.jsonld_post2' ); ?></p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<label for="jsonld_author_support"><?php echo ltint( 'opt.jsonld_author_support' ); ?>
									<input type="checkbox" name="jsonld_author_support"
									       id="jsonld_author_support" <?php echo ltichk( 'jsonld_author_support' ); ?>/>
								</label>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'opt.hlp.jsonld_author' ); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				/***********************************************************************************************
				 *                             SOCIAL TAB
				 ***********************************************************************************************/
				?>
				<div role="tabpanel" class="tab-pane" id="tab_social">
					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="open_graph_support"><?php echo ltint( 'opt.open_graph_support' ); ?>
									<input type="checkbox" name="open_graph_support" data-toggle="seo-options"
									       data-target="#fb_publisher_group"
									       id="open_graph_support" <?php echo ltichk( 'open_graph_support' ); ?> />
								</label>
							</div>
							<div id="fb_publisher_group">
								<label for="facebook_publisher"><?php echo ltint( 'opt.facebook_publisher' ); ?>
									<input type="text" name="facebook_publisher" id="facebook_publisher"
									       value="<?php echo ltiopt( 'facebook_publisher' ); ?>"
									       placeholder="https://www.facebook.com/publisher"/>
								</label>
							</div>
						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.open_graph1' ); ?></p>
								<ul>
									<li><?php echo ltint( 'opt.hlp.open_graph2' ); ?></li>
									<li><?php echo ltint( 'opt.hlp.open_graph3' ); ?></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="input-group">
							<div class="checkbox">
								<label for="twitter_card_support"><?php echo ltint( 'opt.twitter_card_support' ); ?>
									<input type="checkbox" name="twitter_card_support" data-toggle="seo-options"
									       data-target="#twitter_card_group"
									       id="twitter_card_support" <?php echo ltichk( 'twitter_card_support' ); ?> />
								</label>
							</div>
							<div id="twitter_card_group" class="input-group">
								<label>
									<input name="twitter_card_type"
									       type="radio" <?php echo ltirad( 'twitter_card_type', 'summary' ); ?>
									       value="summary"/><?php echo ltint( 'opt.twitter_card_summary' ); ?>
								</label>
								<label>
									<input name="twitter_card_type"
									       type="radio" <?php echo ltirad( 'twitter_card_type',
										'summary_large_image' ); ?>
									       value="summary_large_image"/><?php echo ltint( 'opt.twitter_card_large' ); ?>
								</label>
								<label for="twitter_publisher"><?php echo ltint( 'opt.twitter_publisher' ); ?>
									<input type="text" name="twitter_publisher" id="twitter_publisher"
									       value="<?php echo ltiopt( 'twitter_publisher' ); ?>"
									       placeholder="@publisher" required="required"/>
								</label>
							</div>

						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.twitter_cards1' ); ?></p>
								<ul>
									<li><?php echo ltint( 'opt.hlp.twitter_cards2' ); ?></li>
									<li><?php echo ltint( 'opt.hlp.twitter_cards3' ); ?></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<label for="gplus_publisher"><?php echo ltint( 'opt.gplus_publisher' ); ?></label>
							<input type="text" name="gplus_publisher" id="gplus_publisher"
							       value="<?php echo ltiopt( 'gplus_publisher' ); ?>"
							       placeholder="https://plus.google.com/+Editor/"/>

						</div>
						<div class="form-help-container">
							<div class="form-help">
								<p><?php echo ltint( 'opt.hlp.gplus' ); ?></p>
							</div>
						</div>
					</div>
				</div>
				<?php
				/***********************************************************************************************
				 *                             GOOGLE TAB
				 ***********************************************************************************************/
				/**
				 * @var $this \Lti\Seo\Admin
				 */
				if ($this->google->can_send_curl_requests): ?>
				<div role="tabpanel" class="tab-pane" id="tab_google">
					<div class="form-group">
						<?php
						/***********************************************************************************************
						 *                              NOT AUTHENTICATED YET
						 ***********************************************************************************************/

						if ( ! $this->google->helper->is_authenticated() ): ?>
							<div class="input-group">
								<div class="btn-group">
									<input id="btn-get-google-auth" class="button-primary" type="button"
									       value="<?php echo ltint( 'btn.google.get_auth' ); ?>"/>
									<input id="google_auth_url" type="hidden"
									       value="<?php echo esc_url( $this->google->helper->get_authentication_url() ); ?>"/>
								</div>

								<div class="btn-group">
									<input type="text" name="google_auth_token"
									       id="google_auth_token"
									       placeholder="<?php echo ltint( 'in.google.cp_token' ); ?>"/>
									<input id="btn-google-log-in" class="button-primary" type="submit"
									       name="lti_seo_google_auth"
									       value="<?php echo ltint( 'btn.google.log_in' ); ?>"/>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'hlp.google.log_in' ); ?></p>

									<p><?php echo ltint( 'hlp.google.log_in1' ); ?></p>
									<ol>
										<li><?php echo ltint( 'hlp.google.log_in2' ); ?></li>
										<li><?php echo ltint( 'hlp.google.log_in3' ); ?></li>
									</ol>
								</div>
							</div>
						<?php
						/***********************************************************************************************
						 *                           AUTHENTICATED
						 ***********************************************************************************************/
						else:
							$site = $this->google->get_site_info();
							?>
							<div class="input-group">
								<div class="btn-group">
									<?php if ( $site->is_listed === true ): ?>
										<?php if ( $site->site->is_site_unverified_user() ): ?>
											<input id="btn-verify" class="button-primary btn-verify" type="submit"
											       name="lti_seo_google_verify"
											       value="<?php echo ltint( 'btn.google.verify' ); ?>"/>
										<?php else: ?>
											<p><strong><?php echo ltint( 'msg.google.verified' ); ?></strong></p>
											<p>
												<a href="<?php echo $this->google->helper->get_site_console_url( esc_url( $this->helper->get_home_url() ),
													get_locale() ); ?>"
												   target="_blank"><?php echo ltint( 'msg.google.go_to_console' ); ?></a>
											</p>
										<?php endif; ?>
									<?php else: ?>
										<input id="btn-verify" class="button-primary btn-add" type="submit"
										       name="lti_seo_google_add"
										       value="<?php echo ltint( 'btn.google.add' ); ?>"/>
									<?php endif; ?>
									<input id="btn-log-out" class="button-primary" type="submit"
									       name="lti_seo_google_logout"
									       value="<?php echo ltint( 'btn.google.log-out' ); ?>"/>
								</div>
							</div>
							<div class="form-help-container">
								<div class="form-help">
									<p><?php echo ltint( 'hlp.google.logged_in' ); ?></p>
									<ul>
										<li><p><?php echo ltint( 'hlp.google.logged_in1' ); ?></p></li>
										<li><?php echo ltint( 'hlp.google.logged_in3' ); ?></li>
										<li><p><?php echo ltint( 'hlp.google.logged_in4' ); ?></p><p><strong><?php echo ltint( 'hlp.google.logged_in5' ); ?></strong></p></li>
									</ul>
									<?php echo ltint( 'hlp.google.logged_in6' ); ?>
								</div>
							</div>
						<?php endif; ?>
						<?php endif; ?>
						<?php if ( ! is_null( $this->google->error ) ): ?>
							<div class="google_errors">
								<p class="error_msg"><?php echo $this->google->error['error']; ?></p>

								<p class="error_msg"><?php echo $this->google->error['google_response']; ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="form-group-submit">
				<div class="button-group-submit">
					<input id="in-seopt-submit" class="button-primary" type="submit" name="lti_seo_update"
					       value="<?php echo ltint( 'general.save_changes' ); ?>"/>
					<input id="in-seopt-reset" class="button-primary" type="submit" name="lti_seo_reset"
					       value="<?php echo ltint( 'general.reset_defaults' ); ?>"/>
				</div>
			</div>
		</form>
	</div>
</div>