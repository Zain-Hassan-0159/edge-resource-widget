<?php

/**
 * resource Form
 *
 * @package           resource Form
 * @author            Zain Hassan
 *
 */
   


/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class resource_widget_elementore  extends \Elementor\Widget_Base {
	
	
	/**
	 * Get widget name.
	 *
	 * Retrieve company widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'resource';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve company widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Resource', 'resource-widget' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve company widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-heart';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget help URL.
	 */
	public function get_custom_help_url() {
		return 'https://developers.elementor.com/widgets/';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the company of categories the company widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'el-custom' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the company of keywords the company widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'resource', 'widgets', 'custom', 'resource widgets' ];
	}

	public function get_all_categories(){
		$categories = get_categories(array(
			'orderby' => 'name',
			'hide_empty' => false
		));
		$allcat = [];
		foreach ($categories as $category) {
			$allcat[$category->slug] = $category->name;
		}
		return $allcat;
	}


	/**
	 * Register company widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'resource', 'resource-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
			

		$this->add_control(
			'post_per_page',
			[
				'label'     => esc_html__('Posts Per Page', 'resource-widget'),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => -1,
				'default'       => 18,
				'step'      => 1,
			]
		);

		$this->add_control(
			'select_categories',
			[
				'label' => esc_html__( 'Select Categories', 'resource-widget' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => $this->get_all_categories(),
				'default' => [],
			]
		);

		$this->add_responsive_control(
			'total_columns',
			[
				'type' => \Elementor\Controls_Manager::SLIDER,
				'label' => esc_html__( 'Total Columns', 'resource-widget' ),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 4,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 3,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .resource' => 'columns: {{SIZE}};'
				],
				'condition' => [
					'select_layout' => 'three',
				],
			]
		);

		
		$this->end_controls_section();

        $this->start_controls_section(
			'resource_style_section',
			[
				'label' => __( 'Style', 'resource-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Categories', 'resource-widget' ),
				'selector' => '{{WRAPPER}} #resource_center .categories .category',
			]
		);
		
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_cat_typography',
				'label' => __( 'Categories Title', 'resource-widget' ),
				'selector' => '{{WRAPPER}} #resource_center span',
			]
		);
		
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title', 'resource-widget' ),
				'selector' => '{{WRAPPER}} #resource_center h2',
			]
		);

			
		$this->add_control(
			'card_bg_color3',
			[
				'label' => esc_html__( 'Background Color', 'resource-widget' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => ' #242a42',
				'selectors' => [
					'body' => 'background-color: {{VALUE}} !important;'
				]
			]
		);

		
		$this->end_controls_section();


	}

	/**
	 * Render company widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// echo "<pre>";
		// var_dump($settings['posts_include_terms']);
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $settings['post_per_page'],
			'category_name' => implode(",", $settings['select_categories']) // Replace with the desired category slugs separated by commas
        );
        
        $query = new WP_Query($args);
        $posts = array();
        if ($query->have_posts()) {

            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $post_title = get_the_title();
                $post_permalink = get_permalink();
                $post_image = get_the_post_thumbnail_url($post_id, 'full');
                
                // Get the category names
                $category_names = array();
                $categories = get_the_category();
                foreach ($categories as $category) {
                    array_push($category_names, $category->name);
                }
                $category_names = implode(', ', $category_names);
        
                $post_data = array(
                    'title' => $post_title,
                    'permalink' => $post_permalink,
                    'image' => $post_image,
                    'categories' => $category_names // Add the category names to the $post_data array
                );
        
                array_push($posts, $post_data);
            }
        
            wp_reset_postdata();
        
            // Do something with the $posts array
        }
        if(!empty($posts)) {
            $no_of_posts = count($posts);
            $no_of_iterations = $no_of_posts > 9 ? intval($no_of_posts / 9) : 1;
         //   print_r($settings['select_categories']);
            ?>
            <style>

				#resource_center a{
					color: white;
				}
                #resource_center .categories{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 25px 0;
                }
                #resource_center .categories .category{
                    border: 1px solid #8b93b3;
                    background: none;
                    padding: 4px 15px;
                    color: #8b93b3;
                    margin: 10px;
					cursor: pointer;
					font-size: 16px;
                }
                #resource_center .categories .category.active{
                    border: 1px solid #23aa95 !important;
                    background: none;
                    padding: 4px 15px;
                    color: white;
                    margin: 10px;
                }
                #resource_center .categories .category.active:focus, #resource_center .categories .category.active:focus-visible{
                    border: none;
					outline: none;
                }
                #resource_center .left, #resource_center .top, #resource_center .bottom {
                    background-color: #121727;
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: cover;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    color: white;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                        -ms-flex-direction: column;
                            flex-direction: column;
                    -webkit-box-pack: end;
                        -ms-flex-pack: end;
                            justify-content: end;
                    padding: 60px 0 20px;
                    flex: 1;
                    }
                    #resource_center .pattern_one {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    gap: 40px;
                    margin-bottom: 40px;
                    min-height: 660px;
                    }
                    #resource_center .pattern_one .right {
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                        -ms-flex-direction: column;
                            flex-direction: column;
                    }
                    #resource_center .text {
                    padding: 0 20px;
                    }
                    #resource_center .pattern_two {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    gap: 40px;
                    margin-bottom: 40px;
					min-height: 310px;
                    }
                    #resource_center .left, #resource_center .right {
                    -webkit-box-flex: 1;
                        -ms-flex: 1;
                            flex: 1;
                    }
                    #resource_center .right {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    gap: 40px;
                    }
                    #resource_center span {
						font-size: 16px;
						margin-bottom: 10px;
						display: inline-block;
                    }
                    #resource_center h2 {
                    margin: 0;
                    font-size: 25px;
                    }

					#resource_center .d-none{
						display: none;
					}

                    @media(max-width: 600px){
                        #resource_center .pattern_one, #resource_center .pattern_two, #resource_center .right{
                            flex-direction: column;
                        }
                        #resource_center .categories{
                            flex-wrap: wrap;
                        }
                    }
                    @media(max-width: 400px){
                        #resource_center .categories{
                            justify-content: initial;
                        }
                    }
            </style>
            <div id="resource_center" class="resource_center">
                <div class="categories">
                    <button class="category active" onClick="filter_Posts(event, 'all')" >All</button>
                    <?php
					$categories = $this->get_all_categories();
					if(!empty($settings['select_categories'])){
						foreach ($settings['select_categories'] as $category) {
							?>
							<button class="category" onClick="filter_Posts(event, '<?php echo $category; ?>')" ><?php echo $categories[$category]; ?></button>
							<?php
							
						}
					}
                    ?>
                </div>
                <div class="posts all">
                    <?php
                    $j = 0;
                    for ($i = 1; $i <= $no_of_iterations; $i++) {
                        ?>
                        <div class="pattern_one">
                            <div class="left" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(<?php echo $posts[$j]['image'] ?>);">
							<a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
								<span><?php echo $posts[$j]['categories']; ?></span>
								<h2><?php echo $posts[$j]['title'] ?></h2>
							</a>
                            </div>

                            <div class="right">
                                <?php  $j++; 
								if(array_key_exists($j, $posts)){
								?>
                                <div class="top" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(<?php echo $posts[$j]['image'] ?>);">
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
									</a>
                                </div>
                                <?php } $j++; 
								if(array_key_exists($j, $posts)){?>
                                <div class="bottom" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(<?php echo $posts[$j]['image'] ?>);">
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
								</a>
                                </div>
                            </div>
                        </div>
                        <div class="pattern_two">
                            <div class="right">
                                <?php } $j++; 
								if(array_key_exists($j, $posts)){?>
                                <div class="top" >
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
								</a>
                                </div>
                                <?php } $j++; 
								if(array_key_exists($j, $posts)){?>
                                <div class="bottom" >
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
								</a>
                                </div>
                            </div>
                            <?php } $j++; 
							if(array_key_exists($j, $posts)){?>
                            <div class="left" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(<?php echo $posts[$j]['image'] ?>);">
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
								</a>
                            </div>
                        </div>
                        <div class="pattern_one">
                            <div class="right">
                                <?php } $j++; 
								if(array_key_exists($j, $posts)){?>
                                <div class="top" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(<?php echo $posts[$j]['image'] ?>);">
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
								</a>
                                </div>
                                <?php } $j++; 
								if(array_key_exists($j, $posts)){?>
                                <div class="bottom" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(<?php echo $posts[$j]['image'] ?>);">
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
								</a>
                                </div>
                            </div>
                            <?php } $j++; 
							if(array_key_exists($j, $posts)){?>
                            <div class="left" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(<?php echo $posts[$j]['image'] ?>);">
                                <a href="<?php echo $posts[$j]['permalink'] ?>" class="text">
									<span><?php echo $posts[$j]['categories']; ?></span>
									<h2><?php echo $posts[$j]['title'] ?></h2>
								</a>
                            </div>
                        </div>
                        <?php } $j++; 
                      }
                      
                    ?>
                </div>
            </div>
			<script>
				function filter_Posts(event, category){
					jQuery("#resource_center .categories .category.active").removeClass("active");
					event.target.classList.add("active");

					jQuery("#resource_center .posts").addClass('d-none');
					if(jQuery("#resource_center .posts." + category).length !== 0){
						jQuery("#resource_center .posts").addClass('d-none');
						jQuery("#resource_center .posts." + category).removeClass('d-none');
						return;
					}
		
					let all_posts = window.posts;
					all_posts = all_posts[`${category}`];
					let HTML_s = '';
					let no_of_posts = all_posts.length;
            		let no_of_iterations = no_of_posts > 9 ? Math.floor(no_of_posts / 9) : 1;
					let inc = 0;
					let patterns = ''
                    for (i = 1; i <= no_of_iterations; i++) {
                        pat_one = `<div class="pattern_one">
								<div class="left" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(${all_posts[inc]['image']});">
								<a href="${all_posts[inc]['permalink']}" class="text">
									<span>${all_posts[inc]['categories']}</span>
									<h2>${all_posts[inc]['title']}</h2>
								</a>
								</div>
								<div class="right">`;
									inc++ 
								pat_one +=	all_posts[inc] ? `<div class="top" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(${all_posts[inc]['image']});">
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
										</a>
									</div>` : '';
									inc++
									pat_one +=	all_posts[inc] ? `<div class="bottom" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(${all_posts[inc]['image']});">
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
									</a>
									</div>
								</div>
							</div>
							<div class="pattern_two">
								<div class="right">` : '';
									inc++
									pat_one +=	all_posts[inc] ? `<div class="top" >
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
									</a>
									</div>` : '';
									inc++
									pat_one +=	all_posts[inc] ? `<div class="bottom" >
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
									</a>
									</div>
								</div>` : '';
								inc++
								pat_one +=	all_posts[inc] ? `<div class="left" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(${all_posts[inc]['image']});">
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
									</a>
								</div>
							</div>
							<div class="pattern_one">
								<div class="right">` : '';
									inc++
									pat_one +=	all_posts[inc] ? `<div class="top" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(${all_posts[inc]['image']});">
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
									</a>
									</div>` : '';
									inc++
									pat_one +=	all_posts[inc] ? `<div class="bottom" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(${all_posts[inc]['image']});">
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
									</a>
									</div>
								</div>` : '';
								inc++
								pat_one +=	all_posts[inc] ? `<div class="left" style="background-image: linear-gradient(rgb(0 0 0 / 5%), rgb(0 0 0 / 80%)), url(${all_posts[inc]['image']});">
									<a href="${all_posts[inc]['permalink']}" class="text">
										<span>${all_posts[inc]['categories']}</span>
										<h2>${all_posts[inc]['title']}</h2>
									</a>
								</div>
							</div>` : '';
							inc++

						patterns += pat_one;
                    }

					HTML_s = `<div class="posts ${category}">
						${patterns}
					</div>`;
                      
					
					jQuery("#resource_center").append(HTML_s);


				}

				jQuery.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					type: 'post',
					data: { action: 'all_post_category_wise' },
					success: function(data) {
						window.posts = JSON.parse(data);
					}
				});
			</script>
            <?php
        }

	}


}