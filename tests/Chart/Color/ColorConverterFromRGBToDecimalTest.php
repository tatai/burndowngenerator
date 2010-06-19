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

class ColorConverterFromRGBToDecimalTest extends PHPUnit_Framework_TestCase {
	/**
	 * 
	 * @var ColorConverter
	 */
	private $_converter = null;

	public function setUp() {
		$this->_converter = new ColorConverter();
	}

	/**
	 * @test
	 */
	public function acceptsStringWithSixHexadecimalValues() {
		$this->assertTrue($this->_converter->fromRGBToColor('123abc') instanceof Color);
	}

	/**
	 * @test
	 */
	public function acceptsStringWithReducedFormatThreeHexadecimalValues() {
		$this->assertTrue($this->_converter->fromRGBToColor('1ac') instanceof Color);
	}

	/**
	 * @test
	 */
	public function sharpIsValidCharacterAtTheBegining() {
		$this->assertTrue($this->_converter->fromRGBToColor('#123abc') instanceof Color);
		$this->assertTrue($this->_converter->fromRGBToColor('#1ac') instanceof Color);
	}
	
	/**
	 * @test
	 */
	public function doNotAcceptNonHexadecimalValues() {
		$this->assertNull($this->_converter->fromRGBToColor('asg123'));
	}

	/**
	 * @test
	 */
	public function stringIsCaseInsensitive() {
		$this->assertTrue($this->_converter->fromRGBToColor('#123aBc') instanceof Color);
	}

	/**
	 * @test
	 */
	public function eachPairIsConvertedToDecimalBase1() {
		$red = '12';
		$green = '3a';
		$blue = 'bc';
		
		$value = $red . $green . $blue;
		
		$color = $this->_converter->fromRGBToColor($value);
		
		$this->assertEquals(hexdec($red) / 255, $color->red());
		$this->assertEquals(hexdec($green) / 255, $color->green());
		$this->assertEquals(hexdec($blue) / 255, $color->blue());
	}

	/**
	 * @test
	 */
	public function eachValueInReducedFormatIsConvertedToDecimalBase1() {
		$red = '1';
		$green = 'a';
		$blue = 'b';
		
		$value = $red . $green . $blue;
		
		$color = $this->_converter->fromRGBToColor($value);
		
		$this->assertEquals(hexdec($red . $red) / 255, $color->red());
		$this->assertEquals(hexdec($green . $green) / 255, $color->green());
		$this->assertEquals(hexdec($blue . $blue) / 255, $color->blue());
	}
	
	/**
	 * @test
	 */
	public function whenRGBIsNotValidReturnsNull() {
		$this->assertNull($this->_converter->fromRGBToColor('12gi3d'));
		$this->assertNull($this->_converter->fromRGBToColor(null));
	}
}

require_once (dirname(__FILE__) . '/../test_shutdown.php');