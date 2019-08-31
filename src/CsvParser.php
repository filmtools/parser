<?php
namespace FilmTools\Parser;

use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Exception as CsvException;
use function League\Csv\delimiter_detect;

class CsvParser implements ParserInterface
{

    /**
     * Allowed CSV delimiters
     * @var array
     */
    public $delimiters = array();

    /**
     * @var integer
     */
    public $header_offset = 0;


    /**
     * @param array Optional array with allowed delimiters. Defaults to typical characters.
     */
    public function __construct( array $delimiters = array() )
    {
        $this->delimiters = $delimiters ?: [ ";", "\t", '|',  "," ];
    }


    /**
     * @inheritDoc
     * @return ArrayIterator
     */
    public function parse( $file ) : \Traversable
    {
        try {
            // May throw ParserException
            $this->assertReadableFile( $file );
            $csv = Reader::createFromPath($file);
            return $this->processRecords( $csv );
        }
        catch (CsvException $e) {
            throw new ParserException("Problem when parsing file", 0, $e);
        }
    }


    /**
     * @inheritDoc
     * @return ArrayIterator
     */
    public function parseString( string $csv_text ) : \Traversable
    {
        try {
            $csv = Reader::createFromString($csv_text);
            return $this->processRecords( $csv );
        }
        catch (CsvException $e) {
            throw new ParserException("Problem when parsing file", 0, $e);
        }
    }



    /**
     * @param  Reader $csv League CSV Reader instance
     * @return ArrayIterator
     */
    protected function processRecords( Reader $csv ) : \Traversable
    {

        // Start working with Reader instance
        $delimiter = $this->detectDelimiters( $csv );
        $csv->setDelimiter( $delimiter );
        $csv->setHeaderOffset ($this->header_offset);


        // FEATURE:
        // Filter out comment lines with starting "#" sign
        $stmt = (new Statement())
            ->where(function (array $row) {
                return (mb_substr( current($row), 0, 1 ) !== "#");
            });

        $resultset = $stmt->process($csv);
        $records_generator = $resultset->getRecords();

        // Create an Iterator from the Generator. Sort of Workaround.
        return new \ArrayIterator(iterator_to_array( $records_generator ));

    }




    /**
     * @param  string $csv   CSV file path
     * @param  int    $lines Number of lines used to detect; default is 10
     * @return string      The delimiter detected
     */
    protected function detectDelimiters( Reader $csv, $lines = 10 )
    {
        $delimiters = delimiter_detect($csv, $this->delimiters, $lines);
        asort($delimiters);
        $delimiters = array_reverse( $delimiters );
        return key($delimiters );
    }


    /**
     * @param  string $file
     * @return void
     * @throws ParserException
     */
    protected function assertReadableFile( $file )
    {
        if (!is_file( $file )):
            $msg = sprintf("File not found: '%s'", $file);
            throw new ParserException( $msg );
        endif;

        if (!is_readable( $file )):
            $msg = sprintf("File not readable: '%s'", $file);
            throw new ParserException( $msg );
        endif;

    }

}
