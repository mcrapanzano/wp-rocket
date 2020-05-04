<?php

namespace WP_Rocket\Tests\Unit\inc\Engine\CriticalPath\AdminSubscriber;

use Mockery;
use Brain\Monkey\Functions;
use WP_Rocket\Admin\Options_Data;
use WP_Rocket\Engine\Admin\Beacon\Beacon;
use WP_Rocket\Engine\CriticalPath\AdminSubscriber;
use WP_Rocket\Tests\Unit\FilesystemTestCase;

/**
 * @covers \WP_Rocket\Engine\CriticalPath\AdminSubscriber::cpcss_section
 * @group  CriticalPath
 */
class Test_CpcssSection extends FilesystemTestCase {
	protected $path_to_test_data = '/inc/Engine/CriticalPath/AdminSubscriber/cpcssSection.php';
	protected static $mockCommonWpFunctionsInSetUp = true;

	private $beacon;
	private $options;
	private $subscriber;

	public function setUp() {
		parent::setUp();

		Functions\when( 'get_current_blog_id' )->justReturn( 1 );

		$this->beacon     = Mockery::mock( Beacon::class );
		$this->options    = Mockery::mock( Options_Data::class );
		$this->subscriber = new AdminSubscriber(
			$this->options,
			$this->beacon,
			'wp-content/cache/critical-css/',
			$this->filesystem->getUrl( 'wp-content/plugins/wp-rocket/views/metabox/cpcss' )
		);
	}

	private function getActualHtml() {
		ob_start();
		$this->subscriber->cpcss_section();

		return $this->format_the_html( ob_get_clean() );
	}

	/**
	 * @dataProvider providerTestData
	 */
	public function testShouldDisplayCPCSSSection( $config, $expected ) {
		$this->options->shouldReceive( 'get' )
			->with( 'async_css', 0 )
			->andReturn( $config['options']['async_css'] );

		$GLOBALS['post'] = (object) [
			'post_status' => $config['post']['post_status'],
			'ID'          => $config['post']['ID'],
		];

		Functions\when( 'get_post_meta' )->justReturn( $config['is_option_excluded'] );
		Functions\when( 'rest_url' )->justReturn( 'http://example.org/wp-rocket/v1/cpcss/post/' . $config['post']['ID'] );
		Functions\when( 'wp_create_nonce' )->justReturn( 'wp_rest_nonce' );

		$this->assertSame(
			$this->format_the_html( $expected ),
			$this->getActualHtml()
		);
		$this->assertSame( 1, did_action( 'rocket_metabox_cpcss_content' ) );
	}
}
