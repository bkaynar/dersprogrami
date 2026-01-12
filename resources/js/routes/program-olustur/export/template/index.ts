import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
export const a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: a.url(options),
    method: 'get',
})

a.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/template/a',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
a.url = (options?: RouteQueryOptions) => {
    return a.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: a.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
const aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
*/
aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: a.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::a
* @see app/Http/Controllers/ProgramOlusturController.php:326
* @route '/program-olustur/export/template/a'
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
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
export const b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: b.url(options),
    method: 'get',
})

b.definition = {
    methods: ["get","head"],
    url: '/program-olustur/export/template/b',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
b.url = (options?: RouteQueryOptions) => {
    return b.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: b.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
const bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
*/
bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: b.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::b
* @see app/Http/Controllers/ProgramOlusturController.php:337
* @route '/program-olustur/export/template/b'
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

const template = {
    a: Object.assign(a, a),
    b: Object.assign(b, b),
}

export default template