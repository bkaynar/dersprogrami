import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import exportMethod from './export'
/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/program-olustur',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::index
* @see app/Http/Controllers/ProgramOlusturController.php:25
* @route '/program-olustur'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
export const generate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

generate.definition = {
    methods: ["post"],
    url: '/program-olustur/generate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
generate.url = (options?: RouteQueryOptions) => {
    return generate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
generate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
const generateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: generate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::generate
* @see app/Http/Controllers/ProgramOlusturController.php:53
* @route '/program-olustur/generate'
*/
generateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: generate.url(options),
    method: 'post',
})

generate.form = generateForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
export const status = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

status.definition = {
    methods: ["get","head"],
    url: '/program-olustur/status',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
status.url = (options?: RouteQueryOptions) => {
    return status.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
status.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
status.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: status.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
const statusForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
statusForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::status
* @see app/Http/Controllers/ProgramOlusturController.php:136
* @route '/program-olustur/status'
*/
statusForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: status.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

status.form = statusForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/program-olustur/show',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::show
* @see app/Http/Controllers/ProgramOlusturController.php:162
* @route '/program-olustur/show'
*/
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
export const destroy = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/program-olustur',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
destroy.url = (options?: RouteQueryOptions) => {
    return destroy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
destroy.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
const destroyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProgramOlusturController::destroy
* @see app/Http/Controllers/ProgramOlusturController.php:214
* @route '/program-olustur'
*/
destroyForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const programOlustur = {
    index: Object.assign(index, index),
    generate: Object.assign(generate, generate),
    status: Object.assign(status, status),
    show: Object.assign(show, show),
    export: Object.assign(exportMethod, exportMethod),
    destroy: Object.assign(destroy, destroy),
}

export default programOlustur