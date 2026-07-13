<?php

declare(strict_types=1);

return [
    'title' => 'CS: PHP Basics',
    'slug' => 'cs-php-basics',
    'description' => 'CS: Learn the PHP programming language from the ground up. Covers variables, control flow, functions, arrays, OOP, and more.',
    'user_email' => 'instructor@example.com',
    'lessons' => [
        0 => [
            'title' => 'CS: What is PHP?',
            'slug' => 'cs-what-is-php',
            'description' => 'CS: History of PHP, how it works, syntax basics, embedding PHP with HTML, comments, and environment setup.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: A Brief History of PHP',
                    'content' => 'CS: PHP originally stood for "Personal Home Page" and was created by Rasmus Lerdorf in 1994. It has since evolved into a full-featured server-side scripting language, now powering over 75% of websites. PHP 8.x introduced major performance improvements via the JIT compiler, named arguments, attributes, and union types. Modern PHP is a general-purpose language especially suited to web development, with frameworks like Laravel and Symfony leading the ecosystem. PHP code is executed on the server, generating HTML that is sent to the client\'s browser.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: PHP History Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Who created PHP?',
                            'options' => [
                                0 => 'CS: Andi Gutmans',
                                1 => 'CS: Rasmus Lerdorf',
                                2 => 'CS: Zeev Suraski',
                                3 => 'CS: Linus Torvalds',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Rasmus Lerdorf created PHP in 1994 as a set of Perl scripts.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: How PHP Works',
                    'content' => 'CS: PHP is a server-side scripting language. When a browser requests a PHP page, the web server (like Apache or Nginx) passes the request to the PHP interpreter. The interpreter executes the PHP code, which can interact with databases, files, and other services, and produces HTML output. That HTML is then sent back to the browser. This means users never see your PHP source code — they only see the rendered output. PHP files typically have a .php extension and contain a mix of HTML and PHP code delimited by <?php and ?> tags.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: How PHP Works Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Where does PHP code execute?',
                            'options' => [
                                0 => 'CS: In the browser',
                                1 => 'CS: On the web server',
                                2 => 'CS: In a database',
                                3 => 'CS: On a CDN',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: PHP is a server-side language; all code runs on the server before output is sent to the browser.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Basic PHP Syntax',
                    'content' => 'CS: PHP code is embedded in HTML using <?php and ?> tags. Every statement ends with a semicolon. Variables start with a dollar sign ($) and do not require explicit type declaration. PHP is loosely typed, meaning it automatically converts types as needed. Comments can be single-line (// or #) or multi-line (/* */). Unlike HTML and JavaScript, whitespace is largely ignored by the PHP parser, which allows you to format your code for readability. A simple "Hello, World!" in PHP looks like: <?php echo "Hello, World!"; ?>.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: PHP Syntax Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid PHP comment styles?',
                            'options' => [
                                0 => 'CS: // Single-line comment',
                                1 => 'CS: # Single-line comment',
                                2 => 'CS: /* Multi-line comment */',
                                3 => 'CS: <!-- HTML comment -->',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: PHP supports // and # for single-line comments and /* */ for multi-line comments. <!-- --> is an HTML comment, not PHP.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: PHP and HTML Together',
                    'content' => 'CS: PHP files seamlessly mix HTML and PHP. Any text outside <?php ... ?> tags is output directly as HTML. This makes it easy to inject dynamic content into your web pages. A common pattern is to use PHP to generate variable content while keeping the HTML structure clean. You can use short echo tags (<?= $variable ?>) as a shorthand for <?php echo $variable; ?>. Large blocks of PHP can also live entirely in <?php ?> at the top of a file, or be split into multiple smaller blocks inline throughout the HTML.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: PHP and HTML Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does <?= $name ?> do in a PHP template?',
                            'options' => [
                                0 => 'CS: Assigns $name to a variable',
                                1 => 'CS: Outputs the value of $name',
                                2 => 'CS: Checks if $name is set',
                                3 => 'CS: Creates a new scope for $name',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: <?= is a shorthand for <?php echo, so <?= $name ?> outputs the value of $name directly.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Setting Up Your PHP Environment',
                    'content' => 'CS: To run PHP locally, you need a development environment. Options include XAMPP (Apache + MySQL + PHP + phpMyAdmin), Laravel Herd (macOS), Laravel Valet (macOS), or Docker containers. PHP also has a built-in development server you can start with: php -S localhost:8000. For writing code, any text editor works, but IDEs like PhpStorm or VS Code with PHP extensions provide syntax highlighting, autocomplete, and debugging. Composer is the dependency manager for PHP, essential for modern PHP projects. You should also install a database like MySQL or PostgreSQL and learn to use phpMyAdmin or TablePlus for database management.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: PHP Setup Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid PHP development environments?',
                            'options' => [
                                0 => 'CS: XAMPP',
                                1 => 'CS: Laravel Herd',
                                2 => 'CS: Docker',
                                3 => 'CS: Node.js',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: XAMPP, Laravel Herd, and Docker are all valid PHP development environments. Node.js is a JavaScript runtime, not a PHP environment.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        1 => [
            'title' => 'CS: Variables and Data Types',
            'slug' => 'cs-variables-data-types',
            'description' => 'CS: Variables, scalar types, compound types, type juggling, variable variables, constants, and variable scope.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Variables in PHP',
                    'content' => 'CS: Variables in PHP are prefixed with a dollar sign ($) and are case-sensitive. Variable names must start with a letter or underscore, followed by any number of letters, numbers, or underscores. PHP variables do not need to be declared before assignment — assigning a value creates the variable. Variables are loosely typed, meaning the same variable can hold different types at different times. Naming conventions vary, but camelCase ($firstName) is common in modern PHP, while snake_case ($first_name) is also widely used. Always use meaningful variable names that describe the data they hold.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Variable Naming Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid PHP variable names?',
                            'options' => [
                                0 => 'CS: $_myVar',
                                1 => 'CS: $2fast',
                                2 => 'CS: $user_name',
                                3 => 'CS: $this',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                            ],
                            'explanation' => 'CS: $_myVar and $user_name are valid. $2fast starts with a number, and $this is a reserved keyword.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Scalar Data Types',
                    'content' => 'CS: PHP has four scalar (primitive) data types. bool (true/false) represents boolean values. int represents whole numbers like 42 or -7. float (also called double) represents decimal numbers like 3.14 or 2.997e8. string represents sequences of characters enclosed in single or double quotes. Double-quoted strings interpret escape sequences and expand variable names, while single-quoted strings are literal. Key difference: "$name" outputs the value of $name, but \'$name\' outputs the literal text $name.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Scalar Types Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the output of echo \'Hello, $name\' when $name = "World"?',
                            'options' => [
                                0 => 'CS: Hello, World',
                                1 => 'CS: Hello, $name',
                                2 => 'CS: Error',
                                3 => 'CS: Hello, World!',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Single-quoted strings do not expand variables, so the literal $name is output.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Compound Data Types',
                    'content' => 'CS: PHP provides two compound types: arrays and objects. Arrays are ordered maps that can hold multiple values of any type, accessed by integer indices or string keys. Indexed arrays use numeric keys starting at 0: $colors = ["red", "green", "blue"]. Associative arrays use named string keys: $ages = ["Alice" => 30, "Bob" => 25]. Objects are instances of classes, encapsulating both data (properties) and behaviour (methods). There are also two special types: null (the absence of a value) and resource (an external reference like a database connection or file handle).',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Compound Types Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you access the value "green" in $colors = ["red", "green", "blue"]?',
                            'options' => [
                                0 => 'CS: $colors[0]',
                                1 => 'CS: $colors[1]',
                                2 => 'CS: $colors["green"]',
                                3 => 'CS: $colors->green',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Arrays are zero-indexed, so $colors[1] returns the second element, "green".',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Type Juggling and Casting',
                    'content' => 'CS: PHP automatically converts between types when the context demands it — this is called type juggling. For example, "5" + 3 returns integer 8 because the string is converted to an integer in a numeric context. While convenient, this can lead to subtle bugs. Explicit type casting uses (type) syntax: (int) $var, (string) $var, (float) $var, (bool) $var, (array) $var. PHP 8 introduced strict types mode (declare(strict_types=1)) which prevents automatic type coercion in function calls, enforcing that arguments must match declared types exactly.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Type Juggling Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does "10" + 5 return in PHP?',
                            'options' => [
                                0 => 'CS: "105"',
                                1 => 'CS: 15',
                                2 => 'CS: 105',
                                3 => 'CS: Error',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: PHP converts the string "10" to an integer and performs numeric addition, resulting in 15.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Constants and Variable Variables',
                    'content' => 'CS: Constants are immutable values defined with define("NAME", value) or the const keyword (const NAME = value). By convention, constants are uppercase. Unlike variables, constants do not have a $ prefix and are globally accessible. Variable variables allow you to use the value of one variable as the name of another: $var = "hello"; $$var = "world"; creates $hello = "world". This feature is rarely used and can make code hard to read. Predefined constants like PHP_VERSION, PHP_INT_MAX, and PHP_EOL are built into the language.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Constants and Variable Variables Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following statements about PHP constants are TRUE?',
                            'options' => [
                                0 => 'CS: Constants are prefixed with $',
                                1 => 'CS: Constants cannot be changed after definition',
                                2 => 'CS: Constants are defined with define() or const',
                                3 => 'CS: Constants are automatically global',
                            ],
                            'answer' => [
                                0 => 1,
                                1 => 2,
                                2 => 3,
                            ],
                            'explanation' => 'CS: Constants do NOT use the $ prefix. They are immutable once defined, can be created with define() or const, and are globally accessible.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        2 => [
            'title' => 'CS: Strings and Arrays',
            'slug' => 'cs-strings-arrays',
            'description' => 'CS: String basics, string functions, array basics, associative arrays, array functions, multidimensional arrays, and variable interpolation.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: String Basics in PHP',
                    'content' => 'CS: Strings are sequences of characters. You can create them with single quotes (\'...\'), double quotes ("..."), heredoc (<<<EOT), and nowdoc (<<<\'EOT\'). Double-quoted strings and heredocs support escape sequences (\\n, \\t, \\, \\$) and variable interpolation. Single-quoted strings and nowdocs are literal — only \\ (backslash) and \' (single quote) are escaped. Heredoc syntax is ideal for multi-line strings: <<<EOT followed by content then EOT; on its own line. Nowdoc is the single-quoted equivalent and does not parse variables.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: String Syntax Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which string syntax allows variable interpolation?',
                            'options' => [
                                0 => 'CS: Single quotes',
                                1 => 'CS: Double quotes',
                                2 => 'CS: Nowdoc',
                                3 => 'CS: All of the above',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Only double-quoted strings and heredocs support variable interpolation. Single-quoted strings and nowdocs treat everything literally.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Common String Functions',
                    'content' => 'CS: PHP has a rich set of built-in string functions. strlen($str) returns the byte length. strpos($haystack, $needle) finds the position of the first occurrence. substr($str, $start, $length) extracts a portion. str_replace($search, $replace, $subject) performs find-and-replace. strtolower() and strtoupper() change case. trim() removes whitespace from both ends. explode($delimiter, $string) splits a string into an array, and implode($glue, $array) joins array elements into a string. These functions are essential for everyday PHP development.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: String Functions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which functions can be used to split a string into an array?',
                            'options' => [
                                0 => 'CS: explode()',
                                1 => 'CS: implode()',
                                2 => 'CS: preg_split()',
                                3 => 'CS: str_split()',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                                2 => 3,
                            ],
                            'explanation' => 'CS: explode() splits by a delimiter, preg_split() uses regex, and str_split() splits into fixed-length chunks. implode() does the opposite — it joins array elements into a string.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Indexed and Associative Arrays',
                    'content' => 'CS: Arrays in PHP are ordered maps. Indexed arrays use sequential integer keys starting from 0: $fruits = ["apple", "banana", "cherry"]. You can add elements with $fruits[] = "date". Associative arrays use named string keys: $user = ["name" => "Alice", "email" => "alice@example.com"]. Array keys are case-sensitive. You can mix integer and string keys, though it is rarely advisable. The short array syntax [] has been available since PHP 5.4 and is preferred over the older array() syntax. Arrays are dynamic — they grow and shrink automatically.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Array Basics Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the index of "cherry" in $fruits = ["apple", "banana", "cherry"]?',
                            'options' => [
                                0 => 'CS: 0',
                                1 => 'CS: 1',
                                2 => 'CS: 2',
                                3 => 'CS: 3',
                            ],
                            'answer' => 2,
                            'explanation' => 'CS: Arrays are zero-indexed, so "apple" is 0, "banana" is 1, and "cherry" is 2.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Multidimensional Arrays',
                    'content' => 'CS: An array can contain other arrays as elements, creating multidimensional arrays. This is useful for representing tabular data or nested structures. For example, $matrix = [[1, 2], [3, 4], [5, 6]] is a 3x2 matrix. You access elements with successive square brackets: $matrix[0][1] returns 2. Associative multidimensional arrays are common: $users[0]["name"] accesses the "name" field of the first user. While powerful, deeply nested arrays can become hard to manage — consider using objects or collections for complex data structures.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Multidimensional Arrays Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Given $data = [["a" => 1], ["a" => 2]], how do you get the value 2?',
                            'options' => [
                                0 => 'CS: $data[1]["a"]',
                                1 => 'CS: $data["a"][1]',
                                2 => 'CS: $data[2]["a"]',
                                3 => 'CS: $data["a"][2]',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: $data[1] is the second inner array (["a" => 2]), and ["a"] accesses key "a", giving 2.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Useful Array Utility Functions',
                    'content' => 'CS: PHP provides many built-in array functions. count($arr) returns the number of elements. array_push() and array_pop() add/remove from the end. array_shift() and array_unshift() modify the beginning. in_array($needle, $haystack) checks if a value exists. array_key_exists($key, $arr) checks for a key. array_keys() and array_values() extract keys or values. array_merge() combines arrays. array_diff() finds differences. array_unique() removes duplicates. Mastering these functions will save you from writing manual loops in many situations.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Array Functions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which functions add an element to the end of an array?',
                            'options' => [
                                0 => 'CS: array_push()',
                                1 => 'CS: array_pop()',
                                2 => 'CS: $arr[] = $value',
                                3 => 'CS: array_unshift()',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                            ],
                            'explanation' => 'CS: Both array_push() and the $arr[] syntax add to the end. array_pop() removes from the end, and array_unshift() adds to the beginning.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        3 => [
            'title' => 'CS: Control Flow',
            'slug' => 'cs-control-flow',
            'description' => 'CS: If/else statements, comparison and logical operators, switch, ternary operator, and match expressions.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: If, Else, and Elseif',
                    'content' => 'CS: Conditional statements let your code make decisions. The if statement executes a block if a condition is true. else provides an alternative when the condition is false. elseif (two words) chains additional conditions. Conditions are expressions that evaluate to true or false. PHP\'s type coercion means non-boolean values are evaluated in boolean context: 0, "0", "" (empty string), [] (empty array), and null are all falsy. All other values are truthy. Curly braces {} define the block boundaries. For single statements, you can omit braces, but using them consistently improves readability and prevents bugs.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: If Statements Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which values are considered falsy in PHP?',
                            'options' => [
                                0 => 'CS: "false"',
                                1 => 'CS: 0',
                                2 => 'CS: " " (space)',
                                3 => 'CS: "0"',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: The integer 0 and the string "0" are falsy, as are null, false, and empty arrays. The string "false" and " " (space) are truthy.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Comparison Operators',
                    'content' => 'CS: Comparison operators compare two values. == (equal) and != (not equal) perform type-juggling comparison: "5" == 5 is true. === (identical) and !== (not identical) compare both value and type: "5" === 5 is false. Other operators: <, >, <=, >=. The spaceship operator <=> returns -1, 0, or 1. PHP 8 introduced str_contains(), str_starts_with(), and str_ends_with() for string comparisons. Always prefer === over == to avoid unexpected type-coercion bugs. When comparing with null, use ?? (null coalescing operator) or the null-safe operator (?->) for object access.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Comparison Operators Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does "5" === 5 evaluate to?',
                            'options' => [
                                0 => 'CS: true',
                                1 => 'CS: false',
                                2 => 'CS: null',
                                3 => 'CS: Error',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: The === operator checks both value and type. "5" is a string and 5 is an integer, so they are not identical.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Logical Operators',
                    'content' => 'CS: Logical operators combine multiple conditions. && (AND) and || (OR) are the most common. and and or also exist but have lower precedence, which can cause subtle bugs. ! (NOT) negates a condition. xor (exclusive OR) is true if exactly one operand is true. PHP uses short-circuit evaluation: if the first operand of && is false, the second is never evaluated. Similarly, if the first operand of || is true, the second is skipped. This is useful for guarding: $user && $user->isAdmin() will not throw an error if $user is null.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Logical Operators Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which logical operators use short-circuit evaluation in PHP?',
                            'options' => [
                                0 => 'CS: &&',
                                1 => 'CS: ||',
                                2 => 'CS: and',
                                3 => 'CS: xor',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: &&, ||, and the low-precedence and/or all use short-circuit evaluation. xor always evaluates both operands because it needs both values.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Switch Statements',
                    'content' => 'CS: The switch statement is an alternative to a long chain of if/elseif blocks. It compares an expression against multiple case values using loose comparison (==). Each case is followed by a block of code and typically a break statement to prevent fall-through. Without break, execution continues into the next case — this is sometimes intentional but usually a bug. A default case handles unmatched values. Modern PHP developers often prefer match expressions (PHP 8) over switch because match uses strict comparison (===) and returns values without requiring break.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Switch Statements Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What happens if you omit break in a switch case?',
                            'options' => [
                                0 => 'CS: The case is skipped',
                                1 => 'CS: Execution falls through to the next case',
                                2 => 'CS: A syntax error occurs',
                                3 => 'CS: PHP automatically exits the switch',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Without break, execution continues into the next case regardless of whether it matches. This is called fall-through.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Ternary Operator and Match Expression',
                    'content' => 'CS: The ternary operator (condition ? value_if_true : value_if_false) is a concise if/else expression. It can be chained but that hurts readability. The null coalescing operator (??) returns the left side if it is not null, otherwise the right side: $name = $_GET["name"] ?? "Guest". The match expression (PHP 8) is a powerful switch-like construct that returns a value, uses strict comparison, supports multiple comma-separated conditions per arm, and throws an UnhandledMatchError if no arm matches. Example: $result = match ($status) { 200 => "OK", 404 => "Not Found", default => "Unknown" };',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Ternary and Match Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the value of $x after $x = null ?? "default"?',
                            'options' => [
                                0 => 'CS: null',
                                1 => 'CS: "default"',
                                2 => 'CS: false',
                                3 => 'CS: 0',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: The null coalescing operator returns the left operand if it is not null. Since null is null, it returns the right operand, "default".',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        4 => [
            'title' => 'CS: Loops',
            'slug' => 'cs-loops',
            'description' => 'CS: While, do-while, for, foreach, break/continue, and nested loops.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: While Loops',
                    'content' => 'CS: A while loop repeats a block of code as long as a condition is true. The condition is checked before each iteration, so if it is false initially, the block never executes. While loops are ideal when you do not know the number of iterations in advance — for example, reading a file line by line or waiting for an external condition. Be careful to ensure the condition eventually becomes false; otherwise you will create an infinite loop. The syntax is straightforward: while (condition) { // code }. A common pattern uses a counter variable that increments inside the loop body to control the number of iterations.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: While Loops Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How many times does "Hello" print in: $i = 5; while ($i < 5) { echo "Hello"; $i++; }?',
                            'options' => [
                                0 => 'CS: 0',
                                1 => 'CS: 1',
                                2 => 'CS: 4',
                                3 => 'CS: 5',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The condition $i < 5 is false when $i is 5, so the loop body never executes.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Do-While Loops',
                    'content' => 'CS: A do-while loop is similar to while, but the condition is checked after each iteration. This guarantees that the loop body executes at least once. The syntax is: do { // code } while (condition);. The semicolon after the while clause is required. Do-while is useful when you need to perform an action first (like showing a menu) and then decide whether to repeat based on user input. Be aware that the guaranteed first execution can lead to unexpected behaviour if you assume the condition is checked first. In practice, do-while is used less frequently than while and foreach.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Do-While Loops Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is a key difference between while and do-while?',
                            'options' => [
                                0 => 'CS: do-while always executes at least once',
                                1 => 'CS: while always executes at least once',
                                2 => 'CS: do-while uses different braces',
                                3 => 'CS: There is no difference',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: do-while checks the condition after the loop body, guaranteeing at least one execution. while checks before each iteration.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: For Loops',
                    'content' => 'CS: A for loop is the most concise loop structure, combining initialization, condition, and increment in one line: for ($i = 0; $i < 10; $i++) { // code }. The initialization runs once before the loop starts. The condition is checked before each iteration. The increment runs after each iteration. Any of the three parts can be empty, though omitting the condition creates an infinite loop. For loops are ideal when iterating a known number of times, especially when you need the index. For iterating over arrays, foreach is usually more readable.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: For Loops Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which parts of a for loop can be left empty?',
                            'options' => [
                                0 => 'CS: Initialization',
                                1 => 'CS: Condition',
                                2 => 'CS: Increment',
                                3 => 'CS: None of them',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: All three parts of a for loop can be empty: for (;;) creates an infinite loop.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Foreach Loops',
                    'content' => 'CS: foreach is PHP\'s most elegant looping construct for iterating over arrays and objects. It comes in two flavours: foreach ($array as $value) for values only, and foreach ($array as $key => $value) when you also need the key. In PHP 7+, foreach operates on a copy of the array unless the array is referenced. To modify array elements inside the loop, use &: foreach ($array as &$value) — but be sure to unset the reference after the loop to avoid unexpected behaviour. foreach works with all countable types including collections, iterables, and generators.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Foreach Loops Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does foreach ($items as $key => $value) iterate over?',
                            'options' => [
                                0 => 'CS: Only values',
                                1 => 'CS: Only keys',
                                2 => 'CS: Both keys and values',
                                3 => 'CS: Only the first element',
                            ],
                            'answer' => 2,
                            'explanation' => 'CS: The $key => $value syntax captures both the key (index or associative key) and the value for each element.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Break, Continue, and Nested Loops',
                    'content' => 'CS: break exits a loop immediately, optionally specifying how many levels to break out of: break 2 exits two nested loops. continue skips the rest of the current iteration and moves to the next one. Like break, continue accepts an optional numeric argument for nested levels. Nested loops have one loop inside another. The inner loop runs completely for each iteration of the outer loop. Be mindful of performance: deeply nested loops with large datasets can be slow. Consider early exits with break, and ensure your loop termination conditions are correct to avoid infinite loops.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Break and Continue Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does break 2 do inside a nested loop?',
                            'options' => [
                                0 => 'CS: Breaks only the current loop',
                                1 => 'CS: Breaks two levels of loops',
                                2 => 'CS: Continues the outer loop',
                                3 => 'CS: Causes a syntax error',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: break with a numeric argument exits that many levels of nested loops. break 2 exits both the inner and outer loop.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        5 => [
            'title' => 'CS: Functions',
            'slug' => 'cs-functions',
            'description' => 'CS: Defining functions, return values, parameters, anonymous functions, variable scope, type declarations, and recursion.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Defining and Calling Functions',
                    'content' => 'CS: Functions are reusable blocks of code that accept input, perform operations, and optionally return output. A function is defined with the function keyword, a name, and parentheses: function greet() { echo "Hello!"; }. To call it, simply use greet(). Function names are case-insensitive in PHP. Functions must be defined before they are called. However, PHP supports conditional function definitions, hoisting does not apply, and nested function definitions can be tricky. Best practice is to define all functions at the top of a file or in separate include files before any execution code.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Function Basics Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Are PHP function names case-sensitive?',
                            'options' => [
                                0 => 'CS: Yes',
                                1 => 'CS: No',
                                2 => 'CS: Only for built-in functions',
                                3 => 'CS: Only for user-defined functions',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: PHP function names are case-insensitive. greet() and GREET() refer to the same function.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Parameters and Return Values',
                    'content' => 'CS: Functions accept parameters listed in the parentheses and return a value with the return keyword. A function without an explicit return statement returns null. Parameters can have default values: function greet(string $name = "Guest") { return "Hello, $name"; }. You can pass arguments by value (default) or by reference using &: function addOne(&$num) { $num++; }. PHP 8 introduced named arguments, letting you skip default parameters: greet(name: "Alice"). Functions can return any type, including arrays and objects.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Parameters and Return Values Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are valid ways to pass arguments in PHP?',
                            'options' => [
                                0 => 'CS: By value (default)',
                                1 => 'CS: By reference using &',
                                2 => 'CS: By named arguments (PHP 8+)',
                                3 => 'CS: By pointer *',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: PHP supports pass-by-value, pass-by-reference with &, and named arguments (PHP 8+). PHP does not have pointer syntax like C.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Variable Scope and the Global Keyword',
                    'content' => 'CS: PHP has function-level scope. Variables defined inside a function are local to that function and inaccessible outside. Variables defined outside are global and not automatically available inside functions. To access a global variable inside a function, use the global keyword: global $count;. Alternatively, use the $GLOBALS superglobal array: $GLOBALS["count"]. Static variables preserve their value between function calls: static $counter = 0; increments on each call. Modern PHP avoids global state in favour of dependency injection, but understanding scope is essential for debugging legacy code.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Variable Scope Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you access a global variable $config inside a function?',
                            'options' => [
                                0 => 'CS: global $config;',
                                1 => 'CS: import $config;',
                                2 => 'CS: use $config;',
                                3 => 'CS: include $config;',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The global keyword imports a global variable into the local scope of a function.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Anonymous Functions and Closures',
                    'content' => 'CS: Anonymous functions (also called closures) are functions without a name. They are often assigned to variables or passed as arguments: $greet = function(string $name) { return "Hello, $name"; };. Closures can inherit variables from the parent scope using the use keyword: $factor = 2; $double = function($x) use ($factor) { return $x * $factor; };. In PHP 7.4+, arrow functions provide a shorter syntax for simple closures: fn($x) => $x * $factor. Arrow functions automatically capture outer variables by value and cannot be multi-line.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Anonymous Functions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you make an outer variable available inside a closure?',
                            'options' => [
                                0 => 'CS: use ($var)',
                                1 => 'CS: global $var',
                                2 => 'CS: $GLOBALS["var"]',
                                3 => 'CS: import $var',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: The use keyword inherits variables from the parent scope into a closure. global and $GLOBALS are for regular function scope.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Type Declarations and Strict Types',
                    'content' => 'CS: PHP 7+ supports type declarations (type hints) for function parameters and return values. Common types: int, float, string, bool, array, object, callable, iterable, and nullable versions (?int, ?string). PHP 8 added union types: int|string|float. The mixed type accepts any value. Declare strict_types=1 at the top of a file to enforce strict type checking, preventing automatic coercion in function calls. With strict types enabled, passing a string "5" to a function expecting int causes a TypeError. This catches bugs early and makes code more predictable.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Type Declarations Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does declare(strict_types=1) do?',
                            'options' => [
                                0 => 'CS: Disables all type hints',
                                1 => 'CS: Enforces strict type checking in function calls',
                                2 => 'CS: Enables dynamic typing',
                                3 => 'CS: Increases performance',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Strict types mode prevents automatic type coercion for function and method calls, throwing TypeError on mismatch.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        6 => [
            'title' => 'CS: Advanced Arrays',
            'slug' => 'cs-advanced-arrays',
            'description' => 'CS: Destructuring, filtering, mapping, reducing, sorting, searching, slicing, and splitting arrays.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Array Destructuring',
                    'content' => 'CS: Array destructuring lets you unpack array values into individual variables in one statement. PHP supports both list() and the shorter [] syntax: [$a, $b] = [1, 2] assigns $a = 1 and $b = 2. You can skip elements by omitting variables: [, , $third] = $array. Associative destructuring works with the [] syntax: ["name" => $name, "age" => $age] = $user. Destructuring is especially useful for returning multiple values from a function or when working with paired data like database rows.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Array Destructuring Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the value of $b after [$a, $b] = [10, 20, 30]?',
                            'options' => [
                                0 => 'CS: 10',
                                1 => 'CS: 20',
                                2 => 'CS: 30',
                                3 => 'CS: null',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Destructuring assigns by position. $a gets 10 (index 0), $b gets 20 (index 1). The value 30 is ignored.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Filtering and Mapping Arrays',
                    'content' => 'CS: array_filter() iterates over an array and returns elements for which the callback returns true. Without a callback, it removes falsy values. array_map() applies a callback to each element and returns a new array of the same keys. array_reduce() reduces an array to a single value using an accumulator callback: array_reduce($items, fn($carry, $item) => $carry + $item, 0) sums the items. These functions are the pillars of functional programming in PHP. They produce cleaner, more declarative code than manual foreach loops.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Filtering and Mapping Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does array_filter($arr) do when no callback is provided?',
                            'options' => [
                                0 => 'CS: Returns all elements',
                                1 => 'CS: Removes elements that are falsy',
                                2 => 'CS: Sorts the array',
                                3 => 'CS: Returns the first element',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Without a callback, array_filter() removes all falsy values (0, "", null, false, []).',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Sorting Arrays',
                    'content' => 'CS: PHP has extensive sorting functions. sort() sorts by values (re-indexes), asort() sorts by values (preserves keys), ksort() sorts by keys. For descending order: rsort(), arsort(), krsort(). usort() accepts a custom comparison function. natsort() uses natural order (e.g., file2 before file10). Sorting functions modify the original array by reference and return true on success. To maintain the original, copy it first. Sorting is case-sensitive by default; use SORT_FLAG_CASE with natcasesort() for case-insensitive natural sorting.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Sorting Arrays Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which sorting functions preserve key-value associations?',
                            'options' => [
                                0 => 'CS: sort()',
                                1 => 'CS: asort()',
                                2 => 'CS: ksort()',
                                3 => 'CS: rsort()',
                            ],
                            'answer' => [
                                0 => 1,
                                1 => 2,
                            ],
                            'explanation' => 'CS: asort() sorts by values and ksort() by keys — both preserve key associations. sort() and rsort() re-index numeric keys.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Searching Arrays',
                    'content' => 'CS: in_array($needle, $haystack) checks if a value exists. array_search($needle, $haystack) returns the key of the first match or false. array_key_exists($key, $array) (or the shorthand isset($array[$key]) but beware of null values) checks for a key. array_keys() can return all keys matching a value: array_keys($array, "value"). By default these use loose comparison; pass true as the third argument for strict comparison. array_column($array, $column) extracts a single column from a multidimensional array — extremely useful with database results.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Array Searching Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does array_search() return if the value is not found?',
                            'options' => [
                                0 => 'CS: null',
                                1 => 'CS: false',
                                2 => 'CS: -1',
                                3 => 'CS: []',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: array_search() returns false when the value is not found. Always use === false to check the result.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Slicing and Splitting Arrays',
                    'content' => 'CS: array_slice($array, $offset, $length) extracts a portion of an array without modifying the original. It re-indexes by default unless you pass preserve_keys: true. array_splice($array, $offset, $length, $replacement) modifies the original by removing or replacing elements, returning the removed portion. array_chunk($array, $size) splits an array into chunks of the given size, useful for paginating results or dividing work. array_merge() combines arrays (later keys overwrite earlier string keys; numeric keys are re-indexed). The spread operator (...) in PHP 7.4+ can also merge arrays: [...$arr1, ...$arr2].',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Array Slicing and Splitting Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the difference between array_slice() and array_splice()?',
                            'options' => [
                                0 => 'CS: They are identical',
                                1 => 'CS: array_slice() modifies the original; array_splice() does not',
                                2 => 'CS: array_slice() does not modify the original; array_splice() does',
                                3 => 'CS: array_slice() only works with indexed arrays',
                            ],
                            'answer' => 2,
                            'explanation' => 'CS: array_slice() returns a portion without modifying the original. array_splice() removes/replaces elements in the original array.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        7 => [
            'title' => 'CS: String Functions',
            'slug' => 'cs-string-functions',
            'description' => 'CS: strlen, strpos, substr, str_replace, strtr, explode, implode, case conversion, trimming, and regular expressions with preg_match.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: String Length, Position, and Substrings',
                    'content' => 'CS: strlen($str) returns the byte length of a string. For multibyte characters (like UTF-8), use mb_strlen() instead. strpos($haystack, $needle, $offset) finds the position of the first occurrence, returning false if not found. Always check with === false. substr($str, $start, $length) extracts a portion. Negative start counts from the end; negative length stops that many characters from the end. For multibyte safety, use mb_substr(). PHP 8 introduced str_contains($haystack, $needle) for a simple boolean check without dealing with false positions.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: String Functions Basics Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does strpos("hello world", "world") return?',
                            'options' => [
                                0 => 'CS: 0',
                                1 => 'CS: 5',
                                2 => 'CS: 6',
                                3 => 'CS: false',
                            ],
                            'answer' => 2,
                            'explanation' => 'CS: "world" starts at index 6 in "hello world". strpos returns the starting position of the first occurrence.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Search and Replace Operations',
                    'content' => 'CS: str_replace($search, $replace, $subject) performs a simple find-and-replace. It accepts arrays for bulk replacements: str_replace(["a", "b"], ["x", "y"], $text). Case-insensitive version: str_ireplace(). strtr() has two modes: with three arguments it translates characters (like str_replace for single characters), and with two arguments it replaces substrings based on an associative array. strtr() does not overlap (replaced parts are not re-scanned), which can be useful or surprising depending on the use case. For regex-based replacement, use preg_replace().',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Search and Replace Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which PHP functions can be used for string replacement?',
                            'options' => [
                                0 => 'CS: str_replace()',
                                1 => 'CS: strtr()',
                                2 => 'CS: preg_replace()',
                                3 => 'CS: strpos()',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: str_replace(), strtr(), and preg_replace() all perform string replacement operations. strpos() finds a position but does not replace.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Explode, Implode, and CSVs',
                    'content' => 'CS: explode($separator, $string, $limit) splits a string into an array. The $limit parameter controls how many elements are returned. implode($glue, $pieces) joins array elements into a string. The $glue parameter is optional (defaults to ""). For CSV parsing, str_getcsv() parses a CSV string into an array, respecting quoted fields and escaped characters. fgetcsv() does the same for file handles. When splitting on empty string, use str_split() instead of explode("", $str). For complex delimiters, preg_split() offers regex-based splitting.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Explode and Implode Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the result of explode(",", "a,b,c", 2)?',
                            'options' => [
                                0 => 'CS: ["a", "b", "c"]',
                                1 => 'CS: ["a", "b,c"]',
                                2 => 'CS: ["a,b", "c"]',
                                3 => 'CS: ["a", "b"]',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: The third argument $limit = 2 means the result will have at most 2 elements. So the array is ["a", "b,c"].',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Case Conversion and Trimming',
                    'content' => 'CS: strtolower($str) and strtoupper($str) convert case. For multibyte (UTF-8) strings, use mb_strtolower() and mb_strtoupper(). ucfirst() capitalizes the first character, ucwords() capitalizes each word\'s first letter. lcfirst() lowercases the first character. trim($str, $characters) removes whitespace (or specified characters) from both ends. ltrim() removes from left only, rtrim() from right only. The second parameter lets you specify which characters to strip: trim($str, "/") removes slashes. These are essential for sanitizing user input and normalising data.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Case and Trimming Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does trim("--Hello--", "-") return?',
                            'options' => [
                                0 => 'CS: "--Hello--"',
                                1 => 'CS: "Hello"',
                                2 => 'CS: "Hello--"',
                                3 => 'CS: "--Hello"',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: trim() removes the specified character ("-") from both the beginning and end of the string, leaving "Hello".',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Regular Expressions with preg_match',
                    'content' => 'CS: preg_match($pattern, $subject, $matches) performs a regex match. It returns 1 if matched, 0 if not, or false on error. The $matches array captures parenthesized subpatterns. Patterns are delimited by forward slashes: /pattern/. Common modifiers: i (case-insensitive), m (multiline), s (dot matches newline), u (UTF-8). preg_match_all() finds all matches. preg_replace() performs regex-based replacement. preg_split() splits by regex. Always use single quotes for regex patterns to avoid escaping backslashes: \\d becomes \\d in double quotes but \\d in single quotes.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Regular Expressions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which modifier makes a regex pattern case-insensitive?',
                            'options' => [
                                0 => 'CS: /g',
                                1 => 'CS: /i',
                                2 => 'CS: /m',
                                3 => 'CS: /s',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: The i modifier makes the regex case-insensitive. g is global, m is multiline, s makes dot match newlines.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        8 => [
            'title' => 'CS: Superglobals',
            'slug' => 'cs-superglobals',
            'description' => 'CS: $_GET, $_POST, $_SERVER, $_SESSION, $_COOKIE, $_FILES, $_REQUEST, and $_ENV.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: $_GET and $_POST',
                    'content' => 'CS: $_GET and $_POST are superglobal arrays containing query string parameters and HTTP POST body data respectively. $_GET is populated from the URL query string (?key=value). $_POST comes from form submissions with method="POST" or API requests with Content-Type: application/x-www-form-urlencoded or multipart/form-data. Both are associative arrays. Always validate and sanitize this data before using it — never trust user input. Use filter_input(INPUT_GET, "key", FILTER_SANITIZE_STRING) or the null coalescing operator with a default: $name = $_GET["name"] ?? null.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: GET and POST Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: When is $_GET populated?',
                            'options' => [
                                0 => 'CS: From form submissions with method="POST"',
                                1 => 'CS: From URL query string parameters',
                                2 => 'CS: From HTTP headers',
                                3 => 'CS: From cookies',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: $_GET contains data from the URL query string. POST data arrives in $_POST from form submissions or HTTP POST body.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: $_SERVER: Server and Environment Info',
                    'content' => 'CS: $_SERVER is an array containing information created by the web server. Common entries include: $_SERVER["SERVER_NAME"] (server hostname), $_SERVER["REQUEST_METHOD"] (GET, POST, etc.), $_SERVER["REQUEST_URI"] (the path component of the URL), $_SERVER["HTTP_HOST"] (the Host header), $_SERVER["REMOTE_ADDR"] (client IP address), $_SERVER["HTTP_USER_AGENT"] (browser identifier), and $_SERVER["SCRIPT_NAME"] (current script path). These values come from the client and server environment, so they should be treated as untrusted input for security purposes.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: $_SERVER Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which $_SERVER keys contain information about the current request?',
                            'options' => [
                                0 => 'CS: REQUEST_METHOD',
                                1 => 'CS: SERVER_NAME',
                                2 => 'CS: REMOTE_ADDR',
                                3 => 'CS: DB_PASSWORD',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: REQUEST_METHOD, SERVER_NAME, and REMOTE_ADDR are standard $_SERVER entries. DB_PASSWORD should never be in $_SERVER.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: $_SESSION and $_COOKIE',
                    'content' => 'CS: $_SESSION provides session data persistence across requests. Session data is stored server-side; a unique session ID is stored in a cookie on the client. Start a session with session_start(), then read/write via $_SESSION["key"] = value. Cookies are stored client-side and accessed via $_COOKIE. Set them with setcookie("name", "value", $expires, $path). Sessions are preferred for sensitive data because data stays on the server. Cookies are limited to 4KB and can be disabled by users. Always validate session data and regenerate session IDs after login to prevent session fixation.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Sessions and Cookies Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Where is session data stored?',
                            'options' => [
                                0 => 'CS: In a cookie on the client',
                                1 => 'CS: On the server',
                                2 => 'CS: In a file on the client',
                                3 => 'CS: In a database accessible from the client',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Session data is stored on the server. Only a session identifier (session ID) is stored on the client, typically in a cookie.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: $_FILES and File Uploads',
                    'content' => 'CS: $_FILES is a superglobal that holds information about uploaded files via HTTP POST. When a form uses enctype="multipart/form-data" and an <input type="file">, PHP populates $_FILES["fieldname"]. Each entry is an array with keys: name (original filename), type (MIME type), tmp_name (temporary path on server), error (upload error code), and size (bytes). Move uploaded files from tmp to a permanent location with move_uploaded_file(). Always validate file type, size, and extension thoroughly. The php.ini settings upload_max_filesize and post_max_size control maximum upload sizes.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: File Uploads Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which function moves an uploaded file to a permanent location?',
                            'options' => [
                                0 => 'CS: copy()',
                                1 => 'CS: move_uploaded_file()',
                                2 => 'CS: upload_file()',
                                3 => 'CS: file_put_contents()',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: move_uploaded_file() is the correct function. It also performs safety checks to ensure the file was actually uploaded via HTTP POST.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: $_REQUEST, $_ENV, and Best Practices',
                    'content' => 'CS: $_REQUEST merges $_GET, $_POST, and $_COOKIE (order depends on the request_order directive). It is convenient but considered poor practice because it is unclear where data originates — use $_GET, $_POST, or $_COOKIE explicitly. $_ENV contains environment variables set by the server or the operating system. In modern PHP, use getenv() and the Dotenv library from .env files instead of $_ENV directly. Superglobals are always available in all scopes, which is both their strength and a potential source of confusion. Access them directly rather than extracting variables with extract().',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: $_REQUEST and $_ENV Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which superglobals are merged into $_REQUEST?',
                            'options' => [
                                0 => 'CS: $_GET',
                                1 => 'CS: $_POST',
                                2 => 'CS: $_COOKIE',
                                3 => 'CS: $_FILES',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: $_REQUEST combines $_GET, $_POST, and $_COOKIE values. $_FILES is not included.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        9 => [
            'title' => 'CS: Forms and User Input',
            'slug' => 'cs-forms-user-input',
            'description' => 'CS: Form creation (GET vs POST), sanitizing and validating input, file uploads, and form helpers.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: HTML Forms and PHP',
                    'content' => 'CS: HTML forms send data from the browser to the server. The <form> tag defines action (URL) and method (GET or POST). Input fields must have a name attribute for their data to be included in the submission. On the server, PHP receives this data in $_GET or $_POST depending on the method. The action attribute can be left empty to submit to the current URL. Always include a CSRF token in forms that modify data to protect against cross-site request forgery. Laravel and other frameworks provide built-in CSRF protection.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Forms Basics Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which HTML attribute determines the name used to access form data in PHP?',
                            'options' => [
                                0 => 'CS: id',
                                1 => 'CS: name',
                                2 => 'CS: class',
                                3 => 'CS: value',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: The name attribute of an HTML input field becomes the array key in $_GET or $_POST on the server.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: GET vs POST Methods',
                    'content' => 'CS: GET appends data to the URL as query parameters (?name=value). It is suitable for search forms, filtering, and idempotent requests. GET requests are bookmarkable, cacheable, and visible in browser history. POST sends data in the HTTP request body. It is suitable for creating or modifying data (forms that change state), login forms, and file uploads. POST data is not visible in the URL, but it is not encrypted unless HTTPS is used. Neither method is inherently secure. Always use HTTPS in production. Never use GET for sensitive data like passwords.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: GET vs POST Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: When should you use POST over GET?',
                            'options' => [
                                0 => 'CS: When you want the URL to be bookmarkable',
                                1 => 'CS: When the form modifies server state',
                                2 => 'CS: When the data is short',
                                3 => 'CS: When you need caching',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: POST should be used for requests that modify server state (create, update, delete). GET is for safe, idempotent requests.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Sanitizing User Input',
                    'content' => 'CS: Never trust user input. Sanitization cleans data to make it safe for use. For HTML output, use htmlspecialchars($str, ENT_QUOTES, "UTF-8") to prevent XSS attacks. For database queries, use prepared statements (PDO or mysqli) — never concatenate variables into SQL. For email headers, strip newlines to prevent header injection. filter_var($input, FILTER_SANITIZE_EMAIL) removes dangerous characters from email addresses. Stripslashes removes escape characters from input if magic_quotes are enabled (legacy). The principle is: validate aggressively, sanitize appropriately to the context where the data will be used.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Sanitizing Input Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of the following are proper ways to prevent XSS when outputting user data in HTML?',
                            'options' => [
                                0 => 'CS: htmlspecialchars()',
                                1 => 'CS: strip_tags()',
                                2 => 'CS: PDO prepared statements',
                                3 => 'CS: Using {{ }} in Blade templates',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 3,
                            ],
                            'explanation' => 'CS: htmlspecialchars() escapes HTML special chars, strip_tags() removes HTML tags, and Blade\'s {{ }} auto-escapes. PDO prepared statements prevent SQL injection, not XSS.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Validating User Input',
                    'content' => 'CS: Validation ensures data meets expected criteria. Check that required fields are present and non-empty. Validate email format with filter_var($input, FILTER_VALIDATE_EMAIL). Check numeric values with is_numeric() or filter_var($input, FILTER_VALIDATE_INT/FILTER_VALIDATE_FLOAT). Validate string length with strlen() or mb_strlen(). Check against allowed values with in_array(). Validate dates with checkdate() or DateTime::createFromFormat(). On failure, store error messages and re-display the form with the user\'s original input. Always validate on the server side — client-side validation is only for user convenience, not security.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Validating Input Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which function validates an email address in PHP?',
                            'options' => [
                                0 => 'CS: is_email()',
                                1 => 'CS: filter_var($email, FILTER_VALIDATE_EMAIL)',
                                2 => 'CS: preg_match("/^[a-z]+$/", $email)',
                                3 => 'CS: strpos($email, "@")',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: filter_var() with FILTER_VALIDATE_EMAIL is the standard way to validate email addresses in PHP.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: File Uploads in Forms',
                    'content' => 'CS: To upload files, the form must have enctype="multipart/form-data". The input type must be "file". PHP stores uploaded files temporarily in the system temp directory. Use $_FILES["fieldname"] to access file details. The "error" key indicates success (UPLOAD_ERR_OK = 0) or failure. Use move_uploaded_file($tmp, $destination) to move the file. Validate the file\'s MIME type with finfo_file() (not $_FILES["type"], which is user-supplied). Limit file size by checking $_FILES["size"]. Never use the user-supplied filename directly — generate a safe name with uniqid() or a UUID.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: File Uploads Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How should you validate a file\'s actual MIME type?',
                            'options' => [
                                0 => 'CS: Check $_FILES["file"]["type"]',
                                1 => 'CS: Use finfo_file()',
                                2 => 'CS: Check the file extension',
                                3 => 'CS: Use getimagesize() for all types',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: finfo_file() reads the actual content to determine MIME type. $_FILES["type"] is user-supplied and cannot be trusted.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        10 => [
            'title' => 'CS: Sessions and Cookies',
            'slug' => 'cs-sessions-cookies',
            'description' => 'CS: Session management, starting and destroying sessions, storing and retrieving session data, cookies vs sessions, and security.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Session Basics',
                    'content' => 'CS: Sessions allow you to persist data across multiple page requests for the same user. PHP manages sessions by creating a unique session ID, storing it in a cookie (by default named PHPSESSID) on the client, and saving the session data on the server. Typical uses include user authentication, shopping carts, and flash messages. Session files are usually stored in the server\'s temporary directory or configured location. Sessions are automatically garbage-collected after a configurable lifetime (default 24 minutes). The session mechanism is transparent — you just use the $_SESSION superglobal after starting the session.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Session Basics Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What must you call before using $_SESSION?',
                            'options' => [
                                0 => 'CS: session_create()',
                                1 => 'CS: session_start()',
                                2 => 'CS: session_init()',
                                3 => 'CS: session_begin()',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: session_start() must be called before reading from or writing to $_SESSION. It creates or resumes the current session.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Starting Sessions and Setting Data',
                    'content' => 'CS: Call session_start() at the beginning of your script (before any output). Once the session is active, you read and write to $_SESSION like a regular array: $_SESSION["user_id"] = 42; $_SESSION["cart"] = ["item1", "item2"];. Session data is serialized automatically. Multiple session variables can hold any type. If you need to modify a session variable that is an array, you can directly manipulate it. If output has already been sent, session_start() will emit a warning. In modern PHP frameworks, session handling is abstracted away by the framework\'s session service.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Session Data Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What types of data can be stored in $_SESSION?',
                            'options' => [
                                0 => 'CS: Only strings',
                                1 => 'CS: Only integers',
                                2 => 'CS: Any serializable type',
                                3 => 'CS: Only boolean values',
                            ],
                            'answer' => 2,
                            'explanation' => 'CS: $_SESSION can store any serializable PHP data type: strings, integers, arrays, objects, etc.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Destroying Sessions',
                    'content' => 'CS: To completely log a user out, you must destroy both the session data and the session cookie. Call $_SESSION = [] to clear all session variables, then session_destroy() to delete the session file on the server. Finally, delete the session cookie by setting it with an expired time: setcookie(session_name(), "", time() - 3600). Failing to clear the cookie means the browser will still send the (now invalid) session ID. For security, always regenerate the session ID after privilege level changes (like login) using session_regenerate_id(true).',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Destroying Sessions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which steps should be taken to fully destroy a session?',
                            'options' => [
                                0 => 'CS: $_SESSION = []',
                                1 => 'CS: session_destroy()',
                                2 => 'CS: Delete the session cookie',
                                3 => 'CS: session_write_close()',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: Clearing data, destroying server-side storage, and deleting the cookie are all necessary. session_write_close() ends writing but does not destroy.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Cookies: Setting, Reading, and Deleting',
                    'content' => 'CS: Cookies are small pieces of data stored on the client\'s browser. Set cookies with setcookie(name, value, expire, path, domain, secure, httponly). The $expire parameter is a Unix timestamp; use time() + 3600 for one hour. To delete a cookie, set its expiration in the past. Read cookies via the $_COOKIE superglobal, but note that setcookie() does not immediately update $_COOKIE — the cookie is sent with the response and returned on the next request. The httponly flag prevents JavaScript access (mitigates XSS), and the secure flag restricts to HTTPS. Path and domain control which URLs the cookie applies to.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Cookies Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you delete a cookie in PHP?',
                            'options' => [
                                0 => 'CS: setcookie("name", "", time() - 3600)',
                                1 => 'CS: deletecookie("name")',
                                2 => 'CS: unset($_COOKIE["name"])',
                                3 => 'CS: setcookie("name", null)',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Set the cookie with an expiration time in the past. The browser will then remove the cookie from storage.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Sessions vs Cookies and Security',
                    'content' => 'CS: Sessions store data on the server; cookies store data on the client. Use sessions for sensitive data (auth status, user roles) because the data never leaves the server. Use cookies for non-sensitive preferences (language, theme) that need to persist across sessions. Security best practices: always use HTTPS, set session cookies as httponly and secure, regenerate session IDs on login, set appropriate session timeouts, validate the session on each request, and store IP addresses or user-agent fingerprints for additional verification (though these can change for legitimate users like mobile network transitions).',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Sessions vs Cookies Security Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Why are sessions more secure than cookies for storing sensitive data?',
                            'options' => [
                                0 => 'CS: Sessions are encrypted',
                                1 => 'CS: Session data stays on the server, only the ID is on the client',
                                2 => 'CS: Sessions use HTTPS by default',
                                3 => 'CS: Sessions cannot be intercepted',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: Session data is stored server-side. The client only holds the session ID, meaning attackers cannot read or modify session data directly.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        11 => [
            'title' => 'CS: File Handling',
            'slug' => 'cs-file-handling',
            'description' => 'CS: Reading and writing files, file information and permissions, directory operations, and error handling with files.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Reading Files in PHP',
                    'content' => 'CS: PHP offers multiple ways to read files. file_get_contents($path) reads an entire file into a string. file($path) reads into an array of lines. fopen() with fgets() provides line-by-line reading for large files. fread($handle, $length) reads a specified number of bytes. The readfile() function reads a file and outputs it directly to the output buffer. For CSV files, fgetcsv() parses each line as CSV fields. For remote files, allow_url_fopen in php.ini enables reading URLs with these functions. Always check if the file exists with file_exists() and is readable with is_readable() before attempting to read.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Reading Files Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which function reads a file into an array of lines?',
                            'options' => [
                                0 => 'CS: file_get_contents()',
                                1 => 'CS: file()',
                                2 => 'CS: fread()',
                                3 => 'CS: readfile()',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: file() reads the file into an array where each element is one line of the file. file_get_contents() returns a single string.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Writing to Files',
                    'content' => 'CS: file_put_contents($path, $data) writes data to a file in one call (overwriting by default, or appending with FILE_APPEND flag). For more control, use fopen() with a mode flag: "w" for write (overwrite), "a" for append, "x" for exclusive create. Then fwrite($handle, $data) writes content, and fclose($handle) closes the file. flock($handle, LOCK_EX) acquires an exclusive lock to prevent concurrent write corruption. LOCK_SH is a shared lock for reading. Always close file handles and check return values — file operations return false on failure.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Writing Files Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What does file_put_contents() with the FILE_APPEND flag do?',
                            'options' => [
                                0 => 'CS: Overwrites the file',
                                1 => 'CS: Adds data to the end of the file',
                                2 => 'CS: Creates a new file only',
                                3 => 'CS: Returns an error if file exists',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: FILE_APPEND prevents overwriting and instead appends the data to the end of the existing file.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: File Information and Permissions',
                    'content' => 'CS: PHP provides functions to inspect files. filesize($path) returns size in bytes. filemtime($path) returns last modification time as a Unix timestamp. filetype($path) returns "file", "dir", or "link". is_file(), is_dir(), is_link() check the type. is_readable() and is_writable() check permissions. fileperms() returns a numeric permission mask; use sprintf("%o", fileperms($path)) to see the octal representation. chmod($path, 0755) changes permissions. chown() and chgrp() change ownership (require appropriate privileges). stat($path) returns detailed file information including all of the above.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: File Permissions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which functions check file permissions before an operation?',
                            'options' => [
                                0 => 'CS: is_readable()',
                                1 => 'CS: is_writable()',
                                2 => 'CS: is_executable()',
                                3 => 'CS: file_exists()',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                            ],
                            'explanation' => 'CS: is_readable(), is_writable(), and is_executable() check the respective permission bits. file_exists() only checks existence.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Directory Operations',
                    'content' => 'CS: Work with directories using mkdir($path, 0777, true) — the third parameter creates nested directories recursively. rmdir() removes an empty directory. scandir($path) lists all files and directories in an array (includes "." and ".." entries). glob($pattern) matches file paths using wildcard patterns: glob("*.txt") finds all text files. DirectoryIterator and RecursiveDirectoryIterator provide object-oriented file iteration. unlink($path) deletes a file. copy($source, $dest) copies a file. rename($old, $new) moves or renames a file or directory. Always check return values for these operations.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Directory Operations Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which function creates a directory, optionally creating intermediate directories?',
                            'options' => [
                                0 => 'CS: mkdir()',
                                1 => 'CS: create_dir()',
                                2 => 'CS: new_dir()',
                                3 => 'CS: dir_create()',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: mkdir($path, $permissions, true) creates a directory. The third parameter ($recursive = true) creates parent directories as needed.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Error Handling with Files',
                    'content' => 'CS: File operations can fail for many reasons: permission denied, disk full, invalid path, or file not found. Always check the return value of file functions — they return false on failure. Use file_exists() before reading. Use @ to suppress warnings (not recommended — use proper error handling instead). Check the global error with error_get_last() after risky operations. For consistent error handling, use exceptions with try/catch by converting warnings to exceptions with set_error_handler(). In modern PHP, consider using the SplFileObject and SplFileInfo classes for object-oriented file handling with clearer error semantics.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: File Error Handling Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What should you check before reading a file to avoid errors?',
                            'options' => [
                                0 => 'CS: file_exists() and is_readable()',
                                1 => 'CS: is_file() and is_writable()',
                                2 => 'CS: filetype() and stat()',
                                3 => 'CS: glob() and filesize()',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Check file_exists() (the file exists) and is_readable() (PHP has permission to read it) before attempting to read.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        12 => [
            'title' => 'CS: Error Handling',
            'slug' => 'cs-error-handling',
            'description' => 'CS: Error types, try/catch/finally, custom exceptions, error reporting levels, logging, and debugging techniques.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Understanding PHP Error Types',
                    'content' => 'CS: PHP has several error types. E_ERROR (1) is a fatal error that stops script execution. E_WARNING (2) is a non-fatal warning — execution continues. E_PARSE (4) is a compile-time syntax error. E_NOTICE (8) is a minor runtime notice (using undefined variables). E_DEPRECATED (8192) warns about deprecated features. E_USER_ERROR and E_USER_WARNING are user-generated via trigger_error(). In PHP 8, most fatal errors are now exceptions (Throwable), making them catchable. The error_reporting() setting controls which levels are reported. In development, show all errors; in production, log them.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Error Types Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: Which error type stops script execution immediately?',
                            'options' => [
                                0 => 'CS: E_WARNING',
                                1 => 'CS: E_NOTICE',
                                2 => 'CS: E_ERROR',
                                3 => 'CS: E_DEPRECATED',
                            ],
                            'answer' => 2,
                            'explanation' => 'CS: E_ERROR is a fatal error. The script stops immediately. E_WARNING, E_NOTICE, and E_DEPRECATED do not halt execution.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Try, Catch, and Finally',
                    'content' => 'CS: PHP\'s exception handling uses try/catch/finally. Code that may throw an exception goes in the try block. catch (ExceptionType $e) handles the exception — you can have multiple catch blocks for different exception types. The finally block always runs after try/catch, regardless of whether an exception occurred — perfect for cleanup operations like closing file handles or database connections. In PHP 8, you can catch multiple exceptions in one block: catch (ValidationException | AuthException $e). Use $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine() for debugging.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Try Catch Finally Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which statements about finally are TRUE?',
                            'options' => [
                                0 => 'CS: It runs after try regardless of success or failure',
                                1 => 'CS: It runs only if an exception is caught',
                                2 => 'CS: It is used for cleanup operations',
                                3 => 'CS: It can contain return statements',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 2,
                                2 => 3,
                            ],
                            'explanation' => 'CS: finally always executes after try/catch, regardless of exceptions. It is ideal for cleanup, and it can contain return statements.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Custom Exception Classes',
                    'content' => 'CS: Extend the base Exception class to create custom exceptions. Custom exceptions can carry additional context. For example: class ValidationException extends Exception { public function __construct(public array $errors) { parent::__construct("Validation failed"); } }. This allows you to catch specific exception types and handle them differently. Custom exceptions improve code organisation and make error handling more expressive. Follow naming conventions: always suffix with "Exception". Keep custom exceptions in a dedicated namespace like App\\Exceptions. When creating them, include meaningful error messages and error codes.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Custom Exceptions Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How do you create a custom exception in PHP?',
                            'options' => [
                                0 => 'CS: Create a new class that extends Exception',
                                1 => 'CS: Use trigger_error()',
                                2 => 'CS: Use set_exception_handler()',
                                3 => 'CS: Define a function named exception()',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: Custom exceptions extend the built-in Exception class (or one of its subclasses).',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Error Reporting Configuration',
                    'content' => 'CS: Error reporting is configured via php.ini or at runtime. error_reporting(E_ALL) sets the level. In development, use E_ALL to catch all issues. In production, use E_ALL & ~E_DEPRECATED & ~E_STRICT. display_errors controls whether errors are shown in output (on in dev, off in prod). log_errors enables logging. error_log specifies the log file path. Use ini_set("display_errors", "0") and ini_set("log_errors", "1") in production scripts. The @ operator suppresses errors for a single expression, but it is slow and discouraged — use proper error handling instead.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Error Reporting Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What should display_errors be set to in production?',
                            'options' => [
                                0 => 'CS: On',
                                1 => 'CS: Off',
                                2 => 'CS: Only for E_ERROR',
                                3 => 'CS: Only for E_WARNING',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: display_errors should be Off in production to prevent sensitive information from leaking to users.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Logging and Debugging Techniques',
                    'content' => 'CS: error_log($message) writes to the PHP error log. For structured logging, use Monolog (the standard PHP logging library) with handlers for files, Slack, or databases. Debugging techniques: var_dump() outputs variable information with types and lengths. print_r() outputs human-readable array/object info. Use exit or die to halt at a specific point. The xdebug extension provides step-through debugging, stack traces, and profiling. In production, never dump debug output — always log to a file. Use assertions (assert()) for development-time invariants. Modern frameworks include a dd() (dump and die) helper for quick debugging.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Logging and Debugging Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which of these are valid PHP debugging techniques?',
                            'options' => [
                                0 => 'CS: var_dump() and exit',
                                1 => 'CS: Using xdebug for step-through debugging',
                                2 => 'CS: error_log() for logging',
                                3 => 'CS: echo statements',
                            ],
                            'answer' => [
                                0 => 0,
                                1 => 1,
                                2 => 2,
                                3 => 3,
                            ],
                            'explanation' => 'CS: All four are valid debugging techniques, though echo and var_dump are quick-and-dirty while xdebug and error_log are more systematic.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
        13 => [
            'title' => 'CS: Introduction to OOP',
            'slug' => 'cs-intro-to-oop',
            'description' => 'CS: Classes and objects, properties and methods, constructors, inheritance, visibility, static members, and interfaces.',
            'steps' => [
                0 => [
                    'type' => 'reading',
                    'title' => 'CS: Classes and Objects',
                    'content' => 'CS: Object-Oriented Programming (OOP) organises code into classes and objects. A class is a blueprint: class User { }. An object is an instance: $user = new User(). Classes contain properties (variables) and methods (functions). The -> operator accesses properties and methods on an instance: $user->name = "Alice"; $user->greet(). PHP supports all major OOP features including inheritance, polymorphism, encapsulation, and abstraction. OOP helps manage complexity by grouping related data and behaviour together, making code more maintainable and reusable.',
                ],
                1 => [
                    'type' => 'quiz',
                    'title' => 'CS: Classes and Objects Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What keyword creates an instance of a class?',
                            'options' => [
                                0 => 'CS: create',
                                1 => 'CS: new',
                                2 => 'CS: instance',
                                3 => 'CS: make',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: The new keyword creates an object instance from a class: $obj = new ClassName().',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                2 => [
                    'type' => 'reading',
                    'title' => 'CS: Properties and Methods',
                    'content' => 'CS: Properties are class variables declared with visibility keywords (public, protected, private). Methods are functions inside a class. Properties can have default values or be initialised in the constructor. PHP 8 constructor property promotion combines declaration and assignment: public function __construct(public string $name) { }. Methods use $this to refer to the current instance: $this->name. Type declarations work on properties since PHP 7.4: public string $name. Typed properties must be initialised before access, either with a default value or in the constructor.',
                ],
                3 => [
                    'type' => 'quiz',
                    'title' => 'CS: Properties and Methods Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: How does a method access its own object\'s properties?',
                            'options' => [
                                0 => 'CS: Using $this->property',
                                1 => 'CS: Using self::$property',
                                2 => 'CS: Using static::$property',
                                3 => 'CS: Using $object->property',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: $this refers to the current object instance and is used to access instance methods and properties from within the class.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                4 => [
                    'type' => 'reading',
                    'title' => 'CS: Constructors',
                    'content' => 'CS: A constructor is a special method (__construct()) that is automatically called when an object is created. It is typically used to initialise properties or perform setup tasks. PHP 8 introduced constructor property promotion, allowing you to define and initialise properties directly in the constructor parameter list. A child class that does not define its own constructor inherits the parent\'s constructor. To call the parent constructor from a child, use parent::__construct(). Destructors (__destruct()) run when an object is destroyed, useful for cleanup like closing connections.',
                ],
                5 => [
                    'type' => 'quiz',
                    'title' => 'CS: Constructors Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What is the name of the constructor method in PHP?',
                            'options' => [
                                0 => 'CS: __construct()',
                                1 => 'CS: __init()',
                                2 => 'CS: __new()',
                                3 => 'CS: constructor()',
                            ],
                            'answer' => 0,
                            'explanation' => 'CS: PHP uses __construct() as the constructor method name. It is called automatically when an object is instantiated.',
                            'difficulty' => 'easy',
                            'topic' => 'php',
                        ],
                    ],
                ],
                6 => [
                    'type' => 'reading',
                    'title' => 'CS: Inheritance',
                    'content' => 'CS: Inheritance allows a child class (subclass) to extend a parent class (superclass), inheriting its properties and methods. Use the extends keyword: class Admin extends User { }. The child can override parent methods by redefining them. To call the parent method, use parent::methodName(). PHP supports single inheritance only — a class can extend only one parent. For sharing behaviour across unrelated classes, use traits (instead of multiple inheritance) and interfaces. The instanceof operator checks if an object is an instance of a particular class or has a parent class in its hierarchy.',
                ],
                7 => [
                    'type' => 'quiz',
                    'title' => 'CS: Inheritance Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'multiple',
                            'question' => 'CS: Which statements about PHP inheritance are TRUE?',
                            'options' => [
                                0 => 'CS: A class can extend multiple classes',
                                1 => 'CS: extends is used for inheritance',
                                2 => 'CS: Child classes inherit parent methods',
                                3 => 'CS: The parent method is called with parent::',
                            ],
                            'answer' => [
                                0 => 1,
                                1 => 2,
                                2 => 3,
                            ],
                            'explanation' => 'CS: PHP supports only single inheritance (one parent). Use extends, child inherits parent methods, and parent:: calls the parent version.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
                8 => [
                    'type' => 'reading',
                    'title' => 'CS: Visibility, Static, and Interfaces',
                    'content' => 'CS: Visibility controls access: public accessible everywhere, protected accessible within the class and subclasses, private accessible only within the defining class. Static members (static property $count and static method::doSomething()) belong to the class, not instances. They are accessed with self:: or static:: in PHP 8+. Interfaces define contracts — a set of method signatures that implementing classes must define. A class can implement multiple interfaces: class User implements Authenticatable, HasRoles { }. Abstract classes provide partial implementation. Traits (use TraitName) reuse code across unrelated classes.',
                ],
                9 => [
                    'type' => 'quiz',
                    'title' => 'CS: Visibility and Interfaces Quiz',
                    'quiz_content' => [
                        0 => [
                            'type' => 'single',
                            'question' => 'CS: What keyword allows a class to fulfill a contract defined by method signatures?',
                            'options' => [
                                0 => 'CS: extends',
                                1 => 'CS: implements',
                                2 => 'CS: use',
                                3 => 'CS: trait',
                            ],
                            'answer' => 1,
                            'explanation' => 'CS: implements is used when a class agrees to implement all methods defined in an interface.',
                            'difficulty' => 'medium',
                            'topic' => 'php',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
