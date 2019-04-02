<?php
namespace FilmTools\Parser;

interface ParserInterface
{

    /**
     * @param  mxied $file  things to parse
     * @return \Traversable
     */
    public function parse( $file ) : \Traversable;
}
