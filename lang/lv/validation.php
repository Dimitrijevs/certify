<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute laukam jābūt akceptētam.',
    'accepted_if' => ':attribute laukam jābūt akceptētam, kad :other ir :value.',
    'active_url' => ':attribute laukam jābūt derīgai URL adresei.',
    'after' => ':attribute laukam jābūt datumam pēc :date.',
    'after_or_equal' => ':attribute laukam jābūt datumam pēc vai vienādam ar :date.',
    'alpha' => ':attribute laukā drīkst būt tikai burti.',
    'alpha_dash' => ':attribute laukā drīkst būt tikai burti, cipari, domuzīmes un pasvītras.',
    'alpha_num' => ':attribute laukā drīkst būt tikai burti un cipari.',
    'array' => ':attribute laukam jābūt masīvam.',
    'ascii' => ':attribute laukā drīkst būt tikai vienbaitu burtciparu simboli un simboli.',
    'before' => ':attribute laukam jābūt datumam pirms :date.',
    'before_or_equal' => ':attribute laukam jābūt datumam pirms vai vienādam ar :date.',
    'between' => [
        'array' => ':attribute laukā jābūt starp :min un :max vienībām.',
        'file' => ':attribute laukam jābūt starp :min un :max kilobaitiem.',
        'numeric' => ':attribute laukam jābūt starp :min un :max.',
        'string' => ':attribute laukam jābūt starp :min un :max rakstzīmēm.',
    ],
    'boolean' => ':attribute laukam jābūt patiesam vai nepatiesam.',
    'can' => ':attribute laukā ir neatļauta vērtība.',
    'confirmed' => ':attribute apstiprinājums nesakrīt.',
    'contains' => ':attribute laukā trūkst nepieciešamās vērtības.',
    'current_password' => 'Parole ir nepareiza.',
    'date' => ':attribute laukam jābūt derīgam datumam.',
    'date_equals' => ':attribute laukam jābūt datumam, kas vienāds ar :date.',
    'date_format' => ':attribute laukam jāsakrīt ar formātu :format.',
    'decimal' => ':attribute laukam jābūt :decimal decimālzīmēm.',
    'declined' => ':attribute laukam jābūt noraidītam.',
    'declined_if' => ':attribute laukam jābūt noraidītam, kad :other ir :value.',
    'different' => ':attribute laukam un :other jābūt dažādiem.',
    'digits' => ':attribute laukam jābūt :digits cipariem.',
    'digits_between' => ':attribute laukam jābūt starp :min un :max cipariem.',
    'dimensions' => ':attribute laukam ir nederīgi attēla izmēri.',
    'distinct' => ':attribute laukam ir dublēta vērtība.',
    'doesnt_end_with' => ':attribute laukam nedrīkst beigties ar kādu no šiem: :values.',
    'doesnt_start_with' => ':attribute laukam nedrīkst sākties ar kādu no šiem: :values.',
    'email' => ':attribute laukam jābūt derīgai e-pasta adresei.',
    'ends_with' => ':attribute laukam jābeidzas ar kādu no šiem: :values.',
    'enum' => 'Izvēlētais :attribute ir nederīgs.',
    'exists' => 'Izvēlētais :attribute ir nederīgs.',
    'extensions' => ':attribute laukam jābūt vienai no šīm paplašinājumiem: :values.',
    'file' => ':attribute laukam jābūt failam.',
    'filled' => ':attribute laukam jābūt aizpildītam.',
    'gt' => [
        'array' => ':attribute laukā jābūt vairāk nekā :value vienībām.',
        'file' => ':attribute laukam jābūt lielākam par :value kilobaitiem.',
        'numeric' => ':attribute laukam jābūt lielākam par :value.',
        'string' => ':attribute laukam jābūt lielākam par :value rakstzīmēm.',
    ],
    'gte' => [
        'array' => ':attribute laukā jābūt :value vienībām vai vairāk.',
        'file' => ':attribute laukam jābūt lielākam vai vienādam ar :value kilobaitiem.',
        'numeric' => ':attribute laukam jābūt lielākam vai vienādam ar :value.',
        'string' => ':attribute laukam jābūt lielākam vai vienādam ar :value rakstzīmēm.',
    ],
    'hex_color' => ':attribute laukam jābūt derīgai heksadecimālai krāsai.',
    'image' => ':attribute laukam jābūt attēlam.',
    'in' => 'Izvēlētais :attribute ir nederīgs.',
    'in_array' => ':attribute laukam jābūt :other.',
    'integer' => ':attribute laukam jābūt veselam skaitlim.',
    'ip' => ':attribute laukam jābūt derīgai IP adresei.',
    'ipv4' => ':attribute laukam jābūt derīgai IPv4 adresei.',
    'ipv6' => ':attribute laukam jābūt derīgai IPv6 adresei.',
    'json' => ':attribute laukam jābūt derīgai JSON virknei.',
    'list' => ':attribute laukam jābūt sarakstam.',
    'lowercase' => ':attribute laukam jābūt mazajiem burtiem.',
    'lt' => [
        'array' => ':attribute laukā jābūt mazāk nekā :value vienībām.',
        'file' => ':attribute laukam jābūt mazākam par :value kilobaitiem.',
        'numeric' => ':attribute laukam jābūt mazākam par :value.',
        'string' => ':attribute laukam jābūt mazākam par :value rakstzīmēm.',
    ],
    'lte' => [
        'array' => ':attribute laukā nedrīkst būt vairāk nekā :value vienību.',
        'file' => ':attribute laukam jābūt mazākam vai vienādam ar :value kilobaitiem.',
        'numeric' => ':attribute laukam jābūt mazākam vai vienādam ar :value.',
        'string' => ':attribute laukam jābūt mazākam vai vienādam ar :value rakstzīmēm.',
    ],
    'mac_address' => ':attribute laukam jābūt derīgai MAC adresei.',
    'max' => [
        'array' => ':attribute laukā nedrīkst būt vairāk nekā :max vienību.',
        'file' => ':attribute laukam jābūt mazākam nekā :max kilobaitiem.',
        'numeric' => ':attribute laukam jābūt mazākam nekā :max.',
        'string' => ':attribute laukam jābūt mazākam nekā :max rakstzīmēm.',
    ],
    'max_digits' => ':attribute laukā nedrīkst būt vairāk nekā :max cipari.',
    'mimes' => ':attribute laukam jābūt failam ar tipu: :values.',
    'mimetypes' => ':attribute laukam jābūt failam ar tipu: :values.',
    'min' => [
        'array' => ':attribute laukā jābūt vismaz :min vienībām.',
        'file' => ':attribute laukam jābūt vismaz :min kilobaitiem.',
        'numeric' => ':attribute laukam jābūt vismaz :min.',
        'string' => ':attribute laukam jābūt vismaz :min rakstzīmēm.',
    ],
    'min_digits' => ':attribute laukā jābūt vismaz :min cipariem.',
    'missing' => ':attribute laukam jābūt trūkstošam.',
    'missing_if' => ':attribute laukam jābūt trūkstošam, kad :other ir :value.',
    'missing_unless' => ':attribute laukam jābūt trūkstošam, ja vien :other nav :value.',
    'missing_with' => ':attribute laukam jābūt trūkstošam, kad :values ir klāt.',
    'missing_with_all' => ':attribute laukam jābūt trūkstošam, kad :values ir klāt.',
    'multiple_of' => ':attribute laukam jābūt :value reizinājumam.',
    'not_in' => 'Izvēlētais :attribute ir nederīgs.',
    'not_regex' => ':attribute lauka formāts ir nederīgs.',
    'numeric' => ':attribute laukam jābūt skaitlim.',
    'min_digits' => ':attribute laukā jābūt vismaz :min cipariem.',
    'missing' => ':attribute laukam ir jābūt trūkumā.',
    'missing_if' => ':attribute laukam ir jābūt trūkumā, kad :other ir :value.',
    'missing_unless' => ':attribute laukam ir jābūt trūkumā, ja vien :other nav :value.',
    'missing_with' => ':attribute laukam ir jābūt trūkumā, kad :values ir klāt.',
    'missing_with_all' => ':attribute laukam ir jābūt trūkumā, kad :values ir klāt.',
    'multiple_of' => ':attribute laukam jābūt :value reizinājumam.',
    'not_in' => 'Izvēlētais :attribute ir nederīgs.',
    'not_regex' => ':attribute lauka formāts ir nederīgs.',
    'numeric' => ':attribute laukam jābūt skaitlim.',
    'password' => [
        'letters' => ':attribute laukam jāiekļauj vismaz viena burts.',
        'mixed' => ':attribute laukam jāiekļauj vismaz viena lielais un viens mazais burts.',
        'numbers' => ':attribute laukam jāiekļauj vismaz viens cipars.',
        'symbols' => ':attribute laukam jāiekļauj vismaz viens simbols.',
        'uncompromised' => 'Norādītais :attribute ir parādījies datu noplūdē. Lūdzu, izvēlieties citu :attribute.',
    ],
    'present' => ':attribute laukam jābūt klāt.',
    'present_if' => ':attribute laukam jābūt klāt, kad :other ir :value.',
    'present_unless' => ':attribute laukam jābūt klāt, ja vien :other nav :value.',
    'present_with' => ':attribute laukam jābūt klāt, kad :values ir klāt.',
    'present_with_all' => ':attribute laukam jābūt klāt, kad :values ir klāt.',
    'prohibited' => ':attribute lauks ir aizliegts.',
    'prohibited_if' => ':attribute lauks ir aizliegts, kad :other ir :value.',
    'prohibited_unless' => ':attribute lauks ir aizliegts, ja vien :other nav :values.',
    'prohibits' => ':attribute lauks aizliedz :other būt klāt.',
    'regex' => ':attribute lauka formāts ir nederīgs.',
    'required' => ':attribute lauks ir obligāts.',
    'required_array_keys' => ':attribute laukam jāietver ieraksti: :values.',
    'required_if' => ':attribute lauks ir obligāts, kad :other ir :value.',
    'required_if_accepted' => ':attribute lauks ir obligāts, kad :other ir pieņemts.',
    'required_if_declined' => ':attribute lauks ir obligāts, kad :other ir noraidīts.',
    'required_unless' => ':attribute lauks ir obligāts, ja vien :other nav :values.',
    'required_with' => ':attribute lauks ir obligāts, kad :values ir klāt.',
    'required_with_all' => ':attribute lauks ir obligāts, kad :values ir klāt.',
    'required_without' => ':attribute lauks ir obligāts, kad :values nav klāt.',
    'required_without_all' => ':attribute lauks ir obligāts, kad neviena no :values nav klāt.',
    'same' => ':attribute laukam jāsakrīt ar :other.',
    'size' => [
        'array' => ':attribute laukā jābūt :size vienībām.',
        'file' => ':attribute laukam jābūt :size kilobaitiem.',
        'numeric' => ':attribute laukam jābūt :size.',
        'string' => ':attribute laukam jābūt :size rakstzīmēm.',
    ],
    'starts_with' => ':attribute laukam jāsākas ar vienu no šādiem: :values.',
    'string' => ':attribute laukam jābūt virknei.',
    'timezone' => ':attribute laukam jābūt derīgai laika zonai.',
    'unique' => ':attribute jau ir aizņemts.',
    'uploaded' => ':attribute neizdevās augšupielādēt.',
    'uppercase' => ':attribute laukam jābūt ar lielajiem burtiem.',
    'url' => ':attribute laukam jābūt derīgai URL adresei.',
    'ulid' => ':attribute laukam jābūt derīgam ULID.',
    'uuid' => ':attribute laukam jābūt derīgam UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],


];
