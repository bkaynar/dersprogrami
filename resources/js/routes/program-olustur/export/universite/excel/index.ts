import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:265
* @route '/program-olustur/export/universite/excel/a'
*/
export const a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: a.url(options),
    method: 'get',
})

a.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/universite/excel/a',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:265
* @route '/program-olustur/export/universite/excel/a'
*/
a.url = (options?: RouteQueryOptions) => {
    return a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:265
* @route '/program-olustur/export/universite/excel/a'
*/
a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:265
* @route '/program-olustur/export/universite/excel/a'
*/
a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: a.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:265
* @route '/program-olustur/export/universite/excel/a'
*/
const aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:265
* @route '/program-olustur/export/universite/excel/a'
*/
aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:265
* @route '/program-olustur/export/universite/excel/a'
*/
aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: a.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

a.form = aForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:276
* @route '/program-olustur/export/universite/excel/b'
*/
export const b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: b.url(options),
    method: 'get',
})

b.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/universite/excel/b',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:276
* @route '/program-olustur/export/universite/excel/b'
*/
b.url = (options?: RouteQueryOptions) => {
    return b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:276
* @route '/program-olustur/export/universite/excel/b'
*/
b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:276
* @route '/program-olustur/export/universite/excel/b'
*/
b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: b.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:276
* @route '/program-olustur/export/universite/excel/b'
*/
const bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:276
* @route '/program-olustur/export/universite/excel/b'
*/
bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:276
* @route '/program-olustur/export/universite/excel/b'
*/
bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: b.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

b.form = bForm

const excel = {
    a: Object.assign(a, a),
    b: Object.assign(b, b),
}

export default excel