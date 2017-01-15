<?php
function main(array $argv)
{
    try {
        runMain($argv);
    } catch (\Exception $e) {
        fwrite(STDERR, "Error occurred: " . $e->getMessage() . "\n");
        exit(1);
    }
}

function runMain(array $argv)
{
    if (count($argv) != 2) {
        throw new \Exception("Usage: php converter.php <filename>");
    }

    $orig_file = $argv[1];

    require(__DIR__ . "/vendor/PHP-Parser/lib/bootstrap.php");

    $traverser = new \PhpParser\NodeTraverser();

    require(__DIR__ . "/Traverser.php");

    $traverser->addVisitor(new Traverser($orig_file));

    $parser = (new \PhpParser\ParserFactory)->create(\PhpParser\ParserFactory::PREFER_PHP7);
    $stmts = $parser->parse(file_get_contents($orig_file));

    $top_level = [];
    $functions = [];
    $classes = [];

    foreach ($stmts as $s) {
        if ($s instanceof \PhpParser\Node\Stmt\Function_) {
            $functions[] = $s;
        } else if ($s instanceof \PhpParser\Builder\Class_) {
            $classes[] = $s;
        } else {
            $top_level[] = $s;
        }
    }

//    $traverser->traverse($stmts);

    echo "Top level code:\n";
    foreach ($top_level as $s) {
        print_r($s);
    }
}

main($argv);
