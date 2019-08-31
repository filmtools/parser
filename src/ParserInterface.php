<?php
namespace FilmTools\Parser;

interface ParserInterface
{

    /**
     * @param  mixed $file  CSV file to parse
     * @return \Traversable
     */
    public function parse( $file ) : \Traversable;

    /**
     * @param  string $csv_text Things to parse
     * @return \Traversable
     */
    public function parseString( string $csv_text ) : \Traversable;
}
