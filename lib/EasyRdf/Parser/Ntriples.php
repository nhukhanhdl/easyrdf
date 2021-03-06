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

/**
 * A pure-php class to parse N-Triples with no dependancies.
 *
 * @package    EasyRdf
 * @copyright  Copyright (c) 2009-2010 Nicholas J Humfrey
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
class EasyRdf_Parser_Ntriples extends EasyRdf_Parser_RdfPhp
{
    /**
     * @ignore
     */
    protected function parseNtriplesSubject($sub)
    {
         if (preg_match('/<([^<>]+)>/', $sub, $matches)) {
             return $matches[1];
         } else if (preg_match('/(_:[A-Za-z][A-Za-z0-9]*)/', $sub, $matches)) {
             return $matches[1];
         } else {
              echo "Failed to parse subject: $sub\n";
         }
    }

    /**
     * @ignore
     */
    protected function parseNtriplesObject($obj)
    {
         if (preg_match('/"(.+)"/', $obj, $matches)) {
             # FIXME: implement unescaping
             # FIXME: implement datatypes
             # FIXME: implement languages
             return array('type' => 'literal', 'value' => $matches[1]);
         } else if (preg_match('/<([^<>]+)>/', $obj, $matches)) {
             return array('type' => 'uri', 'value' => $matches[1]);
         } else if (preg_match('/(_:[A-Za-z][A-Za-z0-9]*)/', $obj, $matches)) {
             return array('type' => 'bnode', 'value' => $matches[1]);
         } else {
              echo "Failed to parse object: $obj\n";
         }
    }

    /**
      * Parse an N-Triples document into an EasyRdf_Graph
      *
      * @param string $graph    the graph to load the data into
      * @param string $data     the RDF document data
      * @param string $format   the format of the input data
      * @param string $baseUri  the base URI of the data being parsed
      * @return boolean         true if parsing was successful
      */
    public function parse($graph, $data, $format, $baseUri)
    {
        parent::checkParseParams($graph, $data, $format, $baseUri);

        if ($format != 'ntriples') {
            throw new EasyRdf_Exception(
                "EasyRdf_Parser_Ntriples does not support: $format"
            );
        }

        $rdfphp = array();
        $lines = preg_split("/[\r\n]+/", strval($data));
        foreach ($lines as $line) {
            if (preg_match(
                "/(.+)\s+<([^<>]+)>\s+(.+)\s*\./",
                $line, $matches
            )) {
                $subject = $this->parseNtriplesSubject($matches[1]);
                $predicate = $matches[2];
                $object = $this->parseNtriplesObject($matches[3]);

                if (!isset($rdfphp[$subject])) {
                    $rdfphp[$subject] = array();
                }

                if (!isset($rdfphp[$subject][$predicate])) {
                    $rdfphp[$subject][$predicate] = array();
                }

                array_push($rdfphp[$subject][$predicate], $object);
            }
        }

        # FIXME: generate objects directly, instead of this second stage
        return parent::parse($graph, $rdfphp, 'php', $baseUri);
    }
}

EasyRdf_Format::registerParser('ntriples', 'EasyRdf_Parser_Ntriples');
