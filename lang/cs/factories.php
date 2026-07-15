<?php

return [
    'course_description' => 'Kurz přátelský pro začátečníky pokrývající základy Laravel PHP frameworku, od routování a控制器 až po Eloquent ORM a Blade šablony.',

    'lesson_description' => 'Naučte se základní koncepty a praktické dovednosti potřebné k tvorbě moderních webových aplikací s Laravelem.',

    'step_reading_default' => "Laravel je PHP framework pro webové aplikace známý svou expresivní syntaxí a robustními nástroji. Používá architektonický vzor Model-View-Controller (MVC), který pomáhá vývojářům organizovat kód do znovupoužitelných komponent. Framework poskytuje bohatý ekosystém nástrojů včetně Eloquent ORM pro práci s databází, Blade pro šablony a Artisan pro příkazový řádek.\n\nJednou z klíčových vlastností Laravelu je service container, který automaticky spravuje závislosti tříd a provádí dependency injection. To usnadňuje výměnu implementací a udržuje kód testovatelný. Framework také obsahuje výkonný query builder, migrační systém pro verzování databázových schémat a vestavěnou podporu pro fronty, cache a event broadcasting.\n\nBezpečnost je v Laravelu zabudována od základů – ochrana CSRF, hashování hesel a šifrované cookies. Framework také obsahuje autorizační systém pomocí policies a gates, který umožňuje snadno řídit přístup k prostředkům aplikace.",

    'step_reading_content' => "Laravel framework poskytuje elegantní syntaxi pro tvorbu webových aplikací. Jeho jádrem je service container pro správu závislostí, který umožňuje bindovat rozhraní na konkrétní implementace. To umožňuje volné propojení komponent a zvyšuje udržovatelnost aplikace.\n\nRoutování v Laravelu je jednoduché a výkonné. Route definujete v souboru `routes/web.php` pro požadavky z prohlížeče a `routes/api.php` pro API endpointy. Route lze seskupovat podle middleware, prefixovat URL segmenty a pojmenovávat pro pohodlné generování URL v celé aplikaci.\n\nControllery organizují logiku zpracování požadavků do tříd. Místo umístění veškeré logiky do route closures můžete vytvořit controller třídy, které sdružují související HTTP akce. Resource controllery poskytují pohodlný způsob implementace RESTful endpointů s jedinou definicí route.\n\nBlade je výkonný šablonovací engine Laravelu, který kompiluje šablony do cacheovaného PHP kódu pro optimální výkon. Nabízí dědičnost šablon pomocí layoutů a sekcí, znovupoužitelné komponenty a direktivy pro běžné PHP řídicí struktury. Blade šablony jsou intuitivní a umožňují psát čistý, čitelný kód bez ztráty funkcionality.\n\nEloquent ORM usnadňuje práci s databází. Každá databázová tabulka má odpovídající Model, který umožňuje dotazovat se na data a vztahy pomocí PHP metod namísto psaní raw SQL. ORM podporuje vztahy jako hasOne, hasMany, belongsTo a many-to-many přes belongsToMany, což usnadňuje práci s komplexními datovými strukturami.",

    'quiz_single_question' => 'Kolik je 2+2?',
    'quiz_single_options' => ['3', '4', '5', '6'],
    'quiz_single_explanation' => '2+2 se rovná 4, takže možnost 2 (4) je správná.',

    'quiz_multiple_question' => 'Které z následujících jsou programovací jazyky?',
    'quiz_multiple_options' => ['Python', 'HTML', 'CSS', 'JavaScript'],
    'quiz_multiple_explanation' => 'Python a JavaScript jsou programovací jazyky; HTML a CSS jsou značkovací/stylovací jazyky.',

    'quiz_text_question' => 'Jaké je hlavní město Francie?',
    'quiz_text_answer' => 'Paříž',
    'quiz_text_explanation' => 'Paříž je hlavním městem Francie od 10. století.',

];
