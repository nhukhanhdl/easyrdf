<?php

/**
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2009-2010 Nicholas J Humfrey.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 3. The name of the author 'Nicholas J Humfrey" may be used to endorse or
 *    promote products derived from this software without specific prior
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    EasyRdf
 * @copyright  Copyright (c) 2009-2010 Nicholas J Humfrey
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @version    $Id$
 */

require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'TestHelper.php';

class EasyRdf_UtilsTest extends EasyRdf_TestCase
{

    public function testCameliseSimple()
    {
        $this->assertEquals(
            'Hello',
            EasyRdf_Utils::camelise('hEllO')
        );
    }

    public function testCameliseUnderscore()
    {
        $this->assertEquals(
            'HelloWorld',
            EasyRdf_Utils::camelise('hello_world')
        );
    }

    public function testCameliseDHyphen()
    {
        $this->assertEquals(
            'HelloWorld',
            EasyRdf_Utils::camelise('hello-world')
        );
    }

    public function testCameliseDoubleHyphen()
    {
        $this->assertEquals(
            'HelloWorld',
            EasyRdf_Utils::camelise('hello--world')
        );
    }

    public function testCameliseSpace()
    {
        $this->assertEquals(
            'HelloWorld',
            EasyRdf_Utils::camelise('hello  world')
        );
    }

    public function testCameliseFilePath()
    {
        $this->assertEquals(
            'IAmEvilPhp',
            EasyRdf_Utils::camelise('../../I/am/Evil.php')
        );
    }

    public function testCameliseEmpty()
    {
        $this->assertEquals(
            '',
            EasyRdf_Utils::camelise('')
        );
    }

    public function testIsAssoc()
    {
        $arr = array('foo' => 'bar');
        $this->assertTrue(EasyRdf_Utils::is_associative_array($arr));

    }

    public function testIsAssocNonArray()
    {
         $this->assertFalse(EasyRdf_Utils::is_associative_array('foo'));
    }

    public function testIsAssocArray()
    {
        $arr = array('foo', 'bar');
        $this->assertFalse(EasyRdf_Utils::is_associative_array($arr));
    }

    public function testIsAssocIntAppend()
    {
        $arr = array('foo' => 'bar');
        array_push($arr, 'rat');
        $this->assertTrue(EasyRdf_Utils::is_associative_array($arr));
    }

    public function testIsAssocIntPreppend()
    {
        $arr = array('foo' => 'bar');
        array_unshift($arr, 'rat');
        $this->assertFalse(EasyRdf_Utils::is_associative_array($arr));
    }

}
