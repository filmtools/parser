# FilmTools · Parser



## Installation

```bash
$ composer require filmtools/parser
```



## Usage

**Creating the Parser object**

```php
<?php
use FilmTools\Parser\ParserFactory;
use FilmTools\Parser\ParserExceptionInterface;

$factory = new ParserFactory;

try { 
	$parser = $factory("data.csv");
}
catch (ParserExceptionInterface $e) {
  echo $e->getMessage();
  // "Invalid file extension ..."
}

```

**Parsing the data**

```php
try {
	$records = $parser->parse("data.csv");
  $records = $parser->parseString( file_get_contents("data.csv"));
  
	foreach($records as $row):
  	// Do things with \Traversable
	endforeach;  
}
catch (ParserExceptionInterface $e) {
  echo $e->getMessage();
  // "File not found ..." 
  // or s.th. like that
}

```



## Unit Tests

Nope, sorry … *cough* … 