<?php

use PHPUnit\Framework\TestCase;
use Wikimedia\RunningStat;

/**
 * @covers Wikimedia\RunningStat
 * @author Ori Livneh <ori@wikimedia.org>
 */
class RunningStatTest extends TestCase {

	/** @var array */
	private array $points = [
		49.7168, 74.3804,  7.0115, 96.5769, 34.9458,
		36.9947, 33.8926, 89.0774, 23.7745, 73.5154,
		86.1322, 53.2124, 16.2046, 73.5130, 10.4209,
		42.7299, 49.3330, 47.0215, 34.9950, 18.2914,
	];

	/**
	 * Verify that the statistical moments and extrema computed by RunningStat
	 * match expected values.
	 */
	public function testRunningStatAccuracy() {
		$rstat = new RunningStat();
		foreach ( $this->points as $point ) {
			$rstat->addObservation( $point );
		}

		$mean = array_sum( $this->points ) / count( $this->points );
		$variance = array_sum( array_map( static function ( $x ) use ( $mean ) {
			return pow( $mean - $x, 2 );
		}, $this->points ) ) / ( $rstat->getCount() - 1 );
		$stddev = sqrt( $variance );

		$this->assertCount( $rstat->getCount(), $this->points );
		$this->assertEquals( $rstat->min, min( $this->points ) );
		$this->assertEquals( $rstat->max, max( $this->points ) );
		$this->assertEquals( $rstat->getMean(), $mean );
		$this->assertEqualsWithDelta( $rstat->getVariance(), $variance, 0.01 );
		$this->assertEqualsWithDelta( $rstat->getStdDev(), $stddev, 0.01 );
	}

	public function testGetVariance() {
		$rstat = new RunningStat();
		$this->assertTrue( is_nan( $rstat->getVariance() ), 'Empty set' );

		$rstat = new RunningStat();
		$rstat->addObservation( 7 );
		$this->assertSame( 0.0, $rstat->getVariance(), 'One value' );
	}

	/**
	 * When one RunningStat instance is merged into another, the state of the
	 * target RunningInstance should have the state that it would have had if
	 * all the data had been accumulated by it alone.
	 */
	public function testMergeTwo() {
		$expected = new RunningStat();
		foreach ( $this->points as $point ) {
			$expected->addObservation( $point );
		}

		// Split the data into two sets
		$sets = array_chunk( $this->points, floor( count( $this->points ) / 2 ) );

		// Accumulate the first half into one RunningStat object
		$first = new RunningStat();
		foreach ( $sets[0] as $point ) {
			$first->addObservation( $point );
		}

		// Accumulate the second half into another RunningStat object
		$second = new RunningStat();
		foreach ( $sets[1] as $point ) {
			$second->addObservation( $point );
		}

		// Merge the second RunningStat object into the first
		$first->merge( $second );

		$this->assertCount( $first->getCount(), $this->points );
		$this->assertEqualsWithDelta( $expected, $first, 0.01 );
	}

	public function testMergeOne() {
		$expected = new RunningStat();
		foreach ( $this->points as $point ) {
			$expected->addObservation( $point );
		}

		// Empty target
		$first = new RunningStat();

		$second = new RunningStat();
		foreach ( $this->points as $point ) {
			$second->addObservation( $point );
		}

		$first->merge( $second );

		$this->assertCount( $first->getCount(), $this->points );
		$this->assertEquals( $expected, $first );
	}

	public function testMergeEmpty() {
		$expected = new RunningStat();

		// Empty target and subject
		$first = new RunningStat();
		$second = new RunningStat();
		$first->merge( $second );

		$this->assertSame( 0, $first->getCount() );
		$this->assertEquals( $expected, $first );
	}
}
