<?php
/**
 * RunningStat
 *
 * Compute running mean, variance, and extrema of a stream of numbers.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Ori Livneh <ori@wikimedia.org>
 */

use RunningStat\RunningStat;

/**
 * @covers RunningStat\RunningStat
 */
class RunningStatTest extends \PHPUnit_Framework_TestCase {

	public $points = array(
		49.7168, 74.3804,  7.0115, 96.5769, 34.9458,
		36.9947, 33.8926, 89.0774, 23.7745, 73.5154,
		86.1322, 53.2124, 16.2046, 73.5130, 10.4209,
		42.7299, 49.3330, 47.0215, 34.9950, 18.2914,
	);

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
		$variance = array_sum( array_map( function ( $x ) use ( $mean ) {
			return pow( $mean - $x, 2 );
		}, $this->points ) ) / ( $rstat->getCount() - 1 );
		$stddev = sqrt( $variance );

		$this->assertEquals( $rstat->getCount(), count( $this->points ) );
		$this->assertEquals( $rstat->min, min( $this->points ) );
		$this->assertEquals( $rstat->max, max( $this->points ) );
		$this->assertEquals( $rstat->getMean(), $mean );
		$this->assertEquals( $rstat->getVariance(), $variance );
		$this->assertEquals( $rstat->getStdDev(), $stddev );
	}

	/**
	 * @covers RunningStat\RunningStat::getVariance
	 */
	public function testGetVariance() {
		$rstat = new RunningStat();
		$this->assertTrue( is_nan( $rstat->getVariance() ), 'Empty set' );

		$rstat = new RunningStat();
		$rstat->addObservation( 7 );
		$this->assertEquals( $rstat->getVariance(), 0.0, 'One value' );
	}

	/**
	 * When one RunningStat instance is merged into another, the state of the
	 * target RunningInstance should have the state that it would have had if
	 * all the data had been accumulated by it alone.
	 *
	 * @covers RunningStat\RunningStat::merge
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

		$this->assertEquals( $first->getCount(), count( $this->points ) );
		$this->assertEquals( $first, $expected );
	}

	/**
	 * @covers RunningStat\RunningStat::merge
	 */
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

		$this->assertEquals( $first->getCount(), count( $this->points ) );
		$this->assertEquals( $first, $expected );
	}

	/**
	 * @covers RunningStat\RunningStat::merge
	 */
	public function testMergeEmpty() {
		$expected = new RunningStat();

		// Empty target and subject
		$first = new RunningStat();
		$second = new RunningStat();
		$first->merge( $second );

		$this->assertEquals( $first->getCount(), 0 );
		$this->assertEquals( $first, $expected );
	}
}
