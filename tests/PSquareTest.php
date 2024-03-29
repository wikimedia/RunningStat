<?php

use PHPUnit\Framework\TestCase;
use Wikimedia\PSquare;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers Wikimedia\PSquare
 * @author Ori Livneh <ori@wikimedia.org>
 */
class PSquareTest extends TestCase {

	/**
	 * Test that the PSquare class implements the P-square algorithm
	 * correctly.
	 *
	 * The values for this test were derived from Table 1 ('An example
	 * of median calculation using P2 Algorithm') from the CACM paper,
	 * adjusted for zero-based marker indexing.
	 */
	public function testPSquare() {
		$ps = new PSquare( 0.5 );
		$ps = TestingAccessWrapper::newFromObject( $ps );

		// Push the initial observations
		$ps->addObservation( 0.02 );
		$ps->addObservation( 0.15 );
		$ps->addObservation( 0.74 );
		$ps->addObservation( 0.83 );
		$ps->addObservation( 3.39 );

		$ps->addObservation( 22.37 );
		$this->assertEquals( [ 0, 1, 2, 3, 5 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 1.25, 2.5, 3.75, 5 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.15, 0.74, 0.83, 22.37 ], $ps->heights, 0.1 );

		$ps->addObservation( 10.15 );
		$this->assertEquals( [ 0, 1, 2, 4, 6 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 1.5, 3, 4.5, 6 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.15, 0.74, 4.47, 22.37 ], $ps->heights, 0.1 );

		$ps->addObservation( 15.43 );
		$this->assertEquals( [ 0, 1, 3, 5, 7 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 1.75, 3.5, 5.25, 7 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.15, 2.18, 8.6, 22.37 ], $ps->heights, 0.1 );

		$ps->addObservation( 38.62 );
		$this->assertEquals( [ 0, 2, 4, 6, 8 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 2, 4, 6, 8 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.87, 4.75, 15.52, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 15.92 );
		$this->assertEquals( [ 0, 2, 4, 6, 9 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 2.25, 4.5, 6.75, 9 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.87, 4.75, 15.52, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 34.6 );
		$this->assertEquals( [ 0, 2, 5, 7, 10 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 2.5, 5, 7.5, 10 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.87, 9.28, 21.58, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 10.28 );
		$this->assertEquals( [ 0, 2, 5, 8, 11 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 2.75, 5.5, 8.25, 11 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.87, 9.28, 21.58, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 1.47 );
		$this->assertEquals( [ 0, 3, 6, 9, 12 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 3, 6, 9, 12 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 2.14, 9.28, 21.58, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 0.4 );
		$this->assertEquals( [ 0, 4, 7, 10, 13 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 3.25, 6.5, 9.75, 13 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 2.14, 9.28, 21.58, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 0.05 );
		$this->assertEquals( [ 0, 4, 7, 11, 14 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 3.5, 7, 10.5, 14 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.74, 6.3, 21.58, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 11.39 );
		$this->assertEquals( [ 0, 4, 7, 12, 15 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 3.75, 7.5, 11.25, 15 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.74, 6.3, 21.58, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 0.27 );
		$this->assertEquals( [ 0, 4, 8, 12, 16 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 4, 8, 12, 16 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.59, 6.3, 17.22, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 0.42 );
		$this->assertEquals( [ 0, 5, 9, 13, 17 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 4.25, 8.5, 12.75, 17 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.59, 6.3, 17.22, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 0.09 );
		$this->assertEquals( [ 0, 5, 9, 14, 18 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 4.5, 9, 13.5, 18 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.5, 4.44, 17.22, 38.62 ], $ps->heights, 0.1 );

		$ps->addObservation( 11.37 );
		$this->assertEquals( [ 0, 5, 9, 15, 19 ], $ps->positions );
		$this->assertEqualsWithDelta( [ 0, 4.75, 9.5, 14.25, 19 ], $ps->desired, 0.1 );
		$this->assertEqualsWithDelta( [ 0.02, 0.5, 4.44, 17.22, 38.62 ], $ps->heights, 0.1 );

		$this->assertEqualsWithDelta( 4.4406347, $ps->getValue(), 0.1 );

		$this->assertEquals( 20, $ps->getCount() );
	}

	public function getPercentile( $p, $values ) {
		sort( $values );
		$i = $p * count( $values );
		$j = floor( $i );
		return $i === $j
			? $values[$i - 1] + $values[$i] / 2
			: $values[$j];
	}

	/**
	 * Test that PSquare::getValue() works even when there are fewer
	 * than five observations.
	 */
	public function testPSquareFewObservations() {
		$ps = new PSquare( 0.5 );

		$ps->addObservation( 5 );
		$ps->addObservation( 10 );
		$ps->addObservation( 1 );

		// Simple median of an odd number of observations.
		$this->assertEquals( 3, $ps->getCount() );
		$this->assertEquals( 5, $ps->getValue() );

		$ps->addObservation( 20 );

		// Simple median of an even number of observations.
		$this->assertEquals( 4, $ps->getCount() );
		$this->assertEquals( 7.5, $ps->getValue() );
	}

	public function testCloneAndDeterminism() {
		$ps1 = new PSquare( 0.5 );
		$ps1->addObservation( 5 );
		$ps1->addObservation( 10 );
		$ps1->addObservation( 1 );
		$this->assertEquals( 3, $ps1->getCount() );
		$this->assertEquals( 5, $ps1->getValue() );

		$ps2 = unserialize( serialize( $ps1 ) );
		$this->assertEquals( 3, $ps2->getCount(), 'ps2 start count' );
		$this->assertEquals( 5, $ps2->getValue(), 'ps2 start value' );

		$ps1->addObservation( 20 );
		$ps2->addObservation( 20 );

		$this->assertEquals( 4, $ps1->getCount(), 'ps1 mod count' );
		$this->assertEquals( 4, $ps2->getCount(), 'ps2 mod count' );

		$this->assertEquals( 7.5, $ps1->getValue(), 'ps1 mod val' );
		$this->assertEquals( 7.5, $ps2->getValue(), 'ps2 mod val' );
	}

	public function testPSquareAccuracy() {
		// The numbers of customers affected in electrical blackouts
		// in the United States between 1984 and 2002.
		// Source: <http://tuvalu.santafe.edu/~aaronc/powerlaws/data.htm>
		$observations = [
			570000, 210882, 190000, 46000, 17000, 360000, 74000, 19000, 460000,
			65000, 18351, 25000, 25000, 63500, 1000, 9000, 50000, 114500, 350000,
			25000, 50000, 25000, 242910, 55000, 164500, 877000, 43000, 1140000,
			464000, 90000, 2100000, 385000, 95630, 166000, 71000, 100000, 234000,
			300000, 258000, 130000, 246000, 114000, 120000, 24506, 36073, 10000,
			160000, 600000, 12000, 203000, 50462, 40000, 59000, 15000, 1646,
			500000, 60000, 133000, 62000, 173000, 81000, 20000, 112000, 600000,
			24000, 37000, 238000, 50000, 50000, 147000, 32000, 40911, 30500,
			14273, 160000, 230000, 92000, 75000, 130000, 124000, 120000, 11000,
			235000, 50000, 94285, 240000, 870000, 70000, 50000, 50000, 18000,
			51000, 51000, 145000, 557354, 219000, 191000, 2900, 163000, 257718,
			1660000, 1600000, 1300000, 80000, 500000, 10000, 290000, 375000,
			95000, 725000, 128000, 148000, 100000, 2000, 48000, 18000, 5300,
			32000, 250000, 45000, 38500, 126000, 284000, 70000, 400000, 207200,
			39500, 363476, 113200, 1500000, 15000, 7500000, 8000, 56000, 88000,
			60000, 29000, 75000, 80000, 7500, 82500, 272000, 272000, 122000,
			145000, 660000, 50000, 92000, 60000, 173000, 106850, 25000, 146000,
			158000, 1500000, 40000, 100000, 300000, 1800, 300000, 70000, 70000,
			29000, 18819, 875000, 100000, 50000, 1500000, 650000, 58000, 142000,
			350000, 71000, 312000, 25000, 35000, 315000, 500000, 404000, 246000,
			43696, 71000, 65000, 29900, 30000, 20000, 899000, 10300, 490000,
			115000, 2085000, 206000, 400000, 26334, 598000, 160000, 91000, 33000,
			53000, 300000, 60000, 55000, 60000, 66005, 11529, 56000, 4150, 40000,
			320831, 30001, 200000
		];

		foreach ( [ 0.25, 0.50, 0.75 ] as $p ) {
			$ps = new PSquare( $p );

			foreach ( $observations as $observation ) {
				$ps->addObservation( $observation );
			}

			$actual = $this->getPercentile( $p, $observations );
			$estimate = $ps->getValue();
			$acceptableDelta = $actual * 0.10;
			$this->assertEqualsWithDelta( $actual, $estimate, $acceptableDelta );
		}
	}
}
