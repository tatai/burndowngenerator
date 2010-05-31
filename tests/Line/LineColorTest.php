<?php
/* 
 * Yet another burndown online generator, http://www.burndowngenerator.com
 * Copyright (C) 2010 Francisco José Naranjo <fran.naranjo@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once (dirname(__FILE__) . '/../test_startup.php');

class LineColorTest extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function whenColorIsBiggerThanDecimal255GivesNotValidData() {
		$colorLine = new LineColor(new Decimal(300), new Decimal(302), new Decimal(304));
		
		$this->assertFalse($colorLine->isValid());
	}

	/**
	 * @test
	 */
	public function whenColorIsSmallerThanDecimal0GivesNotValidData() {
		$colorLine = new LineColor(new Decimal(-25), new Decimal(-32), new Decimal(-45));
		
		$this->assertFalse($colorLine->isValid());
	}

	/**
	 * @test
	 */
	public function whenColorIsNotNumericReturnsDecimal0() {
		$colorLine = new LineColor(new Decimal(null), new Decimal(false), new Decimal('er'));
		
		$this->assertFalse($colorLine->isValid());
	}

	/**
	 * @test
	 */
	public function acceptsHexadecimalValuesAndReturnsDecimal() {
		$colorLine = new LineColor(new Hexadecimal('a'), new Hexadecimal('aa'), new Hexadecimal('ff'));
		
		$this->assertEquals(10 / 255, $colorLine->red());
		$this->assertEquals(170 / 255, $colorLine->green());
		$this->assertEquals(255 / 255, $colorLine->blue());
	}
	
	/**
	 * @test
	 */
	public function dataReturnedIsBaseOne() {
		$red = new Decimal(0);
		$green = new Decimal(200);
		$blue = new Decimal(255);
		$colorLine = new LineColor($red, $green, $blue);

		$this->assertEquals($red->decimal() / 255, $colorLine->red());
		$this->assertEquals($green->decimal() / 255, $colorLine->green());
		$this->assertEquals($blue->decimal() / 255, $colorLine->blue());
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');