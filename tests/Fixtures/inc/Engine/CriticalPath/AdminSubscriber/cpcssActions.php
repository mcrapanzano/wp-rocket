<?php

return [
	'vfs_dir'   => 'public/',

	// Virtual filesystem structure.
	'structure' => [
		'wp-content' => [
			'cache'   => [
				'critical-css' => [
					'1' => [
						'posts' => [
							'post-2.css' => '.p { color: red; }',
						],
					],
				],
			],
			'plugins' => [
				'wp-rocket' => [
					'views' => [
						'metabox' => [
							'cpcss' => [
								'generate.php'   => file_get_contents( WP_ROCKET_PLUGIN_ROOT . 'views/metabox/cpcss/generate.php' ),
							],
						],
					],
				],
			],
		],
	],
	// Test Data
	'test_data' => [
		'testShouldDisplayGenerateTemplateOptionDisabled' => [
			'config'   => [
				'options'            => [
					'async_css' => 0,
				],
				'post'               => [
					'post_status' => 'draft',
					'post_type'   => 'post',
					'ID'          => 1,
				],
				'is_option_excluded' => true,
			],
			'expected' => '<p class="cpcss_generate ">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate hidden">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" disabled="disabled">
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Generate Specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate hidden">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" disabled="disabled">Revert back to the default CPCSS</button>
			</div>',
		],
		'testShouldDisplayRegenerateTemplateOptionDisabled' => [
			'config'   => [
				'options'            => [
					'async_css' => 0,
				],
				'post'               => [
					'post_status' => 'draft',
					'post_type'   => 'post',
					'ID'          => 2,
				],
				'is_option_excluded' => true,
			],
			'expected' => '<p class="cpcss_generate hidden">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate ">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" disabled="disabled">
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Regenerate specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate ">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" disabled="disabled">Revert back to the default CPCSS</button>
			</div>',
		],
		'testShouldDisplayGenerateTemplatePostNotPublished' => [
			'config'   => [
				'options'            => [
					'async_css' => 1,
				],
				'post'               => [
					'post_status' => 'draft',
					'post_type'   => 'post',
					'ID'          => 1,
				],
				'is_option_excluded' => true,
			],
			'expected' => '<p class="cpcss_generate ">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate hidden">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" disabled="disabled">
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Generate Specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate hidden">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" disabled="disabled">Revert back to the default CPCSS</button>
			</div>',
		],
		'testShouldDisplayReenerateTemplatePostNotPublished' => [
			'config'   => [
				'options'            => [
					'async_css' => 1,
				],
				'post'               => [
					'post_status' => 'draft',
					'post_type'   => 'post',
					'ID'          => 2,
				],
				'is_option_excluded' => true,
			],
			'expected' => '<p class="cpcss_generate hidden">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate ">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" disabled="disabled">
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Regenerate specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate ">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" disabled="disabled">Revert back to the default CPCSS</button>
			</div>',
		],
		'testShouldDisplayGenerateTemplateOptionExcludedFromPost' => [
			'config'   => [
				'options'            => [
					'async_css' => 1,
				],
				'post'               => [
					'post_status' => 'publish',
					'post_type'   => 'post',
					'ID'          => 1,
				],
				'is_option_excluded' => true,
			],
			'expected' => '<p class="cpcss_generate ">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate hidden">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" disabled="disabled">
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Generate Specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate hidden">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" disabled="disabled">Revert back to the default CPCSS</button>
			</div>',
		],
		'testShouldDisplayRegenerateTemplateOptionExcludedFromPost' => [
			'config'   => [
				'options'            => [
					'async_css' => 1,
				],
				'post'               => [
					'post_status' => 'publish',
					'post_type'   => 'post',
					'ID'          => 2,
				],
				'is_option_excluded' => true,
			],
			'expected' => '<p class="cpcss_generate hidden">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate ">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" disabled="disabled">
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Regenerate specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate ">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" disabled="disabled">Revert back to the default CPCSS</button>
			</div>',
		],
		'testShouldDisplayGenerateTemplate'               => [
			'config'   => [
				'options'            => [
					'async_css' => 1,
				],
				'post'               => [
					'post_status' => 'publish',
					'post_type'   => 'post',
					'ID'          => 1,
				],
				'is_option_excluded' => false,
			],
			'expected' => '<p class="cpcss_generate ">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate hidden">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" >
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Generate Specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate hidden">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" >Revert back to the default CPCSS</button>
			</div>',
		],
		'testShouldDisplayRegenerateTemplate'             => [
			'config'   => [
				'options'            => [
					'async_css' => 1,
				],
				'post'               => [
					'post_status' => 'publish',
					'post_type'   => 'post',
					'ID'          => 2,
				],
				'is_option_excluded' => false,
			],
			'expected' => '<p class="cpcss_generate hidden">
				Generate specific Critical Path CSS for this post.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<p class="cpcss_regenerate ">
				This post uses specific Critical Path CSS.<a href="" target="_blank" rel="noopener noreferrer">More info</a>
			</p>
			<div class="components-panel__row cpcss_generate cpcss_regenerate">
				<button id="rocket-generate-post-cpss" class="button components-button is-secondary" >
					<span class="spinner"></span>
					<span class="rocket-generate-post-cpss-btn-txt">Regenerate specific CPCSS</span>
				</button>
			</div>
			<div class="components-panel__row cpcss_regenerate ">
				<button id="rocket-delete-post-cpss" class="components-button is-link is-destructive" >Revert back to the default CPCSS</button>
			</div>',
		],
	],
];