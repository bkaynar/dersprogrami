import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:220
* @route '/ogretmen-musaitlik/template-advanced'
*/
export const advanced = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: advanced.url(options),
    method: 'get',
})

advanced.definition = {
    methods: ["get","head"],
    url: '/ogretmen-musaitlik/template-advanced',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:220
* @route '/ogretmen-musaitlik/template-advanced'
*/
advanced.url = (options?: RouteQueryOptions) => {
    return advanced.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:220
* @route '/ogretmen-musaitlik/template-advanced'
*/
advanced.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: advanced.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:220
* @route '/ogretmen-musaitlik/template-advanced'
*/
advanced.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: advanced.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:220
* @route '/ogretmen-musaitlik/template-advanced'
*/
const advancedForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: advanced.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:220
* @route '/ogretmen-musaitlik/template-advanced'
*/
advancedForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: advanced.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OgretmenMusaitlikController::advanced
* @see app/Http/Controllers/OgretmenMusaitlikController.php:220
* @route '/ogretmen-musaitlik/template-advanced'
*/
advancedForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: advanced.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

advanced.form = advancedForm

const template = {
    advanced: Object.assign(advanced, advanced),
}

export default template