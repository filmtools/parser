<?php
namespace FilmTools\Parser;

class ParserFactory
{
    public function __invoke( $file ) : ParserInterface
    {
        $ext = strtolower(pathinfo($file, \PATHINFO_EXTENSION));

        switch( $ext):
            case "csv":
                return new CsvParser;
                break;
            default:
                break;
        endswitch;

        $msg = sprintf("Invalid file extension '%s'.", $ext);
        throw new ParserUnexpectedValueException( $msg );

    }
}
