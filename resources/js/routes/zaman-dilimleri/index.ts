import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import template from './template'
/**
* @see \App\Http\Controllers\ZamanDilimController::exportMethod
* @see app/Http/Controllers/ZamanDilimController.php:86
* @route '/zaman-dilimleri/export'
*/
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/zaman-dilimleri/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::exportMethod
* @see app/Http/Controllers/ZamanDilimController.php:86
* @route '/zaman-dilimleri/export'
*/
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::exportMethod
* @see app/Http/Controllers/ZamanDilimController.php:86
* @route '/zaman-dilimleri/export'
*/
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::exportMethod
* @see app/Http/Controllers/ZamanDilimController.php:86
* @route '/zaman-dilimleri/export'
*/
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::exportMethod
* @see app/Http/Controllers/ZamanDilimController.php:86
* @route '/zaman-dilimleri/export'
*/
const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::exportMethod
* @see app/Http/Controllers/ZamanDilimController.php:86
* @route '/zaman-dilimleri/export'
*/
exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::exportMethod
* @see app/Http/Controllers/ZamanDilimController.php:86
* @route '/zaman-dilimleri/export'
*/
exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

exportMethod.form = exportMethodForm

/**
* @see \App\Http\Controllers\ZamanDilimController::importMethod
* @see app/Http/Controllers/ZamanDilimController.php:91
* @route '/zaman-dilimleri/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/zaman-dilimleri/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::importMethod
* @see app/Http/Controllers/ZamanDilimController.php:91
* @route '/zaman-dilimleri/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::importMethod
* @see app/Http/Controllers/ZamanDilimController.php:91
* @route '/zaman-dilimleri/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::importMethod
* @see app/Http/Controllers/ZamanDilimController.php:91
* @route '/zaman-dilimleri/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::importMethod
* @see app/Http/Controllers/ZamanDilimController.php:91
* @route '/zaman-dilimleri/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\ZamanDilimController::generate
* @see app/Http/Controllers/ZamanDilimController.php:136
* @route '/zaman-dilimleri/generate'
*/
export const generate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

generate.definition = {
    methods: ["post"],
    url: '/zaman-dilimleri/generate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::generate
* @see app/Http/Controllers/ZamanDilimController.php:136
* @route '/zaman-dilimleri/generate'
*/
generate.url = (options?: RouteQueryOptions) => {
    return generate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::generate
* @see app/Http/Controllers/ZamanDilimController.php:136
* @route '/zaman-dilimleri/generate'
*/
generate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::generate
* @see app/Http/Controllers/ZamanDilimController.php:136
* @route '/zaman-dilimleri/generate'
*/
const generateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: generate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::generate
* @see app/Http/Controllers/ZamanDilimController.php:136
* @route '/zaman-dilimleri/generate'
*/
generateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: generate.url(options),
    method: 'post',
})

generate.form = generateForm

/**
* @see \App\Http\Controllers\ZamanDilimController::index
* @see app/Http/Controllers/ZamanDilimController.php:15
* @route '/zaman-dilimleri'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/zaman-dilimleri',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::index
* @see app/Http/Controllers/ZamanDilimController.php:15
* @route '/zaman-dilimleri'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::index
* @see app/Http/Controllers/ZamanDilimController.php:15
* @route '/zaman-dilimleri'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::index
* @see app/Http/Controllers/ZamanDilimController.php:15
* @route '/zaman-dilimleri'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::index
* @see app/Http/Controllers/ZamanDilimController.php:15
* @route '/zaman-dilimleri'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::index
* @see app/Http/Controllers/ZamanDilimController.php:15
* @route '/zaman-dilimleri'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::index
* @see app/Http/Controllers/ZamanDilimController.php:15
* @route '/zaman-dilimleri'
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
* @see \App\Http\Controllers\ZamanDilimController::create
* @see app/Http/Controllers/ZamanDilimController.php:26
* @route '/zaman-dilimleri/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/zaman-dilimleri/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::create
* @see app/Http/Controllers/ZamanDilimController.php:26
* @route '/zaman-dilimleri/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::create
* @see app/Http/Controllers/ZamanDilimController.php:26
* @route '/zaman-dilimleri/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::create
* @see app/Http/Controllers/ZamanDilimController.php:26
* @route '/zaman-dilimleri/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::create
* @see app/Http/Controllers/ZamanDilimController.php:26
* @route '/zaman-dilimleri/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::create
* @see app/Http/Controllers/ZamanDilimController.php:26
* @route '/zaman-dilimleri/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::create
* @see app/Http/Controllers/ZamanDilimController.php:26
* @route '/zaman-dilimleri/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\ZamanDilimController::store
* @see app/Http/Controllers/ZamanDilimController.php:31
* @route '/zaman-dilimleri'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/zaman-dilimleri',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::store
* @see app/Http/Controllers/ZamanDilimController.php:31
* @route '/zaman-dilimleri'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::store
* @see app/Http/Controllers/ZamanDilimController.php:31
* @route '/zaman-dilimleri'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::store
* @see app/Http/Controllers/ZamanDilimController.php:31
* @route '/zaman-dilimleri'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::store
* @see app/Http/Controllers/ZamanDilimController.php:31
* @route '/zaman-dilimleri'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\ZamanDilimController::show
* @see app/Http/Controllers/ZamanDilimController.php:44
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
export const show = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/zaman-dilimleri/{zamanDilimi}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::show
* @see app/Http/Controllers/ZamanDilimController.php:44
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
show.url = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { zamanDilimi: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { zamanDilimi: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            zamanDilimi: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        zamanDilimi: typeof args.zamanDilimi === 'object'
        ? args.zamanDilimi.id
        : args.zamanDilimi,
    }

    return show.definition.url
            .replace('{zamanDilimi}', parsedArgs.zamanDilimi.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::show
* @see app/Http/Controllers/ZamanDilimController.php:44
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
show.get = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::show
* @see app/Http/Controllers/ZamanDilimController.php:44
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
show.head = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::show
* @see app/Http/Controllers/ZamanDilimController.php:44
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
const showForm = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::show
* @see app/Http/Controllers/ZamanDilimController.php:44
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
showForm.get = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::show
* @see app/Http/Controllers/ZamanDilimController.php:44
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
showForm.head = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\ZamanDilimController::edit
* @see app/Http/Controllers/ZamanDilimController.php:51
* @route '/zaman-dilimleri/{zamanDilimi}/edit'
*/
export const edit = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/zaman-dilimleri/{zamanDilimi}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::edit
* @see app/Http/Controllers/ZamanDilimController.php:51
* @route '/zaman-dilimleri/{zamanDilimi}/edit'
*/
edit.url = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { zamanDilimi: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { zamanDilimi: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            zamanDilimi: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        zamanDilimi: typeof args.zamanDilimi === 'object'
        ? args.zamanDilimi.id
        : args.zamanDilimi,
    }

    return edit.definition.url
            .replace('{zamanDilimi}', parsedArgs.zamanDilimi.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::edit
* @see app/Http/Controllers/ZamanDilimController.php:51
* @route '/zaman-dilimleri/{zamanDilimi}/edit'
*/
edit.get = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::edit
* @see app/Http/Controllers/ZamanDilimController.php:51
* @route '/zaman-dilimleri/{zamanDilimi}/edit'
*/
edit.head = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::edit
* @see app/Http/Controllers/ZamanDilimController.php:51
* @route '/zaman-dilimleri/{zamanDilimi}/edit'
*/
const editForm = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::edit
* @see app/Http/Controllers/ZamanDilimController.php:51
* @route '/zaman-dilimleri/{zamanDilimi}/edit'
*/
editForm.get = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::edit
* @see app/Http/Controllers/ZamanDilimController.php:51
* @route '/zaman-dilimleri/{zamanDilimi}/edit'
*/
editForm.head = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\ZamanDilimController::update
* @see app/Http/Controllers/ZamanDilimController.php:58
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
export const update = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/zaman-dilimleri/{zamanDilimi}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::update
* @see app/Http/Controllers/ZamanDilimController.php:58
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
update.url = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { zamanDilimi: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { zamanDilimi: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            zamanDilimi: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        zamanDilimi: typeof args.zamanDilimi === 'object'
        ? args.zamanDilimi.id
        : args.zamanDilimi,
    }

    return update.definition.url
            .replace('{zamanDilimi}', parsedArgs.zamanDilimi.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::update
* @see app/Http/Controllers/ZamanDilimController.php:58
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
update.put = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::update
* @see app/Http/Controllers/ZamanDilimController.php:58
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
update.patch = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::update
* @see app/Http/Controllers/ZamanDilimController.php:58
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
const updateForm = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::update
* @see app/Http/Controllers/ZamanDilimController.php:58
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
updateForm.put = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::update
* @see app/Http/Controllers/ZamanDilimController.php:58
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
updateForm.patch = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\ZamanDilimController::destroy
* @see app/Http/Controllers/ZamanDilimController.php:71
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
export const destroy = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/zaman-dilimleri/{zamanDilimi}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ZamanDilimController::destroy
* @see app/Http/Controllers/ZamanDilimController.php:71
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
destroy.url = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { zamanDilimi: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { zamanDilimi: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            zamanDilimi: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        zamanDilimi: typeof args.zamanDilimi === 'object'
        ? args.zamanDilimi.id
        : args.zamanDilimi,
    }

    return destroy.definition.url
            .replace('{zamanDilimi}', parsedArgs.zamanDilimi.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ZamanDilimController::destroy
* @see app/Http/Controllers/ZamanDilimController.php:71
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
destroy.delete = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::destroy
* @see app/Http/Controllers/ZamanDilimController.php:71
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
const destroyForm = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ZamanDilimController::destroy
* @see app/Http/Controllers/ZamanDilimController.php:71
* @route '/zaman-dilimleri/{zamanDilimi}'
*/
destroyForm.delete = (args: { zamanDilimi: string | number | { id: string | number } } | [zamanDilimi: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const zamanDilimleri = {
    template: Object.assign(template, template),
    export: Object.assign(exportMethod, exportMethod),
    import: Object.assign(importMethod, importMethod),
    generate: Object.assign(generate, generate),
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default zamanDilimleri