import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DersMekanGeresinimController::downloadTemplate
* @see app/Http/Controllers/DersMekanGeresinimController.php:106
* @route '/ders-mekan-gereksinimleri/template/download'
*/
export const downloadTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

downloadTemplate.definition = {
    methods: ["get","head"],
    url: '/ders-mekan-gereksinimleri/template/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::downloadTemplate
* @see app/Http/Controllers/DersMekanGeresinimController.php:106
* @route '/ders-mekan-gereksinimleri/template/download'
*/
downloadTemplate.url = (options?: RouteQueryOptions) => {
    return downloadTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::downloadTemplate
* @see app/Http/Controllers/DersMekanGeresinimController.php:106
* @route '/ders-mekan-gereksinimleri/template/download'
*/
downloadTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::downloadTemplate
* @see app/Http/Controllers/DersMekanGeresinimController.php:106
* @route '/ders-mekan-gereksinimleri/template/download'
*/
downloadTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadTemplate.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::downloadTemplate
* @see app/Http/Controllers/DersMekanGeresinimController.php:106
* @route '/ders-mekan-gereksinimleri/template/download'
*/
const downloadTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::downloadTemplate
* @see app/Http/Controllers/DersMekanGeresinimController.php:106
* @route '/ders-mekan-gereksinimleri/template/download'
*/
downloadTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::downloadTemplate
* @see app/Http/Controllers/DersMekanGeresinimController.php:106
* @route '/ders-mekan-gereksinimleri/template/download'
*/
downloadTemplateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: downloadTemplate.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

downloadTemplate.form = downloadTemplateForm

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::exportMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:111
* @route '/ders-mekan-gereksinimleri/export'
*/
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/ders-mekan-gereksinimleri/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::exportMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:111
* @route '/ders-mekan-gereksinimleri/export'
*/
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::exportMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:111
* @route '/ders-mekan-gereksinimleri/export'
*/
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::exportMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:111
* @route '/ders-mekan-gereksinimleri/export'
*/
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::exportMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:111
* @route '/ders-mekan-gereksinimleri/export'
*/
const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::exportMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:111
* @route '/ders-mekan-gereksinimleri/export'
*/
exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: exportMethod.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::exportMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:111
* @route '/ders-mekan-gereksinimleri/export'
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
* @see \App\Http\Controllers\DersMekanGeresinimController::importMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:116
* @route '/ders-mekan-gereksinimleri/import'
*/
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/ders-mekan-gereksinimleri/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::importMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:116
* @route '/ders-mekan-gereksinimleri/import'
*/
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::importMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:116
* @route '/ders-mekan-gereksinimleri/import'
*/
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::importMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:116
* @route '/ders-mekan-gereksinimleri/import'
*/
const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::importMethod
* @see app/Http/Controllers/DersMekanGeresinimController.php:116
* @route '/ders-mekan-gereksinimleri/import'
*/
importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: importMethod.url(options),
    method: 'post',
})

importMethod.form = importMethodForm

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::index
* @see app/Http/Controllers/DersMekanGeresinimController.php:16
* @route '/ders-mekan-gereksinimleri'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/ders-mekan-gereksinimleri',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::index
* @see app/Http/Controllers/DersMekanGeresinimController.php:16
* @route '/ders-mekan-gereksinimleri'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::index
* @see app/Http/Controllers/DersMekanGeresinimController.php:16
* @route '/ders-mekan-gereksinimleri'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::index
* @see app/Http/Controllers/DersMekanGeresinimController.php:16
* @route '/ders-mekan-gereksinimleri'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::index
* @see app/Http/Controllers/DersMekanGeresinimController.php:16
* @route '/ders-mekan-gereksinimleri'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::index
* @see app/Http/Controllers/DersMekanGeresinimController.php:16
* @route '/ders-mekan-gereksinimleri'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::index
* @see app/Http/Controllers/DersMekanGeresinimController.php:16
* @route '/ders-mekan-gereksinimleri'
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
* @see \App\Http\Controllers\DersMekanGeresinimController::create
* @see app/Http/Controllers/DersMekanGeresinimController.php:28
* @route '/ders-mekan-gereksinimleri/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/ders-mekan-gereksinimleri/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::create
* @see app/Http/Controllers/DersMekanGeresinimController.php:28
* @route '/ders-mekan-gereksinimleri/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::create
* @see app/Http/Controllers/DersMekanGeresinimController.php:28
* @route '/ders-mekan-gereksinimleri/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::create
* @see app/Http/Controllers/DersMekanGeresinimController.php:28
* @route '/ders-mekan-gereksinimleri/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::create
* @see app/Http/Controllers/DersMekanGeresinimController.php:28
* @route '/ders-mekan-gereksinimleri/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::create
* @see app/Http/Controllers/DersMekanGeresinimController.php:28
* @route '/ders-mekan-gereksinimleri/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::create
* @see app/Http/Controllers/DersMekanGeresinimController.php:28
* @route '/ders-mekan-gereksinimleri/create'
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
* @see \App\Http\Controllers\DersMekanGeresinimController::store
* @see app/Http/Controllers/DersMekanGeresinimController.php:34
* @route '/ders-mekan-gereksinimleri'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/ders-mekan-gereksinimleri',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::store
* @see app/Http/Controllers/DersMekanGeresinimController.php:34
* @route '/ders-mekan-gereksinimleri'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::store
* @see app/Http/Controllers/DersMekanGeresinimController.php:34
* @route '/ders-mekan-gereksinimleri'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::store
* @see app/Http/Controllers/DersMekanGeresinimController.php:34
* @route '/ders-mekan-gereksinimleri'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::store
* @see app/Http/Controllers/DersMekanGeresinimController.php:34
* @route '/ders-mekan-gereksinimleri'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::show
* @see app/Http/Controllers/DersMekanGeresinimController.php:40
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
export const show = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/ders-mekan-gereksinimleri/{ders}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::show
* @see app/Http/Controllers/DersMekanGeresinimController.php:40
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
show.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return show.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::show
* @see app/Http/Controllers/DersMekanGeresinimController.php:40
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
show.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::show
* @see app/Http/Controllers/DersMekanGeresinimController.php:40
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
show.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::show
* @see app/Http/Controllers/DersMekanGeresinimController.php:40
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
const showForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::show
* @see app/Http/Controllers/DersMekanGeresinimController.php:40
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
showForm.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::show
* @see app/Http/Controllers/DersMekanGeresinimController.php:40
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
showForm.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\DersMekanGeresinimController::edit
* @see app/Http/Controllers/DersMekanGeresinimController.php:49
* @route '/ders-mekan-gereksinimleri/{ders}/edit'
*/
export const edit = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/ders-mekan-gereksinimleri/{ders}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::edit
* @see app/Http/Controllers/DersMekanGeresinimController.php:49
* @route '/ders-mekan-gereksinimleri/{ders}/edit'
*/
edit.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return edit.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::edit
* @see app/Http/Controllers/DersMekanGeresinimController.php:49
* @route '/ders-mekan-gereksinimleri/{ders}/edit'
*/
edit.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::edit
* @see app/Http/Controllers/DersMekanGeresinimController.php:49
* @route '/ders-mekan-gereksinimleri/{ders}/edit'
*/
edit.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::edit
* @see app/Http/Controllers/DersMekanGeresinimController.php:49
* @route '/ders-mekan-gereksinimleri/{ders}/edit'
*/
const editForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::edit
* @see app/Http/Controllers/DersMekanGeresinimController.php:49
* @route '/ders-mekan-gereksinimleri/{ders}/edit'
*/
editForm.get = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::edit
* @see app/Http/Controllers/DersMekanGeresinimController.php:49
* @route '/ders-mekan-gereksinimleri/{ders}/edit'
*/
editForm.head = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\DersMekanGeresinimController::update
* @see app/Http/Controllers/DersMekanGeresinimController.php:58
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
export const update = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/ders-mekan-gereksinimleri/{ders}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::update
* @see app/Http/Controllers/DersMekanGeresinimController.php:58
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
update.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return update.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::update
* @see app/Http/Controllers/DersMekanGeresinimController.php:58
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
update.put = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::update
* @see app/Http/Controllers/DersMekanGeresinimController.php:58
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
const updateForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::update
* @see app/Http/Controllers/DersMekanGeresinimController.php:58
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
updateForm.put = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::destroy
* @see app/Http/Controllers/DersMekanGeresinimController.php:96
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
export const destroy = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/ders-mekan-gereksinimleri/{ders}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::destroy
* @see app/Http/Controllers/DersMekanGeresinimController.php:96
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
destroy.url = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ders: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { ders: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            ders: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ders: typeof args.ders === 'object'
        ? args.ders.id
        : args.ders,
    }

    return destroy.definition.url
            .replace('{ders}', parsedArgs.ders.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::destroy
* @see app/Http/Controllers/DersMekanGeresinimController.php:96
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
destroy.delete = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::destroy
* @see app/Http/Controllers/DersMekanGeresinimController.php:96
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
const destroyForm = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DersMekanGeresinimController::destroy
* @see app/Http/Controllers/DersMekanGeresinimController.php:96
* @route '/ders-mekan-gereksinimleri/{ders}'
*/
destroyForm.delete = (args: { ders: number | { id: number } } | [ders: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const DersMekanGeresinimController = { downloadTemplate, exportMethod, importMethod, index, create, store, show, edit, update, destroy, export: exportMethod, import: importMethod }

export default DersMekanGeresinimController